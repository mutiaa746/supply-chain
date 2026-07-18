<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Supply Chain Risk')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #1a1a2e;
            color: #fff;
            padding-top: 20px;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }
        .sidebar .brand {
            text-align: center;
            padding: 15px 0 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
        }
        .sidebar .brand h4 {
            color: #00d2ff;
            font-weight: bold;
            margin: 0;
        }
        .sidebar .brand small {
            color: #aaa;
            font-size: 12px;
        }
        .sidebar .nav-link {
            color: #b0b0b0;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
            border-left-color: #00d2ff;
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-left-color: #00d2ff;
        }
        .sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
            font-size: 16px;
        }
        .sidebar .logout-btn {
            background: none;
            border: none;
            color: #b0b0b0;
            padding: 12px 20px;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            cursor: pointer;
            border-left: 3px solid transparent;
        }
        .sidebar .logout-btn:hover {
            background: rgba(255,0,0,0.1);
            color: #ff6b6b;
            border-left-color: #ff6b6b;
        }
        .sidebar .logout-btn i {
            width: 25px;
            margin-right: 10px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px 30px;
            min-height: 100vh;
        }
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background: #1a1a2e;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 20px;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }
            .sidebar.open {
                left: 0;
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .sidebar-toggle {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

   
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <h4>🚢 RiskIntel</h4>
            <small>Supply Chain Risk</small>
        </div>

    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a class="nav-link {{ request()->routeIs('countries') ? 'active' : '' }}" href="{{ route('countries') }}">
        <i class="fas fa-globe"></i> Countries
    </a>
    <a class="nav-link {{ request()->routeIs('weather') ? 'active' : '' }}" href="{{ route('weather') }}">
        <i class="fas fa-cloud-sun"></i> Weather
    </a>
    <a class="nav-link {{ request()->routeIs('economic') ? 'active' : '' }}" href="{{ route('economic') }}">
        <i class="fas fa-chart-line"></i> Economic
    </a>
    <a class="nav-link {{ request()->routeIs('exchange') ? 'active' : '' }}" href="{{ route('exchange') }}">
        <i class="fas fa-money-bill-wave"></i> Exchange
    </a>
    <a class="nav-link {{ request()->routeIs('news') ? 'active' : '' }}" href="{{ route('news') }}" >
        <i class="fas fa-newspaper"></i> News
    </a>   
    <a class="nav-link {{ request()->routeIs('ports') ? 'active' : '' }}" href="{{ route('ports') }}">
        <i class="fas fa-anchor"></i> Ports
    </a>
    <a class="nav-link {{ request()->routeIs('ports.map') ? 'active' : '' }}" href="{{ route('ports.map') }}">
        <i class="fas fa-map"></i> Port Map
    </a>
    <a class="nav-link {{ request()->routeIs('risk') ? 'active' : '' }}" href="{{ route('risk') }}">
        <i class="fas fa-exclamation-triangle"></i> Risk
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('route-simulation*') ? 'active' : '' }}" href="{{ route('route-simulation') }}">
            <i class="fas fa-route"></i> Route Simulation
        </a>
    </li>
    <a class="nav-link {{ request()->routeIs('compare') ? 'active' : '' }}" href="{{ route('compare') }}">
        <i class="fas fa-arrows-left-right"></i> Compare
    </a>
    <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
    <i class="fas fa-user"></i> Profile
    </a>    
    
    @if(Auth::check() && Auth::user()->email == 'admin@example.com')
        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-user-shield"></i> Admin
        </a>
        @endif
        <a class="nav-link" href="{{ route('profile.edit') }}">
            <i class="fas fa-user"></i> Profile
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </nav>

    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            var sidebar = document.getElementById('sidebar');
            var toggle = document.getElementById('sidebarToggle');
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>