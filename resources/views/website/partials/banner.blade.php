@php
    $banner = $banner ?? null;
    $isFirst = $isFirst ?? false;
    $title = $banner?->title ?? 'Discover the best food and drinks near you';
    $subtitle = $banner?->subtitle ?? 'Browse trusted local restaurants and find your next favorite meal.';
    $imageUrl = $banner?->image_url ?? asset('website/assets/img/bg.png');
    $buttonLabel = $banner?->button_label ?? 'Browse Restaurants';
    $buttonUrl = $banner?->button_url ?? route('website.restaurants.index');
@endphp

<article class="homepage-banner-slide position-relative overflow-hidden">
    <img
        src="{{ $imageUrl }}"
        alt="{{ $title }}"
        width="1359"
        height="424"
        class="homepage-banner-image"
        loading="{{ $isFirst ? 'eager' : 'lazy' }}"
        decoding="async"
        @if ($isFirst) fetchpriority="high" @endif
    >
    <div class="homepage-banner-shade" aria-hidden="true"></div>

    <div class="container homepage-banner-content">
        <div class="homepage-banner-copy text-white">
            @if ($isFirst)
                <h1 class="homepage-banner-title text-shadow">{{ $title }}</h1>
            @else
                <h2 class="homepage-banner-title text-shadow">{{ $title }}</h2>
            @endif

            @if ($subtitle)
                <p class="homepage-banner-subtitle text-white-50">{{ $subtitle }}</p>
            @endif

            @if ($buttonLabel && $buttonUrl)
                <a href="{{ $buttonUrl }}" class="btn btn-primary btn-lg btn-gradient">
                    {{ $buttonLabel }} <i class="icofont-rounded-right ml-1"></i>
                </a>
            @endif
        </div>
    </div>
</article>
