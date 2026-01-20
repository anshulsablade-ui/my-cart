<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function detail($slug)
    {
        $product = Product::with('images', 'category', 'brand')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!$product) {
            abort(404);
        }

        $simmlarProducts = Product::where('category_id', $product->category_id)
            ->whereNot('id', $product->id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->take(8)
            ->get();
        // dd($product->toArray());

        return view('mycart.productDetail', compact('product', 'simmlarProducts'));
    }
}
