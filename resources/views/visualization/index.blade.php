@extends('layouts.app')

@section('title', 'Visualization')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📊 Visualisasi Data</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>GDP Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="gdpChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Inflation Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="inflationChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Currency Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="currencyChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Risk Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="riskTrendChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('gdpChart'), {
        type: 'line',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024'],
            datasets: [{ label: 'GDP (USD Trillion)', data: [10, 12, 14, 16, 18], borderColor: '#4e73df', fill: false }]
        }
    });
    new Chart(document.getElementById('inflationChart'), {
        type: 'line',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024'],
            datasets: [{ label: 'Inflation (%)', data: [2.5, 3.0, 5.5, 4.0, 2.8], borderColor: '#e74a3b', fill: false }]
        }
    });
    new Chart(document.getElementById('currencyChart'), {
        type: 'bar',
        data: {
            labels: ['USD', 'EUR', 'GBP', 'JPY', 'IDR'],
            datasets: [{ label: 'Exchange Rate', data: [1, 0.92, 0.78, 148, 15500], backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'] }]
        }
    });
    new Chart(document.getElementById('riskTrendChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{ label: 'Risk Score', data: [30, 35, 28, 40, 32, 25], borderColor: '#e74a3b', fill: true, backgroundColor: 'rgba(231, 74, 59, 0.1)' }]
        }
    });
});
</script>
@endsection