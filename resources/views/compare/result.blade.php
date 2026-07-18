<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Result - RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ... style tetap sama seperti sebelumnya ... */
        body { background: #f0f2f5; font-family: Arial, sans-serif; }
        .navbar { background: #1a1a2e; padding: 15px 30px; color: white; display: flex; justify-content: space-between; flex-wrap: wrap; }
        .navbar a { color: #aaa; margin-left: 20px; text-decoration: none; font-size: 14px; }
        .navbar a:hover { color: white; }
        .navbar .brand { color: #00d2ff; font-weight: bold; font-size: 20px; }
        .container { max-width: 1300px; margin: 20px auto; padding: 0 20px; }
        .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .card-header { font-weight: bold; font-size: 16px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .table tr:hover { background: #f5f5f5; }
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; border: none; }
        .btn:hover { background: #224abe; color: white; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; color: white; }
        .badge-danger { background: #e74a3b; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-success { background: #1cc88a; }
        .badge-info { background: #36b9cc; }
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-md-5 { flex: 1; min-width: 300px; }
        .col-md-4 { flex: 1; min-width: 250px; }
        .col-md-12 { width: 100%; }
        .col-md-2 { min-width: 80px; text-align: center; }
        .text-center { text-align: center; }
        canvas { max-height: 250px; width: 100% !important; }
        .mt-4 { margin-top: 20px; }
        .mb-3 { margin-bottom: 15px; }
        .fs-6 { font-size: 16px; }
        .px-3 { padding-left: 15px; padding-right: 15px; }
        .py-2 { padding-top: 10px; padding-bottom: 10px; }
        .shadow-sm { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
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
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><h4>🚢 RiskIntel</h4><small>Supply Chain Risk</small></div>
        <a class="nav-link" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/countries"><i class="fas fa-globe"></i> Countries</a>
        <a class="nav-link" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
        <a class="nav-link" href="/economic"><i class="fas fa-chart-line"></i> Economic</a>
        <a class="nav-link" href="/exchange"><i class="fas fa-money-bill-wave"></i> Exchange</a>
        <a class="nav-link" href="/news"><i class="fas fa-newspaper"></i> News</a>
        <a class="nav-link" href="/ports"><i class="fas fa-anchor"></i> Ports</a>
        <a class="nav-link" href="/ports/map"><i class="fas fa-map"></i> Port Map</a>
        <a class="nav-link" href="/risk"><i class="fas fa-exclamation-triangle"></i> Risk</a>
        <a class="nav-link" href="/tracking"><i class="fas fa-box"></i> Tracking</a>
        <a class="nav-link active" href="/compare"><i class="fas fa-arrows-left-right"></i> Compare</a>
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

            <h1 class="mb-4">📊 Hasil Perbandingan</h1>
            <a href="/compare" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>

            <!-- TABEL PERBANDINGAN -->
            <div class="row">
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white text-center py-3">
                            <h3 class="mb-0">
                                @if($country1->flag)
                                    <img src="{{ $country1->flag }}" width="30" height="20" class="me-2">
                                @endif
                                {{ $country1->country_name }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr><th>GDP (USD)</th>
                                    <td>
                                        @if($country1->gdp)
                                            ${{ number_format($country1->gdp, 2) }}
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Inflasi</th>
                                    <td>
                                        @if($country1->inflation)
                                            {{ number_format($country1->inflation, 2) }}%
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Populasi</th>
                                    <td>
                                        @if($country1->population)
                                            {{ number_format($country1->population) }}
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Mata Uang</th><td>{{ $country1->currency ?? '-' }}</td></tr>
                                <tr><th>Risk Score</th>
                                    <td>
                                        @php $risk1 = $country1->riskScores->last(); @endphp
                                        <strong>{{ $risk1->total_score ?? 'N/A' }}</strong>
                                    </td>
                                </tr>
                                <tr><th>Risk Level</th>
                                    <td>
                                        @php
                                            $level1 = $risk1->risk_level ?? 'Low';
                                            $color1 = $level1 == 'Critical' ? 'danger' : ($level1 == 'High' ? 'warning' : ($level1 == 'Medium' ? 'info' : 'success'));
                                        @endphp
                                        <span class="badge bg-{{ $color1 }}">{{ $level1 }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                    <div><div style="font-size: 60px; color: #aaa;">⚡</div><div style="font-size: 24px; color: #aaa;">VS</div></div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white text-center py-3">
                            <h3 class="mb-0">
                                @if($country2->flag)
                                    <img src="{{ $country2->flag }}" width="30" height="20" class="me-2">
                                @endif
                                {{ $country2->country_name }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr><th>GDP (USD)</th>
                                    <td>
                                        @if($country2->gdp)
                                            ${{ number_format($country2->gdp, 2) }}
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Inflasi</th>
                                    <td>
                                        @if($country2->inflation)
                                            {{ number_format($country2->inflation, 2) }}%
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Populasi</th>
                                    <td>
                                        @if($country2->population)
                                            {{ number_format($country2->population) }}
                                        @else
                                            <span class="text-muted">Data tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Mata Uang</th><td>{{ $country2->currency ?? '-' }}</td></tr>
                                <tr><th>Risk Score</th>
                                    <td>
                                        @php $risk2 = $country2->riskScores->last(); @endphp
                                        <strong>{{ $risk2->total_score ?? 'N/A' }}</strong>
                                    </td>
                                </tr>
                                <tr><th>Risk Level</th>
                                    <td>
                                        @php
                                            $level2 = $risk2->risk_level ?? 'Low';
                                            $color2 = $level2 == 'Critical' ? 'danger' : ($level2 == 'High' ? 'warning' : ($level2 == 'Medium' ? 'info' : 'success'));
                                        @endphp
                                        <span class="badge bg-{{ $color2 }}">{{ $level2 }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRAFIK -->
            <div class="row mt-4">
                <div class="col-md-12"><h3>📊 Grafik Perbandingan</h3></div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">💰 GDP (USD)</h5></div>
                        <div class="card-body"><canvas id="gdpChart" height="200"></canvas></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">📈 Inflasi (%)</h5></div>
                        <div class="card-body"><canvas id="inflationChart" height="200"></canvas></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">💱 Kurs (USD = 1)</h5></div>
                        <div class="card-body"><canvas id="currencyChart" height="200"></canvas></div>
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

        var country1Name = @json($country1->country_name);
        var country2Name = @json($country2->country_name);

        var gdp1 = {{ $country1->gdp ?? 0 }};
        var gdp2 = {{ $country2->gdp ?? 0 }};

        var inflation1 = {{ $country1->inflation ?? 0 }};
        var inflation2 = {{ $country2->inflation ?? 0 }};

        var currency1 = {{ $currency1 ?? 1 }};
        var currency2 = {{ $currency2 ?? 1 }};

        console.log('GDP:', gdp1, gdp2);
        console.log('Inflation:', inflation1, inflation2);
        console.log('Currency:', currency1, currency2);

        // Chart GDP
        new Chart(document.getElementById('gdpChart'), {
            type: 'bar',
            data: {
                labels: [country1Name, country2Name],
                datasets: [{
                    label: 'GDP (USD)',
                    data: [gdp1, gdp2],
                    backgroundColor: ['#4e73df', '#1cc88a']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Chart Inflasi
        new Chart(document.getElementById('inflationChart'), {
            type: 'bar',
            data: {
                labels: [country1Name, country2Name],
                datasets: [{
                    label: 'Inflasi (%)',
                    data: [inflation1, inflation2],
                    backgroundColor: ['#f6c23e', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Chart Kurs
        new Chart(document.getElementById('currencyChart'), {
            type: 'bar',
            data: {
                labels: [country1Name, country2Name],
                datasets: [{
                    label: 'Kurs (1 USD)',
                    data: [currency1, currency2],
                    backgroundColor: ['#36b9cc', '#6f42c1']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        console.log('All charts created!');
    });
    </script>

</body>
</html>