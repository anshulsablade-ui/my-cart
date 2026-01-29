<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems.product')->where('user_id', session()->get('user.id'))->paginate(8);      
        return view('mycart.account.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->where('id', $id)->first();
        return view('mycart.orderdetails', compact('order'));
    }
}
