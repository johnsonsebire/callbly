@extends('layouts.master')

@section('title', 'USSD Shortcode Services')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <!-- Welcome Banner Hero Card -->
                <div class="card mb-5 mb-xl-8 border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e293b 100%);">
                    <div class="card-body p-6 sm:p-8 text-white relative">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-6">
                            <div class="d-flex align-items-center gap-4">
                                <div class="symbol symbol-60px symbol-lg-75px symbol-circle border border-2 border-cyan-400/40 p-1 shadow-inner" style="background: rgba(14, 165, 233, 0.15);">
                                    <div class="symbol-label font-extrabold text-cyan-400 fs-1 rounded-circle" style="background: #0ea5e9; color: #ffffff;">
                                        <i class="ki-outline ki-phone fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">USSD Shortcode Services</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Interactive Gateways</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Manage registered mobile shortcodes, interactive menu handlers, and live gateway statuses.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('ussd.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Create Service
                                </a>
                                <a href="{{ route('ussd.analytics') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-chart-line-up me-1 fs-5"></i> View Analytics
                                </a>
                                <a href="{{ route('ussd.dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-element-11 me-1 fs-5"></i> USSD Console
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Services Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Registered USSD Shortcodes</span>
                            <span class="text-gray-400 mt-1 fs-7">Active mobile shortcodes connected to your gateway handlers</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if($services->isEmpty())
                            <div class="text-center py-12">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                    <i class="ki-outline ki-phone fs-3hx text-primary"></i>
                                </div>
                                <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Services Configured</h3>
                                <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Build interactive mobile shortcode menus to allow customers to check balances, pay bills, or access portal services.</p>
                                <a href="{{ route('ussd.create') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                    <i class="ki-outline ki-plus fs-4 me-2"></i>Create Your First Service
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                            <th class="min-w-180px">Service Name</th>
                                            <th class="min-w-140px">Shortcode Header</th>
                                            <th class="min-w-110px">Gateway Status</th>
                                            <th class="min-w-130px">Created Date</th>
                                            <th class="min-w-120px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $service)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <span class="symbol-label bg-light-primary text-primary rounded-circle">
                                                                <i class="ki-outline ki-phone fs-3"></i>
                                                            </span>
                                                        </div>
                                                        <span class="font-bold text-gray-900 fs-6">{{ $service->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="font-mono text-gray-800 font-bold fs-7 px-2.5 py-1 bg-light-soft rounded-2">
                                                        {{ $service->shortcode }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $badgeClass = match(strtolower($service->status)) {
                                                            'active', 'approved' => 'badge-light-success',
                                                            'pending' => 'badge-light-warning',
                                                            default => 'badge-light-danger'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} font-bold fs-8 px-2.5 py-1 rounded-pill">
                                                        {{ ucfirst($service->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-800 font-semibold fs-7">{{ $service->created_at->format('M d, Y') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                                        <a href="{{ route('ussd.edit', $service->id) }}" 
                                                           class="btn btn-sm btn-icon btn-light-primary rounded-circle" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-placement="top" 
                                                           title="Edit Service">
                                                            <i class="ki-outline ki-pencil fs-4"></i>
                                                        </a>
                                                        <form action="{{ route('ussd.destroy', $service->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-icon btn-light-danger rounded-circle" 
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-placement="top" 
                                                                    title="Delete Service">
                                                                <i class="ki-outline ki-trash fs-4"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-6">
                                {{ $services->links() }}
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
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush