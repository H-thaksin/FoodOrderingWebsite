@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-dark fw-bold">My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($orders) > 0)
    <div class="accordion" id="ordersAccordion">
        @foreach($orders as $index => $order)
        <div class="card mb-3 shadow-sm card-hover rounded-4">
            <div class="card-header d-flex justify-content-between align-items-center bg-light rounded-top-4" id="heading{{ $index }}">
                <div class="order-summary">
                    <strong>Order #{{ $order->order_number }}</strong>
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                    <span>${{ number_format($order->total_price, 2) }}</span>
                    <span class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#invoice-{{ $order->id }}" aria-expanded="false" aria-controls="invoice-{{ $order->id }}">
                        View Invoice
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="printInvoice('invoice-{{ $order->id }}')">
                        Print
                    </button>
                </div>
            </div>

            <!-- Progress Bar for Order Status -->
            <div class="progress order-progress">
                @php
                    $statuses = ['pending' => 'Order Placed', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
                    $currentIndex = array_search($order->status, array_keys($statuses));
                @endphp
                @foreach($statuses as $key => $label)
                    <div class="progress-step {{ $loop->index <= $currentIndex ? 'active' : '' }}">
                        <span class="step-label">{{ $label }}</span>
                    </div>
                @endforeach
            </div>

            <div id="invoice-{{ $order->id }}" class="collapse">
                <div class="card-body p-3">
                    <h5 class="fw-bold">Customer Information</h5>
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone }}</p>
                    <p><strong>Address:</strong> {{ $order->address }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>

                    <h5 class="fw-bold mt-4">Order Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0 modern-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Food</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $itemsTotal = 0; @endphp
                                @foreach($order->items as $item)
                                    @php $itemsTotal += $item->subtotal; @endphp
                                    <tr>
                                        <td>
                                            @if($item->food_image)
                                                <img src="{{ asset('storage/foods/'.$item->food_image) }}" height="40" class="rounded">
                                            @endif
                                        </td>
                                        <td>{{ $item->food_name }}</td>
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Items Total:</td>
                                    <td>${{ number_format($itemsTotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Delivery Fee:</td>
                                    <td>${{ number_format($order->delivery_fee, 2) }}</td>
                                </tr>
                                @if($order->coupon_code)
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Discount:</td>
                                    <td>- $0.00</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                    <td class="text-pink fw-bold">${{ number_format($order->total_price, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
        <p class="text-muted">You have no orders yet.</p>
        <a href="{{ route('menu.index') }}" class="btn btn-primary">Order Now</a>
    @endif
</div>
@endsection

@section('scripts')
<script>
function printInvoice(id) {
    const invoice = document.getElementById(id).innerHTML;
    const original = document.body.innerHTML;
    document.body.innerHTML = invoice;
    window.print();
    document.body.innerHTML = original;
    location.reload();
}
</script>
@endsection
