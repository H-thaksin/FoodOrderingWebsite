<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Food Ordering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
        }
        .sidebar a {
            text-decoration: none;
        }
        .card-hover:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transform: translateY(-2px);
            transition: all 0.2s;
        }
    </style>
</head>
<body>
<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar bg-dark text-white p-3">
        <h3 class="mb-4">Admin Panel</h3>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('admin.categories.index') }}">Manage Categories</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('admin.foods.index') }}">Manage Foods</a>
            </li>
            
            <li class="nav-item mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-light btn-sm w-100">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')

        <!-- Example Dashboard Cards -->
        @if(Route::currentRouteName() == 'admin.dashboard')
        <div class="row">
            <!-- Categories Card -->
            <div class="col-md-4 mb-4">
                <a href="{{ route('admin.categories.index') }}" class="text-decoration-none text-dark">
                    <div class="card p-3 card-hover">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Categories</h5>
                            <span class="badge bg-primary">{{ \App\Models\Category::count() }}</span>
                        </div>
                        <p class="mb-0">Manage all categories.</p>
                    </div>
                </a>
            </div>

            <!-- Foods Card -->
            <div class="col-md-4 mb-4">
                <a href="{{ route('admin.foods.index') }}" class="text-decoration-none text-dark">
                    <div class="card p-3 card-hover">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Foods</h5>
                            <span class="badge bg-success">{{ \App\Models\Food::count() }}</span>
                        </div>
                        <p class="mb-0">Manage all foods.</p>
                    </div>
                </a>
            </div>

            <!-- Orders Card -->
            
        </div>
        @endif

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
