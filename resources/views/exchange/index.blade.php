@extends('layouts.app')

@section('content')

<h2 class="mb-4">

    Exchange Rate

</h2>

<div class="card shadow">

    <div class="card-body">

        <form action="{{ url('/exchange') }}" method="GET">

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

                    <th>Currency</th>

                    <th>Exchange Rate</th>

                    <th>Recorded At</th>

                </tr>

            </thead>

            <tbody>

            @forelse($exchangeRates as $item)

                <tr>

                    <td>{{ $exchangeRates->firstItem() + $loop->index }}</td>

                    <td>{{ $item->country->country_name }}</td>

                    <td>{{ $item->currency }}</td>

                    <td>{{ number_format($item->exchange_rate, 4) }}</td>

                    <td>{{ $item->recorded_at }}</td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="text-center">

                        No Data

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $exchangeRates->withQueryString()->links() }}

    </div>

</div>

@endsection