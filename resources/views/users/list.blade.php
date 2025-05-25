@extends('layouts.master')
@section('title', 'Users Management')
@section('content')
<div class="container-fluid py-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 fw-bold">👥 Users Management</h1>
            <p class="text-info mb-0">Manage your system users and their permissions</p>
        </div>
        
    </div>

    <!-- Search and Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('users.list') }}" class="row g-4 align-items-end" id="searchForm">
                <div class="col-md-4">
                    <label for="keywords" class="form-label text-secondary">Search Users</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-secondary"></i>
                        </span>
                        <input type="text"
                            class="form-control border-start-0"
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
                    <label for="role" class="form-label text-secondary">Filter by Role</label>
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
                    <label for="status" class="form-label text-secondary">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="verified" {{ request()->status === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request()->status === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 px-4" id="filterBtn">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                        <a href="{{ route('users.list') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-redo me-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
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
                    <thead>
                        <tr class="bg-light">
                            <th scope="col" class="text-secondary">Name</th>
                            <th scope="col" class="text-secondary">Email</th>
                            <th scope="col" class="text-secondary">Roles</th>
                            <th scope="col" class="text-secondary">Status</th>
                            <th scope="col" class="text-secondary">Last Login</th>
                            <th scope="col" class="text-center text-secondary">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <span class="avatar-text">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-info">{{ $user->name }}</div>
                                        <small class="text-secondary">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-info text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : 'primary' }}-subtle text-{{ $role->name === 'admin' ? 'danger' : 'primary' }} px-3 py-2 me-2">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>Unverified
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @can('edit_users')
                                    <a href="{{ route('users_edit', $user->id) }}"
                                       class="btn btn-sm btn-primary px-3"
                                       title="Edit User"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    @endcan

                                    @can('admin_users')
                                    <a href="{{ route('edit_password', $user->id) }}"
                                       class="btn btn-sm btn-warning px-3"
                                       title="Change Password"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-key me-1"></i> Password
                                    </a>
                                    @endcan

                                    @can('delete_users')
                                    <form action="{{ route('users_delete', $user->id) }}"
                                          method="POST"
                                          class="d-inline delete-user-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger px-3"
                                                title="Delete User"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-secondary">
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
                <div class="text-secondary">
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

<style>
/* الحل الأساسي لإزالة اللون الأبيض */
.table td {
    background-color: #23272b !important;
}

/* تحسينات إضافية */
.table tbody tr {
    border-bottom: 1px solid #353b43 !important;
}

.table td, .table th {
    padding: 1rem !important;
}

.table-hover tbody tr {
    transition: all 0.2s ease-in-out;
}
.table-hover tbody tr:hover {
    background: #2d3748 !important;
    filter: brightness(1.1);
}

/* بقية الأنماط الحالية */
body, .container-fluid, .card, .table, .bg-light {
    background-color: #181c20 !important;
    color: #e0e0e0 !important;
}

.card, .card-body, .table, .table-responsive {
    border-radius: 18px !important;
    background: #23272b !important;
    box-shadow: 0 2px 16px 0 rgba(0,0,0,0.10);
    border: none !important;
}

.table thead th, .bg-light {
    background: #23272b !important;
    color: #bfc9d1 !important;
    border-bottom: 1px solid #23272b !important;
}

.table td, .table th {
    border-color: #23272b !important;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3a3f47 60%, #23272b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.10);
}
.avatar-text {
    color: #bfc9d1;
    font-size: 18px;
    font-weight: bold;
}

.btn-primary, .btn-success, .btn-warning, .btn-danger {
    border: none !important;
    border-radius: 8px !important;
    box-shadow: none !important;
}
.btn-primary {
    background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%) !important;
    color: #fff !important;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #8f94fb 0%, #4e54c8 100%) !important;
}
.btn-success {
    background: #3bb273 !important;
    color: #fff !important;
}
.btn-warning {
    background: #f7b731 !important;
    color: #23272b !important;
}
.btn-danger {
    background: #e74c3c !important;
    color: #fff !important;
}
.btn-outline-secondary, .btn-secondary {
    background: transparent !important;
    color: #bfc9d1 !important;
    border: 1px solid #353b43 !important;
}
.btn-outline-secondary:hover, .btn-secondary:hover {
    background: #23272b !important;
    color: #fff !important;
}

.form-control, .form-select {
    background: #23272b !important;
    color: #e0e0e0 !important;
    border: 1px solid #353b43 !important;
    border-radius: 8px !important;
}
.form-control:focus, .form-select:focus {
    border-color: #4e54c8 !important;
    box-shadow: 0 0 0 0.15rem rgba(78, 84, 200, 0.15) !important;
}
.input-group-text {
    background: #23272b !important;
    color: #bfc9d1 !important;
    border: 1px solid #353b43 !important;
}

/* Badges */
.badge {
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.95em;
    padding: 0.45em 1.1em;
    background: #23272b;
    color: #bfc9d1;
    border: none;
}
.badge.bg-primary-subtle, .badge.bg-primary {
    background: #2d4379 !important;
    color: #7ea2e0 !important;
}
.badge.bg-danger-subtle, .badge.bg-danger {
    background: #3a2323 !important;
    color: #e57373 !important;
}
.badge.bg-success-subtle, .badge.bg-success {
    background: #233a2d !important;
    color: #7ed6a7 !important;
}
.badge.bg-warning-subtle, .badge.bg-warning {
    background: #3a3723 !important;
    color: #ffe082 !important;
}

/* Status badge tweaks */
.badge.bg-warning-subtle, .badge.bg-warning {
    color: #ffe082 !important;
    background: #3a3723 !important;
}
.badge.bg-success-subtle, .badge.bg-success {
    color: #7ed6a7 !important;
    background: #233a2d !important;
}

/* Table row hover */
.table-hover tbody tr:hover {
    background: #23272b !important;
    filter: brightness(1.08);
}

/* Alerts */
.alert {
    background: #23272b !important;
    color: #bfc9d1 !important;
    border: 1px solid #353b43 !important;
}

/* Headings and text */
h1, h2, h3, h4, h5, h6 {
    color: #e0e0e0 !important;
    font-weight: 700;
}
.text-info {
    color: #7ea2e0 !important;
}
.text-secondary {
    color: #bfc9d1 !important;
}

/* Remove harsh box shadows from all elements */
* {
    box-shadow: none !important;
}
</style>

<!-- Optional JavaScript for Tooltip and Select All -->
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

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