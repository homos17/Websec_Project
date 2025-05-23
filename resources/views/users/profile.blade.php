@extends('layouts.master')

@section('title', 'User Profile')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="mb-2 fw-bold">👤 Profile</h2>
            <p class="text-info mb-0">User Profile Information</p>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('orders.index') }}" class="btn btn-info px-4">
                <i class="bi bi-bag me-2"></i> My Orders
            </a>
            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-primary px-4">
                <i class="bi bi-pencil-square me-2"></i> Edit Profile
            </a>
            <a href="{{ route('edit_password', $user->id) }}" class="btn btn-outline-primary px-4">
                <i class="bi bi-shield-lock me-2"></i> Change Password
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- User Info Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <div class="avatar-circle mx-auto mb-3">
                            <span class="avatar-text">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                        <p class="text-secondary  mb-3">{{ $user->email }}</p>

                        @if($user->email_verified_at)
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i> Verified Account
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                <i class="bi bi-clock me-1"></i> Pending Verification
                            </span>
                        @endif
                    </div>

                    <hr class="my-4">

                    <div class="text-secondary">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="bi bi-calendar me-2"></i>
                            <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($user->last_login_at)
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-clock-history me-2"></i>
                                <span>Last login {{ $user->last_login_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- User Details Card --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4 fw-bold">
                        <i class="bi bi-person-lines-fill me-2"></i>User Details
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">Full Name</label>
                                <div class="form-control-plaintext text-info fw-medium">{{ $user->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">Email Address</label>
                                <div class="form-control-plaintext text-info fw-medium">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">Address</label>
                                <div class="form-control-plaintext text-info fw-medium">{{ $user->address ?? 'Not specified' }}</div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">User Roles</label>
                                <div>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : 'primary' }}-subtle text-{{ $role->name === 'admin' ? 'danger' : 'primary' }} px-3 py-2 me-2">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('users.list') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-2"></i> Back to Users List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(45deg, #4e73df, #224abe);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-text {
    color: white;
    font-size: 32px;
    font-weight: bold;
}



.form-control-plaintext {
    padding: 0.5rem 0;
}
@endsection
