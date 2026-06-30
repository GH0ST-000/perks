@extends('layouts.landing')

@section('title', 'Perks - ბენეფიტები თანამშრომლებისთვის')

@php
    use Illuminate\Support\Facades\Storage;
    $lcpSlider = isset($sliders) && $sliders->count() > 0 ? $sliders->first() : null;
@endphp

@if($lcpSlider?->background_image)
@push('head')
    <link rel="preload" as="image" href="{{ Storage::url($lcpSlider->background_image) }}" fetchpriority="high">
@endpush
@endif

@section('content')
@include('components.landing.header')

<main class="animate-fade-in">
    @if(isset($sliders) && $sliders->count() > 0)
        <div class="max-w-7xl mx-auto px-4 mt-8">
            <div id="heroSlider" class="relative h-[420px] rounded-[2.5rem] overflow-hidden shadow-2xl">
                @foreach($sliders as $index => $slider)
                    <div class="hero-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? '' : 'opacity-0' }}" data-slide="{{ $index }}">
                        <div class="absolute inset-0 hero-gradient z-10"></div>
                        @if($slider->background_image)
                            <img
                                src="{{ Storage::url($slider->background_image) }}"
                                class="w-full h-full object-cover"
                                alt="{{ $slider->title ?? 'Slider' }}"
                                width="1280"
                                height="420"
                                @if($index === 0) fetchpriority="high" decoding="async" @else loading="lazy" @endif
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                            >
                            <div class="w-full h-full bg-gradient-to-br from-primary-600 to-purple-600 hidden"></div>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-600 to-purple-600"></div>
                        @endif

                        <div class="absolute inset-0 z-20 flex items-center">
                            <div class="max-w-7xl mx-auto px-12 w-full">
                                <div class="max-w-2xl text-white">
                                    @if($slider->tag_text)
                                        <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-bold uppercase tracking-widest mb-4 border border-white/30">
                                            {{ $slider->tag_text }}
                                        </span>
                                    @endif

                                    <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
                                        @if($slider->headline_before)
                                            {{ $slider->headline_before }}
                                        @endif
                                        @if($slider->headline_highlight)
                                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-purple-400">{{ $slider->headline_highlight }}</span>
                                        @endif
                                        @if($slider->headline_after)
                                            {{ $slider->headline_after }}
                                        @endif
                                        @if(!$slider->headline_before && !$slider->headline_highlight && !$slider->headline_after && $slider->title)
                                            {{ $slider->title }}
                                        @endif
                                    </h1>

                                    @if($slider->sub_headline)
                                        <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-lg">
                                            {{ $slider->sub_headline }}
                                        </p>
                                    @elseif($slider->description)
                                        <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-lg">
                                            {{ $slider->description }}
                                        </p>
                                    @endif

                                    <div class="flex gap-4">
                                        @if($slider->button1_text && $slider->button1_link)
                                            <a href="{{ $slider->button1_link }}" class="px-8 py-3 rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium transition-all duration-200 active:scale-95 text-lg shadow-lg">
                                                {{ $slider->button1_text }}
                                            </a>
                                        @endif
                                        @if($slider->button2_text && $slider->button2_link)
                                            <a href="{{ $slider->button2_link }}" class="px-8 py-3 rounded-lg bg-white/10 text-white border border-white/30 hover:bg-white/20 font-medium transition-all duration-200 text-lg backdrop-blur">
                                                {{ $slider->button2_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($sliders->count() > 1)
                    <div class="absolute bottom-8 left-0 right-0 z-30 flex justify-center gap-2">
                        @foreach($sliders as $index => $slider)
                            <button onclick="changeSlide({{ $index }})" class="slide-indicator {{ $index === 0 ? 'w-8 bg-primary-500' : 'w-2 bg-white/50 hover:bg-white' }} h-2 rounded-full transition-all" data-slide="{{ $index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    @include('components.landing.offers', ['offers' => $premiumOffers])

    <section id="categories" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold dark:text-white mb-4">კატეგორიები</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">აირჩიე სასურველი კატეგორია</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-5">
                @forelse($categories as $category)
                    <a href="{{ route('offers.index', ['category' => $category->id]) }}" class="bg-white dark:bg-gray-700 p-5 md:p-6 rounded-2xl shadow-sm hover:shadow-md transition-all text-center group border border-gray-100 dark:border-gray-600 hover:-translate-y-0.5">
                        <div class="mx-auto mb-4 group-hover:scale-105 transition-transform">
                            @if($category->image)
                                <x-category-icon
                                    :src="$category->imageUrl()"
                                    :alt="$category->name"
                                    size="lg"
                                    class="mx-auto"
                                />
                            @elseif($category->icon)
                                <i class="fa-solid fa-{{ $category->icon }} text-2xl text-primary-600"></i>
                            @else
                                <i class="fa-solid fa-circle text-2xl text-gray-300"></i>
                            @endif
                        </div>
                        <h3 class="font-bold text-sm md:text-base text-gray-800 dark:text-gray-200 leading-snug">{{ $category->name }}</h3>
                    </a>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        კატეგორიები არ მოიძებნა
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="pricing" class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold dark:text-white mb-4">აირჩიე პაკეტი</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">ყოველთვიური ბენეფიტების პაკეტი</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="rounded-3xl p-8 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 relative hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <h3 class="text-xl font-bold dark:text-white mb-2">Member</h3>
                    <p class="text-gray-500 text-sm mb-6"></p>
                    <div class="flex items-end gap-1 mb-8">
                        <span class="text-4xl font-bold dark:text-white">{{ number_format(config('perks.membership_plans.member.amount', 19), 0) }}₾</span>
                        <span class="text-gray-500 mb-1">/თვეში</span>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            სტანდარტული ფასდაკლებები ობიექტებში
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            ექსკლუზიური შეთავაზებები
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            {{ config('perks.membership_plans.member.p_coins_label') }}
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            ოჯახის წევრის დამატება
                        </li>
                    </ul>

                    <a href="{{ route('companies') }}#contact-form" class="w-full py-4 px-5 rounded-lg font-medium transition-all duration-200 active:scale-95 text-sm flex items-center justify-center gap-2 border-2 border-primary-600 text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:border-primary-500 dark:hover:bg-gray-800">
                        მოთხოვნა
                    </a>
                </div>

                <div class="rounded-3xl p-8 border border-primary-600 bg-primary-50/50 dark:bg-primary-900/10 relative hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-primary-600 text-white px-4 py-1 rounded-full text-xs font-bold uppercase">
                        ყველაზე პოპულარული
                    </div>
                    <h3 class="text-xl font-bold dark:text-white mb-2">Limited</h3>
                    <p class="text-gray-500 text-sm mb-6"></p>
                    <div class="flex items-end gap-1 mb-8">
                        <span class="text-4xl font-bold dark:text-white">{{ number_format(config('perks.membership_plans.limited.amount', 29), 0) }}₾</span>
                        <span class="text-gray-500 mb-1">/თვეში</span>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            პრემიუმ ფასდაკლებები ობიექტებში
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            ექსკლუზიური შეთავაზებები
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            {{ config('perks.membership_plans.limited.p_coins_label') }}
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            პერსონალური ასისტენტი
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 shrink-0"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            ოჯახის წევრის დამატება
                        </li>
                    </ul>

                    <a href="{{ route('companies') }}#contact-form" class="w-full py-4 px-5 rounded-lg font-medium transition-all duration-200 active:scale-95 text-sm flex items-center justify-center gap-2 bg-primary-600 text-white hover:bg-primary-700 shadow-md shadow-primary-600/20 dark:shadow-none">
                        მოთხოვნა
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if(isset($testimonials) && $testimonials->count() > 0)
        <section id="testimonials" class="py-20 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-8 text-primary-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold dark:text-white mb-12">რას ამბობენ ჩვენზე</h2>

                <div class="relative min-h-[200px] overflow-hidden">
                    @foreach($testimonials as $index => $testimonial)
                        <div class="testimonial-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }} flex flex-col items-center justify-center" data-testimonial="{{ $index }}">
                            <p class="text-2xl font-medium text-gray-800 dark:text-gray-200 italic mb-8 leading-relaxed text-center max-w-3xl">
                                "{{ $testimonial->quote }}"
                            </p>
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white">{{ $testimonial->author_name }}</div>
                                @if($testimonial->author_position || $testimonial->company_name)
                                    <div class="text-gray-500 text-sm">
                                        @if($testimonial->author_position && $testimonial->company_name)
                                            {{ $testimonial->author_position }}, {{ $testimonial->company_name }}
                                        @elseif($testimonial->author_position)
                                            {{ $testimonial->author_position }}
                                        @elseif($testimonial->company_name)
                                            {{ $testimonial->company_name }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($testimonials->count() > 1)
                    <div class="flex justify-center gap-2 mt-8">
                        @foreach($testimonials as $index => $testimonial)
                            <button onclick="changeTestimonial({{ $index }})" class="testimonial-indicator {{ $index === 0 ? 'w-8 bg-primary-500' : 'w-2 bg-gray-300 dark:bg-gray-700' }} h-2 rounded-full transition-all" data-index="{{ $index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif
</main>

@include('components.landing.footer')
@endsection
