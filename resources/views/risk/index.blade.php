@extends('layouts.app')

@section('title', 'Risk Scores')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">⚠️ Risk Scoring Dashboard</h1>
    </div>
</div>

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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>📋 Risk Scores by Country</h5>
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
                                <td>{{ $risk->country->country_name ?? 'Unknown' }}</td>
                                <td>{{ $risk->weather_score }}</td>
                                <td>{{ $risk->inflation_score }}</td>
                                <td>{{ $risk->currency_score }}</td>
                                <td>{{ $risk->news_score }}</td>
                                <td><strong>{{ $risk->total_score }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $risk->risk_level == 'Critical' ? 'danger' : ($risk->risk_level == 'High' ? 'warning' : ($risk->risk_level == 'Medium' ? 'info' : 'success')) }}">
                                        {{ $risk->risk_level }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No risk data. 
                                    <a href="/test/risk/all" class="btn btn-sm btn-primary ms-2">Calculate All</a>
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
@endsection