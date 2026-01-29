<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $products = Product::with('primaryImage', 'category', 'brand')
            ->where('status', 'active')
            ->where('stock', '>', 0);

        $search = $request->search;
        if ($search) {
            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('brand', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        $products = $products->paginate();

        $categories = Category::withCount('products')->whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();

        $brands = Brand::withCount('products')->whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();

        return view('mycart.productList', compact('products', 'categories', 'brands', 'search'));
    }
    public function detail($slug)
    {
        $product = Product::with('images', 'category', 'brand')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        
        $orderCompleted = Order::where('user_id', session()->get('user.id'))
            ->where('order_status', 'completed')
            ->whereHas('orderItems', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();
        if (!$product) {
            abort(404);
        }

        $simmlarProducts = Product::with('primaryImage')
            ->whereNot('id', $product->id)
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('mycart.productDetail', compact('product', 'simmlarProducts', 'orderCompleted'));
    }

    public function productList(Request $request)
    {
        $products = Product::with('primaryImage', 'category', 'brand')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->paginate();

        $categories = Category::withCount('products')->whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();

        $brands = Brand::withCount('products')->whereHas('products', function ($query) {
            $query->where('stock', '>', 0);
        })->get();

        return view('mycart.productList', compact('products', 'categories', 'brands'));
    }

    public function productFilter(Request $request)
    {
        // dd($request->all());
        $products = Product::with('primaryImage')->where('status', 'active')->where('stock', '>', 0);

        // search filter
        $search = $request->search;
        if ($search) {
            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('brand', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        // category filter
        if ($request->category) {
            $products->where('category_id', $request->category);
        }

        // brand filter
        if ($request->brands) {
            $brands = is_array($request->brands)
                ? $request->brands
                : explode(',', $request->brands);

            $products->whereIn('brand_id', $brands);
        }

        // price filter
        if ($request->min_price && $request->max_price) {
            $products->whereBetween('final_price', [$request->min_price, $request->max_price]);
        }

        // sort filter
        switch ($request->sort) {
            case 'price_asc':
                $products->orderBy('final_price', 'asc');
                break;

            case 'price_desc':
                $products->orderBy('final_price', 'desc');
                break;

            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('views', 'desc'); // or sales_count
                break;

            default:
                $products->latest();
        }
        $products = $products->paginate();

        // $categories = Category::whereHas('products', function ($query) {
        //     $query->where('stock', '>', 0);
        // })->get();

        // $brands = Brand::whereHas('products', function ($query) {
        //     $query->where('stock', '>', 0);
        // })->get();

        return view('mycart.component.product', compact('products'))->render();
        // return response()->json(['products' => $products, 'categories' => $categories, 'brands' => $brands]);
    }

}
