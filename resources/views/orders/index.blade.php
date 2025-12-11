@extends('layouts.admin')

@section('content')
<h2>All Customer Orders</h2>

@if($orders->count() > 0)
    @foreach($orders as $order)
        <div class="card mb-4 card-hover">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>Order #{{ $order->id }}</strong> |
                    <span>{{ $order->created_at->format('d M Y H:i') }}</span> |
                    <span>Customer: {{ $order->user->name }}</span>
                </div>

                <div>
                    <!-- Admin: Update order status -->
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                        @csrf
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            @php
                                $statuses = ['pending', 'processing', 'completed', 'cancelled'];
                            @endphp
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Food</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($order->items as $item)
                            @php $subtotal = $item->price * $item->quantity; $total += $subtotal; @endphp
                            <tr>
                                <td>
                                    @if($item->food && $item->food->image)
                                        <img src="{{ asset('storage/foods/'.$item->food->image) }}" style="height:50px;">
                                    @endif
                                </td>
                                <td>{{ $item->food ? $item->food->name : 'Deleted Food' }}</td>
                                <td>${{ number_format($item->price,2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($subtotal,2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td><strong>${{ number_format($total,2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Buttons: View Invoice & Track Order -->
                <div class="mt-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-sm">
                        View Invoice
                    </a>

                    <!-- Track Order button for admins (optional) -->
                    <a href="{{ route('orders.track', $order->id) }}" class="btn btn-primary btn-sm">
                        Track Order
                    </a>

                    <!-- Delete Order -->
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@else
    <p>No orders found!</p>
@endif
@endsection
