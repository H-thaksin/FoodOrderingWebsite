@extends('layouts.app')

@section('content')
<div class="container my-5">

    <!-- Page Heading -->
    <h2 class="text-dark fw-bold mb-4">Order #{{ $order->order_number }}</h2>

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

    <div class="order-progress d-flex justify-content-between align-items-center mb-4">
        @foreach($stages as $key => $stage)
            @php $active = array_search($key, array_keys($stages)) <= $currentIndex; @endphp
            <div class="stage text-center flex-fill position-relative">
                <div class="stage-icon {{ $active ? 'active' : '' }}">
                    {{ $stage['icon'] }}
                </div>
                <div class="stage-label">{{ $stage['label'] }}</div>

                @if (!$loop->last)
                    <div class="stage-line {{ $active ? 'active' : '' }}"></div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Invoice Card -->
    <div class="card p-4 shadow-sm rounded-4" id="invoice">
        <div class="d-flex justify-content-between flex-wrap mb-4">
            <div>
                <h2 class="fw-bold">My Restaurant</h2>
                <p>123 Main Street, City, Country</p>
                <p>Email: info@myrestaurant.com | Phone: +123456789</p>
            </div>
            <div class="text-end">
                <h5>Invoice</h5>
                <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <p><strong>Status:</strong> <span class="text-pink fw-bold">{{ ucfirst(str_replace('_',' ', $order->status)) }}</span></p>
            </div>
        </div>

        <!-- Customer Info -->
        <h5 class="fw-bold">Customer Information</h5>
        <p><strong>Name:</strong> {{ $order->customer_name }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>

        <!-- Order Items Table -->
        <h5 class="fw-bold mt-4">Order Details</h5>
        <div class="table-responsive">
            <table class="table table-bordered modern-table align-middle text-center">
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
                        @php $itemsTotal += $item->unit_price * $item->quantity; @endphp
                        <tr>
                            <td>
                                @if($item->food_image)
                                    <img src="{{ asset('storage/foods/'.$item->food_image) }}" class="rounded" height="50">
                                @endif
                            </td>
                            <td>{{ $item->food_name }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-end"><strong>Items Total:</strong></td>
                        <td>${{ number_format($itemsTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Delivery Fee:</strong></td>
                        <td>${{ number_format($order->delivery_fee, 2) }}</td>
                    </tr>
                    @if($order->coupon_code)
                        <tr>
                            <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                            <td>- $0.00</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                        <td class="fw-bold text-pink">${{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Review Form -->
        @if($order->status === 'completed' && auth()->user()->role !== 'admin')
            <hr>
            <h5 class="fw-bold">Rate & Review Your Order</h5>
            <form action="{{ route('orders.review', $order->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="rating" class="form-label">Rating</label>
                    <select name="rating" id="rating" class="form-select" required>
                        <option value="">Select Rating</option>
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label for="review" class="form-label">Review</label>
                    <textarea name="review" id="review" rows="3" class="form-control" placeholder="Write your review here..." required></textarea>
                </div>
                <button type="submit" class="btn btn-pink">Submit Review</button>
            </form>
        @endif

        <!-- Action Buttons -->
        <div class="d-flex gap-2 mt-4 flex-wrap">
            <button type="button" class="btn btn-outline-secondary" onclick="printInvoice()">Print Invoice</button>
            <a href="{{ route('orders.history') }}" class="btn btn-pink">Back to Orders</a>
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
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection
