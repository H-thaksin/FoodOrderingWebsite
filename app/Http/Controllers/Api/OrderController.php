<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required','array','min:1'],
            'items.*.menu_item_id' => ['required','integer','exists:menu_items,id'],
            'items.*.qty' => ['required','integer','min:1'],
            'delivery_address' => ['required','string','max:500'],
            'note' => ['nullable','string','max:500'],
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($data, $user) {
            $menuItemIds = collect($data['items'])->pluck('menu_item_id')->unique();
            $menuItems = order::whereIn('id', $menuItemIds)->get()->keyBy('id');

            $total = 0;
            foreach ($data['items'] as $line) {
                $item = $menuItems[$line['menu_item_id']];
                $total += ($item->price * $line['qty']);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'delivery_address' => $data['delivery_address'],
                'note' => $data['note'] ?? null,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            foreach ($data['items'] as $line) {
                $item = $menuItems[$line['menu_item_id']];
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item->id,
                    'qty' => $line['qty'],
                    'unit_price' => $item->price,
                    'line_total' => $item->price * $line['qty'],
                ]);
            }

            return response()->json([
                'message' => 'Order created',
                'data' => $order->load('items.menuItem'),
            ], 201);
        });
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->with('items.menuItem')
            ->paginate(10);

        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->with('items.menuItem')
            ->findOrFail($id);

        return response()->json(['data' => $order]);
    }
}