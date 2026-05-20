@php
    $homeUrl = \Illuminate\Support\Facades\Route::has('website.home') ? route('website.home') : url('/');
    $navItems = [
        ['label' => 'Home', 'url' => $homeUrl, 'active' => request()->routeIs('website.home')],
        ['label' => 'Restaurants', 'url' => url('/restaurants'), 'active' => request()->is('restaurants*')],
        ['label' => 'Offers', 'url' => url('/offers'), 'active' => request()->is('offers*')],
        ['label' => 'Contact', 'url' => url('/contact'), 'active' => request()->is('contact')],
    ];
@endphp

<header class="website-header">
    <nav class="navbar navbar-expand-lg navbar-dark osahan-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ $homeUrl }}" aria-label="{{ config('app.name', 'Restaurant Food Order') }}">
                <img alt="{{ config('app.name', 'Restaurant Food Order') }} logo" src="{{ asset('website/assets/img/favicon.png') }}">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#websiteNavbar" aria-controls="websiteNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="websiteNavbar">
                <ul class="navbar-nav ml-auto">
                    @foreach ($navItems as $item)
                        <li class="nav-item {{ $item['active'] ? 'active' : '' }}">
                            <a class="nav-link" href="{{ $item['url'] }}">
                                {{ $item['label'] }}
                                @if ($item['active'])
                                    <span class="sr-only">(current)</span>
                                @endif
                            </a>
                        </li>
                    @endforeach

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/cart') }}">
                            <i class="fas fa-shopping-basket"></i> Cart
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/profile') }}">
                                <i class="icofont-user-alt-3"></i> My Account
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/login') }}">
                                <i class="icofont-login"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
