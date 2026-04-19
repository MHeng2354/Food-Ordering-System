@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 fw-bold"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h1>
            <p class="text-muted">Manage your restaurant's admin accounts and menu items.</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-1">Admin Accounts</h6>
                            <h3 class="mb-0">{{ $adminCount }}</h3>
                        </div>
                        <i class="bi bi-person-badge text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <small class="text-muted">Total admin users</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-1">Menu Items</h6>
                            <h3 class="mb-0">{{ $menuItems }}</h3>
                        </div>
                        <i class="bi bi-list-check text-success" style="font-size: 2rem;"></i>
                    </div>
                    <small class="text-muted">Available foods</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="row g-4">
        <!-- User Management -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>User Management</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted small mb-3">Add, edit, or delete admin accounts</p>
                    <ul class="list-unstyled small mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Create admin accounts</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Edit admin permissions</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Remove admin access</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Manage account security</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-right me-1"></i>Manage Admin Users
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Management -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Menu Management</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted small mb-3">Add, update, or remove food items and promotions</p>
                    <ul class="list-unstyled small mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Add new menu items</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Update prices and descriptions</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Manage availability</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Create promotions and deals</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ route('admin.foods.index') }}" class="btn btn-success">
                            <i class="bi bi-arrow-right me-1"></i>Manage Menu Items
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotions Management -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark border-bottom">
                    <h5 class="mb-0"><i class="bi bi-ticket-perforated me-2"></i>Promotion Management</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted small mb-3">Create, edit, or delete promotional campaigns for your menu.</p>
                    <ul class="list-unstyled small mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Create new promotions</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Set discount dates</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Edit active promotions</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Remove expired deals</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ route('admin.promotions.index') }}" class="btn btn-warning text-dark">
                            <i class="bi bi-arrow-right me-1"></i>Manage Promotions
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Management -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Management</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted small mb-3">View and manage customer orders, update order status.</p>
                    <ul class="list-unstyled small mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle text-success me-2"></i>View all orders</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Update order status</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Track order progress</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Manage cancellations</li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-info">
                            <i class="bi bi-arrow-right me-1"></i>Manage Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
