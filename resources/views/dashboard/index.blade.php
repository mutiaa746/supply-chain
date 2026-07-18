<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f0f2f5; font-family: Arial, sans-serif; }
        .sidebar {
            position: fixed; top: 0; left: 0; width: 250px; height: 100vh;
            background: #1a1a2e; color: white; padding-top: 20px; z-index: 1000; overflow-y: auto;
        }
        .sidebar .brand { text-align: center; padding: 15px 0 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar .brand h4 { color: #00d2ff; font-weight: bold; margin: 0; }
        .sidebar .brand small { color: #aaa; font-size: 12px; }
        .sidebar .nav-link { color: #b0b0b0; padding: 12px 20px; display: flex; align-items: center; text-decoration: none; border-left: 3px solid transparent; transition: all 0.3s; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.05); color: white; border-left-color: #00d2ff; }
        .sidebar .nav-link.active { background: rgba(255,255,255,0.08); color: white; border-left-color: #00d2ff; }
        .sidebar .nav-link i { width: 25px; margin-right: 10px; font-size: 16px; }
        .sidebar .logout-btn { background: none; border: none; color: #b0b0b0; padding: 12px 20px; width: 100%; text-align: left; display: flex; align-items: center; cursor: pointer; border-left: 3px solid transparent; transition: all 0.3s; }
        .sidebar .logout-btn:hover { background: rgba(255,0,0,0.1); color: #ff6b6b; border-left-color: #ff6b6b; }
        .sidebar .logout-btn i { width: 25px; margin-right: 10px; }
        .main-content { margin-left: 250px; padding: 20px 30px; min-height: 100vh; }
        .menu-toggle { display: none; position: fixed; top: 10px; left: 10px; z-index: 1001; background: #1a1a2e; color: white; border: none; padding: 10px 15px; border-radius: 5px; font-size: 20px; cursor: pointer; }
        @media (max-width: 768px) { .sidebar { left: -250px; } .sidebar.open { left: 0; } .main-content { margin-left: 0; } .menu-toggle { display: block; } }
        .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .card-header { font-weight: bold; font-size: 16px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .table tr:hover { background: #f5f5f5; }
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; color: white; }
        .badge-danger { background: #e74a3b; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-success { background: #1cc88a; }
        .badge-info { background: #36b9cc; }
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; border: none; }
        .btn:hover { background: #224abe; color: white; }
        .btn-primary { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; border: none; }
        .btn-primary:hover { background: #224abe; }
        .btn-sm { padding: 4px 10px; font-size: 12px; }
        .form-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; width: 100%; }
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-3 { flex: 1; min-width: 200px; }
        .col-4 { flex: 1; min-width: 250px; }
        .col-6 { flex: 1; min-width: 300px; }
        .col-12 { width: 100%; }
        .mb-4 { margin-bottom: 20px; }
        .mb-0 { margin-bottom: 0; }
        .mt-2 { margin-top: 10px; }
        .text-muted { color: #888; }
        .text-center { text-align: center; }
        .fs-6 { font-size: 16px; }
        .px-3 { padding-left: 15px; padding-right: 15px; }
        .py-2 { padding-top: 10px; padding-bottom: 10px; }
        .table-responsive { overflow-x: auto; }
        .me-2 { margin-right: 10px; }
        .ms-2 { margin-left: 10px; }
        .float-end { float: right; }
        canvas { max-height: 250px; width: 100% !important; }
        .display-1 { font-size: 60px; }
        .btn-warning { background: #f6c23e; color: #333; border: none; padding: 6px 15px; border-radius: 5px; }
        .btn-warning:hover { background: #d39e00; color: #333; }
        .btn-danger { background: #e74a3b; color: white; border: none; padding: 6px 15px; border-radius: 5px; }
        .btn-danger:hover { background: #be2617; color: white; }
        .border p-2 rounded { border: 1px solid #ddd; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><h4>🚢 RiskIntel</h4><small>Supply Chain Risk</small></div>
        <a class="nav-link active" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/countries"><i class="fas fa-globe"></i> Countries</a>
        <a class="nav-link" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
        <a class="nav-link" href="/economic"><i class="fas fa-chart-line"></i> Economic</a>
        <a class="nav-link" href="/exchange"><i class="fas fa-money-bill-wave"></i> Exchange</a>
        <a class="nav-link" href="/news"><i class="fas fa-newspaper"></i> News</a>
        <a class="nav-link" href="/ports"><i class="fas fa-anchor"></i> Ports</a>
        <a class="nav-link" href="/ports/map"><i class="fas fa-map"></i> Port Map</a>
        <a class="nav-link" href="/risk"><i class="fas fa-exclamation-triangle"></i> Risk</a>
        <a class="nav-link" href="/route-simulation"><i class="fas fa-route"></i> Route Simulation</a>
        <a class="nav-link" href="/profile"><i class="fas fa-user"></i> Profile</a>
        <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </nav>

    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">

            <h1 class="mb-4">📊 Dashboard Supply Chain Risk Intelligence</h1>

            <!-- STATISTIK -->
            <div class="row mb-4">
                <div class="col-3"><div class="card text-white bg-primary"><div class="card-body"><h5 class="card-title">🌍 Countries</h5><h2 class="mb-0">{{ $totalCountries ?? 0 }}</h2></div></div></div>
                <div class="col-3"><div class="card text-white bg-success"><div class="card-body"><h5 class="card-title">⚓ Ports</h5><h2 class="mb-0">{{ $totalPorts ?? 0 }}</h2></div></div></div>
                <div class="col-3"><div class="card text-white bg-danger"><div class="card-body"><h5 class="card-title">⚠️ High Risk</h5><h2 class="mb-0">{{ $highRisk ?? 0 }}</h2></div></div></div>
                <div class="col-3"><div class="card text-white bg-info"><div class="card-body"><h5 class="card-title">📰 News</h5><h2 class="mb-0">{{ $totalNews ?? 0 }}</h2></div></div></div>
            </div>

            <!-- COUNTRY SELECTOR -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">🌍 Pilih Negara</h5></div>
                        <div class="card-body">
                            <form method="GET" class="row">
                                <div class="col-md-4">
                                    <select name="country" class="form-select" onchange="this.form.submit()">
                                        @foreach($countries as $c)
                                        <option value="{{ $c->id }}" {{ $selectedCountry && $selectedCountry->id == $c->id ? 'selected' : '' }}>
                                            {{ $c->country_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAIL NEGARA (SEPERTI DI COUNTRIES) -->
            @if($selectedCountry)
            <div class="row">
                <!-- Country Info -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">📋 Country Info</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr><th>Capital</th><td>{{ $selectedCountry->capital ?? '-' }}</td></tr>
                                <tr><th>Region</th><td>{{ $selectedCountry->region ?? '-' }}</td></tr>
                                <tr><th>Currency</th><td>{{ $selectedCountry->currency ?? '-' }}</td></tr>
                                <tr><th>Population</th><td>{{ number_format($selectedCountry->population ?? 0) }}</td></tr>
                                <tr><th>GDP</th><td>${{ number_format($selectedCountry->gdp ?? 0, 2) }}</td></tr>
                                <tr><th>Inflation</th><td>{{ $selectedCountry->inflation ?? 0 }}%</td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Risk Score -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">⚠️ Risk Score</h5>
                        </div>
                        <div class="card-body">
                            @if($selectedRisk)
                            <table class="table table-sm">
                                <tr><th>Weather Risk</th><td>{{ $selectedRisk->weather_score }}</td></tr>
                                <tr><th>Inflation Risk</th><td>{{ $selectedRisk->inflation_score }}</td></tr>
                                <tr><th>Currency Risk</th><td>{{ $selectedRisk->currency_score }}</td></tr>
                                <tr><th>Political Risk</th><td>{{ $selectedRisk->news_score }}</td></tr>
                                <tr><th><strong>Total</strong></th><td><strong>{{ $selectedRisk->total_score }}</strong></td></tr>
                                <tr><th>Level</th>
                                    <td>
                                        @php
                                            $level = $selectedRisk->risk_level ?? 'Low';
                                            $color = $level == 'Critical' ? 'danger' : ($level == 'High' ? 'warning' : ($level == 'Medium' ? 'info' : 'success'));
                                        @endphp
                                        <span class="badge bg-{{ $color }} fs-6">{{ $level }}</span>
                                    </td>
                                </tr>
                            </table>
                            @else
                            <p class="text-muted">No risk data available.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Weather -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">🌤️ Current Weather</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($weather)
                                @php
                                    $temp = $weather['temperature'] ?? 0;
                                    $wind = $weather['windspeed'] ?? 0;
                                    $code = $weather['weathercode'] ?? 0;
                                    $desc = $weather['description'] ?? 'Unknown';
                                @endphp
                                <div class="display-1">
                                    @if($temp > 30) ☀️
                                    @elseif($temp > 20) ⛅
                                    @elseif($temp > 10) 🌤️
                                    @elseif($temp > 0) 🌧️
                                    @else ❄️
                                    @endif
                                </div>
                                <h2>{{ $temp }}°C</h2>
                                <p>{{ $desc }}</p>
                                <p class="text-muted">Wind: {{ $wind }} km/h</p>
                            @else
                                <p class="text-muted">Weather data not available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- News -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">📰 Latest News</h5>
                        </div>
                        <div class="card-body">
                            @if($news->count() > 0)
                                <div class="row">
                                    @foreach($news as $item)
                                    <div class="col-md-6 mb-2">
                                        <div class="border p-2 rounded">
                                            <h6>{{ $item->title }}</h6>
                                            <p class="text-muted small">{{ Str::limit($item->description ?? '', 100) }}</p>
                                            @php
                                                $sentiment = $item->sentiment ?? 'neutral';
                                                $color = $sentiment == 'positive' ? 'success' : ($sentiment == 'negative' ? 'danger' : 'secondary');
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ ucfirst($sentiment) }}</span>
                                            <small class="text-muted ms-2">{{ $item->source ?? 'Unknown' }}</small>
                                            @if($item->url && $item->url != '#')
                                                <a href="{{ $item->url }}" target="_blank" class="btn btn-sm btn-outline-primary float-end">Read</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No news available. <a href="/news/fetch/{{ $selectedCountry->country_code }}" class="btn btn-sm btn-primary ms-2">Fetch news</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ports -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">⚓ Ports in {{ $selectedCountry->country_name }}</h5>
                        </div>
                        <div class="card-body">
                            @if($ports->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr><th>Port Name</th><th>Type</th><th>Size</th><th>Status</th></tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ports as $port)
                                            <tr>
                                                <td>{{ $port->port_name }}</td>
                                                <td>{{ $port->harbor_type ?? '-' }}</td>
                                                <td>{{ $port->harbor_size ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $status = $port->status ?? 'Unknown';
                                                        $color = $status == 'Operational' ? 'success' : ($status == 'Under Construction' ? 'warning' : 'danger');
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">{{ $status }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No ports data available for this country.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Watchlist Button -->
            <div class="row mt-3">
                <div class="col-md-12">
                    @auth
                        @php
                            $isWatched = \App\Models\Watchlist::where('user_id', Auth::id())
                                ->where('country_id', $selectedCountry->id)
                                ->exists();
                        @endphp
                        <form action="{{ route('watchlist.toggle') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="country_id" value="{{ $selectedCountry->id }}">
                            <button type="submit" class="btn {{ $isWatched ? 'btn-danger' : 'btn-warning' }}">
                                <i class="fas fa-star"></i>
                                {{ $isWatched ? ' Hapus dari Pantauan' : ' Tambah ke Pantauan' }}
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>

</body>
</html>