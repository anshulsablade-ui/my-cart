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
        return view('mycart.home.index', compact('products'));
    }
}
