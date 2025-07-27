@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <div class="card-title">
                            <h2 class="fw-bold">Service Plans Management</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.service-plans.create') }}" class="btn btn-sm btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>New Service Plan
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-success">Success</h4>
                                    <span>{{ session('success') }}</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-outline ki-cross fs-2 text-success"></i>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger">Error</h4>
                                    <span>{{ session('error') }}</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-outline ki-cross fs-2 text-danger"></i>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_service_plans">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">Name</th>
                                        <th class="min-w-100px">Type</th>
                                        <th class="min-w-100px">Price</th>
                                        <th class="min-w-100px">Validity</th>
                                        <th class="min-w-80px">Units</th>
                                        <th class="min-w-80px">Popular</th>
                                        <th class="min-w-80px">Status</th>
                                        <th class="text-end min-w-100px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    @forelse($plans as $plan)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('admin.service-plans.show', $plan) }}" class="text-gray-800 text-hover-primary mb-1">{{ $plan->name }}</a>
                                                    <span class="text-muted fs-7">{{ Str::limit($plan->description, 50) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-{{ $plan->type === 'sms' ? 'info' : ($plan->type === 'voice' ? 'success' : ($plan->type === 'contact-center' ? 'warning' : 'primary')) }}">
                                                    {{ ucwords(str_replace('-', ' ', $plan->type)) }}
                                                </span>
                                            </td>
                                            <td>{{ auth()->user()->formatAmount($plan->price) }}</td>
                                            <td>{{ $plan->validity_days }} days</td>
                                            <td>{{ number_format($plan->units) }}</td>
                                            <td>
                                                @if($plan->is_popular)
                                                    <span class="badge badge-light-warning">Popular</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($plan->is_active)
                                                    <span class="badge badge-light-success">Active</span>
                                                @else
                                                    <span class="badge badge-light-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    Actions
                                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                </a>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.service-plans.show', $plan) }}" class="menu-link px-3">View</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('admin.service-plans.edit', $plan) }}" class="menu-link px-3">Edit</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('admin.service-plans.toggle-status', $plan) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="menu-link px-3 border-0 bg-transparent w-100 text-start">
                                                                {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('admin.service-plans.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this service plan?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="menu-link px-3 border-0 bg-transparent w-100 text-start text-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-10">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="ki-duotone ki-shop fs-3x text-muted mb-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                    <h5 class="text-muted">No service plans found</h5>
                                                    <p class="text-muted">Create your first service plan to get started</p>
                                                    <a href="{{ route('admin.service-plans.create') }}" class="btn btn-primary">Create Service Plan</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($plans->hasPages())
                            <div class="d-flex flex-stack flex-wrap pt-10">
                                <div class="fs-6 fw-semibold text-gray-700">
                                    Showing {{ $plans->firstItem() }} to {{ $plans->lastItem() }} of {{ $plans->total() }} results
                                </div>
                                {{ $plans->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable if needed
    $('#kt_table_service_plans').DataTable({
        "pageLength": 15,
        "ordering": true,
        "searching": true,
        "lengthChange": false,
        "info": false,
        "paging": false
    });
});
</script>
@endpush
