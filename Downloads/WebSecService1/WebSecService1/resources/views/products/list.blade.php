@extends('layouts.master')
@section('title', 'Products')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Products</h2>
                    @can('add_products')
                    <a href="{{route('products.create')}}" class="btn btn-success">Add Product</a>
                    @endcan
                </div>

                <div class="card-body">
                    <form class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
                            </div>
                            <div class="col-md-2">
                                <input name="min_price" type="number" class="form-control" placeholder="Min Price" value="{{ request()->min_price }}"/>
                            </div>
                            <div class="col-md-2">
                                <input name="max_price" type="number" class="form-control" placeholder="Max Price" value="{{ request()->max_price }}"/>
                            </div>
                            <div class="col-md-2">
                                <select name="order_by" class="form-select">
                                    <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                                    <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                                    <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="order_direction" class="form-select">
                                    <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Direction</option>
                                    <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                                    <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </div>
                    </form>

                    @foreach($products as $product)
                    <div class="card mb-4 product-card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="product-image-container" style="height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <img src="{{ $product->image_url }}" class="img-fluid rounded" alt="{{$product->name}}" style="object-fit: cover; width: 100%; height: 100%;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-image-available.png') }}'">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="mb-0 fw-bold">{{$product->name}}</h3>
                                        <div class="price-tag">
                                            <span class="fs-4 fw-bold text-primary">${{number_format($product->price, 2)}}</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <p class="text-muted">{{Str::limit($product->description, 150)}}</p>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-2"><strong>Model:</strong> {{$product->model}}</div>
                                            <div class="mb-2"><strong>Code:</strong> {{$product->code}}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <strong>Stock:</strong>
                                                <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Out of stock' }}
                                                </span>
                                            </div>
                                            @if($product->hold)
                                                <div class="mb-2">
                                                    <span class="badge bg-warning text-dark">On Hold</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if(auth()->check() && auth()->user()->hasRole('customer') && $product->stock_quantity > 0 && !$product->hold)
                                                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <div class="btn-group">
                                            @can('edit_products')
                                            <a href="{{route('products.edit', $product->id)}}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            @endcan
                                            @can('delete_products')
                                            <form action="{{route('products.destroy', $product->id)}}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger ms-2" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                </button>
                                            </form>
                                            @endcan
                                            @can('hold_products')
                                                <form action="{{ route('products.hold', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn {{ $product->hold ? 'btn-outline-success' : 'btn-outline-warning' }} ms-2">
                                                        <i class="fas {{ $product->hold ? 'fa-unlock' : 'fa-lock' }} me-1"></i>
                                                        {{ $product->hold ? 'Unhold' : 'Hold' }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($products->isEmpty())
                    <div class="alert alert-info">
                        No products available.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection