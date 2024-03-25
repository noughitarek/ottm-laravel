<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10)->onEachSide(2);
        return view('pages.orders.index')->with('orders', $orders);
    }
}
