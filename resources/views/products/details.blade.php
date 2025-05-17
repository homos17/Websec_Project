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

            {{-- Colors --}}
            <div class="mb-3">
                <h5>Available Colors:</h5>
                @foreach ($product->colors as $color)
                    <label class="me-2">
                        <input type="radio" name="selected_color" value="{{ $color->id }}">
                        <span style="display:inline-block;width:20px;height:20px;background-color:{{ $color->hex_code }};border-radius:50%;border:1px solid #ccc;"></span>
                    </label>
                @endforeach
            </div>

            {{-- Sizes --}}
            <div class="mb-3">
                <h5>Available Sizes:</h5>
                @foreach ($product->sizes as $size)
                    <label class="btn btn-outline-dark btn-sm me-1 mb-1">
                        <input type="radio" name="selected_size" value="{{ $size->id }}" style="display: none;">
                        {{ $size->name }}
                    </label>
                @endforeach
            </div>

            {{-- Add to Cart button --}}
            <form >
                @csrf
                <input type="hidden" name="color_id" id="colorInput">
                <input type="hidden" name="size_id" id="sizeInput">
                <button type="submit" class="btn btn-dark mt-2">Add to Cart</button>
            </form>

            <a href="{{ route('products.byCategory', $product->category) }}" class="btn btn-primary mt-3">Back to Category</a>
        </div>
    </div>
</div>

{{-- JS to handle selected values --}}
<script>
    document.querySelectorAll('input[name="selected_color"]').forEach(input => {
        input.addEventListener('change', function () {
            document.getElementById('colorInput').value = this.value;
        });
    });

    document.querySelectorAll('input[name="selected_size"]').forEach(input => {
        input.addEventListener('change', function () {
            document.getElementById('sizeInput').value = this.value;
        });
    });
</script>
@endsection
