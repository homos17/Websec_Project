@extends('layouts.master')

@section('title', 'Edit Password')

@section('content')
  <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow m-4 col-sm-6">
      <div class="card-body">
        <h2 class="text-center mb-3">Change Password</h2>
        <p class="text-center text-secondary mb-4">Update your password</p>
        <form action="{{ route('save_password', $user->id) }}" method="post" aria-label="Edit password form">
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

          @if(!auth()->user()->hasPermissionTo('admin_users') || auth()->id()==$user->id)
            <div class="form-group mb-3">
              <label for="old_password" class="form-label">Old Password:</label>
              <input type="password" id="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Old Password" name="old_password" required aria-required="true" autocomplete="current-password">
              @error('old_password')
                <div class="invalid-feedback">{{ $error }}</div>
              @enderror
            </div>
          @endif

          <div class="form-group mb-3">
            <label for="password" class="form-label">New Password:</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="New Password" name="password" required aria-required="true" autocomplete="new-password">
            @error('password')
              <div class="invalid-feedback">{{ $error }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label for="password_confirmation" class="form-label">Password Confirmation:</label>
            <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm New Password" name="password_confirmation" required aria-required="true" autocomplete="new-password">
            @error('password_confirmation')
              <div class="invalid-feedback">{{ $error }}</div>
            @enderror
          </div>

          <div class="form-group mb-3 text-center">
            <button type="submit" class="btn btn-primary w-100">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
