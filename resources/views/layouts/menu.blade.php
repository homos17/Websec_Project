<nav class="navbar navbar-expand-lg navbar-custom shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40" class="me-2">
            MyClothes
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto">
                {{-- @can(view_products) --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.byCategory',['category' => 'men']) }}">Men</a>
                </li>
                {{-- @endcan --}}
                {{-- @can(view_products) --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.byCategory',['category' => 'women']) }}">Women</a>
                </li>
                {{-- @endcan --}}
                {{-- @can(view_products) --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.byCategory',['category' => 'kids']) }}">Kids & Baby</a>
                </li>
                {{-- @endcan --}}
                @can('manage_products')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.manage') }}">Manage Products</a>
                </li>
                @endcan
                @can('mange_Orders')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.admin') }}">
                        <i class="bi bi-bag"></i> Manage Orders
                    </a>
                </li>
                @endcan
            </ul>

            <ul class="navbar-nav">
                @auth
                    @can('Complaints')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="supportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-headset"></i> Complaints
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="supportDropdown">
                            @can('show_Complaints')
                            <li>
                                <a class="dropdown-item" href="{{ route('support.list') }}">
                                    <i class="fas fa-ticket-alt"></i> My Complaints
                                </a>
                            </li>
                            @endcan
                            @can('add_Complaints')
                            <li>
                                <a class="dropdown-item" href="{{ route('support.add') }}">
                                    <i class="fas fa-plus-circle"></i> Create New Complaint
                                </a>
                            </li>
                            @endcan()
                            @can('admin_Complaints')
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.support.index') }}">
                                    <i class="fas fa-plus-circle"></i> Admin Complaints
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userManagementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-users-cog"></i> User Management
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userManagementDropdown">
                            @can('view_users')
                            <li>
                                <a class="dropdown-item" href="{{ route('users.list') }}">
                                    <i class="fas fa-list"></i> All Users
                                </a>
                            </li>
                            @endcan
                            @can('add_users')
                            <li>
                                <a class="dropdown-item" href="{{ route('users_create') }}">
                                    <i class="fas fa-user-plus"></i> Add New User
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @endcan
                            <li>
                                <a class="dropdown-item" href="{{ route('profile', ['user' => Auth::id()]) }}">
                                    <i class="fas fa-user-circle"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('edit_password', ['user' => Auth::id()]) }}">
                                    <i class="fas fa-key"></i> Change Password
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile', ['user' => Auth::id()]) }}">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('do_logout') }}">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Register</a></li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="bi bi-bag"></i> Cart
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
