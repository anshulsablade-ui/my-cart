<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // public function AddToCart(Request $request)
    // {
    //     $validator = validator($request->all(), [
    //         'user_id' => 'required|exists:users,id',
    //         'product_id' => 'required|exists:products,id',
    //         'quantity' => 'required|integer|min:1'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
    //     }




    //     return response()->json(['status' => 'success', 'message' => 'Item added to cart']);
    // }


    //

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
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

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
                'user_id' => Auth::id(),
                'session_id' => session()->get('user.id') ? null : $this->getSessionId(),
                'product_id' => $product->id,
                'qty' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart'
        ]);
    }

    /** Cart List */
    public function index()
    {
        $carts = Cart::with('product')
            ->where(function ($q) {
                if (Auth::check()) {
                    $q->where('user_id', Auth::id());
                } else {
                    $q->where('session_id', $this->getSessionId());
                }
            })->get();

        return view('cart.index', compact('carts'));
    }

    /** Update Qty */
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'qty' => 'required|integer|min:1'
        ]);

        Cart::where('id', $request->cart_id)->update([
            'qty' => $request->qty
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cart updated'
        ]);
    }

    /** Remove Item */
    public function remove(Request $request)
    {
        Cart::where('id', $request->cart_id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item removed'
        ]);
    }
}
