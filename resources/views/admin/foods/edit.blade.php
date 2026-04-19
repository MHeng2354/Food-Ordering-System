@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Food Item</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Food Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter food name" value="{{ old('name', $food->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Enter food description">{{ old('description', $food->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price *</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="0.00" step="0.01" value="{{ old('price', $food->price) }}" required>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Availability</label>
                                <select name="availability" class="form-select @error('availability') is-invalid @enderror">
                                    <option value="available" {{ old('availability', $food->availability) == "available" ? 'selected' : '' }}>Available</option>
                                    <option value="unavailable" {{ old('availability', $food->availability) == "unavailable" ? 'selected' : '' }}>Unavailable</option>
                                </select>
                                @error('availability')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            @if($food->image)
                                <div class="mb-2">
                                    <small class="text-muted">Current image:</small>
                                    <br>
                                    <img src="{{ asset('storage/' . $food->image) }}" alt="{{ $food->name }}" style="max-width: 200px; max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $food->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Promotion</label>
                                <select name="promotion_id" class="form-select @error('promotion_id') is-invalid @enderror">
                                    <option value="">No Promotion</option>
                                    @foreach($promotions as $promotion)
                                        <option value="{{ $promotion->id }}" {{ old('promotion_id', $food->promotion_id) == $promotion->id ? 'selected' : '' }}>{{ $promotion->name }} ({{ $promotion->discount_percentage }}% off)</option>
                                    @endforeach
                                </select>
                                @error('promotion_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update Food Item
                            </button>
                            <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
