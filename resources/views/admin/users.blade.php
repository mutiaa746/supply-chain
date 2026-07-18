@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">👤 Manage Users</h1>
        <a href="/admin/users/create" class="btn btn-primary mb-3">+ Add User</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
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
                            <tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                    @if($user->email != 'admin@example.com')
                                    <form action="/admin/users/{{ $user->id }}" method="POST" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                    @endif
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