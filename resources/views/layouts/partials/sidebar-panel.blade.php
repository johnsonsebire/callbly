<div class="card-title"><!--begin::Sidebar panel wrapper-->
<div class="d-flex flex-column flex-grow-1 hover-scroll-overlay-y gap-8 gap-lg-12 ps-6 pe-4 py-3 mx-2 my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto">
    <!--begin::Statistics-->
    <div class="d-flex flex-stack">
        <div class="d-flex flex-column gap-1">
            <h3 class="fs-3 text-gray-900 fw-bold m-0">Total Stats</h3>
            <span class="fs-7 text-gray-600">Stats for all campaigns</span>
        </div><div class="card-title"></div><div class="card-title"></div>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-icon btn-info">
            <i class="ki-outline ki-black-right fs-1"></i>
        </a>
    </div><div class="card-title"></div><div class="card-title"></div>

    <!--begin::Overview cards-->
    <div class="d-flex flex-column gap-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="fs-5 fw-bold mb-0">SMS Overview</h4>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Total SMS Sent</span>
                        <span class="fw-bold">{{ number_format($totalSmsSent ?? 0) }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">SMS Credits</span>
                        <span class="fw-bold">{{ number_format($smsCredits ?? 0) }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Active Campaigns</span>
                        <span class="fw-bold">{{ number_format($activeCampaigns ?? 0) }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="fs-5 fw-bold mb-0">Contact Center Overview</h4>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Total Calls</span>
                        <span class="fw-bold">{{ number_format($totalCalls ?? 0) }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Active Numbers</span>
                        <span class="fw-bold">{{ number_format($activeNumbers ?? 0) }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Call Minutes</span>
                        <span class="fw-bold">{{ number_format($callMinutes ?? 0) }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="fs-5 fw-bold mb-0">Account Overview</h4>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="d-flex flex-column gap-1">
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Account Balance</span>
                        <span class="fw-bold">{{ $currency ?? '$' }}{{ number_format($accountBalance ?? 0, 2) }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Current Plan</span>
                        <span class="fw-bold">{{ $currentPlan ?? 'Free' }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex justify-content-between">
                        <span class="text-gray-600">Next Billing</span>
                        <span class="fw-bold">{{ $nextBilling ?? 'N/A' }}</span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
<!--end::Sidebar panel wrapper-->