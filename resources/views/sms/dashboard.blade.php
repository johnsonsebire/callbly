@extends('layouts.master')
@php
use Illuminate\Support\Str;
@endphp 
@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Header Section -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <div class="symbol-label bg-light-primary text-primary fs-2 fw-bold">
                                        <i class="ki-outline ki-message-text-2 fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h2 class="text-gray-900 fs-2 fw-bold me-1">SMS Dashboard</h2>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-wallet fs-4 me-1"></i>
                                                SMS Credits: <span class="fw-bold text-primary ms-1">{{ number_format(auth()->user()->sms_credits) }}</span>
                                            </span>
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-tag fs-4 me-1"></i>
                                                Rate: <span class="fw-bold text-primary ms-1">{{ auth()->user()->currency->symbol }}{{ number_format(auth()->user()->getSmsRate(), 3) }}/SMS</span>
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-abstract-26 fs-4 me-1"></i>
                                                Tier: <span class="fw-bold text-primary ms-1">{{ auth()->user()->billingTier->name }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('sms.compose') }}" class="btn btn-sm btn-primary me-3">
                                            <i class="ki-outline ki-message-text-2 fs-2"></i>
                                            Compose SMS
                                        </a>
                                        <a href="{{ route('sms.credits') }}" class="btn btn-sm btn-light-primary">
                                            <i class="ki-outline ki-plus-square fs-2"></i>
                                            Buy Credits
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!-- Delivery Rate Card -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 mb-2">{{ number_format($deliveryRate ?? 0, 1) }}%</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Delivery Rate</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                                <div class="d-flex align-items-center flex-column mt-3 w-100">
                                    <div class="d-flex justify-content-between fw-bold fs-6 text-gray-600 w-100 mt-auto mb-2">
                                        <span>{{ number_format($deliveredCount ?? 0) }} Delivered</span>
                                        <span>{{ number_format($totalSent ?? 0) }} Total</span>
                                    </div>
                                    <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                        <div class="bg-success rounded h-8px" role="progressbar" 
                                            style="width: {{ $deliveryRate ?? 0 }}%" aria-valuenow="{{ $deliveryRate ?? 0 }}" 
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Stats Card -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 mb-2">{{ number_format($todayCount ?? 0) }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Messages Today</span>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fs-6 fw-semibold text-gray-600 me-2">Success Rate:</span>
                                    <span class="fs-6 fw-bold text-gray-800">{{ number_format($todaySuccessRate ?? 0, 1) }}%</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fs-6 fw-semibold text-gray-600 me-2">Credits Used:</span>
                                    <span class="fs-6 fw-bold text-gray-800">{{ number_format($todayCreditsUsed ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Campaigns Card -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 mb-2">{{ $activeCampaigns ?? 0 }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Active Campaigns</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end">
                                <a href="{{ route('sms.campaigns') }}" class="btn btn-light-primary">View All Campaigns</a>
                                @if($activeCampaigns > 0)
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="ki-outline ki-loading fs-7 me-1" id="processingIcon"></i>
                                            Processing in background
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sender IDs Card -->
                    <div class="col-xl-3">
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 mb-2">{{ $approvedSenderIds ?? 0 }}</span>
                                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Approved Sender IDs</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end">
                                <a href="{{ route('sms.sender-names') }}" class="btn btn-light-primary">Manage Sender IDs</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Quick Links -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!-- Recent Campaigns -->
                    <div class="col-xl-8">
                        <div class="card card-flush h-lg-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Recent Campaigns</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Last 5 SMS campaigns</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('sms.campaigns') }}" class="btn btn-sm btn-light-primary">View All</a>
                                </div>
                            </div>
                            <div class="card-body pt-5">
                                @if($campaigns->isEmpty())
                                    <div class="text-center py-10">
                                        <i class="ki-outline ki-message-text-2 fs-3tx text-gray-300 mb-5"></i>
                                        <p class="text-gray-600 mb-4">No campaigns found</p>
                                        <a href="{{ route('sms.compose') }}" class="btn btn-primary">Create Your First Campaign</a>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th>Campaign</th>
                                                    <th>Status</th>
                                                    <th>Recipients</th>
                                                    <th>Delivery Rate</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($campaigns as $campaign)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="symbol symbol-45px me-5">
                                                                    <span class="symbol-label bg-light-primary">
                                                                        <i class="ki-outline ki-message-text-2 fs-2 text-primary"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex justify-content-start flex-column">
                                                                    <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                                        {{ Str::limit($campaign->name, 30) }}
                                                                    </a>
                                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $campaign->sender_name }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $campaign->status === 'completed' ? 'success' : ($campaign->status === 'failed' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($campaign->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ number_format($campaign->recipients_count) }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress h-6px w-100 me-2 bg-light-success">
                                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                                        style="width: {{ $campaign->getSuccessRate() }}%" 
                                                                        aria-valuenow="{{ $campaign->getSuccessRate() }}" 
                                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                                <span class="text-muted fs-7 fw-bold">{{ number_format($campaign->getSuccessRate(), 1) }}%</span>
                                                            </div>
                                                        </td>
                                                        <td>{{ $campaign->created_at->format('M d, Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links and Status -->
                    <div class="col-xl-4">
                        <!-- Quick Actions -->
                        <div class="card card-flush mb-5">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Quick Actions</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Common tasks and actions</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-3">
                                    <a href="{{ route('sms.compose') }}" class="btn btn-primary">
                                        <i class="ki-outline ki-message-text-2 fs-2 me-2"></i>Compose New Message
                                    </a>
                                    <a href="{{ route('sms.templates.create') }}" class="btn btn-light-primary">
                                        <i class="ki-outline ki-document fs-2 me-2"></i>Create Template
                                    </a>
                                    <a href="{{ route('sms.sender-names') }}" class="btn btn-light-primary">
                                        <i class="ki-outline ki-badge fs-2 me-2"></i>Register Sender ID
                                    </a>
                                    <a href="{{ route('sms.credits') }}" class="btn btn-light-primary">
                                        <i class="ki-outline ki-plus-square fs-2 me-2"></i>Purchase Credits
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- SMS Templates -->
                        <div class="card card-flush">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">SMS Templates</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Your saved message templates</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('sms.templates') }}" class="btn btn-sm btn-light-primary">View All</a>
                                </div>
                            </div>
                            <div class="card-body pt-5">
                                @if($templates->isEmpty())
                                    <div class="text-center py-10">
                                        <i class="ki-outline ki-document fs-3tx text-gray-300 mb-5"></i>
                                        <p class="text-gray-600 mb-4">No templates found</p>
                                        <a href="{{ route('sms.templates.create') }}" class="btn btn-primary">Create Template</a>
                                    </div>
                                @else
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($templates as $template)
                                            <div class="d-flex align-items-center border border-gray-300 rounded p-3">
                                                <div class="flex-grow-1">
                                                    <span class="fw-bold d-block mb-1">{{ Str::limit($template->name, 30) }}</span>
                                                    <span class="text-muted fs-7">{{ Str::limit($template->content, 50) }}</span>
                                                </div>
                                                <a href="{{ route('sms.compose') }}?template={{ $template->id }}" 
                                                   class="btn btn-sm btn-icon btn-light-primary ms-2" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Use Template">
                                                    <i class="ki-outline ki-arrow-right fs-2"></i>
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