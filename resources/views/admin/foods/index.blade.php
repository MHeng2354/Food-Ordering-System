@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold"><i class="bi bi-list-check me-2"></i>Menu Management</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.foods.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Add New Food
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.foods.index') }}">
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or category...">
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <select name="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Foods Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Promotion</th>
                        <th>Availability</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($foods as $food)
                        <tr>
                            <td>
                                <strong>{{ $food->name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($food->description, 50) }}</small>
                            </td>
                            <td>
                                <strong>${{ number_format($food->price, 2) }}</strong>
                            </td>
                            <td>
                                {{ $food->category->name ?? 'N/A' }}
                            </td>
                            <td>
                                @if($food->promotion)
                                    {{ $food->promotion->name }} ({{ $food->promotion->discount_percentage }}%)
                                @else
                                    No Promotion
                                @endif
                            </td>
                            <td>
                                @if($food->availability)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $food->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">No foods found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
