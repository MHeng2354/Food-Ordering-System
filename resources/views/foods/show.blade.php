@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <a href="{{ route('foods.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Menu
        </a>

        <div class="row g-4">
            <!-- Image Section -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden">
                    @if($food->image)
                        <img src="{{ asset('images/' . $food->image) }}" class="card-img" alt="{{ $food->name }}" style="height: 400px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Details Section -->
            <div class="col-lg-6">
                <div>
                    <div class="mb-3">
                        @if($food->category)
                            <span class="badge bg-primary mb-2">{{ $food->category->name }}</span>
                        @endif
                        <h1 class="display-5 fw-bold mb-2">{{ $food->name }}</h1>
                        
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div>
                                @if($food->promotion && $food->promotion->discount_percentage > 0)
                                    <span class="text-primary h3 fw-bold">RM {{ number_format($food->price * (1 - $food->promotion->discount_percentage / 100), 2) }}</span>
                                    <div>
                                        <small class="text-muted text-decoration-line-through">RM {{ number_format($food->price, 2) }}</small>
                                        <span class="badge bg-warning text-dark ms-2">{{ $food->promotion->discount_percentage }}% OFF</span>
                                    </div>
                                @else
                                    <span class="text-primary h3 fw-bold">RM {{ number_format($food->price, 2) }}</span>
                                @endif
                            </div>
                        </div>

                        @if($food->availability == 'available')
                            <span class="badge bg-success p-2">✓ Available</span>
                        @else
                            <span class="badge bg-danger p-2">✗ Currently Unavailable</span>
                        @endif
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">Description</h5>
                        <p class="text-muted">{{ $food->description }}</p>
                    </div>

                    @if($food->availability == 'available')
                        <div class="mb-4">
                            <form method="POST" action="{{ route('cart.add', $food->id) }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <div class="input-group" style="width: 150px;">
                                        <button class="btn btn-outline-secondary" type="button" onclick="decrementQuantity()">-</button>
                                        <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="99">
                                        <button class="btn btn-outline-secondary" type="button" onclick="incrementQuantity()">+</button>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-info-circle me-2"></i>
                            This item is currently unavailable. Please check back later.
                        </div>
                    @endif

                    <hr>

                    <div>
                        <a href="{{ route('foods.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row g-4 mt-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Customer Reviews</h5>

                        @if($food->reviews && $food->reviews->count() > 0)
                            <div class="reviews-list">
                                @foreach($food->reviews as $review)
                                    <div class="review-item border-bottom pb-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                                <div class="text-warning mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="bi bi-star-fill"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="text-muted ms-2">{{ $review->rating }}/5</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="text-muted mb-0">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-4">
                                <i class="bi bi-chat-left-text me-2"></i>No reviews yet. Be the first to review!
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function incrementQuantity() {
            const input = document.getElementById('quantity');
            input.value = Math.min(parseInt(input.value) + 1, 99);
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity');
            input.value = Math.max(parseInt(input.value) - 1, 1);
        }
    </script>
@endsection
