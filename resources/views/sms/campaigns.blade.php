@extends('layouts.master')

@php 
use Illuminate\Support\Str;
@endphp 

@section('title', 'SMS Campaigns History')

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
                                        <i class="ki-outline ki-element-11 fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">SMS Campaign History</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Broadcasts</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Monitor real-time delivery logs, campaign statuses, and recipient dispatches.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> New Campaign
                                </a>
                                <a href="{{ route('sms.dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-element-11 me-1 fs-5"></i> SMS Overview
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Campaigns List Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Campaign Dispatches</span>
                            <span class="text-gray-400 mt-1 fs-7">Complete record of your broadcast SMS campaigns</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                                <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if($campaigns->isEmpty())
                            <div class="text-center py-12">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                    <i class="ki-outline ki-message-text-2 fs-3hx text-primary"></i>
                                </div>
                                <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Campaigns Found</h3>
                                <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">You haven't dispatched any SMS campaigns yet. Start broadcasting to your target audience in minutes.</p>
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                    <i class="ki-outline ki-plus fs-4 me-2"></i>Send Your First Campaign
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                            <th class="min-w-80px">ID</th>
                                            <th class="min-w-200px">Campaign & Message</th>
                                            <th class="min-w-100px">Sender ID</th>
                                            <th class="min-w-100px">Recipients</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-130px">Timestamp</th>
                                            <th class="min-w-110px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($campaigns as $campaign)
                                            @php
                                                $status = strtolower($campaign->status);
                                                $badgeClass = match($status) {
                                                    'sent', 'completed' => 'badge-light-success',
                                                    'failed' => 'badge-light-danger',
                                                    'processing', 'queued', 'pending' => 'badge-light-warning',
                                                    default => 'badge-light-primary'
                                                };
                                            @endphp
                                            <tr>
                                                <td>
                                                    <span class="badge badge-light-secondary font-mono text-gray-700 font-bold fs-8 px-2 py-1">
                                                        #{{ $campaign->id }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <span class="symbol-label bg-light-primary rounded-circle">
                                                                <i class="ki-outline ki-message-text-2 fs-3 text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="text-gray-900 font-bold text-hover-primary mb-0 fs-6">
                                                                {{ Str::limit($campaign->name, 32) }}
                                                            </a>
                                                            <span class="text-gray-500 font-medium fs-8">{{ Str::limit($campaign->message, 55) }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-secondary font-bold text-gray-800 fs-8 px-2.5 py-1">
                                                        {{ $campaign->sender_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-900 font-bold fs-7">{{ number_format($campaign->recipients_count) }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $badgeClass }} fs-8 font-bold px-2.5 py-1">
                                                        {{ ucfirst($campaign->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-gray-800 font-bold fs-7">{{ $campaign->created_at->format('M d, Y') }}</span>
                                                        <span class="text-gray-400 font-medium fs-8">{{ $campaign->created_at->format('h:i A') }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="btn btn-sm btn-light-primary font-bold shadow-xs">
                                                        View Details &rarr;
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-6">
                                {{ $campaigns->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection