@extends('layouts.app')

@section('title', 'Countries')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">🌍 Countries ({{ $countries->count() }})</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Flag</th>
                                <th>Country</th>
                                <th>Code</th>
                                <th>Capital</th>
                                <th>Region</th>
                                <th>Currency</th>
                                <th>Population</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($countries as $country)
                            <tr>
                                <td>
                                    @if($country->flag)
                                        <img src="{{ $country->flag }}" width="30" height="20" alt="flag">
                                    @else
                                        <img src="https://flagcdn.com/w20/{{ strtolower($country->country_code) }}.png" width="30" height="20" alt="flag">
                                    @endif
                                </td>
                                <td><strong>{{ $country->country_name }}</strong></td>
                                <td>{{ $country->country_code }}</td>
                                <td>{{ $country->capital ?? '-' }}</td>
                                <td>{{ $country->region ?? '-' }}</td>
                                <td>{{ $country->currency ?? '-' }}</td>
                                <td>{{ number_format($country->population ?? 0) }}</td>
                                <td>
                                    <a href="{{ route('countries.show', $country->id) }}" class="btn btn-primary btn-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection