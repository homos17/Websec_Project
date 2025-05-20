@extends('layouts.master')
@section('title', 'Users Management')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Users Management</h1>
            <p class="text-muted mb-0">Manage your system users and their permissions</p>
        </div>
        <div class="d-flex gap-2">
            @can('create_users')
            <a href="{{ route('users_create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New User
            </a>
            @endcan
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('users.list') }}" class="row g-3 align-items-end" id="searchForm">
                <div class="col-md-4">
                    <label for="keywords" class="form-label">Search Users</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text"
                            class="form-control"
                            id="keywords"
                            name="keywords"
                            placeholder="Search by name or email"
                            value="{{ request()->keywords }}"
                            autocomplete="off">
                        <div class="spinner-border spinner-border-sm text-primary d-none" role="status" id="searchSpinner">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="role" class="form-label">Filter by Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request()->role == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="verified" {{ request()->status === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request()->status === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1" id="filterBtn">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('users.list') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table Section -->
    <div class="card">
        <div class="card-body">
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

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Status</th>
                            <th scope="col">Last Login</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : 'primary' }} me-1">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Unverified
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @can('edit_users')
                                    <a href="{{ route('users_edit', $user->id) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit User"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endcan

                                    @can('admin_users')
                                    <a href="{{ route('edit_password', $user->id) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Change Password"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-key"></i> Change Password
                                    </a>
                                    @endcan

                                    @can('delete_users')
                                    <form action="{{ route('users_delete', $user->id) }}"
                                          method="POST"
                                          class="d-inline delete-user-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete User"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p class="mb-0">No users found</p>
                                    <small>Try adjusting your search or filter criteria</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div>
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Optional JavaScript for Tooltip and Select All -->
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Select all checkbox logic
        const selectAllCheckbox = document.getElementById('selectAll');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCountSpan = document.getElementById('selectedCount');

        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
            updateBulkActions();
        });

        userCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const selectedCount = Array.from(userCheckboxes).filter(cb => cb.checked).length;
            selectedCountSpan.textContent = selectedCount;
            bulkActions.classList.toggle('d-none', selectedCount === 0);
            selectAllCheckbox.checked = selectedCount === userCheckboxes.length;
        }

        // Delete confirmation
        document.querySelectorAll('.delete-user-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this user?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection

@endsection
