@extends('layouts.master')

@section('title', 'Manage Products')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Manage Products</h2>
        <a href="{{ route('products.edit') }}" class="btn btn-success">+ Add Product</a>
    </div>

    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($product->photo)
                <img src="{{ asset("images/$product->photo") }}" class="card-img-top" style="height:200px; object-fit:cover;">
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="text-warning">${{ $product->price }}</p>
                    <p><strong>Category:</strong> {{ $product->category }}</p>
                    <p><strong>Quantity:</strong> {{ $product->quantity }}</p>

                    <p><strong>Colors:</strong>
                        @foreach($product->colors as $color)
                            <span style="display:inline-block;width:15px;height:15px;background-color:{{ $color->hex_code }};border-radius:50%;border:1px solid #ccc;margin-right:5px;"></span>
                        @endforeach
                    </p>

                    <p><strong>Sizes:</strong>
                        @foreach($product->sizes as $size)
                            <span class="badge bg-dark">{{ $size->name }}</span>
                        @endforeach
                    </p>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
