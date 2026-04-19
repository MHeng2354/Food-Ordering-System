@extends('layouts.app')

@section('content')
    <section class="hero bg-white text-dark shadow-lg py-5 mb-5">
        <div class="container">
            <div class="row align-items-center gx-5">
                <div class="col-lg-6 text-center mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=900&q=80" class="img-fluid rounded-4 shadow-lg" alt="Malaysian food">
                </div>
                <div class="col-lg-6">
                    <span class="badge bg-primary text-white mb-3">Authentic Malaysian Flavors</span>
                    <h1 class="display-5 fw-bold mb-5">Happy With <span class="text-primary">Delicious Food</span> And Get New Experiences With <span class="text-primary">Asian Food</span></h1>
                    <p class="text-muted mb-5">Exploring new food with different flavors from across Asia, especially Malaysia. Try our curated menu, enjoy great prices, and experience the best meals for your family.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="/menu" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart-check text-white"></i> Order Food
                        </a>
                        <a href="/contact" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-info-circle text-info"></i> Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <section class="mb-5">
            <h2 class="section-title mb-4 text-center">Why choose City Food Express?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-truck text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Fast delivery</h5>
                            <p class="card-text text-muted">Get your order delivered hot and fresh in record time, with real-time updates for every step.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-list-ul text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Variety of options</h5>
                            <p class="card-text text-muted">Enjoy authentic Malaysian dishes, from spicy curries to fresh seafood. We have it all.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="bi bi-cart-check text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title">Easy ordering</h5>
                            <p class="card-text text-muted">A seamless menu experience, secure checkout, and order tracking make dining simple.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="section-title mb-4 text-center">Popular Malaysian Picks</h2>
            <div class="row g-4">
                @foreach($foods->take(3) as $food)
                <div class="col-md-4">
                    <div class="card dish-card h-100 overflow-hidden border-0 shadow-sm" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <a href="/menu" style="text-decoration: none;">
                            @if($food->image)
                                <img src="{{ asset('images/' . $food->image) }}" class="card-img-top" alt="{{ $food->name }}">
                            @else
                                <img src="https://via.placeholder.com/400x300?text=No+Image" class="card-img-top" alt="{{ $food->name }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $food->name }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($food->description, 100) }}</p>
                                <p class="text-primary fw-bold">RM {{ number_format($food->price, 2) }}</p>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="text-white rounded-4 p-5 shadow-sm" style="background: linear-gradient(135deg, #4781ff 0%, #1a60f5 100%);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="h3 mb-3"><i class="bi bi-star-fill me-2"></i>Ready to order?</h2>
                    <p class="mb-0">Browse our full menu, add your favorites to the cart, and enjoy fast delivery or pickup today.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a class="btn btn-light btn-lg text-primary fw-bold" href="/menu"><i class="bi bi-arrow-right-circle me-2"></i>Start Your Order</a>
                </div>
            </div>
        </section>
    </div>
@endsection
