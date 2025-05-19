@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('sms.campaigns') }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left mr-1"></i> Back to Campaigns
            </a>
            <h2>Campaign Details</h2>
            <p class="text-muted">Viewing details for campaign #{{ $campaign->id }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Campaign Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Campaign ID:</strong>
                            <p>{{ $campaign->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong>
                            <p>
                                <span class="badge badge-{{ $campaign->status == 'sent' ? 'success' : ($campaign->status == 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Sender Name:</strong>
                            <p>{{ $campaign->sender_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Created Date:</strong>
                            <p>{{ $campaign->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Recipient Count:</strong>
                            <p>{{ $campaign->recipient_count }} recipients</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Successful Deliveries:</strong>
                            <p>{{ $campaign->success_count }} ({{ $campaign->recipient_count > 0 ? round(($campaign->success_count / $campaign->recipient_count) * 100) : 0 }}%)</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>Message:</strong>
                            <p class="p-3 bg-light">{{ $campaign->message }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Delivery Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="h1 text-success">{{ $campaign->success_count }}</div>
                            <div>Delivered</div>
                        </div>
                        <div class="col-md-4 text-center mb-4">
                            <div class="h1 text-danger">{{ $campaign->failed_count }}</div>
                            <div>Failed</div>
                        </div>
                        <div class="col-md-4 text-center mb-4">
                            <div class="h1 text-warning">{{ $campaign->pending_count }}</div>
                            <div>Pending</div>
                        </div>
                    </div>
                    
                    <div class="progress">
                        @if($campaign->recipient_count > 0)
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ ($campaign->success_count / $campaign->recipient_count) * 100 }}%" 
                                aria-valuenow="{{ ($campaign->success_count / $campaign->recipient_count) * 100 }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ round(($campaign->success_count / $campaign->recipient_count) * 100) }}%
                            </div>
                            <div class="progress-bar bg-warning" role="progressbar" 
                                style="width: {{ ($campaign->pending_count / $campaign->recipient_count) * 100 }}%" 
                                aria-valuenow="{{ ($campaign->pending_count / $campaign->recipient_count) * 100 }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ round(($campaign->pending_count / $campaign->recipient_count) * 100) }}%
                            </div>
                            <div class="progress-bar bg-danger" role="progressbar" 
                                style="width: {{ ($campaign->failed_count / $campaign->recipient_count) * 100 }}%" 
                                aria-valuenow="{{ ($campaign->failed_count / $campaign->recipient_count) * 100 }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ round(($campaign->failed_count / $campaign->recipient_count) * 100) }}%
                            </div>
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
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Message Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Character Count:</strong>
                        <p>{{ strlen($campaign->message) }} characters</p>
                    </div>
                    <div class="mb-3">
                        <strong>Message Parts:</strong>
                        @php
                            $charCount = strlen($campaign->message);
                            $parts = $charCount <= 160 ? 1 : ceil(($charCount - 160) / 153) + 1;
                        @endphp
                        <p>{{ $parts }} parts</p>
                    </div>
                    <div class="mb-3">
                        <strong>Cost Per Recipient:</strong>
                        <p>{{ $parts }} credit(s)</p>
                    </div>
                    <div class="mb-3">
                        <strong>Total Credits Used:</strong>
                        <p>{{ $parts * $campaign->recipient_count }} credits</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('sms.download-report', $campaign->id) }}" class="btn btn-outline-primary btn-block mb-2">
                        <i class="fas fa-download mr-1"></i> Download Report
                    </a>
                    <a href="{{ route('sms.duplicate-campaign', $campaign->id) }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-copy mr-1"></i> Duplicate Campaign
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recipient Details</h5>
                    <form action="{{ route('sms.campaign-details', $campaign->id) }}" method="GET" class="form-inline">
                        <div class="form-group mr-2">
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Filter</button>
                    </form>
                </div>
                <div class="card-body">
                    @if($recipients->isEmpty())
                        <p class="text-center my-4">No recipient details available.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
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
                                            <td>{{ $recipient->phone_number }}</td>
                                            <td>
                                                <span class="badge badge-{{ $recipient->status == 'delivered' ? 'success' : ($recipient->status == 'failed' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($recipient->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $recipient->sent_at ? $recipient->sent_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                                            <td>{{ $recipient->delivered_at ? $recipient->delivered_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                                            <td>{{ $recipient->error_message ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $recipients->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection