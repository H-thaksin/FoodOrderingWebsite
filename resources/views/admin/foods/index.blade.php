@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold">Foods</h2>
        <a href="{{ route('admin.foods.create') }}" class="btn btn-pink">Add Food</a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
    @endif

    <!-- Search Form -->
    <form method="GET" class="mb-3 d-flex gap-2 flex-wrap">
        <input type="text" name="search" placeholder="Search foods..." value="{{ request('search') }}" class="form-control search-input">
        <button type="submit" class="btn btn-outline-pink">Search</button>
        <a href="{{ route('admin.foods.index') }}" class="btn btn-light">Reset</a>
    </form>

    <!-- Foods Table -->
    <div class="table-responsive">
        <table class="table modern-table align-middle text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($foods as $food)
                <tr>
                    <td>{{ $food->id }}</td>
                    <td>{{ $food->name }}</td>
                    <td>{{ $food->category->name }}</td>
                    <td>${{ number_format($food->price,2) }}</td>
                    <td>
                        @if($food->image)
                            <img src="{{ asset('storage/foods/'.$food->image) }}" class="foods-img rounded">
                        @endif
                    </td>
                    <td class="d-flex justify-content-center gap-1">
                        <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-pink">Edit</a>
                        <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-muted">No foods found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
