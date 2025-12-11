@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Profile</h2>

    <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger mt-3">Logout</button>
    </form>
</div>
@endsection
