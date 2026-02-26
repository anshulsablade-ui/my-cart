<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $vendorId = auth()->id();

            $orders = Order::query()
                ->whereHas('orderItems.product', function ($q) use ($vendorId) {
                    $q->where('vendor_id', $vendorId);
                })
                ->with([
                    'orderItems' => function ($q) use ($vendorId) {
                        $q->whereHas('product', function ($q2) use ($vendorId) {
                            $q2->where('vendor_id', $vendorId);
                        })->with('product');
                    }
                ])
                ->latest()
                ->get();
            // dd($orders->toArray());

            return DataTables::of($orders)
                ->addColumn('order_no', function ($order) {
                    $data = [
                        'order_no' => $order->order_no,
                        'url' => route('vendor.orders.show', $order->id),
                        'user' => $order->user->name,
                        'email' => $order->user->email,
                    ];
                    return $data;
                })
                ->addColumn('items', function ($order) {
                    return $order->orderItems->sum('quantity');
                })
                ->addColumn('amount', function ($order) {
                    return Number::currency(
                        $order->orderItems->sum(function ($item) {
                            return $item->price * $item->quantity;
                        }) +  
                        ($order->orderItems->sum(function ($item) {
                            return $item->price * $item->quantity;
                        }) * 18) / 100,
                        'INR'
                    );
                })
                ->addColumn('date', function ($order) {
                    return $order->created_at->format('d-m-Y');
                })
                ->rawColumns(['order_no', 'items', 'amount', 'date'])
                ->make(true);
        }

        return view('vendor.orders.index');
    }

    public function show($id)
    {
        $vendorId = auth()->id();

        // ✅ FIRST get the order
        $order = Order::with(['user', 'orderAddresses.state', 'orderAddresses.city'])
            ->findOrFail($id);

        // ✅ THEN get only this vendor items
        $items = $order->orderItems()
            ->whereHas('product', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->with(['product.primaryImage', 'product.brand'])
            ->get();

        // (optional but recommended)
        if ($items->isEmpty()) {
            abort(403);
        }

        $subtotal = 0;
        $discounted_price = 0;

        foreach ($items as $item) {
            $itemTotal = $item->product->base_price * $item->quantity;
            $itemDiscount = ($itemTotal * $item->product->discount_percentage) / 100;

            $subtotal += $itemTotal;
            $discounted_price += $itemDiscount;
        }

        $tax = (($subtotal - $discounted_price) * 18) / 100;
        $grandTotal = $subtotal - $discounted_price + $tax;

        return view('vendor.orders.show', compact(
            'order',
            'items',
            'subtotal',
            'discounted_price',
            'tax',
            'grandTotal'
        ));
    }
}