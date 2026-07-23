<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<base href="{{url('/')}}" />
		<title>@yield('title', 'Authentication') — {{ config('app.name', 'Callbly') }}</title>
		<meta charset="utf-8" />
		<meta name="description" content="Callbly - Cloud Telephony Services" />
		<meta name="keywords" content="callbly, sms, ussd, payments, telephony, cloud telephony" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="{{ config('app.name', 'Callbly') }} - Cloud Telephony Services" />
		<meta property="og:url" content="{{ url('/') }}" />
		<meta property="og:site_name" content="{{ config('app.name', 'Callbly') }}" />
		<link rel="canonical" href="{{ url('/') }}" />
		<link rel="shortcut icon" href="{{ asset('assets/media/logos/callbly-favicon.png') }}" />
		
		<!--begin::Fonts-->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
		<!--end::Fonts-->
		
		<!--begin::Global Stylesheets Bundle-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->

		<!--begin::Custom Brand Styling Overrides-->
		<style>
			body, html {
				font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif !important;
			}
			:root {
				--kt-primary: #0284c7;
				--kt-primary-active: #0369a1;
				--kt-primary-hover: #0369a1;
				--brand-cyan: #0284c7;
			}
			[data-bs-theme="dark"] {
				--kt-primary: #38bdf8;
				--kt-primary-active: #0284c7;
				--kt-primary-hover: #0284c7;
			}
			.btn-primary {
				background-color: #0284c7 !important;
				border-color: #0284c7 !important;
				border-radius: 0.6rem !important;
				font-weight: 600 !important;
				transition: all 0.2s ease !important;
			}
			.btn-primary:hover, .btn-primary:focus, .btn-primary:active {
				background-color: #0369a1 !important;
				border-color: #0369a1 !important;
				box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35) !important;
			}
			.text-primary, .link-primary {
				color: #0284c7 !important;
			}
			.text-primary:hover, .link-primary:hover {
				color: #0369a1 !important;
			}
			.form-control:focus {
				border-color: #0284c7 !important;
				box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.15) !important;
			}
			.form-control {
				border-radius: 0.6rem !important;
				padding: 0.75rem 1rem !important;
			}
			.auth-hero-panel {
				background: #090d16;
				position: relative;
				overflow: hidden;
			}
			.auth-hero-panel::before {
				content: '';
				position: absolute;
				top: -150px;
				right: -150px;
				width: 600px;
				height: 600px;
				background: radial-gradient(circle, rgba(14, 165, 233, 0.18) 0%, transparent 70%);
				pointer-events: none;
			}
			.auth-hero-panel::after {
				content: '';
				position: absolute;
				bottom: -150px;
				left: -150px;
				width: 500px;
				height: 500px;
				background: radial-gradient(circle, rgba(168, 85, 247, 0.15) 0%, transparent 70%);
				pointer-events: none;
			}
			.hero-feature-card {
				background: rgba(255, 255, 255, 0.04);
				backdrop-filter: blur(12px);
				border: 1px solid rgba(255, 255, 255, 0.08);
				border-radius: 1rem;
				padding: 1.25rem 1.5rem;
				transition: transform 0.2s ease, border-color 0.2s ease;
			}
			.hero-feature-card:hover {
				transform: translateY(-2px);
				border-color: rgba(56, 189, 248, 0.3);
			}
		</style>
		<!--end::Custom Brand Styling Overrides-->

		<script>// Frame-busting to prevent click-jacking
		if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
		</script>
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="app-blank">
		<!--begin::Theme mode setup on page load-->
		<script>
			var defaultThemeMode = "light";
			var themeMode;
			if ( document.documentElement ) {
				if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
					themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
				} else {
					if ( localStorage.getItem("data-bs-theme") !== null ) {
						themeMode = localStorage.getItem("data-bs-theme");
					} else {
						themeMode = defaultThemeMode;
					}
				}
				if (themeMode === "system") {
					themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
				}
				document.documentElement.setAttribute("data-bs-theme", themeMode);
			}
		</script>
		<!--end::Theme mode setup on page load-->
		
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				
				<!--begin::Form Panel (Left side)-->
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-6 p-lg-12 order-2 order-lg-1">
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<div class="w-100 w-md-450px w-lg-500px p-4 p-md-8">
							
							<!-- Header Brand Logo -->
							<div class="text-center mb-8">
								<a href="{{ url('/') }}" class="d-inline-block">
									<img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly logo.png') }}" class="h-45px theme-light-show" />
									<img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="h-45px theme-dark-show" />
								</a>
							</div>
							
							<!-- Dynamic Content -->
							@yield('content')
							
						</div>
					</div>
					
					<!--begin::Footer-->
					<div class="w-100 w-md-450px w-lg-500px d-flex flex-stack px-4 mx-auto mt-6">
						<div class="text-gray-500 fs-7">
							&copy; {{ date('Y') }} {{ config('app.name', 'Callbly') }}. All rights reserved.
						</div>
						<div class="d-flex fw-semibold text-primary fs-7 gap-4">
							<a href="{{ url('/') }}" class="hover-underline">Home</a>
							<a href="{{ url('/developers/api') }}" class="hover-underline">API Docs</a>
						</div>
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Form Panel-->
				
				<!--begin::Hero Panel (Right side)-->
				<div class="d-flex flex-lg-row-fluid w-lg-50 auth-hero-panel order-1 order-lg-2 min-h-300px min-h-lg-auto">
					<div class="d-flex flex-column flex-center py-10 py-lg-20 px-6 px-md-16 w-100 position-relative z-index-2">
						
						<!-- Hero Pill Badge -->
						<div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill bg-white-5 border border-white-10 text-cyan mb-8" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.25);">
							<span class="w-8px h-8px rounded-circle bg-info animate-pulse"></span>
							<span class="text-info fs-7 fw-bold uppercase tracking-wider">Cloud Telephony Platform</span>
						</div>

						<!-- White Logo -->
						<a href="{{ url('/') }}" class="mb-8">
							<img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="h-50px h-lg-60px" />
						</a>
						
						<!-- Hero Headline -->
						<h1 class="d-none d-lg-block text-white fs-2hx fw-bolder text-center mb-6 lh-base" style="letter-spacing: -0.02em;">
							Scale Your Messaging & <br />
							<span style="color: #38bdf8;">Telephony Infrastructure</span>
						</h1>
						
						<p class="d-none d-lg-block text-gray-400 fs-5 text-center mb-10 max-w-500px lh-lg">
							Send high-deliverability SMS, trigger transactional alerts, and connect with customers globally.
						</p>

						<!-- Feature Cards -->
						<div class="d-none d-lg-flex flex-column gap-4 w-100 max-w-450px mb-8">
							<div class="hero-feature-card d-flex items-center gap-4">
								<div class="w-40px h-40px rounded-circle d-flex flex-center" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">
									<i class="ki-outline ki-sms fs-2 text-info"></i>
								</div>
								<div>
									<h4 class="text-white fs-6 fw-bold mb-1">Bulk & Single SMS API</h4>
									<div class="text-gray-400 fs-7">High throughput delivery with live webhook tracking.</div>
								</div>
							</div>

							<div class="hero-feature-card d-flex items-center gap-4">
								<div class="w-40px h-40px rounded-circle d-flex flex-center" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;">
									<i class="ki-outline ki-wallet fs-2 text-primary"></i>
								</div>
								<div>
									<h4 class="text-white fs-6 fw-bold mb-1">Flexible Wallet Billing</h4>
									<div class="text-gray-400 fs-7">Pay-as-you-go credit balance with automated top-ups.</div>
								</div>
							</div>
						</div>

						<div class="text-gray-400 fs-8 tracking-wider text-center uppercase">
							Trusted by thousands of developers & businesses
						</div>

					</div>
				</div>
				<!--end::Hero Panel-->
				
			</div>
		</div>
		<!--end::Root-->
		
		<!--begin::Global Javascript Bundle-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
	</body>
	<!--end::Body-->
</html>