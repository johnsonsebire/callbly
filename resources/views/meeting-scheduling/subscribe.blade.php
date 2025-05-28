@extends('layouts.master')

@section('title', 'Meeting Scheduling Service - Subscribe')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Header -->
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body text-center py-15">
                                <div class="mb-10">
                                    <div class="d-inline-flex align-items-center justify-content-center w-80px h-80px bg-primary rounded-3 mb-7">
                                        <i class="ki-outline ki-calendar text-white fs-2x"></i>
                                    </div>
                                    <h1 class="text-gray-900 mb-3">Meeting Scheduling Service</h1>
                                    <div class="text-gray-600 fw-semibold fs-5">
                                        Create branded scheduling pages and automate your meeting bookings
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Card -->
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <!-- Features List -->
                                        <div class="mb-15">
                                            <h3 class="mb-7">What's Included:</h3>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Branded Scheduling Pages</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Custom URLs like /your-brand/consultation</div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Google Meet Integration</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Automatic meeting links for all bookings</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Smart Notifications</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Email and SMS reminders for both parties</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Multiple Event Types</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Create different meeting types with custom durations</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Availability Management</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Set your available times and buffer periods</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Custom Questions</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Collect additional information during booking</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Company Profile Page</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Professional branded profile with your info</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex flex-stack">
                                                        <div class="d-flex align-items-center me-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                                            <span class="text-gray-800 fw-bold fs-6">Meeting Analytics</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 ps-10">Track bookings, cancellations, and no-shows</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SMS Credit Notice -->
                                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                            <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                                            <div class="d-flex flex-stack flex-grow-1">
                                                <div class="fw-semibold">
                                                    <h4 class="text-gray-900 fw-bold">SMS Notifications</h4>
                                                    <div class="fs-6 text-gray-700">SMS notifications will consume credits from your Callbly SMS balance. Each SMS sent to both parties will be charged according to your current SMS rates.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <!-- Pricing -->
                                        <div class="card border border-gray-300 border-dashed">
                                            <div class="card-body text-center py-10">
                                                <div class="mb-7">
                                                    <span class="text-primary fw-bold fs-1">GHS 500.00</span>
                                                    <span class="text-gray-600 fw-semibold fs-7">/one-time</span>
                                                </div>
                                                
                                                <div class="mb-8">
                                                    <h4 class="fw-bold text-gray-900 mb-3">Lifetime Access</h4>
                                                    <div class="text-gray-600 fw-semibold fs-6">
                                                        Pay once, use forever. No monthly fees or hidden charges.
                                                    </div>
                                                </div>

                                                @if(session('success'))
                                                    <div class="alert alert-success d-flex align-items-center p-5 mb-8">
                                                        <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                                        <div class="d-flex flex-column">
                                                            <h4 class="mb-1 text-success">Payment Successful!</h4>
                                                            <span>{{ session('success') }}</span>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('meeting-scheduling.onboarding') }}" class="btn btn-success w-100">
                                                        <i class="ki-outline ki-arrow-right fs-2 me-2"></i>Continue to Setup
                                                    </a>
                                                @elseif(auth()->user()->hasMeetingSchedulingAccess())
                                                    <div class="alert alert-info d-flex align-items-center p-5 mb-8">
                                                        <i class="ki-outline ki-information fs-2hx text-info me-4"></i>
                                                        <div class="d-flex flex-column">
                                                            <h4 class="mb-1 text-info">Already Subscribed</h4>
                                                            <span>You have access to the Meeting Scheduling Service</span>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('meeting-scheduling.dashboard') }}" class="btn btn-primary w-100">
                                                        <i class="ki-outline ki-element-11 fs-2 me-2"></i>Go to Dashboard
                                                    </a>
                                                @else
                                                    <form id="payment-form" method="POST" action="{{ route('meeting-scheduling.subscribe') }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary w-100" id="pay-button">
                                                            <i class="ki-outline ki-credit-cart fs-2 me-2"></i>Subscribe Now
                                                        </button>
                                                    </form>
                                                @endif

                                                <div class="text-gray-600 fw-semibold fs-7 mt-5">
                                                    Secure payment via Paystack
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Section -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Frequently Asked Questions</h3>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="faqAccordion">
                                    <!-- FAQ Item 1 -->
                                    <div class="accordion-item border-0 mb-5">
                                        <div class="accordion-header" id="faq1">
                                            <button class="accordion-button fs-4 fw-semibold text-gray-900 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1collapse" aria-expanded="false">
                                                How does the one-time payment work?
                                            </button>
                                        </div>
                                        <div id="faq1collapse" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body text-gray-600 fw-semibold fs-6 ps-10">
                                                You pay GHS 500.00 once via Paystack and get lifetime access to the Meeting Scheduling Service. There are no recurring fees or hidden charges. You only pay for SMS credits when sending notifications.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAQ Item 2 -->
                                    <div class="accordion-item border-0 mb-5">
                                        <div class="accordion-header" id="faq2">
                                            <button class="accordion-button fs-4 fw-semibold text-gray-900 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2collapse" aria-expanded="false">
                                                How are SMS notifications charged?
                                            </button>
                                        </div>
                                        <div id="faq2collapse" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body text-gray-600 fw-semibold fs-6 ps-10">
                                                SMS notifications use your existing Callbly SMS credit balance. Each SMS sent to both you and your meeting attendees will consume credits at your current SMS rates. You can top up your SMS balance anytime from your dashboard.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAQ Item 3 -->
                                    <div class="accordion-item border-0 mb-5">
                                        <div class="accordion-header" id="faq3">
                                            <button class="accordion-button fs-4 fw-semibold text-gray-900 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3collapse" aria-expanded="false">
                                                Can I customize my scheduling pages?
                                            </button>
                                        </div>
                                        <div id="faq3collapse" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body text-gray-600 fw-semibold fs-6 ps-10">
                                                Yes! You can create custom branded URLs, upload your logo, set your brand colors, add your company information, and create multiple event types with different durations and requirements.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAQ Item 4 -->
                                    <div class="accordion-item border-0 mb-5">
                                        <div class="accordion-header" id="faq4">
                                            <button class="accordion-button fs-4 fw-semibold text-gray-900 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4collapse" aria-expanded="false">
                                                How does Google Meet integration work?
                                            </button>
                                        </div>
                                        <div id="faq4collapse" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body text-gray-600 fw-semibold fs-6 ps-10">
                                                When someone books a meeting, we automatically generate a Google Meet link and include it in the confirmation emails and SMS notifications sent to both parties. The meeting is also added to your Google Calendar if connected.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAQ Item 5 -->
                                    <div class="accordion-item border-0">
                                        <div class="accordion-header" id="faq5">
                                            <button class="accordion-button fs-4 fw-semibold text-gray-900 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5collapse" aria-expanded="false">
                                                What if I need help setting up?
                                            </button>
                                        </div>
                                        <div id="faq5collapse" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body text-gray-600 fw-semibold fs-6 ps-10">
                                                After subscribing, you'll be guided through a step-by-step onboarding process to set up your company profile and first scheduling page. Our support team is also available to help if you need assistance.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('payment-form');
    const payButton = document.getElementById('pay-button');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable button and show loading state
            payButton.disabled = true;
            payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
            
            // Submit the form
            this.submit();
        });
    }
});
</script>
@endpush
@endsection