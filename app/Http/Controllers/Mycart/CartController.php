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
        // dd($request->all());
        // dd(session()->get('user.id'));
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
            session()->put('cart_count', Cart::where(function ($q) {
                if (session()->get('user')) {
                    $q->where('user_id', session()->get('user.id'));
                } else {
                    $q->where('session_id', $this->getSessionId());
                }
            })->count());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart'
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

        $subtotal = $carts->sum(fn ($item) => $item->product->base_price * $item->quantity);
        $discounted_price = $carts->sum(fn ($item) => $item->product->discounted_price * $item->quantity);
        $gstAmount = ($subtotal * 18) / 100;
        $grandTotal = $subtotal + $gstAmount;
        session()->put('cart_count', $carts->count());
        // dd($carts->toArray());

        return view('mycart.cart', compact('carts', 'subtotal', 'discounted_price', 'gstAmount', 'grandTotal'));
    }

    /** Update Qty */
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('id', $request->cart_id)->update([
            'quantity' => $request->quantity
        ]);

        $carts = Cart::with('product')
            ->where(function ($q) {
                if (session()->get('user')) {
                    $q->where('user_id', session()->get('user.id'));
                } else {
                    $q->where('session_id', $this->getSessionId());
                }
            })->get();

        $subtotal = $carts->sum(fn ($item) => $item->product->base_price * $item->quantity);
        $discounted_price = $carts->sum(fn ($item) => $item->product->discounted_price * $item->quantity);
        $gstAmount = ($subtotal * 18) / 100;
        $grandTotal = $subtotal + $gstAmount;

        $cart = Cart::with('product')->where('id', $request->cart_id)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully.',
            'cart' => $cart,
            'subtotal' => Number::currency($subtotal, 'INR'),
            'discounted_price' => Number::currency($discounted_price, 'INR'),
            'gstAmount' => Number::currency($gstAmount, 'INR'),
            'grand_total' => Number::currency($grandTotal , 'INR')
        ]);
    }

    /** Remove Item */
    public function remove(Request $request)
    {
        Cart::where('id', $request->cart_id)->delete();

        session()->put('cart_count', Cart::where(function ($q) {
            if (session()->get('user')) {
                $q->where('user_id', session()->get('user.id'));
            } else {
                $q->where('session_id', $this->getSessionId());
            }
        })->count());
        return response()->json([
            'status' => 'success',
            'message' => 'Item removed'
        ]);
    }
}
