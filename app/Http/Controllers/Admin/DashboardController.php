<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        $totalOrders = Order::count();
        $totatUsers = User::whereNot('role', 'admin')->count();
        $totalProducts = Product::count();
        $totalRevenue = Order::where('order_status', 'completed')->sum('grand_total');
        return view('admin.dashboard.index', compact('users', 'totalOrders', 'totatUsers', 'totalProducts', 'totalRevenue'));
    }

    public function orderStatusChart()
    {
        $statusData = Order::select(
                'order_status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('order_status')
            ->get();
    
        $labels = $statusData->pluck('order_status');
        $totals = $statusData->pluck('total');
    
        return response()->json([ 'labels' => $labels, 'data' => $totals ]);
    }

    public function salesChart()
    {
        $months = [];
        $sales  = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $months[] = $date->format('M');

            $sales[] = Order::where('order_status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('grand_total');
        }

        return response()->json([ 'labels' => $months, 'data' => $sales ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'image' => $user->image ? asset('images/users/' . $user->image) : null,
                ];
            });

        $products = Product::with('primaryImage')->where('name', 'like', "%$query%")
        ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'url' => route('product.show', $product->slug),
                    'base_price' => Number::currency($product->base_price, 'INR'),
                    'final_price' => Number::currency($product->final_price, 'INR'),
                    'discount_percentage' => $product->discount_percentage,
                    'image' => asset('images/products/thumb/' . ($product->primaryImage ? $product->primaryImage->image : 'no-image.png')),
                ];
            });

        return response()->json([
            'users' => $users,
            'products' => $products,
        ]);
    }
}
