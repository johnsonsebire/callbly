@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h2>SMS Templates</h2>
                <p class="text-muted">Create and manage your message templates</p>
            </div>
            <div>
                <a href="{{ route('sms.templates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Create Template
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <x-card title="Your Templates" icon="fas fa-file-alt">
                @if(session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if($templates->isEmpty())
                    <div class="text-center my-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h4>No templates yet</h4>
                        <p class="text-muted">Create templates to save time when sending frequent messages.</p>
                        <a href="{{ route('sms.templates.create') }}" class="btn btn-primary mt-3">Create Your First Template</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Content</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                    <tr>
                                        <td>{{ $template->name }}</td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;">
                                                {{ $template->content }}
                                            </div>
                                        </td>
                                        <td>{{ $template->description }}</td>
                                        <td>{{ $template->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('sms.compose') }}?template={{ $template->id }}" 
                                                   class="btn btn-sm btn-outline-primary me-2">
                                                    Use Template
                                                </a>
                                                <a href="{{ route('sms.templates.edit', $template->id) }}" 
                                                   class="btn btn-sm btn-outline-secondary me-2">
                                                    Edit
                                                </a>
                                                <form action="{{ route('sms.templates.delete', $template->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        {{ $templates->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</div>
@endsection