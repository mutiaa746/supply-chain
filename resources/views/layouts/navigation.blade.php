<nav class="navbar navbar-dark bg-dark navbar-expand-md">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            🚢 Supply Chain Risk
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">📊 Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('countries') }}">🌍 Countries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('weather') }}">🌤️ Weather</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('economic') }}">📈 Economic</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('exchange') }}">💰 Exchange</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('news') }}">📰 News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ports') }}">⚓ Ports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ports.map') }}">🗺️ Map</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('risk') }}">⚠️ Risk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('watchlist') }}">⭐ Watchlist</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            👤 {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>