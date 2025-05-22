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
                        <a href="javascript:void(0)" onclick="copyToClipboard('{{ Auth::user()->email }}')" class="fw-semibold text-muted text-hover-primary fs-7 d-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to copy">
                            {{ strlen(Auth::user()->email) > 27 ? substr(Auth::user()->email, 0, 24).'...' : Auth::user()->email }}
                            <i class="ki-outline ki-copy ms-1 fs-8"></i>
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

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Create a temporary tooltip message
        const tooltip = document.createElement('div');
        tooltip.textContent = 'Email copied!';
        tooltip.style.position = 'fixed';
        tooltip.style.padding = '5px 10px';
        tooltip.style.background = '#0095E8';
        tooltip.style.color = 'white';
        tooltip.style.borderRadius = '4px';
        tooltip.style.fontSize = '12px';
        tooltip.style.zIndex = '10000';
        
        // Position near the cursor
        const event = window.event;
        if (event) {
            tooltip.style.top = (event.clientY - 40) + 'px';
            tooltip.style.left = event.clientX + 'px';
        } else {
            tooltip.style.top = '50px';
            tooltip.style.right = '50px';
        }
        
        document.body.appendChild(tooltip);
        
        // Remove after 2 seconds
        setTimeout(() => {
            tooltip.style.opacity = '0';
            tooltip.style.transition = 'opacity 0.5s';
            setTimeout(() => document.body.removeChild(tooltip), 500);
        }, 1500);
    }).catch(function() {
        console.error('Failed to copy text to clipboard');
    });
}
</script>