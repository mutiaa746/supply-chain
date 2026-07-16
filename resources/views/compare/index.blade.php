@extends('layouts.app')

@section('title', 'Compare Countries')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📊 Country Comparison</h1>
        <p class="text-muted">Select two countries to compare their economic and risk indicators</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('compare.result') }}" class="row">
                    <div class="col-md-5">
                        <label class="form-label">Country 1</label>
                        <select name="country1" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 text-center pt-4">
                        <h4>VS</h4>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Country 2</label>
                        <select name="country2" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Compare</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection