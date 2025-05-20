@extends('layouts.master')
@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Welcome Section -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <div class="symbol-label bg-light-primary text-primary fs-2 fw-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h2 class="text-gray-900 fs-2 fw-bold me-1">Welcome back, {{ Auth::user()->name }}!</h2>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-calendar fs-4 me-1"></i>
                                                {{ now()->format('F d, Y') }}
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-compass fs-4 me-1"></i>
                                                Here's an overview of your account
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('sms.compose') }}" class="btn btn-sm btn-primary me-2">
                                            <i class="ki-outline ki-message-text-2 fs-3"></i>
                                            Send SMS
                                        </a>
                                        <a href="{{ route('sms.credits') }}" class="btn btn-sm btn-light-primary">
                                            <i class="ki-outline ki-plus-square fs-3"></i>
                                            Buy Credits
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-xl-3">
                        <!--begin::Stats Widget-->
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-primary me-2 lh-1">{{ $smsBalance ?? 0 }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">SMS Credits</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end pe-0">
                                <div class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-message-text-2 text-primary fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Stats Widget-->
                    </div>
                    
                    <div class="col-xl-3">
                        <!--begin::Stats Widget-->
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-success me-2 lh-1">{{ $ussdBalance ?? 0 }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">USSD Credits</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end pe-0">
                                <div class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-call text-success fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Stats Widget-->
                    </div>
                    
                    <div class="col-xl-3">
                        <!--begin::Stats Widget-->
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-info me-2 lh-1">{{ $activeContacts ?? 0 }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Active Contacts</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end pe-0">
                                <div class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-outline ki-people text-info fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Stats Widget-->
                    </div>
                    
                    <div class="col-xl-3">
                        <!--begin::Stats Widget-->
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-warning me-2 lh-1">{{ $balance }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Wallet Balance</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end pe-0">
                                <div class="symbol symbol-50px me-2">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-wallet text-warning fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Stats Widget-->
                    </div>
                </div>

                <!-- Recent Activity and Quick Actions -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-xl-8">
                        <!--begin::Recent Activities-->
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">Recent Activities</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Your latest message history</span>
                                </h3>
                                <div class="card-toolbar">
                                    <i class="ki-outline ki-dots-square fs-2 text-gray-300"></i>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th class="min-w-100px">Type</th>
                                                <th class="min-w-150px">Recipient</th>
                                                <th class="min-w-120px">Date</th>
                                                <th class="min-w-100px text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentActivities ?? [] as $activity)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <span class="symbol-label bg-light-{{ $activity->type === 'sms' ? 'primary' : 'success' }}">
                                                                <i class="ki-outline {{ $activity->type === 'sms' ? 'ki-message-text-2' : 'ki-call' }} fs-2 text-{{ $activity->type === 'sms' ? 'primary' : 'success' }}"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <span class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ strtoupper($activity->type) }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-semibold d-block fs-7">{{ $activity->recipient }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fw-semibold d-block fs-7">{{ $activity->created_at->format('M d, Y') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge badge-light-{{ $activity->status === 'delivered' ? 'success' : 'warning' }} fs-7 fw-bold">{{ ucfirst($activity->status) }}</span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-10">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="ki-outline ki-message-text-2 fs-2tx text-gray-300 mb-5"></i>
                                                        <span class="text-gray-600 fs-5 fw-semibold">No recent activities found</span>
                                                        <span class="text-gray-400 fs-7">Your messaging history will appear here</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end::Recent Activities-->
                    </div>
                    
                    <div class="col-xl-4">
                        <!--begin::Quick Actions-->
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">Quick Actions</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Most used features</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                <div class="d-flex flex-column gap-3">
                                    <a href="{{ route('sms.compose') }}" class="btn btn-success btn-flex btn-flex-center w-100">
                                        <i class="ki-outline ki-message-text-2 fs-1 me-2"></i>
                                        <span class="fw-bold">Send SMS</span>
                                    </a>
                                    <a href="{{ route('sms.sender-names') }}" class="btn btn-light-primary btn-flex btn-flex-center w-100">
                                        <i class="ki-outline ki-abstract-26 fs-1 me-2"></i>
                                        <span class="fw-bold">Register Sender Name</span>
                                    </a>
                                    <a href="{{ route('sms.credits') }}" class="btn btn-light-success btn-flex btn-flex-center w-100">
                                        <i class="ki-outline ki-basket fs-1 me-2"></i>
                                        <span class="fw-bold">Purchase SMS Credits</span>
                                    </a>
                                </div>
                                
                                <!--begin::Help Card-->
                                <div class="position-relative bg-light-primary rounded p-7 mt-8">
                                    <div class="position-absolute opacity-50 bottom-0 start-0">
                                        <i class="ki-outline ki-support text-primary opacity-20 fs-big"></i>
                                    </div>
                                    
                                    <div class="text-center">
                                        <h3 class="fs-3 text-gray-800 fw-bold mb-3">Need Help?</h3>
                                        <div class="text-gray-600 mb-5">
                                            Our support team is available 24/7 to assist you with any questions
                                        </div>
                                        <a href="#" class="btn btn-sm btn-primary">Contact Support</a>
                                    </div>
                                </div>
                                <!--end::Help Card-->
                            </div>
                        </div>
                        <!--end::Quick Actions-->
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