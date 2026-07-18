@extends('layouts.app')

@section('title', 'Manage Articles')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">📰 Manage Articles</h1>
        <a href="/admin/articles/create" class="btn btn-primary mb-3">+ Add Article</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>ID</th><th>Title</th><th>Author</th><th>Created</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->author }}</td>
                                <td>{{ $article->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="/admin/articles/{{ $article->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="/admin/articles/{{ $article->id }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
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