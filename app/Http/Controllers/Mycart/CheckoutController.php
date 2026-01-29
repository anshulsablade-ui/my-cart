<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Show checkout page
    public function index(Request $request)
    {
        $cartItems = Cart::with('product.primaryImage')->where('user_id', session()->get('user.id'))->get();
        // dd($cartItems->toArray());
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->base_price * $item->quantity);
        $discounted_price = $cartItems->sum(fn($item) => $item->product->discounted_price * $item->quantity);
        $gstAmount = ($subtotal * 18) / 100;
        $grandTotal = $subtotal + $gstAmount;

        $addresses = UserAddress::where('user_id', session()->get('user.id'))->with('country', 'state', 'city')->get();
        $countries = Country::all();

        return view('mycart.checkout', compact(
            'cartItems',
            'subtotal',
            'discounted_price',
            'gstAmount',
            'grandTotal',
            'addresses',
            'countries'
        ));
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|in:cod,razorpay',
            'notes' => 'nullable|string',
        ]);

        $userId = session()->get('user.id');

        try {
            DB::beginTransaction();

            // Get cart items
            $cartItems = Cart::with('product')->where('user_id', session()->get('user.id'))->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            $subTotal = 0;
            $discountAmount = 0;

            foreach ($cartItems as $item) {
                $price = $item->product->base_price;
                $qty = $item->quantity;

                $itemTotal = $price * $qty;
                $itemDiscount = ($itemTotal * $item->product->discount_percentage) / 100;

                $subTotal += $itemTotal;
                $discountAmount += $itemDiscount;
            }

            $gst = (($subTotal - $discountAmount) * 18) / 100;
            $shippingAmount = 0;

            $grandTotal = ($subTotal - $discountAmount) + $gst + $shippingAmount;


            $order = Order::create([
                'order_no' => 'ORD-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'user_id' => session()->get('user.id'),
                'subtotal' => $subTotal,
                'discounted_price' => $discountAmount,
                'tax_amount' => $gst,
                'shipping_amount' => $shippingAmount,
                'grand_total' => $grandTotal,
                'payment_status' => 'pending',
                'order_status' => 'processing',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->final_price,
                    'quantity' => $item->quantity,
                    'total' => $item->product->price * $item->quantity
                ]);
                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', session()->get('user.id'))->delete();
            session()->put('cart_count', 0);

            $address = UserAddress::where('id', $request->shipping_address_id)->first();

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

            // Handle payment method
            if ($request->payment_method === 'cod') {
                // For COD, mark as pending and complete order
                Cart::where('user_id', $userId)->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $order->id,
                    'order_no' => $order->order_no,
                    'redirect' => route('order.success', $order->id)
                ]);
            } else {
                // For Razorpay, create payment order
                $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

                $razorpayOrder = $api->order->create([
                    'receipt' => $order->order_no,
                    'amount' => (int) round($grandTotal * 100),
                    'currency' => 'INR',
                    'notes' => [
                        'order_id' => $order->id,
                        'user_id' => $userId
                    ]
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'amount' => $grandTotal,
                    'currency' => 'INR',
                    'order_id' => $order->id,
                    'order_no' => $order->order_no,
                    'key' => config('services.razorpay.key'),
                    'user' => [
                        'name' => session()->get('user.name'),
                        'email' => session()->get('user.email'),
                        'contact' => session()->get('user.phone') ?? ''
                    ],
                    'redirect' => route('order.success', $order->id)
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
            'razorpay_signature' => 'required',
            'order_id' => 'required|exists:orders,id'
        ]);

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            // Verify signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Fetch payment details
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            DB::beginTransaction();

            $order = Order::find($request->order_id);

            // Update order payment status
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing'
            ]);

            // Save payment details
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $request->razorpay_payment_id,
                'gateway' => 'razorpay',
                'amount' => $payment->amount / 100,
                'currency' => $payment->currency,
                'status' => 'success',
                'response' => json_encode($payment->toArray())
            ]);

            // Clear cart
            Cart::where('user_id', $order->user_id)->delete();
            session()->put('cart_count', 0);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'redirect' => route('order.success', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log failed payment
            if ($request->order_id) {
                $order = Order::find($request->order_id);
                $order->update(['payment_status' => 'failed']);

                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $request->razorpay_payment_id ?? null,
                    'gateway' => 'razorpay',
                    'amount' => $order->grand_total,
                    'currency' => 'INR',
                    'status' => 'failed',
                    'response' => json_encode(['error' => $e->getMessage()])
                ]);
            }

            return response()->json(['error' => 'Payment verification failed'], 400);
        }
    }

    public function orderSuccess($orderId)
    {
        $order = Order::with(['orderItems.product', 'orderAddresses', 'payment'])
            ->where('id', $orderId)
            ->where('user_id', session()->get('user.id'))
            ->first();
        // dd($order->toArray());
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return view('mycart.order-success', compact('order'));
    }

    public function createOrder(Request $request)
    {
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $order = Order::where('id', $request->order_id)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $razorpayOrder = $api->order->create([
            'receipt' => 'order_' . time(),
            'amount' => (int) round($request->amount * 100),
            'currency' => 'INR',
            'notes' => [
                'order_id' => $request->order_id,
                'user_id' => session()->get('user.id')
            ]
        ]);

        return response()->json([
            'success' => true,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $request->amount,
            'currency' => 'INR',
            'order_id' => $order->id,
            'order_no' => $order->order_no,
            'key' => config('services.razorpay.key'),
            'user' => [
                'name' => session()->get('user.name'),
                'email' => session()->get('user.email'),
                'contact' => session()->get('user.phone') ?? ''
            ],
            'redirect' => route('order.success', $order->id)
        ]);
    }
}
