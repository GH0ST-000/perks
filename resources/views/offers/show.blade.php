@extends('layouts.landing')

@section('content')
    @include('components.landing.header')

    <main class="bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Offer Detail Section -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
                    <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">მთავარი</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('offers.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">შეთავაზებები</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 dark:text-white">{{ $offer->name }}</span>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                    <!-- Offer Image -->
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800">
                            @if($offer->image)
                                <img src="{{ Storage::url($offer->image) }}" alt="{{ $offer->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=600&fit=crop" alt="{{ $offer->name }}" class="w-full h-full object-cover">
                            @endif
                        </div>

                        @if($offer->discount)
                            <div class="absolute top-6 right-6 bg-primary-500 text-white px-6 py-3 rounded-full text-2xl font-bold shadow-xl">
                                {{ $offer->discount }}% OFF
                            </div>
                        @endif
                    </div>

                    <!-- Offer Details -->
                    <div class="flex flex-col">
                        <!-- Partner Info -->
                        @if($offer->partner)
                            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                                @if($offer->partner->logo)
                                    <img src="{{ Storage::url($offer->partner->logo) }}" alt="{{ $offer->partner->name }}" class="w-16 h-16 rounded-xl object-cover shadow-md">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center shadow-md">
                                        <span class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ substr($offer->partner->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">პარტნიორი</div>
                                    <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $offer->partner->name }}</div>
                                </div>
                            </div>
                        @endif

                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $offer->name }}</h1>

                        @if($offer->header_text)
                            <p class="text-xl text-primary-600 dark:text-primary-400 font-semibold mb-6">
                                {{ $offer->header_text }}
                            </p>
                        @endif

                        @if($offer->description)
                            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                                {{ $offer->description }}
                            </p>
                        @endif

                        <!-- Offer Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">დარჩენილი დრო</span>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $offer->day_left }} დღე</div>
                            </div>

                            @if($offer->discount)
                                <div class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-4">
                                    <div class="flex items-center gap-2 text-primary-600 dark:text-primary-400 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">ფასდაკლება</span>
                                    </div>
                                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $offer->discount }}% OFF</div>
                                </div>
                            @endif
                        </div>

                        <!-- Categories -->
                        @if($offer->partner && $offer->partner->categories->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">კატეგორიები</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($offer->partner->categories as $category)
                                        <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-sm font-medium">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- CTA Button -->
                        @auth
                            <button class="w-full py-4 bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg shadow-primary-600/30 active:scale-95">
                                მიიღე შეთავაზება
                            </button>
                        @else
                            @if(Route::has('login'))
                                <a href="{{ route('login') }}" class="block w-full py-4 bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg shadow-primary-600/30 text-center active:scale-95">
                                    შეთავაზების მისაღებად გაიარეთ ავტორიზაცია
                                </a>
                            @else
                                <button disabled class="w-full py-4 bg-gray-400 text-white rounded-xl font-bold text-lg shadow-lg opacity-50 cursor-not-allowed">
                                    საჭიროა ავტორიზაცია
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Related Offers Section -->
                @if($relatedOffers->count() > 0)
                    <div class="mt-16">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">More from {{ $offer->partner->name }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($relatedOffers as $relatedOffer)
                                <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                                    <div class="relative h-48 overflow-hidden">
                                        @if($relatedOffer->image)
                                            <img src="{{ Storage::url($relatedOffer->image) }}" alt="{{ $relatedOffer->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        @else
                                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=300&fit=crop" alt="{{ $relatedOffer->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        @endif

                                        @if($relatedOffer->discount)
                                            <div class="absolute top-4 right-4 bg-primary-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                                {{ $relatedOffer->discount }}% OFF
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-5 flex flex-col flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $relatedOffer->name }}
                                        </h3>

                                        @if($relatedOffer->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                {{ $relatedOffer->description }}
                                            </p>
                                        @endif

                                        <div class="mt-auto flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $relatedOffer->day_left }} დღე</span>
                                            </div>
                                        </div>

                                        <a href="{{ route('offers.show', $relatedOffer) }}" class="mt-4 w-full px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-all duration-200 text-center text-sm active:scale-95">
                                            დეტალები
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('components.landing.footer')
@endsection
