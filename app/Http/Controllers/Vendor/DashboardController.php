<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $vendorId = auth()->id();

        // Total orders (unique orders for this vendor)
        $totalOrders = OrderItem::whereHas('product', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->distinct('order_id')
            ->count('order_id');

        // Total revenue for this vendor
        $totalRevenue = OrderItem::whereHas('product', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->sum(DB::raw('price * quantity'));

        // Total products
        $totalProducts = Product::where('vendor_id', $vendorId)->count();

        return view('vendor.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts'
        ));
    }

    public function salesChart()
    {
        $vendorId = auth()->id();

        $data = OrderItem::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(price * quantity) as total')
            )
            ->whereHas('product', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'labels' => $data->pluck('date'),
            'data'   => $data->pluck('total'),
        ]);
    }

    public function orderStatusChart()
    {
        $vendorId = auth()->id();

        $data = OrderItem::select(
                'orders.order_status',
                DB::raw('COUNT(DISTINCT order_items.order_id) as total')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereHas('product', function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->groupBy('orders.order_status')
            ->get();

        return response()->json([
            'labels' => $data->pluck('order_status'),
            'data'   => $data->pluck('total'),
        ]);
    }
}