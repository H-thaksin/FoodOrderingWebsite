@extends('layouts.app')

@section('content')
<h2 class="mb-4">Foods</h2>

<a href="{{ route('foods.create') }}" class="btn btn-primary mb-3">+ Add Food</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Food Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($foods as $food)
        <tr>
            <td>{{ $food->id }}</td>
            <td>
                @if ($food->image)
                <img src="{{ asset('storage/foods/'.$food->image) }}" width="50">
                @endif
            </td>
            <td>{{ $food->name }}</td>
            <td>{{ $food->description }}</td>
            <td>{{ $food->category->name }}</td>
            <td>${{ $food->price }}</td>
            <td>
                <a href="{{ route('foods.edit', $food) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('foods.destroy', $food) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
