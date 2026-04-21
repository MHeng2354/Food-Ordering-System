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
            <h5 class="mb-3">Browse by Category</h5>
            <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach($categories as $category)
                    <a href="#category-{{ $category->id }}" class="btn btn-outline-primary btn-sm">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        @if($foodsByCategory->count())
            <!-- Category Sections -->
            @foreach($categories as $category)
                @if(isset($foodsByCategory[$category->name]) && $foodsByCategory[$category->name]->count() > 0)
                    <div id="category-{{ $category->id }}" class="mb-5">
                        <h3 class="mb-4 text-primary">{{ $category->name }}</h3>
                        <div class="row g-4">
                            @foreach($foodsByCategory[$category->name] as $food)
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
                                                        <button
                                                            type="button"
                                                            class="btn btn-primary btn-sm"
                                                            style="flex: 1;"
                                                            onclick="openAddToCartModal(this)"
                                                            data-id="{{ $food->id }}"
                                                            data-name="{{ e($food->name) }}"
                                                        >
                                                            <i class="bi bi-cart-plus me-1"></i>Add
                                                        </button>
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
                    </div>
                @endif
            @endforeach
        @else
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-info-circle me-2"></i>
                <h5>No dishes available yet</h5>
                <p class="text-muted mb-0">Please check back later for our menu items.</p>
            </div>
        @endif
    </div>

    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">How many <span class="fw-semibold" id="addToCartFoodName"></span> would you like to add?</p>
                    <label for="addToCartQuantity" class="form-label">Quantity</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustAddQty(-1)">-</button>
                        <input type="number" id="addToCartQuantity" class="form-control text-center" value="1" min="1" max="99">
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustAddQty(1)">+</button>
                    </div>
                    <div class="form-text">Minimum 1 item, maximum 99 items per add action.</div>
                    <div class="alert mt-3 mb-0 d-none" id="addToCartFeedback"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmAddToCartBtn">
                        <i class="bi bi-cart-check me-1"></i>Add Items
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="menuActionToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="menuActionToastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        let selectedFoodId = null;
        let selectedFoodName = '';
        let addToCartModal = null;
        let menuActionToast = null;

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        function getAddToCartModal() {
            if (!addToCartModal) {
                addToCartModal = new bootstrap.Modal(document.getElementById('addToCartModal'));
            }

            return addToCartModal;
        }

        function getMenuActionToast() {
            if (!menuActionToast) {
                const menuActionToastElement = document.getElementById('menuActionToast');
                menuActionToast = new bootstrap.Toast(menuActionToastElement, { delay: 2200 });
            }

            return menuActionToast;
        }

        function updateCartCount(cartCount) {
            const cartLink = document.querySelector('a.nav-link[href="/cart"]');
            if (cartLink && Number.isInteger(cartCount)) {
                cartLink.textContent = `Cart (${cartCount})`;
            }
        }

        function showMenuToast(message, isSuccess = true) {
            const toastBody = document.getElementById('menuActionToastBody');
            toastBody.textContent = message;

            const menuActionToastElement = document.getElementById('menuActionToast');
            menuActionToastElement.classList.remove('text-bg-success', 'text-bg-danger');
            menuActionToastElement.classList.add(isSuccess ? 'text-bg-success' : 'text-bg-danger');

            getMenuActionToast().show();
        }

        function openAddToCartModal(button) {
            selectedFoodId = button.dataset.id;
            selectedFoodName = button.dataset.name || 'this item';

            document.getElementById('addToCartFoodName').textContent = selectedFoodName;
            document.getElementById('addToCartQuantity').value = 1;

            const feedback = document.getElementById('addToCartFeedback');
            feedback.className = 'alert mt-3 mb-0 d-none';
            feedback.textContent = '';

            getAddToCartModal().show();
        }

        function adjustAddQty(change) {
            const input = document.getElementById('addToCartQuantity');
            const current = parseInt(input.value || '1', 10);
            const next = Math.min(99, Math.max(1, current + change));
            input.value = next;
        }

        document.getElementById('confirmAddToCartBtn').addEventListener('click', async function () {
            const qtyInput = document.getElementById('addToCartQuantity');
            const quantity = Math.min(99, Math.max(1, parseInt(qtyInput.value || '1', 10)));
            qtyInput.value = quantity;

            const feedback = document.getElementById('addToCartFeedback');
            const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

            this.disabled = true;

            try {
                const response = await fetch(`{{ url('/cart/add') }}/${selectedFoodId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ quantity })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to add item to cart.');
                }

                updateCartCount(data.cartCount);
                getAddToCartModal().hide();
                showMenuToast(data.message || 'Food added to cart successfully!', true);
            } catch (error) {
                feedback.className = 'alert alert-danger mt-3 mb-0';
                feedback.textContent = error.message || 'An unexpected error occurred.';
            } finally {
                this.disabled = false;
            }
        });

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
