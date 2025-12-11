<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Food;
use App\Models\Order;
class AdminController extends Controller
{
     public function dashboard()
    {
        $totalOrders = Order::count();
    $pendingOrders = Order::where('status', 'pending')->count();
    $completedOrders = Order::where('status', 'completed')->count();
    $cancelledOrders = Order::where('status', 'cancelled')->count();

    return view('admin.dashboard', compact(
        'totalOrders', 'pendingOrders', 'completedOrders', 'cancelledOrders'
    ));
    }
}
