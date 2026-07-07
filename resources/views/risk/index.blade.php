@extends('layouts.app')

@section('content')

<h2 class="mb-4">

    Risk Score Monitoring

</h2>

<div class="card shadow">

<div class="card-body">

<form action="{{ url('/risk') }}" method="GET">

<div class="row mb-3">

<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Country..."
value="{{ $search }}">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

Search

</button>

</div>

</div>

</form>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>No</th>

<th>Country</th>

<th>Weather</th>

<th>Inflation</th>

<th>Currency</th>

<th>News</th>

<th>Total</th>

<th>Risk</th>

</tr>

</thead>

<tbody>

@forelse($riskScores as $item)

<tr>

<td>{{ $riskScores->firstItem()+$loop->index }}</td>

<td>{{ $item->country->country_name }}</td>

<td>{{ $item->weather_score }}</td>

<td>{{ $item->inflation_score }}</td>

<td>{{ $item->currency_score }}</td>

<td>{{ $item->news_score }}</td>

<td>

<strong>

{{ $item->total_score }}

</strong>

</td>

<td>

@if($item->risk_level=="Low")

<span class="badge bg-success">

Low

</span>

@elseif($item->risk_level=="Medium")

<span class="badge bg-warning">

Medium

</span>

@else

<span class="badge bg-danger">

High

</span>

@endif

</td>

</tr>

@empty

<tr>

<td colspan="8" class="text-center">

No Risk Data

</td>

</tr>

@endforelse

</tbody>

</table>

{{ $riskScores->withQueryString()->links() }}

</div>

</div>

@endsection