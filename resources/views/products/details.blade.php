@extends('layouts.master')
@section('title', "Category")

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset("images/$product->photo") }}" class="img-fluid rounded" alt="{{ $product->name }}">
        </div>

        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p class="text-warning">${{ $product->price }}</p>
            <p>{{ $product->description }}</p>

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

            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                
                {{-- Quantity --}}
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}" required>
                </div>

                {{-- Colors --}}
                <div class="mb-3">
                    <h5>Available Colors:</h5>
                    @foreach ($product->colors as $color)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="color_id" id="color{{ $color->id }}" value="{{ $color->id }}">
                            <label class="form-check-label" for="color{{ $color->id }}">
                                <span style="display:inline-block;width:20px;height:20px;background-color:{{ $color->hex_code }};border-radius:50%;border:1px solid #ccc;margin-right:5px;"></span>
                                {{ $color->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                {{-- Sizes --}}
                <div class="mb-3">
                    <h5>Available Sizes:</h5>
                    @foreach ($product->sizes as $size)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="size_id" id="size{{ $size->id }}" value="{{ $size->id }}">
                            <label class="form-check-label" for="size{{ $size->id }}">
                                {{ $size->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-dark mt-2">Add to Cart</button>
            </form>

            <a href="{{ route('products.byCategory', $product->category) }}" class="btn btn-primary mt-3">Back to Category</a>
        </div>
    </div>
</div>
@endsection
