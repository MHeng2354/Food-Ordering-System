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
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Order ID:</dt>
                                <dd class="col-sm-8">{{ $order->id }}</dd>

                                <dt class="col-sm-4">User:</dt>
                                <dd class="col-sm-8">{{ $order->user->name }}<br><small class="text-muted">{{ $order->user->email }}</small></dd>

                                <dt class="col-sm-4">Food:</dt>
                                <dd class="col-sm-8">{{ $order->food->name }}</dd>

                                <dt class="col-sm-4">Quantity:</dt>
                                <dd class="col-sm-8">{{ $order->quantity }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Total Price:</dt>
                                <dd class="col-sm-8">RM {{ number_format($order->total_price, 2) }}</dd>

                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge
                                        @if($order->status == 'pending') badge-warning
                                        @elseif($order->status == 'preparing') badge-info
                                        @elseif($order->status == 'delivered') badge-success
                                        @elseif($order->status == 'cancelled') badge-danger
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4">Order Time:</dt>
                                <dd class="col-sm-8">{{ $order->created_at->format('M d, Y H:i:s') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
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