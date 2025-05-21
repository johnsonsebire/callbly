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
                                            <h2 class="text-gray-900 fs-2 fw-bold me-1">USSD Analytics</h2>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-calendar fs-4 me-1"></i>
                                                {{ now()->format('F d, Y') }}
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-compass fs-4 me-1"></i>
                                                Track your USSD service performance
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('ussd.services') }}" class="btn btn-sm btn-primary me-2">
                                            <i class="ki-outline ki-element-11 fs-3"></i>
                                            View All Services
                                        </a>
                                        <a href="{{ route('ussd.create') }}" class="btn btn-sm btn-light-primary">
                                            <i class="ki-outline ki-plus-square fs-3"></i>
                                            Create New Service
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Data -->
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">USSD Analytics</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Performance metrics for your USSD services</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        @if($analyticsData->isEmpty())
                            <div class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ki-outline ki-chart-simple fs-3x text-gray-300 mb-5"></i>
                                    <span class="text-gray-600 fs-5 fw-semibold">No analytics data available yet</span>
                                    <span class="text-gray-400 fs-7 mb-5">Create a USSD service to start tracking usage</span>
                                    <a href="{{ route('ussd.services') }}" class="btn btn-primary">View USSD Services</a>
                                </div>
                            </div>
                        @else
                            <div class="row g-5">
                                @foreach($analyticsData as $data)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card card-flush h-lg-100">
                                            <div class="card-header pt-5">
                                                <h3 class="card-title align-items-start flex-column">
                                                    <span class="card-label fw-bold text-dark">{{ $data['name'] }}</span>
                                                </h3>
                                            </div>
                                            <div class="card-body pt-5">
                                                <div class="d-flex flex-column gap-5">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-30px me-5">
                                                            <span class="symbol-label bg-light-primary">
                                                                <i class="ki-outline ki-ranking fs-3 text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-between flex-grow-1">
                                                            <span class="text-gray-800 fw-semibold fs-6">Total Sessions</span>
                                                            <span class="fw-bold fs-6 text-primary">{{ number_format($data['total_sessions']) }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-30px me-5">
                                                            <span class="symbol-label bg-light-success">
                                                                <i class="ki-outline ki-people fs-3 text-success"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-between flex-grow-1">
                                                            <span class="text-gray-800 fw-semibold fs-6">Active Users</span>
                                                            <span class="fw-bold fs-6 text-success">{{ number_format($data['active_users']) }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-30px me-5">
                                                            <span class="symbol-label bg-light-info">
                                                                <i class="ki-outline ki-timer fs-3 text-info"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-between flex-grow-1">
                                                            <span class="text-gray-800 fw-semibold fs-6">Avg. Session Duration</span>
                                                            <span class="fw-bold fs-6 text-info">{{ $data['average_session_duration'] }}s</span>
                                                        </div>
                                                    </div>
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
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection