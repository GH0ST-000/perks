@extends('layouts.landing')

@section('title', 'კომპანიებისთვის - Perks')

@section('content')
@include('components.landing.header')

<main class="bg-white dark:bg-gray-900 transition-colors duration-300">
<div class="animate-fade-in pb-20">
    <!-- Hero Section -->
    <div class="bg-gray-900 text-white py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-primary-900/50"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <!-- Left Content -->
                <div class="flex-1 space-y-6">
                    <h1 class="text-4xl md:text-6xl font-black leading-tight">
                        იზრუნეთ თქვენს გუნდზე <span class="text-primary-400">Perks</span>-ით.
                    </h1>
                    <p class="text-xl text-gray-300 max-w-lg">
                        თანამშრომელთა ბენეფიტების პლატფორმა, რომელიც ზრდის ლოიალობას და კმაყოფილებას.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#contact-form" class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-lg transition-colors shadow-lg">
                            მოითხოვეთ შეთავაზება
                        </a>
                        <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-600 text-gray-300 hover:text-white hover:bg-white/10 rounded-xl font-bold text-lg transition-colors">
                            გაიგე მეტი
                        </a>
                    </div>
                </div>

                <!-- Right Content - Calculator -->
                <div class="flex-1 w-full">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-3xl">
                        <h3 class="text-2xl font-bold mb-6">გამოთვალეთ დანაზოგი</h3>
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-300">თანამშრომლების რაოდენობა</span>
                                    <span class="font-bold" id="employee-count">50</span>
                                </div>
                                <input
                                    type="range"
                                    min="5"
                                    max="500"
                                    value="50"
                                    id="employee-slider"
                                    class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-primary-500"
                                />
                            </div>
                            <div class="pt-4 border-t border-white/10">
                                <div class="text-sm text-gray-400 mb-1">წლიური დანაზოგი</div>
                                <div class="text-4xl font-bold text-green-400" id="savings-amount">30,000 ₾</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div id="how-it-works" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center dark:text-white mb-4">როგორ მუშაობს?</h2>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-16 max-w-2xl mx-auto">
                მარტივი 3 ნაბიჯი თქვენი გუნდისთვის ბენეფიტების სისტემის დასაწყებად
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="relative p-8 rounded-3xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="text-6xl font-black text-gray-200 dark:text-gray-700 absolute top-4 right-6 pointer-events-none select-none">01</div>
                    <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl shadow-sm flex items-center justify-center text-primary-600 mb-6 relative z-10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold dark:text-white mb-2 relative z-10">მოთხოვნა</h3>
                    <p class="text-gray-500 dark:text-gray-400 relative z-10">შეავსეთ მარტივი ფორმა და მიიღეთ პასუხი 24 საათში</p>
                </div>

                <!-- Step 2 -->
                <div class="relative p-8 rounded-3xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="text-6xl font-black text-gray-200 dark:text-gray-700 absolute top-4 right-6 pointer-events-none select-none">02</div>
                    <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl shadow-sm flex items-center justify-center text-primary-600 mb-6 relative z-10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold dark:text-white mb-2 relative z-10">გააქტიურება</h3>
                    <p class="text-gray-500 dark:text-gray-400 relative z-10">მიიღეთ ციფრული ბარათები თქვენი თანამშრომლებისთვის</p>
                </div>

                <!-- Step 3 -->
                <div class="relative p-8 rounded-3xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-shadow">
                    <div class="text-6xl font-black text-gray-200 dark:text-gray-700 absolute top-4 right-6 pointer-events-none select-none">03</div>
                    <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl shadow-sm flex items-center justify-center text-primary-600 mb-6 relative z-10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold dark:text-white mb-2 relative z-10">სარგებელი</h3>
                    <p class="text-gray-500 dark:text-gray-400 relative z-10">თქვენი გუნდი იწყებს ბენეფიტებით სარგებლობას</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center dark:text-white mb-4">რატომ Perks?</h2>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-16 max-w-2xl mx-auto">
                აღმოაჩინეთ რა სარგებელს მოუტანს Perks თქვენს კომპანიას და თანამშრომლებს
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Company Benefits -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold dark:text-white mb-4">სარგებელი კომპანიისთვის</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">თანამშრომლების ლოიალობის და მოტივაციის ზრდა</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">გამარტივებული ადმინისტრირება HR-ისთვის</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">დეტალური რეპორტინგი და ანალიტიკა</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">მოქნილი პაკეტები და ფასები</span>
                        </li>
                    </ul>
                </div>

                <!-- Employee Benefits -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold dark:text-white mb-4">სარგებელი თანამშრომლისთვის</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">ექსკლუზიური ფასდაკლებები 100+ პრემიუმ ობიექტში</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">P-Coin დაგროვების სისტემა და საჩუქრები</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">ერთი ბარათი ყველა საჭიროებისთვის (კვება, ფიტნესი, დასვენება)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">მარტივი და ხელსაყრელი აპლიკაცია</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div id="contact-form" class="max-w-3xl mx-auto px-4 py-20">
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-gray-700 p-8 md:p-12">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold dark:text-white mb-4">მოითხოვეთ შეთავაზება</h2>
                <p class="text-gray-500 dark:text-gray-400">შეავსეთ ფორმა და ჩვენი კორპორატიული მენეჯერი დაგიკავშირდებათ</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('companies.request') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input
                        type="text"
                        name="company_name"
                        placeholder="კომპანიის სახელი"
                        required
                        value="{{ old('company_name') }}"
                        class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('company_name') border-red-500 @enderror"
                    />
                    <input
                        type="text"
                        name="contact_person"
                        placeholder="საკონტაქტო პირი"
                        required
                        value="{{ old('contact_person') }}"
                        class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('contact_person') border-red-500 @enderror"
                    />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input
                        type="email"
                        name="email"
                        placeholder="ელ-ფოსტა"
                        required
                        value="{{ old('email') }}"
                        class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('email') border-red-500 @enderror"
                    />
                    <input
                        type="tel"
                        name="phone"
                        placeholder="საკონტაქტო ტელეფონი"
                        required
                        value="{{ old('phone') }}"
                        class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('phone') border-red-500 @enderror"
                    />
                </div>
                <input
                    type="number"
                    name="employees"
                    placeholder="თანამშრომლების რაოდენობა"
                    min="1"
                    value="{{ old('employees') }}"
                    class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('employees') border-red-500 @enderror"
                />
                <button
                    type="submit"
                    class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-lg transition-colors shadow-lg"
                >
                    მოთხოვნის გაგზავნა
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Calculator functionality
    const slider = document.getElementById('employee-slider');
    const countDisplay = document.getElementById('employee-count');
    const savingsDisplay = document.getElementById('savings-amount');

    slider.addEventListener('input', function() {
        const employees = this.value;
        const savings = employees * 600; // 600 GEL per employee per year

        countDisplay.textContent = employees;
        savingsDisplay.textContent = savings.toLocaleString() + ' ₾';
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

</script>
</div>
</main>

@include('components.landing.footer')
@endsection
