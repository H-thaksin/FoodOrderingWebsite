@extends('layouts.app')

@section('content')
<div class="food-detail-container my-5">
    <div class="food-detail-card row g-4">
        <!-- Food Image -->
        <div class="col-md-6">
            @if($food->image)
            <img src="{{ asset('storage/foods/'.$food->image) }}" alt="{{ $food->name }}" class="food-img img-fluid rounded">
            @else
            <img src="{{ asset('images/placeholder-food.png') }}" alt="No image" class="food-img img-fluid rounded">
            @endif
        </div>

        <!-- Food Details -->
        <div class="col-md-6">
            <h2 class="food-name mb-3">{{ $food->name }}</h2>

            <p class="food-category mb-2">
                <strong>Category:</strong>
                <span>{{ $food->category->name ?? 'N/A' }}</span>
            </p>

            <p class="food-price mb-3">
                <strong>Price:</strong>
                <span>${{ number_format($food->price, 2) }}</span>
            </p>

            <p class="food-description mb-4">
                <strong>Description:</strong>
                {{ $food->description ?? 'No description available.' }}
            </p>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form d-flex gap-2 align-items-center mb-3">
                @csrf
                <input type="hidden" name="food_id" value="{{ $food->id }}">
                <input type="number" name="qty" value="1" min="1" class="form-control qty-input" style="width:80px;">
                <button type="submit" class="btn btn-primary cart-btn">Add to Cart</button>
            </form>

            <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary detail-btn">Back to Menu</a>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/food-detail.css') }}">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);

        $.post(form.attr('action'), form.serialize(), function(res) {
            if(res.success) {
                alert(res.message);
                $('#cart-count').text(res.cart_count);
            } else {
                alert('Failed to add to cart.');
            }
        }, 'json');
    });
});
</script>
@endsection
