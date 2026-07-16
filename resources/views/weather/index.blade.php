@extends('layouts.app')

@section('title', 'Weather')

@section('styles')
<style>
    #weatherMap {
        height: 400px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }
    .legend {
        position: absolute;
        bottom: 30px;
        right: 10px;
        z-index: 1000;
        background: white;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        font-size: 12px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 3px 0;
    }
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1px solid #ddd;
    }
    .weather-card {
        transition: transform 0.2s;
    }
    .weather-card:hover {
        transform: scale(1.05);
        z-index: 10;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">🌤️ Global Weather Monitoring</h1>
        <p class="text-muted">
            Menampilkan <strong>{{ count($weatherData) }}</strong> negara
            <span class="badge bg-info ms-2">Live from Open-Meteo API</span>
            <span class="badge bg-success ms-2">⚡ Fast Loading</span>
        </p>
    </div>
</div>

<!-- ============ PETA ============ -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">🗺️ Peta Cuaca Global</h5>
            </div>
            <div class="card-body position-relative">
                <div id="weatherMap"></div>
                
                <div class="legend">
                    <h6 class="mb-1">🌡️ Suhu</h6>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #e74a3b;"></span>
                        <span>&gt; 30°C (Panas)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #f6c23e;"></span>
                        <span>20-30°C (Hangat)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #1cc88a;"></span>
                        <span>10-20°C (Sejuk)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #4e73df;"></span>
                        <span>0-10°C (Dingin)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #858796;"></span>
                        <span>&lt; 0°C (Beku)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ DATA CUACA ============ -->
<div class="row">
    @if(isset($weatherData) && count($weatherData) > 0)
        @foreach($weatherData as $data)
        <div class="col-md-2 col-sm-3 col-4 mb-2">
            <div class="card weather-card text-center h-100" style="font-size: 12px;">
                <div class="card-body p-2">
                    <div style="font-size: 10px; font-weight: bold;">
                        @if($data['country']->flag)
                            <img src="{{ $data['country']->flag }}" width="16" height="12" class="me-1">
                        @endif
                        {{ Str::limit($data['country']->country_name, 10) }}
                    </div>
                    <div class="my-1" style="font-size: 22px;">
                        @php
                            $temp = $data['weather']['temperature'] ?? 0;
                        @endphp
                        @if($temp > 30) ☀️
                        @elseif($temp > 20) ⛅
                        @elseif($temp > 10) 🌤️
                        @elseif($temp > 0) 🌧️
                        @else ❄️
                        @endif
                    </div>
                    <div style="font-weight: bold; font-size: 14px;">{{ round($temp) }}°C</div>
                    <div style="font-size: 9px; color: #666;">{{ Str::limit($data['description'] ?? 'Unknown', 10) }}</div>
                    <div style="font-size: 9px; color: #999;">
                        <i class="fas fa-wind"></i> {{ $data['weather']['windspeed'] ?? 0 }} km/h
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-md-12">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                No weather data available. 
                <a href="{{ route('weather') }}" class="btn btn-sm btn-primary ms-2">🔄 Refresh</a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('weatherMap').setView([0, 0], 2);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    
    var weatherData = @json($weatherData ?? []);
    
    weatherData.forEach(function(data) {
        var country = data.country;
        var weather = data.weather;
        var temp = weather.temperature || 0;
        
        if (country.latitude && country.longitude) {
            var color;
            if (temp > 30) color = '#e74a3b';
            else if (temp > 20) color = '#f6c23e';
            else if (temp > 10) color = '#1cc88a';
            else if (temp > 0) color = '#4e73df';
            else color = '#858796';
            
            var marker = L.circleMarker([country.latitude, country.longitude], {
                radius: 8,
                fillColor: color,
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.85
            }).addTo(map);
            
            var flag = country.flag ? `<img src="${country.flag}" width="24" height="16" class="me-1">` : '';
            var emoji = temp > 30 ? '☀️' : (temp > 20 ? '⛅' : (temp > 10 ? '🌤️' : (temp > 0 ? '🌧️' : '❄️')));
            
            marker.bindPopup(`
                <div style="text-align: center;">
                    ${flag} <strong>${country.country_name}</strong><br>
                    <div style="font-size: 32px;">${emoji}</div>
                    <div style="font-size: 20px; font-weight: bold;">${Math.round(temp)}°C</div>
                    <div>${data.description || 'Unknown'}</div>
                    <div style="font-size: 12px; color: #666;">
                        <i class="fas fa-wind"></i> ${weather.windspeed || 0} km/h
                    </div>
                </div>
            `);
        }
    });
    
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
@endsection