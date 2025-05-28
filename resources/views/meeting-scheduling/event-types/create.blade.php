@extends('layouts.master')

@section('title', 'Create Event Type')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Page Header -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Create Event Type</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Set up a new meeting type for bookings</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('meeting-scheduling.event-types.index') }}" class="btn btn-sm btn-light">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Event Types
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('meeting-scheduling.event-types.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <!-- Basic Information -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Basic Information</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <label class="required form-label">Event Type Name</label>
                                                <input type="text" name="name" class="form-control mb-2" 
                                                       placeholder="e.g., 30-min Consultation" 
                                                       value="{{ old('name') }}" required />
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="text-muted fs-7">What type of meeting is this?</div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control mb-2" rows="4" 
                                                          placeholder="Brief description of this meeting type">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="required form-label">Duration (minutes)</label>
                                                        <select name="duration" class="form-select mb-2" required>
                                                            <option value="">Select Duration</option>
                                                            <option value="15" {{ old('duration') == '15' ? 'selected' : '' }}>15 minutes</option>
                                                            <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 minutes</option>
                                                            <option value="45" {{ old('duration') == '45' ? 'selected' : '' }}>45 minutes</option>
                                                            <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>1 hour</option>
                                                            <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>1.5 hours</option>
                                                            <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>2 hours</option>
                                                        </select>
                                                        @error('duration')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-10 fv-row">
                                                        <label class="form-label">Buffer Time (minutes)</label>
                                                        <select name="buffer_time" class="form-select mb-2">
                                                            <option value="0" {{ old('buffer_time') == '0' ? 'selected' : '' }}>No buffer</option>
                                                            <option value="5" {{ old('buffer_time') == '5' ? 'selected' : '' }}>5 minutes</option>
                                                            <option value="10" {{ old('buffer_time') == '10' ? 'selected' : '' }}>10 minutes</option>
                                                            <option value="15" {{ old('buffer_time') == '15' ? 'selected' : '' }}>15 minutes</option>
                                                            <option value="30" {{ old('buffer_time') == '30' ? 'selected' : '' }}>30 minutes</option>
                                                        </select>
                                                        @error('buffer_time')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        <div class="text-muted fs-7">Time between meetings</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">URL Slug</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">{{ url('/') }}/{{ auth()->user()->company_profile->brand_name ?? 'company' }}/</span>
                                                    <input type="text" name="slug" class="form-control" 
                                                           placeholder="meeting-slug" 
                                                           value="{{ old('slug') }}" />
                                                </div>
                                                @error('slug')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <div class="text-muted fs-7">Leave blank to auto-generate from name</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Meeting Settings -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Meeting Settings</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Meeting Type</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check form-check-custom form-check-solid mb-5">
                                                            <input class="form-check-input" type="radio" value="one_on_one" name="meeting_type" id="one_on_one" {{ old('meeting_type', 'one_on_one') == 'one_on_one' ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="one_on_one">
                                                                <div class="fw-bold text-gray-800">One-on-One</div>
                                                                <div class="text-gray-600">Private meeting between you and one attendee</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check form-check-custom form-check-solid mb-5">
                                                            <input class="form-check-input" type="radio" value="group" name="meeting_type" id="group" {{ old('meeting_type') == 'group' ? 'checked' : '' }} />
                                                            <label class="form-check-label" for="group">
                                                                <div class="fw-bold text-gray-800">Group Meeting</div>
                                                                <div class="text-gray-600">Multiple attendees can join</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('meeting_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div id="group_settings" style="{{ old('meeting_type') == 'group' ? '' : 'display: none;' }}">
                                                <div class="mb-10 fv-row">
                                                    <label class="form-label">Maximum Attendees</label>
                                                    <input type="number" name="max_attendees" class="form-control mb-2" 
                                                           placeholder="Enter maximum number of attendees" 
                                                           value="{{ old('max_attendees', 10) }}" min="2" max="100" />
                                                    @error('max_attendees')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Custom Questions -->
                                    <div class="card card-flush mb-8">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Custom Questions</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-5">
                                                <div class="text-muted fs-7 mb-5">Ask custom questions when someone books this meeting</div>
                                                <div id="custom_questions">
                                                    @if(old('questions'))
                                                        @foreach(old('questions') as $index => $question)
                                                            <div class="question-item border rounded p-4 mb-3">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <input type="text" name="questions[{{ $index }}][question]" 
                                                                               class="form-control mb-2" placeholder="Enter your question" 
                                                                               value="{{ $question['question'] ?? '' }}" />
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <select name="questions[{{ $index }}][type]" class="form-select mb-2">
                                                                            <option value="text" {{ ($question['type'] ?? '') == 'text' ? 'selected' : '' }}>Text</option>
                                                                            <option value="textarea" {{ ($question['type'] ?? '') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                                                            <option value="select" {{ ($question['type'] ?? '') == 'select' ? 'selected' : '' }}>Dropdown</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-question">
                                                                            <i class="ki-outline ki-trash fs-2"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="form-check form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" 
                                                                           name="questions[{{ $index }}][required]" value="1"
                                                                           {{ isset($question['required']) ? 'checked' : '' }} />
                                                                    <label class="form-check-label">Required</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <button type="button" id="add_question" class="btn btn-light-primary btn-sm">
                                                    <i class="ki-outline ki-plus fs-2 me-2"></i>Add Question
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <!-- Settings -->
                                    <div class="card card-flush">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2>Settings</h2>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="mb-10 fv-row">
                                                <div class="form-check form-check-custom form-check-solid form-switch form-check-success">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="is_active">
                                                        Active
                                                    </label>
                                                </div>
                                                <div class="text-muted fs-7">Enable this event type for booking</div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Color</label>
                                                <input type="color" name="color" class="form-control form-control-color" 
                                                       value="{{ old('color', '#3B82F6') }}" />
                                                <div class="text-muted fs-7">Choose a color for this event type</div>
                                            </div>

                                            <div class="mb-10 fv-row">
                                                <label class="form-label">Advance Notice</label>
                                                <select name="advance_notice" class="form-select mb-2">
                                                    <option value="0" {{ old('advance_notice') == '0' ? 'selected' : '' }}>No minimum notice</option>
                                                    <option value="15" {{ old('advance_notice') == '15' ? 'selected' : '' }}>15 minutes</option>
                                                    <option value="30" {{ old('advance_notice') == '30' ? 'selected' : '' }}>30 minutes</option>
                                                    <option value="60" {{ old('advance_notice') == '60' ? 'selected' : '' }}>1 hour</option>
                                                    <option value="120" {{ old('advance_notice') == '120' ? 'selected' : '' }}>2 hours</option>
                                                    <option value="1440" {{ old('advance_notice') == '1440' ? 'selected' : '' }}>1 day</option>
                                                    <option value="2880" {{ old('advance_notice') == '2880' ? 'selected' : '' }}>2 days</option>
                                                </select>
                                                <div class="text-muted fs-7">Minimum time before meeting can be booked</div>
                                            </div>

                                            <div class="separator separator-dashed my-5"></div>

                                            <div class="d-flex flex-stack">
                                                <button type="submit" class="btn btn-primary">
                                                    <span class="indicator-label">Create Event Type</span>
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
    let questionIndex = {{ old('questions') ? count(old('questions')) : 0 }};

    // Toggle group settings
    document.querySelectorAll('input[name="meeting_type"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const groupSettings = document.getElementById('group_settings');
            if (this.value === 'group') {
                groupSettings.style.display = 'block';
            } else {
                groupSettings.style.display = 'none';
            }
        });
    });

    // Add question functionality
    document.getElementById('add_question').addEventListener('click', function() {
        const questionsContainer = document.getElementById('custom_questions');
        const questionHtml = `
            <div class="question-item border rounded p-4 mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="questions[${questionIndex}][question]" 
                               class="form-control mb-2" placeholder="Enter your question" />
                    </div>
                    <div class="col-md-3">
                        <select name="questions[${questionIndex}][type]" class="form-select mb-2">
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="select">Dropdown</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-question">
                            <i class="ki-outline ki-trash fs-2"></i>
                        </button>
                    </div>
                </div>
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" 
                           name="questions[${questionIndex}][required]" value="1" />
                    <label class="form-check-label">Required</label>
                </div>
            </div>
        `;
        questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
        questionIndex++;
    });

    // Remove question functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-question')) {
            e.target.closest('.question-item').remove();
        }
    });

    // Auto-generate slug from name
    document.querySelector('input[name="name"]').addEventListener('input', function() {
        const slugInput = document.querySelector('input[name="slug"]');
        if (!slugInput.value || slugInput.dataset.manual !== 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });

    // Mark slug as manually edited
    document.querySelector('input[name="slug"]').addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
});
</script>
@endpush
@endsection