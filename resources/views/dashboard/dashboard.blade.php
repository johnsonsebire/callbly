@extends('layouts.master')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Main Dashboard')

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
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-gray-400 fs-7">
                                        <span><i class="ki-outline ki-calendar fs-6 me-1 text-primary"></i> {{ now()->format('F d, Y') }}</span>
                                        <span>•</span>
                                        <span><i class="ki-outline ki-compass fs-6 me-1 text-primary"></i> Telephony Console Overview</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-message-text-2 me-1 fs-5"></i> Send SMS
                                </a>
                                <a href="{{ route('sms.credits') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-plus-square me-1 fs-5"></i> Buy Credits
                                </a>
                                <a href="{{ route('api.docs') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-code me-1 fs-5"></i> API Docs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Stats Cards Grid -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    
                    <!-- SMS Credits -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card card-flush h-lg-100 border-0 shadow-sm hover-top transition-all">
                            <div class="card-header pt-5 pb-2">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx font-extrabold text-primary me-2 lh-1">{{ number_format($smsBalance ?? 0) }}</span>
                                    <span class="text-gray-400 pt-1 font-medium fs-7">SMS Credits Available</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between pt-0 pb-4">
                                <a href="{{ route('sms.credits') }}" class="text-primary font-semibold fs-7 hover-underline">Top up &rarr;</a>
                                <div class="symbol symbol-45px">
                                    <span class="symbol-label bg-light-primary rounded-xl">
                                        <i class="ki-outline ki-message-text-2 text-primary fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- USSD Credits -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card card-flush h-lg-100 border-0 shadow-sm hover-top transition-all">
                            <div class="card-header pt-5 pb-2">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx font-extrabold text-success me-2 lh-1">{{ number_format($ussdBalance ?? 0) }}</span>
                                    <span class="text-gray-400 pt-1 font-medium fs-7">USSD Credits</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between pt-0 pb-4">
                                <a href="{{ route('ussd.dashboard') }}" class="text-success font-semibold fs-7 hover-underline">Manage &rarr;</a>
                                <div class="symbol symbol-45px">
                                    <span class="symbol-label bg-light-success rounded-xl">
                                        <i class="ki-outline ki-call text-success fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Contacts -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card card-flush h-lg-100 border-0 shadow-sm hover-top transition-all">
                            <div class="card-header pt-5 pb-2">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx font-extrabold text-info me-2 lh-1">{{ number_format($activeContacts ?? 0) }}</span>
                                    <span class="text-gray-400 pt-1 font-medium fs-7">Active Audience Contacts</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between pt-0 pb-4">
                                <a href="{{ route('contacts.index') }}" class="text-info font-semibold fs-7 hover-underline">Contacts &rarr;</a>
                                <div class="symbol symbol-45px">
                                    <span class="symbol-label bg-light-info rounded-xl">
                                        <i class="ki-outline ki-people text-info fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Wallet Balance -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="card card-flush h-lg-100 border-0 shadow-sm hover-top transition-all">
                            <div class="card-header pt-5 pb-2">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx font-extrabold text-warning me-2 lh-1">{{ $balance ?? '₵0.00' }}</span>
                                    <span class="text-gray-400 pt-1 font-medium fs-7">Prepaid Wallet Balance</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-between pt-0 pb-4">
                                <a href="{{ route('wallet.topup') }}" class="text-warning font-semibold fs-7 hover-underline">Add Funds &rarr;</a>
                                <div class="symbol symbol-45px">
                                    <span class="symbol-label bg-light-warning rounded-xl">
                                        <i class="ki-outline ki-wallet text-warning fs-2x"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Recent Activity & Quick Actions Grid -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    
                    <!-- Recent Activity Table (8 Cols) -->
                    <div class="col-xl-8">
                        <div class="card card-flush h-lg-100 border-0 shadow-sm">
                            <div class="card-header pt-6 pb-2">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Recent Messaging History</span>
                                    <span class="text-gray-400 mt-1 fs-7">Real-time dispatch and delivery logs</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('sms.messages') }}" class="btn btn-sm btn-light-primary font-semibold">View All Logs &rarr;</a>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                                <th class="min-w-100px">Channel</th>
                                                <th class="min-w-150px">Recipient / Message</th>
                                                <th class="min-w-120px">Timestamp</th>
                                                <th class="min-w-100px text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentActivities ?? [] as $activity)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <span class="symbol-label bg-light-{{ $activity->type === 'sms' ? 'primary' : 'success' }} rounded-circle">
                                                                <i class="ki-outline {{ $activity->type === 'sms' ? 'ki-message-text-2' : 'ki-call' }} fs-3 text-{{ $activity->type === 'sms' ? 'primary' : 'success' }}"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <span class="text-gray-900 font-bold mb-0 fs-6">{{ strtoupper($activity->type) }}</span>
                                                            <span class="text-gray-400 font-medium fs-8">ID #{{ $activity->campaign_id ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-gray-900 font-semibold d-block fs-7">{{ $activity->recipient }}</span>
                                                    <span class="text-gray-500 font-normal d-block fs-8">{{ Str::limit($activity->message, 32) }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-800 font-semibold d-block fs-7">{{ $activity->created_at->format('M d, Y') }}</span>
                                                    <span class="text-gray-400 font-normal d-block fs-8">{{ $activity->created_at->format('h:i A') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge badge-light-{{ $activity->status === 'delivered' ? 'success' : ($activity->status === 'failed' ? 'danger' : 'warning') }} fs-8 font-bold px-2.5 py-1">
                                                        {{ ucfirst($activity->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-10">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="ki-outline ki-message-text-2 fs-3hx text-gray-300 mb-3"></i>
                                                        <span class="text-gray-700 fs-5 font-semibold">No recent messaging activity</span>
                                                        <span class="text-gray-400 fs-7 mt-1">Send an SMS or dispatch a USSD campaign to view live logs.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Tools & Support (4 Cols) -->
                    <div class="col-xl-4">
                        <div class="card card-flush h-lg-100 border-0 shadow-sm">
                            <div class="card-header pt-6 pb-2">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Quick Workflows</span>
                                    <span class="text-gray-400 mt-1 fs-7">Frequently used platform tools</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <div class="d-flex flex-column gap-3 mb-6">
                                    <a href="{{ route('sms.compose') }}" class="btn btn-primary btn-flex btn-flex-center w-100 py-3">
                                        <i class="ki-outline ki-message-text-2 fs-2 me-2"></i>
                                        <span class="font-bold">Compose & Send SMS</span>
                                    </a>
                                    <a href="{{ route('sms.sender-names') }}" class="btn btn-light-primary btn-flex btn-flex-center w-100 py-3">
                                        <i class="ki-outline ki-shield-search fs-2 me-2"></i>
                                        <span class="font-bold">Register Brand Sender ID</span>
                                    </a>
                                    <a href="{{ route('wallet.topup') }}" class="btn btn-light-success btn-flex btn-flex-center w-100 py-3">
                                        <i class="ki-outline ki-wallet fs-2 me-2"></i>
                                        <span class="font-bold">Top-up Wallet Credits</span>
                                    </a>
                                </div>
                                
                                <!-- Support Help Card -->
                                <div class="rounded-3 p-6 text-center text-white" style="background: linear-gradient(135deg, #090d16 0%, #0369a1 100%);">
                                    <i class="ki-outline ki-support text-cyan-300 fs-3hx mb-3"></i>
                                    <h4 class="text-white font-extrabold fs-5 mb-2">Round the Clock Support</h4>
                                    <p class="text-gray-300 fs-7 mb-4">
                                        Our engineering NOC is available 24/7 for technical and API assistance.
                                    </p>
                                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#supportModal">
                                        Contact Support Team
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- Support Modal -->
<div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-slate-950 text-white">
                <h5 class="modal-title font-bold text-white" id="supportModalLabel">Contact Technical Support</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('support.send') }}" method="POST" id="supportForm">
                @csrf
                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label for="support_subject" class="form-label font-semibold text-gray-800">Issue Category *</label>
                        <select class="form-select" id="support_subject" name="subject" required>
                            <option value="">Select a topic</option>
                            <option value="Technical API Support">Technical API & Webhooks</option>
                            <option value="Billing Issue">Wallet & Billing Issue</option>
                            <option value="Sender ID Registration">Sender ID & KYC Approval</option>
                            <option value="Account Management">Account Management</option>
                            <option value="Other">Other Inquiry</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="support_message" class="form-label font-semibold text-gray-800">Detailed Message *</label>
                        <textarea class="form-control" id="support_message" name="message" rows="5" placeholder="Please describe your technical issue or question in detail..." required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label font-semibold text-gray-800">Priority Level</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" id="priority_low" value="Low" checked>
                                <label class="form-check-label" for="priority_low">Standard</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" id="priority_medium" value="Medium">
                                <label class="form-check-label" for="priority_medium">High</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="priority" id="priority_high" value="High">
                                <label class="form-check-label" for="priority_high">Urgent Escalation</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light font-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-semibold" id="submitSupportRequest">
                        <span>Send Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection