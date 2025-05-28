@extends('layouts.master')

@section('title', 'Meeting Scheduling Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Meeting Scheduling Dashboard</h1>
                <div class="btn-group">
                    <a href="{{ route('meeting-scheduling.event-types.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Event Type
                    </a>
                    <a href="{{ route('meeting-scheduling.profile') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-3 p-3 text-white">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">{{ $upcomingMeetings }}</h5>
                            <p class="card-text text-muted mb-0">Upcoming Meetings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success rounded-3 p-3 text-white">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">{{ $totalBookings }}</h5>
                            <p class="card-text text-muted mb-0">Total Bookings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info rounded-3 p-3 text-white">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">{{ $eventTypes }}</h5>
                            <p class="card-text text-muted mb-0">Event Types</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning rounded-3 p-3 text-white">
                                <i class="fas fa-sms fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">{{ $smsCredits }}</h5>
                            <p class="card-text text-muted mb-0">SMS Credits</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions and Recent Bookings -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('meeting-scheduling.event-types.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Create Event Type
                        </a>
                        <a href="{{ route('meeting-scheduling.availability') }}" class="btn btn-outline-success">
                            <i class="fas fa-clock me-2"></i>Set Availability
                        </a>
                        <a href="{{ route('meeting-scheduling.notifications') }}" class="btn btn-outline-info">
                            <i class="fas fa-bell me-2"></i>Notification Settings
                        </a>
                        <a href="{{ route('meeting-scheduling.profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>Company Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Bookings</h5>
                    <a href="{{ route('meeting-scheduling.bookings') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Attendee</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle p-2 text-white me-3">
                                                    <i class="fas fa-calendar fa-sm"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $booking->eventType->name }}</h6>
                                                    <small class="text-muted">{{ $booking->eventType->duration }} min</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $booking->booker_name }}</h6>
                                                <small class="text-muted">{{ $booking->booker_email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-medium">{{ $booking->start_time->format('M j, Y') }}</span><br>
                                                <small class="text-muted">{{ $booking->start_time->format('g:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ $booking->meeting_link }}" class="btn btn-outline-primary" target="_blank" title="Join Meeting">
                                                    <i class="fas fa-video"></i>
                                                </a>
                                                <a href="{{ route('meeting-scheduling.bookings.show', $booking) }}" class="btn btn-outline-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No bookings yet</h5>
                            <p class="text-muted">Share your scheduling link to start receiving bookings.</p>
                            <a href="{{ route('meeting-scheduling.event-types.create') }}" class="btn btn-primary">Create Your First Event Type</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scheduling Links -->
    @if($eventTypes > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Your Scheduling Links</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($userEventTypes as $eventType)
                        <div class="col-lg-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $eventType->name }}</h6>
                                        <p class="text-muted mb-2">{{ $eventType->duration }} minutes</p>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" 
                                                   value="{{ route('public.booking.show', ['brand' => auth()->user()->company_profile->brand_name ?? 'company', 'event' => $eventType->slug]) }}" 
                                                   readonly>
                                            <button class="btn btn-outline-secondary btn-sm copy-link" type="button" data-bs-toggle="tooltip" title="Copy Link">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Copy link functionality
    document.querySelectorAll('.copy-link').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(function() {
                // Show success message
                const originalIcon = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(function() {
                    button.innerHTML = originalIcon;
                }, 2000);
            });
        });
    });
});
</script>
@endpush
@endsection