@extends('layouts.website')

@section('title', $restaurant->name)
@section('meta_description', $restaurant->description ?? 'View restaurant details and menu information.')

@section('content')
    <section class="section pt-5 pb-5 bg-white">
        <div class="container">
            <div class="section-header">
                <h1>{{ $restaurant->name }}</h1>
                @if ($restaurant->description)
                    <p>{{ $restaurant->description }}</p>
                @endif
            </div>
        </div>
    </section>
@endsection
