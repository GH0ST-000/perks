@extends('layouts.landing')

@section('title', 'ბლოგი - Perks')

@section('content')
@include('components.landing.header')

<main class="bg-white dark:bg-gray-900 transition-colors duration-300">
<div class="animate-fade-in pb-20">
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden bg-gradient-to-br from-primary-50 via-purple-50 to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-primary-600 via-purple-600 to-pink-600">
                    ბლოგი
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                    გაეცანით ახალ ამბებს, სასარგებლო გაიდებს და ბენეფიტების შესახებ ინფორმაციას
                </p>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="max-w-7xl mx-auto px-4 -mt-8 relative z-20">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('blog.index') }}" class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="ძებნა..."
                            class="w-full px-4 py-3 pl-11 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 dark:text-white"
                        />
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <select
                    name="category"
                    class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 dark:text-white"
                >
                    <option value="All">ყველა კატეგორია</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors shadow-md"
                >
                    ძებნა
                </button>

                @if(request('search') || request('category') && request('category') !== 'All')
                    <a
                        href="{{ route('blog.index') }}"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-xl font-medium transition-colors"
                    >
                        გასუფთავება
                    </a>
                @endif
            </form>
        </div>
    </section>

    <!-- Blog Posts Grid -->
    <section class="max-w-7xl mx-auto px-4 py-20">
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <article class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                        <!-- Image -->
                        @if($post->image)
                            <div class="aspect-video overflow-hidden bg-gray-100 dark:bg-gray-900">
                                <img
                                    src="{{ Storage::url($post->image) }}"
                                    alt="{{ $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                            </div>
                        @else
                            <div class="aspect-video bg-gradient-to-br from-primary-500 via-purple-500 to-pink-500 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Category & Date -->
                            <div class="flex items-center gap-3 mb-3">
                                @if($post->category)
                                    <span class="px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-xs font-bold rounded-full">
                                        {{ $post->category }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $post->published_at->locale('ka')->translatedFormat('j F, Y') }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                {{ $post->title }}
                            </h3>

                            <!-- Excerpt -->
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                {{ $post->excerpt }}
                            </p>

                            <!-- Author & Read More -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($post->author, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $post->author }}</span>
                                </div>
                                <a
                                    href="{{ route('blog.show', $post->slug) }}"
                                    class="flex items-center gap-1 text-primary-600 dark:text-primary-400 hover:gap-2 transition-all font-medium text-sm"
                                >
                                    წაიკითხე
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">პოსტები ვერ მოიძებნა</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">სცადეთ სხვა საძიებო კრიტერიები</p>
                @if(request('search') || request('category') && request('category') !== 'All')
                    <a
                        href="{{ route('blog.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors"
                    >
                        ყველა პოსტის ნახვა
                    </a>
                @endif
            </div>
        @endif
    </section>
</div>
</main>

@include('components.landing.footer')
@endsection
