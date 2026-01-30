<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
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
        // dd($products->toArray());
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
        $reviews = ProductReview::with(['user:id,name'])->where('product_id', $product->id)->paginate(5);
        // dd($product->toArray());
        $orderCompleted = false;
        $reviewhas = false;

        if ($product) {
            $orderCompleted = Order::where('user_id', session()->get('user.id'))
                ->where('order_status', 'completed')
                ->whereHas('orderItems', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();
            $reviewhas = ProductReview::where('product_id', $product->id)->where('user_id', session()->get('user.id'))->exists();
        }
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

        return view('mycart.productDetail', compact('product', 'reviews', 'simmlarProducts', 'orderCompleted', 'reviewhas'));
    }

    public function productList(Request $request)
    {
        $products = Product::with('primaryImage')
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

    // public function productFilter(Request $request)
    // {
    //     // dd($request->all());
    //     $products = Product::with('primaryImage')->where('status', 'active')->where('stock', '>', 0);

    //     // search filter
    //     $search = $request->search;
    //     if ($search) {
    //         $products->where(function ($query) use ($search) {
    //             $query->where('name', 'like', '%' . $search . '%');
    //         })->orWhereHas('category', function ($query) use ($search) {
    //             $query->where('name', 'like', '%' . $search . '%');
    //         })->orWhereHas('brand', function ($query) use ($search) {
    //             $query->where('name', 'like', '%' . $search . '%');
    //         });
    //     }

    //     // category filter
    //     if ($request->category) {
    //         $products->where('category_id', $request->category);
    //     }

    //     // brand filter
    //     if ($request->brands) {
    //         $brands = is_array($request->brands)
    //             ? $request->brands
    //             : explode(',', $request->brands);

    //         $products->whereIn('brand_id', $brands);
    //     }

    //     // price filter
    //     if ($request->min_price && $request->max_price) {
    //         $products->whereBetween('final_price', [$request->min_price, $request->max_price]);
    //     }

    //     // sort filter
    //     switch ($request->sort) {
    //         case 'price_asc':
    //             $products->orderBy('final_price', 'asc');
    //             break;

    //         case 'price_desc':
    //             $products->orderBy('final_price', 'desc');
    //             break;

    //         case 'newest':
    //             $products->orderBy('created_at', 'desc');
    //             break;

    //         default:
    //             $products->get();
    //     }
    //     $products = $products->paginate();


    //     return view('mycart.component.product', compact('products'))->render();
    // }


    public function productFilter(Request $request)
    {
        $products = Product::with('primaryImage')
            ->where('status', 'active')
            ->where('stock', '>', 0);

        // search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $products->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('brand', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // category filter
        if ($request->filled('category')) {
            $products->where('category_id', $request->category);
        }

        // brand filter
        if ($request->filled('brands')) {

            $brands = is_array($request->brands)
                ? $request->brands
                : explode(',', $request->brands);

            $products->whereIn('brand_id', $brands);
        }

        // price filter
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $products->whereBetween('final_price', [
                $request->min_price,
                $request->max_price
            ]);
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

            default:
                $products->orderBy('id', 'desc');
                break;
        }

        $products = $products->paginate(12)->withQueryString();

        return view('mycart.component.product', compact('products'))->render();
    }

}
