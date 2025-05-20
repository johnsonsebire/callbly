<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h2>SMS Messages</h2>
                <p class="text-muted">View all your sent and received messages</p>
            </div>
            <div>
                <a href="{{ route('sms.compose') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Compose New Message
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">All Messages</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($messages->isEmpty())
                        <div class="text-center my-5">
                            <h4>No messages yet</h4>
                            <p class="text-muted">Start sending SMS messages to see them here.</p>
                            <a href="{{ route('sms.compose') }}" class="btn btn-primary mt-3">Send Your First Message</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sender ID</th>
                                        <th>Message</th>
                                        <th>Recipients</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td>
                                                <div>{{ $message->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $message->created_at->format('H:i:s') }}</small>
                                            </td>
                                            <td>{{ $message->sender_name }}</td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;">
                                                    {{ $message->message }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $message->recipients_count }} recipient(s)</div>
                                                <small class="text-success">{{ $message->delivered_count }} delivered</small>
                                                @if($message->failed_count > 0)
                                                    <br>
                                                    <small class="text-danger">{{ $message->failed_count }} failed</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $message->status === 'sent' ? 'success' : ($message->status === 'failed' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($message->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('sms.campaign-details', $message->id) }}" class="btn btn-sm btn-light-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection