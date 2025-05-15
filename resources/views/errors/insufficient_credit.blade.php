@extends('layouts.master')
@section('title', 'Insufficient Credit')
@section('content')
<div class="container mt-5">
    <div class="alert alert-danger text-center">
        <h3>🚫 Purchase Failed</h3>
        <p>You don't have enough credit to purchase this product.</p>
        <p>Please contact an employee to recharge your credit balance.</p>
        <a href="{{ route('products_list') }}" class="btn btn-primary mt-3">Go Back to Products</a>
    </div>
</div>
@endsection
