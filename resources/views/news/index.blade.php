@extends('layouts.app')

@section('content')

<h2 class="mb-4">
    News Monitoring
</h2>

<div class="card shadow">

    <div class="card-body">

        <form action="{{ url('/news') }}" method="GET">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search Country...">

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
                    <th>Title</th>
                    <th>Source</th>
                    <th>Sentiment</th>
                    <th>Published</th>

                </tr>

            </thead>

            <tbody>

            @forelse($news as $item)

                <tr>

                    <td>{{ $news->firstItem() + $loop->index }}</td>

                    <td>{{ $item->country->country_name }}</td>

                    <td>
                        <a href="{{ $item->url }}" target="_blank">
                            {{ $item->title }}
                        </a>
                    </td>

                    <td>{{ $item->source }}</td>

                    <td>

                        @if($item->sentiment=="positive")

                            <span class="badge bg-success">
                                Positive
                            </span>

                        @elseif($item->sentiment=="negative")

                            <span class="badge bg-danger">
                                Negative
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Neutral
                            </span>

                        @endif

                    </td>

                    <td>{{ $item->published_at }}</td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center">

                        No News Found

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

        {{ $news->withQueryString()->links() }}

    </div>

</div>

@endsection