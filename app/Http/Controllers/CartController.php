<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Apply auth middleware only for checkout pages
    public function __construct()
    {
        $this->middleware('auth')->only(['checkoutForm', 'checkout']);
    }

    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = $this->calculateTotal($cart);

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $food = Food::findOrFail($request->food_id);
        $qty = $request->qty ?? 1;

        $cart = Session::get('cart', []);

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

        Session::put('cart', $cart);

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart),
            'message'    => "{$food->name} added to cart!"
        ]);
    }

    /**
     * Update cart quantity
     */
    public function update(Request $request)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$request->food_id])) {
            $cart[$request->food_id]['qty'] = max(1, intval($request->qty));
            Session::put('cart', $cart);
        }

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart),
            'message'    => 'Cart updated successfully!'
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$request->food_id])) {
            unset($cart[$request->food_id]);
            Session::put('cart', $cart);
        }

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart),
            'message'    => 'Item removed successfully!'
        ]);
    }

    /**
     * Show checkout form
     */
    public function checkoutForm()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $total = $this->calculateTotal($cart);
        $deliveryFee = 5.00; // fixed
        $discount = 0;        // optional coupon logic
        $finalTotal = $total + $deliveryFee - $discount;

        return view('cart.checkout', compact('cart', 'total', 'deliveryFee', 'discount', 'finalTotal'));
    }

    /**
     * Process checkout and create order
     */
    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'payment_method' => 'required|in:cash,online',
            'coupon_code'    => 'nullable|string|max:50',
        ]);

        $userId = Auth::id();
        $deliveryFee = 5.00;
        $discount = 0;
        $totalPrice = 0;

        // Create Order
        $order = Order::create([
            'user_id'       => $userId,
            'order_number'  => strtoupper(uniqid('ORD')),
            'customer_name' => $request->customer_name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'total_price'   => 0, // will update later
            'delivery_fee'  => $deliveryFee,
            'payment_method'=> $request->payment_method,
            'coupon_code'   => $request->coupon_code,
            'status'        => 'pending',
        ]);

        // Save order items (snapshot)
        foreach ($cart as $foodId => $item) {
            $subtotal = $item['price'] * $item['qty'];
            OrderItem::create([
                'order_id'   => $order->id,
                'food_id'    => $foodId,
                'food_name'  => $item['name'],
                'food_image' => $item['image'] ?? null,
                'unit_price' => $item['price'],
                'quantity'   => $item['qty'],
                'subtotal'   => $subtotal,
                'price'      => $subtotal,
            ]);

            $totalPrice += $subtotal;
        }

        // Update final order total
        $order->update([
            'total_price' => $totalPrice + $deliveryFee - $discount
        ]);

        // Clear cart
        Session::forget('cart');

        // Redirect to user orders history
        return redirect()->route('orders.history')
                         ->with('success', 'Order placed successfully!');
    }

    /**
     * Helper function to calculate total price of cart
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }
}
