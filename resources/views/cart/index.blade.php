@extends('layouts.app')

@section('content')
<h2>Your Cart</h2>

@php $cart = session('cart', []); @endphp

@if(count($cart) > 0)
<table class="table table-bordered" id="cart-table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Food</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach($cart as $item)
            @php $subtotal = $item['price'] * $item['qty']; $total += $subtotal; @endphp
            <tr id="cart-item-{{ $item['id'] }}">
                <td>
                    @if(!empty($item['image']))
                        <img src="{{ asset('storage/foods/'.$item['image']) }}" style="height:50px;">
                    @endif
                </td>
                <td>{{ $item['name'] }}</td>
                <td class="item-price" data-price="{{ $item['price'] }}">${{ number_format($item['price'],2) }}</td>
                <td>
                    <input type="number" class="form-control cart-qty" data-id="{{ $item['id'] }}" value="{{ $item['qty'] }}" min="1" style="width:70px;">
                </td>
                <td class="item-subtotal">${{ number_format($subtotal,2) }}</td>
                <td>
                    <button class="btn btn-sm btn-danger cart-remove" data-id="{{ $item['id'] }}">Remove</button>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-end"><strong>Total:</strong></td>
            <td colspan="2" id="cart-total"><strong>${{ number_format($total,2) }}</strong></td>
        </tr>
    </tbody>
</table>

<a href="{{ route('checkout.form') }}" class="btn btn-success">Proceed to Checkout</a>

@else
<p>Your cart is empty!</p>
<a href="{{ route('menu.index') }}" class="btn btn-primary">Back to Menu</a>
@endif
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    function recalcTotal() {
        let total = 0;
        $('#cart-table tbody tr').each(function(){
            let subtotalText = $(this).find('.item-subtotal').text();
            if(subtotalText){
                total += parseFloat(subtotalText.replace('$','')) || 0;
            }
        });
        $('#cart-total').html('<strong>$'+total.toFixed(2)+'</strong>');
    }

    // Update quantity dynamically
    $('.cart-qty').on('change', function(){
        var food_id = $(this).data('id');
        var qty = $(this).val();
        var row = $('#cart-item-' + food_id);
        var price = parseFloat(row.find('.item-price').data('price'));

        $.post('{{ route("cart.update") }}', {
            _token: '{{ csrf_token() }}',
            food_id: food_id,
            qty: qty
        }, function(res){
            if(res.success){
                let newSubtotal = (price * qty).toFixed(2);
                row.find('.item-subtotal').text('$' + newSubtotal);
                recalcTotal();
            }
        }, 'json');
    });

    // Remove item dynamically
    $('.cart-remove').click(function(){
        var food_id = $(this).data('id');
        if(!confirm('Are you sure you want to remove this item?')) return;
        var row = $('#cart-item-' + food_id);

        $.post('{{ route("cart.remove") }}', {
            _token: '{{ csrf_token() }}',
            food_id: food_id
        }, function(res){
            if(res.success){
                row.remove();
                recalcTotal();
                $('#cart-count').text(res.cart_count);
                if($('#cart-table tbody tr').length <= 1){
                    $('#cart-table').replaceWith('<p>Your cart is empty!</p><a href="{{ route("menu.index") }}" class="btn btn-primary">Back to Menu</a>');
                }
            }
        }, 'json');
    });

});
</script>
@endsection
