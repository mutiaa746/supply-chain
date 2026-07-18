@extends('layouts.app')

@section('title', 'Visualization')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📊 Data Visualization Dashboard</h1>
        <p class="text-muted">Visualisasi data ekonomi dan risiko</p>
    </div>
</div>

<!-- 4 GRAFIK -->
<div class="row">
    <!-- GDP CHART -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">💰 GDP Top 10 Countries (Trillion USD)</h5>
            </div>
            <div class="card-body">
                <canvas id="gdpChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- INFLATION CHART -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📈 Inflation Top 10 Countries (%)</h5>
            </div>
            <div class="card-body">
                <canvas id="inflationChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- CURRENCY CHART -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">💱 Exchange Rates (USD = 1)</h5>
            </div>
            <div class="card-body">
                <canvas id="currencyChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- RISK CHART -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">⚠️ Risk Score Top 10 Countries</h5>
            </div>
            <div class="card-body">
                <canvas id="riskChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- RISK DISTRIBUTION -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📊 Risk Distribution</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="riskDistributionChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Level</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total = array_sum($riskDistribution);
                                    @endphp
                                    <tr>
                                        <td><span class="badge bg-danger">🔴 High</span></td>
                                        <td>{{ $riskDistribution['High'] }}</td>
                                        <td>{{ $total > 0 ? round(($riskDistribution['High'] / $total) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning">🟡 Medium</span></td>
                                        <td>{{ $riskDistribution['Medium'] }}</td>
                                        <td>{{ $total > 0 ? round(($riskDistribution['Medium'] / $total) * 100, 1) : 0 }}%</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-success">🟢 Low</span></td>
                                        <td>{{ $riskDistribution['Low'] }}</td>
                                        <td>{{ $total > 0 ? round(($riskDistribution['Low'] / $total) * 100, 1) : 0 }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chart.js loaded!');

    // ========== GDP CHART ==========
    var gdpData = @json($gdpData ?? []);
    if (gdpData.length > 0) {
        new Chart(document.getElementById('gdpChart'), {
            type: 'bar',
            data: {
                labels: gdpData.map(function(d) { return d.country; }),
                datasets: [{
                    label: 'GDP (Trillion USD)',
                    data: gdpData.map(function(d) { return d.gdp; }),
                    backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#6f42c1','#fd7e14','#20c997','#0dcaf0','#d63384'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // ========== INFLATION CHART ==========
    var inflationData = @json($inflationData ?? []);
    if (inflationData.length > 0) {
        new Chart(document.getElementById('inflationChart'), {
            type: 'bar',
            data: {
                labels: inflationData.map(function(d) { return d.country; }),
                datasets: [{
                    label: 'Inflation (%)',
                    data: inflationData.map(function(d) { return d.inflation; }),
                    backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#4e73df','#36b9cc','#6f42c1','#fd7e14','#20c997','#0dcaf0','#d63384'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // ========== CURRENCY CHART ==========
    var currencyData = @json($currencyData ?? []);
    if (currencyData.length > 0) {
        new Chart(document.getElementById('currencyChart'), {
            type: 'bar',
            data: {
                labels: currencyData.map(function(d) { return d.currency; }),
                datasets: [{
                    label: 'Exchange Rate (1 USD)',
                    data: currencyData.map(function(d) { return d.rate; }),
                    backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#6f42c1','#fd7e14','#20c997','#0dcaf0','#d63384'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // ========== RISK CHART ==========
    var riskData = @json($riskData ?? []);
    if (riskData.length > 0) {
        new Chart(document.getElementById('riskChart'), {
            type: 'bar',
            data: {
                labels: riskData.map(function(d) { return d.country; }),
                datasets: [{
                    label: 'Risk Score',
                    data: riskData.map(function(d) { return d.risk; }),
                    backgroundColor: riskData.map(function(d) {
                        return d.risk >= 70 ? '#e74a3b' : (d.risk >= 50 ? '#f6c23e' : '#1cc88a');
                    }),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // ========== RISK DISTRIBUTION CHART ==========
    var riskDistribution = @json($riskDistribution ?? []);
    new Chart(document.getElementById('riskDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['High Risk', 'Medium Risk', 'Low Risk'],
            datasets: [{
                data: [riskDistribution.High || 0, riskDistribution.Medium || 0, riskDistribution.Low || 0],
                backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a'],
                borderColor: '#fff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true, pointStyle: 'circle' }
                }
            },
            cutout: '65%'
        }
    });
});
</script>
@endsection