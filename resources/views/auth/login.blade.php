@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Login</button>

        <p class="mt-3 text-center">
            Don't have an account? <a href="{{ route('register') }}">Register now</a>
        </p>
    </form>
</div>
@endsection
