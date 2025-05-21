@extends('layouts.master')

@section('title', 'USSD Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-2">USSD Services Dashboard</h2>
                    <div class="text-muted">Manage your USSD services and monitor their performance</div>
                </div>
                <div>
                    <a href="{{ route('ussd.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Create New Service
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-grid-3x3-gap fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted mb-1">Total Services</div>
                            <h3 class="mb-0">{{ $servicesCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-people fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted mb-1">Available Credits</div>
                            <h3 class="mb-0">{{ number_format(auth()->user()->ussd_credits) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-phone fs-4 text-info"></i>
                        </div>
                        <div>
                            <div class="text-muted mb-1">Status</div>
                            <h3 class="mb-0">{{ $servicesCount > 0 ? 'Active' : 'No Services' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <a href="{{ route('ussd.services') }}" class="text-decoration-none">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-list-check fs-1 text-primary mb-3"></i>
                                        <h5>Manage Services</h5>
                                        <p class="text-muted mb-0">View and manage your USSD services</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('ussd.create') }}" class="text-decoration-none">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-plus-circle fs-1 text-success mb-3"></i>
                                        <h5>Create New Service</h5>
                                        <p class="text-muted mb-0">Set up a new USSD service</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('ussd.analytics') }}" class="text-decoration-none">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="bi bi-graph-up fs-1 text-info mb-3"></i>
                                        <h5>Analytics</h5>
                                        <p class="text-muted mb-0">View service performance metrics</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($servicesCount > 0)
        <!-- Recent Services -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Your Services</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Shortcode</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\UssdService::where('user_id', auth()->id())->latest()->take(5)->get() as $service)
                            <tr>
                                <td>{{ $service->name }}</td>
                                <td><code>{{ $service->shortcode }}</code></td>
                                <td>
                                    <span class="badge bg-{{ $service->status === 'active' ? 'success' : ($service->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($service->status) }}
                                    </span>
                                </td>
                                <td>{{ $service->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('ussd.edit', $service->id) }}" class="btn btn-sm btn-light me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('ussd.destroy', $service->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" onclick="return confirm('Are you sure you want to delete this service?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('ussd.services') }}" class="btn btn-primary">View All Services</a>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <img src="{{ asset('img/empty-state.svg') }}" alt="No services" class="img-fluid mb-4" style="max-height: 200px;">
                <h4>No USSD Services Yet</h4>
                <p class="text-muted mb-4">Get started by creating your first USSD service</p>
                <a href="{{ route('ussd.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Create Your First Service
                </a>
            </div>
        </div>
    @endif
</div>
@endsection