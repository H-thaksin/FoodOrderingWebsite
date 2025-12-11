@extends('layouts.app')

@section('content')
<h2>
    @isset($category)
        Foods in {{ $category->name }}
    @else
        All Foods
    @endisset
</h2>

<div class="row">
    @forelse($foods as $food)
        <div class="col-md-4 mb-3">
            <div class="card">
                @if($food->image)
                    <img src="{{ asset('storage/'.$food->image) }}" class="card-img-top" alt="{{ $food->name }}">
                @else
                    <img src="{{ asset('images/default-food.png') }}" class="card-img-top" alt="{{ $food->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $food->name }}</h5>
                    <p class="card-text">${{ number_format($food->price, 2) }}</p>
                    <p class="card-text"><small>Category: {{ $food->category->name }}</small></p>
                    <a href="{{ route('menu.details', $food->id) }}" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
    @empty
        <p>No foods available.</p>
    @endforelse
</div>
@endsection
