@extends('layouts.app')

@section('content')
    <section class="hero bg-white text-dark shadow-lg py-5 mb-5">
        <div class="container">
            <div class="row align-items-center gx-5">
                <div class="col-lg-8">
                    <span class="badge bg-primary text-white mb-3">Payment</span>
                    <h1 class="display-5 fw-bold mb-4">Payment Confirmation</h1>
                    <p class="text-muted mb-0">Choose a payment method to complete your purchase.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Back to Cart
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
                    <!-- Order Summary Cards -->
                    <div class="card border-0 shadow-sm overflow-hidden mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Ordered Food List ({{ $totalQuantity }} items)</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Food</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
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
                                                        <div style="width: 60px; height: 60px; overflow: hidden; border-radius: 8px; background: #f8f9fa;">
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
                                                                <small class="text-muted">{{ $food->category->name }}</small>
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
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark">{{ $item['quantity'] }}</span>
                                                </td>
                                                <td class="fw-semibold">RM {{ number_format($item['line_total'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Select Payment Method</h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="paymentForm" method="POST" action="{{ route('payment.process') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check payment-option">
                                            <input class="form-check-input payment-radio" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                            <label class="form-check-label w-100 p-3 border rounded-3" for="credit_card">
                                                <i class="bi bi-credit-card me-2 text-primary payment-option-icon"></i>
                                                <div>
                                                    <strong>Credit Card</strong>
                                                    <p class="text-muted mb-0 small">Visa, Mastercard, Amex</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check payment-option">
                                            <input class="form-check-input payment-radio" type="radio" name="payment_method" id="debit_card" value="debit_card">
                                            <label class="form-check-label w-100 p-3 border rounded-3" for="debit_card">
                                                <i class="bi bi-credit-card me-2 text-success payment-option-icon"></i>
                                                <div>
                                                    <strong>Debit Card</strong>
                                                    <p class="text-muted mb-0 small">Direct bank payment</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check payment-option">
                                            <input class="form-check-input payment-radio" type="radio" name="payment_method" id="ewallet" value="ewallet">
                                            <label class="form-check-label w-100 p-3 border rounded-3" for="ewallet">
                                                <i class="bi bi-wallet2 me-2 text-warning payment-option-icon"></i>
                                                <div>
                                                    <strong>E-Wallet</strong>
                                                    <p class="text-muted mb-0 small">GCash, Grabpay</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check payment-option">
                                            <input class="form-check-input payment-radio" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                            <label class="form-check-label w-100 p-3 border rounded-3" for="bank_transfer">
                                                <i class="bi bi-bank me-2 text-info payment-option-icon"></i>
                                                <div>
                                                    <strong>Bank Transfer</strong>
                                                    <p class="text-muted mb-0 small">Direct bank transfer</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="total_price" value="{{ $subtotal }}">

                                <div class="mt-4 text-center">
                                    <button type="button" class="btn btn-primary btn-lg w-100" id="paymentSubmitBtn">
                                        <i class="bi bi-check-circle me-2"></i>Complete Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Receipt -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 90px;">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Receipt</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Items</span>
                                <span class="fw-semibold">{{ $totalQuantity }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">RM {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3 p-3 bg-light rounded-3">
                                <span class="text-muted">Delivery Fee</span>
                                <span class="fw-semibold">RM 0.00</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold">Total Amount</span>
                                <span class="fw-bold text-primary h5 mb-0" id="paymentTotalAmount">RM {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>This is a prototype payment system, no real payment required.</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                                    <i class="bi bi-arrow-left me-1"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center py-5">
                <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                <h5 class="mt-3">No items to checkout</h5>
                <p class="text-muted mb-3">Your cart is empty! Please add items before proceeding.</p>
                <a href="{{ route('foods.index') }}" class="btn btn-primary">
                    <i class="bi bi-basket me-1"></i>Browse Menu
                </a>
            </div>
        @endif
    </div>

    <style>
        .payment-option {
            position: relative;
        }

        .payment-option .form-check-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .payment-option .form-check-input:checked + .form-check-label {
            background-color: #e7f1ff !important;
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .payment-option .form-check-label {
            transition: all 0.3s ease;
            display: block !important;
            margin-bottom: 0;
            cursor: pointer;
        }

        .payment-option .form-check-label:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .payment-option-icon {
            font-size: 1.5rem;
        }
    </style>

    <script>
        const paymentSubmitBtn = document.getElementById('paymentSubmitBtn');
        const paymentForm = document.getElementById('paymentForm');
        const paymentTotalAmount = document.getElementById('paymentTotalAmount');

        paymentSubmitBtn.addEventListener('click', function () {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');

            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }

            const methodLabel = document.querySelector(`label[for="${selectedMethod.id}"] strong`).textContent;
            const totalAmount = paymentTotalAmount ? paymentTotalAmount.textContent.trim() : 'RM 0.00';

            if (confirm(`Confirm payment of ${totalAmount} using ${methodLabel}?`)) {
                paymentForm.submit();
            }
        });
    </script>
@endsection
