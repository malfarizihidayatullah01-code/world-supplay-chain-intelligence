<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GSC Risk Intelligence') }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"/>
    <style>
        :root {
            --primary: #4A7A44; /* Forest green from image */
            --primary-hover: #3C6337;
            --primary-soft: #E9F1E2; /* Very soft pistachio for accent backgrounds */
            --bg-light: #F9FBF6; /* Soft warm eco-friendly cream */
            --text-dark: #1E2D1D; /* Deep earthy green-black */
            --text-muted: #6B7A68;
            --card-border-radius: 24px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Override Bootstrap Primary to Eco-Friendly Green */
        .bg-primary { background-color: var(--primary) !important; }
        .text-primary { color: var(--primary) !important; }
        .btn-primary { background-color: var(--primary) !important; border-color: var(--primary) !important; border-radius: 50px; padding: 10px 24px; font-weight: 600; }
        .btn-primary:hover { background-color: var(--primary-hover) !important; border-color: var(--primary-hover) !important; }
        
        .bg-primary.bg-opacity-10 { background-color: var(--primary-soft) !important; opacity: 1 !important; }
        
        /* Navbar specific styling */
        .top-header { background-color: var(--bg-light); }
        
        /* Global Card Styling (Eco-Friendly Vibe) */
        .card {
            border-radius: var(--card-border-radius) !important;
            border: none !important;
            box-shadow: 0 8px 24px rgba(60, 99, 55, 0.04) !important;
        }
        
        /* Badges & Pills */
        .badge {
            border-radius: 50px !important;
            padding: 0.5em 1em !important;
            font-weight: 600;
        }

        /* Enterprise Top Navbar Layout */
        .top-header-wrapper {
            padding: 16px 24px 0 24px;
        }
        .top-header {
            background-color: #ffffff;
            border-radius: 24px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .top-header-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-title-section {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .header-title {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            font-size: 15px;
            color: #64748B;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Sync Button */
        .btn-sync {
            height: 34px;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            border: 1px solid #d1d5db !important;
            background-color: transparent !important;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .btn-sync:hover {
            background-color: #2563EB !important;
            color: #ffffff !important;
            border-color: #2563EB !important;
        }

        /* DateTime */
        .header-datetime {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        /* Nav Container */
        .nav-pills-container {
            display: flex;
            overflow-x: auto;
            width: 100%;
        }
        
        .nav-pills-container::-webkit-scrollbar { display: none; }

        .nav-item-link {
            color: #64748B;
            font-weight: 500;
            font-size: 15px;
            padding: 8px 16px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .nav-item-link:hover {
            color: #2563EB;
            background-color: #eff6ff;
        }

        .nav-item-link.active {
            background-color: #2563EB;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        /* Profile */
        .profile-pill {
            background-color: #ffffff;
            border-radius: 50px;
            padding: 4px 12px 4px 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #f3f4f6;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .profile-pill:hover {
            background-color: #f9fafb;
            border-color: #e5e7eb;
        }

        .profile-pill img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.2;
        }

        /* Main Content Area */
        #main-content {
            min-height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
            transition: all var(--transition-speed);
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 20px; /* More rounded */
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            margin-bottom: 24px;
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
            background-color: #ffffff;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #f3f4f6;
            padding: 16px 20px;
            font-weight: 600;
            border-radius: 20px 20px 0 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Utility */
        .bg-lime-soft { background-color: rgba(221, 247, 102, 0.4); color: #4a5c00; }
        .text-lime { color: #8bbd00; }
        
        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: #888;
        }
        .empty-state i { font-size: 2.5rem; color: #ddd; margin-bottom: 10px; }
    </style>
    @stack('styles')
</head>
<body style="background: linear-gradient(to right bottom, #e0e6ed, #f4f6f8); min-height: 100vh;">

    @guest
        <!-- Simple Layout for Guest/Auth -->
        <div class="container-fluid py-4">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @yield('content')
        </div>
    @else
        <!-- SaaS Layout (Centered Pill Navbar) -->
        <div class="top-header-wrapper d-none d-lg-block">
            <header class="top-header">
                <div class="top-header-main">
                    <!-- Left: Title & Subtitle -->
                    <div class="header-title-section">
                        <h1 class="header-title">{{ __('Global Supply Chain Monitoring') }}</h1>
                        <p class="header-subtitle">{{ __('Real-time monitoring of logistics, weather, economy, trade, and supply chain risks.') }}</p>
                    </div>

                    <!-- Right: Actions -->
                    <div class="header-actions">
                        <button class="btn btn-outline-secondary btn-sm btn-sync" onclick="location.reload()">
                            <i class="bi bi-arrow-repeat"></i> {{ __('Sync All') }}
                        </button>
                        
                        <div class="header-datetime">
                            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y • H:i') }} WIB
                        </div>

                        <!-- Language Switcher -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-pill px-3 d-flex align-items-center gap-2 border shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: white;">
                                <i class="bi bi-translate text-primary"></i>
                                <span class="fw-bold text-dark" style="font-size: 0.8rem;">{{ session('locale', config('app.locale')) == 'id' ? 'ID' : 'EN' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 mt-2" style="min-width: 120px;">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ session('locale', config('app.locale')) == 'id' ? 'active bg-primary text-white rounded-3 mx-1' : '' }}" href="{{ route('lang.switch', 'id') }}">
                                        <span class="fi fi-id rounded-circle shadow-sm" style="font-size: 1.2rem;"></span>
                                        <span style="font-size: 0.85rem;" class="fw-semibold">Indonesia</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 {{ session('locale', config('app.locale')) == 'en' ? 'active bg-primary text-white rounded-3 mx-1 mt-1' : 'mt-1' }}" href="{{ route('lang.switch', 'en') }}">
                                        <span class="fi fi-us rounded-circle shadow-sm" style="font-size: 1.2rem;"></span>
                                        <span style="font-size: 0.85rem;" class="fw-semibold">English</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <div class="profile-pill" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F3F4F6&color=111827" alt="User">
                                <div class="profile-info d-none d-xl-flex">
                                    <span class="fw-bold text-dark" style="font-size: 0.8rem;">{{ Auth::user()->name }}</span>
                                    <span class="text-muted" style="font-size: 0.7rem; text-transform: capitalize;">{{ Auth::user()->role }}</span>
                                </div>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 mt-2" style="min-width: 150px;">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger py-2">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bottom: Nav Pills -->
                <div class="nav-pills-container justify-content-center align-items-center gap-4">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">{{ __('Dashboard') }}</a>
                        <a href="{{ route('admin.users.index') }}" class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">{{ __('Users') }}</a>
                        <a href="{{ route('admin.ports.index') }}" class="nav-item-link {{ request()->routeIs('admin.ports.*') ? 'active' : '' }}">{{ __('Ports') }}</a>
                        <a href="{{ route('admin.articles.index') }}" class="nav-item-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">{{ __('Articles') }}</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="nav-item-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">{{ __('Dashboard') }}</a>
                        <a href="{{ route('user.countries.index') }}" class="nav-item-link {{ request()->routeIs('user.country') || request()->routeIs('user.countries.index') ? 'active' : '' }}">{{ __('Countries') }}</a>
                        <a href="{{ route('user.weather') }}" class="nav-item-link {{ request()->routeIs('user.weather') ? 'active' : '' }}">{{ __('Weather') }}</a>
                        <a href="{{ route('user.currency') }}" class="nav-item-link {{ request()->routeIs('user.currency') ? 'active' : '' }}">{{ __('Currency') }}</a>
                        <a href="{{ route('user.news') }}" class="nav-item-link {{ request()->routeIs('user.news') ? 'active' : '' }}">{{ __('News') }}</a>
                        <a href="{{ route('user.ports.index') }}" class="nav-item-link {{ request()->routeIs('user.ports.*') ? 'active' : '' }}">{{ __('Ports') }}</a>
                        <a href="{{ route('user.comparison') }}" class="nav-item-link {{ request()->routeIs('user.comparison') ? 'active' : '' }}">{{ __('Compare') }}</a>
                        <a href="{{ route('user.shipments.index') }}" class="nav-item-link {{ request()->routeIs('user.shipments.*') ? 'active' : '' }}">{{ __('Shipments') }}</a>
                        <a href="{{ route('user.watchlist.index') }}" class="nav-item-link {{ request()->routeIs('user.watchlist.index') ? 'active' : '' }}">{{ __('Favorites') }}</a>
                    @endif
                </div>
            </header>
        </div>

        <!-- Mobile Navbar (Fallback) -->
        <nav class="navbar navbar-expand-lg bg-white d-flex d-lg-none px-3 shadow-sm">
            <a class="navbar-brand fw-bold" href="#">GSC Risk</a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mobileMenu">
                <ul class="navbar-nav mt-2 gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <!-- Add more mobile links if needed -->
                    <li class="nav-item mt-2">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm w-100">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div id="main-content">
            <!-- Page Content -->
            <div class="container-fluid px-4 pb-4 pt-1" style="max-width: 1700px; margin: 0 auto;">
                @if(session('status'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3">{{ session('status') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    @endguest

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Optional global scripts
        });
    </script>
    @stack('scripts')
</body>
</html>



