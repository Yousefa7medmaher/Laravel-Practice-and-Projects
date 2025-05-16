@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Order Tracking</h2>
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Order Status</h5>
                    <div class="mb-3">
                        <ul class="list-inline d-flex justify-content-between align-items-center">
                            <li class="list-inline-item {{ in_array($purchase->order_status, ['Pending','On the way','Delivered']) ? 'text-primary fw-bold' : 'text-muted' }}">
                                <i class="fas fa-clipboard-list fa-lg me-1"></i> Pending
                            </li>
                            <li class="list-inline-item {{ in_array($purchase->order_status, ['On the way','Delivered']) ? 'text-primary fw-bold' : 'text-muted' }}">
                                <i class="fas fa-truck fa-lg me-1"></i> On the way
                            </li>
                            <li class="list-inline-item {{ $purchase->order_status == 'Delivered' ? 'text-success fw-bold' : 'text-muted' }}">
                                <i class="fas fa-check-circle fa-lg me-1"></i> Delivered
                            </li>
                        </ul>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $purchase->order_status == 'Pending' ? '33' : ($purchase->order_status == 'On the way' ? '66' : '100') }}%"></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <strong>Estimated Delivery:</strong>
                        @if($purchase->estimated_delivery_time)
                            {{ \Carbon\Carbon::parse($purchase->estimated_delivery_time)->format('Y-m-d H:i') }}
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Current Status:</strong> {{ $purchase->order_status }}
                    </div>
                    @if($purchase->order_status == 'Delivered' && $purchase->delivered_at)
                        <div class="mb-2 text-success">
                            <i class="fas fa-check-circle"></i> Delivered at {{ \Carbon\Carbon::parse($purchase->delivered_at)->format('Y-m-d H:i') }}
                        </div>
                    @endif
                    @role('admin|driver')
                    <form method="POST" action="{{ route('purchases.updateStatus', $purchase) }}" class="mt-4">
                        @csrf
                        <div class="mb-2">
                            <label for="order_status" class="form-label">Update Status</label>
                            <select name="order_status" id="order_status" class="form-select">
                                <option value="Pending" {{ $purchase->order_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="On the way" {{ $purchase->order_status == 'On the way' ? 'selected' : '' }}>On the way</option>
                                <option value="Delivered" {{ $purchase->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="estimated_delivery_time" class="form-label">Estimated Delivery Time</label>
                            <input type="datetime-local" name="estimated_delivery_time" id="estimated_delivery_time" class="form-control" value="{{ $purchase->estimated_delivery_time ? \Carbon\Carbon::parse($purchase->estimated_delivery_time)->format('Y-m-d\TH:i') : '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                    @endrole
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="mb-2">
                        <strong>Order #{{ $purchase->id }}</strong>
                    </div>
                    <div class="mb-2">
                        <strong>Product:</strong> {{ optional($purchase->product)->name ?? 'Product Deleted' }}
                    </div>
                    <div class="mb-2">
                        <strong>Quantity:</strong> {{ $purchase->quantity }}
                    </div>
                    <div class="mb-2">
                        <strong>Total:</strong> E£{{ number_format($purchase->total_price, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 