@extends('layouts.app')

@section('content')
<h2 class="mb-4">Place an Order</h2>

<a href="{{ route('orders.index') }}" class="btn btn-secondary mb-3">Back to Orders</a>

<form action="{{ route('orders.store') }}" method="POST" id="orderForm">
    @csrf

    <!-- Delivery Address -->
    <div class="mb-3">
        <label for="address" class="form-label">Delivery Address</label>
        <input type="text" name="address" id="address" class="form-control" 
               value="{{ old('address') }}" required>
        @error('address')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <!-- Food Selection -->
    <h4>Foods</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Food</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($foods as $food)
            <tr>
                <td>{{ $food->name }}</td>
                <td>$<span class="price">{{ number_format($food->price, 2) }}</span></td>
                <td>
                    <input type="hidden" name="food_id[]" value="{{ $food->id }}">
                    <input type="number" name="qty[]" min="0" value="0" class="form-control qty-input" 
                           data-price="{{ $food->price }}">
                </td>
                <td>$<span class="subtotal">0.00</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Price -->
    <h4>Total: $<span id="totalPrice">0.00</span></h4>

    <button type="submit" class="btn btn-primary">Place Order</button>
</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const qtyInputs = document.querySelectorAll('.qty-input');
    const totalPriceEl = document.getElementById('totalPrice');

    function updateTotal() {
        let total = 0;
        qtyInputs.forEach(input => {
            const price = parseFloat(input.dataset.price);
            const qty = parseInt(input.value) || 0;
            const subtotal = price * qty;

            // Update subtotal column
            input.closest('tr').querySelector('.subtotal').textContent = subtotal.toFixed(2);

            total += subtotal;
        });
        totalPriceEl.textContent = total.toFixed(2);
    }

    qtyInputs.forEach(input => {
        input.addEventListener('input', updateTotal);
    });

    // Initial calculation
    updateTotal();
});
</script>
@endsection
