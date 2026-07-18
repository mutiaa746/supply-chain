@extends('layouts.app')

@section('title', 'Route Result')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #routeMap { height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ddd; }
    .result-card { background: #f8f9fa; border-radius: 10px; padding: 15px; text-align: center; }
    .result-card .value { font-size: 24px; font-weight: bold; }
    .result-card .label { color: #888; font-size: 14px; }
    .risk-high { color: #e74a3b; }
    .risk-medium { color: #f6c23e; }
    .risk-low { color: #1cc88a; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">🗺️ Hasil Simulasi Rute</h1>
        <a href="{{ route('route-simulation') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- 2 NEGARA -->
<div class="row mb-4">
    <div class="col-md-5">
        <div class="card text-center">
            <div class="card-body">
                <h3>
                    @if($country1->flag)<img src="{{ $country1->flag }}" width="30" height="20">@endif
                    {{ $country1->country_name }}
                </h3>
                <p class="text-muted">{{ $country1->capital ?? '' }}</p>
                <div class="row">
                    <div class="col-6"><small>🌡️ {{ $weather1->temperature ?? 'N/A' }}°C</small></div>
                    <div class="col-6"><small>💱 {{ $country1->currency ?? '-' }}</small></div>
                </div>
                <div class="mt-2">
                    <span class="badge bg-{{ $riskLevel1 == 'High' ? 'danger' : ($riskLevel1 == 'Medium' ? 'warning' : 'success') }}">
                        Risk: {{ $riskLevel1 }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
        <div>
            <div style="font-size: 40px;">{{ $transportIcon }}</div>
            <div class="text-muted">{{ $transportName }}</div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card text-center">
            <div class="card-body">
                <h3>
                    @if($country2->flag)<img src="{{ $country2->flag }}" width="30" height="20">@endif
                    {{ $country2->country_name }}
                </h3>
                <p class="text-muted">{{ $country2->capital ?? '' }}</p>
                <div class="row">
                    <div class="col-6"><small>🌡️ {{ $weather2->temperature ?? 'N/A' }}°C</small></div>
                    <div class="col-6"><small>💱 {{ $country2->currency ?? '-' }}</small></div>
                </div>
                <div class="mt-2">
                    <span class="badge bg-{{ $riskLevel2 == 'High' ? 'danger' : ($riskLevel2 == 'Medium' ? 'warning' : 'success') }}">
                        Risk: {{ $riskLevel2 }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PETA RUTE -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">🗺️ Rute Perjalanan</h5></div>
            <div class="card-body"><div id="routeMap"></div></div>
        </div>
    </div>
</div>

<!-- JARAK & WAKTU -->
<div class="row mb-4">
    <div class="col-md-3"><div class="result-card"><div class="value">{{ number_format($distanceKm) }}</div><div class="label">Kilometer (km)</div></div></div>
    <div class="col-md-3"><div class="result-card"><div class="value">{{ number_format($distanceMiles) }}</div><div class="label">Miles (mi)</div></div></div>
    <div class="col-md-3"><div class="result-card"><div class="value">{{ number_format($distanceNautical) }}</div><div class="label">Nautical Miles (nm)</div></div></div>
    <div class="col-md-3"><div class="result-card"><div class="value">{{ $timeText }}</div><div class="label">Estimasi Waktu</div></div></div>
</div>

<!-- RISK MONITORING -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">⚠️ Risk Monitoring</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Indikator</th>
                                <th>{{ $country1->country_name }}</th>
                                <th>{{ $country2->country_name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>🌧️ Cuaca</td>
                                <td><span class="badge bg-{{ $weatherRisk1 >= 70 ? 'danger' : ($weatherRisk1 >= 40 ? 'warning' : 'success') }}">{{ $weatherRisk1 }}%</span></td>
                                <td><span class="badge bg-{{ $weatherRisk2 >= 70 ? 'danger' : ($weatherRisk2 >= 40 ? 'warning' : 'success') }}">{{ $weatherRisk2 }}%</span></td>
                            </tr>
                            <tr>
                                <td>💱 Kurs</td>
                                <td><span class="badge bg-{{ $currency1 >= 70 ? 'danger' : ($currency1 >= 40 ? 'warning' : 'success') }}">{{ $currency1 }}%</span></td>
                                <td><span class="badge bg-{{ $currency2 >= 70 ? 'danger' : ($currency2 >= 40 ? 'warning' : 'success') }}">{{ $currency2 }}%</span></td>
                            </tr>
                            <tr>
                                <td>📈 Inflasi</td>
                                <td><span class="badge bg-{{ $inflationRisk1 >= 70 ? 'danger' : ($inflationRisk1 >= 40 ? 'warning' : 'success') }}">{{ $inflationRisk1 }}%</span></td>
                                <td><span class="badge bg-{{ $inflationRisk2 >= 70 ? 'danger' : ($inflationRisk2 >= 40 ? 'warning' : 'success') }}">{{ $inflationRisk2 }}%</span></td>
                            </tr>
                            <tr>
                                <td>📰 Berita</td>
                                <td><span class="badge bg-{{ $newsRisk1 >= 70 ? 'danger' : ($newsRisk1 >= 40 ? 'warning' : 'success') }}">{{ $newsRisk1 }}%</span></td>
                                <td><span class="badge bg-{{ $newsRisk2 >= 70 ? 'danger' : ($newsRisk2 >= 40 ? 'warning' : 'success') }}">{{ $newsRisk2 }}%</span></td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Total Risk</strong></td>
                                <td><strong class="text-{{ $riskLevel1 == 'High' ? 'danger' : ($riskLevel1 == 'Medium' ? 'warning' : 'success') }}">{{ $riskLevel1 }}</strong></td>
                                <td><strong class="text-{{ $riskLevel2 == 'High' ? 'danger' : ($riskLevel2 == 'Medium' ? 'warning' : 'success') }}">{{ $riskLevel2 }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PELABUHAN TERDEKAT -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">⚓ Pelabuhan Terdekat dari {{ $country1->country_name }}</h5></div>
            <div class="card-body">
                @if($nearestPorts->count() > 0)
                    <table class="table table-striped">
                        <thead><tr><th>#</th><th>Port Name</th><th>Country</th><th>Distance</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($nearestPorts as $index => $port)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $port->port_name }}</strong></td>
                                <td>{{ $port->country->country_name ?? $port->country_name ?? '-' }}</td>
                                <td>{{ number_format($port->distance, 0) }} km</td>
                                <td><span class="badge bg-{{ $port->status == 'Operational' ? 'success' : ($port->status == 'Under Construction' ? 'warning' : 'danger') }}">{{ $port->status ?? 'Unknown' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Tidak ada pelabuhan terdekat.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('routeMap').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var route = @json($route ?? []);
    var routeCurved = @json($routeCurved ?? []);

    if (route.length >= 2) {
        var startIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41]
        });
        var endIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41]
        });

        L.marker(route[0], { icon: startIcon }).addTo(map).bindPopup('<strong>Start</strong><br>{{ $country1->country_name }}');
        L.marker(route[1], { icon: endIcon }).addTo(map).bindPopup('<strong>Destination</strong><br>{{ $country2->country_name }}');

        L.polyline(routeCurved, {
            color: '#4e73df', weight: 4, opacity: 0.8, dashArray: '10, 10', smoothFactor: 1
        }).addTo(map);

        var bounds = L.latLngBounds(route);
        map.fitBounds(bounds, { padding: [50, 50] });
    }
});
</script>
@endsection