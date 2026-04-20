@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 fw-bold"><i class="bi bi-percent me-2"></i>Promotions</h1>
            <p class="text-muted">Manage your active promotions and deals.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Add Promotion
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Discount</th>
                        <th>Period</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                        <tr>
                            <td>
                                @if($promotion->image)
                                    <img src="{{ asset('images/' . $promotion->image) }}" alt="Promotion Image" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>{{ $promotion->name }}</td>
                            <td>{{ number_format($promotion->discount_percentage, 0) }}%</td>
                            <td>
                                @if($promotion->start_date){{ date('M d, Y', strtotime($promotion->start_date)) }}@else N/A @endif
                                -
                                @if($promotion->end_date){{ date('M d, Y', strtotime($promotion->end_date)) }}@else N/A @endif
                            </td>
                            <td>{{ $promotion->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this promotion?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">No promotions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $promotions->links() }}
    </div>
</div>
@endsection
