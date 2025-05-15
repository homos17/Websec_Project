@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
  <div class="d-flex justify-content-center">
    <div class="card m-4 col-sm-6">
      <div class="card-body">
        <h3 class="text-center mb-4">Reset Your Password</h3>
        <p class="text-center mb-4">Please enter a new password to continue.</p>

        <form action="{{ route('password.update') }}" method="POST">
          @csrf

          <!-- Hidden email and token for the form submission -->
          <input type="hidden" name="token" value="{{ $token }}">
          <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

          <!-- Display validation errors -->
          <div class="form-group">
            @foreach($errors->all() as $error)
              <div class="alert alert-danger">
                <strong>Error!</strong> {{$error}}
              </div>
            @endforeach
          </div>

          <!-- Password Input -->
          <div class="form-group mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" id="password" class="form-control" name="password" required placeholder="Enter a new password" minlength="8">
            <small class="form-text text-muted">Your password must be at least 8 characters long.</small>
          </div>

          <!-- Confirm Password Input -->
          <div class="form-group mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required placeholder="Confirm your password">
          </div>

          <!-- Submit Button -->
          <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
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
