@extends('layouts.app')

@section('content')
<!-- LOCATION PAGE -->
<section id="location" class="container my-5 py-5">
    <h2 class="text-center fw-bold mb-4">Our Location</h2>
        <p class="text-center mb-4">Visit us at St 160, Phnom Penh and enjoy authentic Khmer flavors!</p>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="map-responsive">
                    <!-- Google Maps Embed -->
                     <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d43611.728895178465!2d104.90500850824769!3d11.582008283449728!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109510bca0f91c1%3A0x5d36db9c4db27cdf!2sSt%20160%2C%20Phnom%20Penh!5e0!3m2!1sen!2skh!4v1765876577661!5m2!1sen!2skh" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
                </div>
                <div class="card-body text-center py-4">
                    <h5 class="fw-bold mb-2">Apsara Flavors</h5>
                    <p>123 Main Street, Phnom Penh, Cambodia</p>
                    <p>Email: info@apsaraflavors.com | Phone: +855 123 456 789</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.map-responsive {
    overflow: hidden;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    position: relative;
    height: 0;
}
.map-responsive iframe {
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    position: absolute;
    border-radius: 8px;
}
</style>
@endsection
