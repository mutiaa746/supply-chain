@extends('layouts.app')

@section('title', 'Route Simulation')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">🗺️ Route Simulation</h1>
        <p class="text-muted">Simulasikan rute antar negara dan lihat risiko logistik</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('route-simulation.calculate') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">📍 Negara Asal</label>
                            <select name="country1" class="form-select" required>
                                <option value="">-- Pilih Negara --</option>
                                @foreach($countries as $c)
                                <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-center pt-4">
                            <h1 class="display-4 text-muted">✈️</h1>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">📍 Negara Tujuan</label>
                            <select name="country2" class="form-select" required>
                                <option value="">-- Pilih Negara --</option>
                                @foreach($countries as $c)
                                <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">🚗 Moda Transportasi</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="transport" value="plane" id="plane" checked>
                                    <label class="form-check-label" for="plane">✈️ Pesawat</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="transport" value="ship" id="ship">
                                    <label class="form-check-label" for="ship">🚢 Kapal</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-route me-2"></i> Simulasikan Rute
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection