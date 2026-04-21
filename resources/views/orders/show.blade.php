@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold"><i class="bi bi-receipt me-2"></i>Order #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to My Orders
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Order Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Items ({{ $order->orderItems->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div style="width: 60px; height: 60px; overflow: hidden; border-radius: 8px; background: #f8f9fa;">
                                                        @if($item->food->image)
                                                            <img src="{{ asset('images/' . $item->food->image) }}" alt="{{ $item->food->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                                                                <i class="bi bi-image"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->food->name }}</h6>
                                                        @if($item->food->category)
                                                            <span class="badge bg-primary">{{ $item->food->category->name }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>RM {{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end pe-4 fw-semibold">RM {{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order ID:</span>
                            <span class="fw-semibold">#{{ $order->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Order Date:</span>
                            <span class="fw-semibold">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Status:</span>
                            <span class="badge
                                @if($order->status == 'pending') bg-warning text-dark
                                @elseif($order->status == 'preparing') bg-info
                                @elseif($order->status == 'completed') bg-success
                                @elseif($order->status == 'completed') bg-success
                                @elseif($order->status == 'cancelled') bg-danger
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Items:</span>
                            <span class="fw-semibold">{{ $order->orderItems->sum('quantity') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total Amount:</span>
                            <span class="fw-bold text-primary h4 mb-0">RM {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                        </div>

                        @if($order->status == 'pending')
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Your order is being prepared. We'll notify you when it's ready.
                            </div>
                        @elseif($order->status == 'preparing')
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Your order is currently being prepared.
                            </div>
                        @elseif($order->status == 'ready')
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Your order is ready for pickup!
                            </div>
                        @elseif($order->status == 'completed')
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                Your order has been completed. Enjoy your meal!
                            </div>
                        @elseif($order->status == 'cancelled')
                            <div class="alert alert-danger">
                                <i class="bi bi-x-circle me-2"></i>
                                This order has been cancelled.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection