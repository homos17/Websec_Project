@extends('layouts.master')
@section('title', "Home")

@section('content')
<div class="container my-5">
    <div class="p-5 mb-4 rounded-4 shadow-sm position-relative overflow-hidden bg-light">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="display-4 fw-bold mb-3 text-white">Welcome to MyClothes</h1>
                <p class="fs-5 text-secondary">Explore stylish and modern fashion for men, women, and kids at unbeatable prices.</p>
                <a href="{{ route('products.category') }}" class="btn btn-outline-light btn-lg mt-3 px-4">Shop Now</a>
            </div>
            <div class="col-md-5 d-none d-md-block">
                <img src="{{ asset('images/logo.png') }}" alt="Banner Image" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection
