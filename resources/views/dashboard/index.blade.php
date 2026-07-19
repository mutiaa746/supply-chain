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
        .stat-card { padding: 20px; border-radius: 10px; color: white; }
        .stat-card h2 { margin: 0; font-size: 30px; }
        .stat-card .title { opacity: 0.8; font-size: 14px; }
        .bg-blue { background: linear-gradient(135deg, #4e73df, #224abe); }
        .bg-green { background: linear-gradient(135deg, #1cc88a, #13855c); }
        .bg-red { background: linear-gradient(135deg, #e74a3b, #be2617); }
        .bg-cyan { background: linear-gradient(135deg, #36b9cc, #206d7a); }
        
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-3 { flex: 1; min-width: 200px; }
        .col-4 { flex: 1; min-width: 250px; }
        .col-6 { flex: 1; min-width: 300px; }
        .col-12 { width: 100%; }
        .mb-4 { margin-bottom: 20px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-4 { margin-top: 20px; }
        .text-muted { color: #888; }
        .text-center { text-align: center; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .table tr:hover { background: #f5f5f5; }
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; color: white; }
        .badge-danger { background: #e74a3b; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-success { background: #1cc88a; }
        .badge-info { background: #36b9cc; }
        .form-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; width: 100%; }
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; border: none; }
        .btn:hover { background: #224abe; color: white; }
        .btn-primary { background: #4e73df; color: white; }
        .btn-primary:hover { background: #224abe; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .input-group { display: flex; }
        .form-control { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; flex: 1; }
        .table-responsive { overflow-x: auto; }
        .me-2 { margin-right: 10px; }
        .ms-2 { margin-left: 10px; }
        .float-end { float: right; }
        canvas { max-height: 250px; width: 100% !important; }
        .display-1 { font-size: 48px; }
        .border-bottom { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><h4>🚢 RiskIntel</h4><small>Supply Chain Risk</small></div>
        <a class="nav-link active" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
        <a class="nav-link" href="/economic"><i class="fas fa-chart-line"></i> Economic</a>
        <a class="nav-link" href="/exchange"><i class="fas fa-money-bill-wave"></i> Exchange</a>
        <a class="nav-link" href="/news"><i class="fas fa-newspaper"></i> News</a>
        <a class="nav-link" href="/ports"><i class="fas fa-anchor"></i> Ports</a>
        <a class="nav-link" href="/risk"><i class="fas fa-exclamation-triangle"></i> Risk</a>
        <a class="nav-link" href="/visualization"><i class="fas fa-chart-pie"></i> Visualization</a>
        <a class="nav-link" href="/route-simulation"><i class="fas fa-route"></i> Route Simulation</a>
        <a class="nav-link" href="/compare"><i class="fas fa-arrows-left-right"></i> Compare</a>
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
                <div class="col-3"><div class="stat-card bg-blue"><div class="title">🌍 Countries</div><h2>{{ $totalCountries ?? 0 }}</h2></div></div>
                <div class="col-3"><div class="stat-card bg-green"><div class="title">⚓ Ports</div><h2>{{ $totalPorts ?? 0 }}</h2></div></div>
                <div class="col-3"><div class="stat-card bg-red"><div class="title">⚠️ High Risk</div><h2>{{ $highRisk ?? 0 }}</h2></div></div>
                <div class="col-3"><div class="stat-card bg-cyan"><div class="title">📰 News</div><h2>{{ $totalNews ?? 0 }}</h2></div></div>
            </div>

            <!-- SEARCH -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">🔍 Cari Negara</h5></div>
                        <div class="card-body">
                            <form method="GET" class="row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Ketik nama negara..." value="{{ $search ?? '' }}">
                                        <button type="submit" class="btn btn-primary">Cari</button>
                                        @if($search)
                                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select name="country" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Pilih dari Daftar --</option>
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

            <!-- DETAIL NEGARA -->
            @if($selectedCountry)
            <div class="row mb-4">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white"><h5 class="mb-0">
                            @if($selectedCountry->flag)<img src="{{ $selectedCountry->flag }}" width="30" height="20" class="me-2">@else<img src="https://flagcdn.com/w40/{{ strtolower($selectedCountry->country_code) }}.png" width="30" height="20" class="me-2">@endif
                            {{ $selectedCountry->country_name }}
                        </h5></div>
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
                <div class="col-4">
                    <div class="card">
                        <div class="card-header bg-warning text-white"><h5 class="mb-0">⚠️ Risk Score</h5></div>
                        <div class="card-body">
                            @if($selectedRisk)
                            <table class="table table-sm">
                                <tr><th>Weather</th><td>{{ $selectedRisk->weather_score }}</td></tr>
                                <tr><th>Inflation</th><td>{{ $selectedRisk->inflation_score }}</td></tr>
                                <tr><th>Currency</th><td>{{ $selectedRisk->currency_score }}</td></tr>
                                <tr><th>Political</th><td>{{ $selectedRisk->news_score }}</td></tr>
                                <tr><th><strong>Total</strong></th><td><strong>{{ $selectedRisk->total_score }}</strong></td></tr>
                                <tr><th>Level</th>
                                    <td>@php $level = $selectedRisk->risk_level ?? 'Low'; $color = $level == 'Critical' ? 'danger' : ($level == 'High' ? 'warning' : ($level == 'Medium' ? 'info' : 'success')); @endphp
                                    <span class="badge bg-{{ $color }}">{{ $level }}</span>
                                </td></tr>
                            </table>
                            @else <p class="text-muted">No risk data</p> @endif
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header bg-info text-white"><h5 class="mb-0">🌤️ Weather</h5></div>
                        <div class="card-body text-center">
                            @if($weather)
                                @php $temp = $weather['temperature'] ?? 0; $desc = $weather['description'] ?? 'Tidak Diketahui'; @endphp
                                <div class="display-1">@if($temp > 30) ☀️ @elseif($temp > 20) ⛅ @elseif($temp > 10) 🌤️ @elseif($temp > 0) 🌧️ @else ❄️ @endif</div>
                                <h2>{{ $temp }}°C</h2>
                                <p>{{ $desc }}</p>
                                <p class="text-muted">Wind: {{ $weather['windspeed'] ?? 0 }} km/h</p>
                            @else <p class="text-muted">Weather not available</p> @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- GRAFIK -->
            <div class="row mt-4">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">💰 Exchange Rates (USD Base)</h5></div>
                        <div class="card-body">
                            <canvas id="exchangeChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">📊 Risk Distribution</h5></div>
                        <div class="card-body">
                            <canvas id="riskChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Chart.js loaded!', typeof Chart !== 'undefined');

        // ========== EXCHANGE CHART ==========
        var exchangeData = @json($chartData ?? []);
        console.log('Exchange Data:', exchangeData);

        var exchangeCtx = document.getElementById('exchangeChart');
        if (exchangeCtx && exchangeData.labels && exchangeData.labels.length > 0) {
            new Chart(exchangeCtx, {
                type: 'bar',
                data: {
                    labels: exchangeData.labels,
                    datasets: [{
                        label: 'Exchange Rate (USD = 1)',
                        data: exchangeData.rates,
                        backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#6f42c1','#fd7e14','#20c997','#0dcaf0','#d63384'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            console.log('Exchange chart created!');
        } else {
            console.log('No exchange data');
        }

        // ========== RISK CHART ==========
        var riskData = @json($riskDistribution ?? []);
        console.log('Risk Data:', riskData);

        var riskCtx = document.getElementById('riskChart');
        if (riskCtx) {
            var totalRisk = (riskData.High || 0) + (riskData.Medium || 0) + (riskData.Low || 0);
            if (totalRisk > 0) {
                new Chart(riskCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['High Risk', 'Medium Risk', 'Low Risk'],
                        datasets: [{
                            data: [riskData.High || 0, riskData.Medium || 0, riskData.Low || 0],
                            backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a'],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
                console.log('Risk chart created!');
            } else {
                console.log('No risk data');
            }
        }
    });
    </script>

</body>
</html>