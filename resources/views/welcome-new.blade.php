@extends('layouts.landing')

@section('content')
    @include('components.landing.header')

    <main class="animate-fade-in">
        @include('components.landing.hero')
        @include('components.landing.offers', ['offers' => $premiumOffers])
        @include('components.landing.categories', ['categories' => $categories])
        @include('components.landing.pricing')
        @include('components.landing.testimonials')
    </main>

    @include('components.landing.footer')
@endsection
