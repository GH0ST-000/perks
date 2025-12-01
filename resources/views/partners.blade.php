@extends('layouts.landing')

@section('title', 'პარტნიორებისთვის - Perks')

@section('content')
@include('components.landing.header')

<main class="bg-white dark:bg-gray-900 transition-colors duration-300">
<div class="animate-fade-in pb-20">
    <!-- Hero Section -->
    <div class="bg-primary-50 dark:bg-gray-900 py-24 border-b border-primary-100 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 font-bold text-sm mb-6">
                ბიზნეს პარტნიორებისთვის
            </span>
            <h1 class="text-4xl md:text-6xl font-black mb-6 text-gray-900 dark:text-white">
                გაზარდეთ თქვენი ბიზნესი <span class="text-primary-600">Perks</span>-თან ერთად
            </h1>
            <p class="text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto mb-10">
                მიიღეთ წვდომა ათასობით გადახდისუნარიან კორპორატიულ კლიენტზე და მართეთ თქვენი მარკეტინგი ეფექტურად
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#partner-form" class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-lg transition-colors shadow-lg">
                    გახდი პარტნიორი
                </a>
                <a href="#benefits" class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-xl font-bold text-lg transition-colors">
                    გაიგე მეტი
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="max-w-7xl mx-auto px-4 -mt-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stat 1 -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 text-center">
                <div class="text-4xl font-black text-primary-600 mb-2">30%</div>
                <div class="font-bold text-gray-900 dark:text-white mb-1">გაზრდილი გაყიდვები</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">საშუალო გაყიდვების ზრდა</div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 text-center">
                <div class="text-4xl font-black text-primary-600 mb-2">50k+</div>
                <div class="font-bold text-gray-900 dark:text-white mb-1">მიზნობრივი კლიენტურა</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">აქტიური მომხმარებელი</div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 text-center">
                <div class="text-4xl font-black text-primary-600 mb-2">100%</div>
                <div class="font-bold text-gray-900 dark:text-white mb-1">ტრაფიკის კონტროლი</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">მონაცემების ხილვადობა</div>
            </div>
        </div>
    </div>

    <!-- Marketing Tools Section -->
    <div id="benefits" class="max-w-7xl mx-auto px-4 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold dark:text-white mb-4">მარკეტინგული მხარდაჭერა</h2>
            <p class="text-gray-500 dark:text-gray-400">ძლიერი ხელსაწყოები თქვენი ბიზნესის განსავითარებლად</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Tool 1 -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 transition-colors">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold dark:text-white mb-2">Push შეტყობინებები</h3>
                <p class="text-gray-500 dark:text-gray-400">პირდაპირი კომუნიკაცია მომხმარებელთან</p>
            </div>

            <!-- Tool 2 -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 transition-colors">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold dark:text-white mb-2">სოციალური მედია</h3>
                <p class="text-gray-500 dark:text-gray-400">პოსტები ჩვენს გვერდებზე</p>
            </div>

            <!-- Tool 3 -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 hover:border-primary-200 dark:hover:border-primary-800 transition-colors">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold dark:text-white mb-2">Email მარკეტინგი</h3>
                <p class="text-gray-500 dark:text-gray-400">ყოველთვიური დაიჯესტი</p>
            </div>
        </div>
    </div>

    <!-- Why Partner Section -->
    <div class="bg-gray-50 dark:bg-gray-800/50 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold dark:text-white mb-4">რატომ უნდა გახდეთ Perks პარტნიორი?</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">აღმოაჩინეთ სარგებელი, რომელსაც მიიღებთ ჩვენს პლატფორმაში</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Benefit 1 -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold dark:text-white mb-2">გაზრდილი გაყიდვები</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">გაყიდვების 30%-იანი ზრდა პირველ 3 თვეში</p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold dark:text-white mb-2">კორპორატიული კლიენტები</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">წვდომა გადახდისუნარიან მომხმარებლებზე</p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold dark:text-white mb-2">დეტალური ანალიტიკა</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">პანელში იხილეთ ყველა მონაცემი</p>
                </div>

                <!-- Benefit 4 -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold dark:text-white mb-2">პრემიუმ ხილვადობა</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">თქვენი ბრენდი პრემიუმ კატალოგში</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold dark:text-white mb-4">როგორ გავხდეთ პარტნიორი?</h2>
                <p class="text-gray-500 dark:text-gray-400">მარტივი 4 ნაბიჯი თქვენი ბიზნესის დასაწყებად Perks-ზე</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 mx-auto mb-4 text-2xl font-black">
                        01
                    </div>
                    <h3 class="text-lg font-bold dark:text-white mb-2">განაცხადი</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">შეავსეთ პარტნიორის ფორმა</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 mx-auto mb-4 text-2xl font-black">
                        02
                    </div>
                    <h3 class="text-lg font-bold dark:text-white mb-2">შემოწმება</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ჩვენი გუნდი შეამოწმებს განაცხადს</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 mx-auto mb-4 text-2xl font-black">
                        03
                    </div>
                    <h3 class="text-lg font-bold dark:text-white mb-2">შეთანხმება</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">პარტნიორობის ხელშეკრულება</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center text-primary-600 dark:text-primary-400 mx-auto mb-4 text-2xl font-black">
                        04
                    </div>
                    <h3 class="text-lg font-bold dark:text-white mb-2">გაშვება</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">თქვენი ობიექტი გამოჩნდება აპში</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Partner Form Section -->
    <div id="partner-form" class="bg-gray-50 dark:bg-gray-800/50 py-20">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-gray-700 p-8 md:p-12">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold dark:text-white mb-4">გახდი პარტნიორი</h2>
                    <p class="text-gray-500 dark:text-gray-400">დაგვიტოვეთ საკონტაქტო ინფორმაცია და ჩვენ დაგიკავშირდებით</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('partners.request') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input
                            type="text"
                            name="business_name"
                            placeholder="კომპანიის / ობიექტის სახელი"
                            required
                            value="{{ old('business_name') }}"
                            class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('business_name') border-red-500 @enderror"
                        />
                        <select
                            name="category"
                            required
                            class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('category') border-red-500 @enderror"
                        >
                            <option value="">ბიზნესის კატეგორია</option>
                            <option value="restaurant" {{ old('category') == 'restaurant' ? 'selected' : '' }}>რესტორანი</option>
                            <option value="hotel" {{ old('category') == 'hotel' ? 'selected' : '' }}>სასტუმრო</option>
                            <option value="fitness" {{ old('category') == 'fitness' ? 'selected' : '' }}>ფიტნესი</option>
                            <option value="wellness" {{ old('category') == 'wellness' ? 'selected' : '' }}>ველნესი</option>
                            <option value="entertainment" {{ old('category') == 'entertainment' ? 'selected' : '' }}>გასართობი</option>
                            <option value="shopping" {{ old('category') == 'shopping' ? 'selected' : '' }}>შოპინგი</option>
                            <option value="cafe" {{ old('category') == 'cafe' ? 'selected' : '' }}>კაფე</option>
                            <option value="spa" {{ old('category') == 'spa' ? 'selected' : '' }}>სპა</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>სხვა</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input
                            type="text"
                            name="contact_person"
                            placeholder="საკონტაქტო პირი"
                            required
                            value="{{ old('contact_person') }}"
                            class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('contact_person') border-red-500 @enderror"
                        />
                        <input
                            type="tel"
                            name="phone"
                            placeholder="ტელეფონი"
                            required
                            value="{{ old('phone') }}"
                            class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('phone') border-red-500 @enderror"
                        />
                    </div>
                    <input
                        type="text"
                        name="website"
                        placeholder="ვებ-გვერდი / Facebook"
                        value="{{ old('website') }}"
                        class="w-full p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-primary-500 dark:text-white transition-all @error('website') border-red-500 @enderror"
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

    <!-- FAQ Section -->
    <div class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-3xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold dark:text-white mb-4">ხშირად დასმული კითხვები</h2>
                <p class="text-gray-500 dark:text-gray-400">პასუხები თქვენს კითხვებზე</p>
            </div>

            <div class="space-y-6">
                <!-- FAQ 1 -->
                <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-lg dark:text-white mb-2">რა ღირს პარტნიორობა?</h3>
                    <p class="text-gray-600 dark:text-gray-300">პარტნიორად დარეგისტრირება უფასოა. მოქმედებს მხოლოდ საკომისიო სისტემა.</p>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-lg dark:text-white mb-2">როგორ აღვრიცხავ მომხმარებლებს?</h3>
                    <p class="text-gray-600 dark:text-gray-300">ჩვენი Partner App-ის მეშვეობით მარტივად დაასკანერებთ QR კოდს.</p>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-lg dark:text-white mb-2">რამდენი ხანი სჭირდება დამატებას?</h3>
                    <p class="text-gray-600 dark:text-gray-300">განაცხადის შევსებიდან 2-3 სამუშაო დღეში თქვენი ობიექტი გამოჩნდება აპლიკაციაში.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<script>
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

@include('components.landing.footer')
@endsection
