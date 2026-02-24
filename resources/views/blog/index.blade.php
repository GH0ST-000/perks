@extends('layouts.landing')

@section('content')
    @include('components.landing.header')

    <main class="pt-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Page Header with Search -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col gap-6 mb-10">
                    <div>
                        <h1 class="text-3xl font-bold dark:text-white mb-2">ბლოგი</h1>
                        <p class="text-gray-500 dark:text-gray-400">სიახლეები, გზამკვლევები და სასარგებლო ინფორმაცია</p>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('blog.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
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

                            <!-- Category Filter -->
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <select
                                    name="category"
                                    class="bg-transparent outline-none text-sm font-medium text-gray-700 dark:text-gray-200 w-full md:w-auto"
                                >
                                    <option value="All" {{ request('category') == 'All' || !request('category') ? 'selected' : '' }}>ყველა კატეგორია</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Search Button -->
                            <button
                                type="submit"
                                class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-xl text-sm font-medium transition-colors w-full md:w-auto"
                            >
                                ძებნა
                            </button>
                        </div>
                    </form>
                </div>

                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($posts as $post)
                            <article class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-600">
                                <!-- Featured Image -->
                                @if($post->featured_image)
                                    <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-video overflow-hidden">
                                        <img
                                            src="{{ asset('storage/' . $post->featured_image) }}"
                                            alt="{{ $post->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                        >
                                    </a>
                                @endif

                                <!-- Post Content -->
                                <div class="p-6">
                                    <!-- Category Badge -->
                                    @if($post->category)
                                        <span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-semibold rounded-full mb-3">
                                            {{ $post->category }}
                                        </span>
                                    @endif

                                    <!-- Title -->
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h2>

                                    <!-- Excerpt -->
                                    @if($post->excerpt)
                                        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                    @endif

                                    <!-- Meta Info -->
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-500">
                                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                                        @if($post->read_time)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $post->read_time }} წთ
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Read More Link -->
                                    <a
                                        href="{{ route('blog.show', $post->slug) }}"
                                        class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium mt-4 group"
                                    >
                                        წაიკითხე მეტი
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $posts->links() }}
                    </div>
                @else
                    <!-- No Posts Found -->
                    <div class="text-center py-16">
                        <svg class="w-20 h-20 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">სტატიები არ მოიძებნა</h3>
                        <p class="text-gray-600 dark:text-gray-400">მალე დაემატება ახალი სტატიები</p>
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('components.landing.footer')
@endsection
