@extends('layouts.master')

@section('title', 'Manage Orders')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2 fw-bold">📦 Manage Orders</h2>
            <p class="text-secondary mb-0">View and manage all customer orders</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> No orders found.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($orders as $order)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">Order #{{ $order->id }}</h5>
                                    <p class="text-secondary small mb-0">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ ucfirst($order->status) }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="dropdown-item-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="dropdown-item {{ $order->status === 'pending' ? 'active' : '' }}">
                                                    Pending
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="dropdown-item-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="dropdown-item {{ $order->status === 'processing' ? 'active' : '' }}">
                                                    Processing
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="dropdown-item-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item {{ $order->status === 'completed' ? 'active' : '' }}">
                                                    Completed
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST" class="dropdown-item-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item {{ $order->status === 'cancelled' ? 'active' : '' }}">
                                                    Cancelled
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @if ($order->user)
                            <div class="avatar-circle-sm me-2">
                                <span class="avatar-text-sm">{{ substr($order->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="fw-medium">{{ $order->user->name }}</div>
                                <small class="text-secondary">{{ $order->user->email }}</small>
                            </div>
                            @else
                                <div class="avatar-circle-sm me-2">
                                    <span class="avatar-text-sm">?</span>
                                </div>
                                <div>
                                    <div class="fw-medium text-danger">Unknown User</div>
                                    <small class="text-secondary">N/A</small>
                                </div>
                            @endif


                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Items:</span>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $order->items->count() }} items
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-secondary">Total Amount:</span>
                                    <span class="fw-medium">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                <i class="bi bi-eye me-1"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Details Modal -->
                <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Order #{{ $order->id }} Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Customer Information</h6>
                                        <p class="mb-1"><strong>Name:</strong> {{ $order->user?->name ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $order->user?->email ?? 'N/A' }}</p>
                                        <p class="mb-0"><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Order Information</h6>
                                        <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                        <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                        <p class="mb-0"><strong>Status:</strong>
                                            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'info') }}-subtle text-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'info') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <h6 class="mb-3">Order Items</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $item)
                                                <tr>
                                                    <td>{{ $item->product?->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->color ? $item->color->name : 'N/A' }}</td>
                                                    <td>{{ $item->size ? $item->size->name : 'N/A' }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>${{ number_format($item->price, 2) }}</td>
                                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-end"><strong>Total Amount:</strong></td>
                                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.avatar-circle-sm {
    width: 32px;
    height: 32px;
    background: linear-gradient(45deg, #4e73df, #224abe);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-text-sm {
    color: white;
    font-size: 14px;
    font-weight: bold;
}

.dropdown-item-form {
    margin: 0;
    padding: 0;
}

.dropdown-item-form .dropdown-item {
    border-radius: 0;
    padding: 0.5rem 1rem;
}

.dropdown-item-form .dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item-form .dropdown-item.active {
    background-color: #0d6efd;
    color: white;
}
</style>
@endsection
