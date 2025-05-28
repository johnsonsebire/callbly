<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Book a Meeting - {{ $company->company_name ?? 'Schedule Meeting' }}</title>
    <meta name="description" content="Book a meeting with {{ $company->company_name ?? 'us' }}" />
    <meta name="keywords" content="meeting, scheduling, booking, {{ $company->company_name ?? '' }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Book a Meeting - {{ $company->company_name ?? 'Schedule Meeting' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ $company->company_name ?? 'Callbly Meeting Scheduler' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: {{ $company->primary_color ?? '#3B82F6' }};
            --primary-rgb: {{ implode(',', sscanf($company->primary_color ?? '#3B82F6', "#%02x%02x%02x")) }};
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: rgba(var(--primary-rgb), 0.9);
            border-color: rgba(var(--primary-rgb), 0.9);
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        .time-slot {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .time-slot:hover {
            background-color: rgba(var(--primary-rgb), 0.1);
            border-color: var(--primary-color);
        }
        
        .time-slot.selected {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .calendar-day {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .calendar-day:hover {
            background-color: rgba(var(--primary-rgb), 0.1);
        }
        
        .calendar-day.selected {
            background-color: var(--primary-color);
            color: white;
        }
        
        .calendar-day.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 1rem;
            background-color: #e9ecef;
            color: #6c757d;
            font-weight: bold;
        }
        
        .step.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed {
            background-color: #28a745;
            color: white;
        }
        
        .step-line {
            width: 60px;
            height: 2px;
            background-color: #e9ecef;
            margin-top: 19px;
        }
        
        .step-line.completed {
            background-color: #28a745;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg" style="background-color: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.profile.show', $company->brand_name) }}">
                @if($company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->company_name }}" height="40" class="me-2">
                @endif
                <span class="logo-text">{{ $company->company_name ?? 'Callbly' }}</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('public.profile.show', $company->brand_name) }}">
                    <i class="fas fa-arrow-left me-1"></i>Back to Profile
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step" id="step-1">1</div>
                    <div class="step-line" id="line-1"></div>
                    <div class="step" id="step-2">2</div>
                    <div class="step-line" id="line-2"></div>
                    <div class="step" id="step-3">3</div>
                </div>

                <!-- Event Type Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px; background-color: {{ $eventType->color }}; border-radius: 12px;">
                                <i class="fas fa-calendar text-white fa-2x"></i>
                            </div>
                        </div>
                        <h3 class="mb-2">{{ $eventType->name }}</h3>
                        <p class="text-muted mb-3">{{ $eventType->description }}</p>
                        <div class="d-flex justify-content-center gap-4 text-sm">
                            <span><i class="fas fa-clock text-primary me-1"></i>{{ $eventType->duration }} minutes</span>
                            <span><i class="fas fa-video text-primary me-1"></i>Google Meet</span>
                            @if($eventType->meeting_type === 'group')
                                <span><i class="fas fa-users text-primary me-1"></i>Up to {{ $eventType->max_attendees }} attendees</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form id="booking-form" method="POST" action="{{ route('public.booking.store', ['brand' => $company->brand_name, 'event' => $eventType->slug]) }}">
                            @csrf
                            
                            <!-- Step 1: Select Date -->
                            <div class="booking-step" id="step-date">
                                <h5 class="mb-4">Select a Date</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="calendar-container">
                                            <!-- Calendar will be generated here -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="mb-3">Selected Date</h6>
                                                <p id="selected-date-display" class="text-muted">Please select a date</p>
                                                
                                                <h6 class="mb-3 mt-4">Time Zone</h6>
                                                <p class="text-muted">{{ $company->timezone ?? 'Africa/Accra' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary" id="next-to-time" disabled>
                                        Next: Select Time <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Select Time -->
                            <div class="booking-step d-none" id="step-time">
                                <h5 class="mb-4">Select a Time</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="time-slots-container">
                                            <!-- Time slots will be loaded here -->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="mb-3">Your Meeting</h6>
                                                <p class="mb-2"><strong>{{ $eventType->name }}</strong></p>
                                                <p class="text-muted mb-2" id="meeting-date-time"></p>
                                                <p class="text-muted">{{ $eventType->duration }} minutes</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" id="back-to-date">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </button>
                                    <button type="button" class="btn btn-primary" id="next-to-details" disabled>
                                        Next: Your Details <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Enter Details -->
                            <div class="booking-step d-none" id="step-details">
                                <h5 class="mb-4">Enter Your Details</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="booker_name" class="form-label">Full Name *</label>
                                                <input type="text" class="form-control" id="booker_name" name="booker_name" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="booker_email" class="form-label">Email Address *</label>
                                                <input type="email" class="form-control" id="booker_email" name="booker_email" required>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="booker_phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="booker_phone" name="booker_phone" placeholder="+233 XX XXX XXXX">
                                        </div>

                                        <!-- Custom Questions -->
                                        @if($eventType->custom_questions && count($eventType->custom_questions) > 0)
                                            <hr class="my-4">
                                            <h6 class="mb-3">Additional Information</h6>
                                            @foreach($eventType->custom_questions as $index => $question)
                                                <div class="mb-3">
                                                    <label for="question_{{ $index }}" class="form-label">
                                                        {{ $question['question'] }}
                                                        @if($question['required'] ?? false) * @endif
                                                    </label>
                                                    
                                                    @if($question['type'] === 'textarea')
                                                        <textarea class="form-control" id="question_{{ $index }}" 
                                                                  name="custom_answers[{{ $index }}]" rows="3"
                                                                  {{ ($question['required'] ?? false) ? 'required' : '' }}></textarea>
                                                    @elseif($question['type'] === 'select')
                                                        <select class="form-select" id="question_{{ $index }}" 
                                                                name="custom_answers[{{ $index }}]"
                                                                {{ ($question['required'] ?? false) ? 'required' : '' }}>
                                                            <option value="">Select an option</option>
                                                            @if(isset($question['options']))
                                                                @foreach($question['options'] as $option)
                                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control" id="question_{{ $index }}" 
                                                               name="custom_answers[{{ $index }}]"
                                                               {{ ($question['required'] ?? false) ? 'required' : '' }}>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Additional Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                      placeholder="Anything else you'd like us to know?"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="mb-3">Meeting Summary</h6>
                                                <p class="mb-2"><strong>{{ $eventType->name }}</strong></p>
                                                <p class="text-muted mb-2" id="final-date-time"></p>
                                                <p class="text-muted mb-2">{{ $eventType->duration }} minutes</p>
                                                <p class="text-muted mb-0">Google Meet link will be sent via email</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary" id="back-to-time">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check me-1"></i> Confirm Booking
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden inputs for selected values -->
                            <input type="hidden" name="selected_date" id="selected_date">
                            <input type="hidden" name="selected_time" id="selected_time">
                        </form>
                    </div>
                </div>

                <!-- Loading State -->
                <div class="card border-0 shadow-sm d-none" id="loading-card">
                    <div class="card-body text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5>Processing your booking...</h5>
                        <p class="text-muted">Please wait while we confirm your meeting.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            let selectedDate = null;
            let selectedTime = null;
            
            // Update step indicators
            function updateStepIndicators() {
                for (let i = 1; i <= 3; i++) {
                    const step = document.getElementById(`step-${i}`);
                    const line = document.getElementById(`line-${i}`);
                    
                    if (i < currentStep) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                        if (line) line.classList.add('completed');
                    } else if (i === currentStep) {
                        step.classList.add('active');
                        step.classList.remove('completed');
                    } else {
                        step.classList.remove('active', 'completed');
                        if (line) line.classList.remove('completed');
                    }
                }
            }
            
            // Show specific step
            function showStep(step) {
                document.querySelectorAll('.booking-step').forEach(el => el.classList.add('d-none'));
                document.getElementById(`step-${step === 1 ? 'date' : step === 2 ? 'time' : 'details'}`).classList.remove('d-none');
                currentStep = step;
                updateStepIndicators();
            }
            
            // Generate calendar
            function generateCalendar() {
                const container = document.getElementById('calendar-container');
                const today = new Date();
                const maxDate = new Date();
                maxDate.setDate(maxDate.getDate() + {{ $availability->days_in_advance ?? 30 }});
                
                let calendarHtml = '<div class="row g-2">';
                
                for (let d = new Date(today); d <= maxDate; d.setDate(d.getDate() + 1)) {
                    const dateStr = d.toISOString().split('T')[0];
                    const dayName = d.toLocaleDateString('en-US', { weekday: 'short' });
                    const dayNum = d.getDate();
                    const isToday = d.toDateString() === today.toDateString();
                    
                    calendarHtml += `
                        <div class="col-6 col-md-4 col-lg-3 mb-2">
                            <div class="calendar-day border rounded p-3 text-center h-100" data-date="${dateStr}">
                                <div class="fw-bold">${dayName}</div>
                                <div class="h4 mb-0">${dayNum}</div>
                                ${isToday ? '<small class="text-primary">Today</small>' : ''}
                            </div>
                        </div>
                    `;
                }
                
                calendarHtml += '</div>';
                container.innerHTML = calendarHtml;
                
                // Add click handlers
                document.querySelectorAll('.calendar-day').forEach(day => {
                    day.addEventListener('click', function() {
                        if (this.classList.contains('disabled')) return;
                        
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                        this.classList.add('selected');
                        
                        selectedDate = this.dataset.date;
                        document.getElementById('selected_date').value = selectedDate;
                        document.getElementById('selected-date-display').textContent = 
                            new Date(selectedDate).toLocaleDateString('en-US', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            });
                        
                        document.getElementById('next-to-time').disabled = false;
                    });
                });
            }
            
            // Load available time slots
            function loadTimeSlots(date) {
                const container = document.getElementById('time-slots-container');
                container.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
                
                // Mock time slots - replace with actual API call
                setTimeout(() => {
                    const timeSlots = [
                        '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                        '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
                    ];
                    
                    let slotsHtml = '<div class="row g-2">';
                    timeSlots.forEach(time => {
                        slotsHtml += `
                            <div class="col-6 col-md-4 col-lg-3 mb-2">
                                <div class="time-slot border rounded p-2 text-center" data-time="${time}">
                                    ${time}
                                </div>
                            </div>
                        `;
                    });
                    slotsHtml += '</div>';
                    
                    container.innerHTML = slotsHtml;
                    
                    // Add click handlers
                    document.querySelectorAll('.time-slot').forEach(slot => {
                        slot.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                            this.classList.add('selected');
                            
                            selectedTime = this.dataset.time;
                            document.getElementById('selected_time').value = selectedTime;
                            
                            const dateTime = new Date(`${selectedDate}T${selectedTime}`);
                            const dateTimeStr = dateTime.toLocaleDateString('en-US', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit'
                            });
                            
                            document.getElementById('meeting-date-time').textContent = dateTimeStr;
                            document.getElementById('final-date-time').textContent = dateTimeStr;
                            document.getElementById('next-to-details').disabled = false;
                        });
                    });
                }, 500);
            }
            
            // Navigation handlers
            document.getElementById('next-to-time').addEventListener('click', () => {
                loadTimeSlots(selectedDate);
                showStep(2);
            });
            
            document.getElementById('back-to-date').addEventListener('click', () => showStep(1));
            
            document.getElementById('next-to-details').addEventListener('click', () => showStep(3));
            
            document.getElementById('back-to-time').addEventListener('click', () => showStep(2));
            
            // Form submission
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                document.querySelector('.card:not(#loading-card)').classList.add('d-none');
                document.getElementById('loading-card').classList.remove('d-none');
                
                // Submit form after delay (simulate processing)
                setTimeout(() => {
                    this.submit();
                }, 2000);
            });
            
            // Initialize
            generateCalendar();
            updateStepIndicators();
        });
    </script>
</body>
</html>