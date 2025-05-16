@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h2>Products</h2>
        </div>
        @can('manage-products')
        <div class="col-auto">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
        </div>
        @endcan
    </div>

    <!-- Search and Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-end">
                <div class="col-md">
                    <label for="keywords" class="form-label">Search Keywords</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" value="{{ request('keywords') }}" placeholder="Search products...">
                </div>
                <div class="col-md">
                    <label for="min_price" class="form-label">Min Price</label>
                    <input type="number" class="form-control" id="min_price" name="min_price" value="{{ request('min_price') }}" placeholder="Min price" min="0" step="0.01">
                </div>
                <div class="col-md">
                    <label for="max_price" class="form-label">Max Price</label>
                    <input type="number" class="form-control" id="max_price" name="max_price" value="{{ request('max_price') }}" placeholder="Max price" min="0" step="0.01">
                </div>
                <div class="col-md">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="">Select field...</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                    </select>
                </div>
                <div class="col-md">
                    <label for="sort_direction" class="form-label">Order</label>
                    <select class="form-select" id="sort_direction" name="sort_direction">
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('products.index') }}" class="btn btn-danger">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm product-card">
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:220px;overflow:hidden;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="object-fit:cover; width:100%; height:100%; min-height:180px;" onerror="this.onerror=null; this.src='https://via.placeholder.com/300x220?text=Image+Error'; console.error('Failed to load image: {{ $product->image }}');">
                    @else
                        <img src="https://via.placeholder.com/300x220?text=No+Image" alt="No image" class="img-fluid rounded" style="object-fit:cover; width:100%; height:100%; min-height:180px;">
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                    <div class="mb-2 text-primary fs-5 fw-semibold">${{ number_format($product->price, 2) }}</div>
                    <p class="card-text text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                    <div class="mt-auto">
                        @if($product->stock_quantity <= 0)
                            <button class="btn btn-outline-secondary w-100" disabled>Out of Stock</button>
                        @else
                            @if(auth()->user()->hasRole('customer'))
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary w-100">Add to Cart</button>
                                </form>
                                <form action="{{ route('cart.buyNow', $product) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">Buy Now</button>
                                </form>
                            @endif
                        @endif
                        @can('manage-products')
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning btn-sm flex-fill">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline flex-fill" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Delete</button>
                            </form>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($products->isEmpty())
    <div class="alert alert-info">
        No products available.
    </div>
    @endif

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>

<style>
.product-card:hover { box-shadow: 0 4px 24px rgba(0,0,0,0.10); transform: translateY(-2px); transition: 0.2s; }
.product-card .card-title { font-size: 1.2rem; }
</style>
@endsection
