<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::all();
        if ($request->ajax()) {
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('orderAndUser', function ($order) {
                    $data = [
                        'order_no' => $order->order_no,
                        'url' => route('admin.order.show', $order->id),
                        'user' => $order->user->name,
                        'email' => $order->user->email,
                    ];
                    return $data;
                })
                ->addColumn('items', function ($order) {
                    return $order->orderItems->count();
                })
                ->addColumn('date', function ($order) {
                    return $order->created_at->format('d-m-Y');
                })
                ->addColumn('actions', function ($order) {
                    $editUrl = route('admin.order.update', $order->id);
                    $deleteUrl = route('admin.order.destroy', $order->id);
                    return compact('editUrl', 'deleteUrl');
                })
                ->rawColumns(['orderAndUser', 'items', 'date', 'actions'])
                ->make(true);
        }

        return view('admin.orders.index');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,failed',
            'order_status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
        $order = Order::where('id', $id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $order->update([
            'payment_status' => $request->payment_status,
            'order_status' => $request->order_status
        ]);
        
        return response()->json([ 'status' => 'success', 'message' => 'Order status updated successfully']);
    }
    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'orderAddresses', 'user'])->find($id);
        // dd($order->toArray());
        return view('admin.orders.show', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::where('id', $id)->first();
    
        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Order not found');
        }
    
        $orderItems = $order->orderItems;
        foreach ($orderItems as $orderItem) {
            $orderItem->delete();
        }
        $order->orderAddresses->delete();
        $order->payment->delete();

        $order->delete();
        session()->flash('success', 'Order deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Order deleted successfully']);
    }
}
