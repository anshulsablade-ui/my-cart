<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('vendor_id', auth()->id())->get();
        if ($request->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('category', function ($product) {
                    return $product->category ? $product->category->name : 'N/A';
                })
                ->addColumn('brand', function ($product) {
                    return $product->brand ? $product->brand->name : 'N/A';
                })
                ->addColumn('image', function ($product) {
                    $imageUrl = $product->images->first() ? asset('images/products/thumb/' . $product->images->first()->image) : asset('images/products/no-image.png');
                    return compact('imageUrl');
                })
                ->addColumn('actions', function ($product) {
                    $editUrl = route('vendor.products.edit', $product->id);
                    $deleteUrl = route('vendor.products.destroy', $product->id);
                    return compact('editUrl', 'deleteUrl');
                })
                ->rawColumns(['category', 'brand', 'image', 'actions'])
                ->make(true);
        }

        return view('vendor.products.index');
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('vendor.products.create', compact('categories', 'brands'));
    }

    public function store(ProductRequest $request)
    {
        $request->validated();

        $product = Product::create([
            'category_id' => $request->category,
            'brand_id' => $request->brand,
            'vendor_id' => auth()->id(),
            'name' => $request->name,
            'base_price' => $request->base_price,
            'discount_percentage' => $request->discount_percentage,
            'discounted_price' => $request->discounted_price,
            'final_price' => $request->final_price,
            'stock' => $request->stock,
            'description' => $request->description,
            'status' => $request->status
        ]);

        if ($request->hasFile('images')) {

            $isPrimary = true; // first image will be primary

            foreach ($request->file('images') as $image) {

                $fileName = Str::uuid() . '.png';

                $basePath = public_path('images/products/');
                $thumbPath = $basePath . 'thumb/';
                $mediumPath = $basePath . 'medium/';
                $largePath = $basePath . 'large/';

                foreach ([$thumbPath, $mediumPath, $largePath] as $path) {
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                }

                Image::read($image)->resize(150, 150)->toPng()->save($thumbPath . $fileName);
                Image::read($image)->resize(300, 300)->toPng()->save($mediumPath . $fileName);
                Image::read($image)->resize(800, 800)->toPng()->save($largePath . $fileName);

                $product->images()->create([
                    'image' => $fileName,
                    'is_primary' => $isPrimary ? 1 : 0,
                ]);

                $isPrimary = false; // only first image
            }
        }

        session()->flash('success', 'Product created successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully'
        ], 201);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        Gate::authorize('view', $product);
        $categories = Category::all();
        $brands = Brand::all();
        return view('vendor.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::where('id', $id)->first();
        Gate::authorize('update', $product);
        $request->validated();

        $product->update([
            'category_id' => $request->category,
            'brand_id' => $request->brand,
            'name' => $request->name,
            'base_price' => $request->base_price,
            'discount_percentage' => $request->discount_percentage,
            'discounted_price' => $request->discounted_price,
            'final_price' => $request->final_price,
            'stock' => $request->stock,
            'description' => $request->description,
            'status' => $request->status
        ]);

        if ($request->hasFile('images')) {
            $product = Product::with('images')->where('vendor_id', auth()->id())->find($request->id);

            // delete old images
            if ($product->images) {
                
                foreach ($product->images as $image) {

                    $paths = [
                        public_path('images/products/large/' . $image->image),
                        public_path('images/products/medium/' . $image->image),
                        public_path('images/products/thumb/' . $image->image),
                    ];

                    foreach ($paths as $path) {
                        if (is_file($path)) {
                            @unlink($path);
                        }
                    }

                    $image->delete();
                }
            }

            $isPrimary = true;

            // insert new images
            foreach ($request->file('images') as $image) {

                // Resize and save different sizes
                $fileName = Str::uuid() . '.png';

                $basePath = public_path('images/products/');
                $thumbPath = $basePath . 'thumb/';
                $mediumPath = $basePath . 'medium/';
                $largePath = $basePath . 'large/';

                foreach ([$thumbPath, $mediumPath, $largePath] as $path) {
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                }
                Image::read($image)->resize(150, 150)->toPng()->save($thumbPath . $fileName);
                Image::read($image)->resize(300, 300)->toPng()->save($mediumPath . $fileName);
                Image::read($image)->resize(800, 800)->toPng()->save($largePath . $fileName);
                $product->images()->create(['image' => $fileName, 'is_primary' => $isPrimary ? 1 : 0]);
                $isPrimary = false;
            }
        }

        session()->flash('success', 'Product updated successfully');
        return response()->json(['status' => 'success', 'message' => 'Product updated successfully'], 200);
    }

    public function destroy($id)
    {
        $product = Product::with('images')->where('vendor_id', auth()->id())->find($id);
        Gate::authorize('delete', $product);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found.'], 404);
        }

        \DB::transaction(function () use ($product) {

            // Delete images (files + db)
            foreach ($product->images as $image) {

                $paths = [
                    public_path('images/products/large/' . $image->image),
                    public_path('images/products/medium/' . $image->image),
                    public_path('images/products/thumb/' . $image->image),
                ];

                foreach ($paths as $path) {
                    if (is_file($path)) {
                        @unlink($path);
                    }
                }

                $image->delete();
            }

            // Remove related records
            Wishlist::where('product_id', $product->id)->delete();
            Cart::where('product_id', $product->id)->delete();

            // Finally delete product
            $product->delete();
        });

        session()->flash('success', 'Product deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
    }

}
