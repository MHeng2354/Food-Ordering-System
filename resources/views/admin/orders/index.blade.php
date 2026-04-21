@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold"><i class="bi bi-receipt me-2"></i>Order Management</h1>
        </div>
        <div class="col-md-4 text-end">
            <!-- No add button for orders -->
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by user or food...">
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Items</th>
                        <th>Total Qty</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Order Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}<br><small class="text-muted">{{ $order->user->email }}</small></td>
                        <td>
                            @if($order->orderItems->count() == 1)
                                {{ $order->orderItems->first()->food->name }}
                            @else
                                <div class="small">
                                    {{ $order->orderItems->count() }} items:
                                    @foreach($order->orderItems->take(2) as $item)
                                        <div>{{ $item->food->name }} ({{ $item->quantity }}×)</div>
                                    @endforeach
                                    @if($order->orderItems->count() > 2)
                                        <div>+{{ $order->orderItems->count() - 2 }} more</div>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td>{{ $order->orderItems->sum('quantity') }}</td>
                        <td>RM {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</td>
                        <td>
                            <span class="badge
                                @if($order->status == 'pending') bg-warning text-dark
                                @elseif($order->status == 'preparing') bg-info
                                @elseif($order->status == 'completed') bg-success
                                @elseif($order->status == 'cancelled') bg-danger
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection