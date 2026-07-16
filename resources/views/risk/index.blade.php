@extends('layouts.app')

@section('title', 'Risk Scores')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">⚠️ Risk Scoring Dashboard</h1>
        <p class="text-muted">Total: <strong>{{ $riskScores->count() }}</strong> countries</p>
    </div>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-danger">
            <div class="card-body text-center">
                <h5>🔴 High Risk</h5>
                <h2>{{ $highRisk ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <h5>🟡 Medium Risk</h5>
                <h2>{{ $mediumRisk ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h5>🟢 Low Risk</h5>
                <h2>{{ $lowRisk ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH -->
<div class="row mb-3">
    <div class="col-md-6">
        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" 
                   placeholder="Cari negara..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Cari
            </button>
            <a href="{{ route('risk') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-undo"></i> Reset
            </a>
        </form>
    </div>
</div>

<!-- TABEL -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📋 Risk Scores by Country</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Weather</th>
                                <th>Inflation</th>
                                <th>Currency</th>
                                <th>Political</th>
                                <th>Total</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riskScores as $risk)
                            <tr>
                                <td>
                                    @if($risk->country && $risk->country->flag)
                                        <img src="{{ $risk->country->flag }}" width="20" height="13" class="me-1">
                                    @endif
                                    <strong>{{ $risk->country->country_name ?? 'Unknown' }}</strong>
                                </td>
                                <td>{{ $risk->weather_score }}</td>
                                <td>{{ $risk->inflation_score }}</td>
                                <td>{{ $risk->currency_score }}</td>
                                <td>{{ $risk->news_score }}</td>
                                <td>
                                    <strong class="text-{{ $risk->total_score >= 70 ? 'danger' : ($risk->total_score >= 50 ? 'warning' : 'success') }}">
                                        {{ $risk->total_score }}
                                    </strong>
                                </td>
                                <td>
                                    @php
                                        $lvl = $risk->risk_level ?? 'Low';
                                        $clr = $lvl == 'Critical' ? 'danger' : ($lvl == 'High' ? 'warning' : ($lvl == 'Medium' ? 'info' : 'success'));
                                    @endphp
                                    <span class="badge bg-{{ $clr }} fs-6 px-3 py-2">{{ $lvl }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No risk data available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection