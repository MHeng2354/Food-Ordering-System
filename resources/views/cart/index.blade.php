@extends('layouts.app')

@section('content')
	<section class="hero bg-white text-dark shadow-lg py-5 mb-5">
		<div class="container">
			<div class="row align-items-center gx-5">
				<div class="col-lg-8">
					<span class="badge bg-primary text-white mb-3">Your Cart</span>
					<h1 class="display-5 fw-bold mb-4">Cart Inventory</h1>
					<p class="text-muted mb-0">Review all foods added to your cart, update quantities, and confirm your order details before checkout.</p>
				</div>
				<div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
					<a href="{{ route('foods.index') }}" class="btn btn-outline-primary btn-lg">
						<i class="bi bi-arrow-left me-2"></i>Continue Shopping
					</a>
				</div>
			</div>
		</div>
	</section>

	<div class="container py-4">
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

		@if($cartItems->count())
			<div class="row g-4">
				<div class="col-lg-8">
					<div class="card border-0 shadow-sm overflow-hidden">
						<div class="card-header bg-white border-0 py-3">
							<h5 class="mb-0">Foods In Your Cart ({{ $totalQuantity }} items)</h5>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table align-middle mb-0">
									<thead class="table-light">
										<tr>
											<th class="ps-4">Food</th>
											<th>Price</th>
											<th style="width: 160px;">Quantity</th>
											<th>Subtotal</th>
											<th class="text-end pe-4">Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach($cartItems as $item)
											@php
												$food = $item['food'];
											@endphp
											<tr>
												<td class="ps-4">
													<div class="d-flex align-items-center gap-3">
														<div style="width: 72px; height: 72px; overflow: hidden; border-radius: 12px; background: #f8f9fa;">
															@if($food->image)
																<img src="{{ asset('images/' . $food->image) }}" alt="{{ $food->name }}" style="width: 100%; height: 100%; object-fit: cover;">
															@else
																<div class="h-100 d-flex align-items-center justify-content-center text-muted">
																	<i class="bi bi-image"></i>
																</div>
															@endif
														</div>
														<div>
															<h6 class="mb-1">{{ $food->name }}</h6>
															@if($food->category)
																<span class="badge bg-primary">{{ $food->category->name }}</span>
															@endif
														</div>
													</div>
												</td>
												<td>
													@if($item['has_promotion'])
														<div class="fw-bold text-primary">RM {{ number_format($item['unit_price'], 2) }}</div>
														<small class="text-muted text-decoration-line-through">RM {{ number_format($food->price, 2) }}</small>
													@else
														<div class="fw-bold text-primary">RM {{ number_format($item['unit_price'], 2) }}</div>
													@endif
												</td>
												<td>
													<form method="POST" action="{{ route('cart.update', $food->id) }}">
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
												<td class="text-end pe-4">
													<form method="POST" action="{{ route('cart.remove', $food->id) }}">
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

				<div class="col-lg-4">
					<div class="card border-0 shadow-sm sticky-top" style="top: 90px;">
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
			</div>
		@else
			<div class="alert alert-info text-center py-5">
				<i class="bi bi-cart-x" style="font-size: 2rem;"></i>
				<h5 class="mt-3">Your cart is currently empty</h5>
				<p class="text-muted mb-3">Add your favorite dishes from our menu to get started.</p>
				<a href="{{ route('foods.index') }}" class="btn btn-primary">
					<i class="bi bi-basket me-1"></i>Browse Menu
				</a>
			</div>
		@endif
	</div>
@endsection
