@extends('layouts.app')

@section('content')

<h2 class="mb-4">

    Ports Monitoring

</h2>

<div class="card shadow">

    <div class="card-body">

        <form action="{{ url('/ports') }}" method="GET">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search Port or Country">

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
                    <th>Port</th>
                    <th>Country</th>
                    <th>Harbor Size</th>
                    <th>Harbor Type</th>
                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

            @forelse($ports as $item)

                <tr>

                    <td>{{ $ports->firstItem()+$loop->index }}</td>

                    <td>{{ $item->port_name }}</td>

                    <td>{{ $item->country_name }}</td>

                    <td>{{ $item->harbor_size }}</td>

                    <td>{{ $item->harbor_type }}</td>

                    <td>

                        @if($item->status=="Open")

                            <span class="badge bg-success">

                                Open

                            </span>

                        @elseif($item->status=="Closed")

                            <span class="badge bg-danger">

                                Closed

                            </span>

                        @else

                            <span class="badge bg-warning">

                                {{ $item->status }}

                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">

                        No Port Data

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $ports->withQueryString()->links() }}

    </div>

</div>

@endsection