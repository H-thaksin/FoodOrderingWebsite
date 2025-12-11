@extends('layouts.app')

@section('content')
<h2>Categories</h2>
<div class="row">
    @foreach($categories as $cat)
        <div class="col-md-4 mb-3">
            <a href="{{ route('foods.index', $cat->id) }}" class="btn btn-primary w-100">
                {{ $cat->name }}
            </a>
        </div>
    @endforeach
</div>
@endsection
