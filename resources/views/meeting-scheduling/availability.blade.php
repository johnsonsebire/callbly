@extends('layouts.master')

@section('title', 'Availability Settings')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Page Header -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Availability Settings</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Set your available times for meetings</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('meeting-scheduling.dashboard') }}" class="btn btn-sm btn-light">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Dashboard
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

                        <form method="POST" action="{{ route('meeting-scheduling.availability.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <!-- Weekly Schedule -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Weekly Schedule</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="text-muted fs-7 mb-8">Set your availability for each day of the week</div>
                                            
                                            @php
                                                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                                $dayLabels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            @endphp

                                            @foreach($days as $index => $day)
                                                <div class="border rounded p-5 mb-5">
                                                    <div class="d-flex align-items-center mb-5">
                                                        <div class="form-check form-check-custom form-check-solid me-5">
                                                            <input class="form-check-input day-toggle" type="checkbox" 
                                                                   name="availability[{{ $day }}][enabled]" value="1" 
                                                                   id="{{ $day }}_enabled"
                                                                   {{ old("availability.{$day}.enabled", $availability->$day ?? true) ? 'checked' : '' }} />
                                                            <label class="form-check-label fw-bold fs-6" for="{{ $day }}_enabled">
                                                                {{ $dayLabels[$index] }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="day-times" id="{{ $day }}_times" style="{{ old("availability.{$day}.enabled", $availability->$day ?? true) ? '' : 'display: none;' }}">
                                                        <div class="time-slots" data-day="{{ $day }}">
                                                            @php
                                                                $daySchedule = old("availability.{$day}.times", $availability->{$day . '_times'} ?? [['start' => '09:00', 'end' => '17:00']]);
                                                            @endphp
                                                            
                                                            @foreach($daySchedule as $timeIndex => $timeSlot)
                                                                <div class="time-slot row align-items-center mb-3">
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Start Time</label>
                                                                        <input type="time" name="availability[{{ $day }}][times][{{ $timeIndex }}][start]" 
                                                                               class="form-control" value="{{ $timeSlot['start'] ?? '09:00' }}" />
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">End Time</label>
                                                                        <input type="time" name="availability[{{ $day }}][times][{{ $timeIndex }}][end]" 
                                                                               class="form-control" value="{{ $timeSlot['end'] ?? '17:00' }}" />
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">&nbsp;</label>
                                                                        <div class="d-flex">
                                                                            <button type="button" class="btn btn-sm btn-light-success add-time-slot me-2">
                                                                                <i class="ki-outline ki-plus fs-2"></i>
                                                                            </button>
                                                                            @if($timeIndex > 0)
                                                                                <button type="button" class="btn btn-sm btn-light-danger remove-time-slot">
                                                                                    <i class="ki-outline ki-trash fs-2"></i>
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Break Times -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Break Times</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="text-muted fs-7 mb-5">Set regular break times that should be blocked from booking</div>
                                            
                                            <div id="break-times">
                                                @php
                                                    $breakTimes = old('break_times', $availability->break_times ?? []);
                                                @endphp
                                                
                                                @foreach($breakTimes as $index => $breakTime)
                                                    <div class="break-time-item border rounded p-4 mb-3">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" name="break_times[{{ $index }}][title]" 
                                                                       class="form-control" placeholder="e.g., Lunch Break" 
                                                                       value="{{ $breakTime['title'] ?? '' }}" />
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Start Time</label>
                                                                <input type="time" name="break_times[{{ $index }}][start]" 
                                                                       class="form-control" value="{{ $breakTime['start'] ?? '' }}" />
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">End Time</label>
                                                                <input type="time" name="break_times[{{ $index }}][end]" 
                                                                       class="form-control" value="{{ $breakTime['end'] ?? '' }}" />
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">&nbsp;</label>
                                                                <div class="d-flex">
                                                                    <button type="button" class="btn btn-sm btn-light-danger remove-break-time">
                                                                        <i class="ki-outline ki-trash fs-2"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-12">
                                                                <label class="form-label">Days</label>
                                                                <div class="d-flex flex-wrap gap-3">
                                                                    @foreach($dayLabels as $dayIndex => $dayLabel)
                                                                        <div class="form-check form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox" 
                                                                                   name="break_times[{{ $index }}][days][]" 
                                                                                   value="{{ $days[$dayIndex] }}" 
                                                                                   id="break_{{ $index }}_{{ $days[$dayIndex] }}"
                                                                                   {{ in_array($days[$dayIndex], $breakTime['days'] ?? []) ? 'checked' : '' }} />
                                                                            <label class="form-check-label" for="break_{{ $index }}_{{ $days[$dayIndex] }}">
                                                                                {{ $dayLabel }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            <button type="button" id="add-break-time" class="btn btn-light-primary btn-sm">
                                                <i class="ki-outline ki-plus fs-2 me-2"></i>Add Break Time
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <!-- General Settings -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>General Settings</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Meeting Increment</label>
                                                <select name="meeting_increment" class="form-select mb-2">
                                                    <option value="15" {{ old('meeting_increment', $availability->meeting_increment ?? 15) == 15 ? 'selected' : '' }}>15 minutes</option>
                                                    <option value="30" {{ old('meeting_increment', $availability->meeting_increment ?? 15) == 30 ? 'selected' : '' }}>30 minutes</option>
                                                    <option value="60" {{ old('meeting_increment', $availability->meeting_increment ?? 15) == 60 ? 'selected' : '' }}>60 minutes</option>
                                                </select>
                                                <div class="text-muted fs-7">Time slots will be available in these increments</div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Days in Advance</label>
                                                <input type="number" name="days_in_advance" class="form-control mb-2" 
                                                       placeholder="30" min="1" max="365"
                                                       value="{{ old('days_in_advance', $availability->days_in_advance ?? 30) }}" />
                                                <div class="text-muted fs-7">How many days in advance can people book?</div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Maximum Meetings Per Day</label>
                                                <input type="number" name="max_meetings_per_day" class="form-control mb-2" 
                                                       placeholder="0 for unlimited" min="0" max="20"
                                                       value="{{ old('max_meetings_per_day', $availability->max_meetings_per_day ?? 0) }}" />
                                                <div class="text-muted fs-7">0 means unlimited meetings per day</div>
                                            </div>

                                            <div class="separator separator-dashed my-5"></div>

                                            <div class="mb-10 fv-row">
                                                <div class="form-check form-check-custom form-check-solid form-switch form-check-success">
                                                    <input class="form-check-input" type="checkbox" name="weekend_availability" value="1" 
                                                           id="weekend_availability" {{ old('weekend_availability', $availability->weekend_availability ?? false) ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="weekend_availability">
                                                        Weekend Availability
                                                    </label>
                                                </div>
                                                <div class="text-muted fs-7">Allow bookings on weekends</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Time Zone -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Time Zone</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Your Time Zone</label>
                                                <select name="timezone" class="form-select mb-2">
                                                    <option value="Africa/Accra" {{ old('timezone', $availability->timezone ?? 'Africa/Accra') == 'Africa/Accra' ? 'selected' : '' }}>Africa/Accra (GMT)</option>
                                                    <option value="UTC" {{ old('timezone', $availability->timezone ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                                    <option value="America/New_York" {{ old('timezone', $availability->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                                    <option value="Europe/London" {{ old('timezone', $availability->timezone ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                                    <option value="Asia/Dubai" {{ old('timezone', $availability->timezone ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                                                </select>
                                                <div class="text-muted fs-7">All times will be displayed in this timezone</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="card card-flush">
                                        <div class="card-body pt-0">
                                            <div class="d-flex flex-stack">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <span class="indicator-label">Save Availability</span>
                                                    <span class="indicator-progress">Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let breakTimeIndex = {{ count(old('break_times', $availability->break_times ?? [])) }};

    // Day toggle functionality
    document.querySelectorAll('.day-toggle').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const day = this.id.replace('_enabled', '');
            const timesDiv = document.getElementById(day + '_times');
            if (this.checked) {
                timesDiv.style.display = 'block';
            } else {
                timesDiv.style.display = 'none';
            }
        });
    });

    // Add time slot functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-time-slot')) {
            const timeSlotsContainer = e.target.closest('.time-slots');
            const day = timeSlotsContainer.getAttribute('data-day');
            const timeSlotIndex = timeSlotsContainer.children.length;
            
            const timeSlotHtml = `
                <div class="time-slot row align-items-center mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="availability[${day}][times][${timeSlotIndex}][start]" 
                               class="form-control" value="09:00" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Time</label>
                        <input type="time" name="availability[${day}][times][${timeSlotIndex}][end]" 
                               class="form-control" value="17:00" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex">
                            <button type="button" class="btn btn-sm btn-light-success add-time-slot me-2">
                                <i class="ki-outline ki-plus fs-2"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light-danger remove-time-slot">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            timeSlotsContainer.insertAdjacentHTML('beforeend', timeSlotHtml);
        }
    });

    // Remove time slot functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-time-slot')) {
            e.target.closest('.time-slot').remove();
        }
    });

    // Add break time functionality
    document.getElementById('add-break-time').addEventListener('click', function() {
        const breakTimesContainer = document.getElementById('break-times');
        const breakTimeHtml = `
            <div class="break-time-item border rounded p-4 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="break_times[${breakTimeIndex}][title]" 
                               class="form-control" placeholder="e.g., Lunch Break" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="break_times[${breakTimeIndex}][start]" 
                               class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Time</label>
                        <input type="time" name="break_times[${breakTimeIndex}][end]" 
                               class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex">
                            <button type="button" class="btn btn-sm btn-light-danger remove-break-time">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <label class="form-label">Days</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" 
                                       name="break_times[${breakTimeIndex}][days][]" 
                                       value="monday" id="break_${breakTimeIndex}_monday" />
                                <label class="form-check-label" for="break_${breakTimeIndex}_monday">Monday</label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" 
                                       name="break_times[${breakTimeIndex}][days][]" 
                                       value="tuesday" id="break_${breakTimeIndex}_tuesday" />
                                <label class="form-check-label" for="break_${breakTimeIndex}_tuesday">Tuesday</label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" 
                                       name="break_times[${breakTimeIndex}][days][]" 
                                       value="wednesday" id="break_${breakTimeIndex}_wednesday" />
                                <label class="form-check-label" for="break_${breakTimeIndex}_wednesday">Wednesday</label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" 
                                       name="break_times[${breakTimeIndex}][days][]" 
                                       value="thursday" id="break_${breakTimeIndex}_thursday" />
                                <label class="form-check-label" for="break_${breakTimeIndex}_thursday">Thursday</label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" 
                                       name="break_times[${breakTimeIndex}][days][]" 
                                       value="friday" id="break_${breakTimeIndex}_friday" />
                                <label class="form-check-label" for="break_${breakTimeIndex}_friday">Friday</label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" 
                                       name="break_times[${breakTimeIndex}][days][]" 
                                       value="saturday" id="break_${breakTimeIndex}_saturday" />
                                <label class="form-check-label" for="break_${breakTimeIndex}_saturday">Saturday</label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" 
                                       name="break_times[${breakTimeIndex}][days][]" 
                                       value="sunday" id="break_${breakTimeIndex}_sunday" />
                                <label class="form-check-label" for="break_${breakTimeIndex}_sunday">Sunday</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        breakTimesContainer.insertAdjacentHTML('beforeend', breakTimeHtml);
        breakTimeIndex++;
    });

    // Remove break time functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-break-time')) {
            e.target.closest('.break-time-item').remove();
        }
    });
});
</script>
@endpush
@endsection