@extends('layouts.master')
@section('title', 'Checkout')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-6 mb-4">
            <div class="card bg-dark">
                <div class="card-body">
                    <h2 class="card-title mb-4">Order Summary</h2>
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('images/' . $item->product->photo) }}"
                                     alt="{{ $item->product->name }}"
                                     class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <p class="text-secondary mb-1">
                                        Quantity: {{ $item->quantity }}
                                        @if($item->color)
                                            | Color: {{ $item->color->name }}
                                        @endif
                                        @if($item->size)
                                            | Size: {{ $item->size->name }}
                                        @endif
                                    </p>
                                    <p class="mb-0">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-top border-secondary mt-3 pt-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="col-md-6">
            <div class="card bg-dark">
                <div class="card-body">
                    <h2 class="card-title mb-4">Shipping & Payment</h2>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <!-- Shipping Address -->
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                      class="form-control bg-dark text-white"
                                      required></textarea>
                            @error('shipping_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label">Payment Method</label>
                            <div class="form-check">
                                <input type="radio" name="payment_method" value="credit_card"
                                       class="form-check-input" required>
                                <label class="form-check-label">Credit Card</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="payment_method" value="paypal"
                                       class="form-check-input">
                                <label class="form-check-label">PayPal</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="payment_method" value="paypal"
                                       class="form-check-input">
                                <label class="form-check-label">Cash on Delivery</label>
                            </div>
                            @error('payment_method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection