<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        $totalOrders = Order::count();
        $totatUsers = User::whereNot('role', 'admin')->count();
        $totalProducts = Product::count();
        $totalRevenue = Order::sum('grand_total');
        return view('admin.dashboard.index', compact('users', 'totalOrders', 'totatUsers', 'totalProducts', 'totalRevenue'));
    }

    public function salesChart()
    {
        $months = [];
        $sales  = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $months[] = $date->format('M');

            $sales[] = Order::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('grand_total');
        }

        return response()->json([
            'labels' => $months,
            'data'   => $sales
        ]);
    }
}
