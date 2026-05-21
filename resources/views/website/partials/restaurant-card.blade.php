@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $imagePath = data_get($restaurant, 'image_url')
        ?? data_get($restaurant, 'image_path')
        ?? data_get($restaurant, 'logo_path')
        ?? data_get($restaurant, 'image')
        ?? data_get($restaurant, 'logo');

    $imageUrl = $imagePath
        ? (Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : Storage::url($imagePath))
        : asset('website/assets/img/list/1.png');

    $categoryName = data_get($restaurant, 'category_name')
        ?? (method_exists($restaurant, 'relationLoaded') && $restaurant->relationLoaded('category') ? data_get($restaurant, 'category.name') : null);
    $cityName = data_get($restaurant, 'city_name')
        ?? (method_exists($restaurant, 'relationLoaded') && $restaurant->relationLoaded('city') ? data_get($restaurant, 'city.name') : null);
    $detailsUrl = data_get($restaurant, 'details_url') ?: (Route::has('website.restaurants.show')
        ? route('website.restaurants.show', $restaurant)
        : '#');
    $rating = data_get($restaurant, 'rating');
    $ratingClass = data_get($restaurant, 'rating_class', 'success');
    $badgeLabel = data_get($restaurant, 'badge_label', 'Promoted');
    $badgeClass = data_get($restaurant, 'badge_class', 'dark');
    $meta = data_get($restaurant, 'meta');
    $time = data_get($restaurant, 'time');
    $price = data_get($restaurant, 'price_for_two');
    $offer = data_get($restaurant, 'offer');
@endphp

<article class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
    <div class="list-card-image">
        @if ($rating)
            <div class="star position-absolute">
                <span class="badge badge-{{ $ratingClass }}"><i class="icofont-star"></i> {{ $rating }}</span>
            </div>
        @endif
        <div class="favourite-heart text-danger position-absolute">
            <a href="{{ $detailsUrl }}"><i class="icofont-heart"></i></a>
        </div>
        @if ($badgeLabel)
            <div class="member-plan position-absolute">
                <span class="badge badge-{{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
        @endif
        <a href="{{ $detailsUrl }}">
            <img src="{{ $imageUrl }}" class="img-fluid item-img" alt="{{ data_get($restaurant, 'name') }}">
        </a>
    </div>

    <div class="p-3 position-relative">
        <div class="list-card-body">
            <h6 class="mb-1">
                <a href="{{ $detailsUrl }}" class="text-black">
                    {{ data_get($restaurant, 'name') }}
                </a>
            </h6>

            @if ($meta || $categoryName || $cityName)
                <p class="text-gray mb-3">
                    {{ $meta ?: collect([$categoryName, $cityName])->filter()->implode(' | ') }}
                </p>
            @endif

            @if ($time || $price)
                <p class="text-gray mb-3 time">
                    @if ($time)
                        <span class="bg-light text-dark rounded-sm pl-2 pb-1 pt-1 pr-2"><i class="icofont-wall-clock"></i> {{ $time }}</span>
                    @endif
                    @if ($price)
                        <span class="float-right text-black-50">{{ $price }}</span>
                    @endif
                </p>
            @elseif (data_get($restaurant, 'description'))
                <p class="text-gray mb-3">{{ Str::limit(data_get($restaurant, 'description'), 100) }}</p>
            @endif
        </div>

        @if ($offer)
            <div class="list-card-badge mb-3">
                <span class="badge badge-success">OFFER</span> <small>{{ $offer }}</small>
            </div>
        @endif

        <a href="{{ $detailsUrl }}" class="btn btn-primary btn-sm btn-block">View Details</a>
    </div>
</article>
