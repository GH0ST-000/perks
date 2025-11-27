<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perks.ge - Employee Benefits Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
        <div class="max-w-7xl mx-auto px-4 mt-8">
            <div id="heroSlider" class="relative h-[420px] rounded-[2.5rem] overflow-hidden shadow-2xl">
                <!-- Slide 1 -->
                <div class="hero-slide absolute inset-0 transition-opacity duration-1000" data-slide="0">
                    <div class="absolute inset-0 hero-gradient z-10"></div>
                    <img src="https://images.unsplash.com/photo-1556740749-887f6717d7e4?w=1200&h=600&fit=crop" class="w-full h-full object-cover" alt="Employee Benefits">

                    <div class="absolute inset-0 z-20 flex items-center">
                        <div class="max-w-7xl mx-auto px-12 w-full">
                            <div class="max-w-2xl text-white">
                                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-bold uppercase tracking-widest mb-4 border border-white/30">
                                    Employee Benefits
                                </span>
                                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
                                    Unlock Exclusive <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-purple-400">Benefits</span> at Your Favorite Places
                                </h1>
                                <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-lg">
                                    Join thousands of employees enjoying exclusive discounts, rewards, and perks at hundreds of partner locations across Georgia.
                                </p>
                                <div class="flex gap-4">
                                    <a href="#offers" class="px-8 py-3 rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium transition-all duration-200 active:scale-95 text-lg shadow-lg">
                                        Explore Offers
                                    </a>
                                    <a href="#companies" class="px-8 py-3 rounded-lg bg-white/10 text-white border border-white/30 hover:bg-white/20 font-medium transition-all duration-200 text-lg backdrop-blur">
                                        For Companies
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-0" data-slide="1">
                    <div class="absolute inset-0 hero-gradient z-10"></div>
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=1200&h=600&fit=crop" class="w-full h-full object-cover" alt="Corporate Benefits">

                    <div class="absolute inset-0 z-20 flex items-center">
                        <div class="max-w-7xl mx-auto px-12 w-full">
                            <div class="max-w-2xl text-white">
                                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-bold uppercase tracking-widest mb-4 border border-white/30">
                                    For Companies
                                </span>
                                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
                                    Empower Your Team with <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-purple-400">Premium</span> Benefits
                                </h1>
                                <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-lg">
                                    Attract and retain top talent with comprehensive employee benefits. Easy to manage, loved by employees.
                                </p>
                                <div class="flex gap-4">
                                    <a href="#pricing" class="px-8 py-3 rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium transition-all duration-200 active:scale-95 text-lg shadow-lg">
                                        View Pricing
                                    </a>
                                    <a href="#companies" class="px-8 py-3 rounded-lg bg-white/10 text-white border border-white/30 hover:bg-white/20 font-medium transition-all duration-200 text-lg backdrop-blur">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="hero-slide absolute inset-0 transition-opacity duration-1000 opacity-0" data-slide="2">
                    <div class="absolute inset-0 hero-gradient z-10"></div>
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=1200&h=600&fit=crop" class="w-full h-full object-cover" alt="Partner Network">

                    <div class="absolute inset-0 z-20 flex items-center">
                        <div class="max-w-7xl mx-auto px-12 w-full">
                            <div class="max-w-2xl text-white">
                                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-bold uppercase tracking-widest mb-4 border border-white/30">
                                    Partner Network
                                </span>
                                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
                                    Join <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-purple-400">Hundreds</span> of Premium Partners
                                </h1>
                                <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-lg">
                                    From restaurants to hotels, fitness to wellness - discover exclusive offers from Georgia's best businesses.
                                </p>
                                <div class="flex gap-4">
                                    <a href="#partners" class="px-8 py-3 rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium transition-all duration-200 active:scale-95 text-lg shadow-lg">
                                        Become a Partner
                                    </a>
                                    <a href="#offers" class="px-8 py-3 rounded-lg bg-white/10 text-white border border-white/30 hover:bg-white/20 font-medium transition-all duration-200 text-lg backdrop-blur">
                                        View All Offers
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide Indicators -->
                <div class="absolute bottom-8 left-0 right-0 z-30 flex justify-center gap-2">
                    <button onclick="changeSlide(0)" class="slide-indicator w-8 h-2 rounded-full bg-primary-500 transition-all" data-slide="0"></button>
                    <button onclick="changeSlide(1)" class="slide-indicator w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all" data-slide="1"></button>
                    <button onclick="changeSlide(2)" class="slide-indicator w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-all" data-slide="2"></button>
                </div>
            </div>
        </div>

        @include('components.landing.offers', ['offers' => $premiumOffers])

        <!-- Categories Section -->
        <section id="categories" class="py-20 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold dark:text-white mb-4">Explore by Category</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">Discover exclusive offers across all your favorite categories</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <a href="#" class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all text-center group border border-gray-100 dark:border-gray-600 hover:scale-105">
                        <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Restaurant</h3>
                    </a>

                    <a href="#" class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all text-center group border border-gray-100 dark:border-gray-600 hover:scale-105">
                        <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Hotel</h3>
                    </a>

                    <a href="#" class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all text-center group border border-gray-100 dark:border-gray-600 hover:scale-105">
                        <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Fitness</h3>
                    </a>

                    <a href="#" class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all text-center group border border-gray-100 dark:border-gray-600 hover:scale-105">
                        <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Wellness</h3>
                    </a>

                    <a href="#" class="bg-white dark:bg-gray-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-all text-center group border border-gray-100 dark:border-gray-600 hover:scale-105">
                        <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Entertainment</h3>
                    </a>
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
        <section id="testimonials" class="py-20 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-8 text-primary-600">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold dark:text-white mb-12">What Our Members Say</h2>

                <div class="relative min-h-[200px] flex flex-col items-center justify-center animate-fade-in">
                    <p class="text-2xl font-medium text-gray-800 dark:text-gray-200 italic mb-8 leading-relaxed">
                        "Perks has transformed how we offer benefits to our employees. The platform is easy to use and our team loves the variety of exclusive offers available."
                    </p>
                    <div>
                        <div class="font-bold text-gray-900 dark:text-white">Giorgi Beridze</div>
                        <div class="text-gray-500 text-sm">HR Director, TechCorp Georgia</div>
                    </div>
                </div>

                <div class="flex justify-center gap-2 mt-8">
                    <button class="w-8 h-2 rounded-full bg-primary-500 transition-all"></button>
                    <button class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700 transition-all"></button>
                    <button class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700 transition-all"></button>
                </div>
            </div>
        </section>
    </main>

    @include('components.landing.footer')

    @include('layouts.scripts')
</body>
</html>
