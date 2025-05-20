<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">USSD Services</h5>
                    <a href="{{ route('ussd.create') }}" class="btn btn-primary">Create New Service</a>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    @if($services->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted mb-3">You haven't created any USSD services yet.</p>
                            <a href="{{ route('ussd.create') }}" class="btn btn-primary">Create Your First Service</a>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Shortcode</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->shortcode }}</td>
                                            <td>
                                                <span class="badge bg-{{ $service->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $service->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('ussd.edit', $service->id) }}" class="btn btn-sm btn-light-primary me-2">
                                                    Edit
                                                </a>
                                                <form action="{{ route('ussd.destroy', $service->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('Are you sure you want to delete this service?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $services->links() }}
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection