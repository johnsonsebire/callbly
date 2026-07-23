@extends('layouts.master')

@section('title', 'Analytics & Overview')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <!-- Overview Header -->
                <div class="card mb-5 mb-xl-8 border-0 shadow-sm" style="background: linear-gradient(135deg, #090d16 0%, #1e293b 100%);">
                    <div class="card-body p-6 sm:p-8 text-white">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-4">
                            <div>
                                <span class="badge badge-light-primary fw-bold px-3 py-1 text-uppercase tracking-wider mb-2">Platform Overview</span>
                                <h1 class="text-white font-extrabold fs-2x mb-1">Telephony & Call Analytics</h1>
                                <p class="text-gray-400 fs-6 mb-0">Real-time performance metrics across inbound and outbound channels.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm px-4">
                                    <i class="ki-outline ki-element-11 me-1"></i> Main Console
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call Metrics Row -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-6 col-xxl-6">
                        <div class="card card-flush h-md-100 border-0 shadow-sm">
                            <div class="card-header pt-6">
                                <div class="card-title flex-column">
                                    <h3 class="fw-extrabold text-gray-900 fs-3 mb-1">Inbound Calls</h3>
                                    <span class="text-gray-400 fs-7">Customer inbound traffic volume</span>
                                </div>
                                <div class="card-toolbar">
                                    <span class="badge badge-light-success fw-bold px-3 py-1">Today</span>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div class="d-flex flex-column align-items-center text-center py-4">
                                    <span class="fs-3hx font-extrabold text-primary mb-1" data-kt-countup="true" data-kt-countup-value="{{ $todayInboundCalls ?? 0 }}">0</span>
                                    <span class="fs-6 fw-semibold text-gray-500">Inbound calls received today</span>
                                    
                                    <div class="d-flex align-items-center mt-3 bg-light-success px-3 py-1.5 rounded-2">
                                        <span class="fw-bold fs-6 text-success me-2">+{{ $inboundGrowth ?? '0%' }}</span>
                                        <span class="fw-semibold fs-7 text-gray-600">vs. previous period</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-xxl-6">
                        <div class="card card-flush h-md-100 border-0 shadow-sm">
                            <div class="card-header pt-6">
                                <div class="card-title flex-column">
                                    <h3 class="fw-extrabold text-gray-900 fs-3 mb-1">Outbound Calls</h3>
                                    <span class="text-gray-400 fs-7">Agent outbound dispatch volume</span>
                                </div>
                                <div class="card-toolbar">
                                    <span class="badge badge-light-warning fw-bold px-3 py-1">Month</span>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div class="d-flex flex-column align-items-center text-center py-4">
                                    <span class="fs-3hx font-extrabold text-info mb-1" data-kt-countup="true" data-kt-countup-value="{{ $monthOutboundCalls ?? 0 }}">0</span>
                                    <span class="fs-6 fw-semibold text-gray-500">Outbound calls dispatched this month</span>
                                    
                                    <div class="d-flex align-items-center mt-3 bg-light-warning px-3 py-1.5 rounded-2">
                                        <span class="fw-bold fs-6 text-warning me-2">{{ $outboundGrowth ?? '0%' }}</span>
                                        <span class="fw-semibold fs-7 text-gray-600">vs. 30-day average</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Referral Table Row -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-xl-12">
                        <div class="card card-flush h-xl-100 border-0 shadow-sm">
                            <div class="card-header pt-6 pb-2">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Campaign & Channel Attribution</span>
                                    <span class="text-gray-400 mt-1 fs-7">Performance metrics by referral source</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3">
                                        <thead>
                                            <tr class="fs-7 fw-bold text-gray-500 border-bottom-0 uppercase">
                                                <th>Campaign / Source</th>
                                                <th class="text-end pe-8">Sessions</th>
                                                <th class="text-end pe-4">Conv. Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topReferrals ?? [] as $referral)
                                            <tr>
                                                <td>
                                                    <span class="text-gray-900 font-bold fs-6">{{ $referral->campaign }}</span>
                                                </td>
                                                <td class="pe-8 text-end">
                                                    <span class="text-gray-800 font-semibold fs-6">{{ number_format($referral->sessions) }}</span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <span class="badge badge-light-success font-bold fs-7">{{ number_format($referral->conversion_rate, 2) }}%</span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-6 text-gray-500 fs-7">
                                                    No referral analytics recorded yet.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Timeline Row -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-xxl-12">
                        <div class="card card-flush border-0 shadow-sm">
                            <div class="card-header pt-6">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Live Activity Logs</span>
                                    <span class="text-gray-400 mt-1 fs-7">Latest call, SMS, and USSD events</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                @forelse($recentActivities ?? [] as $activity)
                                <div class="d-flex flex-stack py-3">
                                    <div class="d-flex align-items-center me-4">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label fs-3 fw-semibold bg-light-{{ $activity->type === 'call' ? 'primary' : 'success' }} text-{{ $activity->type === 'call' ? 'primary' : 'success' }} rounded-circle">
                                                <i class="ki-outline {{ $activity->type === 'call' ? 'ki-call' : 'ki-message-text-2' }}"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-900 fs-6 font-bold">{{ $activity->title }}</span>
                                            <span class="text-gray-500 fs-7">{{ $activity->description }}</span>
                                        </div>
                                    </div>
                                    <span class="badge badge-light-secondary font-mono text-gray-500 fs-7">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                @if(!$loop->last)
                                    <div class="separator separator-dashed my-2"></div>
                                @endif
                                @empty
                                <div class="text-center text-gray-400 fs-6 py-6">
                                    No recent activity logs.
                                </div>
                                @endforelse
                            </div>
                        </div>
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
        document.querySelectorAll('[data-kt-countup="true"]').forEach(element => {
            if (typeof CountUp !== 'undefined') {
                new CountUp(element, 0, parseInt(element.getAttribute('data-kt-countup-value')), 0, 2.5, {
                    separator: ',',
                    useGrouping: true,
                    useEasing: true,
                }).start();
            }
        });
    });
</script>
@endpush