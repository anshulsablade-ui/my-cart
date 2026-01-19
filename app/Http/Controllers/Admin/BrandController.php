<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::all();
        if ($request->ajax()) {
            return DataTables::of($brands)
                ->addIndexColumn()
                ->addColumn('logo', function ($brand) {
                    return $brand->logo ? asset('images/brands/' . $brand->logo) : '';
                })
                ->addColumn('total_product', function ($brand) {
                    return $brand->products->count();
                })
                ->addColumn('actions', function ($brand) {
                    $editUrl = route('admin.brands.edit', $brand->id);
                    $deleteUrl = route('admin.brands.destroy', $brand->id);
                    return compact('editUrl', 'deleteUrl');
                })
                ->rawColumns(['logo', 'total_product', 'actions'])
                ->make(true);
        }
        return view('admin.brands.index');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'name' => 'required|string|unique:brands,name|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()], 422);
        }

        $brand =Brand::create($request->only('name', 'status'));

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = $request->name . '_'. time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/brands'), $imageName);
            $brand->update(['logo' => $imageName]);
        }

        session()->flash('success', 'Brand created successfully');
        return response()->json(['status' => 'success', 'message' => 'Brand created successfully'], 201);
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $brand], 200);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()], 422);
        }

        $brand->update($request->only('name', 'status'));

        if ($request->hasFile('logo')) {
            // old image delete
            if ($brand->logo && file_exists(public_path('images/brands/' . $brand->logo))) {
                unlink(public_path('images/brands/' . $brand->logo));
            }

            // upload new image
            $image = $request->file('logo');
            $imageName = $request->name . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/brands'), $imageName);
            $brand->update(['logo' => $imageName]);
        }

        session()->flash('success', 'Brand updated successfully');
        return response()->json(['status' => 'success', 'message' => 'Brand updated successfully'], 200);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // old image delete
        if ($brand->logo && file_exists(public_path('images/brands/' . $brand->logo))) {
            unlink(public_path('images/brands/' . $brand->logo));
        }

        $brand->delete();

        session()->flash('success', 'Brand deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Brand deleted successfully'], 200);
    }
}
