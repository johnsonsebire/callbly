<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
Use Illuminate\Support\Str;
@endphp 

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Callbly') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/media/logos/callbly-favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Preload Critical Assets -->
    <link rel="preload" href="{{ asset('assets/media/logos/callbly-white.png') }}" as="image">
    <link rel="preload" href="{{ asset('assets/media/logos/callbly-favicon.png') }}" as="image">

    <!-- Optimized Vite Assets for Backend -->
    @vite(['resources/css/backend.css', 'resources/js/backend.js'])

    <!-- Critical CSS for immediate rendering -->
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
        
        /* Mobile navigation styles */
        #kt_app_header_mobile_toggle {
            display: none;
        }
        
        @media (max-width: 991.98px) {
            #kt_app_header_mobile_toggle {
                display: inline-flex;
            }
            
            .mobile-nav-header {
                padding: 10px 15px;
                border-bottom: 1px solid #eee;
            }
            
            .mobile-nav-item {
                padding: 10px 15px;
                border-bottom: 1px solid #f5f5f5;
            }
            
            .mobile-nav-item.active {
                background-color: #f8f9fa;
            }
            
            .mobile-nav-item i {
                width: 20px;
                text-align: center;
                margin-right: 10px;
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

    @stack('styles')
</head>

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
    class="app-default">

    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
           
            
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header d-flex d-lg-none">
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between">
                    <div class="d-flex align-items-center d-lg-none">
                        <div class="btn btn-icon btn-active-color-primary" id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-outline ki-burger-menu-2 fs-1"></i>
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
                    <div class="offcanvas-header mobile-nav-header d-flex align-items-center justify-content-between">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                            <img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-favicon.png') }}" class="h-30px me-2" />
                            <span class="fs-4 fw-bold text-dark">Callbly</span>
                        </a>
                        <div class="btn btn-icon btn-sm btn-active-color-primary" data-bs-dismiss="offcanvas">
                            <i class="ki-outline ki-cross fs-2x"></i>
                        </div>
                    </div>
                    <div class="offcanvas-body p-0">
                        <div class="d-flex flex-column pt-5">
                            @foreach(app(\App\Services\NavigationService::class)->getBackendNavigation() as $item)
                                @if(isset($item['children']))
                                    <!-- Parent menu item with children -->
                                    <div class="mobile-nav-item parent-item">
                                        <div class="d-flex align-items-center justify-content-between fs-6 fw-bold" 
                                             data-bs-toggle="collapse" 
                                             data-bs-target="#mobile_nav_item_{{ Str::slug($item['label']) }}">
                                            <div>
                                                <i class="{{ $item['icon'] }}"></i>
                                                <span>{{ $item['label'] }}</span>
                                            </div>
                                            <i class="ki-outline ki-arrow-down fs-7"></i>
                                        </div>
                                    </div>
                                    <!-- Children submenu -->
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
                                    <!-- Single menu item without children -->
                                    <div class="mobile-nav-item {{ $item['active'] ? 'active' : '' }}">
                                        <a href="{{ $item['url'] }}" class="d-flex align-items-center text-dark text-hover-primary">
                                            <i class="{{ $item['icon'] }}"></i>
                                            <span>{{ $item['label'] }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                            
                            <!-- User account section -->
                            <div class="border-top my-5 pt-5 px-5">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-40px me-3">
                                        <img src="{{ auth()->user()->avatar_url ?? asset('assets/media/avatars/blank.png') }}" alt="user" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="fw-bold">{{ auth()->user()->name }}</div>
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
                    <div class="container mt-5">
                           @if(session()->has('admin_user_id'))
            <!--begin::Impersonation Banner-->
            <div class="alert alert-warning py-3 mb-0 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="ki-outline ki-eye fs-2 me-2"></i>
                    <span class="fw-bold">You are currently viewing the site as {{ Auth::user()->name }}. This is an impersonation session.</span>
                </div>
                <a href="{{ route('stop-impersonating') }}" class="btn btn-sm btn-light-primary">
                    Stop Impersonating
                </a>
            </div>
            <!--end::Impersonation Banner-->
            @endif
                    </div> 
                    @yield('content')
                    
                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer">
                        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                            <div class="text-dark order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">&copy; {{ date('Y') }}</span>
                                <a href="{{ config('app.url') }}" class="text-gray-800 text-hover-primary">{{ config('app.name') }}</a>
                            </div>
                        </div>
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    @stack('scripts')

</body>

</html>
