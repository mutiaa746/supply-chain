@extends('layouts.app')

@section('title', 'News')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📰 News Intelligence</h1>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form method="GET" class="d-flex">
            <select name="country" class="form-select me-2">
                @foreach($countries as $c)
                    <option value="{{ $c->country_code }}" {{ $countryCode == $c->country_code ? 'selected' : '' }}>
                        {{ $c->country_name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <a href="/news/fetch/{{ $countryCode }}" class="btn btn-success">🔄 Fetch Latest News</a>
    </div>
</div>

<div class="row">
    @forelse($news as $item)
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $item->title }}</h5>
                <p class="card-text">{{ Str::limit($item->description ?? 'No description', 200) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @php
                            $sentiment = $item->sentiment ?? 'neutral';
                            $color = $sentiment == 'positive' ? 'success' : ($sentiment == 'negative' ? 'danger' : 'secondary');
                        @endphp
                        <span class="badge bg-{{ $color }}">
                            {{ ucfirst($sentiment) }}
                        </span>
                        <span class="text-muted small ms-2">{{ $item->source ?? 'Unknown' }}</span>
                    </div>
                    <small class="text-muted">{{ $item->published_at ? $item->published_at->format('d M Y H:i') : '-' }}</small>
                </div>
                @if($item->url && $item->url != '#')
                    <a href="{{ $item->url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Read More</a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12">
        <div class="alert alert-warning">
            No news found for {{ $countryCode }}. 
            <a href="/news/fetch/{{ $countryCode }}" class="btn btn-sm btn-primary ms-2">Fetch News</a>
        </div>
    </div>
    @endforelse
</div>
@endsection