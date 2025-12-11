<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apsara Flavors</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

   <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm custom-navbar">
    <div class="container">

    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
    <img src="{{ asset('images/ApsaraFlavors.jpg') }}" 
         alt="Apsara Flavors Logo"
         class="brand-logo">

    <span class="brand-text">Apsara Flavors</span>
</a>



        <!-- Hamburger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- LEFT MENU -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active-link' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door-fill"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about-us"><i class="bi bi-info-circle-fill"></i> About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#catalog"><i class="bi bi-menu-button-wide-fill"></i> Catalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#gallery"><i class="bi bi-images"></i> Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#location"><i class="bi bi-geo-alt-fill"></i> Location</a>
                </li>
            </ul>

            <!-- SEARCH -->
            <form class="d-flex me-3 search-box" action="{{ route('menu.index') }}" method="GET">
                <input class="form-control search-input" type="text" name="search" placeholder="Search food...">
                <button class="btn btn-light ms-2 search-btn"><i class="bi bi-search"></i></button>
            </form>

            <!-- RIGHT SIDE -->
            <ul class="navbar-nav align-items-center">

              @auth
    @if(strtolower(auth()->user()->role) === 'user')
        <!-- CUSTOMER CART (New Order Items) -->
        <li class="nav-item me-2">
            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                <i class="bi bi-cart-fill fs-5"></i>
                <span class="badge badge-cart position-absolute top-0 start-100 translate-middle">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>
        </li>

        <!-- CUSTOMER ORDERS / INVOICE -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('orders.history') }}">
                <i class="bi bi-receipt fs-5"></i> Orders
            </a>
        </li>
    @endif



                    @if(auth()->user()->role === 'admin')
                        <!-- ADMIN DASHBOARD -->
                        <li class="nav-item me-2">
                            <a class="nav-link btn btn-warning text-dark" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                    @endif

                    <!-- PROFILE DROPDOWN -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="ms-1">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person-lines-fill"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                @else
                    <!-- GUEST LINKS -->
                    <li class="nav-item me-2">
                        <a class="btn btn-light btn-sm" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-sm" href="{{ route('register') }}"><i class="bi bi-person-plus-fill"></i> Register</a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>


<!-- Page Content -->
<div class="container my-5">
    @yield('content')
</div>

<!-- Footer -->
<footer class="site-footer bg-dark text-white py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} Apsara Flavors All rights reserved.</p>
        <div class="footer-links">
            <!-- Example social links -->
            <a href="#" class="text-white mx-2">Facebook</a>
            <a href="#" class="text-white mx-2">Instagram</a>
            <a href="#" class="text-white mx-2">Twitter</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
