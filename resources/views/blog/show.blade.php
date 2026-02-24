@extends('layouts.landing')

@section('content')
    @include('components.landing.header')

    <main class="pt-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <article class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <!-- Category Badge -->
                    @if($post->category)
                        <span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-semibold rounded-full mb-4">
                            {{ $post->category }}
                        </span>
                    @endif

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ $post->title }}
                    </h1>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-4 text-gray-600 dark:text-gray-400 mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $post->published_at->format('F d, Y') }}</span>
                        </div>
                        @if($post->read_time)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $post->read_time }} წუთი</span>
                            </div>
                        @endif
                        @if($post->author)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>{{ $post->author }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Featured Image (Properly Sized) -->
                    @if($post->featured_image)
                        <div class="mb-8 rounded-2xl overflow-hidden">
                            <img
                                src="{{ asset('storage/' . $post->featured_image) }}"
                                alt="{{ $post->title }}"
                                class="w-full h-auto object-contain"
                                style="max-height: 400px;"
                            >
                        </div>
                    @endif

                    <!-- Excerpt -->
                    @if($post->excerpt)
                        <div class="text-lg text-gray-700 dark:text-gray-300 mb-8 leading-relaxed bg-gray-50 dark:bg-gray-800 p-6 rounded-xl border-l-4 border-primary-500">
                            {{ $post->excerpt }}
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="prose prose-lg dark:prose-invert max-w-none mb-12 text-gray-800 dark:text-gray-200">
                        <div class="space-y-4 leading-relaxed text-base">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($post->tags && count($post->tags) > 0)
                        <div class="flex flex-wrap gap-2 mb-12 pb-12 border-b border-gray-200 dark:border-gray-700">
                            @foreach($post->tags as $tag)
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Share Buttons -->
                    <div class="mb-12">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">გაზიარება</h3>
                        <div class="flex gap-3">
                            <a
                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                                target="_blank"
                                class="flex items-center justify-center w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-colors"
                                title="Share on Facebook"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                                </svg>
                            </a>
                            <a
                                href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                                target="_blank"
                                class="flex items-center justify-center w-10 h-10 bg-sky-500 hover:bg-sky-600 text-white rounded-full transition-colors"
                                title="Share on Twitter"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path>
                                </svg>
                            </a>
                            <a
                                href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->slug)) }}&title={{ urlencode($post->title) }}"
                                target="_blank"
                                class="flex items-center justify-center w-10 h-10 bg-blue-700 hover:bg-blue-800 text-white rounded-full transition-colors"
                                title="Share on LinkedIn"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <section class="py-12 bg-gray-50 dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">მსგავსი სტატიები</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $relatedPost)
                                <article class="bg-white dark:bg-gray-900 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                                    @if($relatedPost->featured_image)
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="block aspect-video overflow-hidden">
                                            <img
                                                src="{{ asset('storage/' . $relatedPost->featured_image) }}"
                                                alt="{{ $relatedPost->title }}"
                                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                            >
                                        </a>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}">{{ $relatedPost->title }}</a>
                                        </h3>
                                        @if($relatedPost->excerpt)
                                            <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-3">{{ $relatedPost->excerpt }}</p>
                                        @endif
                                        <a
                                            href="{{ route('blog.show', $relatedPost->slug) }}"
                                            class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium text-sm inline-flex items-center gap-1"
                                        >
                                            წაიკითხე მეტი
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Back to Blog Link -->
        <div class="py-8 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <a
                        href="{{ route('blog.index') }}"
                        class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        დაბრუნება ბლოგზე
                    </a>
                </div>
            </div>
        </div>
    </main>

    @include('components.landing.footer')
@endsection
