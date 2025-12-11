@extends('layouts.app')

@section('content')
<h2 class="mb-4">Edit Food</h2>

<form action="{{ route('foods.update', $food->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Food Name</label>
        <input type="text" name="name" class="form-control" value="{{ $food->name }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ $food->description }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ $food->price }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control" required>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ $food->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Image</label>
        @if ($food->image)
            <br>
            <img src="{{ asset('storage/foods/'.$food->image) }}" width="100" class="mb-2">
        @endif
        <input type="file" name="image" class="form-control">
    </div>

    <button class="btn btn-success">Update</button>
    <a href="{{ route('foods.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
