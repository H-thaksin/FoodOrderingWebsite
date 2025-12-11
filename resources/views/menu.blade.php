@extends('layouts.app')

@section('content')
<h1 class="text-center mb-4">Menu</h1>

<div class="menu-container">
    @foreach($foods as $food)
    <div class="menu-item">
        @if($food->image)
            <img src="{{ asset('storage/foods/'.$food->image) }}" alt="{{ $food->name }}">
        @endif
        <h3>{{ $food->name }} <span>${{ number_format($food->price,2) }}</span></h3>
        <p>{{ $food->category->name }}</p>

        <div class="cart-controls">
            <input type="number" min="1" value="1" class="qty-input" id="qty-{{ $food->id }}">
            <button class="cart-btn add-to-cart-btn" data-id="{{ $food->id }}">Add to Cart</button>
        </div>
    </div>
    @endforeach
</div>

<!-- Floating Cart Badge -->
<div id="floatingCart">
    <a href="{{ route('cart.index') }}" class="btn btn-primary">
        Cart (<span id="cartCount">{{ session('cart') ? count(session('cart')) : 0 }}</span>)
    </a>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.add-to-cart-btn');
    const cartCountEl = document.getElementById('cartCount');

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            const foodId = this.dataset.id;
            const qtyInput = document.getElementById('qty-' + foodId);
            const qty = parseInt(qtyInput.value) || 1;

            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ food_id: foodId, qty: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    cartCountEl.textContent = data.cart_count;
                    alert('Added to cart!');
                } else {
                    alert('Failed to add item.');
                }
            });
        });
    });
});
</script>
@endsection
