<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        // Require API token authentication for all routes
        $this->middleware('auth:sanctum');
    }

    // -------------------------
    // Show user cart
    // -------------------------
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?? [];
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);

        return response()->json([
            'status' => 'success',
            'cart'   => $cart,
            'total'  => $total
        ]);
    }

    // -------------------------
    // Add item to cart
    // -------------------------
    public function add(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'qty'     => 'nullable|integer|min:1',
        ]);

        $user = $request->user();
        $food = Food::findOrFail($request->food_id);
        $qty = $request->qty ?? 1;

        $cart = $user->cart ?? [];

        if (isset($cart[$food->id])) {
            $cart[$food->id]['qty'] += $qty;
        } else {
            $cart[$food->id] = [
                'id'    => $food->id,
                'name'  => $food->name,
                'price' => $food->price,
                'qty'   => $qty,
                'image' => $food->image,
            ];
        }

        $user->cart = $cart;
        $user->save();

        return response()->json([
            'status'     => 'success',
            'message'    => "{$food->name} added to cart",
            'cart_count' => count($cart)
        ]);
    }

    // -------------------------
    // Update cart item
    // -------------------------
    public function update(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'qty'     => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $cart = $user->cart ?? [];

        if (isset($cart[$request->food_id])) {
            $cart[$request->food_id]['qty'] = $request->qty;
            $user->cart = $cart;
            $user->save();
        }

        return response()->json([
            'status'     => 'success',
            'message'    => 'Cart updated successfully',
            'cart_count' => count($cart)
        ]);
    }

    // -------------------------
    // Remove item from cart
    // -------------------------
    public function remove(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
        ]);

        $user = $request->user();
        $cart = $user->cart ?? [];

        if (isset($cart[$request->food_id])) {
            unset($cart[$request->food_id]);
            $user->cart = $cart;
            $user->save();
        }

        return response()->json([
            'status'     => 'success',
            'message'    => 'Item removed from cart',
            'cart_count' => count($cart)
        ]);
    }

    // -------------------------
    // Checkout
    // -------------------------
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'payment_method' => 'required|in:cash,online',
        ]);

        $user = $request->user();
        $cart = $user->cart ?? [];

        if (empty($cart)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Cart is empty'
            ], 400);
        }

        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
        $deliveryFee = 5.00;

        $order = Order::create([
            'user_id'       => $user->id,
            'order_number'  => strtoupper(uniqid('ORD')),
            'customer_name' => $request->customer_name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'total_price'   => $totalPrice + $deliveryFee,
            'delivery_fee'  => $deliveryFee,
            'payment_method'=> $request->payment_method,
            'status'        => 'pending',
        ]);

        foreach ($cart as $foodId => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'food_id'    => $foodId,
                'food_name'  => $item['name'],
                'food_image' => $item['image'],
                'unit_price' => $item['price'],
                'quantity'   => $item['qty'],
                'subtotal'   => $item['price'] * $item['qty'],
                'price'      => $item['price'] * $item['qty'],
            ]);
        }

        // Clear cart
        $user->cart = [];
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Order placed successfully',
            'order'   => $order
        ]);
    }
}