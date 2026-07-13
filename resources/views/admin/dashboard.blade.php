@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">👑 Admin Dashboard</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h5>👤 Users</h5>
                <h2>{{ $totalUsers ?? 0 }}</h2>
                <a href="/admin/users" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h5>⚓ Ports</h5>
                <h2>{{ $totalPorts ?? 0 }}</h2>
                <a href="/admin/ports" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <h5>📰 Articles</h5>
                <h2>{{ $totalArticles ?? 0 }}</h2>
                <a href="/admin/articles" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
</div>
@endsection