<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">

        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
        </a>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Men</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Woman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Kids&Baby</a>
                </li>
            </ul>

            {{-- <form class="d-flex me-3">
                <div class="input-group">
                    <input class="form-control border-end-2" type="search" placeholder="Search: jeans, shirts..." aria-label="Search">
                    <button class="btn btn-outline-primary border-start-0" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form> --}}


            <ul class="navbar-nav">
                @if (Auth::check())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('do_logout') }}">
                        <i class="fas fa-user"></i> Logout
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="{{ asset('images/cart1.png') }}" alt="Logo" height="35">Shopping Cart
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


