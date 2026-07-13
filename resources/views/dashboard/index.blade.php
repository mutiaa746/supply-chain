@extends('layouts.app')

@section('title', 'Dashboard - Supply Chain Risk')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📊 Dashboard Supply Chain Risk Intelligence</h1>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">🌍 Countries</h5>
                <h2>{{ $totalCountries ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">⚓ Ports</h5>
                <h2>{{ $totalPorts ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">⚠️ High Risk</h5>
                <h2>{{ $highRisk ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">📰 News</h5>
                <h2>{{ $totalNews ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row mb-4">
    <!-- Grafik Exchange Rates -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>💰 Exchange Rates (USD Base)</h5>
            </div>
            <div class="card-body">
                <canvas id="exchangeChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Grafik Risk Distribution -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>📊 Risk Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="riskChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Risk Scores -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>🔄 Recent Risk Scores</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
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
                            @forelse($recentRisks ?? [] as $risk)
                            <tr>
                                <td>{{ $risk->country->country_name ?? 'Unknown' }}</td>
                                <td>{{ $risk->weather_score ?? 0 }}</td>
                                <td>{{ $risk->inflation_score ?? 0 }}</td>
                                <td>{{ $risk->currency_score ?? 0 }}</td>
                                <td>{{ $risk->news_score ?? 0 }}</td>
                                <td><strong>{{ $risk->total_score ?? 0 }}</strong></td>
                                <td>
                                    @php
                                        $level = $risk->risk_level ?? 'Low';
                                        $color = $level == 'Critical' ? 'danger' : ($level == 'High' ? 'warning' : ($level == 'Medium' ? 'info' : 'success'));
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ $level }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No risk data available. 
                                    <a href="/test/risk/all" class="btn btn-sm btn-primary ms-2">Calculate Risk</a>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Exchange Rates
    @if(isset($chartData) && !empty($chartData['labels']))
    const exchangeData = @json($chartData);
    const ctx1 = document.getElementById('exchangeChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: exchangeData.labels,
            datasets: [{
                label: 'Exchange Rate (USD = 1)',
                data: exchangeData.rates,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
    
    // Chart Risk Distribution
    @if(isset($riskDistribution))
    const riskData = @json($riskDistribution);
    const ctx2 = document.getElementById('riskChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['High Risk', 'Medium Risk', 'Low Risk'],
            datasets: [{
                data: [riskData.High || 0, riskData.Medium || 0, riskData.Low || 0],
                backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif
});
</script>
@endsection