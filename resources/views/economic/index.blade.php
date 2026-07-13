@extends('layouts.app')

@section('title', 'Economic Indicators')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📈 Economic Indicators</h1>
    </div>
</div>

<div class="row">
    @forelse($countries as $country)
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5>
                    @if($country->flag)
                        <img src="{{ $country->flag }}" width="24" height="16">
                    @endif
                    {{ $country->country_name }}
                </h5>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>GDP</td>
                        <td><strong>${{ number_format($country->gdp ?? 0, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Inflation</td>
                        <td><strong>{{ $country->inflation ?? 0 }}%</strong></td>
                    </tr>
                    <tr>
                        <td>Population</td>
                        <td><strong>{{ number_format($country->population ?? 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Currency</td>
                        <td><strong>{{ $country->currency ?? '-' }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12">
        <div class="alert alert-warning">No economic data available. Please run <a href="/test/countries">/test/countries</a>.</div>
    </div>
    @endforelse
</div>
@endsection