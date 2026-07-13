@extends('layouts.app')

@section('title', 'Exchange Rates')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">💰 Currency Exchange Rates</h1>
        <p class="text-muted">Base Currency: <strong>USD</strong> | <a href="/exchange/fetch" class="btn btn-sm btn-primary">🔄 Refresh</a></p>
    </div>
</div>

<!-- Grafik -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>📊 Exchange Rate Chart (USD = 1)</h5>
            </div>
            <div class="card-body">
                <canvas id="exchangeChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabel -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>📋 All Exchange Rates</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Rate (1 USD)</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rates as $rate)
                            <tr>
                                <td><strong>{{ $rate->target_currency }}</strong></td>
                                <td>{{ number_format($rate->rate, 4) }}</td>
                                <td>{{ $rate->updated_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No data. <a href="/exchange/fetch">Fetch Data</a></td>
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
    var chartRates = @json($chartRates ?? []);
    var labels = chartRates.map(function(r) { return r.target_currency; });
    var data = chartRates.map(function(r) { return r.rate; });
    
    var ctx = document.getElementById('exchangeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Exchange Rate (USD = 1)',
                data: data,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14', '#20c997', '#0dcaf0', '#d63384'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endsection