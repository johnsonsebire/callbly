@extends('layouts.master')

@section('title', 'Event Types')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Page Header -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Event Types</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage your meeting event types</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('meeting-scheduling.event-types.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-outline ki-plus fs-2 me-2"></i>Create Event Type
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-success">Success</h4>
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        @if($eventTypes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Name</th>
                                            <th class="min-w-100px">Duration</th>
                                            <th class="min-w-120px">Buffer Time</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-150px">Booking Link</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($eventTypes as $eventType)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <span class="symbol-label bg-light-primary">
                                                                <i class="ki-outline ki-calendar fs-2 text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <span class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $eventType->name }}</span>
                                                            <span class="text-muted fw-semibold text-muted d-block fs-7">{{ Str::limit($eventType->description, 50) }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block fs-7">{{ $eventType->duration }} minutes</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fw-semibold d-block fs-7">{{ $eventType->buffer_time }} minutes</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-{{ $eventType->is_active ? 'success' : 'danger' }} fs-7 fw-bold">
                                                        {{ $eventType->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm" 
                                                               value="{{ route('public.booking.show', ['brand' => auth()->user()->company_profile->brand_name ?? 'company', 'event' => $eventType->slug]) }}" 
                                                               readonly>
                                                        <button class="btn btn-outline-secondary btn-sm copy-link" type="button" 
                                                                data-bs-toggle="tooltip" title="Copy Link">
                                                            <i class="ki-outline ki-copy fs-2"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end flex-shrink-0">
                                                        <a href="{{ route('meeting-scheduling.event-types.edit', $eventType) }}" 
                                                           class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                            <i class="ki-outline ki-pencil fs-2"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('meeting-scheduling.event-types.destroy', $eventType) }}" 
                                                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event type?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                                                <i class="ki-outline ki-trash fs-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ki-outline ki-calendar fs-2tx text-gray-300 mb-5"></i>
                                    <span class="text-gray-600 fs-5 fw-semibold mb-3">No event types found</span>
                                    <span class="text-gray-400 fs-7 mb-5">Create your first event type to start accepting bookings</span>
                                    <a href="{{ route('meeting-scheduling.event-types.create') }}" class="btn btn-primary">
                                        <i class="ki-outline ki-plus fs-2 me-2"></i>Create Event Type
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                button.innerHTML = '<i class="ki-outline ki-check fs-2"></i>';
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