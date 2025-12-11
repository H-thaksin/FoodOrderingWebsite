@extends('layouts.app')

@section('content')
<div class="container my-5">

    <h2 class="mb-4">Order #{{ $order->order_number }}</h2>

    <!-- Order Progress Bar -->
    @php
        $stages = [
            'pending' => ['label' => 'Order Placed', 'icon' => '📝'],
            'confirmed' => ['label' => 'Confirmed', 'icon' => '✅'],
            'preparing' => ['label' => 'Preparing', 'icon' => '👨‍🍳'],
            'out_for_delivery' => ['label' => 'Out for Delivery', 'icon' => '🚚'],
            'completed' => ['label' => 'Delivered', 'icon' => '📦'],
        ];
        $currentIndex = array_search($order->status, array_keys($stages));
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
        @foreach($stages as $key => $stage)
            @php $active = array_search($key, array_keys($stages)) <= $currentIndex; @endphp
            <div class="text-center" style="flex:1; position: relative;">
                <div class="rounded-circle mx-auto mb-2" style="width:50px; height:50px; line-height:50px;
                    background-color: {{ $active ? '#28a745' : '#e9ecef' }}; color: {{ $active ? 'white' : '#6c757d' }};">
                    {{ $stage['icon'] }}
                </div>
                <div style="font-size: 0.9rem;">{{ $stage['label'] }}</div>

                @if (!$loop->last)
                    <div style="position:absolute; top:25px; right:-50%; width:100%; height:4px; background-color: {{ $active ? '#28a745' : '#e9ecef' }}; z-index:-1;"></div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Invoice Card -->
    <div class="card p-4" id="invoice">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>My Restaurant</h2>
                <p>123 Main Street, City</p>
                <p>Email: info@myrestaurant.com | Phone: +123456789</p>
            </div>
            <div class="text-end">
                <h5>Invoice</h5>
                <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_',' ', $order->status)) }}</p>
            </div>
        </div>

        <h5>Customer Information</h5>
        <p><strong>Name:</strong> {{ $order->customer_name }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>

        <h5>Order Details</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Food</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $itemsTotal = 0; @endphp
                @foreach($order->items as $item)
                    @php $itemsTotal += $item->unit_price * $item->quantity; @endphp
                    <tr>
                        <td>{{ $item->food_name }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Items Total:</strong></td>
                    <td>${{ number_format($itemsTotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Delivery Fee:</strong></td>
                    <td>${{ number_format($order->delivery_fee, 2) }}</td>
                </tr>
                @if($order->coupon_code)
                    <tr>
                        <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                        <td>- $0.00</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td>${{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn btn-secondary" onclick="printInvoice()">Print Invoice</button>
            <a href="{{ route('admin.orders') }}" class="btn btn-primary">Back to Orders</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function printInvoice() {
    let invoice = document.getElementById('invoice').innerHTML;
    let original = document.body.innerHTML;
    document.body.innerHTML = invoice;
    window.print();
    document.body.innerHTML = original;
    location.reload();
}
</script>
@endsection

@section('styles')
<style>
#invoice h2, #invoice h5 { margin-bottom: 0.5rem; }
#invoice table th, #invoice table td { vertical-align: middle !important; }

/* Hover effect on order stages */
.d-flex > div:hover {
    transform: scale(1.05);
    transition: transform 0.2s;
}
</style>
@endsection
