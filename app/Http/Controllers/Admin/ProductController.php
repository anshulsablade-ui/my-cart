<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
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
                    $editUrl = route('admin.products.edit', $product->id);
                    $deleteUrl = route('admin.products.destroy', $product->id);
                    return compact('editUrl', 'deleteUrl');
                })
                ->rawColumns(['category', 'brand', 'image', 'actions'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(ProductRequest $request)
    {
        $request->validated();

        $product = Product::create([
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
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductRequest $request, $id)
    {
        // dd($request->all(), $id);
        $product = Product::where('id', $id)->first();

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
            $product = Product::with('images')->find($request->id);

            // delete old images
            if ($product->images) {
                foreach ($product->images as $image) {
                    if (file_exists(public_path('images/products/large/' . $image->image))) {
                        unlink(public_path('images/products/large/' . $image->image));
                    }
                    if (file_exists(public_path('images/products/medium/' . $image->image))) {
                        unlink(public_path('images/products/medium/' . $image->image));
                    }
                    if (file_exists(public_path('images/products/thumb/' . $image->image))) {
                        unlink(public_path('images/products/thumb/' . $image->image));
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
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['errors' => 'Product not found.', 'status' => 'errors']);
        }
        $images = $product->images;
        foreach ($images as $image) {
            if (file_exists(public_path('images/products/large/' . $image->image))) {
                unlink(public_path('images/products/large/' . $image->image));
            }
            if (file_exists(public_path('images/products/medium/' . $image->image))) {
                unlink(public_path('images/products/medium/' . $image->image));
            }
            if (file_exists(public_path('images/products/thumb/' . $image->image))) {
                unlink(public_path('images/products/thumb/' . $image->image));
            }
            $image->delete();
        }
        $product->delete();

        session()->flash('success', 'Product deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
    }
}
