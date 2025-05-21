@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Messages List Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">SMS Messages</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">View all your sent and received messages</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('sms.compose') }}" class="btn btn-sm btn-primary">
                                <i class="ki-outline ki-message-text-2 fs-2 me-2"></i> Compose New Message
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($messages->isEmpty())
                            <div class="text-center my-5">
                                <i class="ki-outline ki-message-text-2 fs-2tx text-gray-300 mb-5"></i>
                                <h4>No messages yet</h4>
                                <p class="text-muted">Start sending SMS messages to see them here.</p>
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary mt-3">Send Your First Message</a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-120px">Date</th>
                                            <th class="min-w-100px">Sender ID</th>
                                            <th class="min-w-250px">Message</th>
                                            <th class="min-w-100px">Recipients</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($messages as $message)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-dark fw-semibold mb-1">{{ $message->created_at->format('M d, Y') }}</span>
                                                        <span class="text-muted fw-semibold d-block fs-7">{{ $message->created_at->format('H:i:s') }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $message->sender_name }}</td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 300px;">
                                                        {{ $message->message }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-dark fw-semibold mb-1">{{ $message->recipients_count }} recipient(s)</span>
                                                        <span class="text-success fw-semibold d-block fs-7">{{ $message->delivered_count }} delivered</span>
                                                        @if($message->failed_count > 0)
                                                            <span class="text-danger fw-semibold d-block fs-7">{{ $message->failed_count }} failed</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-{{ $message->status === 'sent' ? 'success' : ($message->status === 'failed' ? 'danger' : 'warning') }} fs-7 fw-bold">
                                                        {{ ucfirst($message->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('sms.campaign-details', $message->id) }}" class="btn btn-sm btn-light-primary">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $messages->links() }}
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