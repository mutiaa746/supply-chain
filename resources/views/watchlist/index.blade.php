@extends('layouts.app')

@section('title', 'Watchlist')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">⭐ Daftar Pantauan</h1>
        <p class="text-muted">Negara-negara yang Anda pantau</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if($watchlists->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Negara</th>
                                    <th>Kode</th>
                                    <th>Risk Score</th>
                                    <th>Level</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($watchlists as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($item->country->flag)
                                            <img src="{{ $item->country->flag }}" width="24" height="16" class="me-1">
                                        @endif
                                        <strong>{{ $item->country->country_name }}</strong>
                                    </td>
                                    <td>{{ $item->country->country_code }}</td>
                                    <td>
                                        @php
                                            $risk = $item->country->riskScores->last();
                                        @endphp
                                        {{ $risk->total_score ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @php
                                            $level = $risk->risk_level ?? 'Low';
                                            $color = $level == 'Critical' ? 'danger' : ($level == 'High' ? 'warning' : ($level == 'Medium' ? 'info' : 'success'));
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ $level }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('watchlist.destroy', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dari watchlist?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada negara yang dipantau.</p>
                        <p class="text-muted">Kunjungi halaman <a href="{{ route('countries') }}">Countries</a> untuk menambahkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection