@extends('layouts.app')

@section('title', 'Watchlist')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">⭐ Daftar Pantauan</h1>
        <p class="text-muted">Negara-negara yang Anda pantau</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Klik tombol <strong>⭐ Tambah ke Pantauan</strong> di halaman detail negara untuk menambahkan ke daftar ini.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Negara</th>
                                <th>Risk Score</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada negara yang dipantau.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection