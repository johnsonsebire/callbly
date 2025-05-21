@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <h2>SMS Dashboard</h2>
                        <p class="text-muted">Manage your SMS campaigns, sender IDs, and balance</p>
                    </div>
                </div>

                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <div class="card-title">
                                    <h2 class="fw-bold">Account Balance</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="mb-0">{{ auth()->user()->sms_credits ?? 0 }}</h3>
                                    <span class="badge badge-primary">Credits</span>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('sms.credits') }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                    <a href="#" class="btn btn-sm btn-primary">Buy Credits</a>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>

                    <div class="col-md-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <div class="card-title">
                                    <h2 class="fw-bold">Quick Actions</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary w-100 mb-2">
                                    <i class="bi bi-send me-2"></i>Compose New SMS
                                </a>
                                <a href="{{ route('sms.campaigns') }}" class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="bi bi-list-ul me-2"></i>View Campaigns
                                </a>
                                <a href="{{ route('sms.sender-names') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-person-vcard me-2"></i>Manage Sender Names
                                </a>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>

                    <div class="col-md-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <div class="card-title">
                                    <h2 class="fw-bold">Sender IDs</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @php
                                    $senderNames = auth()->user()->senderNames ?? collect([]);
                                @endphp
                                
                                @if($senderNames->isEmpty())
                                    <p>You don't have any sender IDs yet.</p>
                                    <a href="{{ route('sms.sender-names') }}" class="btn btn-sm btn-primary">Register Sender ID</a>
                                @else
                                    <ul class="list-group list-group-flush">
                                        @foreach($senderNames->take(3) as $senderName)
                                            <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                {{ $senderName->name }}
                                                <span class="badge bg-{{ $senderName->status == 'approved' ? 'success' : ($senderName->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($senderName->status) }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ route('sms.sender-names') }}" class="btn btn-sm btn-link mt-2">View All</a>
                                @endif
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>

                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Recent Campaigns</h2>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('sms.campaigns') }}" class="btn btn-sm btn-link">View All</a>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @php
                                    $campaigns = auth()->user()->smsCampaigns()->latest()->take(5)->get() ?? collect([]);
                                @endphp
                                
                                @if($campaigns->isEmpty())
                                    <p class="text-center my-4">You haven't sent any campaigns yet.</p>
                                    <div class="text-center">
                                        <a href="{{ route('sms.compose') }}" class="btn btn-primary">Send Your First Campaign</a>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th>Title</th>
                                                    <th>Sender</th>
                                                    <th>Recipients</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($campaigns as $campaign)
                                                    <tr>
                                                        <td>{{ \Illuminate\Support\Str::limit($campaign->name, 30) }}</td>
                                                        <td>{{ $campaign->sender_name }}</td>
                                                        <td>{{ $campaign->recipients_count }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $campaign->status == 'sent' ? 'success' : ($campaign->status == 'failed' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($campaign->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $campaign->created_at->format('M d, Y H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="btn btn-sm btn-outline-primary">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
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