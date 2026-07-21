<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ports - RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
        .card-header { font-weight: bold; font-size: 16px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; border: none; }
        .btn:hover { background: #224abe; color: white; }
        .btn-primary { background: #4e73df; color: white; }
        .btn-primary:hover { background: #224abe; }
        .btn-success { background: #1cc88a; color: white; }
        .btn-success:hover { background: #13855c; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; color: white; }
        .badge-success { background: #1cc88a; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-danger { background: #e74a3b; }
        .badge-info { background: #36b9cc; }
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-6 { flex: 1; min-width: 300px; }
        .col-12 { width: 100%; }
        .col-3 { flex: 1; min-width: 200px; }
        .mb-4 { margin-bottom: 20px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-2 { margin-top: 10px; }
        .text-muted { color: #888; }
        .text-center { text-align: center; }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .table tr:hover { background: #f5f5f5; }
        .d-flex { display: flex; }
        .gap-2 { gap: 10px; }
        .flex-wrap { flex-wrap: wrap; }
        .ms-2 { margin-left: 10px; }
        .me-2 { margin-right: 10px; }
        .form-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; width: 100%; }
        #portMap { height: 450px; width: 100%; border-radius: 8px; border: 1px solid #ddd; }
        .legend {
            position: absolute; bottom: 30px; left: 10px; z-index: 1000;
            background: white; padding: 10px 15px; border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2); font-size: 12px;
        }
        .legend-item { display: flex; align-items: center; gap: 8px; margin: 3px 0; }
        .legend-color { width: 16px; height: 16px; border-radius: 50%; border: 1px solid #ddd; display: inline-block; }
        .position-relative { position: relative; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin-bottom: 15px; }
        .anchor-icon {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            transition: transform 0.2s;
        }
        .anchor-icon:hover {
            transform: scale(1.2);
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><h4>🚢 RiskIntel</h4><small>Supply Chain Risk</small></div>
        <a class="nav-link" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
        <a class="nav-link" href="/economic"><i class="fas fa-chart-line"></i> Economic</a>
        <a class="nav-link" href="/exchange"><i class="fas fa-money-bill-wave"></i> Exchange</a>
        <a class="nav-link" href="/news"><i class="fas fa-newspaper"></i> News</a>
        <a class="nav-link active" href="/ports"><i class="fas fa-anchor"></i> Ports</a>
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

            <h1 class="mb-4">⚓ World Ports</h1>
            <p class="text-muted">Pilih negara untuk melihat pelabuhan</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- PILIH NEGARA + FETCH DATA -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-2">
                                <div class="col-md-4">
                                    <select name="country" class="form-select form-select-lg" onchange="this.form.submit()">
                                        <option value="">-- Pilih Negara --</option>
                                        @foreach($countries as $c)
                                        <option value="{{ $c->id }}" {{ request('country') == $c->id ? 'selected' : '' }}>
                                            {{ $c->country_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('ports.fetch') }}" class="btn btn-success w-100" title="Fetch from API">
                                        <i class="fas fa-sync"></i> Fetch Data
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('ports') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($selectedCountry)
            <!-- PETA -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            🗺️ Pelabuhan di {{ $selectedCountry->country_name }}
                            <span class="badge bg-secondary ms-2">Total: {{ $ports->count() }} Ports</span>
                        </div>
                        <div class="card-body position-relative">
                            <div id="portMap"></div>
                            <div class="legend">
                                <h6 class="mb-1">📌 Status</h6>
                                <div class="legend-item"><span class="legend-color" style="background:#28a745;"></span> Operational</div>
                                <div class="legend-item"><span class="legend-color" style="background:#ffc107;"></span> Under Construction</div>
                                <div class="legend-item"><span class="legend-color" style="background:#dc3545;"></span> Closed</div>
                                <div class="legend-item"><span class="legend-color" style="background:#17a2b8;"></span> Unknown</div>
                                <hr style="margin: 5px 0;">
                                <div class="legend-item">
                                    <span style="font-size: 18px; color: #e74a3b;">⚓</span>
                                    <span style="font-weight: bold;">Port Location</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DAFTAR PELABUHAN -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            📋 Daftar Pelabuhan di {{ $selectedCountry->country_name }}
                        </div>
                        <div class="card-body">
                            @if($ports->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Port Name</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Status</th>
                                            <th>Coordinates</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ports as $index => $port)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $port->port_name }}</strong></td>
                                            <td>{{ $port->harbor_type ?? '-' }}</td>
                                            <td>{{ $port->harbor_size ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $statusColors = ['Operational' => 'success', 'Under Construction' => 'warning', 'Closed' => 'danger'];
                                                    $color = $statusColors[$port->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ $port->status ?? 'Unknown' }}</span>
                                            </td>
                                            <td>
                                                @if($port->latitude && $port->longitude)
                                                    {{ number_format($port->latitude, 2) }}, {{ number_format($port->longitude, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <p class="text-muted">Tidak ada pelabuhan untuk negara ini.</p>
                            </div>
                            @endif
                        </div>
                    </div>
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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('portMap').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var ports = @json($ports ?? []);
        console.log('Ports loaded:', ports.length);

        // ========== ICON JANGKAR MERAH ==========
        var anchorIcon = L.divIcon({
            html: `<div style="font-size: 28px; color: #e74a3b; text-align: center; line-height: 1; text-shadow: 0 0 10px rgba(255,255,255,0.8), 0 2px 4px rgba(0,0,0,0.3); filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">⚓</div>`,
            className: 'anchor-icon',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        });

        ports.forEach(function(port) {
            if (port.latitude && port.longitude) {
                var marker = L.marker([parseFloat(port.latitude), parseFloat(port.longitude)], {
                    icon: anchorIcon,
                    riseOnHover: true
                }).addTo(map);

                var status = port.status || 'Unknown';
                var statusColor = status === 'Operational' ? 'success' : (status === 'Under Construction' ? 'warning' : (status === 'Closed' ? 'danger' : 'secondary'));

                marker.bindPopup(`
                    <div style="min-width: 180px; text-align: center;">
                        <div style="font-size: 24px; margin-bottom: 5px;">⚓</div>
                        <strong>${port.port_name}</strong><br>
                        Country: ${port.country?.country_name || port.country_name || 'Unknown'}<br>
                        Status: <span class="badge bg-${statusColor}">${status}</span><br>
                        Type: ${port.harbor_type || '-'}<br>
                        Size: ${port.harbor_size || '-'}
                    </div>
                `);
            }
        });

        var points = [];
        ports.forEach(function(port) {
            if (port.latitude && port.longitude) {
                points.push([parseFloat(port.latitude), parseFloat(port.longitude)]);
            }
        });

        if (points.length > 0) {
            var bounds = L.latLngBounds(points);
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    });
    </script>

</body>
</html>