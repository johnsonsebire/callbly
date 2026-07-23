@extends('layouts.master')

@section('title', 'USSD Performance Telemetry')

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
                                        <i class="ki-outline ki-chart-line-up fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">USSD Performance Telemetry</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Session Analytics</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Track real-time session volume, active unique users, and menu navigation duration across all shortcodes.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('ussd.services') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-element-11 me-1 fs-5"></i> View Services
                                </a>
                                <a href="{{ route('ussd.create') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Create Service
                                </a>
                                <a href="{{ route('ussd.dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-phone me-1 fs-5"></i> USSD Console
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Analytics Section -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Service Performance Metrics</span>
                            <span class="text-gray-400 mt-1 fs-7">Real-time session throughput and customer engagement per shortcode</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if($analyticsData->isEmpty())
                            <div class="text-center py-12">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                    <i class="ki-outline ki-chart-line-up fs-3hx text-primary"></i>
                                </div>
                                <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Session Telemetry Available</h3>
                                <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Create and activate USSD shortcodes to start capturing live session traffic and user interactions.</p>
                                <a href="{{ route('ussd.services') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                    <i class="ki-outline ki-element-11 fs-4 me-2"></i>View USSD Services
                                </a>
                            </div>
                        @else
                            <div class="row g-5">
                                @foreach($analyticsData as $data)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card border border-gray-200 shadow-xs h-100 p-5 overflow-hidden" style="border-radius: 1rem; border-top: 3px solid #0284c7 !important;">
                                            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-gray-200">
                                                <div class="d-flex align-items-center me-2">
                                                    <div class="symbol symbol-35px me-3">
                                                        <span class="symbol-label bg-light-primary text-primary rounded-circle">
                                                            <i class="ki-outline ki-phone fs-4"></i>
                                                        </span>
                                                    </div>
                                                    <h4 class="font-extrabold text-gray-900 fs-6 mb-0">{{ $data['name'] }}</h4>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column gap-3.5">
                                                <!-- Total Sessions -->
                                                <div class="d-flex align-items-center justify-content-between p-3 bg-light-soft rounded-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-32px me-3">
                                                            <span class="symbol-label bg-light-primary text-primary rounded-circle">
                                                                <i class="ki-outline ki-ranking fs-4"></i>
                                                            </span>
                                                        </div>
                                                        <span class="text-gray-700 font-bold fs-7">Total Sessions</span>
                                                    </div>
                                                    <span class="font-extrabold fs-6 text-primary">{{ number_format($data['total_sessions']) }}</span>
                                                </div>
                                                
                                                <!-- Active Users -->
                                                <div class="d-flex align-items-center justify-content-between p-3 bg-light-soft rounded-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-32px me-3">
                                                            <span class="symbol-label bg-light-success text-success rounded-circle">
                                                                <i class="ki-outline ki-profile-user fs-4"></i>
                                                            </span>
                                                        </div>
                                                        <span class="text-gray-700 font-bold fs-7">Active Users</span>
                                                    </div>
                                                    <span class="font-extrabold fs-6 text-success">{{ number_format($data['active_users']) }}</span>
                                                </div>
                                                
                                                <!-- Avg Session Duration -->
                                                <div class="d-flex align-items-center justify-content-between p-3 bg-light-soft rounded-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-32px me-3">
                                                            <span class="symbol-label bg-light-info text-info rounded-circle">
                                                                <i class="ki-outline ki-timer fs-4"></i>
                                                            </span>
                                                        </div>
                                                        <span class="text-gray-700 font-bold fs-7">Avg. Duration</span>
                                                    </div>
                                                    <span class="font-extrabold fs-6 text-info">{{ $data['average_session_duration'] }}s</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection