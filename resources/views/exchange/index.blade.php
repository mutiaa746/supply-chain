<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rates - RiskIntel</title>
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
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; border: none; }
        .btn:hover { background: #224abe; color: white; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .badge-success { background: #1cc88a; color: white; padding: 3px 10px; border-radius: 20px; font-size: 12px; }
        .d-flex { display: flex; }
        .gap-2 { gap: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .mb-4 { margin-bottom: 20px; }
        .text-muted { color: #888; }
        .text-center { text-align: center; }
        canvas { max-height: 350px; width: 100% !important; }
        .form-control { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; }
        .table-responsive { overflow-x: auto; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
        .badge-primary { background: #4e73df; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-success { background: #1cc88a; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-danger { background: #e74a3b; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-warning { background: #f6c23e; color: #333; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .badge-purple { background: #6f42c1; color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .row { display: flex; flex-wrap: wrap; margin: 0 -10px; }
        .col-md-12 { width: 100%; padding: 0 10px; }
        .col-md-6 { width: 50%; padding: 0 10px; }
        @media (max-width: 768px) { .col-md-6 { width: 100%; } }
        #exchangeChart { max-height: 350px; width: 100%; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><h4>🚢 RiskIntel</h4><small>Supply Chain Risk</small></div>
        <a class="nav-link" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
        <a class="nav-link" href="/economic"><i class="fas fa-chart-line"></i> Economic</a>
        <a class="nav-link active" href="/exchange"><i class="fas fa-money-bill-wave"></i> Exchange</a>
        <a class="nav-link" href="/news"><i class="fas fa-newspaper"></i> News</a>
        <a class="nav-link" href="/ports"><i class="fas fa-anchor"></i> Ports</a>
        <a class="nav-link" href="/ports/map"><i class="fas fa-map"></i> Port Map</a>
        <a class="nav-link" href="/risk"><i class="fas fa-exclamation-triangle"></i> Risk</a>
        <a class="nav-link" href="/route-simulation"><i class="fas fa-route"></i> Route Simulation</a>
        <a class="nav-link" href="/compare"><i class="fas fa-arrows-left-right"></i> Compare</a>
        <a class="nav-link" href="/watchlist"><i class="fas fa-star"></i> Watchlist</a>
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

            <div class="row">
                <div class="col-md-12">
                    <h1 class="mb-4">💰 Currency Exchange Rates</h1>
                    <p class="text-muted">
                        Base Currency: <strong>USD</strong>
                        <span class="badge-success ms-2">Live from API</span>
                        <a href="/exchange/fetch" class="btn btn-sm btn-primary ms-2">
                            <i class="fas fa-sync"></i> Refresh
                        </a>
                    </p>
                    @if(session('success'))
                        <div class="alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert-danger">{{ session('error') }}</div>
                    @endif
                </div>
            </div>

            <!-- SEARCH -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari mata uang (IDR, USD, EUR...)..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="/exchange" class="btn btn-secondary">Reset</a>
                    </form>
                    @if(request('search'))
                        <small class="text-muted mt-1 d-block">
                            Menampilkan hasil untuk: <strong>{{ request('search') }}</strong>
                        </small>
                    @endif
                </div>
            </div>

            <!-- GRAFIK -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">📊 Exchange Rate Chart (USD = 1) - Multi Currency</div>
                        <div class="card-body">
                            <div style="position: relative; height: 350px;">
                                <canvas id="exchangeChart"></canvas>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="badge-primary">🔵 IDR (Rupiah)</span>
                                <span class="badge-success">🟢 EUR (Euro)</span>
                                <span class="badge-danger">🔴 GBP (Pound)</span>
                                <span class="badge-warning">🟡 JPY (Yen)</span>
                                <span class="badge-purple">🟣 CNY (Yuan)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            📋 All Exchange Rates
                            @if($search)
                                <span class="badge bg-info ms-2">Filter: {{ $search }}</span>
                            @endif
                            <span class="badge bg-secondary ms-2">Total: {{ $allRates->count() }} currencies</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Currency</th>
                                            <th>Rate (1 USD)</th>
                                            <th>Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rates as $rate)
                                        <tr>
                                            <td><strong>{{ $rate->target_currency }}</strong></td>
                                            <td>{{ number_format($rate->rate, 4) }}</td>
                                            <td>{{ $rate->updated_at->diffForHumans() }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                <i class="fas fa-search fa-2x mb-2 d-block"></i>
                                                Tidak ada hasil untuk: <strong>{{ $search }}</strong>
                                                <br>
                                                <a href="/exchange" class="btn btn-sm btn-primary mt-2">
                                                    <i class="fas fa-undo"></i> Reset Search
                                                </a>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <script>
        // Toggle sidebar mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>

    <!-- CHART SCRIPT -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Chart.js version:', Chart.version);

        var chartData = @json($chartData ?? []);

        console.log('Chart Data received:', chartData);

        var canvas = document.getElementById('exchangeChart');

        if (!canvas) {
            console.log('Canvas not found!');
            return;
        }

        var ctx = canvas.getContext('2d');

        // CEK APAKAH DATA ADA
        if (chartData && chartData.labels && chartData.labels.length > 0 && chartData.idr) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'IDR (Rupiah)',
                            data: chartData.idr,
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.1)',
                            yAxisID: 'y',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 6,
                            pointBackgroundColor: '#4e73df',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 3
                        },
                        {
                            label: 'EUR (Euro)',
                            data: chartData.eur,
                            borderColor: '#1cc88a',
                            backgroundColor: 'rgba(28, 200, 138, 0.1)',
                            yAxisID: 'y1',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 6,
                            pointBackgroundColor: '#1cc88a',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 3
                        },
                        {
                            label: 'GBP (Pound)',
                            data: chartData.gbp,
                            borderColor: '#e74a3b',
                            backgroundColor: 'rgba(231, 74, 59, 0.1)',
                            yAxisID: 'y1',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 6,
                            pointBackgroundColor: '#e74a3b',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 3
                        },
                        {
                            label: 'JPY (Yen)',
                            data: chartData.jpy,
                            borderColor: '#f6c23e',
                            backgroundColor: 'rgba(246, 194, 62, 0.1)',
                            yAxisID: 'y1',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 6,
                            pointBackgroundColor: '#f6c23e',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 3
                        },
                        {
                            label: 'CNY (Yuan)',
                            data: chartData.cny,
                            borderColor: '#6f42c1',
                            backgroundColor: 'rgba(111, 66, 193, 0.1)',
                            yAxisID: 'y1',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 6,
                            pointBackgroundColor: '#6f42c1',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12, weight: 'bold' }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let value = context.parsed.y;
                                    if (context.dataset.label.includes('IDR')) {
                                        return label + ': Rp ' + value.toLocaleString('id-ID');
                                    }
                                    return label + ': ' + value.toFixed(4);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'IDR (Rupiah)',
                                font: { size: 13, weight: 'bold' },
                                color: '#4e73df'
                            },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'EUR / GBP / JPY / CNY',
                                font: { size: 13, weight: 'bold' },
                                color: '#1cc88a'
                            },
                            grid: { drawOnChartArea: false },
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(2);
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            title: {
                                display: true,
                                text: 'Kuartal',
                                font: { size: 13, weight: 'bold' }
                            }
                        }
                    }
                }
            });
            console.log('✅ Chart created successfully!');
        } else {
            canvas.innerHTML = '<p class="text-center text-muted py-4">No data available for chart. Please refresh.</p>';
            console.log('❌ No chart data');
            console.log('chartData:', chartData);
        }
    });
    </script>

</body>
</html>