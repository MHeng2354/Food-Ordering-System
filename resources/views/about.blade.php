@extends('layouts.app')

@section('content')
    <section class="hero bg-light text-dark py-5 mb-5">
        <div class="container">
            <div class="row align-items-center gx-5">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">About FoodHub</h1>
                    <p class="mb-4">Bringing authentic Malaysian flavors to your doorstep with passion, quality, and convenience.</p>
                    <p class="mb-4">Founded with a love for Malaysian cuisine, we connect food lovers with the best local restaurants and deliver unforgettable dining experiences.</p>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-4 shadow-sm" alt="Malaysian food spread">
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <section class="mb-5">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h2 class="h1 mb-4">Our Story</h2>
                    <p class="mb-4">FoodHub was born from a simple idea: to make authentic Malaysian food accessible to everyone, everywhere. Our founders, passionate food enthusiasts, noticed how difficult it was to find genuine Malaysian dishes outside of Malaysia.</p>
                    <p class="mb-4">Starting as a small delivery service in the heart of the city, we've grown into a trusted platform that connects customers with the finest Malaysian restaurants. We believe that great food brings people together, and we're committed to delivering not just meals, but memorable experiences.</p>
                    <p>Today, we serve thousands of happy customers daily, from nasi lemak lovers to rendang enthusiasts, all while maintaining our core values of quality, authenticity, and exceptional service.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-4 shadow-sm" alt="Restaurant kitchen">
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="h1 text-center mb-5">Our Mission & Vision</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <i class="bi bi-bullseye text-primary" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="card-title h4 mb-3">Our Mission</h3>
                            <p class="card-text">To deliver authentic Malaysian cuisine with unparalleled quality and service, making every meal a celebration of culture and flavor.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <i class="bi bi-eye text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="card-title h4 mb-3">Our Vision</h3>
                            <p class="card-text">To be the leading platform for Malaysian food delivery worldwide, connecting communities through the joy of authentic cuisine.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="h1 text-center mb-5">Why Choose FoodHub?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Authentic Quality</h5>
                            <p class="card-text text-muted">We partner only with restaurants that maintain the highest standards of authenticity and quality in Malaysian cuisine.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-lightning-charge text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Lightning Fast</h5>
                            <p class="card-text text-muted">Our efficient delivery network ensures your food arrives hot and fresh, with real-time tracking every step of the way.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-heart text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Customer First</h5>
                            <p class="card-text text-muted">Your satisfaction is our priority. We listen to feedback and continuously improve to serve you better.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="text-white rounded-4 p-5 shadow-sm" style="background: linear-gradient(135deg, #4781ff 0%, #1a60f5 100%);">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="h2 mb-3"><i class="bi bi-envelope-fill me-2"></i>Get in Touch</h2>
                    <p class="mb-0">Have questions about our service or want to partner with us? We'd love to hear from you!</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="/contact" class="btn btn-light btn-lg text-primary fw-bold"><i class="bi bi-chat-dots me-2"></i>Contact Us</a>
                </div>
            </div>
        </section>
    </div>
@endsection