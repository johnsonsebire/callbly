@extends('layouts.app')

@section('title', 'Callbly - Cloud Telephony Services')



@section('hero')
<!--begin::Landing hero-->
					<div class="d-flex flex-column flex-center w-100 min-h-350px min-h-lg-500px px-9">
						<!--begin::Heading-->
						<div class="text-center mb-5 mb-lg-10 py-10 py-lg-20">
							<!--begin::Title-->
							<h1 class="text-white lh-base fw-bold fs-2x fs-lg-3x mb-15">Communication Re-Imagined
							<br />Welcome to  
							<span style="background: linear-gradient(to right, #3ABFF8 0%, #fff 100%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">
								<span id="kt_landing_hero_text">Callbly</span>
							</span></h1>
							<!--end::Title-->
							<!--begin::Action-->
							{{-- <a href="index.html" class="btn btn-primary">Try for Free!</a> --}}
							<!--end::Action-->
						</div>
						<!--end::Heading-->
						<!--begin::Clients-->
						<div class="d-flex flex-center flex-wrap position-relative px-5">
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Fujifilm">
								<img src="assets/media/svg/brand-logos/fujifilm.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Vodafone">
								<img src="assets/media/svg/brand-logos/vodafone.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="KPMG International">
								<img src="assets/media/svg/brand-logos/kpmg.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Nasa">
								<img src="assets/media/svg/brand-logos/nasa.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Aspnetzero">
								<img src="assets/media/svg/brand-logos/aspnetzero.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="AON - Empower Results">
								<img src="assets/media/svg/brand-logos/aon.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Hewlett-Packard">
								<img src="assets/media/svg/brand-logos/hp-3.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
							<!--begin::Client-->
							<div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Truman">
								<img src="assets/media/svg/brand-logos/truman.svg" class="mh-30px mh-lg-40px" alt="" />
							</div>
							<!--end::Client-->
						</div>
						<!--end::Clients-->
					</div>
					<!--end::Landing hero-->
                    @endsection



                    @section('content')
                        <!--begin::Services Section-->
                        <div class="py-10 py-lg-20">
                            <!--begin::Container-->
                            <div class="container">
                                <!--begin::Heading-->
                                <div class="text-center mb-12">
                                    <!--begin::Title-->
                                    <h3 class="fs-2hx text-dark mb-5" id="services" data-kt-scroll-offset="{default: 100, lg: 150}">Our Cloud Telephony Services</h3>
                                    <!--end::Title-->
                                    <!--begin::Sub-title-->
                                    <div class="fs-5 text-muted fw-bold">Callbly provides innovative cloud telephony solutions 
                                    <br />designed to enhance your business communication infrastructure</div>
                                    <!--end::Sub-title=-->
                                </div>
                                <!--end::Heading-->
                                
                                <!--begin::Row-->
                                <div class="row g-10 mb-10">
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <div class="mb-5">
                                                    <i class="ki-outline ki-call fs-3x text-primary mb-5"></i>
                                                    <h3 class="fs-2 fw-bolder mb-3">Virtual Numbers</h3>
                                                    <div class="fs-6 text-gray-600 fw-bold">Get local and international virtual phone numbers for your business with advanced call routing capabilities.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <div class="mb-5">
                                                    <i class="ki-outline ki-tablet-ok fs-3x text-primary mb-5"></i>
                                                    <h3 class="fs-2 fw-bolder mb-3">IVR System</h3>
                                                    <div class="fs-6 text-gray-600 fw-bold">Create customized Interactive Voice Response menus to direct callers efficiently through your call flow.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <div class="mb-5">
                                                    <i class="ki-outline ki-chart-line-star fs-3x text-primary mb-5"></i>
                                                    <h3 class="fs-2 fw-bolder mb-3">Call Analytics</h3>
                                                    <div class="fs-6 text-gray-600 fw-bold">Access detailed call reports and analytics to optimize your team's performance and improve customer service.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="row g-10">
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <div class="mb-5">
                                                    <i class="ki-outline ki-message-text-2 fs-3x text-primary mb-5"></i>
                                                    <h3 class="fs-2 fw-bolder mb-3">Bulk SMS</h3>
                                                    <div class="fs-6 text-gray-600 fw-bold">Reach your customers instantly with our high-throughput bulk SMS messaging platform for marketing or notifications.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <div class="mb-5">
                                                    <i class="ki-outline ki-badge fs-3x text-primary mb-5"></i>
                                                    <h3 class="fs-2 fw-bolder mb-3">Sender Names</h3>
                                                    <div class="fs-6 text-gray-600 fw-bold">Personalize your SMS campaigns with custom sender names that reflect your brand identity to recipients.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <div class="mb-5">
                                                    <i class="ki-outline ki-phone fs-3x text-primary mb-5"></i>
                                                    <h3 class="fs-2 fw-bolder mb-3">USSD Solutions</h3>
                                                    <div class="fs-6 text-gray-600 fw-bold">Build interactive USSD applications to engage with customers directly on their mobile devices without internet access.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Services Section-->

                        <!--begin::Pricing Section-->
                        <div class="py-10 py-lg-20 bg-light">
                            <!--begin::Container-->
                            <div class="container">
                                <!--begin::Plans-->
                                <div class="d-flex flex-column container">
                                    <!--begin::Heading-->
                                    <div class="mb-13 text-center">
                                        <h1 class="fs-2hx fw-bold text-dark mb-5" id="pricing" data-kt-scroll-offset="{default: 100, lg: 150}">Choose Your Plan</h1>
                                        <div class="text-gray-600 fw-semibold fs-5">Select a plan that best suits your business communication needs</div>
                                    </div>
                                    <!--end::Heading-->

                                    <!--begin::Row-->
                                    <div class="row g-10 mb-10">
                                        <!--begin::Col-->
                                        <div class="col-xl-4">
                                            <div class="card card-stretch h-100 shadow">
                                                <!--begin::Header-->
                                                <div class="card-header text-center pt-10 bg-light">
                                                    <h3 class="card-title fw-bolder text-dark fs-2x">Starter</h3>
                                                    <br>
                                                    <div class="card-text text-muted">For small businesses just getting started</div>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="fs-3x fw-bold text-primary">{{ app(\App\Services\Currency\CurrencyService::class)->format(5000, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency()) }}</span>
                                                        <span class="fs-7 fw-semibold opacity-50">/month</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <div class="pt-8">
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">1 Virtual Number</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">100 Minutes Monthly</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Basic Call Analytics</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-cross-circle fs-1 text-danger me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-400">Advanced IVR</span>
                                                        </div>
                                                        <!--end::Item-->
                                                    </div>
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
                                        <div class="col-xl-4">
                                            <div class="card card-stretch h-100 shadow border border-primary border-2">
                                                <!--begin::Header-->
                                                <div class="card-header text-center pt-10 bg-primary">
                                                    <h3 class="card-title fw-bolder text-white fs-2x">Business</h3>
                                                    <br>
                                                    <div class="card-text text-white-75">Perfect for growing businesses</div>
                                                    <span class="position-absolute top-0 start-50 translate-middle badge badge-primary">Popular</span>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="fs-3x fw-bold text-primary">{{ app(\App\Services\Currency\CurrencyService::class)->format(15000, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency()) }}</span>
                                                        <span class="fs-7 fw-semibold opacity-50">/month</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <div class="pt-8">
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">5 Virtual Numbers</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">500 Minutes Monthly</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Advanced Call Analytics</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Basic IVR System</span>
                                                        </div>
                                                        <!--end::Item-->
                                                    </div>
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
                                        <div class="col-xl-4">
                                            <div class="card card-stretch h-100 shadow">
                                                <!--begin::Header-->
                                                <div class="card-header text-center pt-10 bg-light">
                                                    <h3 class="card-title fw-bolder text-dark fs-2x">Enterprise</h3>
                                                    <br>
                                                    <div class="card-text text-muted">For large organizations with advanced needs</div>
                                                </div>
                                                <!--end::Header-->

                                                <!--begin::Card body-->
                                                <div class="card-body pt-10">
                                                    <!--begin::Price-->
                                                    <div class="text-center">
                                                        <span class="fs-3x fw-bold text-primary">{{ app(\App\Services\Currency\CurrencyService::class)->format(50000, app(\App\Services\Currency\CurrencyService::class)->getDefaultCurrency()) }}</span>
                                                        <span class="fs-7 fw-semibold opacity-50">/month</span>
                                                    </div>
                                                    <!--end::Price-->

                                                    <!--begin::Features-->
                                                    <div class="pt-8">
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Unlimited Virtual Numbers</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">2000+ Minutes Monthly</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Custom Call Analytics</span>
                                                        </div>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <div class="d-flex align-items-center mb-5">
                                                            <i class="ki-outline ki-check-circle fs-1 text-success me-2"></i>
                                                            <span class="fw-semibold fs-6 text-gray-800">Advanced IVR System</span>
                                                        </div>
                                                        <!--end::Item-->
                                                    </div>
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
                                <div class="text-center mb-12">
                                    <!--begin::Title-->
                                    <h3 class="fs-2hx text-dark mb-5" id="testimonials" data-kt-scroll-offset="{default: 100, lg: 150}">What Our Clients Say</h3>
                                    <!--end::Title-->
                                    <!--begin::Sub-title-->
                                    <div class="fs-5 text-muted fw-bold">Hear from businesses that have transformed their communication with Callbly</div>
                                    <!--end::Sub-title=-->
                                </div>
                                <!--end::Heading-->

                                <!--begin::Testimonials-->
                                <div class="row g-10">
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <!--begin::Testimonial-->
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <!--begin::Rating-->
                                                <div class="d-flex mb-6">
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                </div>
                                                <!--end::Rating-->
                                                
                                                <!--begin::Content-->
                                                <p class="fw-semibold fs-6 text-gray-600 mb-6">"Callbly has revolutionized how we handle customer calls. The virtual number system and call routing have made our small team appear much larger and more professional to clients."</p>
                                                <!--end::Content-->
                                                
                                                <!--begin::Author-->
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-50px me-5">
                                                        <img src="assets/media/avatars/300-1.jpg" class="" alt="" />
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Michael Johnson</a>
                                                        <span class="text-muted d-block fw-semibold">TechSmart Solutions</span>
                                                    </div>
                                                </div>
                                                <!--end::Author-->
                                            </div>
                                        </div>
                                        <!--end::Testimonial-->
                                    </div>
                                    <!--end::Col-->
                                    
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <!--begin::Testimonial-->
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <!--begin::Rating-->
                                                <div class="d-flex mb-6">
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                </div>
                                                <!--end::Rating-->
                                                
                                                <!--begin::Content-->
                                                <p class="fw-semibold fs-6 text-gray-600 mb-6">"The analytics and reporting features have given us insights we never had before. We've been able to optimize our call handling and significantly improve customer satisfaction as a result."</p>
                                                <!--end::Content-->
                                                
                                                <!--begin::Author-->
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-50px me-5">
                                                        <img src="assets/media/avatars/300-2.jpg" class="" alt="" />
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Sarah Williams</a>
                                                        <span class="text-muted d-block fw-semibold">Global Connections Ltd</span>
                                                    </div>
                                                </div>
                                                <!--end::Author-->
                                            </div>
                                        </div>
                                        <!--end::Testimonial-->
                                    </div>
                                    <!--end::Col-->
                                    
                                    <!--begin::Col-->
                                    <div class="col-lg-4">
                                        <!--begin::Testimonial-->
                                        <div class="card card-stretch shadow-sm h-100">
                                            <div class="card-body p-10">
                                                <!--begin::Rating-->
                                                <div class="d-flex mb-6">
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                    <i class="ki-outline ki-star text-warning fs-6 me-1"></i>
                                                </div>
                                                <!--end::Rating-->
                                                
                                                <!--begin::Content-->
                                                <p class="fw-semibold fs-6 text-gray-600 mb-6">"Setting up our custom IVR was incredibly easy with Callbly. Our customers now get directed to the right department immediately, and our call handling efficiency has increased by over 40%."</p>
                                                <!--end::Content-->
                                                
                                                <!--begin::Author-->
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-circle symbol-50px me-5">
                                                        <img src="assets/media/avatars/300-5.jpg" class="" alt="" />
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="#" class="text-dark fw-bold text-hover-primary fs-6">David Akpan</a>
                                                        <span class="text-muted d-block fw-semibold">Rapid Logistics</span>
                                                    </div>
                                                </div>
                                                <!--end::Author-->
                                            </div>
                                        </div>
                                        <!--end::Testimonial-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Testimonials-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::Testimonials Section-->
                        
                        <!--begin::CTA Section-->
                        <div class="py-10 py-lg-20 bg-light">
                            <!--begin::Container-->
                            <div class="container">
                                <!--begin::Highlight-->
                                <div class="d-flex flex-stack flex-wrap flex-md-nowrap card-rounded shadow p-8 p-lg-12" style="background: linear-gradient(90deg, #3ABFF8 0%, #0078AA 100%);">
                                    <!--begin::Content-->
                                    <div class="my-2 me-5">
                                        <!--begin::Title-->
                                        <div class="fs-1 fs-lg-2qx fw-bold text-white mb-2">Ready to Transform Your Business Communication?</div>
                                        <!--end::Title-->
                                        <!--begin::Description-->
                                        <div class="fs-6 fs-lg-5 text-white fw-semibold opacity-75">Join thousands of businesses using Callbly's cloud telephony solutions</div>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Content-->
                                    <!--begin::Link-->
                                    <a href="{{ route('register') }}" class="btn btn-lg btn-outline border-2 btn-outline-white flex-shrink-0 my-2 text-white">Get Started Today</a>
                                    <!--end::Link-->
                                </div>
                                <!--end::Highlight-->
                            </div>
                            <!--end::Container-->
                        </div>
                        <!--end::CTA Section-->
                    @endsection
