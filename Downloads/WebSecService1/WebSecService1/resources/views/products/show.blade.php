@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <div class="row align-items-center">
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow" style="width: 100%; max-width: 320px; height: 220px; object-fit: cover;" onerror="this.onerror=null; this.src='https://via.placeholder.com/320x220?text=Image+Error'; console.error('Failed to load image: {{ $product->image }}');">
                        @else
                            <img src="https://via.placeholder.com/320x220?text=No+Image" alt="No image" class="img-fluid rounded shadow" style="width: 100%; max-width: 320px; height: 220px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="col-md-7">
                        <h1 class="fw-bold mb-2" style="font-size:2rem;">{{ $product->name }}</h1>
                        <div class="mb-2 text-primary fs-3 fw-semibold">${{ number_format($product->price, 2) }}</div>
                        <div class="mb-2">
                            <span class="badge bg-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }}">
                                {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                ({{ $product->stock_quantity }} available)
                            </span>
                        </div>
                        <div class="mb-2 text-muted">Code: <span class="fw-semibold">{{ $product->code }}</span></div>
                        <div class="mb-2 text-muted">Model: <span class="fw-semibold">{{ $product->model }}</span></div>
                        <div class="mb-3">{{ $product->description }}</div>
                        @if(auth()->check() && auth()->user()->hasRole('customer'))
                            <!-- Buy Button with Icon -->
                            <button type="button" class="btn btn-primary btn-lg mt-2" data-bs-toggle="modal" data-bs-target="#purchaseModal">
                                <i class="fas fa-truck"></i> Buy
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @if(auth()->check() && auth()->user()->hasRole('customer') && $product->hasBeenPurchasedBy(auth()->user()))
                <div class="mt-3">
                    <form action="{{ route('products.like', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            @if($product->isLikedBy(auth()->user()))
                                <i class="fas fa-heart text-danger"></i> Unlike
                            @else
                                <i class="far fa-heart"></i> Like
                            @endif
                            <span class="badge bg-secondary">{{ $product->likes_count ?? 0 }}</span>
                        </button>
                    </form>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
                @can('manage-products')
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit Product</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete Product</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- Purchase Modal -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('purchases.store', $product) }}" id="purchaseForm">
      @csrf
      <input type="hidden" name="quantity" value="1">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="purchaseModalLabel">Complete Your Purchase</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Delivery Method -->
          <div class="mb-3">
            <label class="form-label">Delivery Method</label>
            <div>
              <input type="radio" class="btn-check" name="delivery_method" id="deliveryHome" value="home" autocomplete="off" checked>
              <label class="btn btn-outline-primary me-2" for="deliveryHome">Home Delivery</label>
              <input type="radio" class="btn-check" name="delivery_method" id="deliveryPickup" value="pickup" autocomplete="off">
              <label class="btn btn-outline-primary" for="deliveryPickup">Pickup from Branch</label>
            </div>
          </div>
          <div class="mb-3 d-none" id="addressField">
            <label for="delivery_address" class="form-label">Delivery Address</label>
            <input type="text" class="form-control" name="delivery_address" id="delivery_address" placeholder="Enter your address">
          </div>
          <div class="mb-3 d-none" id="branchField">
            <label for="pickup_branch" class="form-label">Select Branch</label>
            <select class="form-select" name="pickup_branch" id="pickup_branch">
              <option value="Branch 1">Branch 1</option>
              <option value="Branch 2">Branch 2</option>
              <option value="Branch 3">Branch 3</option>
            </select>
          </div>
          <!-- Color Selection -->
          <div class="mb-3">
            <label class="form-label">Choose Color</label>
            <div class="d-flex gap-3">
              <input type="radio" class="btn-check" name="color" id="colorRed" value="red" autocomplete="off" checked>
              <label class="btn" style="background:#dc3545;color:white;" for="colorRed">Red</label>
              <input type="radio" class="btn-check" name="color" id="colorBlue" value="blue" autocomplete="off">
              <label class="btn" style="background:#0d6efd;color:white;" for="colorBlue">Blue</label>
              <input type="radio" class="btn-check" name="color" id="colorBlack" value="black" autocomplete="off">
              <label class="btn" style="background:#222;color:white;" for="colorBlack">Black</label>
            </div>
          </div>
          <!-- Payment Method -->
          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <div>
              <input type="radio" class="btn-check" name="payment_method" id="payCOD" value="cod" autocomplete="off" checked>
              <label class="btn btn-outline-success me-2" for="payCOD">Cash on Delivery</label>
              <input type="radio" class="btn-check" name="payment_method" id="payCard" value="card" autocomplete="off">
              <label class="btn btn-outline-success" for="payCard">Credit Card</label>
            </div>
          </div>
          <div class="mb-3 d-none" id="cardField">
            <label for="card_last4" class="form-label">Card Number (Mock)</label>
            <input type="text" class="form-control" name="card_last4" id="card_last4" maxlength="4" placeholder="Last 4 digits">
            <small class="text-muted">This is a mock field. No real payment will be processed.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm Purchase</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    function toggleFields() {
      const home = document.getElementById('deliveryHome').checked;
      document.getElementById('addressField').classList.toggle('d-none', !home);
      document.getElementById('branchField').classList.toggle('d-none', home);
    }
    document.getElementById('deliveryHome').addEventListener('change', toggleFields);
    document.getElementById('deliveryPickup').addEventListener('change', toggleFields);
    toggleFields();

    function toggleCardField() {
      const card = document.getElementById('payCard').checked;
      document.getElementById('cardField').classList.toggle('d-none', !card);
    }
    document.getElementById('payCOD').addEventListener('change', toggleCardField);
    document.getElementById('payCard').addEventListener('change', toggleCardField);
    toggleCardField();
  });
</script>
@endsection