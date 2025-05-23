@extends('layouts.master')

@section('title', ucfirst($category) . ' Category')

@section('content')
<form>
    <div class="row">
        <div class="col col-sm-2">
            <input name="keywords" type="text"  class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col col-sm-2">
            <input name="min_price" type="numeric"  class="form-control" placeholder="Min Price" value="{{ request()->min_price }}"/>
        </div>
        <div class="col col-sm-2">
            <input name="max_price" type="numeric"  class="form-control" placeholder="Max Price" value="{{ request()->max_price }}"/>
        </div>
        <div class="col col-sm-2">
            <select name="order_by" class="form-select">
                <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
            </select>
        </div>
        <div class="col col-sm-2">
            <select name="order_direction" class="form-select">
                <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Order Direction</option>
                <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
            </select>
        </div>
        <div class="col col-sm-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-danger">
                <a href="{{ route('products.byCategory', $category) }}" class="btn btn-danger">Reset</a>
            </button>
        </div>
    </div>
</form>

<div class="container py-4">
    <h2 class="mb-4 text-capitalize">{{ $category }} Collection</h2>

    <div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            @if($product->photo)
            <img src="{{ asset("images/$product->photo") }}" class="card-img-top" alt="{{ $product->name }}">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                {{-- <p class="card-text">{{ $product->description }}</p> --}}
                <p class="text-warning h4 mb-3">${{ $product->price }}</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('products.details', $product->id) }}" class="btn btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
    @empty
        <p>No products found in this category.</p>
    @endforelse
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-5px);
}
.btn {
    transition: all 0.2s ease-in-out;
}
.btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection
