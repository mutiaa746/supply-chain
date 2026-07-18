@extends('layouts.app')

@section('title', 'Edit Port')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Edit Port</h5></div>
            <div class="card-body">
                <form method="POST" action="/admin/ports/{{ $port->id }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Port Name</label>
                        <input type="text" name="port_name" class="form-control" value="{{ $port->port_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="0.0000001" name="latitude" class="form-control" value="{{ $port->latitude }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="0.0000001" name="longitude" class="form-control" value="{{ $port->longitude }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="Operational" {{ $port->status == 'Operational' ? 'selected' : '' }}>Operational</option>
                            <option value="Under Construction" {{ $port->status == 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                            <option value="Closed" {{ $port->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/ports" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection