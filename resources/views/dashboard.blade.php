@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold">
            Supply Chain Risk Monitoring
        </h2>

        <small class="text-muted">
            Dashboard Monitoring
        </small>

    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('refresh.data') }}"
           class="btn btn-primary">

            <i class="bi bi-arrow-repeat"></i>

            Refresh Data

        </a>

        <span class="badge bg-success p-3">

            Last Update :
            {{ $lastUpdate ?? '-' }}

        </span>

    </div>

</div>

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    <strong>Success!</strong>

    {{ session('success') }}

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif

@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">

    <strong>Error!</strong>

    {{ session('error') }}

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif

{{-- SUMMARY --}}

<div class="row g-3 mb-4">

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body">

<h6>Countries</h6>

<h2>{{ $countries }}</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body">

<h6>Weather</h6>

<h2>{{ $weather }}</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body">

<h6>News</h6>

<h2>{{ $news }}</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body">

<h6>Risk</h6>

<h2>{{ $risk }}</h2>

</div>

</div>

</div>

</div>

{{-- RISK CARD --}}

<div class="row g-3 mb-4">

<div class="col-md-4">

<div class="card border-success">

<div class="card-body text-center">

<h5>Low Risk</h5>

<h1 class="text-success">

{{ $lowRisk }}

</h1>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-warning">

<div class="card-body text-center">

<h5>Medium Risk</h5>

<h1 class="text-warning">

{{ $mediumRisk }}

</h1>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-danger">

<div class="card-body text-center">

<h5>High Risk</h5>

<h1 class="text-danger">

{{ $highRisk }}

</h1>

</div>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header">

Risk Distribution

</div>

<div class="card-body">

<canvas id="riskChart"></canvas>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header">

Sentiment Distribution

</div>

<div class="card-body">

<canvas id="sentimentChart"></canvas>

</div>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header">

Weather Status

</div>

<div class="card-body">

<canvas id="weatherChart"></canvas>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header">

Economic Summary

</div>

<div class="card-body">

<table class="table">

<tr>

<th>Average GDP</th>

<td>{{ number_format($avgGDP,2) }}</td>

</tr>

<tr>

<th>Average Inflation</th>

<td>{{ number_format($avgInflation,2) }}%</td>

</tr>

<tr>

<th>Average Exchange Rate</th>

<td>{{ number_format($avgExchange,2) }}</td>

</tr>

</table>

</div>

</div>

</div>

</div>

<div class="card shadow mb-4">

<div class="card-header">

Top 5 High Risk Countries

</div>

<div class="card-body">

<table class="table table-striped">

<thead>

<tr>

<th>Country</th>

<th>Total Score</th>

<th>Risk</th>

</tr>

</thead>

<tbody>

@foreach($topRisk as $item)

<tr>

<td>{{ $item->country->country_name }}</td>

<td>{{ $item->total_score }}</td>

<td>

<span class="badge bg-danger">

{{ $item->risk_level }}

</span>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(document.getElementById('riskChart'),{

type:'doughnut',

data:{

labels:['Low','Medium','High'],

datasets:[{

data:[

{{ $lowRisk }},

{{ $mediumRisk }},

{{ $highRisk }}

],

backgroundColor:[

'#198754',

'#ffc107',

'#dc3545'

]

}]

}

});

new Chart(document.getElementById('sentimentChart'),{

type:'pie',

data:{

labels:['Positive','Neutral','Negative'],

datasets:[{

data:[

{{ $positiveNews }},

{{ $neutralNews }},

{{ $negativeNews }}

],

backgroundColor:[

'#198754',

'#0dcaf0',

'#dc3545'

]

}]

}

});

new Chart(document.getElementById('weatherChart'),{

type:'bar',

data:{

labels:['Low','Medium','High'],

datasets:[{

label:'Storm Risk',

data:[

{{ $lowStorm }},

{{ $mediumStorm }},

{{ $highStorm }}

]

}]

}

});

</script>

@endsection