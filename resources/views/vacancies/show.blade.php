@extends('layouts.landing')

@section('content')
    @include('components.landing.header')

    <main class="bg-white dark:bg-gray-900 transition-colors duration-300">
        <!-- Vacancy Detail Section -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
                    <a href="{{ route('home') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">მთავარი</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('vacancies.index') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">ვაკანსიები</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 dark:text-white">{{ $vacancy->title }}</span>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <!-- Company Info -->
                        @if($vacancy->company)
                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                                @if($vacancy->company->logo ?? false)
                                    <img src="{{ Storage::url($vacancy->company->logo) }}" alt="{{ $vacancy->company->name }}" class="w-20 h-20 rounded-xl object-cover shadow-md">
                                @else
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center shadow-md">
                                        <span class="text-3xl font-bold text-gray-600 dark:text-gray-300">{{ substr($vacancy->company->name ?? 'C', 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">კომპანია</div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $vacancy->company->name }}</div>
                                    @if($vacancy->company->website)
                                        <a href="{{ $vacancy->company->website }}" target="_blank" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            {{ $vacancy->company->website }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">{{ $vacancy->title }}</h1>

                        <!-- Description -->
                        @if($vacancy->description)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">აღწერა</h2>
                                <div class="prose prose-gray dark:prose-invert max-w-none">
                                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                        {{ $vacancy->description }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Responsibilities -->
                        @if($vacancy->responsibilities)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">პასუხისმგებლობა</h2>
                                <div class="prose prose-gray dark:prose-invert max-w-none">
                                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                        {{ $vacancy->responsibilities }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Requirements -->
                        @if($vacancy->requirements)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">მოთხოვნები</h2>
                                <div class="prose prose-gray dark:prose-invert max-w-none">
                                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                        {{ $vacancy->requirements }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Benefits -->
                        @if($vacancy->benefits)
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">პრივილეგიები</h2>
                                <div class="prose prose-gray dark:prose-invert max-w-none">
                                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                        {{ $vacancy->benefits }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 sticky top-24">
                            <!-- Job Details -->
                            <div class="space-y-4 mb-8">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">დეტალები</h3>

                                @if($vacancy->city)
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">ლოკაცია</div>
                                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $vacancy->city }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if($vacancy->employment_type)
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">დასაქმების ტიპი</div>
                                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $vacancy->employment_type }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if($vacancy->experience_level)
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">გამოცდილება</div>
                                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{ ucfirst($vacancy->experience_level) }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if($vacancy->salary_range !== 'დასახელებულია')
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">ხელფასი</div>
                                            <div class="text-base font-semibold text-primary-600 dark:text-primary-400">{{ $vacancy->salary_range }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if($vacancy->days_left !== null)
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">დარჩენილი დრო</div>
                                            <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $vacancy->days_left }} დღე</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Apply Button -->
                            <div class="space-y-3">
                                <button
                                    onclick="openApplicationModal({{ $vacancy->id }}, '{{ $vacancy->title }}')"
                                    class="w-full py-4 bg-gradient-to-r from-primary-600 to-purple-600 hover:from-primary-700 hover:to-purple-700 text-white rounded-xl font-bold text-lg transition-all duration-200 shadow-lg shadow-primary-600/30 text-center active:scale-95 flex items-center justify-center gap-2"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    განაცხადის გაგზავნა
                                </button>

                                <button onclick="window.print()" class="w-full py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-all duration-200">
                                    ბეჭდვა
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Vacancies Section -->
                @if($relatedVacancies->count() > 0)
                    <div class="mt-16">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">სხვა ვაკანსიები <b>{{ $vacancy->company->name ?? 'კომპანიისგან' }}</b></h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($relatedVacancies as $relatedVacancy)
                                <a href="{{ route('vacancies.show', $relatedVacancy->slug) }}" class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                                    <div class="p-5 flex flex-col flex-1">
                                        @if($relatedVacancy->company)
                                            <div class="flex items-center gap-2 mb-3">
                                                @if($relatedVacancy->company->logo ?? false)
                                                    <img src="{{ Storage::url($relatedVacancy->company->logo) }}" alt="{{ $relatedVacancy->company->name }}" class="w-8 h-8 rounded-full object-cover">
                                                @else
                                                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ substr($relatedVacancy->company->name ?? 'C', 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $relatedVacancy->company->name }}</span>
                                            </div>
                                        @endif

                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                            {{ $relatedVacancy->title }}
                                        </h3>

                                        @if($relatedVacancy->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                {{ $relatedVacancy->description }}
                                            </p>
                                        @endif

                                        <div class="mt-auto space-y-2">
                                            @if($relatedVacancy->city)
                                                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span>{{ $relatedVacancy->city }}</span>
                                                </div>
                                            @endif

                                            @if($relatedVacancy->salary_range !== 'დასახელებულია')
                                                <div class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                                                    {{ $relatedVacancy->salary_range }}
                                                </div>
                                            @endif
                                        </div>

                                        <span class="mt-4 text-sm text-primary-600 dark:text-primary-400 font-medium">დეტალები →</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <!-- Application Modal -->
    <x-modal name="application-modal" maxWidth="2xl">
        <div class="bg-white dark:bg-gray-800 p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="modal-title">განაცხადის გაგზავნა</h2>
                <button
                    onclick="closeApplicationModal()"
                    class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Application Form -->
            <form id="application-form" method="POST" action="{{ route('vacancies.apply', $vacancy->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Name and Surname -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">სახელი, გვარი</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="სახელი, გვარი"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ელ-ფოსტა</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="ელ-ფოსტა"
                    >
                </div>

                <!-- CV Upload -->
                <div>
                    <label for="cv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ატვირთეთ CV</label>
                    <div class="relative">
                        <input
                            type="file"
                            id="cv"
                            name="cv"
                            required
                            accept=".pdf"
                            class="hidden"
                            onchange="updateFileLabel(this)"
                        >
                        <label
                            for="cv"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors"
                        >
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-600 dark:text-gray-400" id="file-label">
                                    <span class="font-semibold">Click to upload</span> PDF
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Cover Letter (Optional) -->
                <div>
                    <label for="cover_letter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">დამატებითი ინფორმაცია (არასავალდებულო)</label>
                    <textarea
                        id="cover_letter"
                        name="cover_letter"
                        rows="4"
                        class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="დაწერეთ დამატებითი ინფორმაცია თქვენს შესახებ..."
                    ></textarea>
                </div>

                <!-- Phone (Optional) -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ტელეფონი (არასავალდებულო)</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="ტელეფონი"
                    >
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4 pt-4">
                    <button
                        type="button"
                        onclick="closeApplicationModal()"
                        class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg transition-colors"
                    >
                        გაუქმება
                    </button>
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                    >
                        გაგზავნა
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    @include('components.landing.footer')

    <script>
        function openApplicationModal(vacancyId, vacancyTitle) {
            document.getElementById('modal-title').textContent = 'განაცხადის გაგზავნა - ' + vacancyTitle;

            // Reset form
            const form = document.getElementById('application-form');
            form.reset();
            document.getElementById('file-label').innerHTML = '<span class="font-semibold">Click to upload</span> PDF';

            // Open modal
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'application-modal' }));
        }

        function closeApplicationModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'application-modal' }));
        }

        function updateFileLabel(input) {
            const label = document.getElementById('file-label');
            if (input.files && input.files[0]) {
                label.innerHTML = '<span class="font-semibold">' + input.files[0].name + '</span>';
            } else {
                label.innerHTML = '<span class="font-semibold">Click to upload</span> PDF';
            }
        }
    </script>
@endsection

