<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
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
        $request->validate([
            'address_id' => 'required',
            'payment_method' => 'required'
        ]);

        $cartItems = Cart::with('product.primaryImage')->where('user_id', session()->get('user.id'))->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Cart is empty']);
        }

        DB::beginTransaction();

        try {
            $subTotal = 0;

            foreach ($cartItems as $item) {
                $subTotal += $item->price * $item->qty;
            }

            $gst = round($subTotal * 0.18, 2);
            $discountPercentage = $request->discount_percentage ?? 0;
            $discountAmount = round($subTotal * $discountPercentage / 100, 2);
            $grandTotal = $subTotal + $gst - $discountAmount;

            $order = Order::create([
                'user_id' => session()->get('user.id'),
                'address_id' => $request->address_id,
                'subtotal' => $subTotal,
                'gst_amount' => $gst,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'payment_method' => $request->payment_method,
                'status' => 'pending'
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'qty' => $item->qty,
                    'total' => $item->price * $item->qty
                ]);
            }

            $this->clearCart($request);

            DB::commit();

            return response()->json([
                'status' => true,
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
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }

    // Clear cart after order
    private function clearCart(Request $request)
    {
        Cart::where(function ($q) use ($request) {
            if (session()->get('user')) {
                $q->where('user_id', session()->get('user.id'));
            } else {
                $q->where('session_id', $request->session()->getId());
            }
        })->delete();
    }
}
