<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
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

        $simmlarProducts = Product::with('primaryImage', 'category', 'brand')
            ->where('category_id', $product->category_id)
            ->whereNot('id', $product->id)
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();
        // dd($product->toArray());

        return view('mycart.productDetail', compact('product', 'simmlarProducts'));
    }

    public function productList(Request $request)
    {
        $products = Product::with('primaryImage', 'category', 'brand')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->paginate();
            // dd($products->toArray());
        $categories = Category::withCount('products')->whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();
        $brands = Brand::withCount('products')->whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();
        // $brands = Brand::whereHas('products', function ($query) use ($request) {
        //     $query->where('category_id', $request->category_id ?? null);
        // })->get();
        return view('mycart.productList', compact('products', 'categories', 'brands'));
    }

    public function productFillter(Request $request)
    {
        $products = Product::with('primaryImage', 'category', 'brand')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->where('category_id', $request->category_id ?? null)
            ->where('brand_id', $request->brand_id ?? null)
            ->paginate();
        $categories = Category::whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();
        $brands = Brand::whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();
        return response()->json([ 'products' => $products, 'categories' => $categories, 'brands' => $brands ]);
    }
}
