<div class="card-title">@auth
    <div class="d-flex align-items-center ms-1 ms-lg-3">
        <div class="btn btn-icon btn-active-light-primary position-relative w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            <i class="ki-outline ki-user-circle fs-2 text-white"></i>
        </div><div class="card-title"></div><div class="card-title"></div>
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-5">
                            {{ Auth::user()->name }}
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                            {{ Auth::user()->email }}
                        </a>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
            
            <div class="separator my-2"></div><div class="card-title"></div><div class="card-title"></div>
            
            <div class="menu-item px-5">
                <a href="{{ route('profile.edit') }}" class="menu-link px-5">Profile</a>
            </div><div class="card-title"></div><div class="card-title"></div>
            
            <div class="menu-item px-5">
                <a href="{{ route('settings.currency') }}" class="menu-link px-5">Settings</a>
            </div><div class="card-title"></div><div class="card-title"></div>
            
            <div class="separator my-2"></div><div class="card-title"></div><div class="card-title"></div>
            
            <div class="menu-item px-5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-link px-5 bg-transparent border-0 w-100 text-start">
                        Sign Out
                    </button>
                </form>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div></div>
@endauth