<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Port Map - RiskIntel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { margin: 0; padding: 0; background: #f4f6f9; font-family: Arial, sans-serif; }
        .navbar-custom { background: #1a1a2e; padding: 10px 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .navbar-custom .brand { color: #00d2ff; font-size: 22px; font-weight: bold; }
        .navbar-custom .brand span { color: white; }
        .navbar-custom a { color: #b0b0b0; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .navbar-custom a:hover { color: white; }
        .navbar-custom a.active { color: #00d2ff; }
        .container-custom { padding: 20px 30px; }
        #map { height: 650px; width: 100%; border-radius: 10px; border: 1px solid #ddd; }
        .legend {
            position: absolute;
            bottom: 30px;
            left: 30px;
            background: white;
            padding: 12px 18px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-size: 13px;
            z-index: 1000;
        }
        .legend-item { display: flex; align-items: center; gap: 10px; margin: 4px 0; }
        .legend-color { width: 16px; height: 16px; border-radius: 50%; border: 1px solid #ddd; display: inline-block; }
        .badge-custom { padding: 5px 12px; border-radius: 20px; font-size: 12px; margin-right: 5px; }
        .logout-btn { background: none; border: none; color: #b0b0b0; font-size: 14px; cursor: pointer; }
        .logout-btn:hover { color: #ff6b6b; }
        .ship-icon { font-size: 24px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); text-shadow: 0 0 10px rgba(255,255,255,0.8); }
        .info-panel {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-size: 12px;
            max-width: 200px;
            max-height: 250px;
            overflow-y: auto;
        }
        .info-panel h6 { margin-bottom: 5px; font-weight: bold; }
        .info-panel ul { list-style: none; padding: 0; margin: 0; }
        .info-panel li { padding: 3px 0; font-size: 11px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .ship-status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .ship-status.moving { background: #28a745; color: white; }
        .ship-status.docked { background: #ffc107; color: #333; }
        .legend-marker {
            display: inline-block;
            width: 16px;
            height: 16px;
            background: url('https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png') no-repeat center;
            background-size: contain;
        }
        @media (max-width: 768px) { .navbar-custom { flex-wrap: wrap; gap: 10px; } .navbar-custom a { margin-left: 10px; } }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar-custom">
        <div class="brand">🚢 RiskIntel <span>Supply Chain Risk</span></div>
        <div>
            <a href="/dashboard">Dashboard</a>
            <a href="/countries">Countries</a>
            <a href="/weather">Weather</a>
            <a href="/exchange">Exchange</a>
            <a href="/ports">Ports</a>
            <a href="/ports/map" class="active">Port Map</a>
            <a href="/risk">Risk</a>
            <a href="/tracking">Tracking</a>
            <a href="/profile">Profile</a>
            <form method="POST" action="/logout" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- KONTEN -->
    <div class="container-custom">
        <h1 class="mb-2">🗺️ Global Port Location & Ship Tracking</h1>
        <p>
            <span class="badge bg-danger badge-custom">📍 Port Location</span>
            <span class="badge bg-success badge-custom">🟢 Operational</span>
            <span class="badge bg-warning badge-custom">🟡 Under Construction</span>
            <span class="badge bg-danger badge-custom">🔴 Closed</span>
            <span class="badge bg-primary badge-custom">🚢 Ship Position</span>
            <span class="badge bg-secondary ms-2">Total: {{ $ports->count() }} Ports</span>
        </p>

        <div class="card">
            <div class="card-body position-relative" style="padding: 10px;">
                <div id="map"></div>
                
                <!-- Legend -->
                <div class="legend">
                    <h6 class="mb-1">📌 Legend</h6>
                    <div class="legend-item"><span class="legend-marker"></span> Port Location</div>
                    <div class="legend-item"><span class="legend-color" style="background:#28a745;"></span> Operational</div>
                    <div class="legend-item"><span class="legend-color" style="background:#ffc107;"></span> Under Construction</div>
                    <div class="legend-item"><span class="legend-color" style="background:#dc3545;"></span> Closed</div>
                    <hr class="my-1">
                    <div class="legend-item"><span style="font-size:18px;">🚢</span> Kapal Bergerak</div>
                    <div class="legend-item"><span style="font-size:14px;">⚓</span> Kapal Berlabuh</div>
                </div>
                
                <!-- Info Panel -->
                <div class="info-panel">
                    <h6>🚢 Ship Status</h6>
                    <ul id="shipStatusList">
                        <li style="color:#999; font-style:italic;">Loading ships...</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- LEAFET JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
    // ============================================================
    // DATA PORTS (SEMUA)
    // ============================================================
    var portsData = @json($ports ?? []);
    console.log('Total ports:', portsData.length);

    // ============================================================
    // DATA KAPAL
    // ============================================================
    var ships = [
        { name: '🚢 M/V Sea Explorer', status: 'Moving', position: [1.27, 103.84], destination: 'Port of Jakarta', speed: '12 knots', eta: '2 hours' },
        { name: '🚢 M/V Ocean Star', status: 'Docked', position: [-6.10, 106.89], destination: 'Port of Jakarta', speed: '0 knots', eta: 'Docked' },
        { name: '🚢 M/V Pacific Queen', status: 'Moving', position: [13.55, 100.60], destination: 'Port of Singapore', speed: '15 knots', eta: '6 hours' },
        { name: '🚢 M/V Atlantic Voyager', status: 'Moving', position: [35.65, 139.75], destination: 'Port of Shanghai', speed: '18 knots', eta: '4 hours' },
        { name: '🚢 M/V Indian Ocean', status: 'Moving', position: [51.50, 0.00], destination: 'Port of Rotterdam', speed: '10 knots', eta: '8 hours' },
        { name: '🚢 M/V Pacific Trader', status: 'Docked', position: [40.70, -74.00], destination: 'Port of New York', speed: '0 knots', eta: 'Docked' },
        { name: '🚢 M/V Asia Express', status: 'Moving', position: [22.30, 114.16], destination: 'Port of Singapore', speed: '20 knots', eta: '3 hours' },
        { name: '🚢 M/V Europe Carrier', status: 'Moving', position: [41.90, 12.50], destination: 'Port of Barcelona', speed: '14 knots', eta: '5 hours' },
        { name: '🚢 M/V America Star', status: 'Docked', position: [-33.85, 151.20], destination: 'Port of Sydney', speed: '0 knots', eta: 'Docked' },
        { name: '🚢 M/V Africa Trader', status: 'Moving', position: [-33.92, 18.42], destination: 'Port of Durban', speed: '11 knots', eta: '7 hours' }
    ];

    // ============================================================
    // BUAT MAP
    // ============================================================
    var map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // ============================================================
    // ICON LOKASI MERAH
    // ============================================================
    var redIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // ============================================================
    // TAMBAHKAN MARKER PORT (SEMUA) - PAKAI IKON MERAH
    // ============================================================
    var markers = [];

    portsData.forEach(function(port) {
        if (port.latitude && port.longitude) {
            var lat = parseFloat(port.latitude);
            var lng = parseFloat(port.longitude);
            
            var marker = L.marker([lat, lng], {
                icon: redIcon
            }).addTo(map);
            
            markers.push(marker);
            
            var status = port.status || 'Unknown';
            var countryName = port.country?.country_name || port.country_name || 'Unknown';
            
            marker.bindPopup(`
                <div style="min-width: 160px;">
                    <strong>${port.port_name}</strong><br>
                    Country: ${countryName}<br>
                    Status: <span class="badge bg-${status === 'Operational' ? 'success' : (status === 'Under Construction' ? 'warning' : (status === 'Closed' ? 'danger' : 'secondary'))}">${status}</span><br>
                    Type: ${port.harbor_type || '-'}<br>
                    Size: ${port.harbor_size || '-'}
                </div>
            `);
        }
    });

    console.log('Markers added:', markers.length);

    // ============================================================
    // TAMBAHKAN MARKER KAPAL
    // ============================================================
    var shipMarkers = [];
    var shipStatusList = document.getElementById('shipStatusList');
    if (shipStatusList) shipStatusList.innerHTML = '';

    ships.forEach(function(ship) {
        var isMoving = ship.status === 'Moving';
        var iconHtml = isMoving ? '🚢' : '⚓';
        
        var shipIcon = L.divIcon({
            html: `<div style="font-size: ${isMoving ? '28px' : '24px'}; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); text-shadow: 0 0 10px rgba(255,255,255,0.8);">${iconHtml}</div>`,
            className: 'ship-icon',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });
        
        var shipMarker = L.marker(ship.position, {
            icon: shipIcon,
            title: ship.name,
            riseOnHover: true,
            zIndexOffset: 1000
        }).addTo(map);
        
        var statusColor = isMoving ? 'moving' : 'docked';
        var statusText = isMoving ? '🟢 Moving' : '🟡 Docked';
        
        shipMarker.bindPopup(`
            <div style="text-align: center; min-width: 150px;">
                <div style="font-size: 32px;">${iconHtml}</div>
                <strong>${ship.name}</strong><br>
                <span class="ship-status ${statusColor}">${statusText}</span><br>
                Destination: ${ship.destination}<br>
                Speed: ${ship.speed}<br>
                ETA: ${ship.eta}
            </div>
        `);
        
        shipMarkers.push({ marker: shipMarker, ship: ship });
        
        if (shipStatusList) {
            var li = document.createElement('li');
            li.innerHTML = `
                <span style="font-size: 14px;">${isMoving ? '🚢' : '⚓'}</span>
                <span style="flex:1; margin-left:5px; font-size:10px;">${ship.name.replace('🚢 ', '')}</span>
                <span class="ship-status ${statusColor}">${ship.status}</span>
            `;
            shipStatusList.appendChild(li);
        }
    });

    console.log('Ships added:', ships.length);

    // ============================================================
    // ANIMASI KAPAL
    // ============================================================
    setInterval(function() {
        shipMarkers.forEach(function(item) {
            if (item.ship.status === 'Moving') {
                var lat = item.marker.getLatLng().lat;
                var lng = item.marker.getLatLng().lng;
                var newLat = lat + (Math.random() - 0.5) * 0.02;
                var newLng = lng + (Math.random() - 0.5) * 0.02;
                if (newLat > 85) newLat = 85;
                if (newLat < -85) newLat = -85;
                if (newLng > 180) newLng = 180;
                if (newLng < -180) newLng = -180;
                item.marker.setLatLng([newLat, newLng]);
            }
        });
    }, 2000);

    // ============================================================
    // UPDATE STATUS KAPAL
    // ============================================================
    setInterval(function() {
        var statuses = ['Moving', 'Moving', 'Moving', 'Docked'];
        var destinations = ['Port of Singapore', 'Port of Jakarta', 'Port of Bangkok', 'Port of Tokyo', 'Port of Shanghai', 'Port of London', 'Port of New York', 'Port of Sydney', 'Port of Rotterdam'];
        
        shipMarkers.forEach(function(item) {
            var newStatus = statuses[Math.floor(Math.random() * statuses.length)];
            var newDest = destinations[Math.floor(Math.random() * destinations.length)];
            item.ship.status = newStatus;
            item.ship.destination = newDest;
            
            var isMoving = newStatus === 'Moving';
            var iconHtml = isMoving ? '🚢' : '⚓';
            var newIcon = L.divIcon({
                html: `<div style="font-size: ${isMoving ? '28px' : '24px'}; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); text-shadow: 0 0 10px rgba(255,255,255,0.8);">${iconHtml}</div>`,
                className: 'ship-icon',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
            item.marker.setIcon(newIcon);
            
            var statusColor = isMoving ? 'moving' : 'docked';
            var statusText = isMoving ? '🟢 Moving' : '🟡 Docked';
            item.marker.setPopupContent(`
                <div style="text-align: center; min-width: 150px;">
                    <div style="font-size: 32px;">${iconHtml}</div>
                    <strong>${item.ship.name}</strong><br>
                    <span class="ship-status ${statusColor}">${statusText}</span><br>
                    Destination: ${item.ship.destination}<br>
                    Speed: ${isMoving ? Math.floor(Math.random() * 20 + 5) + ' knots' : '0 knots'}<br>
                    ETA: ${isMoving ? Math.floor(Math.random() * 10 + 1) + ' hours' : 'Docked'}
                </div>
            `);
        });
        
        if (shipStatusList) {
            shipStatusList.innerHTML = '';
            shipMarkers.forEach(function(item) {
                var isMoving = item.ship.status === 'Moving';
                var li = document.createElement('li');
                li.innerHTML = `
                    <span style="font-size: 14px;">${isMoving ? '🚢' : '⚓'}</span>
                    <span style="flex:1; margin-left:5px; font-size:10px;">${item.ship.name.replace('🚢 ', '')}</span>
                    <span class="ship-status ${isMoving ? 'moving' : 'docked'}">${item.ship.status}</span>
                `;
                shipStatusList.appendChild(li);
            });
        }
    }, 10000);

    // ============================================================
    // FIT BOUNDS
    // ============================================================
    if (markers.length > 0) {
        var latLngs = markers.map(function(m) { return m.getLatLng(); });
        var bounds = L.latLngBounds(latLngs);
        map.fitBounds(bounds, { padding: [50, 50] });
    }
    </script>

</body>
</html>