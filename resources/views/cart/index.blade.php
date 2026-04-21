@extends('layouts.app')

@section('content')
<!-- Page Header -->
	<section class="py-4">
        <div class="container" style="padding: 1%;">
			<div class="d-flex justify-content-between align-items-end">
				<div>
                    <span class="badge bg-primary text-white mb-3">Cart</span>
					<h1 class="display-6 fw-bold mb-2">Cart Inventory</h1>
					<p class="text-muted mb-0">Update quantities, remove items, or continue browsing before checkout.</p>
                </div>
				<div>
					<a href="{{ route('foods.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Menu
                    </a>
                </div>
            </div>
        </div>
    </section>
<! -- End of Page Header -->
	<div class="container py-4">
<!-- Load Session -->
		@if(session('success'))
			<div class="alert alert-success">
				<i class="bi bi-check-circle me-2"></i>{{ session('success') }}
			</div>
		@endif

		@if(session('error'))
			<div class="alert alert-danger">
				<i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
			</div>
		@endif
<!-- End of Session -->


<!-- Cart Items -->
		@if($cartItems->count())
			<div class="row">
				<div class="col">
					<div class="card border-dark-subtle shadow-sm overflow-hidden">
						<div class="card-header bg-white border-0 py-3">
							<h5 class="border-bottom border-dark">Items in Cart ({{ $totalQuantity }})</h5>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table align-middle mb-0">
									<thead class="table-secondary">
										<tr>
											<th class="ps-3">Food</th>
											<th>Price</th>
											<th style="width: 160px;">Quantity</th>
											<th>Subtotal</th>
											<th class="text-end pe-3">Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($cartItems as $item)
											<tr>
												<td class="ps-3">
													<div class="d-flex align-items-center gap-3">
														<div class="rounded-5 overflow-hidden" style="width: 70px; height: 70px;">
															@if($item['food']->image)
																<img src="{{ asset('images/' . $item['food']->image) }}" alt="{{ $item['food']->name }}" style="width: 100%; height: 100%; object-fit: cover;">
															@else
																<div class="h-100 d-flex align-items-center justify-content-center text-muted">
																	<i class="bi bi-image"></i>
																</div>
															@endif
														</div>
														<div>
															<h6>{{ $item['food']->name }}</h6>
															@if($item['food']->category)
																<span class="badge bg-primary">{{ $item['food']->category->name }}</span>
															@endif
														</div>
													</div>
												</td>
												<td>
													@if($item['has_promotion'])
														<div class="fw-bold text-primary">RM {{ number_format($item['unit_price'], 2) }}</div>
														<small class="text-muted text-decoration-line-through">RM {{ number_format($item['food']->price, 2) }}</small>
														<div><small class="text-success">Promo applied</small></div>
													@else
														<div class="fw-bold text-primary">RM {{ number_format($item['unit_price'], 2) }}</div>
													@endif
												</td>
												<td>
													<form method="POST" action="{{ route('cart.update', $item['food']->id) }}">
														@csrf
														<div class="input-group input-group-sm">
															<input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control">
															<button class="btn btn-outline-primary" type="submit" title="Update quantity">
																<i class="bi bi-arrow-repeat"></i>
															</button>
														</div>
													</form>
												</td>
												<td class="fw-semibold">RM {{ number_format($item['line_total'], 2) }}</td>
												<td class="text-end pe-3">
													<form method="POST" action="{{ route('cart.remove', $item['food']->id) }}">
														@csrf
														<button type="submit" class="btn btn-sm btn-outline-danger">
															<i class="bi bi-trash me-1"></i>Remove
														</button>
													</form>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
<!-- Order Summary Sidebar -->
				<div class="col-lg-4">
					<div class="card border-dark-subtle">
						<div class="card-body p-4">
							<h5 class="mb-4">Order Summary</h5>

							<div class="d-flex justify-content-between mb-2">
								<span class="text-muted">Total Items</span>
								<span class="fw-semibold">{{ $totalQuantity }}</span>
							</div>
							<div class="d-flex justify-content-between mb-3">
								<span class="text-muted">Subtotal</span>
								<span class="fw-semibold">RM {{ number_format($subtotal, 2) }}</span>
							</div>

							<hr>

							<div class="d-flex justify-content-between mb-4">
								<span class="fw-bold">Total</span>
								<span class="fw-bold text-primary h5 mb-0">RM {{ number_format($subtotal, 2) }}</span>
							</div>

							<a href="{{ route('cart.checkout') }}" class="btn btn-primary w-100">
								<i class="bi bi-credit-card me-2"></i>Proceed to Payment
							</a>
						</div>
					</div>
				</div>
<!-- End of Order Summary Sidebar -->
			</div>
		@else
			<div class="alert alert-info text-center py-5">
				<i class="bi bi-cart-x" style="font-size: 2rem;"></i>
				<h5 class="mt-3">Your cart is empty</h5>
				<p class="text-muted mb-3">Add something from the menu and it will show up here.</p>
				<a href="{{ route('foods.index') }}" class="btn btn-primary">
					<i class="bi bi-basket me-1"></i>Browse Menu
				</a>
			</div>
		@endif
<!-- End of Cart Items -->
	</div>
@endsection
