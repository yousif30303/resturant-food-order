@extends('layouts.website')

@section('title', 'Restaurants')
@section('meta_description', 'Browse available restaurants and discover menus for online food ordering.')

@section('content')
    <section class="section pt-5 pb-5 bg-white">
        <div class="container">
            <div class="section-header text-center">
                <h1>Restaurants</h1>
                <p>Browse approved restaurants by city and category.</p>
                <span class="line"></span>
            </div>

            <form method="GET" action="{{ route('website.restaurants.index') }}" class="bg-light rounded shadow-sm p-3 mb-4">
                <div class="form-row align-items-end">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <label for="city" class="font-weight-bold">City</label>
                        <select name="city" id="city" class="custom-select">
                            <option value="">All cities</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->slug }}" @selected($filters['city'] === $city->slug)>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5 mb-3 mb-md-0">
                        <label for="category" class="font-weight-bold">Category</label>
                        <select name="category" id="category" class="custom-select">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected($filters['category'] === $category->slug)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        @if ($filters['city'] || $filters['category'])
                            <a href="{{ route('website.restaurants.index') }}" class="btn btn-link btn-block mt-2">Clear</a>
                        @endif
                    </div>
                </div>
            </form>

            @if ($restaurants->isNotEmpty())
                <div class="row">
                    @foreach ($restaurants as $restaurant)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            @include('website.partials.restaurant-card', ['restaurant' => $restaurant])
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $restaurants->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="bg-light rounded shadow-sm p-5 text-center">
                    <h5 class="mb-2">No restaurants found</h5>
                    <p class="text-muted mb-3">Try changing the city or category filter.</p>
                    <a href="{{ route('website.restaurants.index') }}" class="btn btn-primary">View all restaurants</a>
                </div>
            @endif
        </div>
    </section>
@endsection
