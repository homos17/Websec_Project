@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow m-4 col-sm-6">
    <div class="card-body">
        <h2 class="text-center mb-3">Welcome Back!</h2>
        <p class="text-center text-primary mb-4">Sign in to your account</p>
        <form action="{{ route('do_login') }}" method="post" aria-label="Login form">
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

        <!-- Email Input -->
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" name="email" value="{{ old('email') }}" required aria-required="true" aria-describedby="emailHelp">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Input -->
        <div class="form-group mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" name="password" required aria-required="true">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <!-- Login Button -->
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </div>

        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <div class="form-group mb-3 text-center">
            <a href="{{ route('password.request') }}" class="text-sm text-primary text-decoration-underline">
                Forgot your password?
            </a>
            </div>
        @endif

        <!-- Social Login Buttons -->
        <div class="form-group text-center mb-3">
            <span class="text-success">Or sign in with</span>
            <div class="mt-2">
            <a href="{{ route('google.login') }}" class="btn btn-outline-primary mx-1" title="Login with Google">
                <img src="{{ asset('images/google.png') }}" alt="Google Logo" style="width: 30px; height: 30px;">
            </a>
            <a href="{{ route('facebook.login') }}" class="btn btn-outline-primary mx-1" title="Login with Facebook">
                <img src="{{ asset('images/facebooklogo.png') }}" alt="Facebook Logo" style="width: 30px; height: 30px;">
            </a>
            <a href="{{ route('github.redirect') }}" class="btn btn-outline-primary mx-1" title="Login with GitHub">
                <img src="{{ asset('images/github.png') }}" alt="GitHub Logo" style="width: 30px; height: 30px;">
            </a>
            </div>
        </div>

        </form>
        <div class="form-group mt-3 text-center">
        <span class="text-white">Don't have an account?</span>
        <a href="{{ route('register') }}" class="text-sm text-primary text-decoration-underline ms-1">
            Register here
        </a>
        </div>
    </div>
    </div>
</div>
@endsection
