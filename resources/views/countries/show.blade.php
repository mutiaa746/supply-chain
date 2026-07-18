@extends('layouts.app')

@section('title', $country->country_name)

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">
            @if($country->flag)
                <img src="{{ $country->flag }}" width="40" height="30">
            @else
                <img src="https://flagcdn.com/w40/{{ strtolower($country->country_code) }}.png" width="40" height="30">
            @endif
            {{ $country->country_name }}
            <small class="text-muted">({{ $country->country_code }})</small>
        </h1>

        <!-- ========== TAMBAHKAN INI ========== -->
        <!-- TOMBOL WATCHLIST -->
        @auth
        <div class="mb-3">
            @php
                $isWatched = \App\Models\Watchlist::where('user_id', Auth::id())
                    ->where('country_id', $country->id)
                    ->exists();
            @endphp
            <form action="{{ route('watchlist.toggle') }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="country_id" value="{{ $country->id }}">
                <button type="submit" class="btn {{ $isWatched ? 'btn-danger' : 'btn-warning' }}">
                    <i class="fas fa-star"></i>
                    {{ $isWatched ? ' Hapus dari Pantauan' : ' Tambah ke Pantauan' }}
                </button>
            </form>
        </div>
        @endauth
        <!-- ========== SAMPAI SINI ========== -->

    </div>
</div>

<div class="row">
    <!-- Info Negara -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📋 Country Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Capital</th><td>{{ $country->capital ?? '-' }}</td></tr>
                    <tr><th>Region</th><td>{{ $country->region ?? '-' }}</td></tr>
                    <tr><th>Currency</th><td>{{ $country->currency ?? '-' }}</td></tr>
                    <tr><th>Population</th><td>{{ number_format($country->population ?? 0) }}</td></tr>
                    <tr><th>GDP</th><td>${{ number_format($country->gdp ?? 0, 2) }}</td></tr>
                    <tr><th>Inflation</th><td>{{ $country->inflation ?? 0 }}%</td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Risk Score -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">⚠️ Risk Score</h5>
            </div>
            <div class="card-body">
                @if(isset($latestRisk) && $latestRisk)
                <table class="table table-sm">
                    <tr><th>Weather Risk</th><td>{{ $latestRisk->weather_score }}</td></tr>
                    <tr><th>Inflation Risk</th><td>{{ $latestRisk->inflation_score }}</td></tr>
                    <tr><th>Currency Risk</th><td>{{ $latestRisk->currency_score }}</td></tr>
                    <tr><th>Political Risk</th><td>{{ $latestRisk->news_score }}</td></tr>
                    <tr><th><strong>Total</strong></th><td><strong>{{ $latestRisk->total_score }}</strong></td></tr>
                    <tr><th>Level</th>
                        <td>
                            @php
                                $level = $latestRisk->risk_level ?? 'Low';
                                $color = 'success';
                                if($level == 'Critical') $color = 'danger';
                                elseif($level == 'High') $color = 'warning';
                                elseif($level == 'Medium') $color = 'info';
                            @endphp
                            <span class="badge bg-{{ $color }} fs-6">{{ $level }}</span>
                        </td>
                    </tr>
                </table>
                @else
                <p class="text-muted">No risk data. <a href="/test/risk/{{ $country->country_code }}">Calculate</a></p>
                @endif
            </div>
        </div>
    </div>

    <!-- Weather -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">🌤️ Current Weather</h5>
            </div>
            <div class="card-body text-center">
                @if(isset($weather) && $weather && isset($weather['current_weather']))
                    @php
                        $temp = $weather['current_weather']['temperature'] ?? 0;
                        $wind = $weather['current_weather']['windspeed'] ?? 0;
                        $code = $weather['current_weather']['weathercode'] ?? 0;
                        $desc = \App\Services\WeatherService::getWeatherDescription($code);
                    @endphp
                    <div class="display-1">
                        @if($temp > 30) ☀️
                        @elseif($temp > 20) ⛅
                        @elseif($temp > 10) 🌤️
                        @elseif($temp > 0) 🌧️
                        @else ❄️
                        @endif
                    </div>
                    <h2>{{ $temp }}°C</h2>
                    <p>{{ $desc }}</p>
                    <p class="text-muted">Wind: {{ $wind }} km/h</p>
                @else
                    <p class="text-muted">Weather data not available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- News -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">📰 Latest News</h5>
            </div>
            <div class="card-body">
                @if(isset($news) && $news->count() > 0)
                    <div class="row">
                        @foreach($news as $item)
                        <div class="col-md-6 mb-2">
                            <div class="border p-2 rounded">
                                <h6>{{ $item->title }}</h6>
                                <p class="text-muted small">{{ Str::limit($item->description ?? '', 100) }}</p>
                                @php
                                    $sentiment = $item->sentiment ?? 'neutral';
                                    $color = $sentiment == 'positive' ? 'success' : ($sentiment == 'negative' ? 'danger' : 'secondary');
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($sentiment) }}</span>
                                <small class="text-muted ms-2">{{ $item->source ?? 'Unknown' }}</small>
                                @if($item->url && $item->url != '#')
                                    <a href="{{ $item->url }}" target="_blank" class="btn btn-sm btn-outline-primary float-end">Read</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No news available. <a href="/test/news/{{ $country->country_code }}">Fetch news</a></p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Ports -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">⚓ Ports in {{ $country->country_name }}</h5>
            </div>
            <div class="card-body">
                @if(isset($ports) && $ports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Port Name</th><th>Type</th><th>Size</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach($ports as $port)
                                <tr>
                                    <td>{{ $port->port_name }}</td>
                                    <td>{{ $port->harbor_type ?? '-' }}</td>
                                    <td>{{ $port->harbor_size ?? '-' }}</td>
                                    <td>
                                        @php
                                            $status = $port->status ?? 'Unknown';
                                            $color = $status == 'Operational' ? 'success' : ($status == 'Under Construction' ? 'warning' : 'danger');
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ $status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No ports data available for this country.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection