<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getSessionId()
    {
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => Str::uuid()]);
        }
        return session('cart_session_id');
    }

    /** Add to Cart */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $product = Product::where('id', $request->product_id)->first();

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ]);
        }

        $cart = Cart::where('product_id', $product->id)
            ->where(function ($q) {
                if (session()->get('user')) {
                    $q->where('user_id', session()->get('user.id'));
                } else {
                    $q->where('session_id', $this->getSessionId());
                }
            })->first();

        if ($cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product already in cart'
            ]);
        } else {
            Cart::create([
                'user_id' => session()->get('user.id') ? session()->get('user.id') : null,
                'session_id' => session()->get('user.id') ? null : $this->getSessionId(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);

            $cart_count = Cart::where(function ($q) {
                if (session()->get('user')) {
                    $q->where('user_id', session()->get('user.id'));
                } else {
                    $q->where('session_id', $this->getSessionId());
                }
            })->count();
            session()->put('cart_count', $cart_count);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart',
            'cart_count' => $cart_count
        ]);
    }

    /** Cart List */
    public function index()
    {
        $carts = Cart::with('product.primaryImage')
            ->where(function ($q) {
                if (session()->get('user')) {
                    $q->where('user_id', session()->get('user.id'));
                } else {
                    $q->where('session_id', $this->getSessionId());
                }
            })->get();

        $data = $this->calculateGrandTotal();

        $subtotal = Number::currency($data['subTotal'], 'INR');
        $discounted_price = Number::currency($data['discountAmount'], 'INR');
        $gstAmount = Number::currency($data['gstAmount'], 'INR');
        $grandTotal = Number::currency($data['grandTotal'], 'INR');
        session()->put('cart_count', $carts->count());

        return view('mycart.cart', compact('carts', 'subtotal', 'discounted_price', 'gstAmount', 'grandTotal'));
    }

    /** Update Qty */
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = Cart::with('product')->where('id', $request->cart_id)->first();
        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart not found'
            ]);
        }
        $product = $cart->product;
        if ($product->stock < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => $cart->product->name . ' only ' . $product->stock . ' stock available.'
            ]);
        }
        $cart = Cart::where('id', $request->cart_id)->update([
            'quantity' => $request->quantity
        ]);

        $cart = Cart::where('id', $request->cart_id)->first();
        $data = $this->calculateGrandTotal();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully.',
            'cart' => [
                'base_price_total' => Number::currency($cart->product->base_price*$cart->quantity, 'INR'),
                'final_price_total' => Number::currency($cart->product->final_price*$cart->quantity, 'INR')
            ],
            // 'cart' => $cart,
            'subtotal' => Number::currency($data['subTotal'], 'INR'),
            'discounted_price' => Number::currency($data['discountAmount'], 'INR'),
            'gstAmount' => Number::currency($data['gstAmount'], 'INR'),
            'grand_total' => Number::currency($data['grandTotal'], 'INR')
        ]);
    }

    /** Remove Item */
    public function remove(Request $request)
    {
        Cart::where('id', $request->cart_id)->delete();

        $cart_count = Cart::where(function ($q) {
            if (session()->get('user')) {
                $q->where('user_id', session()->get('user.id'));
            } else {
                $q->where('session_id', $this->getSessionId());
            }
        })->count();
        session()->put('cart_count', $cart_count);
        return response()->json([
            'status' => 'success',
            'message' => 'Item removed.',
            'cart_count' => $cart_count
        ]);
    }

    public function removeAll()
    {
        Cart::where(function ($q) {
            if (session()->get('user')) {
                $q->where('user_id', session()->get('user.id'));
            } else {
                $q->where('session_id', $this->getSessionId());
            }
        })->delete();
        session()->put('cart_count', 0);
        return response()->json([
            'status' => 'success',
            'message' => 'Cart cleared.',
            'cart_count' => 0
        ]);
    }

    public function calculateGrandTotal(): array
    {
        $cartItems = Cart::with('product.primaryImage')
            ->where(function ($q) {
                if (session()->get('user')) {
                    $q->where('user_id', session()->get('user.id'));
                } else {
                    $q->where('session_id', $this->getSessionId());
                }
            })->get();

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
}
