@extends('layouts.master')

@section('title', ucfirst($category) . ' Category')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-capitalize">{{ $category }} Collection</h2>

    <div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            @if($product->photo)
            <img src="{{ asset('storage/' . $product->photo) }}" class="card-img-top" alt="{{ $product->name }}">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">{{ $product->description }}</p>
                <p class="text-muted">${{ $product->price }}</p>
            </div>
        </div>
    </div>
    @empty
        <p>No products found in this category.</p>
    @endforelse
    </div>
</div>
@endsection
