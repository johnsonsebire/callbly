@extends('layouts.master')
@php 
use Illuminate\Support\Str;

@endphp 

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Campaigns List Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">SMS Campaigns</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">View and manage your SMS campaigns</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('sms.compose') }}" class="btn btn-sm btn-primary">
                                <i class="ki-outline ki-plus-square fs-2 me-2"></i> New Campaign
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($campaigns->isEmpty())
                            <div class="text-center my-5">
                                <i class="ki-outline ki-message-text-2 fs-2tx text-gray-300 mb-5"></i>
                                <h4>No campaigns yet</h4>
                                <p class="text-muted">You haven't sent any SMS campaigns yet.</p>
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary mt-3">Send Your First Campaign</a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-50px">ID</th>
                                            <th class="min-w-150px">Title/Message</th>
                                            <th class="min-w-100px">Sender</th>
                                            <th class="min-w-100px">Recipients</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-120px">Date</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($campaigns as $campaign)
                                            <tr>
                                                <td>{{ $campaign->id }}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-dark fw-bold mb-1">{{ Str::limit($campaign->name, 30) }}</span>
                                                        <span class="text-muted fw-semibold d-block fs-7">{{ Str::limit($campaign->message, 50) }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $campaign->sender_name }}</td>
                                                <td>{{ $campaign->recipients_count }}</td>
                                                <td>
                                                    <span class="badge badge-light-{{ $campaign->status == 'sent' ? 'success' : ($campaign->status == 'failed' ? 'danger' : 'warning') }} fs-7 fw-bold">
                                                        {{ ucfirst($campaign->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-dark fw-semibold mb-1">{{ $campaign->created_at->format('M d, Y') }}</span>
                                                        <span class="text-muted fw-semibold d-block fs-7">{{ $campaign->created_at->format('H:i:s') }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="btn btn-sm btn-light-primary">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $campaigns->links() }}
                            </div>
                        @endif
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