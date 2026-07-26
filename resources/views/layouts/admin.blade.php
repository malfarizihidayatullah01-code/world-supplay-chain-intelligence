<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="forest">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GSC Risk Intelligence') }} - Admin Portal</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        /* Specific Layout structure for Sidebar & Navbar */
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: var(--admin-sidebar-width);
            background-color: var(--sidebar, #13212E);
            color: var(--sidebar-text, #FFFFFF);
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
            margin-left: var(--admin-sidebar-width);
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
            background-color: var(--sidebar-hover, #1E3140);
        }
        
        .nav-link-item.active {
            color: white;
            background: linear-gradient(90deg, var(--primary, #2F7A68), var(--secondary, #56C5A8));
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        /* Floating Navbar */
        .floating-navbar {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid var(--border, #E5E7EB);
            margin-bottom: 32px;
            position: relative;
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
            background-color: var(--background, #F4F7FB);
            border: 1px solid var(--border, #E5E7EB);
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
        
        @keyframes ping {
            75%, 100% { transform: scale(2.5); opacity: 0; }
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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i data-lucide="shield-alert" style="color: var(--secondary, #56C5A8)"></i>
                Admin Panel
            </a>
            
            <nav class="sidebar-nav">
                <div class="text-uppercase mb-3 px-3" style="font-size: 0.75rem; color: rgba(255,255,255,0.4); font-weight: 600; letter-spacing: 1px;">{{ __('Administration') }}</div>
                
                <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> {{ __('Overview') }}
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i> {{ __('Users') }}
                </a>
                <a href="{{ route('admin.ports.index') }}" class="nav-link-item {{ request()->routeIs('admin.ports.*') ? 'active' : '' }}">
                    <i data-lucide="anchor"></i> {{ __('Ports DB') }}
                </a>
                <a href="{{ route('admin.articles.index') }}" class="nav-link-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                    <i data-lucide="file-text"></i> {{ __('Articles Management') }}
                </a>
                <a href="#" onclick="alert('Sinkronisasi data sedang berjalan di latar belakang...'); return false;" class="nav-link-item">
                    <i data-lucide="refresh-cw"></i> {{ __('Sync Database') }}
                </a>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563EB&color=ffffff" alt="Admin" class="rounded-circle flex-shrink-0" style="width: 36px; height: 36px;">
                        <div class="overflow-hidden">
                            <div class="text-white fw-semibold text-truncate" style="font-size: 0.85rem; line-height: 1.2;">{{ Auth::user()->name }}</div>
                            <div class="text-truncate" style="font-size: 0.75rem; color: rgba(255,255,255,0.5); text-transform: capitalize;">{{ Auth::user()->role ?? 'Admin' }}</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 flex-shrink-0 ms-2">
                        @csrf
                        <button type="submit" class="btn btn-link p-2 m-0 text-white d-flex align-items-center justify-content-center" title="{{ __('Logout') }}" style="background: rgba(255, 255, 255, 0.1); border-radius: 8px; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.15); transition: all 0.2s;">
                            <i data-lucide="log-out" style="width: 18px; height: 18px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            
            <!-- Floating Navbar -->
            <nav class="floating-navbar fade-in-up">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light d-lg-none border-0" id="mobileMenuToggle">
                        <i data-lucide="menu"></i>
                    </button>
                    <!-- Search Box -->
                    <div class="input-group d-none d-md-flex" style="width: 300px; background: var(--background, #F4F7FB); border-radius: 50px; padding: 4px 16px; border: 1px solid var(--border, #E5E7EB);">
                        <i data-lucide="search" style="width: 18px; color: var(--text-muted, #6B7280); margin-top: 6px;"></i>
                        <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Search operations...">
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-4">
                    <!-- Modern Live Date & Time -->
                    <div class="d-none d-md-flex align-items-center gap-3 bg-white px-3 py-1 rounded-pill shadow-sm border" style="background: var(--card, #FFFFFF) !important; border-color: var(--border, #E5E7EB) !important;">
                        <!-- Pulsing Dot -->
                        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 12px; height: 12px;">
                            <span class="position-absolute rounded-circle bg-success opacity-50" style="width: 100%; height: 100%; animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                            <span class="position-relative rounded-circle bg-success" style="width: 6px; height: 6px;"></span>
                        </div>
                        
                        <!-- Date -->
                        <div class="d-flex align-items-center text-muted" style="font-size: 0.75rem; font-weight: 500; letter-spacing: 0.5px; text-transform: uppercase;">
                            {{ now()->format('d M Y') }}
                        </div>
                        
                        <div class="vr" style="opacity: 0.1; height: 16px; margin-top: auto; margin-bottom: auto;"></div>
                        
                        <!-- Time -->
                        <div class="d-flex align-items-center gap-1" style="font-size: 0.85rem; font-weight: 700; color: #0F172A; font-family: monospace;">
                            <span id="liveTime">{{ now()->format('H:i:s') }}</span>
                            <span class="text-muted ms-1" style="font-size: 0.65rem; font-weight: 600;">WIB</span>
                        </div>
                    </div>
                    
                    <!-- Theme Switcher (using theme.js) -->
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

                    <!-- Language Switcher -->
                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill px-3 d-flex align-items-center gap-2 border-0 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 600; font-size: 0.85rem;">
                            <i data-lucide="languages" style="width: 16px; height: 16px;"></i>
                            {{ App::getLocale() == 'id' ? 'ID' : 'ENG' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 mt-2">
                            <li>
                                <a href="{{ route('lang.switch', 'id') }}" class="dropdown-item d-flex align-items-center gap-2 {{ App::getLocale() == 'id' ? 'active bg-primary text-white' : '' }}">
                                    <i data-lucide="check" style="width: 14px; {{ App::getLocale() == 'id' ? '' : 'visibility: hidden;' }}"></i> Bahasa Indonesia
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item d-flex align-items-center gap-2 {{ App::getLocale() == 'en' ? 'active bg-primary text-white' : '' }}">
                                    <i data-lucide="check" style="width: 14px; {{ App::getLocale() == 'en' ? '' : 'visibility: hidden;' }}"></i> English
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-grow-1 fade-in-up delay-100">
                @if(session('status'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                        <i data-lucide="check-circle" style="width: 18px; margin-right: 8px; margin-bottom: 2px;"></i>
                        {{ session('status') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
            
        </div>
    </div>
    @endguest

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Theme System JS -->
    <script src="{{ asset('js/theme.js') }}"></script>
    
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
    <script>
        // Live ticking clock
        setInterval(function() {
            var date = new Date();
            var hours = String(date.getHours()).padStart(2, '0');
            var minutes = String(date.getMinutes()).padStart(2, '0');
            var seconds = String(date.getSeconds()).padStart(2, '0');
            var timeEl = document.getElementById('liveTime');
            if(timeEl) timeEl.innerText = hours + ':' + minutes + ':' + seconds;
        }, 1000);
        
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
    @stack('scripts')
</body>
</html>
