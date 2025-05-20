@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">User Profile</h1>
            <p class="text-muted mb-0">View and manage user information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit Profile
            </a>
            <a href="{{ route('edit_password', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-key me-1"></i> Change Password
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- User Info Card -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($user->email_verified_at)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i> Verified
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i> Unverified
                            </span>
                        @endif
                    </div>

                    <div class="text-muted small">
                        <div class="mb-1">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </div>
                        @if($user->last_login_at)
                            <div>
                                <i class="fas fa-sign-in-alt me-1"></i>
                                Last login {{ $user->last_login_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-user me-2"></i>User Information
                    </h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-user me-1"></i> Name
                                </label>
                                <p class="form-control-plaintext">{{ $user->name }}</p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-envelope me-1"></i> Email
                                </label>
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i> Address
                                </label>
                                <p class="form-control-plaintext">{{ $user->address ?? 'Not provided' }}</p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">
                                    <i class="fas fa-user-tag me-1"></i> Roles
                                </label>
                                <div>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : 'primary' }} me-1">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('users.list') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
        font-size: 0.875em;
    }
    
    .alert {
        margin-bottom: 0;
    }

    .form-control-plaintext {
        padding: 0.5rem 0;
        margin-bottom: 0;
        line-height: 1.5;
        color: #212529;
        background-color: transparent;
        border: solid transparent;
        border-width: 1px 0;
        font-size: 1.1rem;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }

    .card-title {
        color: #495057;
        font-weight: 600;
    }

    .text-muted {
        color: #6c757d !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    });
});
</script>
@endpush
@endsection