@extends('layouts.app')

@section('title', 'Add Port')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Add Port</h5></div>
            <div class="card-body">
                <form method="POST" action="/admin/ports">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Port Name</label>
                        <input type="text" name="port_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="0.0000001" name="latitude" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="0.0000001" name="longitude" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="Operational">Operational</option>
                            <option value="Under Construction">Under Construction</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/ports" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection