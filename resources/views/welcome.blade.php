<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perks.ge - Employee Benefits Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Load theme immediately to prevent flash -->
    <script>
        // Load theme preference before page renders
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'fade-in-up': 'fadeInUp 0.5s ease-in-out',
                        'fade-in-down': 'fadeInDown 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .hero-gradient {
            background: linear-gradient(to right, rgba(0,0,0,0.8), transparent);
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-300 font-sans">
    @include('components.landing.header')

    <main class="animate-fade-in">
        <!-- Hero Section -->
        @if(isset($sliders) && $sliders->count() > 0)
            <div class="max-w-7xl mx-auto px-4 mt-8">
                <div id="heroSlider" class="relative h-[420px] rounded-[2.5rem] overflow-hidden shadow-2xl">
                    @foreach($sliders as $index => $slider)
                        <div class="hero-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? '' : 'opacity-0' }}" data-slide="{{ $index }}">
                            <div class="absolute inset-0 hero-gradient z-10"></div>
                            @if($slider->background_image)
                                <img src="{{ Storage::url($slider->background_image) }}" class="w-full h-full object-cover" alt="{{ $slider->title ?? 'Slider' }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
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

                    <!-- Slide Indicators -->
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

        <!-- Categories Section -->
        <section id="categories" class="py-20 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold dark:text-white mb-4">კატეგორიები</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">იპოვე ზუსტად ის, რაც გჭირდება ჩვენი პარტნიორებისგან.</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @forelse($categories as $category)
                        <a href="{{ route('offers.index', ['category' => $category->id]) }}" class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all text-center group border border-gray-100 dark:border-gray-600 hover:scale-105">
                            <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-600 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-{{ $category->icon ?? 'circle' }} text-xl"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 dark:text-gray-200">{{ $category->name }}</h3>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            კატეგორიები არ მოიძებნა
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-20 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold dark:text-white mb-4">აირჩიეთ თქვენი პაკეტი</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">საუკეთესო პირობები ინდივიდუალური და კორპორატიული წევრებისთვის</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <!-- Basic Plan -->
                    <div class="rounded-3xl p-8 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 relative hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                        <h3 class="text-xl font-bold dark:text-white mb-2">სტანდარტი</h3>
                        <p class="text-gray-500 text-sm mb-6">იდეალურია ინდივიდუალური გამოყენებისთვის</p>
                        <div class="flex items-end gap-1 mb-8">
                            <span class="text-4xl font-bold dark:text-white">29₾</span>
                            <span class="text-gray-500 mb-1">/თვეში</span>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big text-green-500 shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                წვდომა ყველა შეთავაზებაზე
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big text-green-500 shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                მობილური აპლიკაცია
                            </li>

                            <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big text-green-500 shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                24/7 მხარდაჭერა
                            </li>
                        </ul>

                        <button class="w-full py-4 px-5 rounded-lg font-medium transition-all duration-200 active:scale-95 text-sm flex items-center justify-center gap-2 border-2 border-primary-600 text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:border-primary-500 dark:hover:bg-gray-800">
                            მოთხოვნა
                        </button>
                    </div>

                    <!-- Business Plan -->
                    <div class="rounded-3xl p-8 border border-primary-600 bg-primary-50/50 dark:bg-primary-900/10 relative hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-primary-600 text-white px-4 py-1 rounded-full text-xs font-bold uppercase">
                            ყველაზე პოპულარული
                        </div>
                        <h3 class="text-xl font-bold dark:text-white mb-2">პრემიუმი</h3>
                        <p class="text-gray-500 text-sm mb-6">საუკეთესო არჩევანი ოჯახისთვის</p>
                        <div class="flex items-end gap-1 mb-8">
                            <span class="text-4xl font-bold dark:text-white">49₾</span>
                            <span class="text-gray-500 mb-1">/თვეში</span>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big text-green-500 shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                წვდომა ყველა შეთავაზებაზე
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big text-green-500 shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                მობილური აპლიკაცია

                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big text-green-500 shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                24/7 მხარდაჭერა

                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big text-green-500 shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                ოჯახის წევრის დამატება

                            </li>

                        </ul>

                        <button class="w-full py-4 px-5 rounded-lg font-medium transition-all duration-200 active:scale-95 text-sm flex items-center justify-center gap-2 bg-primary-600 text-white hover:bg-primary-700 shadow-md shadow-primary-600/20 dark:shadow-none">
                            მოთხოვნა
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
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

    @include('layouts.scripts')
</body>
</html>
