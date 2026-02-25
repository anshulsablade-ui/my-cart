<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = User::where('role', 'vendor')->get();
        if ($request->ajax()) {
            return DataTables::of($vendors)
                ->addIndexColumn()
                ->addColumn('total_product', function ($vendor) {
                    return $vendor->products->count() == 0 ? 'No Product' : $vendor->products->count() . ' Products';
                })
                ->addColumn('actions', function ($vendor) {
                    $deleteUrl = route('admin.vendors.destroy', $vendor->id);
                    return compact('deleteUrl');
                })
                ->rawColumns(['category', 'brand', 'image', 'actions'])
                ->make(true);
        }
        return view('admin.vendors.index');
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors',
            'password' => 'required|string|min:8',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Vendor created successfully']);
    }

    public function destroy($id)
    {
        $vendor = User::where('id', $id)->where('role','vendor')->first();
        if (!$vendor) {
            return response()->json(['status' => 'error', 'message' => 'Vendor not found'], 404);
        }
        
        $vendor->delete();
        return response()->json(['status' => 'success', 'message' => 'Vendor deleted successfully']);
    }
}
