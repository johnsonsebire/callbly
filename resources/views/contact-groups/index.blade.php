@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Contact Groups</h5>
            <a href="{{ route('contact-groups.create') }}" class="btn btn-sm btn-primary float-end">Create Group</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <div class="mb-3">
                <form action="{{ route('contact-groups.index') }}" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search groups..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                            @if(request('search'))
                                <a href="{{ route('contact-groups.index') }}" class="btn btn-outline-secondary">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Contacts</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $group)
                            <tr>
                                <td>
                                    <a href="{{ route('contact-groups.show', $group) }}">{{ $group->name }}</a>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($group->description, 50) ?: 'No description' }}</td>
                                <td>{{ $group->contacts_count }}</td>
                                <td>{{ $group->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('contact-groups.show', $group) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('contact-groups.edit', $group) }}" class="btn btn-sm btn-secondary">Edit</a>
                                        <form action="{{ route('contact-groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this group?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No contact groups found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $groups->links() }}
        </div>
    </div>
</div>
@endsection