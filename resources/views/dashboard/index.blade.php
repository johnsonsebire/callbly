<div class="card-title">@extends('layouts.master')

@section('content')
<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xxl-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>Inbound Calls</h2>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-toolbar">
                    <span class="badge badge-light-success">Today</span>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="card-body pt-3">
                <div class="d-flex flex-stack h-100">
                    <div class="w-100 d-flex flex-column flex-center">
                        <span class="fs-2hx fw-bold text-gray-800 mb-2" data-kt-countup="true" data-kt-countup-value="{{ $todayInboundCalls ?? 0 }}">0</span>
                        <span class="fs-6 fw-semibold text-gray-500">Recent customer inbound calls</span>
                        
                        <div class="d-flex align-items-center mt-3">
                            <span class="fw-bold fs-4 text-success me-2">+{{ $inboundGrowth ?? '0%' }}</span>
                            <span class="fw-semibold fs-7 text-gray-500">For past 24 hours</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
    <!--end::Col-->
    
    <!--begin::Col-->
    <div class="col-xxl-6">
        <div class="card card-flush h-md-100">
            <div class="card-header">
                <div class="card-title">
                    <h2>Outbound Calls</h2>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-toolbar">
                    <span class="badge badge-light-warning">Month</span>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="card-body pt-3">
                <div class="d-flex flex-stack h-100">
                    <div class="w-100 d-flex flex-column flex-center">
                        <span class="fs-2hx fw-bold text-gray-800 mb-2" data-kt-countup="true" data-kt-countup-value="{{ $monthOutboundCalls ?? 0 }}">0</span>
                        <span class="fs-6 fw-semibold text-gray-500">Recent customer outbound calls</span>
                        
                        <div class="d-flex align-items-center mt-3">
                            <span class="fw-bold fs-4 text-danger me-2">{{ $outboundGrowth ?? '0%' }}</span>
                            <span class="fw-semibold fs-7 text-gray-500">For past 30 days</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
    <!--end::Col-->
</div><div class="card-title"></div><div class="card-title"></div>
<!--end::Row-->

<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-xl-12">
        <div class="card card-flush h-xl-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800">Top Referral Sources</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Counted in Millions</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-light">PDF Report</a>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="card-body pt-6">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                        <thead>
                            <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                                <th>CAMPAIGN</th>
                                <th class="p-0 pb-3 min-w-150px text-end pe-12">SESSIONS</th>
                                <th class="p-0 pb-3 min-w-150px text-end pe-7">CONV. RATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topReferrals ?? [] as $referral)
                            <tr>
                                <td>
                                    <span class="text-gray-800 fw-bold fs-6 me-3">{{ $referral->campaign }}</span>
                                </td>
                                <td class="pe-12 text-end">
                                    <span class="text-gray-800 fw-bold fs-6">{{ number_format($referral->sessions) }}</span>
                                </td>
                                <td class="pe-7 text-end">
                                    <span class="text-gray-800 fw-bold fs-6">{{ number_format($referral->conversion_rate, 2) }}%</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
    <!--end::Col-->
</div><div class="card-title"></div><div class="card-title"></div>
<!--end::Row-->

<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <div class="col-xxl-12">
        <div class="card card-flush">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Recent Activities</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Latest call and SMS activities</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-outline ki-category fs-6"></i>
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">All Activities</a>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">Call Activities</a>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">SMS Activities</a>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="card-body pt-5">
                @forelse($recentActivities ?? [] as $activity)
                <div class="d-flex flex-stack">
                    <div class="symbol symbol-40px me-4">
                        <div class="symbol-label fs-2 fw-semibold bg-light-{{ $activity->type === 'call' ? 'primary' : 'success' }} text-{{ $activity->type === 'call' ? 'primary' : 'success' }}">
                            <i class="ki-outline {{ $activity->type === 'call' ? 'ki-call' : 'ki-message-text-2' }}"></i>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 fs-6 fw-bold">{{ $activity->title }}</span>
                            <span class="text-gray-500 fs-7">{{ $activity->description }}</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <span class="text-gray-500 fs-7">{{ $activity->created_at->diffForHumans() }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
                @if(!$loop->last)
                    <div class="separator separator-dashed my-4"></div><div class="card-title"></div><div class="card-title"></div>
                @endif
                @empty
                <div class="text-center text-gray-500 fs-6">
                    No recent activities
                </div><div class="card-title"></div><div class="card-title"></div>
                @endforelse
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
<!--end::Row-->
@endsection

@push('scripts')
<script>
    // Initialize counters
    document.querySelectorAll('[data-kt-countup="true"]').forEach(element => {
        new CountUp(element, 0, parseInt(element.getAttribute('data-kt-countup-value')), 0, 2.5, {
            separator: ',',
            useGrouping: true,
            useEasing: true,
        }).start();
    });
</script>
@endpush