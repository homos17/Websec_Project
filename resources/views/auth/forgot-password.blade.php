@extends('layouts.master')

@section('title', 'Forgot Password')

@section('content')
  <div class="d-flex justify-content-center">
    <div class="card m-4 col-sm-6">
      <div class="card-body">
        <h3 class="text-center mb-4">Forgot Your Password?</h3>
        <p class="text-center mb-4">Enter your email address and we'll send you a link to reset your password.</p>

        <form action="{{ route('password.email') }}" method="POST">
          @csrf

          <!-- Display validation errors -->
          <div class="form-group">
            @foreach($errors->all() as $error)
              <div class="alert alert-danger">
                <strong>Error!</strong> {{$error}}
              </div>
            @endforeach
          </div>

          <!-- Email Input -->
          <div class="form-group mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
          </div>

          <!-- Submit Button -->
          <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
          </div>

          <!-- Back to Login Link -->
          <div class="form-group text-center">
            <a href="{{ route('login') }}" class="text-muted">
              Back to Login
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
