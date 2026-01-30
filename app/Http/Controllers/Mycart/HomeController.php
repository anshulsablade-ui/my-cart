<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('primaryImage')
            ->where('status', 'active')
            ->limit(8)
            ->get();
        $slideres = Product::with('primaryImage')->whereIn('id', [7, 101, 102, 9])->where('status', 'active')->get();

        return view('mycart.home.index', compact('products', 'slideres'));
    }
}
