@extends('layouts.master')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'SMS Telephony Dashboard')

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
                                        <i class="ki-outline ki-message-text-2 fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">SMS Telephony Console</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Live Engine</span>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 text-gray-400 fs-7 mt-2">
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-wallet fs-5 me-1 text-primary"></i>
                                            Credits: <strong class="text-white ms-1 me-2">{{ number_format(auth()->user()->sms_credits) }}</strong>
                                        </span>
                                        <span>•</span>
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-tag fs-5 me-1 text-success"></i>
                                            Rate: <strong class="text-white ms-1 me-2">{{ auth()->user()->currency->symbol }}{{ number_format(auth()->user()->getSmsRate(), 3) }}/SMS</strong>
                                        </span>
                                        <span>•</span>
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-badge fs-5 me-1 text-info"></i>
                                            Tier: <strong class="text-white ms-1">{{ auth()->user()->billingTier->name }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-message-text-2 me-1 fs-5"></i> Compose SMS
                                </a>
                                <a href="{{ route('sms.credits') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-plus-square me-1 fs-5"></i> Buy Credits
                                </a>
                                <a href="{{ route('sms.sender-names') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-shield-search me-1 fs-5"></i> Sender IDs
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

    [data-bs-theme="dark"] .stat-card:hover {
        box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.4) !important;
    }
    
    .stat-card .stat-arrow {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    
    .stat-card:hover .stat-arrow {
        transform: translateX(4px) !important;
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-card-icon {
        transform: scale(1.08);
    }
</style>
@endpush

                <!-- 4 Enhanced Modern Stats Cards Grid -->
                <div class="row g-5 g-xl-6 mb-5 mb-xl-10">
                    
                    <!-- Delivery Rate Card -->
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('sms.messages') }}" class="card stat-card h-100 text-decoration-none group overflow-hidden" style="border-top: 4px solid #10b981 !important;">
                            <div class="card-body p-6 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Header: Label & Icon -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider">Delivery Rate</span>
                                        <div class="stat-card-icon shadow-xs" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(52, 211, 153, 0.22) 100%);">
                                            <i class="ki-outline ki-double-check text-success fs-1"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Metric Value & Badge -->
                                    <div class="d-flex align-items-baseline gap-2 mb-2">
                                        <span class="fs-2hx font-extrabold text-gray-900 lh-1">{{ number_format($deliveryRate ?? 0, 1) }}%</span>
                                        <span class="badge badge-light-success fs-8 font-bold px-2.5 py-1 rounded-pill">Optimal</span>
                                    </div>
                                    
                                    <!-- Delivery Progress Bar -->
                                    <div class="w-100 mt-2 mb-1">
                                        <div class="d-flex justify-content-between fs-8 font-semibold text-gray-500 mb-1">
                                            <span>{{ number_format($deliveredCount ?? 0) }} Delivered</span>
                                            <span>{{ number_format($totalSent ?? 0) }} Total</span>
                                        </div>
                                        <div class="progress h-6px w-100 bg-light-success rounded">
                                            <div class="progress-bar bg-success rounded" role="progressbar" 
                                                style="width: {{ $deliveryRate ?? 0 }}%" aria-valuenow="{{ $deliveryRate ?? 0 }}" 
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer Action Link -->
                                <div class="pt-4 mt-3 border-top border-gray-100 d-flex align-items-center justify-content-between text-success font-bold fs-7">
                                    <span>View Message Logs</span>
                                    <i class="ki-outline ki-arrow-right fs-5 stat-arrow"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Messages Today Card -->
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('sms.compose') }}" class="card stat-card h-100 text-decoration-none group overflow-hidden" style="border-top: 4px solid #0284c7 !important;">
                            <div class="card-body p-6 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Header: Label & Icon -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider">Messages Today</span>
                                        <div class="stat-card-icon shadow-xs" style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.12) 0%, rgba(56, 189, 248, 0.22) 100%);">
                                            <i class="ki-outline ki-send text-primary fs-1"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Metric Value & Badge -->
                                    <div class="d-flex align-items-baseline gap-2 mb-2">
                                        <span class="fs-2hx font-extrabold text-gray-900 lh-1">{{ number_format($todayCount ?? 0) }}</span>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1 rounded-pill">Today</span>
                                    </div>
                                    
                                    <!-- Extra Details -->
                                    <div class="d-flex flex-column gap-1 text-gray-500 fs-7 font-medium">
                                        <div class="d-flex justify-content-between">
                                            <span>Success Rate:</span>
                                            <strong class="text-gray-800">{{ number_format($todaySuccessRate ?? 0, 1) }}%</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Credits Used:</span>
                                            <strong class="text-gray-800">{{ number_format($todayCreditsUsed ?? 0) }}</strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer Action Link -->
                                <div class="pt-4 mt-3 border-top border-gray-100 d-flex align-items-center justify-content-between text-primary font-bold fs-7">
                                    <span>Compose Message</span>
                                    <i class="ki-outline ki-arrow-right fs-5 stat-arrow"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Active Campaigns Card -->
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('sms.campaigns') }}" class="card stat-card h-100 text-decoration-none group overflow-hidden" style="border-top: 4px solid #06b6d4 !important;">
                            <div class="card-body p-6 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Header: Label & Icon -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider">Active Campaigns</span>
                                        <div class="stat-card-icon shadow-xs" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(34, 211, 238, 0.22) 100%);">
                                            <i class="ki-outline ki-element-11 text-info fs-1"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Metric Value & Badge -->
                                    <div class="d-flex align-items-baseline gap-2 mb-1">
                                        <span class="fs-2hx font-extrabold text-gray-900 lh-1">{{ $activeCampaigns ?? 0 }}</span>
                                        <span class="badge badge-light-info fs-8 font-bold px-2.5 py-1 rounded-pill">Active</span>
                                    </div>
                                    
                                    <span class="text-gray-400 fs-7 font-medium">
                                        @if(($activeCampaigns ?? 0) > 0)
                                            <i class="ki-outline ki-loading fs-7 me-1 text-info" id="processingIcon"></i> Dispatches processing live
                                        @else
                                            No active background queues
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Footer Action Link -->
                                <div class="pt-4 mt-3 border-top border-gray-100 d-flex align-items-center justify-content-between text-info font-bold fs-7">
                                    <span>Manage Campaigns</span>
                                    <i class="ki-outline ki-arrow-right fs-5 stat-arrow"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Sender IDs Card -->
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('sms.sender-names') }}" class="card stat-card h-100 text-decoration-none group overflow-hidden" style="border-top: 4px solid #f59e0b !important;">
                            <div class="card-body p-6 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Header: Label & Icon -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider">Sender IDs</span>
                                        <div class="stat-card-icon shadow-xs" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(251, 191, 36, 0.22) 100%);">
                                            <i class="ki-outline ki-shield-tick text-warning fs-1"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Metric Value & Badge -->
                                    <div class="d-flex align-items-baseline gap-2 mb-1">
                                        <span class="fs-2hx font-extrabold text-gray-900 lh-1">{{ $approvedSenderIds ?? 0 }}</span>
                                        <span class="badge badge-light-warning fs-8 font-bold px-2.5 py-1 rounded-pill">Approved</span>
                                    </div>
                                    <span class="text-gray-400 fs-7 font-medium">Registered brand Sender IDs</span>
                                </div>
                                
                                <!-- Footer Action Link -->
                                <div class="pt-4 mt-3 border-top border-gray-100 d-flex align-items-center justify-content-between text-warning font-bold fs-7">
                                    <span>Manage Sender IDs</span>
                                    <i class="ki-outline ki-arrow-right fs-5 stat-arrow"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

                <!-- Recent Activity and Quick Links Grid -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    
                    <!-- Recent Campaigns Table (8 Cols) -->
                    <div class="col-xl-8">
                        <div class="card card-flush h-lg-100 border-0 shadow-sm">
                            <div class="card-header pt-6 pb-2">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Recent SMS Dispatches</span>
                                    <span class="text-gray-400 mt-1 fs-7">Performance and delivery metrics of recent SMS broadcasts</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('sms.campaigns') }}" class="btn btn-sm btn-light-primary font-semibold">View All Campaigns &rarr;</a>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                @if($campaigns->isEmpty())
                                    <div class="text-center py-10">
                                        <i class="ki-outline ki-message-text-2 fs-3hx text-gray-300 mb-3"></i>
                                        <p class="text-gray-700 fs-5 font-semibold">No recent SMS dispatches</p>
                                        <p class="text-gray-400 fs-7 mb-4">Compose your first SMS campaign to start broadcasting.</p>
                                        <a href="{{ route('sms.compose') }}" class="btn btn-primary font-semibold">Compose First Campaign</a>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                                    <th class="min-w-150px">Campaign</th>
                                                    <th class="min-w-90px">Status</th>
                                                    <th class="min-w-100px">Recipients</th>
                                                    <th class="min-w-140px">Delivery Rate</th>
                                                    <th class="min-w-100px text-end">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($campaigns as $campaign)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="symbol symbol-40px me-3">
                                                                    <span class="symbol-label bg-light-primary rounded-circle">
                                                                        <i class="ki-outline ki-message-text-2 fs-3 text-primary"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex justify-content-start flex-column">
                                                                    <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="text-gray-900 font-bold text-hover-primary mb-0 fs-6">
                                                                        {{ Str::limit($campaign->name, 28) }}
                                                                    </a>
                                                                    <span class="text-gray-400 font-medium fs-8">Sender: {{ $campaign->sender_name }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $campaign->status === 'completed' ? 'success' : ($campaign->status === 'failed' ? 'danger' : 'warning') }} fs-8 font-bold px-2.5 py-1">
                                                                {{ ucfirst($campaign->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-900 font-bold fs-7">{{ number_format($campaign->recipients_count) }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center me-2">
                                                                <div class="progress h-6px w-100 me-2 bg-light-success rounded">
                                                                    <div class="progress-bar bg-success rounded" role="progressbar" 
                                                                        style="width: {{ $campaign->getSuccessRate() }}%" 
                                                                        aria-valuenow="{{ $campaign->getSuccessRate() }}" 
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                                <span class="text-gray-800 fs-8 font-bold">{{ number_format($campaign->getSuccessRate(), 1) }}%</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="text-gray-800 font-semibold fs-7">{{ $campaign->created_at->format('M d, Y') }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links & SMS Templates (4 Cols) -->
                    <div class="col-xl-4">
                        <!-- Quick Actions -->
                        <div class="card card-flush mb-5 border-0 shadow-sm">
                            <div class="card-header pt-6 pb-2">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">SMS Workflows</span>
                                    <span class="text-gray-400 mt-1 fs-7">Frequently used SMS tools</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <div class="d-flex flex-column gap-3">
                                    <a href="{{ route('sms.compose') }}" class="btn btn-primary btn-flex btn-flex-center w-100 py-3">
                                        <i class="ki-outline ki-message-text-2 fs-2 me-2"></i>
                                        <span class="font-bold">Compose New SMS</span>
                                    </a>
                                    <a href="{{ route('sms.templates.create') }}" class="btn btn-light-primary btn-flex btn-flex-center w-100 py-3">
                                        <i class="ki-outline ki-document fs-2 me-2"></i>
                                        <span class="font-bold">Create Template</span>
                                    </a>
                                    <a href="{{ route('sms.sender-names') }}" class="btn btn-light-primary btn-flex btn-flex-center w-100 py-3">
                                        <i class="ki-outline ki-shield-search fs-2 me-2"></i>
                                        <span class="font-bold">Register Sender ID</span>
                                    </a>
                                    <a href="{{ route('sms.credits') }}" class="btn btn-light-success btn-flex btn-flex-center w-100 py-3">
                                        <i class="ki-outline ki-plus-square fs-2 me-2"></i>
                                        <span class="font-bold">Buy SMS Credits</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- SMS Templates -->
                        <div class="card card-flush border-0 shadow-sm">
                            <div class="card-header pt-6 pb-2">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Saved Templates</span>
                                    <span class="text-gray-400 mt-1 fs-7">Reusable message copy</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('sms.templates') }}" class="btn btn-sm btn-light-primary font-semibold">View All</a>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                @if($templates->isEmpty())
                                    <div class="text-center py-6">
                                        <i class="ki-outline ki-document fs-2hx text-gray-300 mb-2"></i>
                                        <p class="text-gray-600 fs-7 mb-3">No templates saved yet.</p>
                                        <a href="{{ route('sms.templates.create') }}" class="btn btn-sm btn-light-primary font-semibold">Create Template</a>
                                    </div>
                                @else
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($templates as $template)
                                            <div class="d-flex align-items-center border border-gray-200 rounded-3 p-3 bg-light-soft hover-top transition-all">
                                                <div class="flex-grow-1 me-2">
                                                    <span class="font-bold text-gray-900 d-block fs-7 mb-0.5">{{ Str::limit($template->name, 26) }}</span>
                                                    <span class="text-gray-500 fs-8 font-medium d-block">{{ Str::limit($template->content, 45) }}</span>
                                                </div>
                                                <a href="{{ route('sms.compose') }}?template={{ $template->id }}" 
                                                   class="btn btn-sm btn-icon btn-light-primary ms-2 shadow-xs" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Use Template">
                                                    <i class="ki-outline ki-arrow-right fs-4"></i>
                                                </a>
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
    </div>
</div>
@endsection