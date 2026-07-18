<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Supply Chain Risk Intelligence</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #2563EB;
            --bs-success: #198754;
            --bs-warning: #FFC107;
            --bs-danger: #DC3545;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: none;
        }
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        .sidebar {
            min-height: calc(100vh - 56px - 60px); /* minus navbar and footer */
            background-color: #ffffff;
            box-shadow: 1px 0 3px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: #4b5563;
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            border-radius: 8px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #eff6ff;
            color: var(--bs-primary);
        }
        .navbar {
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>
    @auth
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-primary" href="/dashboard">Risk Intelligence</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid flex-grow-1 d-flex p-0">
        <div class="row w-100 m-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}" href="{{ route('countries.index') }}">
                            <i class="bi bi-globe-americas me-2"></i> Countries
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ports.*') ? 'active' : '' }}" href="{{ route('ports.index') }}">
                            <i class="bi bi-water me-2"></i> Ports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shipping-routes.*') ? 'active' : '' }}" href="{{ route('shipping-routes.index') }}">
                            <i class="bi bi-geo-alt me-2"></i> Shipping Routes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('weather.*') ? 'active' : '' }}" href="{{ route('weather.index') }}">
                            <i class="bi bi-cloud-sun me-2"></i> Weather
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('currency.*') ? 'active' : '' }}" href="{{ route('currency.index') }}">
                            <i class="bi bi-currency-exchange me-2"></i> Currency
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                            <i class="bi bi-newspaper me-2"></i> News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('risk-analysis.*') ? 'active' : '' }}" href="{{ route('risk-analysis.index') }}">
                            <i class="bi bi-shield-exclamation me-2"></i> Risk Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shipment-analysis.*') ? 'active' : '' }}" href="{{ route('shipment-analysis.index') }}">
                            <i class="bi bi-box-seam me-2"></i> Shipment Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('administration.*') ? 'active' : '' }}" href="{{ route('administration.index') }}">
                            <i class="bi bi-gear me-2"></i> Administration
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-white border-top">
        <div class="container text-center">
            <span class="text-muted">Global Supply Chain Risk Intelligence Platform &copy; {{ date('Y') }}</span>
        </div>
    </footer>
    @else
        <main class="d-flex align-items-center justify-content-center w-100 flex-grow-1">
            @yield('content')
        </main>
    @endauth

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
