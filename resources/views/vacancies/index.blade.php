@extends('layouts.landing')

@section('content')
    @include('components.landing.header')

    <main class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen transition-colors duration-300">
        <!-- Hero Section -->
        <section class="py-20 px-4">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 text-gray-900 dark:text-white">შემოუერთდი ჩვენს გუნდს</h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">ჩვენ ვქმნით საუკეთესო გამოცდილებას დასაქმებულებისთვის.</p>
            </div>
        </section>

        <!-- Vacancies Section -->
        <section class="py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <!-- Section Header with Filter -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-3xl font-bold mb-4 md:mb-0 text-gray-900 dark:text-white">მიმდინარე ვაკანსიები</h2>

                    <!-- Department Filter -->
                    @if(isset($departments) && $departments->count() > 0)
                        <form method="GET" action="{{ route('vacancies.index') }}" class="w-full md:w-auto">
                            <div class="relative">
                                <select
                                    name="department"
                                    onchange="this.form.submit()"
                                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg px-4 py-2 pr-10 appearance-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent cursor-pointer"
                                >
                                    <option value="All" {{ request('department') == 'All' || !request('department') ? 'selected' : '' }}>ყველა დეპარტამენტი</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Vacancies List -->
                @if($vacancies->count() > 0)
                    <div class="space-y-4">
                        @foreach($vacancies as $vacancy)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <!-- Left Side: Job Info -->
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $vacancy->title }}</h3>
                                        <div class="flex flex-wrap items-center gap-4 text-gray-600 dark:text-gray-400 text-sm">
                                            @if($vacancy->department)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    <span>{{ $vacancy->department }}</span>
                                                </div>
                                            @endif

                                            @if($vacancy->city)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span>{{ $vacancy->city }}, Georgia</span>
                                                </div>
                                            @endif

                                            @if($vacancy->employment_type)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ $vacancy->employment_type }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right Side: Apply Button -->
                                    <div>
                                        <button
                                            onclick="openApplicationModal({{ $vacancy->id }}, '{{ $vacancy->title }}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition-colors whitespace-nowrap"
                                        >
                                            განაცხადის გაგზავნა
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $vacancies->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-500 dark:text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">ვაკანსიები არ მოიძებნა</h3>
{{--                        <p class="text-gray-600 dark:text-gray-400 mb-8">სცადეთ სხვა დეპარტამენტი</p>--}}
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
            <form id="application-form" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="vacancy_id" id="vacancy_id" value="">

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
            document.getElementById('vacancy_id').value = vacancyId;
            document.getElementById('modal-title').textContent = 'განაცხადის გაგზავნა - ' + vacancyTitle;

            // Update form action
            const form = document.getElementById('application-form');
            form.action = `/vacancies/${vacancyId}/apply`;

            // Reset form
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
