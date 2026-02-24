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

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl flex items-center gap-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl flex items-center gap-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 px-6 py-4 rounded-xl flex items-center gap-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('info') }}</span>
                    </div>
                @endif

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

                        @if($offer->standard_discount || $offer->premium_discount)
                            <div class="absolute top-6 right-6 flex flex-col gap-2">
                                @if($offer->standard_discount > 0)
                                    <div class="px-4 py-2 rounded-xl text-sm font-bold shadow-xl flex items-center gap-2" style="background-color: #C4C4C4; color: #1F2937;">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4 4a2 2 0 012-2h12a2 2 0 012 2v16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v16h12V4H6z"/>
                                        </svg>
                                        <span>-{{ $offer->standard_discount }}%</span>
                                    </div>
                                @endif
                                @if($offer->premium_discount > 0)
                                    <div class="px-4 py-2 rounded-xl text-sm font-bold shadow-xl flex items-center gap-2" style="background-color: #EFBF04; color: #1F2937;">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        <span>-{{ $offer->premium_discount }}%</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Offer Details -->
                    <div class="flex flex-col">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $offer->name }}</h1>

                        @if($offer->header_text)
                            <p class="text-xl text-primary-600 dark:text-primary-400 font-semibold mb-6">
                                {{ $offer->header_text }}
                            </p>
                        @endif

                        <!-- PRIORITY 1 & 2: Remaining Time and Discount Information -->
                        <div class="grid grid-cols-2 gap-4 mb-8 p-6 bg-gradient-to-br from-primary-50 to-purple-50 dark:from-gray-800 dark:to-gray-800 rounded-2xl border-2 border-primary-200 dark:border-primary-900/30">
                            <!-- Remaining Time -->
                            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow-sm">
                                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold uppercase tracking-wider">დარჩენილი დრო</span>
                                </div>
                                <div class="text-4xl font-black text-gray-900 dark:text-white">{{ $offer->day_left }}</div>
                                <div class="text-lg font-bold text-gray-600 dark:text-gray-300 mt-1">დღე</div>
                            </div>

                            <!-- Discount Information -->
                            <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow-sm">
                                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold uppercase tracking-wider">ფასდაკლებები</span>
                                </div>
                                <div class="flex flex-col gap-2">
                                    @if($offer->standard_discount > 0)
                                        <div class="flex items-center justify-between py-2 px-3 rounded-lg" style="background-color: rgba(196, 196, 196, 0.15);">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" style="color: #9CA3AF;">
                                                    <path d="M4 4a2 2 0 012-2h12a2 2 0 012 2v16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v16h12V4H6z"/>
                                                </svg>
                                                <span class="text-sm font-bold" style="color: #6B7280;">Standard</span>
                                            </div>
                                            <span class="text-2xl font-black" style="color: #4B5563;">-{{ $offer->standard_discount }}%</span>
                                        </div>
                                    @endif
                                    @if($offer->premium_discount > 0)
                                        <div class="flex items-center justify-between py-2 px-3 rounded-lg" style="background-color: rgba(239, 191, 4, 0.15);">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" style="color: #EFBF04;">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                                <span class="text-sm font-bold" style="color: #92400E;">Premium</span>
                                            </div>
                                            <span class="text-2xl font-black" style="color: #B45309;">-{{ $offer->premium_discount }}%</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- P-COINS REWARD - Prominent Display -->
                        @if($offer->p_coins_reward > 0)
                            <div class="mb-8 p-6 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl border-2 border-amber-300 dark:border-amber-700 shadow-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider mb-1">შენაძენის დასტურის შემდეგ</div>
                                            <div class="text-3xl font-black text-amber-900 dark:text-amber-100">
                                                +{{ $offer->p_coins_reward }} <span class="text-2xl">P-coins</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden md:flex items-center gap-2 text-amber-700 dark:text-amber-300 text-sm font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>ვიზიტის დადასტურების შემდეგ</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- PRIORITY 3: Company/Partner Information -->
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

                        @if($offer->description)
                            <div class="mb-8">
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">აღწერა</h3>
                                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                                    {{ $offer->description }}
                                </p>
                            </div>
                        @endif

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
                            <form action="{{ route('offers.claim', $offer) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg shadow-primary-600/30 active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    მიიღე შეთავაზება
                                </button>
                            </form>
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
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">სხვა შეთავაზებები <b>{{ $offer->partner->name }}</b> -სგან</h2>
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
