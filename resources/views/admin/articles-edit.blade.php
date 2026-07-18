@extends('layouts.app')

@section('title', 'Edit Article')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Edit Article</h5></div>
            <div class="card-body">
                <form method="POST" action="/admin/articles/{{ $article->id }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $article->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="5" required>{{ $article->content }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" value="{{ $article->author }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/admin/articles" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection