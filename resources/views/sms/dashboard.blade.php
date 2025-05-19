@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>SMS Dashboard</h2>
            <p class="text-muted">Manage your SMS campaigns, sender IDs, and balance</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Balance</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ $accountBalance['credits'] ?? 0 }}</h3>
                        <span>Credits</span>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('sms.credits') }}" class="btn btn-sm btn-outline-primary">View Details</a>
                        <a href="#" class="btn btn-sm btn-primary">Buy Credits</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('sms.compose') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-paper-plane mr-2"></i>Compose New SMS
                    </a>
                    <a href="{{ route('sms.campaigns') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="fas fa-list mr-2"></i>View Campaigns
                    </a>
                    <a href="{{ route('sms.sender-names') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-id-card mr-2"></i>Manage Sender Names
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sender IDs</h5>
                </div>
                <div class="card-body">
                    @if($senderNames->isEmpty())
                        <p>You don't have any sender IDs yet.</p>
                        <a href="{{ route('sms.sender-names') }}" class="btn btn-sm btn-primary">Register Sender ID</a>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($senderNames->take(3) as $senderName)
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    {{ $senderName->name }}
                                    <span class="badge badge-{{ $senderName->status == 'approved' ? 'success' : ($senderName->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($senderName->status) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('sms.sender-names') }}" class="btn btn-sm btn-link mt-2">View All</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Campaigns</h5>
                    <a href="{{ route('sms.campaigns') }}" class="btn btn-sm btn-link">View All</a>
                </div>
                <div class="card-body">
                    @if($campaigns->isEmpty())
                        <p class="text-center my-4">You haven't sent any campaigns yet.</p>
                        <div class="text-center">
                            <a href="{{ route('sms.compose') }}" class="btn btn-primary">Send Your First Campaign</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
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
                                            <td>{{ Str::limit($campaign->title, 30) }}</td>
                                            <td>{{ $campaign->sender_name }}</td>
                                            <td>{{ $campaign->recipient_count }}</td>
                                            <td>
                                                <span class="badge badge-{{ $campaign->status == 'sent' ? 'success' : ($campaign->status == 'failed' ? 'danger' : 'warning') }}">
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
            </div>
        </div>
    </div>
</div>
@endsection