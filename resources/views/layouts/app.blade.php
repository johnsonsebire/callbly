<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->
	<head>
		<title>@yield('title', 'Callbly')</title>
		<meta charset="utf-8" />
		<meta name="description" content="@yield('meta_description', 'Callbly Web App')" />
        <meta name="keywords" content="@yield('meta_keywords', 'callbly, sms, ussd, payments, web app')" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
		<link rel="canonical" href="{{ config('app.url') }}" />
		<link rel="shortcut icon" href="{{ asset('assets/media/logos/callbly-favicon.png') }}" />
		
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		
		<!--begin::Preload Critical Assets-->
		<link rel="preload" href="{{ asset('assets/media/logos/callbly-white.png') }}" as="image">
		<link rel="preload" href="{{ asset('assets/media/logos/callbly logo.png') }}" as="image">
		<!--end::Preload Critical Assets-->
		
		<!--begin::Vite Assets-->
		@vite(['resources/css/frontend.css', 'resources/js/frontend.js'])
		<!--end::Vite Assets-->
		
		<!-- Custom CSS for enhanced performance -->
		<style>
			/* Critical inline styles for immediate rendering */
			.app-header-logo.scrolled img.logo-default {
				display: block;
			}
			
			.app-header-logo.scrolled img.logo-white {
				display: none;
			}
			
			.app-header-logo img.logo-default {
				display: none;
			}
			
			.app-header-logo img.logo-white {
				display: block;
			}
			
			.footer-logo span {
				font-size: 20px;
			}

			/* Fix for white line between sections */
			.landing-curve {
				line-height: 0;
				font-size: 0;
			}
			
			.landing-curve svg {
				display: block;
				width: 100%;
				height: auto;
			}
			
			/* Ensure no gaps between sections */
			.landing-dark-bg {
				margin-top: -1px;
			}
			
			/* Remove bottom margins on larger screens to prevent gaps */
			@media (min-width: 992px) {
				.landing-curve.mb-lg-20 {
					margin-bottom: 0 !important;
				}
			}

			/* Loading optimization */
			.loading-skeleton {
				background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
				background-size: 200% 100%;
				animation: loading 1.5s infinite;
			}

			@keyframes loading {
				0% { background-position: 200% 0; }
				100% { background-position: -200% 0; }
			}
		</style>
		
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-body position-relative app-blank">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Header Section-->
			<div class="mb-0" id="home">
				<!--begin::Wrapper-->
				<div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-dark-bg" style="background-image: url(assets/media/svg/illustrations/landing.svg)">
					<!--begin::Header-->
					<div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
						<!--begin::Container-->
						<div class="container">
							<!--begin::Wrapper-->
							<div class="d-flex align-items-center justify-content-between">
								<!--begin::Logo-->
								<div class="d-flex align-items-center flex-equal">
									<!--begin::Mobile menu toggle-->
									<button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
										<i class="ki-outline ki-abstract-14 fs-2hx"></i>
									</button>
									<!--end::Mobile menu toggle-->
									<!--begin::Logo image-->
									<a href="/" class="app-header-logo">
										<img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="logo-white h-40px" />
										<img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly logo.png') }}" class="logo-default h-40px" />
									</a>
									<!--end::Logo image-->
								</div>
								<!--end::Logo-->
								<!--begin::Menu wrapper-->
								<div class="d-lg-block" id="kt_header_nav_wrapper">
									<div class="d-lg-block p-5 p-lg-0" data-kt-drawer="true" data-kt-drawer-name="landing-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="200px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_landing_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">
										<!--begin::Menu-->
										<div class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-600 menu-state-title-primary nav nav-flush fs-5 fw-semibold" id="kt_landing_menu">
											@foreach($navigationService->getFrontendNavigation() as $item)
                                        <div class="menu-item">
                                            <a class="menu-link nav-link {{ $item['active'] ? 'active' : '' }} py-3 px-4 px-xxl-6" href="{{ $item['url'] }}" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">{{ $item['label'] }}</a>
                                        </div>
                                        @endforeach
										</div>
										<!--end::Menu-->
									</div>
								</div>
								<!--end::Menu wrapper-->
								<!--begin::Toolbar-->
                                <div class="flex-equal text-end ms-1">
                                    @auth
                                        <a href="{{ route('dashboard') }}" class="btn btn-success">My Dashboard</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-success me-2">Sign In</a>
                                        <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                                    @endauth
                                </div>
								<!--end::Toolbar-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					@yield('hero')
				</div>
				<!--end::Wrapper-->
				<!--begin::Curve bottom-->
				<div class="landing-curve landing-dark-color mb-10 mb-lg-20">
					<svg viewBox="15 12 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z" fill="currentColor"></path>
					</svg>
				</div>
				<!--end::Curve bottom-->
			</div>
			<!--end::Header Section-->
            @yield('content')
			
			<!--begin::Footer Section-->
			<div class="mb-0">
				<!--begin::Curve top-->
				<div class="landing-curve landing-dark-color">
					<svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1Z" fill="currentColor"></path>
					</svg>
				</div>
				<!--end::Curve top-->
				<!--begin::Wrapper-->
				<div class="landing-dark-bg pt-20">
					<!--begin::Container-->
					<div class="container">
						<!--begin::Row-->
						<div class="row py-10 py-lg-20">
							<!--begin::Col-->
							<div class="col-lg-5 pe-lg-10 mb-10 mb-lg-0">
								<a href="/" class="app-header-logo">
									<img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="h-40px mb-5" />
								</a>
								<p class="fw-normal fs-5 text-gray-700">Callbly re-imagines business communication with cutting-edge cloud telephony solutions, empowering you to connect, engage, and grow.</p>
								<div class="d-flex mt-6">
									<a href="#" class="btn btn-icon btn-active-color-primary me-3"><img src="{{ asset('assets/media/svg/brand-logos/facebook-4.svg') }}" class="h-20px" alt="Callbly Facebook"/></a>
									<a href="#" class="btn btn-icon btn-active-color-primary me-3"><img src="{{ asset('assets/media/svg/brand-logos/twitter.svg') }}" class="h-20px" alt="Callbly Twitter"/></a>
									<a href="#" class="btn btn-icon btn-active-color-primary me-3"><img src="{{ asset('assets/media/svg/brand-logos/instagram-2-1.svg') }}" class="h-20px" alt="Callbly Instagram"/></a>
									<a href="#" class="btn btn-icon btn-active-color-primary me-3"><img src="{{ asset('assets/media/svg/brand-logos/github.svg') }}" class="h-20px" alt="Callbly Github"/></a>
								</div>
							</div>
							<!--end::Col-->
							<!--begin::Col-->
							<div class="col-lg-7 ps-lg-10">
								<div class="row">
									<div class="col-md-4 mb-5 mb-md-0">
										<h4 class="fw-bold text-gray-500 mb-6">Product</h4>
										<a href="#services" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">Services</a>
										<a href="#pricing" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">Pricing</a>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">Features</a>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 d-block">Integrations</a>
									</div>
									<div class="col-md-4 mb-5 mb-md-0">
										<h4 class="fw-bold text-gray-500 mb-6">Company</h4>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">About Us</a>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">Blog</a>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">Careers</a>
										<a href="mailto:support@callbly.com" class="text-white opacity-50 text-hover-primary fs-5 d-block">Contact Us</a>
									</div>
									<div class="col-md-4">
										<h4 class="fw-bold text-gray-500 mb-6">Resources</h4>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">FAQ</a>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">Documentation</a>
										<a href="#" class="text-white opacity-50 text-hover-primary fs-5 mb-2 d-block">Support</a>
									</div>
								</div>
							</div>
							<!--end::Col-->
						</div>
						<!--end::Row-->
					</div>
					<!--end::Container-->
					<!--begin::Container-->
					<div class="container">
						<!--begin::Wrapper-->
						<div class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
							<!--begin::Copyright-->
							<div class="d-flex align-items-center order-2 order-md-1">
								 <a href="/" class="footer-logo me-4">
                                    <img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="h-30px" />
                                </a>
								<span class="text-gray-700 fw-semibold fs-6 ms-2">© {{ date('Y') }} Callbly. All rights reserved.</span>
							</div>
							<!--end::Copyright-->
							<!--begin::Menu-->
							<ul class="menu menu-gray-600 menu-hover-primary fw-semibold fs-6 fs-md-5 order-1 mb-5 mb-md-0">
								  @foreach($navigationService->getFooterNavigation() as $item)
                            <li class="menu-item {{ !$loop->first ? 'mx-5' : '' }}">
                                <a href="{{ $item['url'] }}" class="{{ $item['class'] }}">{{ $item['label'] }}</a>
                            </li>
                            @endforeach
							</ul>
							<!--end::Menu-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Footer Section-->
			<!--begin::Scrolltop-->
			<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
				<i class="ki-outline ki-arrow-up"></i>
			</div>
			<!--end::Scrolltop-->
		</div>
		<!--end::Root-->
	</body>
	<!--end::Body-->
</html>