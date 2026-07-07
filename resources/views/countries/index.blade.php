@extends('layouts.app')

@section('content')

<h2 class="mb-4">

    Countries

</h2>

<div class="card shadow">

    <div class="card-body">

        <form action="{{ url('/countries') }}" method="GET">

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

                    <th width="70">No</th>

                    <th>Country</th>

                    <th>Code</th>

                    <th>Capital</th>

                    <th>Region</th>

                    <th>Currency</th>

                </tr>

            </thead>

            <tbody>

            @forelse($countries as $country)

                <tr>

                    <td>

                        {{ $countries->firstItem() + $loop->index }}

                    </td>

                    <td>

                        {{ $country->country_name }}

                    </td>

                    <td>

                        {{ $country->country_code }}

                    </td>

                    <td>

                        {{ $country->capital }}

                    </td>

                    <td>

                        {{ $country->region }}

                    </td>

                    <td>

                        {{ $country->currency }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">

                        No Data Found

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $countries->withQueryString()->links() }}

    </div>

</div>

@endsection