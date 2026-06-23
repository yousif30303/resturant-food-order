<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('client.dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title mt-2" data-key="t-restaurant">Restaurant</li>

                <li>
                    <a href="javascript:void(0);">
                        <i data-feather="shopping-bag"></i>
                        <span data-key="t-restaurants">My Restaurants</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);">
                        <i data-feather="image"></i>
                        <span data-key="t-gallery">Gallery</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);">
                        <i data-feather="package"></i>
                        <span data-key="t-products">Products</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);">
                        <i data-feather="clipboard"></i>
                        <span data-key="t-orders">Orders</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
