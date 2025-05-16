@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">My Purchases</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @php
                        $totalSpent = $purchases->sum(function($purchase) {
                            return $purchase->total_price;
                        });
                    @endphp

                    <div class="alert alert-info mb-3">
                        Total Spent: ${{ number_format($totalSpent, 2) }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Quantity</th>
                                    <th>Price per Unit</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ optional($purchase->product)->name ?? 'Product Deleted' }}</td>
                                    <td>
                                        @if(optional($purchase->product)->image)
                                            <img src="{{ asset('storage/' . optional($purchase->product)->image) }}" alt="{{ optional($purchase->product)->name ?? 'Product' }}" style="width:60px; height:40px; object-fit:cover; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                                        @else
                                            <img src="https://via.placeholder.com/60x40?text=No+Image" alt="No image" style="width:60px; height:40px; object-fit:cover; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                                        @endif
                                    </td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>${{ number_format($purchase->price_per_unit ?? ($purchase->total_price / max($purchase->quantity,1)), 2) }}</td>
                                    <td>${{ number_format($purchase->total_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $purchase->order_status === 'Delivered' ? 'success' : ($purchase->order_status === 'On the way' ? 'primary' : 'warning') }}">
                                            {{ $purchase->order_status }}
                                        </span>
                                        @if($purchase->estimated_delivery_time)
                                            <div class="small text-muted mt-1">
                                                Est. delivery: {{ \Carbon\Carbon::parse($purchase->estimated_delivery_time)->format('M d, H:i') }}
                                            </div>
                                        @endif
                                        <a href="{{ route('purchases.tracking', $purchase) }}" class="btn btn-sm btn-outline-primary mt-1">
                                            <i class="fas fa-truck"></i> Track Order
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No purchases found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 