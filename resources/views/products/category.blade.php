@extends('layouts.master')
@section('title', "Category")

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-5">Categories</h1>
    <div class="row justify-content-center g-4">
    <!-- Women Category -->
    <div class="col-md-4">
        <div class="card bg-dark text-white category-card shadow-sm">
            <img src="{{ asset('images/women.jpg') }}" class="card-img" alt="Women Category">
            <div class="card-img-overlay d-flex flex-column justify-content-end">
                <h3 class="card-title text-center fw-bold">Women</h3>
                <a href="{{ route('products.byCategory',['category' => 'women']) }}" class="btn btn-outline-light mt-3">Shop Now</a>
            </div>
        </div>
    </div>

    <!-- Men Category -->
    <div class="col-md-4">
        <div class="card bg-dark text-white category-card shadow-sm">
            <img src="{{ asset('images/men.jpg') }}" class="card-img" alt="Men Category">
            <div class="card-img-overlay d-flex flex-column justify-content-end">
                <h3 class="card-title text-center fw-bold">Men</h3>
                <a href="{{ route('products.byCategory',['category' => 'men']  ) }}" class="btn btn-outline-light mt-3">Shop Now</a>
            </div>
        </div>
    </div>

    <!-- Kids Category -->
    <div class="col-md-4">
        <div class="card bg-dark text-white category-card shadow-sm">
            <img src="{{ asset('images/kids.jpg') }}" class="card-img" alt="Kids Category">
            <div class="card-img-overlay d-flex flex-column justify-content-end">
                <h3 class="card-title text-center fw-bold">Kids</h3>
                <a href="{{ route('products.byCategory',['category' => 'kids']) }}" class="btn btn-outline-light mt-3">Shop Now</a>
            </div>
        </div>
    </div>
</div>
@endsection
