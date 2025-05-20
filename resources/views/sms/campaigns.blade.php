<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h2>SMS Campaigns</h2>
                <p class="text-muted">View and manage your SMS campaigns</p>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div>
                <a href="{{ route('sms.compose') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> New Campaign
                </a>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">All Campaigns</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif

                    @if($campaigns->isEmpty())
                        <div class="text-center my-5">
                            <h4>No campaigns yet</h4>
                            <p class="text-muted">You haven't sent any SMS campaigns yet.</p>
                            <a href="{{ route('sms.compose') }}" class="btn btn-primary mt-3">Send Your First Campaign</a>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title/Message</th>
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
                                            <td>{{ $campaign->id }}</td>
                                            <td>
                                                <div>{{ Str::limit($campaign->title, 30) }}</div><div class="card-title"></div><div class="card-title"></div>
                                                <small class="text-muted">{{ Str::limit($campaign->message, 50) }}</small>
                                            </td>
                                            <td>{{ $campaign->sender_name }}</td>
                                            <td>{{ $campaign->recipient_count }}</td>
                                            <td>
                                                <span class="badge badge-{{ $campaign->status == 'sent' ? 'success' : ($campaign->status == 'failed' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($campaign->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $campaign->created_at->format('M d, Y') }}</div><div class="card-title"></div><div class="card-title"></div>
                                                <small class="text-muted">{{ $campaign->created_at->format('H:i:s') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mt-4">
                            {{ $campaigns->links() }}
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection