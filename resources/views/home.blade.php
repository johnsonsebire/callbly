<div class="card-title">@extends('layouts.frontend')

@section('title', 'Callbly - Cloud Telephony Services')

@section('content')
<!-- Hero Section -->
<div class="container py-5">
    <div class="row align-items-center min-vh-60">
        <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
            <div class="position-relative">
                <div class="position-absolute top-0 start-0 translate-middle-y opacity-10 w-75 h-75 bg-primary rounded-circle filter blur-3x"></div><div class="card-title"></div><div class="card-title"></div>
                <h1 class="display-4 fw-bolder mb-3 position-relative">
                    <span style="color: #0078d4; font-size: 20px;">Cloud</span> <span style="color: #212121; font-size: 20px;">Telephony</span>
                    <br>
                    <span class="gradient-text" style="background: linear-gradient(to right, #0078d4, #00a2ed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 3rem;">Made Simple</span>
                </h1>
            </div><div class="card-title"></div><div class="card-title"></div>
            <p class="lead text-secondary mb-4">
                Transform your communication strategy with our powerful SMS and USSD solutions. Reach your audience instantly with our reliable, scalable platform.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <a href="#pricing" class="btn btn-primary btn-lg px-5 shadow-sm hover-elevate-up">Get Started</a>
                <a href="#services" class="btn btn-outline-primary btn-lg px-5">Learn More</a>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
        <div class="col-lg-6 text-center">
            <div class="position-relative">
                <div class="position-absolute top-50 start-50 translate-middle opacity-10 w-75 h-75 bg-info rounded-circle filter blur-3x"></div><div class="card-title"></div><div class="card-title"></div>
                <img src="{{ asset('assets/media/svg/illustrations/landing.svg') }}" class="img-fluid position-relative hover-elevate-up animation-float" alt="Callbly Hero Illustration" style="max-height: 400px;">
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>

    <!-- Partners/Clients Section -->
    <div class="d-flex flex-center flex-wrap position-relative px-5 my-10 py-5">
        <h6 class="text-center text-muted fw-semibold mb-5 w-100">Trusted by industry leaders</h6>
        <div class="d-flex flex-center m-3 m-md-6 opacity-75" data-bs-toggle="tooltip" title="Client">
            <img src="{{ asset('assets/media/svg/brand-logos/vodafone.svg') }}" class="mh-30px mh-lg-40px" alt="Client" />
        </div><div class="card-title"></div><div class="card-title"></div>
        <div class="d-flex flex-center m-3 m-md-6 opacity-75" data-bs-toggle="tooltip" title="Client">
            <img src="{{ asset('assets/media/svg/brand-logos/aon.svg') }}" class="mh-30px mh-lg-40px" alt="Client" />
        </div><div class="card-title"></div><div class="card-title"></div>
        <div class="d-flex flex-center m-3 m-md-6 opacity-75" data-bs-toggle="tooltip" title="Client">
            <img src="{{ asset('assets/media/svg/brand-logos/klarna.svg') }}" class="mh-30px mh-lg-40px" alt="Client" />
        </div><div class="card-title"></div><div class="card-title"></div>
        <div class="d-flex flex-center m-3 m-md-6 opacity-75" data-bs-toggle="tooltip" title="Client">
            <img src="{{ asset('assets/media/svg/brand-logos/kpmg.svg') }}" class="mh-30px mh-lg-40px" alt="Client" />
        </div><div class="card-title"></div><div class="card-title"></div>
        <div class="d-flex flex-center m-3 m-md-6 opacity-75" data-bs-toggle="tooltip" title="Client">
            <img src="{{ asset('assets/media/svg/brand-logos/hp-3.svg') }}" class="mh-30px mh-lg-40px" alt="Client" />
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>

    <!-- Services Section -->
    <div class="text-center my-10" id="services">
        <h2 class="fs-1 text-gray-900 mb-3">Our Services</h2>
        <div class="fs-5 text-muted fw-bold mb-10 px-lg-15">Comprehensive communication solutions for businesses of all sizes</div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>

    <div class="row text-center g-5 mt-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm hover-elevate-up border-0 card-rounded">
                <div class="card-body py-5">
                    <div class="mb-5">
                        <span class="svg-icon svg-icon-primary svg-icon-3x">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M8 8C8 7.4 8.4 7 9 7H16V3C16 2.4 15.6 2 15 2H3C2.4 2 2 2.4 2 3V13C2 13.6 2.4 14 3 14H5V16.1C5 16.8 5.79999 17.1 6.29999 16.6L8 14.9V8Z" fill="currentColor"/>
                                <path d="M22 8V18C22 18.6 21.6 19 21 19H19V21.1C19 21.8 18.2 22.1 17.7 21.6L15 18.9H9C8.4 18.9 8 18.5 8 17.9V7.90002C8 7.30002 8.4 6.90002 9 6.90002H21C21.6 7.00002 22 7.4 22 8ZM19 11C19 10.4 18.6 10 18 10H12C11.4 10 11 10.4 11 11C11 11.6 11.4 12 12 12H18C18.6 12 19 11.6 19 11ZM17 15C17 14.4 16.6 14 16 14H12C11.4 14 11 14.4 11 15C11 15.6 11.4 16 12 16H16C16.6 16 17 15.6 17 15Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <h3 class="fs-2 fw-bold mb-4">Bulk SMS</h3>
                    <p class="text-muted fs-6 px-lg-5 mb-5">Send thousands of messages instantly to your customers. Perfect for marketing campaigns, notifications, and alerts.</p>
                    <div class="bg-light rounded p-4 mb-5">
                        <ul class="list-unstyled text-start mx-auto" style="max-width: 300px;">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-3"></i>
                                <span>High delivery rates</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-3"></i>
                                <span>Customizable sender ID</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-3"></i>
                                <span>Detailed analytics</span>
                            </li>
                        </ul>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <a href="/sms" class="btn btn-primary hover-elevate">Explore SMS Services</a>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm hover-elevate-up border-0 card-rounded">
                <div class="card-body py-5">
                    <div class="mb-5">
                        <span class="svg-icon svg-icon-info svg-icon-3x">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"/>
                                <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <h3 class="fs-2 fw-bold mb-4">USSD Services</h3>
                    <p class="text-muted fs-6 px-lg-5 mb-5">Create interactive USSD applications that work on any phone. Ideal for surveys, customer engagement, and information services.</p>
                    <div class="bg-light rounded p-4 mb-5">
                        <ul class="list-unstyled text-start mx-auto" style="max-width: 300px;">
                            <li class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-3"></i>
                                <span>Works on all phone types</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-3"></i>
                                <span>Easy-to-use builder</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="ki-outline ki-check-circle fs-4 text-success me-3"></i>
                                <span>Real-time reporting</span>
                            </li>
                        </ul>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <a href="/ussd" class="btn btn-info hover-elevate text-white">Explore USSD Services</a>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>

    <!-- Virtual Numbers Section -->
    <div class="card bg-light shadow-sm card-rounded my-15 overflow-hidden">
        <div class="card-body p-lg-10">
            <div class="row">
                <div class="col-lg-6 mb-10 mb-lg-0">
                    <div class="position-relative">
                        <div class="position-absolute top-50 start-50 translate-middle opacity-10 w-75 h-75 bg-primary rounded-circle filter blur-3x"></div><div class="card-title"></div><div class="card-title"></div>
                        <img src="{{ asset('assets/media/svg/illustrations/calling.svg') }}" class="img-fluid rounded position-relative hover-elevate-up animation-float" alt="Virtual Numbers">
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="col-lg-6 d-flex flex-column justify-content-center ps-lg-10">
                    <div class="mb-5">
                        <span class="badge badge-light-primary mb-3 fs-7 fw-semibold">VIRTUAL NUMBERS</span>
                        <h2 class="fs-1 fw-bolder mb-4">Global Connectivity, <span class="text-primary">Local Presence</span></h2>
                        <p class="fs-5 text-gray-700 mb-4">Get local or toll-free numbers in multiple countries. Route calls to your existing phone systems, set up IVR menus, and create a professional presence in new markets.</p>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="row g-5">
                        <div class="col-sm-4">
                            <div class="bg-white p-4 card-rounded text-center hover-elevate-up shadow-sm">
                                <div class="d-flex flex-center w-50px h-50px bg-light-primary card-rounded mx-auto mb-3">
                                    <i class="ki-outline ki-call text-primary fs-2"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <h5 class="fw-bold mb-1">Local Presence</h5>
                                <p class="text-muted fs-7 mb-0">Establish local presence in new markets</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="col-sm-4">
                            <div class="bg-white p-4 card-rounded text-center hover-elevate-up shadow-sm">
                                <div class="d-flex flex-center w-50px h-50px bg-light-info card-rounded mx-auto mb-3">
                                    <i class="ki-outline ki-call-ringing text-info fs-2"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <h5 class="fw-bold mb-1">Call Forwarding</h5>
                                <p class="text-muted fs-7 mb-0">Route calls to any device or team member</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="col-sm-4">
                            <div class="bg-white p-4 card-rounded text-center hover-elevate-up shadow-sm">
                                <div class="d-flex flex-center w-50px h-50px bg-light-success card-rounded mx-auto mb-3">
                                    <i class="ki-outline ki-call-incoming text-success fs-2"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <h5 class="fw-bold mb-1">Call Tracking</h5>
                                <p class="text-muted fs-7 mb-0">Measure the effectiveness of campaigns</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="d-flex mt-7">
                        <a href="/virtual-numbers" class="btn btn-primary">Learn More</a>
                        <a href="#pricing" class="btn btn-light ms-3">See Pricing</a>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
    
    <!-- Pricing Section -->
    <div id="pricing" class="my-15 py-15">
        <div class="text-center mb-15">
            <span class="badge badge-light-primary mb-3 fs-7 fw-semibold">PRICING PLANS</span>
            <h2 class="fs-2x text-gray-900 mb-3">Simple, Transparent Pricing</h2>
            <p class="fs-5 text-gray-600 fw-semibold">Choose the plan that works best for your business needs</p>
        </div><div class="card-title"></div><div class="card-title"></div>
        
        <div class="row g-10 g-xl-15">
            <div class="col-xl-4">
                <div class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-header position-relative min-h-150px">
                        <div class="card-title">
                            <h3 class="fs-2 fw-bold mb-0 text-gray-900">Starter</h3>
                            <p class="text-gray-500 mt-2">For small businesses and startups</p>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="card-toolbar">
                            <span class="fs-2x fw-bolder text-primary">$49</span>
                            <span class="fs-7 fw-semibold text-gray-500">/month</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="card-body p-9">
                        <ul class="list-unstyled mb-9">
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>1,000 SMS credits/month</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>1 USSD service</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Basic reports</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Email support</span>
                            </li>
                        </ul>
                        <a href="/signup" class="btn btn-primary w-100 py-3">Get Started</a>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="col-xl-4">
                <div class="card h-100 border border-2 border-primary shadow hover-elevate-up">
                    <div class="card-header position-relative min-h-150px bg-light-primary">
                        <div class="position-absolute top-0 start-0 mt-5 ms-5">
                            <span class="badge badge-primary">POPULAR</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="card-title">
                            <h3 class="fs-2 fw-bold mb-0 text-gray-900">Business</h3>
                            <p class="text-gray-500 mt-2">For growing businesses</p>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="card-toolbar">
                            <span class="fs-2x fw-bolder text-primary">$149</span>
                            <span class="fs-7 fw-semibold text-gray-500">/month</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="card-body p-9">
                        <ul class="list-unstyled mb-9">
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>10,000 SMS credits/month</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>5 USSD services</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Advanced analytics</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Priority support</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>API access</span>
                            </li>
                        </ul>
                        <a href="/signup" class="btn btn-primary w-100 py-3">Get Started</a>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="col-xl-4">
                <div class="card h-100 hover-elevate-up shadow-sm">
                    <div class="card-header position-relative min-h-150px">
                        <div class="card-title">
                            <h3 class="fs-2 fw-bold mb-0 text-gray-900">Enterprise</h3>
                            <p class="text-gray-500 mt-2">For large organizations</p>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="card-toolbar">
                            <span class="fs-2x fw-bolder text-primary">Custom</span>
                            <span class="fs-7 fw-semibold text-gray-500">Pricing</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="card-body p-9">
                        <ul class="list-unstyled mb-9">
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Unlimited SMS credits</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Unlimited USSD services</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Custom integrations</span>
                            </li>
                            <li class="d-flex align-items-center mb-5">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>Dedicated account manager</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-success svg-icon-2 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                    </svg>
                                </span>
                                <span>SLA guarantees</span>
                            </li>
                        </ul>
                        <a href="/contact" class="btn btn-primary w-100 py-3">Contact Sales</a>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
    
    <!-- Testimonials Section -->
    <div class="my-15">
        <div class="text-center mb-10">
            <span class="badge badge-light-primary mb-3 fs-7 fw-semibold">CLIENT FEEDBACK</span>
            <h2 class="fs-2x text-gray-900 mb-3">What Our Clients Say</h2>
            <p class="fs-5 text-gray-600 fw-semibold">Trusted by businesses across the globe</p>
        </div><div class="card-title"></div><div class="card-title"></div>
        
        <div class="row g-10">
            <div class="col-md-4 mb-5 mb-md-0">
                <div class="card h-100 border-0 shadow-sm hover-elevate-up card-rounded">
                    <div class="card-body p-9">
                        <div class="d-flex align-items-center mb-7">
                            <div class="symbol symbol-60px me-4">
                                <img src="{{ asset('assets/media/avatars/300-1.jpg') }}" class="rounded-circle" alt="John Doe">
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div>
                                <h5 class="fw-bold mb-1">John Doe</h5>
                                <p class="text-muted mb-0">Marketing Director, TechCorp</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="mb-5">
                            <div class="rating mb-1">
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <p class="fs-5 fw-semibold text-gray-800">"Callbly has revolutionized how we communicate with our customers. The platform is intuitive and the delivery rates are exceptional."</p>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="col-md-4 mb-5 mb-md-0">
                <div class="card h-100 border-0 shadow hover-elevate-up card-rounded bg-light-primary">
                    <div class="card-body p-9">
                        <div class="d-flex align-items-center mb-7">
                            <div class="symbol symbol-60px me-4">
                                <img src="{{ asset('assets/media/avatars/300-2.jpg') }}" class="rounded-circle" alt="Jane Smith">
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div>
                                <h5 class="fw-bold mb-1">Jane Smith</h5>
                                <p class="text-muted mb-0">CEO, Rural Connections</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="mb-5">
                            <div class="rating mb-1">
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <p class="fs-5 fw-semibold text-gray-800">"The USSD services have helped us reach customers who don't have smartphones. It's been a game-changer for our business in rural areas."</p>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-elevate-up card-rounded">
                    <div class="card-body p-9">
                        <div class="d-flex align-items-center mb-7">
                            <div class="symbol symbol-60px me-4">
                                <img src="{{ asset('assets/media/avatars/300-5.jpg') }}" class="rounded-circle" alt="David Wilson">
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div>
                                <h5 class="fw-bold mb-1">David Wilson</h5>
                                <p class="text-muted mb-0">CTO, Global Solutions</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="mb-5">
                            <div class="rating mb-1">
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="rating-label me-2 checked">
                                    <i class="ki-outline ki-star fs-6"></i>
                                </div><div class="card-title"></div><div class="card-title"></div>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <p class="fs-5 fw-semibold text-gray-800">"The customer support team at Callbly is exceptional. They're always available to help with any issues or questions we have."</p>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
    
    <!-- CTA Section -->
    <div class="card bg-primary shadow card-rounded my-15">
        <div class="card-body p-10 p-lg-15">
            <div class="text-center">
                <h2 class="fs-2qx fw-bold text-white mb-5">Ready to Transform Your Communication?</h2>
                <p class="fs-4 text-white opacity-75 fw-semibold mb-10 mw-lg-75 mx-auto">Join thousands of businesses that have already enhanced their customer engagement with Callbly's cloud telephony services.</p>
                <a href="/signup" class="btn btn-light btn-lg">Start Your Free Trial</a>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
    
    <!-- Contact Section -->
    <div id="contact" class="my-15">
        <div class="card card-rounded shadow-sm">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-lg-6 d-flex flex-column p-10 p-lg-15 bg-light-primary">
                        <div class="mb-10">
                            <span class="badge badge-light-primary mb-3 fs-7 fw-semibold">CONTACT US</span>
                            <h2 class="fs-2x text-gray-900 mb-5">Get in Touch</h2>
                            <p class="fs-5 text-gray-700">Have questions about our services? Our team is here to help you choose the right solution for your business needs.</p>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        
                        <div class="d-flex align-items-center mb-7">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 w-50px h-50px bg-white rounded shadow-sm me-5">
                                <i class="ki-outline ki-phone fs-2 text-primary"></i>
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div>
                                <h5 class="fs-4 fw-bold mb-1">Phone</h5>
                                <p class="fs-5 text-gray-600 mb-0">+1 (555) 123-4567</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        
                        <div class="d-flex align-items-center mb-7">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 w-50px h-50px bg-white rounded shadow-sm me-5">
                                <i class="ki-outline ki-sms fs-2 text-primary"></i>
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div>
                                <h5 class="fs-4 fw-bold mb-1">Email</h5>
                                <p class="fs-5 text-gray-600 mb-0">support@callbly.com</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0 w-50px h-50px bg-white rounded shadow-sm me-5">
                                <i class="ki-outline ki-geolocation fs-2 text-primary"></i>
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div>
                                <h5 class="fs-4 fw-bold mb-1">Address</h5>
                                <p class="fs-5 text-gray-600 mb-0">123 Tech Street, San Francisco, CA 94107</p>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <div class="col-lg-6 d-flex flex-column p-10 p-lg-15">
                        <h3 class="fs-2 fw-bold mb-7">Send us a message</h3>
                        <form class="form">
                            <div class="d-flex flex-column mb-5">
                                <label class="form-label fs-6 fw-semibold">Name</label>
                                <input type="text" class="form-control form-control-solid" placeholder="Your name">
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div class="d-flex flex-column mb-5">
                                <label class="form-label fs-6 fw-semibold">Email</label>
                                <input type="email" class="form-control form-control-solid" placeholder="Your email">
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div class="d-flex flex-column mb-5">
                                <label class="form-label fs-6 fw-semibold">Subject</label>
                                <input type="text" class="form-control form-control-solid" placeholder="Subject">
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div class="d-flex flex-column mb-10">
                                <label class="form-label fs-6 fw-semibold">Message</label>
                                <textarea class="form-control form-control-solid" rows="5" placeholder="Your message"></textarea>
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <button type="submit" class="btn btn-primary w-100 py-3">Send Message</button>
                        </form>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection
