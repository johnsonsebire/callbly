@extends('layouts.app')

@section('title', 'Callbly - Modern Cloud Telephony Solutions')

@push('styles')
<style>
/* Hero Button White Text Styling */
.btn-primary-modern {
    background: linear-gradient(135deg, #3ABFF8 0%, #1E40AF 100%);
    border: none;
    color: #ffffff !important;
    font-weight: 600;
    padding: 12px 32px;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(58, 191, 248, 0.3);
}

.btn-primary-modern:hover {
    background: linear-gradient(135deg, #22D3EE 0%, #1D4ED8 100%);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(58, 191, 248, 0.4);
}

.btn-primary-modern:focus,
.btn-primary-modern:active {
    color: #ffffff !important;
    background: linear-gradient(135deg, #0EA5E9 0%, #1E3A8A 100%);
    box-shadow: 0 2px 10px rgba(58, 191, 248, 0.5);
}

/* Mobile responsive adjustments for auth buttons */
@media (max-width: 991.98px) {
    .flex-equal .d-flex {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .flex-equal .d-flex a {
        font-size: 14px;
        padding: 8px 16px;
        min-width: auto;
        white-space: nowrap;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }
}

@media (max-width: 575.98px) {
    .flex-equal .d-flex {
        justify-content: center !important;
        margin-top: 8px;
    }
    
    .flex-equal .d-flex a {
        font-size: 13px;
        padding: 6px 12px;
    }
}
</style>
@endpush

@section('hero')
<!--begin::Landing hero-->
					<div class="d-flex flex-column flex-center w-100 min-h-350px min-h-lg-500px px-9 hero-section-modern">
						<!--begin::Heading-->
						<div class="text-center mb-5 mb-lg-10 py-10 py-lg-15">
							<!--begin::Title-->
							<h1 class="text-white lh-base fw-bold mb-7" data-aos="fade-down">Communication Re-Imagined.
							<br />Experience  
							<span style="background: linear-gradient(to right, #3ABFF8 0%, #fff 100%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">
								<span id="kt_landing_hero_text">Callbly</span>
							</span></h1>
							<!--end::Title-->
							<!--begin::Sub-title-->
							<p class="lead mb-8" data-aos="fade-up" data-aos-delay="100">
								Empowering your business with seamless, intelligent, and scalable cloud communication services.
							</p>
							<!--end::Sub-title-->
							<!--begin::Action-->
							<a href="{{ route('register') }}" class="btn btn-primary-modern" data-aos="fade-up" data-aos-delay="200">Get Started Free</a>
							<!--end::Action-->
						</div>
						<!--end::Heading-->
						<!--begin::Clients-->
						<div class="d-flex flex-center flex-wrap position-relative px-5">
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Fujifilm">
								<img src="{{ asset('assets/media/svg/brand-logos/fujifilm.svg') }}" class="mh-30px mh-lg-40px" alt="Fujifilm" data-aos="fade-up" data-aos-delay="300"/>
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Vodafone">
								<img src="{{ asset('assets/media/svg/brand-logos/vodafone.svg') }}" class="mh-30px mh-lg-40px" alt="Vodafone" data-aos="fade-up" data-aos-delay="350"/>
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="KPMG International">
								<img src="{{ asset('assets/media/svg/brand-logos/kpmg.svg') }}" class="mh-30px mh-lg-40px" alt="KPMG" data-aos="fade-up" data-aos-delay="400"/>
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Nasa">
								<img src="{{ asset('assets/media/svg/brand-logos/nasa.svg') }}" class="mh-30px mh-lg-40px" alt="Nasa" data-aos="fade-up" data-aos-delay="450"/>
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Aspnetzero">
								<img src="{{ asset('assets/media/svg/brand-logos/aspnetzero.svg') }}" class="mh-30px mh-lg-40px" alt="Aspnetzero" data-aos="fade-up" data-aos-delay="500"/>
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="AON - Empower Results">
								<img src="{{ asset('assets/media/svg/brand-logos/aon.svg') }}" class="mh-30px mh-lg-40px" alt="AON" data-aos="fade-up" data-aos-delay="550"/>
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Hewlett-Packard">
								<img src="{{ asset('assets/media/svg/brand-logos/hp-3.svg') }}" class="mh-30px mh-lg-40px" alt="HP" data-aos="fade-up" data-aos-delay="600"/>
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Truman">
								<img src="{{ asset('assets/media/svg/brand-logos/truman.svg') }}" class="mh-30px mh-lg-40px" alt="Truman" data-aos="fade-up" data-aos-delay="650"/>
							</div>
							<!--end::Client-->
						</div>
						<!--end::Clients-->
					</div>
					<!--end::Landing hero-->
@endsection

                    @section('content')
                        <!--begin::How It Works Section-->
                        <div class="py-10 py-lg-20">
                            <div class="container">
                                <div class="text-center mb-12 section-title" data-aos="fade-up">
                                    <h2 class="text-dark">How Callbly Works</h2>
                                    <p class="lead">Get started in just a few simple steps.</p>
                                </div>
                                <div class="row g-5 g-lg-10 text-center">
                                    <!-- Step 1 -->
                                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                                        <div class="feature-card-modern p-lg-5 p-4">
                                            <div class="icon-box mx-auto fs-1">
                                                <i class="ki-outline ki-user fs-2x text-primary"></i>
                                            </div>
                                            <h4>1. Sign Up Free</h4>
                                            <p class="text-muted fs-6">Create your Callbly account in minutes, no credit card required.</p>
                                        </div>
                                    </div>
                                    <!-- Step 2 -->
                                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                                        <div class="feature-card-modern p-lg-5 p-4">
                                            <div class="icon-box mx-auto fs-1">
                                                <i class="ki-outline ki-gear fs-2x text-primary"></i>
                                            </div>
                                            <h4>2. Configure Services</h4>
                                            <p class="text-muted fs-6">Easily set up virtual numbers, IVR, and other communication tools.</p>
                                        </div>
                                    </div>
                                    <!-- Step 3 -->
                                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                                        <div class="feature-card-modern p-lg-5 p-4">
                                            <div class="icon-box mx-auto fs-1">
                                                <i class="ki-outline ki-rocket fs-2x text-primary"></i>
                                            </div>
                                            <h4>3. Connect & Grow</h4>
                                            <p class="text-muted fs-6">Start communicating smarter and watch your business thrive.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::How It Works Section-->

                        <!--begin::Services Section-->
        <div class="py-10 py-lg-20">
            <div class="container">
                <div class="text-center mb-12 section-title" data-aos="fade-up">
                    <h3 class="fs-2hx text-dark mb-5" id="services" data-kt-scroll-offset="{default: 100, lg: 150}">Our Cloud Telephony Services</h3>
                    <div class="lead">Callbly provides innovative cloud telephony solutions <br />designed to enhance your business communication infrastructure</div>
                </div>
                <!-- Modern grid for services -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
                    <div class="col">
                        <div class="card h-100 border-1 text-center p-4">
                            <div class="d-flex align-items-center justify-content-center mb-3 mx-auto bg-light-primary rounded-circle" style="width:64px;height:64px;">
                                <i class="ki-outline ki-call fs-2x text-primary"></i>
                            </div>
                            <h4 class="fs-4 fw-bold mb-2">Virtual Numbers</h4>
                            <p class="text-muted">Get local and international virtual phone numbers for your business with customized instant routing.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-1 text-center p-4">
                            <div class="d-flex align-items-center justify-content-center mb-3 mx-auto bg-light-primary rounded-circle" style="width:64px;height:64px;">
                                <i class="ki-outline ki-message-text-2 fs-2x text-primary"></i>
                            </div>
                            <h4 class="fs-4 fw-bold mb-2">Bulk SMS</h4>
                            <p class="text-muted">High-throughput bulk SMS messaging platform for marketing or notifications.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-1 text-center p-4">
                            <div class="d-flex align-items-center justify-content-center mb-3 mx-auto bg-light-primary rounded-circle" style="width:64px;height:64px;">
                                <i class="ki-outline ki-tablet-ok fs-2x text-primary"></i>
                            </div>
                            <h4 class="fs-4 fw-bold mb-2">IVR System</h4>
                            <p class="text-muted">Create customized Interactive Voice Response menus to direct callers efficiently.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-1 text-center p-4">
                            <div class="d-flex align-items-center justify-content-center mb-3 mx-auto bg-light-primary rounded-circle" style="width:64px;height:64px;">
                                <i class="ki-outline ki-chart-line-star fs-2x text-primary"></i>
                            </div>
                            <h4 class="fs-4 fw-bold mb-2">Call Analytics</h4>
                            <p class="text-muted">Access detailed call reports and analytics to optimize your team's performance.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-1 text-center p-4">
                            <div class="d-flex align-items-center justify-content-center mb-3 mx-auto bg-light-primary rounded-circle" style="width:64px;height:64px;">
                                <i class="ki-outline ki-badge fs-2x text-primary"></i>
                            </div>
                            <h4 class="fs-4 fw-bold mb-2">Sender Names</h4>
                            <p class="text-muted">Custom sender names that reflect your brand identity to recipients.</p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 border-1 text-center p-4">
                            <div class="d-flex align-items-center justify-content-center mb-3 mx-auto bg-light-primary rounded-circle" style="width:64px;height:64px;">
                                <i class="ki-outline ki-phone fs-2x text-primary"></i>
                            </div>
                            <h4 class="fs-4 fw-bold mb-2">USSD Solutions</h4>
                            <p class="text-muted">Build interactive USSD applications to engage with customers directly on their mobile devices.</p>
                        </div>
                    </div>
                </div>
                <!-- End modern grid -->
            </div>
        </div>
        <!--end::Services Section-->

                        <!--begin::Pricing Section-->
                        <div class="py-10 py-lg-20 bg-light">
                            <!--begin::Container-->
                            <div class="container">
                                <!--begin::Plans-->
                                <div class="d-flex flex-column container pricing-table-modern">
                                    <!--begin::Heading-->
                                    <div class="mb-13 text-center section-title" data-aos="fade-up">
                                        <h1 class="fs-2hx fw-bold text-dark mb-5" id="pricing" data-kt-scroll-offset="{default: 100, lg: 150}">SMS Billing Tiers</h1>
                                        <div class="lead">Enjoy better rates as you send more SMS messages</div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Row-->
                                    <div class="row g-5 g-lg-10 mb-10">
                                        <!--begin::Col - Basic Tier-->
                                        <div class="col-xl-3" data-aos="fade-up" data-aos-delay="100">
                                            <div class="card h-100">
                                                <!--begin::Header-->
                                                <div class="card-header text-center">
                                                    <h3 class="card-title fw-bolder text-dark">Basic</h3>
                                                    <div class="card-text text-muted">For businesses just getting started</div>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="price">{{ app(\App\Services\Currency\CurrencyService::class)->format(0.035, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency(), true, 3) }}</span>
                                                        <span class="period">/SMS</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <ul class="list-unstyled mt-8 mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">All SMS Features</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Custom Sender Names</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Delivery Reports</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">API Access</span>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <i class="ki-outline ki-information fs-1 text-primary me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-600">Purchase: ₵0 - ₵1,499</span>
                                                        </li>
                                                    </ul>
                                                    <!--end::Features-->

                                                    <!--begin::Action-->
                                                    <div class="text-center mt-8">
                                                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                                                    </div>
                                                    <!--end::Action-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col - Plus Tier-->
                                        <div class="col-xl-3" data-aos="fade-up" data-aos-delay="200">
                                            <div class="card h-100">
                                                <!--begin::Header-->
                                                <div class="card-header text-center">
                                                    <h3 class="card-title fw-bolder text-dark">Plus</h3>
                                                    <div class="card-text text-muted">Better rates for regular users</div>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="price">{{ app(\App\Services\Currency\CurrencyService::class)->format(0.032, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency(), true, 3) }}</span>
                                                        <span class="period">/SMS</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <ul class="list-unstyled mt-8 mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">All Basic Features</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">8.6% Cost Savings</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Priority Support</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Advanced Analytics</span>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <i class="ki-outline ki-information fs-1 text-primary me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-600">Purchase: ₵1,500 - ₵2,999</span>
                                                        </li>
                                                    </ul>
                                                    <!--end::Features-->

                                                    <!--begin::Action-->
                                                    <div class="text-center mt-8">
                                                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                                                    </div>
                                                    <!--end::Action-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col - Premium Tier-->
                                        <div class="col-xl-3" data-aos="fade-up" data-aos-delay="300">
                                            <div class="card h-100 popular">
                                                <!--begin::Header-->
                                                <div class="card-header text-center">
                                                    <h3 class="card-title fw-bolder">Premium</h3>
                                                    <div class="card-text text-muted">Great value for high volume</div>
                                                    <span class="position-absolute top-0 start-50 translate-middle badge badge-primary">Popular</span>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="price">{{ app(\App\Services\Currency\CurrencyService::class)->format(0.029, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency(), true, 3) }}</span>
                                                        <span class="period">/SMS</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <ul class="list-unstyled mt-8 mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">All Plus Features</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">17% Cost Savings</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Dedicated Account Manager</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Custom Integrations</span>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <i class="ki-outline ki-information fs-1 text-primary me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-600">Purchase: ₵3,000 - ₵5,999</span>
                                                        </li>
                                                    </ul>
                                                    <!--end::Features-->

                                                    <!--begin::Action-->
                                                    <div class="text-center mt-8">
                                                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                                                    </div>
                                                    <!--end::Action-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col - Gold Tier-->
                                        <div class="col-xl-3" data-aos="fade-up" data-aos-delay="400">
                                            <div class="card h-100">
                                                <!--begin::Header-->
                                                <div class="card-header text-center">
                                                    <h3 class="card-title fw-bolder text-dark">Gold</h3>
                                                    <div class="card-text text-muted">Best rates for enterprise</div>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="price">{{ app(\App\Services\Currency\CurrencyService::class)->format(0.025, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency(), true, 3) }}</span>
                                                        <span class="period">/SMS</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <ul class="list-unstyled mt-8 mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">All Premium Features</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">29% Cost Savings</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">24/7 Premium Support</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">SLA Guarantee</span>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <i class="ki-outline ki-information fs-1 text-primary me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-600">Purchase: ₵6,000+</span>
                                                        </li>
                                                    </ul>
                                                    <!--end::Features-->

                                                    <!--begin::Action-->
                                                    <div class="text-center mt-8">
                                                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                                                    </div>
                                                    <!--end::Action-->
                                                </div>
                                                <!--end::Card body-->
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->

                                    <!--begin::Tier Info-->
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8">
                                            <div class="alert alert-info d-flex align-items-center p-5">
                                                <i class="ki-outline ki-information-5 fs-2tx text-info me-4"></i>
                                                <div class="d-flex flex-column">
                                                    <h4 class="mb-1 text-info">How It Works</h4>
                                                    <span>Your billing tier is automatically upgraded based on your purchase volume. Once upgraded, you maintain your tier level for all future SMS sending.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Tier Info-->
                                </div>
                                <!--end::Plans-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Pricing Section-->
                        
                        <!--begin::Testimonials Section-->
                        <div class="py-10 py-lg-20">
                            <!--begin::Container-->
                            <div class="container">
                                <!--begin::Heading-->
                                <div class="text-center mb-12 section-title" data-aos="fade-up">
                                    <!--begin::Title-->
                                    <h3 class="fs-2hx text-dark mb-5" id="testimonials" data-kt-scroll-offset="{default: 100, lg: 150}">What Our Clients Say</h3>
                                    <!--end::Title-->
                                    <!--begin::Sub-title-->
                                    <div class="lead">Hear from businesses that have transformed their communication with Callbly</div>
                                    <!--end::Sub-title=-->
                                </div>
                                <!--end::Heading-->

                                <!--begin::Testimonials-->
                                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up" data-aos-delay="100">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-8">
                                                    <div class="testimonial-card-modern">
                                                        <img src="{{ asset('assets/media/avatars/300-1.jpg') }}" class="avatar" alt="Michael Johnson" />
                                                        <div class="stars mb-3">
                                                            <i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i>
                                                        </div>
                                                        <p class="quote">"Callbly has revolutionized how we handle customer calls. The virtual number system and call routing have made our small team appear much larger and more professional to clients."</p>
                                                        <div class="name">Michael Johnson</div>
                                                        <div class="company">TechSmart Solutions</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                             <div class="row justify-content-center">
                                                <div class="col-lg-8">
                                                    <div class="testimonial-card-modern">
                                                        <img src="{{ asset('assets/media/avatars/300-2.jpg') }}" class="avatar" alt="Sarah Williams" />
                                                        <div class="stars mb-3">
                                                            <i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i>
                                                        </div>
                                                        <p class="quote">"The analytics and reporting features have given us insights we never had before. We've been able to optimize our call handling and significantly improve customer satisfaction."</p>
                                                        <div class="name">Sarah Williams</div>
                                                        <div class="company">Global Connections Ltd</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                             <div class="row justify-content-center">
                                                <div class="col-lg-8">
                                                    <div class="testimonial-card-modern">
                                                        <img src="{{ asset('assets/media/avatars/300-5.jpg') }}" class="avatar" alt="David Akpan" />
                                                        <div class="stars mb-3">
                                                           <i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star fs-6 me-1"></i><i class="ki-outline ki-star-half fs-6 me-1"></i>
                                                        </div>
                                                        <p class="quote">"Setting up our custom IVR was incredibly easy with Callbly. Our customers now get directed to the right department immediately, increasing efficiency by over 40%."</p>
                                                        <div class="name">David Akpan</div>
                                                        <div class="company">Rapid Logistics</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                                        <i class="ki-outline ki-arrow-left" aria-hidden="true"></i>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                                        <i class="ki-outline ki-arrow-right" aria-hidden="true"></i>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                                <!--end::Testimonials-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Testimonials Section-->
                        
                        <!--begin::CTA Section-->
                        <div class="py-10 py-lg-20">
                            <!--begin::Container-->
                            <div class="container">
                                <!--begin::Highlight-->
                                <div class="cta-section-modern text-center" data-aos="fade-up">
                                    <!--begin::Content-->
                                    <div class="my-2">
                                        <!--begin::Title-->
                                        <h2 class="mb-4">Ready to Transform Your Business Communication?</h2>
                                        <!--end::Title-->
                                        <!--begin::Description-->
                                        <p class="mb-8">Join thousands of businesses using Callbly's cloud telephony solutions to enhance customer interactions and drive growth.</p>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Content-->
                                    <!--begin::Link-->
                                    <a href="{{ route('register') }}" class="btn btn-cta-modern">Sign Up & Explore</a>
                                    <!--end::Link-->
                                </div>
                                <!--end::Highlight-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::CTA Section-->

                        <!--begin::Contact Section-->
                        <div class="py-10 py-lg-20 bg-white" id="contact">
                            <div class="container">
                                <div class="text-center mb-12 section-title" data-aos="fade-up">
                                    <h2 class="text-dark">Contact Us</h2>
                                    <p class="lead">Have questions or need support? Reach out to our team.</p>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        {{-- Display success/error messages --}}
                                        @if(session('success'))
                                            <div class="alert alert-success d-flex align-items-center p-5 mb-10" data-aos="fade-up">
                                                <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                                <div class="d-flex flex-column">
                                                    <h4 class="mb-1 text-success">Success</h4>
                                                    <span>{{ session('success') }}</span>
                                                </div>
                                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                    <i class="ki-outline ki-cross fs-2 text-success"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if(session('error'))
                                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10" data-aos="fade-up">
                                                <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                                                <div class="d-flex flex-column">
                                                    <h4 class="mb-1 text-danger">Error</h4>
                                                    <span>{{ session('error') }}</span>
                                                </div>
                                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                    <i class="ki-outline ki-cross fs-2 text-danger"></i>
                                                </button>
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('contact.send') }}" class="card p-5 border-1 bg-white">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="name" class="form-label">Your Name</label>
                                                <input type="text" class="form-control form-control-lg" id="name" placeholder="Your Name" name="name" required>
                                            </div>
                                            <div class="mb-4">
                                                <label for="email" class="form-label">Your Email</label>
                                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Your Email" required>
                                            </div>
                                            <div class="mb-4">
                                                <label for="message" class="form-label">Message</label>
                                                <textarea class="form-control form-control-lg" id="message" name="message" rows="5" placeholder="Your message" required></textarea>
                                            </div>

                                            @recaptcha

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-lg px-5">Send Message</button>
                                            </div>
                                            <div class="mt-3 text-center text-muted" style="font-size: 0.95rem;">
                                                Or email us directly at <a href="mailto:support@callbly.com">support@callbly.com</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Contact Section-->
@endsection
