@extends('layouts.master')

@section('title', 'USSD Services Console')

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
                                        <h1 class="text-white font-extrabold fs-2x mb-0">USSD Services Console</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Interactive Menus</span>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 text-gray-400 fs-7 mt-2">
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-wallet fs-5 me-1 text-primary"></i>
                                            USSD Credits: <strong class="text-white ms-1 me-2">{{ number_format(auth()->user()->ussd_credits) }}</strong>
                                        </span>
                                        <span>•</span>
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-element-11 fs-5 me-1 text-success"></i>
                                            Services: <strong class="text-white ms-1">{{ $servicesCount }} Active</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('ussd.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Create Service
                                </a>
                                <a href="{{ route('ussd.services') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-element-11 me-1 fs-5"></i> All Services
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @push('styles')
                <style>
                    .stat-card {
                        background: #ffffff;
                        border-radius: 1.25rem !important;
                        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                        position: relative;
                        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04) !important;
                        border: 1px solid rgba(0, 0, 0, 0.04) !important;
                    }
                    
                    [data-bs-theme="dark"] .stat-card {
                        background: #1e1e2d !important;
                        border-color: rgba(255, 255, 255, 0.06) !important;
                        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.25) !important;
                    }
                    
                    .stat-card:hover {
                        transform: translateY(-4px) !important;
                        box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.08) !important;
                    }

                    .quick-action-card {
                        background: #ffffff;
                        border-radius: 1.25rem !important;
                        border: 1px solid rgba(0, 0, 0, 0.06) !important;
                        transition: all 0.25s ease-in-out !important;
                    }

                    .quick-action-card:hover {
                        transform: translateY(-3px) !important;
                        border-color: #0284c7 !important;
                        box-shadow: 0 10px 24px -4px rgba(2, 132, 199, 0.12) !important;
                    }
                </style>
                @endpush

                <!-- Stat Cards Grid -->
                <div class="row g-5 g-xl-6 mb-6">
                    <!-- Total Services -->
                    <div class="col-md-4">
                        <div class="card stat-card h-100 p-6 overflow-hidden" style="border-top: 4px solid #0284c7 !important;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider d-block mb-1">Configured Services</span>
                                    <h3 class="fs-2hx font-extrabold text-gray-900 mb-0">{{ number_format($servicesCount) }}</h3>
                                </div>
                                <div class="symbol symbol-50px rounded-2xl bg-light-primary d-flex align-items-center justify-content-center">
                                    <i class="ki-outline ki-phone text-primary fs-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Credits -->
                    <div class="col-md-4">
                        <div class="card stat-card h-100 p-6 overflow-hidden" style="border-top: 4px solid #10b981 !important;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider d-block mb-1">USSD Credits Balance</span>
                                    <h3 class="fs-2hx font-extrabold text-gray-900 mb-0">{{ number_format(auth()->user()->ussd_credits) }}</h3>
                                </div>
                                <div class="symbol symbol-50px rounded-2xl bg-light-success d-flex align-items-center justify-content-center">
                                    <i class="ki-outline ki-wallet text-success fs-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <div class="card stat-card h-100 p-6 overflow-hidden" style="border-top: 4px solid #06b6d4 !important;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider d-block mb-1">System Gateway Status</span>
                                    <h3 class="fs-2hx font-extrabold text-gray-900 mb-0">{{ $servicesCount > 0 ? 'Active' : 'Standby' }}</h3>
                                </div>
                                <div class="symbol symbol-50px rounded-2xl bg-light-info d-flex align-items-center justify-content-center">
                                    <i class="ki-outline ki-status text-info fs-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Grid -->
                <div class="row g-4 mb-6">
                    <div class="col-md-4">
                        <a href="{{ route('ussd.services') }}" class="text-decoration-none">
                            <div class="card quick-action-card p-6 h-100 text-center">
                                <div class="symbol symbol-50px symbol-circle bg-light-primary mx-auto mb-3 p-2">
                                    <i class="ki-outline ki-element-11 fs-2x text-primary"></i>
                                </div>
                                <h4 class="font-extrabold text-gray-900 fs-5 mb-1">Manage Services</h4>
                                <p class="text-gray-500 fs-7 mb-0">View, edit, and configure your live USSD menu trees.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('ussd.create') }}" class="text-decoration-none">
                            <div class="card quick-action-card p-6 h-100 text-center">
                                <div class="symbol symbol-50px symbol-circle bg-light-success mx-auto mb-3 p-2">
                                    <i class="ki-outline ki-plus-circle fs-2x text-success"></i>
                                </div>
                                <h4 class="font-extrabold text-gray-900 fs-5 mb-1">Create New Service</h4>
                                <p class="text-gray-500 fs-7 mb-0">Build interactive USSD shortcode menus step by step.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('ussd.analytics') }}" class="text-decoration-none">
                            <div class="card quick-action-card p-6 h-100 text-center">
                                <div class="symbol symbol-50px symbol-circle bg-light-info mx-auto mb-3 p-2">
                                    <i class="ki-outline ki-chart-line-up fs-2x text-info"></i>
                                </div>
                                <h4 class="font-extrabold text-gray-900 fs-5 mb-1">Analytics & Logs</h4>
                                <p class="text-gray-500 fs-7 mb-0">Monitor live session metrics and shortcode usage traffic.</p>
                            </div>
                        </a>
                    </div>
                </div>

                @if($servicesCount > 0)
                    <!-- Recent Services Card -->
                    <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                        <div class="card-header pt-6 pb-2 border-0">
                            <h3 class="card-title flex-column">
                                <span class="card-label font-bold text-gray-900 fs-3">Configured USSD Shortcodes</span>
                                <span class="text-gray-400 mt-1 fs-7">Active shortcodes and interactive gateway services</span>
                            </h3>
                        </div>
                        <div class="card-body pt-4">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                            <th class="min-w-180px">Service Name</th>
                                            <th class="min-w-130px">Shortcode</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-130px">Created Date</th>
                                            <th class="min-w-120px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\UssdService::where('user_id', auth()->id())->latest()->take(5)->get() as $service)
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
                            <div class="text-end mt-4">
                                <a href="{{ route('ussd.services') }}" class="btn btn-sm btn-primary font-bold shadow-xs px-4">
                                    <i class="ki-outline ki-element-11 me-1 fs-5"></i> View All Services &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-radius: 1.25rem;">
                        <div class="card-body text-center py-12">
                            <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                <i class="ki-outline ki-phone fs-3hx text-primary"></i>
                            </div>
                            <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No USSD Services Configured</h3>
                            <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Build interactive mobile shortcode menus to let customers query balances, register accounts, or purchase services.</p>
                            <a href="{{ route('ussd.create') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                <i class="ki-outline ki-plus fs-4 me-2"></i>Create Your First Service
                            </a>
                        </div>
                    </div>
                @endif

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