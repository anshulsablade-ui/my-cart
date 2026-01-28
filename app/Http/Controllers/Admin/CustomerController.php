<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $uesrs = User::whereNot('role', 'admin')->get();
        if ($request->ajax()) {
            return DataTables::of($uesrs)
                ->addIndexColumn()
                ->addColumn('image', function ($user) {
                    $imageUrl = $user->image ? asset('images/users/' . $user->image) : null;
                    return $imageUrl;
                })
                ->addColumn('date', function ($user) {
                    return $user->created_at->format('d-m-Y');
                })
                ->addColumn('actions', function ($user) {
                    $deleteUrl = route('admin.customer.destroy', $user->id);
                    return compact('deleteUrl');
                })
                ->rawColumns(['image', 'date', 'action'])
                ->make(true);
        }
        return view('admin.customers.index');
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json(['success' => 'error', 'message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['success' => 'success', 'message' => 'User deleted successfully'], 200);
    }
}
