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
                <div class="row mb-4">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('sms.campaigns') }}">SMS Campaigns</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Campaign #{{ $campaign->id }}</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h2 class="mb-1">Campaign Details</h2>
                                <p class="text-muted mb-0">Viewing details for campaign #{{ $campaign->id }}</p>
                            </div>
                            <a href="{{ route('sms.campaigns') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Campaigns
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i> Campaign Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase">Campaign ID</label>
                                        <p class="fs-5 fw-semibold">{{ $campaign->id }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase">Status</label>
                                        <div>
                                            <span class="badge bg-{{ $campaign->status == 'completed' ? 'success' : ($campaign->status == 'failed' ? 'danger' : 'warning') }} py-2 px-3 rounded-pill">
                                                <i class="fas fa-{{ $campaign->status == 'completed' ? 'check-circle' : ($campaign->status == 'failed' ? 'times-circle' : 'clock') }} me-1"></i>
                                                {{ ucfirst($campaign->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase">Sender Name</label>
                                        <p class="fs-5 fw-semibold">{{ $campaign->sender_name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase">Created Date</label>
                                        <p class="fs-5 fw-semibold">{{ $campaign->created_at->format('M d, Y H:i:s') }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase">Recipient Count</label>
                                        <p class="fs-5 fw-semibold">{{ number_format($totalRecipients) }} recipients</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small text-uppercase">Successful Deliveries</label>
                                        <p class="fs-5 fw-semibold">
                                            {{ number_format($deliveredCount) }}
                                            <span class="text-muted fs-6">
                                                ({{ $campaign->getSuccessRate() }}%)
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="text-muted small text-uppercase">Message</label>
                                        <div class="p-3 bg-light rounded border">
                                            <p class="mb-0" style="white-space: pre-wrap;">{{ $campaign->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2"></i> Delivery Statistics
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-4 text-center mb-4 mb-md-0">
                                        <div class="d-inline-block p-3 rounded-circle bg-success bg-opacity-10 mb-2">
                                            <h1 class="text-success mb-0">{{ number_format($deliveredCount) }}</h1>
                                        </div>
                                        <div class="text-muted">Delivered</div>
                                    </div>
                                    <div class="col-md-4 text-center mb-4 mb-md-0">
                                        <div class="d-inline-block p-3 rounded-circle bg-danger bg-opacity-10 mb-2">
                                            <h1 class="text-danger mb-0">{{ number_format($failedCount) }}</h1>
                                        </div>
                                        <div class="text-muted">Failed</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="d-inline-block p-3 rounded-circle bg-warning bg-opacity-10 mb-2">
                                            <h1 class="text-warning mb-0">{{ number_format($pendingCount) }}</h1>
                                        </div>
                                        <div class="text-muted">Pending</div>
                                    </div>
                                </div>
                                
                                <div class="progress" style="height: 25px;">
                                    @if($totalRecipients > 0)
                                        @if($deliveredPercentage > 0)
                                        <div class="progress-bar bg-success" role="progressbar" 
                                            style="width: {{ $deliveredPercentage }}%" 
                                            aria-valuenow="{{ $deliveredPercentage }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            {{ $deliveredPercentage }}%
                                        </div>
                                        @endif
                                        
                                        @if($pendingPercentage > 0)
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                            style="width: {{ $pendingPercentage }}%" 
                                            aria-valuenow="{{ $pendingPercentage }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            {{ $pendingPercentage }}%
                                        </div>
                                        @endif
                                        
                                        @if($failedPercentage > 0)
                                        <div class="progress-bar bg-danger" role="progressbar" 
                                            style="width: {{ $failedPercentage }}%" 
                                            aria-valuenow="{{ $failedPercentage }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            {{ $failedPercentage }}%
                                        </div>
                                        @endif
                                    @else
                                        <div class="progress-bar" role="progressbar" 
                                            style="width: 0%" 
                                            aria-valuenow="0" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            0%
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="color-dot bg-success mr-2"></div>
                                            <small>Delivered: {{ $campaign->getSuccessRate() }}%</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="color-dot bg-warning mr-2"></div>
                                            <small>Pending: {{ $campaign->getPendingRate() }}%</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="color-dot bg-danger mr-2"></div>
                                            <small>Failed: {{ $campaign->getFailureRate() }}%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-comment-alt me-2"></i> Message Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small text-uppercase">Character Count</label>
                                    <p class="fs-5 fw-semibold">{{ number_format(mb_strlen($campaign->message)) }} characters</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small text-uppercase">Message Parts</label>
                                    <p class="fs-5 fw-semibold">{{ $parts }} {{ Str::plural('part', $parts) }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small text-uppercase">Cost Per Recipient</label>
                                    <p class="fs-5 fw-semibold">{{ $parts }} {{ Str::plural('credit', $parts) }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small text-uppercase">Total Credits Used</label>
                                    <p class="fs-5 fw-semibold">{{ number_format($campaign->getTotalCreditsUsed()) }} {{ Str::plural('credit', $campaign->getTotalCreditsUsed()) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-cogs me-2"></i> Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('sms.download-report', $campaign->id) }}" class="btn btn-primary w-100 mb-3">
                                    <i class="fas fa-download me-2"></i> Download Report
                                </a>
                                <a href="{{ route('sms.duplicate-campaign', $campaign->id) }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-copy me-2"></i> Duplicate Campaign
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-users me-2"></i> Recipient Details
                                </h5>
                                <form action="{{ route('sms.campaign-details', $campaign->id) }}" method="GET" class="d-flex align-items-center">
                                    <div class="input-group">
                                        <select name="status" class="form-select">
                                            <option value="">All Statuses</option>
                                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                @if($recipients->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No recipient details available.</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Phone Number</th>
                                                    <th>Status</th>
                                                    <th>Sent Time</th>
                                                    <th>Delivery Time</th>
                                                    <th>Error Message</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recipients as $recipient)
                                                    <tr>
                                                        <td>{{ $recipient->getFormattedPhoneNumber() }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $recipient->status == 'delivered' ? 'success' : ($recipient->status == 'failed' ? 'danger' : 'warning') }} rounded-pill">
                                                                {{ ucfirst($recipient->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $recipient->created_at ? $recipient->created_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                                                        <td>{{ $recipient->delivered_at ? $recipient->delivered_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                                                        <td>
                                                            @if($recipient->error_message)
                                                                <span class="text-danger">{{ Str::limit($recipient->error_message, 50) }}</span>
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $recipients->appends(request()->except('page'))->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>

<style>
.color-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}
</style>
@endsection