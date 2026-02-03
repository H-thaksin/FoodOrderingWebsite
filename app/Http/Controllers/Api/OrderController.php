<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // -------------------------
    // User Orders (requires auth)
    // -------------------------
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
                       ->with('items.food')
                       ->latest()
                       ->get();

        return response()->json([
            'status' => 'success',
            'orders' => $orders
        ]);
    }

    // -------------------------
    // Order Details
    // -------------------------
    public function show(Order $order, Request $request)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $order->load('items.food');

        return response()->json([
            'status' => 'success',
            'order'  => $order
        ]);
    }
}