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
                                    <li class="breadcrumb-item"><a href="{{ route('sms.campaigns') }}">SMS Campaigns</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Campaign #{{ $campaign->id }}
                                    </li>
                                </ol>
                            </nav>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h2 class="mb-1">Campaign Details</h2>
                                    <p class="text-muted mb-0">Viewing details for campaign #{{ $campaign->id }}</p>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($campaign->scheduled_at && $campaign->scheduled_at > now() && $campaign->status === 'pending')
                                        <button type="button" class="btn btn-warning btn-sm" onclick="openEditModal()">
                                            <i class="fas fa-edit me-2"></i> Edit Schedule
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                                            <i class="fas fa-trash me-2"></i> Delete
                                        </button>
                                    @endif
                                    <a href="{{ route('sms.campaigns') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Campaigns
                                    </a>
                                </div>
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
                                                <span
                                                    class="badge bg-{{ $campaign->status == 'completed' ? 'success' : ($campaign->status == 'failed' ? 'danger' : ($campaign->status == 'processing' ? 'primary' : 'warning')) }} py-2 px-3 rounded-pill"
                                                    data-campaign-status="{{ $campaign->status }}">
                                                    <i
                                                        class="fas fa-{{ $campaign->status == 'completed' ? 'check-circle' : ($campaign->status == 'failed' ? 'times-circle' : ($campaign->status == 'processing' ? 'spinner fa-spin' : 'clock')) }} me-1"></i>
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
                                            <p class="fs-5 fw-semibold">{{ $campaign->created_at->format('M d, Y H:i:s') }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-muted small text-uppercase">Recipient Count</label>
                                            <p class="fs-5 fw-semibold">{{ number_format($totalRecipients) }} recipients
                                            </p>
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
                                        @if($campaign->scheduled_at)
                                        <div class="col-md-6 mb-3">
                                            <label class="text-muted small text-uppercase">Scheduled Time</label>
                                            <p class="fs-5 fw-semibold">{{ $campaign->scheduled_at->format('M d, Y H:i:s') }}</p>
                                        </div>
                                        @endif
                                        <div class="col-md-12">
                                            <label class="text-muted small text-uppercase">Message</label>
                                            <div class="p-3 bg-light rounded border">
                                                <p class="mb-0" style="white-space: pre-wrap;">{{ $campaign->message }}
                                                </p>
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
                                            <div
                                                class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 mb-2 stat-circle">
                                                <h1 class="text-success mb-0" data-delivered-count>
                                                    {{ number_format($deliveredCount) }}</h1>
                                            </div>
                                            <div class="text-muted">Delivered</div>
                                        </div>
                                        <div class="col-md-4 text-center mb-4 mb-md-0">
                                            <div
                                                class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 mb-2 stat-circle">
                                                <h1 class="text-danger mb-0" data-failed-count>
                                                    {{ number_format($failedCount) }}</h1>
                                            </div>
                                            <div class="text-muted">Failed</div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div
                                                class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 mb-2 stat-circle">
                                                <h1 class="text-warning mb-0" data-pending-count>
                                                    {{ number_format($pendingCount) }}</h1>
                                            </div>
                                            <div class="text-muted">Pending</div>
                                        </div>
                                    </div>


                                    <div class="progress" style="height: 25px;">
                                        @if ($totalRecipients > 0)
                                            @if ($deliveredPercentage > 0)
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $deliveredPercentage }}%"
                                                    aria-valuenow="{{ $deliveredPercentage }}" aria-valuemin="0"
                                                    aria-valuemax="100" data-progress-delivered>
                                                    <span data-delivered-percentage>{{ $deliveredPercentage }}%</span>
                                                </div>
                                            @endif

                                            @if ($pendingPercentage > 0)
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: {{ $pendingPercentage }}%"
                                                    aria-valuenow="{{ $pendingPercentage }}" aria-valuemin="0"
                                                    aria-valuemax="100" data-progress-pending>
                                                    <span data-pending-percentage>{{ $pendingPercentage }}%</span>
                                                </div>
                                            @endif

                                            @if ($failedPercentage > 0)
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    style="width: {{ $failedPercentage }}%"
                                                    aria-valuenow="{{ $failedPercentage }}" aria-valuemin="0"
                                                    aria-valuemax="100" data-progress-failed>
                                                    <span data-failed-percentage>{{ $failedPercentage }}%</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100">
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
                                        <p class="fs-5 fw-semibold">{{ number_format(mb_strlen($campaign->message)) }}
                                            characters</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small text-uppercase">Message Length</label>
                                        <p class="fs-5 fw-semibold">{{ $parts }} {{ Str::plural('page', $parts) }}
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small text-uppercase">Cost Per Recipient</label>
                                        <p class="fs-5 fw-semibold">{{ $parts }}
                                            {{ Str::plural('credit', $parts) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small text-uppercase">Total Credits Used</label>
                                        <p class="fs-5 fw-semibold">{{ number_format($campaign->getTotalCreditsUsed()) }}
                                            {{ Str::plural('credit', $campaign->getTotalCreditsUsed()) }}</p>
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
                                    <a href="{{ route('sms.download-report', $campaign->id) }}"
                                        class="btn btn-primary w-100 mb-3">
                                        <i class="fas fa-download me-2"></i> Download Report
                                    </a>
                                    <a href="{{ route('sms.duplicate-campaign', $campaign->id) }}"
                                        class="btn btn-outline-secondary w-100">
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
                                    <form action="{{ route('sms.campaign-details', $campaign->id) }}" method="GET"
                                        class="d-flex align-items-center">
                                        <div class="input-group">
                                            <select name="status" class="form-select">
                                                <option value="">All Statuses</option>
                                                <option value="delivered"
                                                    {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                                                </option>
                                                <option value="failed"
                                                    {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                                <option value="pending"
                                                    {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter me-1"></i> Filter
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body">
                                    @if ($recipients->isEmpty())
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
                                                    @foreach ($recipients as $recipient)
                                                        <tr>
                                                            <td>{{ $recipient->getFormattedPhoneNumber() }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $recipient->status == 'delivered' ? 'success' : ($recipient->status == 'failed' ? 'danger' : 'warning') }} rounded-pill">
                                                                    {{ ucfirst($recipient->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $recipient->created_at ? $recipient->created_at->format('M d, Y H:i:s') : 'N/A' }}
                                                            </td>
                                                            <td>{{ $recipient->delivered_at ? $recipient->delivered_at->format('M d, Y H:i:s') : 'N/A' }}
                                                            </td>
                                                            <td>
                                                                @if ($recipient->error_message)
                                                                    <span
                                                                        class="text-danger">{{ Str::limit($recipient->error_message, 50) }}</span>
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

        .stat-circle {
            width: 100px;
            height: 100px;
        }

        @media (max-width: 576px) {
            .stat-circle {
                width: 70px;
                height: 70px;
            }
        }
    </style>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editScheduleModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Scheduled Campaign
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editScheduleForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_campaign_name" class="form-label">Campaign Name</label>
                                <input type="text" class="form-control" id="edit_campaign_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_sender_name" class="form-label">Sender Name</label>
                                <select class="form-select" id="edit_sender_name" required>
                                    @foreach(auth()->user()->getAvailableSenderNames()->where('status', 'approved') as $senderName)
                                        <option value="{{ $senderName->name }}">{{ $senderName->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_message" class="form-label">Message</label>
                            <textarea class="form-control" id="edit_message" rows="4" required></textarea>
                            <div class="form-text">
                                <span id="edit_char_count">0</span> characters
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_scheduled_at" class="form-label">Scheduled Time</label>
                            <input type="datetime-local" class="form-control" id="edit_scheduled_at" required>
                            <div class="form-text">Time will be scheduled in your local timezone</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-changes-btn" onclick="saveScheduledChanges()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const campaignId = {{ $campaign->id }};
                let isPolling = false;
                let pollInterval;

                // Check if campaign is still processing
                function shouldPoll() {
                    const statusBadge = document.querySelector('[data-campaign-status]');
                    if (!statusBadge) return false;

                    const status = statusBadge.dataset.campaignStatus;
                    return ['pending', 'processing'].includes(status);
                }

                // Start polling for status updates
                function startPolling() {
                    if (isPolling || !shouldPoll()) return;

                    isPolling = true;
                    console.log('Starting campaign status polling...');

                    pollInterval = setInterval(fetchCampaignStatus, 3000); // Poll every 3 seconds
                }

                // Stop polling
                function stopPolling() {
                    if (pollInterval) {
                        clearInterval(pollInterval);
                        isPolling = false;
                        console.log('Stopped campaign status polling');
                    }
                }

                // Fetch campaign status from server
                function fetchCampaignStatus() {
                    fetch(`/sms/campaigns/${campaignId}/status`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                updateCampaignUI(data.data);

                                // Stop polling if campaign is no longer processing
                                if (!data.data.is_processing) {
                                    stopPolling();

                                    // Optionally reload the page to show updated recipient details
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching campaign status:', error);
                            // Continue polling even on error, but limit retries
                        });
                }

                // Update UI with new campaign data
                function updateCampaignUI(campaign) {
                    // Update status badge
                    const statusBadge = document.querySelector('[data-campaign-status]');
                    if (statusBadge) {
                        statusBadge.dataset.campaignStatus = campaign.status;
                        statusBadge.className = `badge py-2 px-3 rounded-pill bg-${getStatusColor(campaign.status)}`;
                        statusBadge.innerHTML =
                            `<i class="fas fa-${getStatusIcon(campaign.status)} me-1"></i>${capitalize(campaign.status)}`;
                    }

                    // Update delivery counts
                    updateElement('[data-delivered-count]', campaign.delivered_count.toLocaleString());
                    updateElement('[data-failed-count]', campaign.failed_count.toLocaleString());
                    updateElement('[data-pending-count]', campaign.pending_count.toLocaleString());

                    // Update percentages
                    updateElement('[data-delivered-percentage]', campaign.delivered_percentage + '%');
                    updateElement('[data-failed-percentage]', campaign.failed_percentage + '%');
                    updateElement('[data-pending-percentage]', campaign.pending_percentage + '%');

                    // Update progress bars
                    updateProgressBar('[data-progress-delivered]', campaign.delivered_percentage);
                    updateProgressBar('[data-progress-failed]', campaign.failed_percentage);
                    updateProgressBar('[data-progress-pending]', campaign.pending_percentage);

                    // Update timestamps
                    if (campaign.started_at) {
                        updateElement('[data-started-at]', campaign.started_at);
                    }
                    if (campaign.completed_at) {
                        updateElement('[data-completed-at]', campaign.completed_at);
                    }

                    console.log('Campaign status updated:', campaign.status,
                        `Delivered: ${campaign.delivered_count}/${campaign.recipients_count}`);
                }

                // Helper functions
                function updateElement(selector, value) {
                    const element = document.querySelector(selector);
                    if (element) element.textContent = value;
                }

                function updateProgressBar(selector, percentage) {
                    const progressBar = document.querySelector(selector);
                    if (progressBar) {
                        progressBar.style.width = percentage + '%';
                        progressBar.setAttribute('aria-valuenow', percentage);
                        progressBar.textContent = percentage + '%';
                    }
                }

                function getStatusColor(status) {
                    switch (status) {
                        case 'completed':
                            return 'success';
                        case 'failed':
                            return 'danger';
                        case 'processing':
                            return 'primary';
                        default:
                            return 'warning';
                    }
                }

                function getStatusIcon(status) {
                    switch (status) {
                        case 'completed':
                            return 'check-circle';
                        case 'failed':
                            return 'times-circle';
                        case 'processing':
                            return 'spinner fa-spin';
                        default:
                            return 'clock';
                    }
                }

                function capitalize(str) {
                    return str.charAt(0).toUpperCase() + str.slice(1);
                }

                // Start polling if campaign is processing
                if (shouldPoll()) {
                    startPolling();
                }

                // Clean up on page unload
                window.addEventListener('beforeunload', stopPolling);

                // Character counting for edit modal
                document.getElementById('edit_message').addEventListener('input', function() {
                    document.getElementById('edit_char_count').textContent = this.value.length;
                });

                // Edit Schedule Modal Functions
                window.openEditModal = function() {
                    const modal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
                    
                    // Pre-fill current values
                    document.getElementById('edit_campaign_name').value = '{{ $campaign->name }}';
                    document.getElementById('edit_message').value = `{{ addslashes($campaign->message) }}`;
                    document.getElementById('edit_sender_name').value = '{{ $campaign->sender_name }}';
                    
                    @if($campaign->scheduled_at)
                        // Format the current scheduled time for the datetime-local input
                        const currentScheduled = new Date('{{ $campaign->scheduled_at->format('Y-m-d\\TH:i:s') }}');
                        const localTimeString = currentScheduled.toISOString().slice(0, 16);
                        document.getElementById('edit_scheduled_at').value = localTimeString;
                    @endif
                    
                    modal.show();
                };

                window.confirmDelete = function() {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This will delete the scheduled campaign. This action cannot be undone and any credits used will be refunded.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteScheduledCampaign();
                        }
                    });
                };

                function saveScheduledChanges() {
                    const formData = {
                        name: document.getElementById('edit_campaign_name').value,
                        message: document.getElementById('edit_message').value,
                        sender_name: document.getElementById('edit_sender_name').value,
                        scheduled_at: document.getElementById('edit_scheduled_at').value.replace('T', ' '),
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
                    };

                    const saveBtn = document.getElementById('save-changes-btn');
                    const originalText = saveBtn.innerHTML;
                    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                    saveBtn.disabled = true;

                    fetch(`/api/sms/campaigns/{{ $campaign->id }}/scheduled`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin', // Include session cookies
                        body: JSON.stringify(formData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Show success message with SweetAlert
                            Swal.fire({
                                title: 'Success!',
                                text: 'Campaign updated successfully',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload(); // Reload to show updated data
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to update campaign',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while updating the campaign',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    })
                    .finally(() => {
                        saveBtn.innerHTML = originalText;
                        saveBtn.disabled = false;
                    });
                }

                function deleteScheduledCampaign() {
                    fetch(`/api/sms/campaigns/{{ $campaign->id }}/scheduled`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin' // Include session cookies
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Campaign deleted successfully. Credits have been refunded.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '{{ route("sms.campaigns") }}';
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to delete campaign',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the campaign',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        </script>
    @endpush

@endsection
