@extends('layouts.master')

@section('title', 'Contact Center Operations')

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
                                        <i class="ki-outline ki-call fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Contact Center Operations</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Voice Telephony</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Manage voice calls, monitor live telemetry, credit usage, and call logs.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary btn-sm px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_call">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> New Call
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-home me-1 fs-5"></i> Dashboard
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
                </style>
                @endpush

                <!-- Stat Cards Grid -->
                <div class="row g-5 g-xl-6 mb-6">
                    <!-- Today's Calls -->
                    <div class="col-xl-4">
                        <div class="card stat-card h-100 p-6 overflow-hidden" style="border-top: 4px solid #0284c7 !important;">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider d-block mb-1">Today's Call Volume</span>
                                    <h3 class="fs-2hx font-extrabold text-gray-900 mb-0">{{ number_format($todayCalls) }}</h3>
                                </div>
                                <div class="symbol symbol-50px rounded-2xl bg-light-primary d-flex align-items-center justify-content-center">
                                    <i class="ki-outline ki-call text-primary fs-2x"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top border-gray-200">
                                <span class="text-gray-600 font-semibold fs-7">Call Outcome Ratio</span>
                                <div class="d-flex gap-2">
                                    <span class="badge badge-light-success font-bold fs-8 px-2.5 py-1">
                                        {{ $successRate }}% Success
                                    </span>
                                    <span class="badge badge-light-danger font-bold fs-8 px-2.5 py-1">
                                        {{ $failureRate }}% Fail
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Credits -->
                    <div class="col-xl-4">
                        <div class="card stat-card h-100 p-6 overflow-hidden" style="border-top: 4px solid #10b981 !important;">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider d-block mb-1">Available Credits</span>
                                    <h3 class="fs-2hx font-extrabold text-gray-900 mb-0">{{ number_format($totalCredits) }}</h3>
                                </div>
                                <div class="symbol symbol-50px rounded-2xl bg-light-success d-flex align-items-center justify-content-center">
                                    <i class="ki-outline ki-wallet text-success fs-2x"></i>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-gray-600 font-semibold fs-8">Credit Usage Today</span>
                                    <span class="font-mono text-gray-900 font-bold fs-8">{{ $creditUsagePercent }}%</span>
                                </div>
                                <div class="progress h-6px bg-light-success rounded-pill">
                                    <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $creditUsagePercent }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Minutes Used -->
                    <div class="col-xl-4">
                        <div class="card stat-card h-100 p-6 overflow-hidden" style="border-top: 4px solid #06b6d4 !important;">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider d-block mb-1">Call Minutes Used</span>
                                    <h3 class="fs-2hx font-extrabold text-gray-900 mb-0">{{ number_format($totalMinutes) }} <span class="fs-6 font-normal text-gray-400">mins</span></h3>
                                </div>
                                <div class="symbol symbol-50px rounded-2xl bg-light-info d-flex align-items-center justify-content-center">
                                    <i class="ki-outline ki-timer text-info fs-2x"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top border-gray-200">
                                <span class="text-gray-600 font-semibold fs-7">Completed vs Failed</span>
                                <div class="d-flex gap-2">
                                    <span class="badge badge-light-success font-bold fs-8 px-2 py-0.5">{{ $successfulCalls }} Ok</span>
                                    <span class="badge badge-light-danger font-bold fs-8 px-2 py-0.5">{{ $failedCalls }} Failed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Calls Section -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <div class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Recent Call Activity</span>
                            <span class="text-gray-400 mt-1 fs-7">Log of inbound and outbound telephony interactions</span>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-2 position-absolute ms-4 text-gray-400"></i>
                                <input type="text" data-kt-call-table-filter="search" class="form-control form-control-solid ps-12 pe-4 rounded-3 w-250px" placeholder="Search Calls..." />
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed table-row-gray-200 fs-6 gy-4" id="kt_calls_table">
                                <thead>
                                    <tr class="text-start text-gray-400 font-bold fs-7 uppercase">
                                        <th class="min-w-140px">Caller</th>
                                        <th class="min-w-140px">Receiver</th>
                                        <th class="min-w-100px">Duration</th>
                                        <th class="min-w-110px">Status</th>
                                        <th class="min-w-130px">Date</th>
                                        <th class="min-w-70px text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @forelse($recentCalls as $call)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-35px me-3">
                                                    <span class="symbol-label bg-light-primary text-primary rounded-circle">
                                                        <i class="ki-outline ki-phone fs-4"></i>
                                                    </span>
                                                </div>
                                                <span class="font-mono text-gray-900 font-bold fs-7">{{ $call->caller_number }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-mono text-gray-800 font-semibold fs-7">{{ $call->receiver_number }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info font-bold fs-8 px-2.5 py-1">
                                                {{ $call->duration }} seconds
                                            </span>
                                        </td>
                                        <td>
                                            @if($call->status === 'completed')
                                                <span class="badge badge-light-success font-bold fs-8 px-2.5 py-1 rounded-pill">Completed</span>
                                            @elseif($call->status === 'failed')
                                                <span class="badge badge-light-danger font-bold fs-8 px-2.5 py-1 rounded-pill">Failed</span>
                                            @else
                                                <span class="badge badge-light-primary font-bold fs-8 px-2.5 py-1 rounded-pill">{{ ucfirst($call->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-gray-700 fs-7 font-medium">{{ $call->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-light font-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Actions
                                                <i class="ki-outline ki-down fs-5 ms-1"></i>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                @if($call->recording_url)
                                                <div class="menu-item px-3">
                                                    <a href="{{ $call->recording_url }}" class="menu-link px-3" target="_blank">
                                                        Listen Recording
                                                    </a>
                                                </div>
                                                @endif
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-call-id="{{ $call->id }}">
                                                        Call Details
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-12">
                                            <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                                <i class="ki-outline ki-call fs-3hx text-primary"></i>
                                            </div>
                                            <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Recent Calls</h3>
                                            <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-4">No call records found in your contact center activity log.</p>
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
    </div>
</div>

<!-- Modal - Add Call -->
<div class="modal fade" id="kt_modal_add_call" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <div class="modal-header border-0 pt-6 px-6">
                <h3 class="font-extrabold text-gray-900 fs-4 mb-0">Initiate Web Call</h3>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-2"></i>
                </button>
            </div>
            <div class="modal-body p-6 text-center">
                <div class="symbol symbol-80px symbol-circle bg-light-primary mx-auto mb-4 p-3">
                    <i class="ki-outline ki-call fs-3hx text-primary"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 mb-2 fs-4">Browser Calling Integration</h3>
                <p class="text-gray-500 fs-7 max-w-450px mx-auto mb-6">WebRTC browser calling is in private preview. For immediate enterprise sip trunking or voice setup, contact support at <a href="mailto:support@callbly.com" class="font-bold text-primary">support@callbly.com</a>.</p>
                <button type="button" class="btn btn-light font-bold px-6 py-2.5" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    "use strict";

    var KTCallsDashboard = function () {
        var initCallTable = function () {
            var table = $("#kt_calls_table").DataTable({
                "info": false,
                'order': [],
                "pageLength": 10,
                "lengthChange": false,
                'columnDefs': [
                    { orderable: false, targets: 5 },
                ]
            });

            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector('[data-kt-call-table-filter="search"]');
                if (filterSearch) {
                    filterSearch.addEventListener('keyup', function (e) {
                        table.search(e.target.value).draw();
                    });
                }
            }

            handleSearchDatatable();
        }

        return {
            init: function () {
                initCallTable();
            }
        }
    }();

    document.addEventListener("DOMContentLoaded", function() {
        KTCallsDashboard.init();
    });
</script>
@endpush