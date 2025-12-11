<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Food;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Require login for all order pages
    public function __construct()
    {
      $this->middleware('auth'); // This is correct
    }

    public function index()
    {
        // Only show orders of logged-in user
        $orders = Order::where('user_id', Auth::id())
                       ->with('items.food')
                       ->latest()
                       ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $foods = Food::all();
        return view('orders.create', compact('foods'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'food_id.*' => 'required|exists:foods,id',
            'qty.*'     => 'required|integer|min:1',
            'address'   => 'required|string|max:255',
        ]);

        $totalPrice = 0;

        // Create order
        $order = Order::create([
            'user_id'     => Auth::id(),
            'status'      => 'pending',
            'total_price' => 0, // update later
            'address'     => $request->address,
        ]);

        // Loop through order items
        foreach ($request->food_id as $i => $foodId) {
            $quantity = $request->qty[$i];
            if ($quantity < 1) continue;

            $food = Food::findOrFail($foodId);
            $itemPrice = $food->price * $quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'food_id'  => $foodId,
                'quantity' => $quantity,
                'price'    => $itemPrice,
            ]);

            $totalPrice += $itemPrice;
        }

        // Update final order price
        $order->update(['total_price' => $totalPrice]);

        return redirect()
                ->route('orders.index')
                ->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        // Prevent unauthorized access
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $order->load('items.food');
        return view('orders.show', compact('order'));
    }
    public function checkout(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'Your cart is empty!');
    }

    $request->validate([
        'address' => 'required|string|max:255',
    ]);

    $userId = Auth::check() ? Auth::id() : 1;

    $totalPrice = 0;

    // Create order
    $order = Order::create([
        'user_id'     => $userId,
        'status'      => 'pending',
        'total_price' => 0, // temporary
        'address'     => $request->address,
    ]);

    // Add order items
    foreach ($cart as $foodId => $item) {
        $subtotal = $item['price'] * $item['qty'];

        OrderItem::create([
            'order_id' => $order->id,
            'food_id'  => $foodId,
            'quantity' => $item['qty'],
            'price'    => $subtotal,
        ]);

        $totalPrice += $subtotal;
    }

    // Update order total
    $order->update(['total_price' => $totalPrice]);

    // Clear cart
    session()->forget('cart');

    return redirect()->route('orders.index')
                     ->with('success', 'Order placed successfully!');
}

public function userOrders() {
    $orders = Order::where('user_id', auth()->id())->with('items.food')->latest()->get();
    return view('orders.history', compact('orders'));
}
public function track(Order $order)
{
    if(auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
        abort(403);
    }

    return view('orders.track', compact('order'));
}

    // Store review
    public function review(Request $request, Order $order)
    {
        // Ensure order belongs to current user and is completed
        if($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403, 'You cannot review this order.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['order_id' => $order->id, 'user_id' => Auth::id()],
            ['rating' => $request->rating, 'review' => $request->review]
        );

        return redirect()->back()->with('success', 'Your review has been submitted!');
    }
}
