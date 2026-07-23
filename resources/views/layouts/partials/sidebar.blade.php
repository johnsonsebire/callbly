<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="100px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_toggle" style="background-color: #090d16; border-right: 1px solid rgba(255, 255, 255, 0.08);">
    
    <!--begin::Logo-->
    <div class="app-sidebar-logo d-none d-lg-flex flex-center pt-8 mb-2" id="kt_app_sidebar_logo">
        <a href="{{ route('dashboard') }}" class="d-flex flex-column align-items-center group">
            <img alt="Callbly Logo" src="{{ asset('assets/media/logos/callbly-white.png') }}" class="h-36px object-contain" />
            <span class="badge badge-sm mt-2 text-cyan font-mono" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-size: 0.65rem; border: 1px solid rgba(56, 189, 248, 0.3);">
                Console
            </span>
        </a>
    </div>
    <!--end::Logo-->

    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu d-flex flex-center overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper"
            class="app-sidebar-wrapper d-flex hover-scroll-overlay-y scroll-ps mx-2 my-5" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu, #kt_app_sidebar" data-kt-scroll-offset="5px">
            
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded my-auto" id="#kt_app_sidebar_menu" data-kt-menu="true"
                data-kt-menu-expand="false">
                @foreach(app(\App\Services\NavigationService::class)->getBackendNavigation() as $item)
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                        class="menu-item py-2 {{ $item['active'] ? 'here show' : '' }}">
                        @if(isset($item['children']))
                            <!--begin:Menu link for items with children-->
                            <span class="menu-link menu-center {{ $item['active'] ? 'active' : '' }}">
                                <span class="menu-icon me-0">
                                    <i class="{{ $item['icon'] }} fs-2 text-gray-400"></i>
                                </span>
                            </span>
                            <!--end:Menu link-->
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-dropdown px-2 py-4 w-250px mh-75 overflow-auto shadow-lg" style="background-color: #111827; border: 1px solid rgba(255,255,255,0.1);">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-6 fw-bold text-white ps-2 py-1">{{ $item['label'] }}</span>
                                    </div>
                                </div>
                                @foreach($item['children'] as $child)
                                    <div class="menu-item">
                                        <a class="menu-link {{ $child['active'] ? 'active' : '' }} text-gray-300 text-hover-white" href="{{ $child['url'] }}">
                                            <span class="menu-bullet me-2">
                                                <span class="bullet bullet-dot" style="background-color: #38bdf8;"></span>
                                            </span>
                                            <span class="menu-title">{{ $child['label'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <!--end:Menu sub-->
                        @else
                            <!--begin:Menu link for items without children-->
                            <a href="{{ $item['url'] }}" class="menu-link menu-center {{ $item['active'] ? 'active' : '' }}" title="{{ $item['label'] }}" data-bs-toggle="tooltip" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="{{ $item['icon'] }} fs-2 text-gray-400"></i>
                                </span>
                            </a>
                            <!--end:Menu link-->
                        @endif
                    </div>
                    <!--end:Menu item-->
                @endforeach
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
    
    <!--begin::Footer-->
    <div class="app-sidebar-footer d-flex flex-center flex-column-auto pt-4 mb-6" id="kt_app_sidebar_footer">
        <!--begin::User menu-->
        <div class="cursor-pointer symbol symbol-40px symbol-circle border border-primary" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" 
            data-kt-attach="parent" data-kt-menu-placement="right-end">
            <img src="{{ auth()->user()->avatar_url ?? asset('assets/media/avatars/blank.png') }}" alt="user" />
        </div>
        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px shadow-xl"
            data-kt-menu="true" style="background-color: #111827; border: 1px solid rgba(255,255,255,0.1);">
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <div class="symbol symbol-50px me-4">
                        <img alt="Logo" src="{{ auth()->user()->avatar_url ?? asset('assets/media/avatars/blank.png') }}" class="rounded-circle" />
                    </div>
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-6 text-white">{{ auth()->user()->name }}</div>
                        <a href="#" class="fw-semibold text-gray-400 text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                        <div class="mt-1">
                            <span class="badge badge-light-primary fw-bold fs-8 px-2 py-1">
                                @if(auth()->user()->isSuperAdmin())
                                    Super Admin
                                @elseif(auth()->user()->isStaff())
                                    Staff
                                @elseif(auth()->user()->isCustomer())
                                    Customer
                                @else
                                    User
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator my-2 border-slate-800"></div>
            <div class="menu-item px-5">
                <a href="{{ url('profile') }}" class="menu-link px-5 text-gray-300 text-hover-white">My Profile</a>
            </div>
            <div class="separator my-2 border-slate-800"></div>
            <div class="menu-item px-5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="menu-link px-5 text-danger" 
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        Sign Out
                    </a>
                </form>
            </div>
        </div>
        <!--end::User account menu-->
    </div>
    <!--end::Footer-->
</div>
<!--end::Sidebar-->