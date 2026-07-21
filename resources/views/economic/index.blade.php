<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Economic & Exchange - RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f0f2f5; font-family: Arial, sans-serif; }
        
        /* SIDEBAR */
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
        .card-header { font-weight: bold; font-size: 16px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .table tr:hover { background: #f5f5f5; }
        
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; color: white; }
        .badge-danger { background: #e74a3b; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-success { background: #1cc88a; }
        .badge-info { background: #36b9cc; }
        
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; border: none; }
        .btn:hover { background: #224abe; color: white; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-success { background: #1cc88a; }
        .btn-success:hover { background: #13855c; }
        
        .form-control { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; width: 100%; }
        .form-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; width: 100%; }
        
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-6 { flex: 1; min-width: 300px; }
        .col-4 { flex: 1; min-width: 250px; }
        .col-12 { width: 100%; }
        .mb-4 { margin-bottom: 20px; }
        .mb-3 { margin-bottom: 15px; }
        .mb-0 { margin-bottom: 0; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        .text-muted { color: #888; }
        .text-center { text-align: center; }
        .fs-6 { font-size: 16px; }
        .px-3 { padding-left: 15px; padding-right: 15px; }
        .py-2 { padding-top: 10px; padding-bottom: 10px; }
        .table-responsive { overflow-x: auto; }
        .me-2 { margin-right: 10px; }
        .ms-2 { margin-left: 10px; }
        .d-flex { display: flex; }
        .gap-2 { gap: 10px; }
        .flex-wrap { flex-wrap: wrap; }
        canvas { max-height: 250px; width: 100% !important; }
        .badge-primary { background: #4e73df; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-success { background: #1cc88a; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-danger { background: #e74a3b; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-warning { background: #f6c23e; color: #333; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-purple { background: #6f42c1; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
        .badge-success { background: #1cc88a; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><h4>🚢 RiskIntel</h4><small>Supply Chain Risk</small></div>
        <a class="nav-link" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
        <a class="nav-link active" href="/economic"><i class="fas fa-chart-line"></i> Economic</a>
        <a class="nav-link" href="/exchange"><i class="fas fa-money-bill-wave"></i> Exchange</a>
        <a class="nav-link" href="/news"><i class="fas fa-newspaper"></i> News</a>
        <a class="nav-link" href="/ports"><i class="fas fa-anchor"></i> Ports</a>
        <a class="nav-link" href="/ports/map"><i class="fas fa-map"></i> Port Map</a>
        <a class="nav-link" href="/risk"><i class="fas fa-exclamation-triangle"></i> Risk</a>
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

            <h1 class="mb-4">📊 Economic & Exchange Dashboard</h1>
            <p class="text-muted">Data ekonomi, kurs mata uang, dan visualisasi risiko</p>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-danger">{{ session('error') }}</div>
            @endif

            <!-- ============================================================ -->
            <!-- SECTION 1: EXCHANGE RATES (Multi Currency Chart) -->
            <!-- ============================================================ -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            💰 Currency Exchange Rates
                            <span class="badge-success ms-2">Live from API</span>
                            <a href="/exchange/fetch" class="btn btn-sm btn-primary ms-2">Refresh</a>
                        </div>
                        <div class="card-body">
                            <!-- Search -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form method="GET" class="d-flex gap-2 flex-wrap">
                                        <input type="text" name="search" class="form-control" style="max-width: 300px;" 
                                               placeholder="Cari mata uang (IDR, USD, EUR...)..." 
                                               value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">Cari</button>
                                        <a href="/economic" class="btn btn-secondary">Reset</a>
                                    </form>
                                    @if(request('search'))
                                        <small class="text-muted mt-1 d-block">
                                            Menampilkan hasil untuk: <strong>{{ request('search') }}</strong>
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Grafik Exchange Multi Currency -->
                            <div style="position: relative; height: 300px;">
                                <canvas id="exchangeChart"></canvas>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="badge-primary">🔵 IDR (Rupiah)</span>
                                <span class="badge-success">🟢 EUR (Euro)</span>
                                <span class="badge-danger">🔴 GBP (Pound)</span>
                                <span class="badge-warning">🟡 JPY (Yen)</span>
                                <span class="badge-purple">🟣 CNY (Yuan)</span>
                                <span class="badge-info">🔷 SGD (Dollar)</span>
                                <span class="badge-warning">🟠 MYR (Ringgit)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 2: 4 GRAFIK EKONOMI -->
            <!-- ============================================================ -->
            <div class="row">
                <!-- GDP CHART -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-header">💰 GDP Top 10 Countries (Trillion USD)</div>
                        <div class="card-body"><canvas id="gdpChart" height="200"></canvas></div>
                    </div>
                </div>

                <!-- INFLATION CHART -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-header">📈 Inflation Top 10 Countries (%)</div>
                        <div class="card-body"><canvas id="inflationChart" height="200"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- CURRENCY CHART -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-header">💱 Exchange Rates (USD = 1)</div>
                        <div class="card-body"><canvas id="currencyChart" height="200"></canvas></div>
                    </div>
                </div>

                <!-- RISK CHART -->
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-header">⚠️ Risk Score Top 10 Countries</div>
                        <div class="card-body"><canvas id="riskChart" height="200"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 3: RISK DISTRIBUTION -->
            <!-- ============================================================ -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">📊 Risk Distribution</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <canvas id="riskDistributionChart" height="200"></canvas>
                                </div>
                                <div class="col-6">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr><th>Level</th><th>Count</th><th>Percentage</th></tr>
                                        </thead>
                                        <tbody>
                                            @php $total = array_sum($riskDistribution); @endphp
                                            <tr>
                                                <td><span class="badge bg-danger">🔴 High</span></td>
                                                <td>{{ $riskDistribution['High'] ?? 0 }}</td>
                                                <td>{{ $total > 0 ? round(($riskDistribution['High'] / $total) * 100, 1) : 0 }}%</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-warning">🟡 Medium</span></td>
                                                <td>{{ $riskDistribution['Medium'] ?? 0 }}</td>
                                                <td>{{ $total > 0 ? round(($riskDistribution['Medium'] / $total) * 100, 1) : 0 }}%</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-success">🟢 Low</span></td>
                                                <td>{{ $riskDistribution['Low'] ?? 0 }}</td>
                                                <td>{{ $total > 0 ? round(($riskDistribution['Low'] / $total) * 100, 1) : 0 }}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 4: TABEL EKONOMI NEGARA -->
            <!-- ============================================================ -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">📋 Country Economic Data</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Country</th>
                                            <th>GDP (USD)</th>
                                            <th>Inflation</th>
                                            <th>Population</th>
                                            <th>Currency</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($countries as $country)
                                        <tr>
                                            <td>
                                                @if($country->flag)
                                                    <img src="{{ $country->flag }}" width="20" height="13" class="me-1">
                                                @endif
                                                {{ $country->country_name }}
                                            </td>
                                            <td>${{ number_format($country->gdp ?? 0, 2) }}</td>
                                            <td>{{ $country->inflation ?? 0 }}%</td>
                                            <td>{{ number_format($country->population ?? 0) }}</td>
                                            <td>{{ $country->currency ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No data available.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 5: TABEL EXCHANGE RATES -->
            <!-- ============================================================ -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">💱 All Exchange Rates (USD Base)</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Currency</th>
                                            <th>Rate (1 USD)</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($exchangeRates as $rate)
                                        <tr>
                                            <td><strong>{{ $rate->target_currency }}</strong></td>
                                            <td>{{ number_format($rate->rate, 4) }}</td>
                                            <td>{{ $rate->updated_at->diffForHumans() }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No exchange rate data.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
        console.log('Chart.js loaded!');

        // ========== 1. EXCHANGE CHART (Multi Currency) ==========
        var chartRates = @json($chartRates ?? []);
        console.log('Chart Rates:', chartRates);
        if (chartRates.length > 0) {
            var labels = chartRates.map(function(r) { return r.target_currency; });
            var values = chartRates.map(function(r) { return r.rate; });
            
            new Chart(document.getElementById('exchangeChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Exchange Rate (USD = 1)',
                        data: values,
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
        }

        // ========== 2. GDP CHART ==========
        var gdpData = @json($gdpData ?? []);
        console.log('GDP Data:', gdpData);
        if (gdpData.length > 0) {
            new Chart(document.getElementById('gdpChart'), {
                type: 'bar',
                data: {
                    labels: gdpData.map(function(d) { return d.country; }),
                    datasets: [{
                        label: 'GDP (Trillion USD)',
                        data: gdpData.map(function(d) { return d.gdp; }),
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
        }

        // ========== 3. INFLATION CHART ==========
        var inflationData = @json($inflationData ?? []);
        console.log('Inflation Data:', inflationData);
        if (inflationData.length > 0) {
            new Chart(document.getElementById('inflationChart'), {
                type: 'bar',
                data: {
                    labels: inflationData.map(function(d) { return d.country; }),
                    datasets: [{
                        label: 'Inflation (%)',
                        data: inflationData.map(function(d) { return d.inflation; }),
                        backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#4e73df','#36b9cc','#6f42c1','#fd7e14','#20c997','#0dcaf0','#d63384'],
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
        }

        // ========== 4. CURRENCY CHART ==========
        var currencyData = @json($currencyData ?? []);
        console.log('Currency Data:', currencyData);
        if (currencyData.length > 0) {
            new Chart(document.getElementById('currencyChart'), {
                type: 'bar',
                data: {
                    labels: currencyData.map(function(d) { return d.currency; }),
                    datasets: [{
                        label: 'Exchange Rate (1 USD)',
                        data: currencyData.map(function(d) { return d.rate; }),
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
        }

        // ========== 5. RISK CHART ==========
        var riskData = @json($riskData ?? []);
        console.log('Risk Data:', riskData);
        if (riskData.length > 0) {
            new Chart(document.getElementById('riskChart'), {
                type: 'bar',
                data: {
                    labels: riskData.map(function(d) { return d.country; }),
                    datasets: [{
                        label: 'Risk Score',
                        data: riskData.map(function(d) { return d.risk; }),
                        backgroundColor: riskData.map(function(d) {
                            return d.risk >= 70 ? '#e74a3b' : (d.risk >= 50 ? '#f6c23e' : '#1cc88a');
                        }),
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
        }

        // ========== 6. RISK DISTRIBUTION CHART ==========
        var riskDistribution = @json($riskDistribution ?? []);
        console.log('Risk Distribution:', riskDistribution);
        new Chart(document.getElementById('riskDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: ['High Risk', 'Medium Risk', 'Low Risk'],
                datasets: [{
                    data: [riskDistribution.High || 0, riskDistribution.Medium || 0, riskDistribution.Low || 0],
                    backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a'],
                    borderColor: '#fff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true, pointStyle: 'circle' }
                    }
                },
                cutout: '65%'
            }
        });
    });
    </script>

</body>
</html>