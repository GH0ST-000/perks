@extends('layouts.landing')

@section('content')
    @include('components.landing.header')

    <main class="bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Page Header with Filters -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col gap-6 mb-10">
                    <div>
                        <h1 class="text-3xl font-bold dark:text-white mb-2">შეთავაზებები</h1>
                        <p class="text-gray-500">იპოვე ზუსტად ის, რაც გჭირდება ჩვენი პარტნიორებისგან</p>
                    </div>

                    <!-- Unified Filter Bar -->
                    <form method="GET" action="{{ route('offers.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <!-- Search Input -->
                            <div class="relative flex-1 w-full">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="ძებნა..."
                                    value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-700 dark:text-white rounded-xl outline-none focus:ring-2 focus:ring-primary-500 text-sm"
                                />
                            </div>

                            <div class="w-px h-8 bg-gray-200 dark:bg-gray-700 hidden md:block"></div>

                            <!-- Location Filter -->
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <select
                                    name="location"
                                    class="bg-transparent outline-none text-sm font-medium text-gray-700 dark:text-gray-200 w-full md:w-auto"
                                    onchange="this.form.submit()"
                                >
                                    <option value="All" {{ request('location') == 'All' || !request('location') ? 'selected' : '' }}>ყველა ლოკაცია</option>
                                    <option value="Tbilisi" {{ request('location') == 'Tbilisi' ? 'selected' : '' }}>თბილისი</option>
                                    <option value="Batumi" {{ request('location') == 'Batumi' ? 'selected' : '' }}>ბათუმი</option>
                                    <option value="Kutaisi" {{ request('location') == 'Kutaisi' ? 'selected' : '' }}>ქუთაისი</option>
                                </select>
                            </div>

                            <!-- Search Button -->
                            <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium text-sm transition-colors w-full md:w-auto">
                                ძებნა
                            </button>
                        </div>
                    </form>

                    <!-- Category Tabs -->
                    <div class="flex gap-6 overflow-x-auto pb-2 scrollbar-hide border-b border-gray-100 dark:border-gray-700">
                        <a href="{{ route('offers.index', array_merge(request()->except('category'), ['category' => 'All'])) }}"
                           class="whitespace-nowrap pb-2 text-sm font-bold transition-all border-b-2 {{ (!request('category') || request('category') == 'All') ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:text-gray-400' }}">
                            ყველა
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('offers.index', array_merge(request()->except('category'), ['category' => $category->id])) }}"
                               class="whitespace-nowrap pb-2 text-sm font-bold transition-all border-b-2 {{ request('category') == $category->id ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800 dark:text-gray-400' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($offers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($offers as $offer)
                            <a href="{{ route('offers.show', $offer) }}" class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                                <div class="relative h-48 overflow-hidden">
                                    @if($offer->image)
                                        <img src="{{ Storage::url($offer->image) }}" alt="{{ $offer->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=300&fit=crop" alt="{{ $offer->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    @endif

                                    @if($offer->discount)
                                        <div class="absolute top-4 right-4 bg-primary-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                            {{ $offer->discount }}% ფასდაკლება
                                        </div>
                                    @endif
                                </div>

                                <div class="p-5 flex flex-col flex-1">
                                    @if($offer->partner)
                                        <div class="flex items-center gap-2 mb-3">
                                            @if($offer->partner->logo)
                                                <img src="{{ Storage::url($offer->partner->logo) }}" alt="{{ $offer->partner->name }}" class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ substr($offer->partner->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $offer->partner->name }}</span>
                                        </div>
                                    @endif

                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $offer->name }}
                                    </h3>

                                    @if($offer->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                            {{ $offer->description }}
                                        </p>
                                    @endif

                                    <div class="mt-auto flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $offer->day_left }} დღე</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $offers->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-gray-50 dark:bg-gray-800 rounded-3xl">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">შეთავაზებები არ მოიძებნა</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-8">სცადეთ სხვა კატეგორია ან ფილტრი</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            მთავარ გვერდზე დაბრუნება
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('components.landing.footer')
@endsection
