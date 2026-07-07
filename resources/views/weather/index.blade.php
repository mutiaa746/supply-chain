@extends('layouts.app')

@section('content')

<h2 class="mb-4">

    Weather Monitoring

</h2>

<div class="card shadow">

    <div class="card-body">

        <form action="{{ url('/weather') }}" method="GET">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search Country">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">

                        Search

                    </button>

                </div>

            </div>

        </form>

        <table class="table table-bordered table-hover">

            <thead class="table-dark">

                <tr>

                    <th>No</th>

                    <th>Country</th>

                    <th>Temperature</th>

                    <th>Rainfall</th>

                    <th>Wind Speed</th>

                    <th>Storm Risk</th>

                    <th>Recorded</th>

                </tr>

            </thead>

            <tbody>

            @forelse($weather as $item)

                <tr>

                    <td>

                        {{ $weather->firstItem() + $loop->index }}

                    </td>

                    <td>

                        {{ $item->country->country_name }}

                    </td>

                    <td>

                        {{ $item->temperature }} °C

                    </td>

                    <td>

                        {{ $item->rainfall }} mm

                    </td>

                    <td>

                        {{ $item->wind_speed }} km/h

                    </td>

                    <td>

                        @if($item->storm_risk=="High")

                            <span class="badge bg-danger">

                                High

                            </span>

                        @elseif($item->storm_risk=="Medium")

                            <span class="badge bg-warning">

                                Medium

                            </span>

                        @else

                            <span class="badge bg-success">

                                Low

                            </span>

                        @endif

                    </td>

                    <td>

                        {{ $item->recorded_at }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center">

                        No Weather Data

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $weather->withQueryString()->links() }}

    </div>

</div>

@endsection