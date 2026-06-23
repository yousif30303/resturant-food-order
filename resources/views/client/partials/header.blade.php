<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <div class="navbar-brand-box">
                <a href="{{ route('client.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo-sm.svg') }}" alt="{{ config('app.name') }}" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo-sm.svg') }}" alt="{{ config('app.name') }}" height="24">
                        <span class="logo-txt">Client</span>
                    </span>
                </a>

                <a href="{{ route('client.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('backend/assets/images/logo-sm.svg') }}" alt="{{ config('app.name') }}" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('backend/assets/images/logo-sm.svg') }}" alt="{{ config('app.name') }}" height="24">
                        <span class="logo-txt">Client</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('backend/assets/images/users/avatar-1.jpg') }}" alt="Client avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">
                        {{ auth('client')->user()->name ?? 'Restaurant Owner' }}
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">
                        <i class="mdi mdi-account-circle font-size-16 align-middle me-1"></i> Profile
                    </a>
                    <a class="dropdown-item" href="{{ route('website.home') }}">
                        <i class="mdi mdi-web font-size-16 align-middle me-1"></i> Public Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
