@extends('layouts.app')

@section('content')
<h2>Categories</h2>

<a href="{{ route('admin.categories.create') }}" class="btn btn-success mb-3">Add Category</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="GET" class="mb-3">
    <input type="text" name="search" placeholder="Search categories..." value="{{ request('search') }}" class="form-control" style="width:300px; display:inline-block;">
    <button type="submit" class="btn btn-secondary">Search</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Reset</a>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="3">No categories found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
