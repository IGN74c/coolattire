<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $revenueTotal = Order::where('status', 'завершен')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total')
            ->value('total') ?? 0;
            
        $latestOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        $newUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalUsers', 
            'totalOrders', 
            'revenueTotal', 
            'latestOrders', 
            'newUsers'
        ));
    }
} 