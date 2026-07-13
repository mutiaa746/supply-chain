@extends('layouts.app')

@section('title', 'Weather')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">🌤️ Global Weather Monitoring</h1>
        <p class="text-muted">Real-time weather data from Open-Meteo API</p>
    </div>
</div>

<div class="row">
    @if(isset($weatherData) && count($weatherData) > 0)
        @foreach($weatherData as $data)
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>
                        @if($data['country']->flag)
                            <img src="{{ $data['country']->flag }}" width="24" height="16">
                        @endif
                        {{ $data['country']->country_name }}
                    </h5>
                    <div class="display-1">
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
                    <h3>{{ $temp }}°C</h3>
                    <p>{{ $data['description'] ?? 'Unknown' }}</p>
                    <p class="text-muted small">Wind: {{ $data['weather']['windspeed'] ?? 0 }} km/h</p>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-md-12">
            <div class="alert alert-warning">
                No weather data available. Please check your internet connection.
            </div>
        </div>
    @endif
</div>
@endsection