<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather - RiskIntel</title>
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
        .row { display: flex; flex-wrap: wrap; gap: 15px; }
        .col-2 { flex: 1; min-width: 150px; }
        .col-12 { width: 100%; }
        .mb-4 { margin-bottom: 20px; }
        .mb-3 { margin-bottom: 15px; }
        .text-muted { color: #888; }
        .text-center { text-align: center; }
        .btn { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; border: none; }
        .btn:hover { background: #224abe; color: white; }
        .btn-primary { background: #4e73df; color: white; padding: 6px 15px; border-radius: 5px; border: none; }
        .btn-primary:hover { background: #224abe; }
        .btn-success { background: #1cc88a; color: white; border: none; padding: 6px 15px; border-radius: 5px; }
        .btn-success:hover { background: #13855c; }
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 13px; color: white; }
        .badge-info { background: #36b9cc; }
        .badge-success { background: #1cc88a; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-danger { background: #e74a3b; }
        #weatherMap { height: 500px; width: 100%; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 20px; }
        .legend {
            position: absolute; bottom: 30px; right: 10px; z-index: 1000;
            background: white; padding: 10px 15px; border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2); font-size: 12px;
        }
        .legend-item { display: flex; align-items: center; gap: 8px; margin: 3px 0; }
        .legend-color { width: 16px; height: 16px; border-radius: 50%; border: 1px solid #ddd; display: inline-block; }
        .weather-card { transition: transform 0.2s; }
        .weather-card:hover { transform: scale(1.05); z-index: 10; }
        .position-relative { position: relative; }
        .mt-2 { margin-top: 10px; }
        .ms-2 { margin-left: 10px; }
        .weather-icon-big { font-size: 28px; }
        .weather-icon-small { font-size: 18px; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebar">
        <div class="brand"><h4>🚢 RiskIntel</h4><small>Supply Chain Risk</small></div>
        <a class="nav-link" href="/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link" href="/countries"><i class="fas fa-globe"></i> Countries</a>
        <a class="nav-link active" href="/weather"><i class="fas fa-cloud-sun"></i> Weather</a>
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

            <h1 class="mb-4">🌤️ Global Weather Monitoring</h1>
            <p class="text-muted">
                Menampilkan <strong>{{ count($weatherData) }}</strong> negara
                <span class="badge-success">✅ Data from Open-Meteo API</span>
                <span class="badge-info ms-2">🌧️ Hujan | ⛈️ Badai | 💨 Angin Kencang</span>
                <a href="/weather/refresh" class="btn btn-sm btn-primary ms-2">Refresh</a>
            </p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- ============ PETA CUACA GLOBAL ============ -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">🗺️ Peta Cuaca Global - Hujan, Badai, Angin Kencang</div>
                        <div class="card-body position-relative">
                            <div id="weatherMap"></div>

                            <!-- Legend -->
                            <div class="legend">
                                <h6 class="mb-1">🌡️ Cuaca</h6>
                                <div class="legend-item">
                                    <span style="font-size:18px;">☀️</span>
                                    <span>Cerah</span>
                                </div>
                                <div class="legend-item">
                                    <span style="font-size:18px;">🌧️</span>
                                    <span>Hujan</span>
                                </div>
                                <div class="legend-item">
                                    <span style="font-size:18px;">⛈️</span>
                                    <span>Badai</span>
                                </div>
                                <div class="legend-item">
                                    <span style="font-size:18px;">💨</span>
                                    <span>Angin Kencang</span>
                                </div>
                                <div class="legend-item">
                                    <span style="font-size:18px;">❄️</span>
                                    <span>Salju</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============ DATA CUACA ============ -->
            <div class="row">
                @foreach($weatherData as $data)
                <div class="col-2 mb-2">
                    <div class="card weather-card text-center h-100" style="font-size: 12px;">
                        <div class="card-body p-2">
                            <div style="font-size: 10px; font-weight: bold;">
                                @if($data['country']->flag)
                                    <img src="{{ $data['country']->flag }}" width="16" height="12" class="me-1">
                                @endif
                                {{ Str::limit($data['country']->country_name, 10) }}
                            </div>
                            <div class="my-1 weather-icon-big">
                                @php
                                    $code = $data['weather']['weathercode'] ?? 0;
                                    $wind = $data['weather']['windspeed'] ?? 0;
                                    $rain = $data['weather']['rain'] ?? 0;
                                @endphp
                                @if($code == 95 || $code == 96 || $code == 99) ⛈️
                                @elseif($code == 65 || $code == 82 || $rain > 25) 🌧️
                                @elseif($code == 63 || $code == 81 || $rain > 15) 🌧️
                                @elseif($code == 61 || $code == 80 || $rain > 5) 🌧️
                                @elseif($wind > 60) 💨
                                @elseif($wind > 40) 💨
                                @elseif($code >= 71 && $code <= 77) ❄️
                                @elseif($code == 45 || $code == 48) 🌫️
                                @elseif($code == 0) ☀️
                                @elseif($code == 1) 🌤️
                                @elseif($code == 2 || $code == 3) ⛅
                                @else 🌍
                                @endif
                            </div>
                            <div style="font-weight: bold; font-size: 14px;">{{ round($data['weather']['temperature'] ?? 0) }}°C</div>
                            <div style="font-size: 9px; color: #666;">{{ Str::limit($data['description'] ?? 'Tidak Diketahui', 12) }}</div>
                            <div style="font-size: 9px; color: #999;">
                                <i class="fas fa-wind"></i> {{ $data['weather']['windspeed'] ?? 0 }} km/h
                            </div>
                            @if($data['risk'] != 'Normal')
                                <div style="font-size: 9px; margin-top: 2px;">
                                    <span class="badge bg-{{ $data['risk'] == 'Badai Petir' ? 'danger' : ($data['risk'] == 'Angin Kencang' ? 'warning' : 'warning') }}">
                                        {{ $data['risk'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>

    <!-- LEAFLET JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('weatherMap').setView([0, 0], 2);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        
        var weatherData = @json($weatherData ?? []);
        console.log('Weather Data:', weatherData.length);
        
        weatherData.forEach(function(data) {
            var country = data.country;
            var weather = data.weather;
            var temp = weather.temperature || 0;
            var wind = weather.windspeed || 0;
            var code = weather.weathercode || 0;
            var rain = weather.rain || 0;
            
            if (country.latitude && country.longitude) {
                // Warna marker berdasarkan kondisi cuaca
                var color;
                var icon = '';
                
                // Badai Petir
                if (code == 95 || code == 96 || code == 99) {
                    color = '#e74a3b';
                    icon = '⛈️';
                }
                // Hujan Lebat
                else if (code == 65 || code == 82 || rain > 25) {
                    color = '#2980b9';
                    icon = '🌧️';
                }
                // Hujan Sedang
                else if (code == 63 || code == 81 || rain > 15) {
                    color = '#3498db';
                    icon = '🌧️';
                }
                // Hujan Ringan
                else if (code == 61 || code == 80 || rain > 5) {
                    color = '#5dade2';
                    icon = '🌧️';
                }
                // Angin Kencang
                else if (wind > 60) {
                    color = '#f39c12';
                    icon = '💨';
                }
                else if (wind > 40) {
                    color = '#f1c40f';
                    icon = '💨';
                }
                // Salju
                else if (code >= 71 && code <= 77) {
                    color = '#85c1e9';
                    icon = '❄️';
                }
                // Kabut
                else if (code == 45 || code == 48) {
                    color = '#95a5a6';
                    icon = '🌫️';
                }
                // Cerah
                else if (code == 0) {
                    color = '#f6c23e';
                    icon = '☀️';
                }
                else if (code == 1) {
                    color = '#f8d76e';
                    icon = '🌤️';
                }
                // Berawan
                else if (code == 2 || code == 3) {
                    color = '#bdc3c7';
                    icon = '⛅';
                }
                else {
                    color = '#95a5a6';
                    icon = '🌍';
                }
                
                var marker = L.circleMarker([country.latitude, country.longitude], {
                    radius: 14,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.85
                }).addTo(map);
                
                var flag = country.flag ? `<img src="${country.flag}" width="24" height="16" class="me-1">` : '';
                var emoji = temp > 30 ? '☀️' : (temp > 20 ? '⛅' : (temp > 10 ? '🌤️' : (temp > 0 ? '🌧️' : '❄️')));
                
                // Status cuaca
                var weatherStatus = 'Normal';
                if (code == 95 || code == 96 || code == 99) weatherStatus = '⛈️ Badai Petir';
                else if (code == 65 || code == 82 || rain > 25) weatherStatus = '🌧️ Hujan Lebat';
                else if (wind > 60) weatherStatus = '💨 Angin Kencang';
                else if (code >= 71 && code <= 77) weatherStatus = '❄️ Salju';
                else if (code == 45 || code == 48) weatherStatus = '🌫️ Kabut';
                else if (rain > 5) weatherStatus = '🌧️ Hujan';
                
                marker.bindPopup(`
                    <div style="text-align: center; min-width: 180px;">
                        ${flag} <strong>${country.country_name}</strong><br>
                        <div style="font-size: 40px;">${icon}</div>
                        <div style="font-size: 22px; font-weight: bold;">${Math.round(temp)}°C</div>
                        <div>${data.description || 'Tidak Diketahui'}</div>
                        <div style="font-size: 12px; color: #666;">
                            <i class="fas fa-wind"></i> ${wind} km/h
                            ${rain > 0 ? ' | 🌧️ ' + rain + ' mm' : ''}
                        </div>
                        ${weatherStatus != 'Normal' ? `<div style="margin-top: 5px;"><span class="badge bg-danger">${weatherStatus}</span></div>` : ''}
                    </div>
                `);
            }
        });
        
        // Fit Bounds
        var markers = [];
        weatherData.forEach(function(data) {
            var country = data.country;
            if (country.latitude && country.longitude) {
                markers.push([country.latitude, country.longitude]);
            }
        });
        
        if (markers.length > 0) {
            var bounds = L.latLngBounds(markers);
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    });
    </script>

</body>
</html>