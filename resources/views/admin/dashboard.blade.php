@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Dashboard Heading -->
    <h2 class="mb-4 text-dark fw-bold">Admin Dashboard</h2>

    <!-- Stats Cards -->
    <div class="row g-4">
        <!-- Total Orders -->
        <div class="col-md-3">
            <div class="dashboard-card bg-gradient-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text fs-2 fw-bold">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-md-3">
            <div class="dashboard-card bg-gradient-warning">
                <div class="card-body text-center">
                    <h5 class="card-title">Pending Orders</h5>
                    <p class="card-text fs-2 fw-bold">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-md-3">
            <div class="dashboard-card bg-gradient-success">
                <div class="card-body text-center">
                    <h5 class="card-title">Completed Orders</h5>
                    <p class="card-text fs-2 fw-bold">{{ $completedOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Cancelled Orders -->
        <div class="col-md-3">
            <div class="dashboard-card bg-gradient-danger">
                <div class="card-body text-center">
                    <h5 class="card-title">Cancelled Orders</h5>
                    <p class="card-text fs-2 fw-bold">{{ $cancelledOrders }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mt-5">
        <h4 class="mb-3 text-dark fw-semibold">Quick Links</h4>
        <div class="list-group shadow-sm rounded-3">
            <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">
                Manage Orders
            </a>
            <a href="{{ route('admin.foods.index') }}" class="list-group-item list-group-item-action">
                Manage Foods
            </a>
            <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action">
                Manage Categories
            </a>
        </div>
    </div>
</div>
@endsection
