<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product.primaryImage')
            ->where('user_id', session()->get('user.id'))
            ->latest()
            ->get();

        return view('mycart.account.wishlist', compact('wishlists'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $exists = Wishlist::where('user_id', session()->get('user.id'))
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'info',
                'message' => 'Product already in wishlist'
            ]);
        }

        Wishlist::create([
            'user_id'    => session()->get('user.id'),
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to wishlist'
        ]);
    }

    public function remove(Request $request)
    {
        Wishlist::where('user_id', session()->get('user.id'))
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Wishlist item removed.'
        ]);
    }
}
