@extends('layouts.website')

@section('title', 'Gallery')
@section('meta_description', 'Explore restaurant food, spaces, and featured gallery images.')

@section('content')
    <section class="section pt-5 pb-5 bg-white">
        <div class="container">
            <div class="section-header text-center">
                <h1>Gallery</h1>
                <p>Explore food, spaces, and highlights from our restaurants.</p>
                <span class="line"></span>
            </div>

            @if ($galleryItems->isNotEmpty())
                <div class="row">
                    @foreach ($galleryItems as $galleryItem)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <article class="bg-light rounded overflow-hidden shadow-sm h-100">
                                <img
                                    src="{{ $galleryItem->image_url }}"
                                    alt="{{ $galleryItem->title ?: $galleryItem->restaurant->name }}"
                                    class="img-fluid w-100 gallery-grid-image"
                                >

                                <div class="p-3">
                                    @if ($galleryItem->title)
                                        <h2 class="h6 mb-1">{{ $galleryItem->title }}</h2>
                                    @endif
                                    <a href="{{ route('website.restaurants.show', $galleryItem->restaurant) }}" class="text-muted">
                                        {{ $galleryItem->restaurant->name }}
                                    </a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $galleryItems->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="bg-light rounded shadow-sm p-5 text-center">
                    <i class="icofont-image icofont-3x text-muted d-block mb-3"></i>
                    <h5 class="mb-2">No gallery images yet</h5>
                    <p class="text-muted mb-0">Restaurant photos will appear here once they are published.</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .gallery-grid-image {
            height: 260px;
            object-fit: cover;
        }

        @media (max-width: 767.98px) {
            .gallery-grid-image {
                height: 220px;
            }
        }
    </style>
@endpush
