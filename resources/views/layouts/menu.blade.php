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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.byCategory',['category' => 'men']) }}">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.byCategory',['category' => 'women']) }}">Women</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.byCategory',['category' => 'kids']) }}">Kids & Baby</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.manage') }}">Manage Products</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
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
                    <a class="nav-link" href="#">
                        <i class="bi bi-bag"></i> Cart
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
