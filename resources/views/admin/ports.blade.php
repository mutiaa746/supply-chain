@extends('layouts.app')

@section('title', 'Manage Ports')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">⚓ Manage Ports</h1>
        <a href="/admin/ports/create" class="btn btn-primary mb-3">+ Add Port</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Port Name</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ports as $port)
                            <tr>
                                <td>{{ $port->id }}</td>
                                <td>{{ $port->port_name }}</td>
                                <td>{{ $port->latitude }}</td>
                                <td>{{ $port->longitude }}</td>
                                <td>{{ $port->status ?? '-' }}</td>
                                <td>
                                    <a href="/admin/ports/{{ $port->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="/admin/ports/{{ $port->id }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this port?')">Delete</button>
                                    </form>
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