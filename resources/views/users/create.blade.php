@extends('layouts.master')
@section('title', 'Add User')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-8">
    <div class="card-body">
      <form action="{{ route('users_store') }}" method="post">
        @csrf
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach

        <div class="mb-2">
          <label class="form-label">Name:</label>
          <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-2">
          <label class="form-label">Email:</label>
          <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-2">
          <label class="form-label">Password:</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-2">
          <label class="form-label">Confirm Password:</label>
          <input type="password" class="form-control" name="password_confirmation" required>
        </div>

        <div class="mb-2">
          <label class="form-label">Assign Roles:</label>
          <select class="form-select" name="roles[]" multiple required>
            @foreach($roles as $role)
              <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
          </select>
        </div>

        <button type="submit" class="btn btn-success">Create User</button>
      </form>
    </div>
  </div>
</div>
@endsection
