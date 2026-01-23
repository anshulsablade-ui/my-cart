<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
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

        $subtotal = $cartItems->sum(fn ($item) => $item->product->base_price * $item->quantity);
        $discounted_price = $cartItems->sum(fn ($item) => $item->product->discounted_price * $item->quantity);
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

    
    // Place order
    public function placeOrder(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'address_id' => 'required',
            'payment_method' => 'required|in:cod,online',
        ]);

        $cartItems = Cart::with('product')->where('user_id', session()->get('user.id'))->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Cart is empty']);
        }

        DB::beginTransaction();

        try {
            $subTotal = $cartItems->sum(fn ($item) => $item->product->base_price * $item->quantity);
            $discountPercentage = $cartItems->sum(fn ($item) => $item->product->discount_percentage * $item->quantity);
            $discountAmount = ($subTotal * $discountPercentage) / 100;
            $gst = ($subTotal * 18) / 100;
            $shippingAmount = 0;
            $grandTotal = $subTotal - $discountAmount + $gst + $shippingAmount;

            $order = Order::create([
                'order_no' => 'ORD-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'user_id' => session()->get('user.id'),
                'subtotal' => $subTotal,
                'discount_percentage' => $discountPercentage,
                'discounted_price' => $discountAmount,
                'tax_amount' => $gst,
                'shipping_amount' => $shippingAmount,
                'grand_total' => $grandTotal,
                'payment_status' => 'pending',
                'order_status' => 'pending',
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
            }

            Cart::where('user_id', session()->get('user.id'))->delete();

            $address = UserAddress::where('id', $request->address_id)->first();

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

            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'redirect' => route('checkout.success', $order->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    
    // Order success page
    public function success($orderId)
    {
        $order = Order::with('orderItems.product.primaryImage')->where('id', $orderId)->first();
        // dd($order->toArray());
        return view('mycart.orderdetails', compact('order'));
    }

        public function payment(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $amount = 500 * 100; // ₹500 → paise

            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'inr',
                'payment_method' => $request->payment_method,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('checkout.success')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
