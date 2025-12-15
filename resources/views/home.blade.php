@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<section class="hero-fullscreen">
    <div class="hero-inner text-center">
        <h1>Savor the Magic of Authentic Khmer Flavors</h1>
        <p>Taste the Heart of Cambodia — Fresh Authentic Khmer Flavors.</p>

        <a href="{{ route('menu.index') }}" class="btn btn-primary-modern me-2">GO MENU</a>

        @guest
        <a href="{{ route('register') }}" class="btn btn-outline-modern">Sign Up</a>
        @endguest
    </div>
</section>

<!-- ABOUT US SECTION -->
<section id="about-us" class="container my-5 py-5 text-center">
    <h2 class="fw-bold mb-4">About Us</h2>
    <p class="mb-3 fs-5">
        Welcome to <strong>Apsara Flavors</strong>! We bring you the authentic taste of Khmer cuisine,
        crafted with love and the freshest ingredients. Taste the heart of Cambodia in every bite.
    </p>
    <p class="mb-4 fs-5">
        Our mission is to deliver quality, freshness, and a true Cambodian flavor experience to our customers.
    </p>

    <div class="row justify-content-center mt-4 g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 team-card">
                <img src="{{ asset('images/about/team1.jpg') }}" class="card-img-top rounded-4" alt="Sokha Chhay">
                <div class="card-body">
                    <h5 class="fw-bold">Hun Sen</h5>
                    <p class="text-muted">Founder & CEO</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 team-card">
                <img src="{{ asset('images/about/team2.jpg') }}" class="card-img-top rounded-4" alt="Vannak Dara">
                <div class="card-body">
                    <h5 class="fw-bold">Sam Rainsy</h5>
                    <p class="text-muted">Head Chef</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 team-card">
                <img src="{{ asset('images/about/team3.jpg') }}" class="card-img-top rounded-4" alt="Anutin Charnvirakul">
                <div class="card-body">
                    <h5 class="fw-bold">Anutin Charnvirakul</h5>
                    <p class="text-muted">Restaurant Manager</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORY FILTER -->
<section class="container my-5">
    <h3 class="text-center mb-4 fw-semibold">Select a Category You Like</h3>

    <div class="d-flex flex-wrap justify-content-center gap-2">
        <a href="/?category=all" class="btn filter-btn {{ request('category') == 'all' ? 'active' : '' }}">All</a>

        @foreach($categories as $category)
            <a href="/?category={{ $category->id }}" 
               class="btn filter-btn {{ request('category') == $category->id ? 'active' : '' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
</section>

<!-- FOOD GRID -->
<section class="container mb-5">
    <h3 class="text-center fw-semibold mb-4">
        {{ request('category') && request('category') !== 'all' ? 'Foods in Category' : 'Featured FOOD' }}
    </h3>

    <div class="row g-4">
        @foreach($foods as $food)
        <div class="col-lg-4 col-md-6">
            <div class="card donut-card shadow-sm border-0 rounded-4">

                @if($food->image)
                <div class="food-img-container">
                    <img src="{{ asset('storage/foods/'.$food->image) }}" class="food-img rounded-top-4" alt="{{ $food->name }}">
                </div>
                @endif

                <div class="card-body text-center">
                    <h5 class="fw-bold">{{ $food->name }}</h5>
                    <p class="text-pink fw-semibold fs-5 mb-2">${{ number_format($food->price, 2) }}</p>

                    <div class="d-flex justify-content-center mb-3">
                        <input type="number" 
                               min="1" 
                               value="1" 
                               id="qty-{{ $food->id }}" 
                               class="qty-input form-control rounded-pill text-center">
                    </div>

                    <button class="btn btn-pink rounded-pill add-to-cart-btn px-4" data-id="{{ $food->id }}">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </div>

            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- FLOATING CART -->
<div id="floatingCart">
    <a href="{{ route('cart.index') }}" class="btn btn-pink shadow-lg rounded-pill px-4 py-2">
        <i class="bi bi-cart-fill"></i> Cart 
        (<span id="cartCount">{{ session('cart') ? count(session('cart')) : 0 }}</span>)
    </a>
</div>

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Add to cart functionality
    const buttons = document.querySelectorAll('.add-to-cart-btn');
    const cartCountEl = document.getElementById('cartCount');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const foodId = this.dataset.id;
            const qty = document.getElementById('qty-' + foodId).value;

            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ food_id: foodId, qty: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    cartCountEl.textContent = data.cart_count;
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to cart!',
                        timer: 1000,
                        showConfirmButton: false
                    });
                }
            });
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script>
@endsection
