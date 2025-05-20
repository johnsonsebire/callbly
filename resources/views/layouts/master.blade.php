<div class="card-title"><!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Callbly') }}</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    
    <!-- Vendor CSS -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Custom CSS -->
    <style>
        .logo-text {
            font-size: 20px;
        }
        
        .logo-text-white {
            color: #ffffff;
        }
        
        .logo-text-dark {
            color: #181C32;
        }
    </style>

    @stack('styles')
</head>
<body id="kt_app_body" 
    data-kt-app-header-fixed-mobile="true" 
    data-kt-app-sidebar-enabled="true" 
    data-kt-app-sidebar-fixed="true" 
    data-kt-app-sidebar-hoverable="true" 
    data-kt-app-sidebar-push-header="true" 
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
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
                            <a href="{{ route('dashboard') }}">
                                <span class="logo-text logo-text-dark">{{ config('app.name', 'Callbly') }}</span>
                            </a>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    @include('layouts.partials.user-menu')
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('layouts.partials.sidebar')

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                @yield('content')
                            </div><div class="card-title"></div><div class="card-title"></div>
                            <!--end::Content container-->
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <!--end::Content-->
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <!--end::Content wrapper-->
                    
                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer">
                        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                            <div class="text-dark order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">&copy; {{ date('Y') }}</span>
                                <a href="{{ config('app.url') }}" class="text-gray-800 text-hover-primary">{{ config('app.name') }}</a>
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    <!--end::Footer-->
                </div><div class="card-title"></div><div class="card-title"></div>
                <!--end::Main-->
            </div><div class="card-title"></div><div class="card-title"></div>
            <!--end::Wrapper-->
        </div><div class="card-title"></div><div class="card-title"></div>
        <!--end::Page-->
    </div><div class="card-title"></div></div>
    <!--end::App-->

    <!--begin::Javascript-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Javascript-->

    @stack('scripts')

</body>
</html>