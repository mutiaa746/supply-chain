@extends('layouts.app')

@section('title', 'Ports')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">⚓ World Ports ({{ $ports->count() }})</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="portsTable">
                        <thead>
                            <tr>
                                <th>Port Name</th>
                                <th>Country</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Status</th>
                                <th>Coordinates</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ports as $port)
                            <tr>
                                <td><strong>{{ $port->port_name }}</strong></td>
                                <td>{{ $port->country->country_name ?? $port->country_name }}</td>
                                <td>{{ $port->harbor_type ?? '-' }}</td>
                                <td>{{ $port->harbor_size ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $port->status == 'Operational' ? 'success' : ($port->status == 'Under Construction' ? 'warning' : 'danger') }}">
                                        {{ $port->status ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>
                                    @if($port->latitude && $port->longitude)
                                        {{ number_format($port->latitude, 2) }}, {{ number_format($port->longitude, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No ports data available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection