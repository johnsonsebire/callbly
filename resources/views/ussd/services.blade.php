@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Header Section -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <div class="symbol-label bg-light-primary text-primary fs-2 fw-bold">
                                        <i class="ki-outline ki-call fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h2 class="text-gray-900 fs-2 fw-bold me-1">USSD Services</h2>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-calendar fs-4 me-1"></i>
                                                {{ now()->format('F d, Y') }}
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-compass fs-4 me-1"></i>
                                                Manage your USSD services
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('ussd.analytics') }}" class="btn btn-sm btn-light-primary me-2">
                                            <i class="ki-outline ki-chart fs-3"></i>
                                            View Analytics
                                        </a>
                                        <a href="{{ route('ussd.create') }}" class="btn btn-sm btn-primary">
                                            <i class="ki-outline ki-plus-square fs-3"></i>
                                            Create New Service
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services List -->
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Your USSD Services</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">List of all your registered USSD services</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        @if($services->isEmpty())
                            <div class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ki-outline ki-call fs-3x text-gray-300 mb-5"></i>
                                    <span class="text-gray-600 fs-5 fw-semibold">You haven't created any USSD services yet</span>
                                    <span class="text-gray-400 fs-7 mb-5">Start by creating your first USSD service</span>
                                    <a href="{{ route('ussd.create') }}" class="btn btn-primary">Create Your First Service</a>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Service Name</th>
                                            <th class="min-w-120px">Shortcode</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-120px">Created Date</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $service)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-45px me-3">
                                                            <span class="symbol-label bg-light-primary">
                                                                <i class="ki-outline ki-call fs-2 text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <span class="text-dark fw-bold text-hover-primary fs-6">{{ $service->name }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-semibold d-block fs-6">{{ $service->shortcode }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-{{ $service->status === 'active' ? 'success' : 'warning' }} fs-7 fw-bold">
                                                        {{ ucfirst($service->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fw-semibold d-block fs-7">{{ $service->created_at->format('M d, Y') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('ussd.edit', $service->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ki-outline ki-pencil fs-2"></i>
                                                    </a>
                                                    <form action="{{ route('ussd.destroy', $service->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this service?')">
                                                            <i class="ki-outline ki-trash fs-2"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-6">
                                {{ $services->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection