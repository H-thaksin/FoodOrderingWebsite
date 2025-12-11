@extends('layouts.app')

@section('content')
@php 
    $cart = session('cart', []); 
    $total = 0;
    foreach($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }
    $deliveryFee = 5.00; // fixed fee
    $finalTotal = $total + $deliveryFee;
@endphp

<div class="container my-5">
    <div class="card p-4" id="invoice">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>My Restaurant</h2>
                <p>123 Main Street, City, Country</p>
                <p>Email: info@myrestaurant.com | Phone: +123456789</p>
            </div>
            <div class="text-end">
                <h5>Invoice</h5>
                <p><strong>Order #:</strong> {{ time() }}</p>
                <p><strong>Date:</strong> {{ now()->format('d M Y H:i') }}</p>
            </div>
        </div>

        @if(count($cart) > 0)
        <form action="{{ route('checkout.submit') }}" method="POST">
            @csrf
            <!-- Customer Information -->
            <h5>Customer Information</h5>
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="customer_name" class="form-control" 
                    value="{{ old('customer_name', auth()->user()->name) }}" required>
            </div>
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" 
                    value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
            </div>
            <div class="mb-3">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="cash" {{ old('payment_method')=='cash' ? 'selected' : '' }}>Cash</option>
                    <option value="online" {{ old('payment_method')=='online' ? 'selected' : '' }}>Online</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Coupon Code (Optional)</label>
                <input type="text" name="coupon_code" class="form-control" value="{{ old('coupon_code') }}">
            </div>

            <!-- Order Details -->
            <h5>Order Details</h5>
            <table class="table table-bordered">
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
                    @foreach($cart as $item)
                        @php $subtotal = $item['price'] * $item['qty']; @endphp
                        <tr>
                            <td>
                                @if(isset($item['image']))
                                    <img src="{{ asset('storage/foods/'.$item['image']) }}" height="50">
                                @endif
                            </td>
                            <td>{{ $item['name'] }}</td>
                            <td>${{ number_format($item['price'], 2) }}</td>
                            <td>{{ $item['qty'] }}</td>
                            <td>${{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-end"><strong>Items Total:</strong></td>
                        <td>${{ number_format($total, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Delivery Fee:</strong></td>
                        <td>${{ number_format($deliveryFee, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Final Total:</strong></td>
                        <td>${{ number_format($finalTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Place Order</button>
                <button type="button" class="btn btn-secondary" onclick="printInvoice()">Print Invoice</button>
                <a href="{{ route('cart.index') }}" class="btn btn-warning">Back to Cart</a>
            </div>
        </form>
        @else
            <p>Your cart is empty!</p>
            <a href="{{ route('menu.index') }}" class="btn btn-primary">Back to Menu</a>
        @endif
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
</style>
@endsection
