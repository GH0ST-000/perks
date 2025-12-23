@extends('layouts.landing')

@section('title', $post->title . ' - Perks ბლოგი')

@section('content')
@include('components.landing.header')

<main class="bg-white dark:bg-gray-900 transition-colors duration-300">
<div class="animate-fade-in pb-20">
    <!-- Hero Section with Image -->
    <section class="relative pt-24 pb-12 overflow-hidden">
        @if($post->image)
            <div class="absolute inset-0 z-0">
                <img
                    src="{{ Storage::url($post->image) }}"
                    alt="{{ $post->title }}"
                    class="w-full h-full object-cover opacity-20 dark:opacity-10"
                />
                <div class="absolute inset-0 bg-gradient-to-b from-white via-white/90 to-white dark:from-gray-900 dark:via-gray-900/90 dark:to-gray-900"></div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto px-4 relative z-10 pt-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">მთავარი</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">ბლოგი</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                    <li class="text-gray-900 dark:text-white font-medium">{{ Str::limit($post->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Category & Date -->
            <div class="flex items-center gap-4 mb-6">
                @if($post->category)
                    <span class="px-4 py-2 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 text-sm font-bold rounded-full">
                        {{ $post->category }}
                    </span>
                @endif
                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm">{{ $post->published_at->locale('ka')->translatedFormat('j F, Y') }}</span>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                {{ $post->title }}
            </h1>

            <!-- Excerpt -->
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                {{ $post->excerpt }}
            </p>

            <!-- Author -->
            <div class="flex items-center gap-3 pb-8 border-b border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ substr($post->author, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ავტორი</p>
                    <p class="font-bold text-gray-900 dark:text-white">{{ $post->author }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    @if($post->image)
        <section class="max-w-5xl mx-auto px-4 mb-12">
            <div class="rounded-2xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700">
                <img
                    src="{{ Storage::url($post->image) }}"
                    alt="{{ $post->title }}"
                    class="w-full h-auto"
                />
            </div>
        </section>
    @endif

    <!-- Content -->
    <section class="max-w-4xl mx-auto px-4 mb-20">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 md:p-12 border border-gray-200 dark:border-gray-700 shadow-lg">
            <article class="prose prose-lg dark:prose-invert max-w-none 
                prose-headings:font-bold 
                prose-headings:text-gray-900 dark:prose-headings:text-white 
                prose-p:text-gray-700 dark:prose-p:text-gray-200 
                prose-p:leading-relaxed
                prose-a:text-primary-600 dark:prose-a:text-primary-400 
                prose-a:no-underline hover:prose-a:underline
                prose-strong:text-gray-900 dark:prose-strong:text-white 
                prose-strong:font-bold
                prose-li:text-gray-700 dark:prose-li:text-gray-200
                prose-ul:text-gray-700 dark:prose-ul:text-gray-200
                prose-ol:text-gray-700 dark:prose-ol:text-gray-200
                prose-blockquote:text-gray-700 dark:prose-blockquote:text-gray-200
                prose-blockquote:border-l-primary-500 dark:prose-blockquote:border-l-primary-400
                prose-code:text-gray-900 dark:prose-code:text-gray-200
                prose-code:bg-gray-100 dark:prose-code:bg-gray-700
                prose-code:px-1 prose-code:py-0.5 prose-code:rounded
                prose-pre:bg-gray-100 dark:prose-pre:bg-gray-900
                prose-pre:text-gray-900 dark:prose-pre:text-gray-200
                prose-pre:border dark:prose-pre:border-gray-700
                prose-img:rounded-xl prose-img:shadow-lg
                prose-hr:border-gray-200 dark:prose-hr:border-gray-700
                [&_p]:text-gray-700 dark:[&_p]:text-gray-200
                [&_span]:text-gray-700 dark:[&_span]:text-gray-200
                [&_div]:text-gray-700 dark:[&_div]:text-gray-200">
                {!! $post->content !!}
            </article>
        </div>
    </section>

    <!-- Share Section -->
    <section class="max-w-4xl mx-auto px-4 mb-20">
        <div class="bg-gradient-to-br from-primary-50 via-purple-50 to-pink-50 dark:from-gray-800 dark:via-gray-700 dark:to-gray-800 rounded-2xl p-8 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">გააზიარე ეს სტატია</h3>
            <div class="flex gap-4">
                <a
                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                    </svg>
                    Facebook
                </a>
                <a
                    href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center gap-2 px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-medium transition-colors"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path>
                    </svg>
                    Twitter
                </a>
                <button
                    onclick="copyToClipboard('{{ route('blog.show', $post->slug) }}')"
                    class="flex items-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-800 text-white rounded-xl font-medium transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    კოპირება
                </button>
            </div>
        </div>
    </section>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 mb-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">მსგავსი სტატიები</h2>
                <p class="text-gray-600 dark:text-gray-400">გაეცანით სხვა საინტერესო სტატიებს {{ $post->category }} კატეგორიიდან</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedPosts as $relatedPost)
                    <article class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                        <!-- Image -->
                        @if($relatedPost->image)
                            <div class="aspect-video overflow-hidden bg-gray-100 dark:bg-gray-900">
                                <img
                                    src="{{ Storage::url($relatedPost->image) }}"
                                    alt="{{ $relatedPost->title }}"
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
                            <!-- Date -->
                            <span class="text-xs text-gray-500 dark:text-gray-400 mb-2 block">
                                {{ $relatedPost->published_at->locale('ka')->translatedFormat('j F, Y') }}
                            </span>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                {{ $relatedPost->title }}
                            </h3>

                            <!-- Excerpt -->
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2 text-sm">
                                {{ $relatedPost->excerpt }}
                            </p>

                            <!-- Read More -->
                            <a
                                href="{{ route('blog.show', $relatedPost->slug) }}"
                                class="inline-flex items-center gap-1 text-primary-600 dark:text-primary-400 hover:gap-2 transition-all font-medium text-sm"
                            >
                                წაიკითხე
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- View All -->
            <div class="text-center mt-12">
                <a
                    href="{{ route('blog.index') }}"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors shadow-lg"
                >
                    ყველა სტატიის ნახვა
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </section>
    @endif
</div>
</main>

@include('components.landing.footer')

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3 min-w-[300px] max-w-md">
        <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-900 dark:text-white">ბმული დაკოპირდა!</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">ბმული წარმატებით დაკოპირდა კლიპბორდში</p>
        </div>
        <button onclick="hideToast()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            showToast();
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showToast();
            } catch (err) {
                console.error('Fallback copy failed: ', err);
            }
            document.body.removeChild(textArea);
        });
    }

    function showToast() {
        const toast = document.getElementById('toast');
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            hideToast();
        }, 3000);
    }

    function hideToast() {
        const toast = document.getElementById('toast');
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
    }
</script>
@endsection
