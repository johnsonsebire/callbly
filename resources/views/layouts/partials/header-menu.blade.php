<div class="card-title"><div class="menu-item me-lg-1">
    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <span class="menu-title text-white">Dashboard</span>
    </a>
</div><div class="card-title"></div><div class="card-title"></div>
<div class="menu-item me-lg-1">
    <a class="menu-link {{ request()->routeIs('sms.*') ? 'active' : '' }}" href="{{ route('sms.dashboard') }}">
        <span class="menu-title text-white">SMS</span>
    </a>
</div><div class="card-title"></div><div class="card-title"></div>
<div class="menu-item me-lg-1">
    <a class="menu-link {{ request()->routeIs('contact-center.*') ? 'active' : '' }}" href="{{ route('contact-center.dashboard') }}">
        <span class="menu-title text-white">Contact Center</span>
    </a>
</div><div class="card-title"></div></div>