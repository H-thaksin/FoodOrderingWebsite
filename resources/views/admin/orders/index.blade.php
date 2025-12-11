@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="order-card p-4 rounded-4 shadow-sm bg-white">
       <h2 class="mb-4 text-dark fw-bold">Order Management</h2>


        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($orders->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-pink fw-medium">
                                {{ $order->order_number }}
                            </a>
                        </td>

                        <td>
                            {{ $order->items->pluck('food_name')->join(', ') }}
                        </td>

                        <td>${{ number_format($order->total_price, 2) }}</td>

                        <td>
                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                @csrf
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    @php
                                        $statuses = ['pending','confirmed','preparing','out_for_delivery','completed','cancelled'];
                                    @endphp
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_',' ',$status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>

                        <td>{{ $order->address }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>

                        <td class="d-flex flex-column gap-1">
                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-pink">Delete</button>
                            </form>

                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-pink">
                                Track Order
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted text-center">No orders found.</p>
        @endif
    </div>
</div>
@endsection
