<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Mail\OrderSuccessMail;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Number;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Errors\SignatureVerificationError;
use Twilio\Rest\Client;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = Cart::with('product.primaryImage')
            ->where('user_id', session()->get('user.id'))
            ->whereHas('product', function ($q) {
                $q->where('status', 'active')->where('stock', '>', 0);
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $orderData = $this->calculateGrandTotal();
        $addresses = UserAddress::where('user_id', session()->get('user.id'))->with('country', 'state', 'city')->get();
        $countries = Country::all();

        return view('mycart.checkout', [
            'cartItems' => $cartItems,
            'subTotal' => $orderData['subTotal'],
            'discounted_price' => $orderData['discountAmount'],
            'gstAmount' => $orderData['gstAmount'],
            'grandTotal' => $orderData['grandTotal'],
            'addresses' => $addresses,
            'countries' => $countries
        ]);
    }

    public function orderSuccess($orderId)
    {
        $order = Order::with(['orderItems.product', 'orderAddresses', 'payment'])
            ->where('id', $orderId)
            ->where('user_id', session()->get('user.id'))
            ->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return view('mycart.order-success', compact('order'));
    }

    public function checkout(Request $request)
    {
        $userId = session()->get('user.id');

        $cartItems = Cart::where('user_id', $userId)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $orderData = $this->calculateGrandTotal();

        if ($request->payment_method === 'cod') {

            DB::beginTransaction();
            try {
                $order = $this->order(
                    $request->shipping_address_id,
                    $request->payment_method,
                    $request->notes,
                    $orderData
                );

                Cart::where('user_id', $order->user_id)
                    ->whereHas('product', function ($q) {
                        $q->where('status', 'active')->where('stock', '>', 0);
                    })
                    ->delete();
                session()->put('cart_count', 0);

                $this->sendOrderNotification($order);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'order_no' => $order->order_no,
                    'redirect' => route('order.success', $order->id)
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }

        // Razorpay
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $razorpayOrder = $api->order->create([
            'receipt' => $userId . '_' . time(),
            'amount' => (int) round($orderData['grandTotal'] * 100),
            'currency' => 'INR',
        ]);

        return response()->json([
            'success' => true,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $orderData['grandTotal'],
            'currency' => 'INR',
            'key' => config('services.razorpay.key'),
            'user' => [
                'name' => session()->get('user.name'),
                'email' => session()->get('user.email'),
                'contact' => session()->get('user.phone') ?? ''
            ],
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string'
        ]);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        DB::beginTransaction();

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            $payment = $api->payment->fetch($request->razorpay_payment_id);

            $orderData = $this->calculateGrandTotal();
            $order = $this->order(
                $request->shipping_address_id,
                $request->payment_method,
                $request->notes,
                $orderData
            );

            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $request->razorpay_payment_id,
                'gateway' => 'razorpay',
                'amount' => $payment->amount / 100,
                'currency' => $payment->currency,
                'status' => 'success',
                'response' => json_encode($payment->toArray())
            ]);

            DB::commit();

            Cart::where('user_id', $order->user_id)
                ->whereHas('product', function ($q) {
                    $q->where('status', 'active')->where('stock', '>', 0);
                })
                ->delete();
            session()->put('cart_count', 0);

            $this->sendOrderNotification($order);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'redirect' => route('order.success', $order->id)
            ]);

        } catch (SignatureVerificationError $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed',
                'error' => $e->getMessage()
            ], 400);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function order($address_id, $payment_method, $notes, array $orderData)
    {
        DB::beginTransaction();

        try {
            $cartItems = Cart::with('product')
                ->where('user_id', session()->get('user.id'))
                ->whereHas('product', function ($q) {
                    $q->where('status', 'active')->where('stock', '>', 0);
                })
                ->get();

            $order = Order::create([
                'order_no' => 'ORD-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'user_id' => session()->get('user.id'),
                'subtotal' => $orderData['subTotal'],
                'discounted_price' => $orderData['discountAmount'],
                'tax_amount' => $orderData['gstAmount'],
                'shipping_amount' => $orderData['shippingAmount'],
                'grand_total' => $orderData['grandTotal'],
                'payment_status' => $payment_method == 'cod' ? 'pending' : 'paid',
                'order_status' => 'processing',
                'payment_method' => $payment_method,
                'notes' => $notes,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'vendor_id' => $item->product->vendor_id,
                    'price' => $item->product->final_price,
                    'quantity' => $item->quantity,
                    'total' => $item->product->final_price * $item->quantity,
                ]);

                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
            }

            $address = UserAddress::where('id', $address_id)->first();

            OrderAddress::create([
                'order_id' => $order->id,
                'address_type' => 'shipping',
                'name' => $address->name,
                'phone' => $address->phone,
                'address' => $address->address,
                'country_id' => $address->country_id,
                'state_id' => $address->state_id,
                'city_id' => $address->city_id,
                'pincode' => $address->pincode
            ]);

            DB::commit();
            return $order;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function calculateGrandTotal(): array
    {
        $cartItems = Cart::with('product')
            ->where('user_id', session()->get('user.id'))
            ->whereHas('product', function ($q) {
                $q->where('status', 'active')->where('stock', '>', 0);
            })
            ->get();

        $subTotal = 0;
        $discountAmount = 0;

        foreach ($cartItems as $item) {
            $itemTotal = $item->product->base_price * $item->quantity;
            $itemDiscount = ($itemTotal * $item->product->discount_percentage) / 100;

            $subTotal += $itemTotal;
            $discountAmount += $itemDiscount;
        }

        $gst = (($subTotal - $discountAmount) * 18) / 100;

        return [
            'subTotal' => $subTotal,
            'discountAmount' => $discountAmount,
            'gstAmount' => $gst,
            'shippingAmount' => 0,
            'grandTotal' => ($subTotal - $discountAmount) + $gst,
        ];
    }

    private function sendOrderNotification(Order $order)
    {
        $user = $order->user;

        if ($user && $user->phone) {

            $client = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $client->messages->create(
                '+91' . $user->phone,
                [
                    'from' => config('services.twilio.from'),
                    'body' => "Hi {$user->name}, your order #{$order->order_no} placed. Amount: " .
                        Number::currency($order->grand_total, 'INR') . ". MyCart"
                ]
            );
        }

        if ($user && $user->email) {
            Mail::to($user->email)->send(new OrderSuccessMail($order));
        }
    }
}
