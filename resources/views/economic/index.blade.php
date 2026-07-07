@extends('layouts.app')

@section('content')

<h2 class="mb-4">

    Economic Indicator

</h2>

<div class="card shadow">

    <div class="card-body">

        <form action="{{ url('/economic') }}" method="GET">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search Country..."
                        value="{{ $search }}">

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

                    <th>GDP</th>

                    <th>Inflation</th>

                    <th>Population</th>

                    <th>Exports</th>

                    <th>Imports</th>

                </tr>

            </thead>

            <tbody>

            @forelse($economics as $item)

                <tr>

                    <td>{{ $economics->firstItem() + $loop->index }}</td>

                    <td>{{ $item->country->country_name }}</td>

                    <td>{{ number_format($item->gdp,2) }}</td>

                    <td>{{ $item->inflation }} %</td>

                    <td>{{ number_format($item->population) }}</td>

                    <td>{{ number_format($item->exports,2) }}</td>

                    <td>{{ number_format($item->imports,2) }}</td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="text-center">

                        No Data

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $economics->withQueryString()->links() }}

    </div>

</div>

@endsection