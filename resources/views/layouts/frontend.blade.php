<div class="card-title"><!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Callbly')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="@yield('meta_description', 'Callbly Web App')" />
    <meta name="keywords" content="@yield('meta_keywords', 'callbly, sms, ussd, payments, web app')" />
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    {{-- <link href="{{ asset('build/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('build/style.bundle.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>
<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-body position-relative app-blank">
    <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Header Section-->
        {{-- HEADER START --}}
        <div class="mb-0" id="home">
            <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-dark-bg" style="background-image: url({{ asset('assets/media/svg/illustrations/landing.svg') }})">
                <div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                    <div class="container">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center flex-equal">
                                <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
                                    <i class="ki-outline ki-abstract-14 fs-2hx"></i>
                                </button>
                                <a href="/" style="text-decoration: none; font-size:20px;cursor: pointer !important;">
                                    <span class="logo-text">
                                        <span style="color: #3ABFF8; font-weight: bold;">Call</span><span style="color: #fff; font-weight: bold;">bly</span>
                                    </span>
                                </a>
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div class="d-lg-block" id="kt_header_nav_wrapper">
                                <div class="d-lg-block p-5 p-lg-0" data-kt-drawer="true" data-kt-drawer-name="landing-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="200px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_landing_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">
                                    <div class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-600 menu-state-title-primary nav nav-flush fs-5 fw-semibold" id="kt_landing_menu">
                                        @foreach($navigationService->getFrontendNavigation() as $item)
                                        <div class="menu-item">
                                            <a class="menu-link nav-link {{ $item['active'] ? 'active' : '' }} py-3 px-4 px-xxl-6" href="{{ $item['url'] }}" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">{{ $item['label'] }}</a>
                                        </div><div class="card-title"></div><div class="card-title"></div>
                                        @endforeach
                                    </div><div class="card-title"></div><div class="card-title"></div>
                                </div><div class="card-title"></div><div class="card-title"></div>
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div class="flex-equal text-end ms-1">
                                @foreach($navigationService->getAuthNavigation() as $item)
                                <a href="{{ $item['url'] }}" class="{{ $item['class'] }}">{{ $item['label'] }}</a>
                                @endforeach
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
        {{-- HEADER END --}}
        <div id="app">
            @yield('content')
        </div><div class="card-title"></div><div class="card-title"></div>
        {{-- FOOTER START --}}
        <div class="mb-0">
            <div class="landing-curve landing-dark-color">
                <svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z" fill="currentColor"></path>
                </svg>
            </div><div class="card-title"></div><div class="card-title"></div>
            <div class="landing-dark-bg pt-20">
                <div class="container">
                    <div class="row py-10 py-lg-20">
                        <div class="col-lg-6 pe-lg-16 mb-10 mb-lg-0">
                            <div class="rounded landing-dark-border p-9 mb-10">
                                <h2 class="text-white">Would you need a Custom License?</h2>
                                <span class="fw-normal fs-4 text-gray-700">Email us to 
                                <a href="mailto:support@callbly.com" class="text-white opacity-50 text-hover-primary">support@callbly.com</a></span>
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <div class="rounded landing-dark-border p-9">
                                <h2 class="text-white">How About a Custom Project?</h2>
                                <span class="fw-normal fs-4 text-gray-700">Use Our Custom Development Service. 
                                <a href="#" class="text-white opacity-50 text-hover-primary">Click to Get a Quote</a></span>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="col-lg-6 ps-lg-16">
                            <div class="d-flex justify-content-center">
                                <div class="d-flex fw-semibold flex-column me-20">
                                    <h4 class="fw-bold text-gray-500 mb-6">More for Callbly</h4>
                                    <a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-6">FAQ</a>
                                    <a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Documentation</a>
                                    <a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Video Tutorials</a>
                                    <a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Changelog</a>
                                    <a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Support Forum</a>
                                    <a href="#" class="text-white opacity-50 text-hover-primary fs-5">Blog</a>
                                </div><div class="card-title"></div><div class="card-title"></div>
                                <div class="d-flex fw-semibold flex-column ms-lg-20">
                                    <h4 class="fw-bold text-gray-500 mb-6">Stay Connected</h4>
                                    <a href="#" class="mb-6">
                                        <img src="{{ asset('assets/media/svg/brand-logos/facebook-4.svg') }}" class="h-20px me-2" alt="" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Facebook</span>
                                    </a>
                                    <a href="#" class="mb-6">
                                        <img src="{{ asset('assets/media/svg/brand-logos/github.svg') }}" class="h-20px me-2" alt="" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Github</span>
                                    </a>
                                    <a href="#" class="mb-6">
                                        <img src="{{ asset('assets/media/svg/brand-logos/twitter.svg') }}" class="h-20px me-2" alt="" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Twitter</span>
                                    </a>
                                    <a href="#" class="mb-6">
                                        <img src="{{ asset('assets/media/svg/brand-logos/dribbble-icon-1.svg') }}" class="h-20px me-2" alt="" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Dribbble</span>
                                    </a>
                                    <a href="#" class="mb-6">
                                        <img src="{{ asset('assets/media/svg/brand-logos/instagram-2-1.svg') }}" class="h-20px me-2" alt="" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Instagram</span>
                                    </a>
                                </div><div class="card-title"></div><div class="card-title"></div>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="landing-dark-separator"></div><div class="card-title"></div><div class="card-title"></div>
                <div class="container">
                    <div class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
                        <div class="d-flex align-items-center order-2 order-md-1">
                            <a href="/" style="text-decoration: none; font-size:20px;">
                                <span class="logo-text">
                                    <span style="color: #3ABFF8; font-weight: bold;">Call</span><span style="color: #fff; font-weight: bold;">bly</span>
                                </span>
                            </a>
                            <span class="mx-5 fs-6 fw-semibold text-gray-600 pt-1">&copy; 2025 Callbly Inc.</span>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold fs-6 fs-md-5 order-1 mb-5 mb-md-0">
                            @foreach($navigationService->getFooterNavigation() as $item)
                            <li class="menu-item {{ !$loop->first ? 'mx-5' : '' }}">
                                <a href="{{ $item['url'] }}" class="{{ $item['class'] }}">{{ $item['label'] }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
        {{-- FOOTER END --}}
    </div><div class="card-title"></div></div>
    <script>var hostUrl = "{{ asset('build/') }}/";</script>
    <script src="{{ asset('build/plugins.bundle.js') }}"></script>
    <script src="{{ asset('build/scripts.bundle.js') }}"></script>
    <script src="{{ asset('build/fslightbox.bundle.js') }}"></script>
    <script src="{{ asset('build/typedjs.bundle.js') }}"></script>
    <script src="{{ asset('build/landing.js') }}"></script>
    <script src="{{ asset('build/pages/pricing/general.js') }}"></script>
</body>
</html>
