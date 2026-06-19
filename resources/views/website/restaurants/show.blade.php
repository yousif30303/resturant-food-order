@extends('layouts.website')

@section('title', $restaurant->name)
@section('meta_description', Str::limit($restaurant->description ?? 'View restaurant details and menu information.', 155))

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
        use Illuminate\Support\Str;

        $imagePath = data_get($restaurant, 'image_url')
            ?? data_get($restaurant, 'image_path')
            ?? data_get($restaurant, 'logo_path')
            ?? data_get($restaurant, 'image')
            ?? data_get($restaurant, 'logo');

        $restaurantImage = $imagePath
            ? (Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : Storage::url($imagePath))
            : asset('website/assets/img/list/1.png');
    @endphp

    <section class="section pt-5 pb-5 bg-white border-bottom">
        <div class="container">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
                <nav aria-label="Restaurant breadcrumb" class="mb-3 mb-sm-0">
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('website.restaurants.index') }}">Restaurants</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $restaurant->name }}</li>
                    </ol>
                </nav>

                <a href="{{ route('website.restaurants.index') }}" class="btn btn-light border rounded-pill shadow-sm px-3">
                    <i class="icofont-rounded-left mr-1"></i> All restaurants
                </a>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <img src="{{ $restaurantImage }}" alt="{{ $restaurant->name }}" class="img-fluid rounded shadow-sm w-100">
                </div>

                <div class="col-lg-7">
                    <div class="section-header mb-3">
                        <h1>{{ $restaurant->name }}</h1>
                        <p class="mb-0">
                            {{ collect([$restaurant->category?->name, $restaurant->city?->name])->filter()->implode(' | ') }}
                        </p>
                    </div>

                    <div class="row">
                        @if ($restaurant->category)
                            <div class="col-sm-6 mb-3">
                                <div class="bg-light rounded p-3 h-100">
                                    <small class="text-muted d-block">Category</small>
                                    <strong>{{ $restaurant->category->name }}</strong>
                                </div>
                            </div>
                        @endif

                        @if ($restaurant->city)
                            <div class="col-sm-6 mb-3">
                                <div class="bg-light rounded p-3 h-100">
                                    <small class="text-muted d-block">City</small>
                                    <strong>{{ $restaurant->city->name }}</strong>
                                </div>
                            </div>
                        @endif

                        @if ($restaurant->phone)
                            <div class="col-sm-6 mb-3">
                                <div class="bg-light rounded p-3 h-100">
                                    <small class="text-muted d-block">Phone</small>
                                    <strong>{{ $restaurant->phone }}</strong>
                                </div>
                            </div>
                        @endif

                        @if ($restaurant->address)
                            <div class="col-sm-6 mb-3">
                                <div class="bg-light rounded p-3 h-100">
                                    <small class="text-muted d-block">Address</small>
                                    <strong>{{ $restaurant->address }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="bg-white rounded shadow-sm p-4 h-100">
                        <h2 class="h4 mb-3">About {{ $restaurant->name }}</h2>
                        <p class="text-muted mb-0">
                            {{ $restaurant->description ?: 'Restaurant description will appear here once it is available.' }}
                        </p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-white rounded shadow-sm p-4 h-100">
                        <h2 class="h4 mb-3">Restaurant Info</h2>
                        <ul class="list-unstyled mb-0">
                            @if ($restaurant->category)
                                <li class="mb-2"><strong>Category:</strong> {{ $restaurant->category->name }}</li>
                            @endif
                            @if ($restaurant->city)
                                <li class="mb-2"><strong>City:</strong> {{ $restaurant->city->name }}</li>
                            @endif
                            @if ($restaurant->email)
                                <li class="mb-2"><strong>Email:</strong> {{ $restaurant->email }}</li>
                            @endif
                            @if ($restaurant->phone)
                                <li><strong>Phone:</strong> {{ $restaurant->phone }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-5 pb-5 bg-white">
        <div class="container">
            <div class="section-header text-center">
                <h2>Gallery</h2>
                <p>Preview food, spaces, and restaurant highlights.</p>
                <span class="line"></span>
            </div>

            @if ($galleryItems->isNotEmpty())
                <div class="row">
                    @foreach ($galleryItems as $galleryItem)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <img
                                src="{{ $galleryItem['image_url'] ?? asset('website/assets/img/list/1.png') }}"
                                alt="{{ $galleryItem['title'] ?? $restaurant->name }}"
                                class="img-fluid rounded shadow-sm w-100"
                            >
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-light rounded shadow-sm p-5 text-center">
                    <h5 class="mb-2">No gallery photos yet</h5>
                    <p class="text-muted mb-0">Photos from this restaurant will appear here once they are available.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
