@extends('layouts.app')

@section('content')
<h2>{{ isset($food) ? 'Edit' : 'Add' }} Food</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ isset($food) ? route('admin.foods.update', $food->id) : route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($food))
        @method('PUT')
    @endif

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $food->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (isset($food) && $food->category_id == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $food->price ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Enter food description">{{ old('description', $food->description ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
        @if(isset($food) && $food->image)
            <img src="{{ asset('storage/foods/'.$food->image) }}" style="height:100px;" class="mt-2">
        @endif
    </div>

    <button type="submit" class="btn btn-{{ isset($food) ? 'primary' : 'success' }}">
        {{ isset($food) ? 'Update' : 'Add' }} Food
    </button>
</form>
@endsection
