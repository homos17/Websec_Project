<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>online shop - @yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <style>
        :root {
            --primary-color: #6610f2;
            --secondary-color: #6f42c1;
            --dark-bg: #121212;
            --card-bg: #1e1e1e;
            --text-primary: #ffffff;
            --text-secondary: #b3b3b3;
            --border-color: #2d2d2d;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .navbar-custom {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .navbar .nav-link,
        .navbar .navbar-brand {
            color: var(--text-primary) !important;
            transition: all 0.3s ease;
        }

        .navbar .nav-link:hover {
            color: rgba(255, 255, 255, 0.8) !important;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .card, .bg-light {
            background-color: var(--card-bg) !important;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid var(--border-color);
        }

        .category-card {
            height: 350px;
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
            }

        .category-card img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            opacity: 0.8;
            transition: 0.3s ease;
        }

        .category-card:hover img {
            opacity: 0.6;
        }

        .table {
            color: var(--text-primary);
        }

        .table thead th {
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 2px solid var(--border-color);
        }

        .table td, .table th {
            border-color: var(--border-color);
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, var(--secondary-color), var(--primary-color));
            transform: translateY(-1px);
        }

        .btn-outline-light {
            border-color: var(--text-primary);
            color: var(--text-primary);
        }

        .btn-outline-light:hover {
            background-color: var(--text-primary);
            color: var(--dark-bg);
        }

        .form-control {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus {
            background-color: var(--card-bg);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(102, 16, 242, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .alert {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    @include('layouts.menu')

    <div class="container my-4">
        @yield('content')
    </div>

</body>
</html>
