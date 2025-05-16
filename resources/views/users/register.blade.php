@extends('layouts.master')

@section('title', 'Register')

@section('content')
  <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow m-4 col-sm-6">
      <div class="card-body">
        <h2 class="text-center mb-3">Create Account</h2>
        <p class="text-center text-primary mb-4">Register a new account</p>
        <form action="{{ route('do_register') }}" method="post" aria-label="Register form">
          {{ csrf_field() }}

          <!-- Display General Errors -->
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- Name Input -->
          <div class="form-group mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name" name="name" value="{{ old('name') }}" required aria-required="true" aria-describedby="nameHelp" autocomplete="name">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Email Input -->
          <div class="form-group mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" name="email" value="{{ old('email') }}" required aria-required="true" aria-describedby="emailHelp" autocomplete="email">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password Input -->
          <div class="form-group mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" name="password" required aria-required="true" aria-describedby="passwordHelp" autocomplete="new-password">
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password Confirmation Input -->
          <div class="form-group mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm your password" name="password_confirmation" required aria-required="true" aria-describedby="passwordConfirmationHelp" autocomplete="new-password">
            @error('password_confirmation')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Register Button -->
          <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary w-100">Register</button>
          </div>

          <!-- Social Register Buttons -->
          <div class="form-group text-center mb-3">
            <span class="text-success">Or sign up with</span>
            <div class="mt-2">
              <a href="{{ route('google.login') }}" class="btn btn-outline-primary mx-1" title="Register with Google">
                <img src="{{ asset('images/google.png') }}" alt="Google Logo" style="width: 30px; height: 30px;">
              </a>
              <a href="{{ route('facebook.login') }}" class="btn btn-outline-primary mx-1" title="Register with Facebook">
                <img src="{{ asset('images/facebooklogo.png') }}" alt="Facebook Logo" style="width: 30px; height: 30px;">
              </a>
              <a href="{{ route('github.redirect') }}" class="btn btn-outline-primary mx-1" title="Register with GitHub">
                <img src="{{ asset('images/github.png') }}" alt="GitHub Logo" style="width: 30px; height: 30px;">
              </a>
            </div>
          </div>

          <!-- Login Link -->
          <div class="form-group mt-3 text-center">
            <span class="text-white">Already have an account?</span>
            <a href="{{ route('login') }}" class="text-sm text-primary text-decoration-underline ms-1">
              Login here
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection