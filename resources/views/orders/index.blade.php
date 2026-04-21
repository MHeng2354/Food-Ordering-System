@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold"><i class="bi bi-receipt me-2"></i>My Orders</h1>
            <a href="{{ route('foods.index') }}" class="btn btn-primary">
                <i class="bi bi-plus me-1"></i>Order More Food
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($orders->count())
            <div class="row g-4">
                @foreach($orders as $order)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Order #{{ $order->id }}</h5>
                                        <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <span class="badge
                                        @if($order->status == 'pending') bg-warning text-dark
                                        @elseif($order->status == 'preparing') bg-info
                                        @elseif($order->status == 'completed') bg-success
                                        @elseif($order->status == 'cancelled') bg-danger
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div style="width: 60px; height: 60px; overflow: hidden; border-radius: 8px; background: #f8f9fa;">
                                        @if($order->orderItems->first() && $order->orderItems->first()->food->image)
                                            <img src="{{ asset('images/' . $order->orderItems->first()->food->image) }}" alt="Order item" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        @if($order->orderItems->count() == 1)
                                            @php $item = $order->orderItems->first(); @endphp
                                            <h6 class="mb-1">{{ $item->food->name }}</h6>
                                            @if($item->food->category)
                                                <span class="badge bg-primary">{{ $item->food->category->name }}</span>
                                            @endif
                                            <div class="text-muted small">
                                                Quantity: {{ $item->quantity }} × RM {{ number_format($item->price, 2) }}
                                            </div>
                                        @else
                                            <h6 class="mb-1">{{ $order->orderItems->count() }} Items</h6>
                                            <div class="text-muted small">
                                                @foreach($order->orderItems->take(2) as $item)
                                                    <div>{{ $item->food->name }} ({{ $item->quantity }}×)</div>
                                                @endforeach
                                                @if($order->orderItems->count() > 2)
                                                    <div>+{{ $order->orderItems->count() - 2 }} more...</div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-primary">RM {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</strong>
                                    </div>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-receipt-x" style="font-size: 3rem; color: #6c757d;"></i>
                <h4 class="mt-3 text-muted">No orders yet</h4>
                <p class="text-muted mb-4">You haven't placed any orders yet. Start by browsing our menu!</p>
                <a href="{{ route('foods.index') }}" class="btn btn-primary">
                    <i class="bi bi-shop me-1"></i>Browse Menu
                </a>
            </div>
        @endif
    </div>
@endsection