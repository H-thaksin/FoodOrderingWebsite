@extends('layouts.app')

@section('content')
<h2 class="text-center mb-4">Menu</h2>

<div class="menu-container">
    @foreach($foods as $food)
    <div class="menu-item">
        @if($food->image)
        <img src="{{ asset('storage/foods/'.$food->image) }}" alt="{{ $food->name }}">
        @endif
        <h3>{{ $food->name }} <span>${{ number_format($food->price,2) }}</span></h3>
        <p>{{ $food->category->name }}</p>

        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
            @csrf
            <input type="hidden" name="food_id" value="{{ $food->id }}">
            <input type="number" name="qty" value="1" min="1" class="qty-input">
            <button type="submit" class="cart-btn">Add to Cart</button>
        </form>

        <a href="{{ route('menu.details', $food->id) }}" class="detail-btn">Details</a>
    </div>
    @endforeach
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('.add-to-cart-form').submit(function(e){
        e.preventDefault();
        var form = $(this);

        $.post(form.attr('action'), form.serialize(), function(res){
            if(res.success){
                alert(res.message); // You can replace with a toast
                $('#cart-count').text(res.cart_count); // update cart count
            }
        }, 'json');
    });
});
</script>
@endsection
