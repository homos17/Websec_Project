@extends('layouts.master')

@section('title', 'Edit product')

@section('content')
<form action="{{ route('products.save', $product->id) }}" method="post">
    {{ csrf_field() }}

    <div class="row mb-2">
        <div class="col-6">
            <label for="code" class="form-label">Code:</label>
            <input type="text" class="form-control" name="code" placeholder="Code" required value="{{ $product->code }}">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" name="name" placeholder="Name" required value="{{ $product->name }}">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-6">
            <label for="price" class="form-label">Price:</label>
            <input type="number" step="0.01" class="form-control" name="price" placeholder="Price" required value="{{ $product->price }}">
        </div>
        <div class="col-6">
            <label for="photo" class="form-label">Photo URL:</label>
            <input type="text" class="form-control" name="photo" placeholder="Photo" required value="{{ $product->photo }}">
        </div>

    <div class="row mb-2">
        <div class="col-6">
            <label class="form-label">Colors:</label><br>
            @foreach($colors as $color)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $color->id }}"
                        {{ $product->colors->contains($color->id) ? 'checked' : '' }}>
                    <label class="form-check-label" style="color: {{ $color->hex_code }}">
                        {{ $color->name }}
                    </label>
                </div>
            @endforeach
        </div>

        <div class="col-6">
            <label class="form-label">Sizes:</label><br>
            @foreach($sizes as $size)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size->id }}"
                        {{ $product->sizes->contains($size->id) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $size->name }}</label>
                </div>
            @endforeach
        </div>
    </div>


    <div class="row mb-2">
        <div class="col-6">
            <label for="category" class="form-label">Category:</label>
            <input type="text" class="form-control" name="category" placeholder="Category" required value="{{ $product->category }}">
        </div>
        <div class="col-6">
            <label for="quantity" class="form-label">Quantity:</label>
            <input type="number" class="form-control" name="quantity" placeholder="Quantity" min="0" required value="{{ $product->quantity }}">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" name="description" placeholder="Description" required>{{ $product->description }}</textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save Product</button>
</form>
@endsection
