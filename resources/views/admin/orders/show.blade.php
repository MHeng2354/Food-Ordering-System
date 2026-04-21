@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold"><i class="bi bi-receipt me-2"></i>Order Details #{{ $order->id }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
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
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order ID:</strong><br>
                        {{ $order->id }}
                    </div>
                    <div class="mb-3">
                        <strong>Customer:</strong><br>
                        {{ $order->user->name }}<br>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </div>
                    <div class="mb-3">
                        <strong>Order Date:</strong><br>
                        {{ $order->created_at->format('M d, Y H:i:s') }}
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge mt-2
                            @if($order->status == 'pending') bg-warning text-dark
                            @elseif($order->status == 'preparing') bg-info
                            @elseif($order->status == 'delivered') bg-success
                            @elseif($order->status == 'cancelled') bg-danger
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <strong>Total Items:</strong><br>
                        {{ $order->orderItems->sum('quantity') }}
                    </div>
                    <div class="mb-3">
                        <strong>Total Amount:</strong><br>
                        <span class="text-primary h5">RM {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-1"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection