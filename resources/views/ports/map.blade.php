@extends('layouts.app')

@section('title', 'Port Map')

@section('styles')
<style>
    #portMap {
        height: 600px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ddd;
    }
    .legend {
        position: absolute;
        bottom: 30px;
        left: 10px;
        z-index: 1000;
        background: white;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
    }
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1px solid #ddd;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">🗺️ Global Port Location Map</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body position-relative">
                <div id="portMap"></div>
                
                <div class="legend">
                    <h6>Port Status</h6>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #28a745;"></span>
                        <span>Operational</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #ffc107;"></span>
                        <span>Under Construction</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #dc3545;"></span>
                        <span>Closed</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #17a2b8;"></span>
                        <span>Unknown</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('portMap').setView([0, 0], 2);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    
    var ports = @json($ports ?? []);
    
    if (ports.length === 0) {
        ports = [
            { port_name: 'Singapore Port', latitude: 1.27, longitude: 103.84, country: { country_name: 'Singapore' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
            { port_name: 'Port of Jakarta', latitude: -6.10, longitude: 106.89, country: { country_name: 'Indonesia' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
            { port_name: 'Port of Bangkok', latitude: 13.55, longitude: 100.60, country: { country_name: 'Thailand' }, status: 'Operational', harbor_type: 'River', harbor_size: 'Medium' },
            { port_name: 'Port of Tokyo', latitude: 35.65, longitude: 139.75, country: { country_name: 'Japan' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
            { port_name: 'Port of London', latitude: 51.50, longitude: 0.00, country: { country_name: 'United Kingdom' }, status: 'Operational', harbor_type: 'River', harbor_size: 'Large' },
            { port_name: 'Port of New York', latitude: 40.70, longitude: -74.00, country: { country_name: 'United States' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
            { port_name: 'Port of Sydney', latitude: -33.85, longitude: 151.20, country: { country_name: 'Australia' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
            { port_name: 'Port of Shanghai', latitude: 31.23, longitude: 121.47, country: { country_name: 'China' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
            { port_name: 'Port of Rotterdam', latitude: 51.92, longitude: 4.48, country: { country_name: 'Netherlands' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
            { port_name: 'Port of Dubai', latitude: 25.20, longitude: 55.27, country: { country_name: 'UAE' }, status: 'Operational', harbor_type: 'Deepwater', harbor_size: 'Large' },
        ];
    }
    
    ports.forEach(function(port) {
        if (port.latitude && port.longitude) {
            var color = '#17a2b8';
            var status = port.status || 'Unknown';
            if (status === 'Operational') color = '#28a745';
            else if (status === 'Under Construction') color = '#ffc107';
            else if (status === 'Closed') color = '#dc3545';
            
            var marker = L.circleMarker([port.latitude, port.longitude], {
                radius: 8,
                fillColor: color,
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);
            
            var countryName = port.country?.country_name || port.country_name || 'Unknown';
            marker.bindPopup(`
                <strong>${port.port_name}</strong><br>
                Country: ${countryName}<br>
                Status: ${status}<br>
                Type: ${port.harbor_type || '-'}<br>
                Size: ${port.harbor_size || '-'}
            `);
        }
    });
});
</script>
@endsection