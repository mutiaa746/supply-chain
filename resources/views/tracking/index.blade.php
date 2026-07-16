@extends('layouts.app')

@section('title', 'Tracking')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📦 Track Your Package</h1>
        <p class="text-muted">Enter your tracking number to check the status of your shipment</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Form Tracking -->
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('tracking.search') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="tracking_number" class="form-control form-control-lg" 
                               placeholder="Enter tracking number (e.g., SC-2024-001)" required>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i> Track
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Hasil Tracking -->
        @if(session('tracking_result'))
        @php
            $result = session('tracking_result');
            $status = $result['status'];
            $statusColor = match($status) {
                'Delivered' => 'success',
                'In Transit' => 'primary',
                'Processing' => 'warning',
                'On Hold' => 'danger',
                'Customs Clearance' => 'info',
                default => 'secondary'
            };
            $statusIcon = match($status) {
                'Delivered' => '✅',
                'In Transit' => '🚚',
                'Processing' => '🔄',
                'On Hold' => '⏸️',
                'Customs Clearance' => '📋',
                default => '📦'
            };
        @endphp
        <div class="card mt-4">
            <div class="card-header bg-{{ $statusColor }} text-white">
                <h5 class="mb-0">
                    {{ $statusIcon }} Tracking Result: 
                    <span class="badge bg-light text-dark ms-2">{{ $status }}</span>
                </h5>
            </div>
            <div class="card-body">
                <!-- Info Ringkas -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th>Tracking Number</th><td><strong>{{ $result['number'] }}</strong></td></tr>
                            <tr><th>Origin</th><td>{{ $result['origin'] }}</td></tr>
                            <tr><th>Destination</th><td>{{ $result['destination'] }}</td></tr>
                            <tr><th>Estimated Delivery</th><td>{{ $result['estimated_delivery'] }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th>Weight</th><td>{{ $result['weight'] }}</td></tr>
                            <tr><th>Package Type</th><td>{{ $result['package_type'] }}</td></tr>
                            <tr><th>Last Update</th><td>{{ $result['last_update'] }}</td></tr>
                            <tr><th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $statusColor }} fs-6">
                                        {{ $statusIcon }} {{ $status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Progress Bar -->
                @php
                    $progress = match($status) {
                        'Delivered' => 100,
                        'In Transit' => 70,
                        'Customs Clearance' => 50,
                        'Processing' => 30,
                        'On Hold' => 20,
                        default => 10
                    };
                @endphp
                <div class="mb-3">
                    <label class="form-label">Shipment Progress</label>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-{{ $statusColor }} progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: {{ $progress }}%;">
                            {{ $progress }}%
                        </div>
                    </div>
                </div>

                <!-- Riwayat Tracking -->
                <h6 class="mt-3">📋 Tracking History</h6>
                <div class="timeline">
                    @foreach($result['history'] as $item)
                    <div class="d-flex mb-2">
                        <div class="me-3 text-muted" style="min-width: 150px;">
                            <small>{{ $item['date'] }}</small>
                        </div>
                        <div>
                            <strong>{{ $item['location'] }}</strong>
                            <span class="badge bg-secondary ms-2">{{ $item['status'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection