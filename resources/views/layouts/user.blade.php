<!DOCTYPE html>
<html lang="en" data-theme="forest">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GSC Risk Intelligence') }} - User Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"/>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        /* Specific Layout structure for Sidebar & Navbar */
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }
        
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 24px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Sidebar branding */
        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            color: white;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        /* Sidebar Links */
        .sidebar-nav {
            padding: 24px 12px;
            flex: 1;
            overflow-y: auto;
        }
        
        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .nav-link-item:hover {
            color: white;
            background-color: var(--sidebar-hover);
        }
        
        .nav-link-item.active {
            color: white;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        /* Floating Navbar */
        .floating-navbar {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
            margin-bottom: 32px;
            position: sticky;
            top: 24px;
            z-index: 1030;
        }
        
        /* User Profile Pill */
        .profile-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 50px;
            background-color: var(--background);
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        .profile-pill:hover {
            background-color: #e5e7eb;
        }
        .profile-pill img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        /* Mobile */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                border-radius: 0;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                padding: 16px;
            }
            .floating-navbar {
                top: 16px;
                margin-bottom: 24px;
            }
        }
    </style>
</head>
<body>
    @guest
        @yield('content')
    @else
    <div class="app-container">
        
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('user.dashboard') }}" class="sidebar-brand">
                <i data-lucide="shield-check" style="color: var(--secondary)"></i>
                GSC Risk
            </a>
            
            <nav class="sidebar-nav">
                <div class="text-uppercase mb-3 px-3" style="font-size: 0.75rem; color: rgba(255,255,255,0.4); font-weight: 600; letter-spacing: 1px;">Menu</div>
                
                <a href="{{ route('user.dashboard') }}" class="nav-link-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
                <a href="{{ route('user.countries.index') }}" class="nav-link-item {{ request()->routeIs('user.country') || request()->routeIs('user.countries.index') ? 'active' : '' }}">
                    <i data-lucide="globe"></i> Countries
                </a>
                <a href="{{ route('user.weather') }}" class="nav-link-item {{ request()->routeIs('user.weather') ? 'active' : '' }}">
                    <i data-lucide="cloud-lightning"></i> Weather
                </a>
                <a href="{{ route('user.currency') }}" class="nav-link-item {{ request()->routeIs('user.currency') ? 'active' : '' }}">
                    <i data-lucide="coins"></i> Currency
                </a>
                <a href="{{ route('user.news') }}" class="nav-link-item {{ request()->routeIs('user.news') ? 'active' : '' }}">
                    <i data-lucide="newspaper"></i> News
                </a>
                <a href="{{ route('user.ports.index') }}" class="nav-link-item {{ request()->routeIs('user.ports.*') ? 'active' : '' }}">
                    <i data-lucide="anchor"></i> Ports
                </a>
                <a href="{{ route('user.comparison') }}" class="nav-link-item {{ request()->routeIs('user.comparison') ? 'active' : '' }}">
                    <i data-lucide="git-compare"></i> Compare
                </a>
                <a href="{{ route('user.shipments.index') }}" class="nav-link-item {{ request()->routeIs('user.shipments.*') ? 'active' : '' }}">
                    <i data-lucide="truck"></i> Shipments
                </a>
                <a href="{{ route('user.watchlist.index') }}" class="nav-link-item {{ request()->routeIs('user.watchlist.index') ? 'active' : '' }}">
                    <i data-lucide="star"></i> Favorites
                </a>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link-item w-100 text-start" style="background: transparent; border: none; color: #ef4444;">
                        <i data-lucide="log-out"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            
            <!-- Floating Navbar -->
            <nav class="floating-navbar fade-in">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light d-lg-none border-0" id="mobileMenuToggle">
                        <i data-lucide="menu"></i>
                    </button>
                    <!-- Search Box -->
                    <div class="input-group d-none d-md-flex" style="width: 300px; background: var(--background); border-radius: 50px; padding: 4px 16px; border: 1px solid var(--border);">
                        <i data-lucide="search" style="width: 18px; color: var(--text-muted); margin-top: 6px;"></i>
                        <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Search...">
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-4">
                    <!-- Date -->
                    <div class="d-none d-md-flex align-items-center gap-2 text-muted fw-medium" style="font-size: 0.9rem;">
                        <i data-lucide="calendar" style="width: 18px;"></i>
                        {{ now()->format('d M Y') }}
                    </div>
                    
                    <!-- Theme Switcher (UI Only, handled by JS later) -->
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2 border-0" data-bs-toggle="dropdown">
                            <i data-lucide="palette" style="width: 20px;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2">
                            <li><h6 class="dropdown-header">Select Theme</h6></li>
                            <li><button class="dropdown-item d-flex align-items-center gap-2 theme-btn" data-theme="forest"><span class="rounded-circle" style="width:12px; height:12px; background:#2F7A68;"></span> Forest (Default)</button></li>
                            <li><button class="dropdown-item d-flex align-items-center gap-2 theme-btn" data-theme="ocean"><span class="rounded-circle" style="width:12px; height:12px; background:#0284C7;"></span> Ocean</button></li>
                            <li><button class="dropdown-item d-flex align-items-center gap-2 theme-btn" data-theme="purple"><span class="rounded-circle" style="width:12px; height:12px; background:#7C3AED;"></span> Purple</button></li>
                            <li><button class="dropdown-item d-flex align-items-center gap-2 theme-btn" data-theme="sunset"><span class="rounded-circle" style="width:12px; height:12px; background:#EA580C;"></span> Sunset</button></li>
                            <li><button class="dropdown-item d-flex align-items-center gap-2 theme-btn" data-theme="emerald"><span class="rounded-circle" style="width:12px; height:12px; background:#059669;"></span> Emerald</button></li>
                            <li><button class="dropdown-item d-flex align-items-center gap-2 theme-btn" data-theme="midnight"><span class="rounded-circle" style="width:12px; height:12px; background:#4F46E5;"></span> Midnight</button></li>
                        </ul>
                    </div>

                    <!-- Notification -->
                    <button class="btn btn-light rounded-circle p-2 border-0 position-relative">
                        <i data-lucide="bell" style="width: 20px;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </button>

                    <!-- Profile -->
                    <div class="dropdown">
                        <div class="profile-pill" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F3F4F6&color=111827" alt="User">
                            <div class="d-none d-md-block">
                                <div class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1;">{{ Auth::user()->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">User</div>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-grow-1 fade-in">
                @if(session('status'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3">{{ session('status') }}</div>
                @endif
                
                @yield('content')
            </main>
            
        </div>
    </div>
    @endguest

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Theme System JS -->
    <script src="{{ asset('js/theme.js') }}"></script>
    
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
