@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">My Orders</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            You haven't placed any orders yet.
        </div>
    @else
        @foreach($orders as $order)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Order #{{ $order->id }}</h5>
                        <small class="text-secondary">Placed on {{ $order->created_at->format('F j, Y') }}</small>
                    </div>
                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'info') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Shipping Address:</strong>
                            <p class="mb-0" >{{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Method:</strong>
                            <p class="mb-0">{{ ucfirst($order->payment_method) }}</p>
                        </div>
                    </div>

                    <h6>Order Items:</h6>
                    <div class="table-responsive">
                        <table class="table">
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
                                        <td>{{ $item->product->name }}</td>
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
            </div>
        @endforeach
    @endif
</div>
@endsection