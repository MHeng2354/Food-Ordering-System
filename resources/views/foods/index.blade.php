@extends('layouts.app')

@section('content')
    <section class="hero bg-white text-dark shadow-lg py-5 mb-5">
        <div class="container">
            <div class="row align-items-center gx-5">
                <div class="col-lg-8">
                    <span class="badge bg-primary text-white mb-3">Our Menu</span>
                    <h1 class="display-5 fw-bold mb-4">Explore Our Delicious Menu</h1>
                    <p class="text-muted mb-4">Browse our complete menu of authentic Malaysian dishes. Each item is carefully prepared with the finest ingredients to bring you an unforgettable dining experience.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/cart" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-cart me-2"></i>View Cart
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <!-- Category Filter -->
        <div class="mb-4">
            <h5 class="mb-3">Filter by Category</h5>
            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="{{ route('foods.index') }}" class="btn {{ !$selectedCategory ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                    <i class="bi bi-list me-1"></i>All Categories
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('foods.index', ['category_id' => $category->id]) }}" class="btn {{ $selectedCategory == $category->id ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        @if($foods->count())
            <div class="row g-4">
                @foreach($foods as $food)
                    <div class="col-lg-4 col-md-6">
                        <div class="card dish-card h-100 overflow-hidden border-0 shadow-sm" style="transition: transform 0.3s, box-shadow 0.3s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 0.5rem 1.5rem rgba(0,0,0,0.2)'" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)'">
                            <div style="position: relative; height: 200px; overflow: hidden;">
                                @if($food->image)
                                    <img src="{{ asset('images/' . $food->image) }}" class="card-img-top" alt="{{ $food->name }}" style="height: 100%; object-fit: cover; width: 100%;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <!-- Availability Badge -->
                                @if($food->availability == 'available')
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">Available</span>
                                @else
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Unavailable</span>
                                @endif

                                <!-- Admin Toggle Availability Button -->
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <button type="button" class="btn btn-sm position-absolute top-0 start-50 translate-middle-x m-2" style="background-color: rgba(255, 255, 255, 0.9);" onclick="toggleAvailability({{ $food->id }}, this)" title="Toggle Availability">
                                        <i class="bi bi-arrow-repeat text-dark"></i>
                                    </button>
                                @endif

                                <!-- Promotion Badge -->
                                @if($food->promotion && $food->promotion->discount_percentage > 0)
                                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">{{ $food->promotion->discount_percentage }}% OFF</span>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $food->name }}</h5>
                                        @if($food->category)
                                            <span class="badge bg-primary ms-2">{{ $food->category->name }}</span>
                                        @endif
                                    </div>
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($food->description, 80) }}</p>
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            @if($food->promotion && $food->promotion->discount_percentage > 0)
                                                <p class="text-primary fw-bold h5 mb-0">RM {{ number_format($food->price * (1 - $food->promotion->discount_percentage / 100), 2) }}</p>
                                                <small class="text-muted text-decoration-line-through">RM {{ number_format($food->price, 2) }}</small>
                                            @else
                                                <p class="text-primary fw-bold h5 mb-0">RM {{ number_format($food->price, 2) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('foods.show', $food->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                        @if($food->availability == 'available')
                                            <form method="POST" action="{{ route('cart.add', $food->id) }}" style="flex: 1;">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                                    <i class="bi bi-cart-plus me-1"></i>Add
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                <i class="bi bi-x-circle me-1"></i>Unavailable
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-info-circle me-2"></i>
                <h5>No dishes available yet</h5>
                <p class="text-muted mb-0">Please check back later for our menu items.</p>
            </div>
        @endif
    </div>

    <script>
        function toggleAvailability(foodId, button) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            
            fetch(`/foods/${foodId}/toggle-availability`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show updated availability
                    location.reload();
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while toggling availability');
            });
        }
    </script>
@endsection
