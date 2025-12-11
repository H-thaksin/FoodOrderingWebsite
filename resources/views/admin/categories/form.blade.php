@extends('layouts.app')

@section('content')
<h2>{{ isset($category) ? 'Edit' : 'Add' }} Category</h2>

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

<form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST">
    @csrf
    @if(isset($category))
        @method('PUT')
    @endif

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
    </div>

    <button type="submit" class="btn btn-{{ isset($category) ? 'primary' : 'success' }}">
        {{ isset($category) ? 'Update' : 'Add' }} Category
    </button>
</form>
@endsection
