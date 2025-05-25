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
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!-- AOS CSS -->
		<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
		<!--end::Global Stylesheets Bundle-->
		

		<!-- Custom CSS for logo switching -->
		<style>
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

			/* Custom CSS for modern look and feel */
			.hero-section-modern {
				padding-top: 40px; /* Adjusted padding */
				padding-bottom: 40px;
			}

			.hero-section-modern h1 {
				font-size: clamp(2.5rem, 5vw, 3.5rem); /* Responsive font size */
				font-weight: 700;
				color: #ffffff; /* Assuming dark hero background from app.blade.php */
			}

			.hero-section-modern .lead {
				font-size: clamp(1rem, 2.5vw, 1.25rem);
				color: rgba(255, 255, 255, 0.85); /* Softer white for subtext */
				margin-bottom: 30px;
				max-width: 600px;
				margin-left: auto;
				margin-right: auto;
			}

			.hero-section-modern .btn-primary-modern {
				background-color: #3ABFF8; /* Callbly accent */
				border-color: #3ABFF8;
				padding: 12px 30px;
				font-size: 1.1rem;
				font-weight: 600;
				transition: all 0.3s ease;
			}
			.hero-section-modern .btn-primary-modern:hover {
				background-color: #2aaadf;
				border-color: #2aaadf;
				transform: translateY(-2px);
				box-shadow: 0 4px 15px rgba(58, 191, 248, 0.4);
			}

			.section-title h2, .section-title h3 { /* Target both h2 and h3 for section titles */
				font-size: clamp(2rem, 4vw, 2.5rem);
				font-weight: 700;
				margin-bottom: 15px;
				color: #212529; /* Dark text for light sections */
			}
			.section-title .lead, .section-title .fs-5 { /* Target both lead and fs-5 for subtitles */
				font-size: clamp(1rem, 2vw, 1.1rem);
				color: #6c757d; /* Muted text */
				margin-bottom: 40px;
			}

			.feature-card-modern {
				background-color: #fff;
				border: 1px solid #e9ecef;
				border-radius: 0.75rem;
				padding: 30px;
				transition: transform 0.3s ease, box-shadow 0.3s ease;
				height: 100%;
				display: flex;
				flex-direction: column;
			}
			.feature-card-modern:hover {
				transform: translateY(-10px);
				box-shadow: 0 1rem 3rem rgba(0,0,0,.1);
			}
			.feature-card-modern .icon-box {
				width: 60px;
				height: 60px;
				background-color: rgba(58, 191, 248, 0.1); /* Light accent color */
				color: #3ABFF8; /* Accent color */
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				margin-bottom: 20px;
				font-size: 1.8rem; /* Icon size */
				flex-shrink: 0;
			}
			.feature-card-modern h4 {
				font-size: 1.3rem;
				font-weight: 600;
				margin-bottom: 10px;
			}
			.feature-card-modern p, .feature-card-modern .fs-6 {
				flex-grow: 1;
			}

			/* Pricing Table Modernization */
			.pricing-table-modern .card {
				border: 1px solid #e9ecef;
				border-radius: 0.75rem;
				box-shadow: 0 0.5rem 1rem rgba(0,0,0,.05);
				transition: transform 0.3s ease, box-shadow 0.3s ease;
				height: 100%;
			}
			.pricing-table-modern .card:hover {
				transform: translateY(-5px);
				box-shadow: 0 1rem 3rem rgba(0,0,0,.1);
			}

			.pricing-table-modern .card-header {
				background-color: #f8f9fa;
				border-bottom: 1px solid #e9ecef;
				padding: 2rem 1.5rem; /* Increased top/bottom padding */
				display: flex;
				flex-direction: column;
				justify-content: center; /* Vertically centers the flex items */
				align-items: center;   /* Horizontally centers the flex items */
				text-align: center;    /* Ensures text within title/description is centered */
				min-height: 140px;     /* Consistent header height */
			}

			.pricing-table-modern .card-header .card-title {
				font-size: 1.6rem; /* Adjusted size */
				font-weight: 700;  /* Bolder */
				margin-bottom: 0.5rem; /* Space between title and description */
			}
			.pricing-table-modern .card-header .card-text {
				font-size: 0.9rem;
				color: #6c757d; /* Default color for description */
				line-height: 1.3;
			}
			.pricing-table-modern .price { font-size: 2.5rem; font-weight: 700; color: #3ABFF8; }
			.pricing-table-modern .price .period { font-size: 0.9rem; color: #6c757d; font-weight: 400; }
			.pricing-table-modern .list-unstyled li { padding: 0.5rem 0; color: #495057; display: flex; align-items: center; }
			.pricing-table-modern .list-unstyled .ki-outline { margin-right: 8px; font-size: 1.2em; }
			.pricing-table-modern .list-unstyled .ki-check-circle { color: #28a745; }
			.pricing-table-modern .list-unstyled .ki-cross-circle { color: #dc3545; }
			.pricing-table-modern .card.popular { border: 2px solid #3ABFF8; }
			.pricing-table-modern .card.popular .card-header {
				background-color: #3ABFF8;
			}
			.pricing-table-modern .card.popular .card-header .card-title {
				color: #fff !important;
			}
			.pricing-table-modern .card.popular .card-header .card-text,
			.pricing-table-modern .card.popular .card-header .text-muted { /* Catch both if text-muted is used */
				color: rgba(255, 255, 255, 0.9) !important; /* Softer white for description */
			}
			.pricing-table-modern .card.popular .price { color: #005f8b; } /* Darker blue for popular price if header is light blue */


			/* Testimonials Carousel */
			.testimonial-card-modern { background-color: #fff; padding: 30px; border-radius: 0.75rem; text-align: center; border: 1px solid #e9ecef; margin-bottom: 20px; }
			.testimonial-card-modern img.avatar { width: 80px; height: 80px; border-radius: 50%; margin-bottom: 15px; object-fit: cover; margin-left:auto; margin-right:auto; }
			.testimonial-card-modern .name { font-weight: 600; font-size: 1.1rem; }
			.testimonial-card-modern .company { font-size: 0.9rem; color: #6c757d; }
			.testimonial-card-modern .quote { font-style: italic; color: #495057; margin-bottom: 15px; font-size: 1rem; }
			.testimonial-card-modern .stars .ki-star { color: #ffc107; }
			#testimonialCarousel .carousel-control-prev-icon, #testimonialCarousel .carousel-control-next-icon { background-color: #3ABFF8; border-radius: 50%; }

			/* CTA Section Modern */
			.cta-section-modern { background: linear-gradient(135deg, #0078AA 0%, #3ABFF8 100%); color: #fff; padding: 60px 20px; border-radius: 1rem; }
			.cta-section-modern h2 { font-size: clamp(1.8rem, 3.5vw, 2.5rem); font-weight: 700; color: #fff; }
			.cta-section-modern p { font-size: clamp(1rem, 2vw, 1.1rem); opacity: 0.9; margin-bottom: 30px; }
			.cta-section-modern .btn-cta-modern { background-color: #fff; color: #0078AA; border-color: #fff; padding: 12px 30px; font-size: 1.1rem; font-weight: 600; transition: all 0.3s ease; }
			.cta-section-modern .btn-cta-modern:hover { background-color: rgba(255,255,255,0.9); transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0, 120, 170, 0.3); }

			/* Client Logos */
			.client-logos-modern img { max-height: 35px; filter: brightness(0) invert(1); opacity: 0.7; transition: opacity 0.3s ease; margin: 10px 15px; }
			.client-logos-modern img:hover { opacity: 1; }

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
											<!--end::Menu item-->
										
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
					<!--begin::Separator-->
					<div class="landing-dark-separator"></div>
					<!--end::Separator-->
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
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		
		<!-- Logo Switch Script -->
		<script>
			// Initialize logo switching based on scroll
			document.addEventListener('DOMContentLoaded', function() {
				const headerLogo = document.querySelector('.app-header-logo');
				const landingHeader = document.querySelector('.landing-header');
				
				if (headerLogo && landingHeader) {
					window.addEventListener('scroll', function() {
						if (window.scrollY > 50 || landingHeader.classList.contains('landing-header-sticky')) {
							headerLogo.classList.add('scrolled');
						} else {
							headerLogo.classList.remove('scrolled');
						}
					});
					
					// Initial check
					if (window.scrollY > 50 || landingHeader.classList.contains('landing-header-sticky')) {
						headerLogo.classList.add('scrolled');
					}
				}
			});
		</script>
		
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="{{ asset('assets/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
		<script src="{{ asset('assets/plugins/custom/typedjs/typedjs.bundle.js') }}"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ asset('assets/js/custom/landing.js') }}"></script>
		<script src="{{ asset('assets/js/custom/pages/pricing/general.js') }}"></script>
		<!-- AOS JS -->
		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<script>
			AOS.init({
				duration: 800, // values from 0 to 3000, with step 50ms
				once: true, // whether animation should happen only once - while scrolling down
			});
		</script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>