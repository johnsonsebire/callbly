<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
use Illuminate\Support\Str;
@endphp 

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Callbly') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/callbly-favicon.png') }}" type="image/png">

    <!-- Fonts: Outfit for brand matching -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />

    <!-- Vendor CSS -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Brand Styling Overrides -->
    <style>
        body, html {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }
        :root {
            --kt-primary: #0284c7;
            --kt-primary-active: #0369a1;
            --kt-primary-hover: #0369a1;
            --kt-app-sidebar-bg-color: #090d16;
            --brand-cyan: #0284c7;
        }
        [data-bs-theme="dark"] {
            --kt-primary: #38bdf8;
            --kt-primary-active: #0284c7;
            --kt-primary-hover: #0284c7;
        }
        .app-sidebar {
            background-color: #090d16 !important;
        }
        @media (min-width: 992px) {
            .app-sidebar {
                height: calc(100vh - 120px) !important;
                margin: 20px !important;
                border-radius: 24px !important;
                border: 1px solid rgba(255, 255, 255, 0.08) !important;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
            }
            body[data-kt-app-sidebar-fixed="true"] .app-main {
                padding-left: 40px !important;
            }
            body[data-kt-app-sidebar-fixed="true"] .app-footer {
                padding-left: 140px !important;
            }
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
        .btn-light-primary {
            background-color: rgba(2, 132, 199, 0.1) !important;
            color: #0284c7 !important;
            border-radius: 0.6rem !important;
        }
        .btn-light-primary:hover {
            background-color: #0284c7 !important;
            color: #ffffff !important;
        }
        .text-primary, .link-primary {
            color: #0284c7 !important;
        }
        .text-primary:hover, .link-primary:hover {
            color: #0369a1 !important;
        }
        .bg-light-primary {
            background-color: rgba(2, 132, 199, 0.12) !important;
        }
        .badge-light-primary {
            background-color: rgba(2, 132, 199, 0.15) !important;
            color: #0284c7 !important;
        }
        .form-control:focus {
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 0.25rem rgba(2, 132, 199, 0.15) !important;
        }
        .form-control {
            border-radius: 0.6rem !important;
        }
        .card {
            border-radius: 0.85rem !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.05) !important;
        }
        [data-bs-theme="dark"] .card {
            border-color: oklch(25% 0.018 250) !important;
            background-color: oklch(16% 0.014 250) !important;
        }
        .menu-item .menu-link.here, .menu-item .menu-link.active {
            background-color: rgba(56, 189, 248, 0.15) !important;
            color: #38bdf8 !important;
            border-radius: 0.6rem !important;
        }
        .menu-item .menu-link.here .menu-icon i, .menu-item .menu-link.active .menu-icon i {
            color: #38bdf8 !important;
        }
        
        /* Mobile navigation styles */
        #kt_app_header_mobile_toggle {
            display: none;
        }
        @media (max-width: 991.98px) {
            #kt_app_header_mobile_toggle {
                display: inline-flex;
            }
            .mobile-nav-header {
                padding: 12px 20px;
                border-bottom: 1px solid #eee;
            }
            .mobile-nav-item {
                padding: 12px 20px;
                border-bottom: 1px solid #f5f5f5;
            }
            .mobile-nav-item.active {
                background-color: rgba(2, 132, 199, 0.08);
            }
        }
    </style>

    @stack('styles')
</head>

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-footer-fixed="true" class="app-default">

    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
           
            <!--begin::Header for Mobile-->
            <div id="kt_app_header" class="app-header d-flex d-lg-none bg-slate-950 border-bottom border-slate-800">
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between">
                    <div class="d-flex align-items-center d-lg-none">
                        <div class="btn btn-icon btn-active-color-primary me-2" id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-outline ki-burger-menu-2 fs-1 text-white"></i>
                        </div>
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
                            <a href="{{ route('dashboard') }}">
                                <img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="h-30px" />
                            </a>
                        </div>
                    </div>
                    @include('layouts.partials.user-menu')
                </div>
            </div>
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('layouts.partials.sidebar')
                
                <!-- Mobile Navigation Drawer -->
                <div id="kt_app_sidebar_mobile" class="bg-white offcanvas offcanvas-start d-lg-none" tabindex="-1">
                    <div class="offcanvas-header mobile-nav-header d-flex align-items-center justify-content-between bg-dark">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                            <img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="h-30px me-2" />
                        </a>
                        <div class="btn btn-icon btn-sm btn-active-color-primary text-white" data-bs-dismiss="offcanvas">
                            <i class="ki-outline ki-cross fs-2x"></i>
                        </div>
                    </div>
                    <div class="offcanvas-body p-0">
                        <div class="d-flex flex-column pt-3">
                            @foreach(app(\App\Services\NavigationService::class)->getBackendNavigation() as $item)
                                @if(isset($item['children']))
                                    <div class="mobile-nav-item parent-item">
                                        <div class="d-flex align-items-center justify-content-between fs-6 fw-bold" 
                                             data-bs-toggle="collapse" 
                                             data-bs-target="#mobile_nav_item_{{ Str::slug($item['label']) }}">
                                            <div>
                                                <i class="{{ $item['icon'] }} me-2 text-primary"></i>
                                                <span>{{ $item['label'] }}</span>
                                            </div>
                                            <i class="ki-outline ki-arrow-down fs-7"></i>
                                        </div>
                                    </div>
                                    <div class="collapse ps-5" id="mobile_nav_item_{{ Str::slug($item['label']) }}">
                                        @foreach($item['children'] as $child)
                                            <div class="mobile-nav-item {{ $child['active'] ? 'active' : '' }}">
                                                <a href="{{ $child['url'] }}" class="d-flex align-items-center text-dark text-hover-primary">
                                                    <i class="bullet bullet-dot me-2"></i>
                                                    <span>{{ $child['label'] }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mobile-nav-item {{ $item['active'] ? 'active' : '' }}">
                                        <a href="{{ $item['url'] }}" class="d-flex align-items-center text-dark text-hover-primary">
                                            <i class="{{ $item['icon'] }} me-2 text-primary"></i>
                                            <span>{{ $item['label'] }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                            
                            <!-- User account section -->
                            <div class="border-top my-4 pt-4 px-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="symbol symbol-40px me-3">
                                        <img src="{{ auth()->user()->avatar_url ?? asset('assets/media/avatars/blank.png') }}" alt="user" class="rounded-circle" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="fw-bold fs-6">{{ auth()->user()->name }}</div>
                                        <div class="text-muted fs-7">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                                
                                <a href="{{ url('profile') }}" class="btn btn-light-primary btn-sm w-100 mb-2">
                                    <i class="ki-outline ki-user fs-6 me-2"></i>My Profile
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-light-danger btn-sm w-100">
                                        <i class="ki-outline ki-logout fs-6 me-2"></i>Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Mobile Navigation -->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="container-fluid px-4 px-lg-8 mt-4">
                        @if(session()->has('admin_user_id'))
                        <!--begin::Impersonation Banner-->
                        <div class="alert alert-warning py-3 mb-4 d-flex align-items-center justify-content-between rounded-3 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-eye fs-2 me-2"></i>
                                <span class="fw-bold">You are currently viewing the site as {{ Auth::user()->name }}. (Impersonation Session)</span>
                            </div>
                            <a href="{{ route('stop-impersonating') }}" class="btn btn-sm btn-light-primary">
                                Stop Impersonating
                            </a>
                        </div>
                        <!--end::Impersonation Banner-->
                        @endif
                    </div> 

                    @yield('content')
                    
            </div>
            <!--end::Main-->
        </div>
        <!--end::Wrapper-->

            <!--begin::Footer-->
            <div id="kt_app_footer" class="app-footer border-top mt-10 py-4 bg-white" style="z-index: 105; position: relative;">
                <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack">
                    <div class="text-dark order-2 order-md-1 fs-7">
                        <span class="text-muted fw-semibold me-1">&copy; {{ date('Y') }}</span>
                        <a href="{{ config('app.url') }}" class="text-gray-800 text-hover-primary fw-bold">{{ config('app.name') }} Telephony Platform</a>
                    </div>
                    <div class="d-flex space-x-4 order-1 order-md-2 fs-7 mb-2 mb-md-0">
                        <a href="{{ route('privacy') }}" class="text-muted text-hover-primary me-3">Privacy</a>
                        <a href="{{ route('terms') }}" class="text-muted text-hover-primary me-3">Terms</a>
                        <a href="{{ route('status') }}" class="text-muted text-hover-primary me-3">Status</a>
                        <a href="{{ route('contact') }}" class="text-muted text-hover-primary me-3">Support</a>
                        <a href="{{ route('api.docs') }}" class="text-muted text-hover-primary">API Docs</a>
                    </div>
                </div>
            </div>
            <!--end::Footer-->
            
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    <!--begin::Javascript-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    
    <!-- Mobile Navigation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarMobileToggle = document.getElementById('kt_app_sidebar_mobile_toggle');
            if (sidebarMobileToggle) {
                sidebarMobileToggle.addEventListener('click', function() {
                    const mobileSidebar = new bootstrap.Offcanvas(document.getElementById('kt_app_sidebar_mobile'));
                    mobileSidebar.show();
                });
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
