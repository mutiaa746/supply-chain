<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f0f2f5; font-family: Arial, sans-serif; }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: #1a1a2e;
            color: white;
            padding-top: 20px;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar .brand {
            text-align: center;
            padding: 15px 0 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 15px;
        }
        .sidebar .brand h4 { color: #00d2ff; font-weight: bold; margin: 0; }
        .sidebar .brand small { color: #aaa; font-size: 12px; }
        .sidebar .nav-link {
            color: #b0b0b0;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.05); color: white; border-left-color: #00d2ff; }
        .sidebar .nav-link.active { background: rgba(255,255,255,0.08); color: white; border-left-color: #00d2ff; }
        .sidebar .nav-link i { width: 25px; margin-right: 10px; font-size: 16px; }
        .sidebar .logout-btn {
            background: none;
            border: none;
            color: #b0b0b0;
            padding: 12px 20px;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        .sidebar .logout-btn:hover { background: rgba(255,0,0,0.1); color: #ff6b6b; border-left-color: #ff6b6b; }
        .sidebar .logout-btn i { width: 25px; margin-right: 10px; }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            padding: 20px 30px;
            min-height: 100vh;
        }

        /* STATISTIK */
        .stat-card { padding: 20px; border-radius: 10px; color: white; }
        .stat-card h2 { margin: 0; font-size: 30px; }
        .stat-card .title { opacity: 0.8; font-size: 14px; }
        .bg-blue { background: linear-gradient(135deg, #4e73df, #224abe); }
        .bg-green { background: linear-gradient(135deg, #1cc88a, #13855c); }
        .bg-red { background: linear-gradient(135deg, #e74a3b, #be2617); }
        .bg-cyan { background: linear-gradient(135deg, #36b9cc, #206d7a); }

        /* CARD */
        .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .card-header { font-weight: bold; font-size: 16px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }

        /* TABLE */
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .table tr:hover { background: #f5f5f5; }

        /* BADGE */
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; color: white; }
        .badge-danger { background: #e74a3b; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-success { background: #1cc88a; }
        .badge-info { background: #36b9cc; }

        /* BUTTON */
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; border: none; }
        .btn:hover { background: #224abe; color: white; }

        /* GRID */
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-3 { flex: 1; min-width: 200px; }
        .col-6 { flex: 1; min-width: 300px; }
        .col-12 { width: 100%; }

        /* CANVAS */
        canvas { max-height: 250px; width: 100% !important; }

        @media (max-width: 768px) {
            .sidebar { left: -250px; }
            .sidebar.open { left: 0; }
            .main-content { margin-left: 0; }
            .col-3 { min-width: 150px; }
            .col-6 { min-width: 100%; }
            .menu-toggle {
                display: block !important;
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1001;
                background: #1a1a2e;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 5px;
                font-size: 20px;
                cursor: pointer;
            }
        }
        .menu-toggle { display: none; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <h4>🚢 RiskIntel</h4>
            <small>Supply Chain Risk</small>
        </div>

        <a class="nav-link active" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/countries"><i class="fas fa-globe"></i> Countries</a>
        <a class="nav-link" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
        <a class="nav-link" href="/economic"><i class="fas fa-chart-line"></i> Economic</a>
        <a class="nav-link" href="/exchange"><i class="fas fa-money-bill-wave"></i> Exchange</a>
        <a class="nav-link" href="/news"><i class="fas fa-newspaper"></i> News</a>
        <a class="nav-link" href="/ports"><i class="fas fa-anchor"></i> Ports</a>
        <a class="nav-link" href="/ports/map"><i class="fas fa-map"></i> Port Map</a>
        <a class="nav-link" href="/risk"><i class="fas fa-exclamation-triangle"></i> Risk</a>
        <a class="nav-link" href="/tracking"><i class="fas fa-box"></i> Tracking</a>
        <a class="nav-link" href="/compare"><i class="fas fa-arrows-left-right"></i> Compare</a>
        <a class="nav-link" href="/profile"><i class="fas fa-user"></i> Profile</a>

        @if(Auth::check() && Auth::user()->email == 'admin@example.com')
            <a class="nav-link" href="/admin/dashboard"><i class="fas fa-user-shield"></i> Admin</a>
        @endif

        <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </nav>

    <!-- TOGGLE (Mobile) -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 style="margin-bottom: 20px;">📊 Dashboard Supply Chain Risk Intelligence</h1>

        <!-- STATISTIK -->
        <div class="row">
            <div class="col-3">
                <div class="stat-card bg-blue">
                    <div class="title">🌍 Countries</div>
                    <h2>{{ $totalCountries ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card bg-green">
                    <div class="title">⚓ Ports</div>
                    <h2>{{ $totalPorts ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card bg-red">
                    <div class="title">⚠️ High Risk</div>
                    <h2>{{ $highRisk ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-3">
                <div class="stat-card bg-cyan">
                    <div class="title">📰 News</div>
                    <h2>{{ $totalNews ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- GRAFIK -->
        <div class="row" style="margin-top: 20px;">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">💰 Exchange Rates (USD Base)</div>
                    <canvas id="exchangeChart"></canvas>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">📊 Risk Distribution</div>
                    <canvas id="riskChart"></canvas>
                </div>
            </div>
        </div>

        <!-- RECENT RISK -->
        <div class="row" style="margin-top: 20px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <span>🔄 Recent Risk Scores</span>
                        <a href="/risk" class="btn">View All</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Weather</th>
                                <th>Inflation</th>
                                <th>Currency</th>
                                <th>Political</th>
                                <th>Total</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRisks ?? [] as $risk)
                            <tr>
                                <td>
                                    @if($risk->country && $risk->country->flag)
                                        <img src="{{ $risk->country->flag }}" width="20" height="13">
                                    @endif
                                    {{ $risk->country->country_name ?? 'Unknown' }}
                                </td>
                                <td>{{ $risk->weather_score ?? 0 }}</td>
                                <td>{{ $risk->inflation_score ?? 0 }}</td>
                                <td>{{ $risk->currency_score ?? 0 }}</td>
                                <td>{{ $risk->news_score ?? 0 }}</td>
                                <td><strong>{{ $risk->total_score ?? 0 }}</strong></td>
                                <td>
                                    @php
                                        $lvl = $risk->risk_level ?? 'Low';
                                        $class = $lvl == 'Critical' ? 'badge-danger' : ($lvl == 'High' ? 'badge-warning' : ($lvl == 'Medium' ? 'badge-info' : 'badge-success'));
                                    @endphp
                                    <span class="badge {{ $class }}">{{ $lvl }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" style="text-align:center;color:#999;">No data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // TOGGLE SIDEBAR MOBILE
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // CHART EXCHANGE
        var exchangeData = @json($chartData ?? []);
        if (exchangeData.labels && exchangeData.labels.length > 0) {
            new Chart(document.getElementById('exchangeChart'), {
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
        }

        // CHART RISK
        var riskData = @json($riskDistribution ?? []);
        new Chart(document.getElementById('riskChart'), {
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
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    </script>

</body>
</html>