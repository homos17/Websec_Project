@extends('layouts.master')
@section('title', 'Shopping Cart')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Shopping Cart</h1>

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

    @if(count($cartItems) > 0)
        <div class="card bg-dark">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/' . $item->product->photo) }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-secondary">Stock: {{ $item->product->quantity }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->color)
                                            <span style="display:inline-block;width:20px;height:20px;background-color:{{ $item->color->hex_code }};border-radius:50%;border:1px solid #ccc;"></span>
                                            {{ $item->color->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $item->size ? $item->size->name : 'N/A' }}</td>
                                    <td>${{ number_format($item->product->price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}" class="form-control form-control-sm" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-primary ms-2">Update</button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <h4>Total: ${{ number_format($total, 2) }}</h4>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                                Clear Cart
                            </button>
                        </form>
                        <a href="{{ route('products.category') }}" class="btn btn-secondary">Continue Shopping</a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card bg-dark">
            <div class="card-body text-center">
                <h3>Your cart is empty</h3>
                <p class="text-secondary">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('products.category') }}" class="btn btn-primary">Start Shopping</a>
            </div>
        </div>
    @endif
</div>
@endsection