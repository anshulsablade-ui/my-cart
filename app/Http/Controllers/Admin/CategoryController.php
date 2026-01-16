<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        if ($request->ajax()) {
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('total_product', function ($category) {
                    return $category->products->count();
                })
                ->addColumn('status', function ($category) {
                    return $category->status == 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('actions', function ($category) {
                    $editUrl = route('admin.categories.edit', $category->id);
                    $deleteUrl = route('admin.categories.destroy', $category->id);
                    return '<a href="' . $editUrl . '" class="btn btn-sm btn-primary edit">Edit</a>
                            <a href="' . $deleteUrl . '" class="btn btn-sm btn-danger delete">Delete</a>';
                })
                ->rawColumns(['total_product', 'status', 'actions'])
                ->make(true);
        }
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'sub_category' => 'nullable|exists:categories,id',
            'name' => 'required|string|unique:categories,name|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()], 422);
        }

        Category::create([
            'name' => $request->name,
            'status' => $request->status,
            'parent_id' => $request->sub_category ?? null,
        ]);

        session()->flash('success', 'Category created successfully');
        return response()->json(['status' => 'success', 'message' => 'Category created successfully'], 201);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $category], 200);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all(), $id);
        $category = Category::where('id', $id)->first();

        $validated = Validator::make($request->all(), [
            'sub_category' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()], 422);
        }

        $category->update([
            'name' => $request->name,
            'status' => $request->status,
            'parent_id' => $request->sub_category ?? null,
        ]);

        session()->flash('success', 'Category updated successfully');
        return response()->json(['status' => 'success', 'message' => 'Category updated successfully'], 200);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->children()->count() > 0) {
            $category->children()->delete();
            return response()->json(['error' => 'Cannot delete category with sub-categories'], 400);
        }
        $category->delete();

        session()->flash('success', 'Category deleted successfully');
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
