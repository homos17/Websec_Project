@extends('layouts.master')
@section('title', 'Order Success')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>

                    <h1 class="card-title mb-4">Order Placed Successfully!</h1>
                    <p class="text-secondary mb-4">Thank you for your purchase. Your order has been received and is being processed.</p>

                    <a href="{{ route('products.category') }}" class="btn btn-primary">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection