@extends('layouts.app')

@section('content')
    <section class="hero bg-white text-dark shadow-lg py-5 mb-5">
        <div class="container">
            <div class="row align-items-center gx-5">
                <div class="col-lg-8">
                    <span class="badge bg-primary text-white mb-3">Exclusive Deals</span>
                    <h1 class="display-5 fw-bold mb-4">Current Promotions</h1>
                    <p class="text-muted mb-4">Browse active promotions with food items already assigned. Only verified promoted deals are shown here.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/menu" class="btn btn-outline-primary btn-lg">View Full Menu</a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        @if($promotions->count())
            <div class="row g-4">
                @foreach($promotions as $promotion)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                            @if($promotion->image)
                                <img src="{{ asset('images/' . $promotion->image) }}" class="card-img-top" alt="{{ $promotion->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/400x300?text=No+Image" class="card-img-top" alt="{{ $promotion->name }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-success fs-6">{{ number_format($promotion->discount_percentage, 0) }}% OFF</span>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <h4 class="fw-bold">{{ $promotion->name }}</h4>
                                        <p class="text-muted mb-2">{{ $promotion->description }}</p>
                                    </div>
                                    <div class="text-end">
                                        @if($promotion->start_date || $promotion->end_date)
                                            <small class="text-muted text-end">
                                                @if($promotion->start_date)<div>Starts: {{ date('M d, Y', strtotime($promotion->start_date)) }}</div>@endif
                                                @if($promotion->end_date)<div>Ends: {{ date('M d, Y', strtotime($promotion->end_date)) }}</div>@endif
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    @foreach($promotion->foods as $food)
                                        <div class="col-12 col-md-6">
                                            <a href="{{ route('foods.show', $food->id) }}" class="text-decoration-none">
                                                <div class="card border-0 shadow-sm overflow-hidden" style="height: 350px; transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                                    @if($food->image)
                                                        <img src="{{ asset('images/' . $food->image) }}" class="card-img-top" alt="{{ $food->name }}" style="height: 180px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary" style="height: 180px;"></div>
                                                    @endif
                                                    <div class="card-body d-flex flex-column">
                                                        <div>
                                                            <h5 class="card-title mb-2">{{ $food->name }}</h5>
                                                            <p class="text-muted small mb-3">{{ Str::limit($food->description, 70) }}</p>
                                                        </div>
                                                        <div class="mt-auto">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="fw-bold">RM {{ number_format($food->price, 2) }}</span>
                                                                <span class="badge bg-primary">{{ $food->category->name ?? 'No category' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">No active promotions with assigned items are available right now.</div>
        @endif
    </div>
@endsection
