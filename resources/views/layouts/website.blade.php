@php
    $siteName = config('app.name', 'Restaurant Food Order');
    $pageTitle = trim($__env->yieldContent('title', $title ?? $siteName));
    $metaDescription = trim($__env->yieldContent(
        'meta_description',
        $metaDescription ?? 'Browse restaurants, discover menus, and order food online.'
    ));
    $canonicalUrl = $canonicalUrl ?? url()->current();
    $ogImage = $ogImage ?? asset('website/assets/img/logo.png');
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $metaDescription }}">

    <title>{{ $pageTitle === $siteName ? $siteName : $pageTitle.' | '.$siteName }}</title>

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" type="image/png" href="{{ asset('website/assets/img/favicon.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">

    <link href="{{ asset('website/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/vendor/owl-carousel/owl.theme.css') }}" rel="stylesheet">
    <link href="{{ asset('website/assets/css/osahan.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    @include('website.partials.header')

    <main class="website-main" role="main">
        @if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
            <section class="pt-3">
                <div class="container">
                    @foreach (['success', 'error', 'warning', 'info'] as $flashType)
                        @if (session($flashType))
                            <div class="alert alert-{{ $flashType === 'error' ? 'danger' : $flashType }} alert-dismissible fade show" role="alert">
                                {{ session($flashType) }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    @endforeach

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @yield('content')
    </main>

    @include('website.partials.footer')

    <script src="{{ asset('website/assets/vendor/jquery/jquery-3.3.1.slim.min.js') }}"></script>
    <script src="{{ asset('website/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('website/assets/vendor/owl-carousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('website/assets/js/custom.js') }}"></script>

    @stack('scripts')
</body>
</html>
