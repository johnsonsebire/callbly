@extends('layouts.app')

@section('title', 'Callbly - Modern Cloud Telephony Solutions')

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
                                        <h1 class="fs-2hx fw-bold text-dark mb-5" id="pricing" data-kt-scroll-offset="{default: 100, lg: 150}">Choose Your Plan</h1>
                                        <div class="lead">Select a plan that best suits your business communication needs</div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Row-->
                                    <div class="row g-5 g-lg-10 mb-10">
                                        <!--begin::Col-->
                                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="100">
                                            <div class="card h-100">
                                                <!--begin::Header-->
                                                <div class="card-header text-center">
                                                    <h3 class="card-title fw-bolder text-dark">Starter</h3>
                                                    <div class="card-text text-muted">For small businesses just getting started</div>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="price">{{ app(\App\Services\Currency\CurrencyService::class)->format(5000, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency()) }}</span>
                                                        <span class="period">/month</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <ul class="list-unstyled mt-8 mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">1 Virtual Number</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">100 Minutes Monthly</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Basic Call Analytics</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-cross-circle fs-1 text-danger me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-400">Advanced IVR</span>
                                                        </li>
                                                         <li class="d-flex align-items-center">
                                                            <i class="ki-outline ki-cross-circle fs-1 text-danger me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-400">Bulk SMS</span>
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

                                        <!--begin::Col-->
                                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="200">
                                            <div class="card h-100 popular">
                                                <!--begin::Header-->
                                                <div class="card-header text-center">
                                                    <h3 class="card-title fw-bolder">Business</h3>
                                                    <div class="card-text text-muted">Perfect for growing businesses</div>
                                                    <span class="position-absolute top-0 start-50 translate-middle badge badge-primary">Popular</span>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="price">{{ app(\App\Services\Currency\CurrencyService::class)->format(15000, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency()) }}</span>
                                                        <span class="period">/month</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <ul class="list-unstyled mt-8 mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">5 Virtual Numbers</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">500 Minutes Monthly</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Advanced Call Analytics</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Basic IVR System</span>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Bulk SMS (Add-on)</span>
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

                                        <!--begin::Col-->
                                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                                            <div class="card h-100">
                                                <!--begin::Header-->
                                                <div class="card-header text-center">
                                                    <h3 class="card-title fw-bolder text-dark">Enterprise</h3>
                                                    <div class="card-text text-muted">For large organizations with advanced needs</div>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="price">{{ app(\App\Services\Currency\CurrencyService::class)->format(50000, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency()) }}</span>
                                                        <span class="period">/month</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <ul class="list-unstyled mt-8 mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Unlimited Virtual Numbers</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">2000+ Minutes Monthly</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Custom Call Analytics</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Advanced IVR System</span>
                                                        </li>
                                                        <li class="d-flex align-items-center">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Dedicated Support</span>
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
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
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
