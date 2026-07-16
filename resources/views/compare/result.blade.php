@extends('layouts.app')

@section('title', 'Compare Result')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📊 Comparison Result</h1>
        <a href="{{ route('compare') }}" class="btn btn-secondary mb-3">← Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4>{{ $country1->country_name }}</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr><th>GDP</th><td>${{ number_format($country1->gdp ?? 0, 2) }}</td></tr>
                    <tr><th>Inflation</th><td>{{ $country1->inflation ?? 0 }}%</td></tr>
                    <tr><th>Population</th><td>{{ number_format($country1->population ?? 0) }}</td></tr>
                    <tr><th>Currency</th><td>{{ $country1->currency ?? '-' }}</td></tr>
                    <tr><th>Risk Score</th>
                        <td>
                            @php
                                $risk1 = $country1->riskScores->last();
                            @endphp
                            {{ $risk1->total_score ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr><th>Risk Level</th>
                        <td>
                            @php
                                $level1 = $risk1->risk_level ?? 'Low';
                                $color1 = $level1 == 'Critical' ? 'danger' : ($level1 == 'High' ? 'warning' : ($level1 == 'Medium' ? 'info' : 'success'));
                            @endphp
                            <span class="badge bg-{{ $color1 }}">{{ $level1 }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-2 text-center pt-5">
        <h1 class="display-3 text-muted">VS</h1>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success text-white text-center">
                <h4>{{ $country2->country_name }}</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr><th>GDP</th><td>${{ number_format($country2->gdp ?? 0, 2) }}</td></tr>
                    <tr><th>Inflation</th><td>{{ $country2->inflation ?? 0 }}%</td></tr>
                    <tr><th>Population</th><td>{{ number_format($country2->population ?? 0) }}</td></tr>
                    <tr><th>Currency</th><td>{{ $country2->currency ?? '-' }}</td></tr>
                    <tr><th>Risk Score</th>
                        <td>
                            @php
                                $risk2 = $country2->riskScores->last();
                            @endphp
                            {{ $risk2->total_score ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr><th>Risk Level</th>
                        <td>
                            @php
                                $level2 = $risk2->risk_level ?? 'Low';
                                $color2 = $level2 == 'Critical' ? 'danger' : ($level2 == 'High' ? 'warning' : ($level2 == 'Medium' ? 'info' : 'success'));
                            @endphp
                            <span class="badge bg-{{ $color2 }}">{{ $level2 }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection