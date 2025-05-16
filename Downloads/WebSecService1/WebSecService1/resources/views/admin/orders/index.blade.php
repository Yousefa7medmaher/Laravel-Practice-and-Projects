@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Management</h2>
        <div class="d-flex gap-2">
            <select class="form-select" id="statusFilter">
                <option value="">All Statuses</option>
                <option value="Pending">Pending</option>
                <option value="On the way">On the way</option>
                <option value="Delivered">Delivered</option>
            </select>
            <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Est. Delivery</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                        <tr>
                            <td>#{{ $purchase->id }}</td>
                            <td>
                                <div>{{ $purchase->user->name }}</div>
                                <small class="text-muted">{{ $purchase->user->email }}</small>
                            </td>
                            <td>
                                <div>{{ optional($purchase->product)->name ?? 'Product Deleted' }}</div>
                                @if(optional($purchase->product)->image)
                                    <img src="{{ asset('storage/' . optional($purchase->product)->image) }}" 
                                         alt="Product" 
                                         style="width:40px; height:40px; object-fit:cover; border-radius:4px;">
                                @endif
                            </td>
                            <td>{{ $purchase->quantity }}</td>
                            <td>${{ number_format($purchase->total_price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $purchase->order_status === 'Delivered' ? 'success' : ($purchase->order_status === 'On the way' ? 'primary' : 'warning') }}">
                                    {{ $purchase->order_status }}
                                </span>
                            </td>
                            <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($purchase->estimated_delivery_time)
                                    {{ \Carbon\Carbon::parse($purchase->estimated_delivery_time)->format('Y-m-d H:i') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('purchases.tracking', $purchase) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateStatusModal{{ $purchase->id }}">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                </div>

                                <!-- Update Status Modal -->
                                <div class="modal fade" id="updateStatusModal{{ $purchase->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('purchases.updateStatus', $purchase) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Order #{{ $purchase->id }} Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Order Status</label>
                                                        <select name="order_status" class="form-select">
                                                            <option value="Pending" {{ $purchase->order_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="On the way" {{ $purchase->order_status == 'On the way' ? 'selected' : '' }}>On the way</option>
                                                            <option value="Delivered" {{ $purchase->order_status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Estimated Delivery Time</label>
                                                        <input type="datetime-local" 
                                                               name="estimated_delivery_time" 
                                                               class="form-control"
                                                               value="{{ $purchase->estimated_delivery_time ? \Carbon\Carbon::parse($purchase->estimated_delivery_time)->format('Y-m-d\\TH:i') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');

    function applyFilters() {
        const status = statusFilter.value;
        const date = dateFilter.value;
        let url = new URL(window.location.href);
        
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        if (date) url.searchParams.set('date', date);
        else url.searchParams.delete('date');
        
        window.location.href = url.toString();
    }

    statusFilter.addEventListener('change', applyFilters);
    dateFilter.addEventListener('change', applyFilters);
});
</script>
@endpush
@endsection 