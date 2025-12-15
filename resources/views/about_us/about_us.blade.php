@extends('layouts.app')

@section('content')

<!-- =========================
     ABOUT US SECTION
========================= -->
<section id="about-us" class="container my-5 py-5 text-center">
    <h2 class="fw-bold mb-4">About Us</h2>
    <p class="mb-3 fs-5">
        Welcome to <strong>Apsara Flavors</strong>! We bring you the authentic taste of Khmer cuisine,
        crafted with love and the freshest ingredients. Taste the heart of Cambodia in every bite.
    </p>
    <p class="mb-4 fs-5">
        Our mission is to deliver quality, freshness, and a true Cambodian flavor experience to our customers.
    </p>

    <!-- TEAM MEMBERS -->
    <div class="row justify-content-center mt-4 g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 team-card">
                <img src="{{ asset('images/about/team1.jpg') }}" class="card-img-top rounded-4" alt="Team Member">
                <div class="card-body">
                    <h5 class="fw-bold">Hun Sen</h5>
                    <p class="text-muted">Founder & CEO</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 team-card">
                <img src="{{ asset('images/about/team2.jpg') }}" class="card-img-top rounded-4" alt="Team Member">
                <div class="card-body">
                    <h5 class="fw-bold">Sam Rainsy</h5>
                    <p class="text-muted">Head Chef</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 team-card">
                <img src="{{ asset('images/about/team3.jpg') }}" class="card-img-top rounded-4" alt="Team Member">
                <div class="card-body">
                    <h5 class="fw-bold">Anutin Charnvirakul</h5>
                    <p class="text-muted">Head Chef</p>
                </div>
            </div>
        </div>
    </div>
    
</section>

<!-- =========================
     COMPANY TIMELINE
========================= -->
<section id="company-history" class="py-5 bg-white text-center">
    <div class="container">
        <h2 class="fw-bold mb-5">Our Journey</h2>

        <div class="timeline">
            @php
            $timeline = [
                ['icon' => '1', 'title' => 'Founded in 2020', 'desc' => 'Apsara Flavors was born with a mission to share authentic Khmer cuisine.'],
                ['icon' => '2', 'title' => 'First Restaurant Opened', 'desc' => 'Our first branch opened in Phnom Penh, bringing Khmer flavors to the heart of the city.'],
                ['icon' => '3', 'title' => 'Expanded Menu', 'desc' => 'Introduced new signature dishes, celebrating traditional flavors with modern presentation.'],
                ['icon' => '4', 'title' => 'Online Delivery', 'desc' => 'Launched our online ordering system to deliver fresh Khmer flavors to your doorstep.'],
            ];
            @endphp

            @foreach ($timeline as $item)
            <div class="timeline-item">
                <div class="timeline-icon bg-pink">{{ $item['icon'] }}</div>
                <div class="timeline-content">
                    <h5>{{ $item['title'] }}</h5>
                    <p>{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- =========================
     MISSION & VISION
========================= -->
<section id="mission-vision" class="container my-5 py-5 text-center">
    <div class="row justify-content-center g-4">

        <div class="col-md-5">
            <div class="card mv-card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Our Mission</h5>
                    <p>
                        To bring the authentic flavors of Khmer cuisine to everyone, using fresh,
                        high-quality ingredients and traditional recipes passed down through generations.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card mv-card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Our Vision</h5>
                    <p>
                        To become the leading name in Cambodian cuisine, delivering a delightful
                        culinary experience that celebrates our culture and traditions.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
