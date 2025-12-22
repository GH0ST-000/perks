<div class="space-y-6">
    <!-- Personal Information -->
    <div>
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">პირადი ინფორმაცია</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">სახელი, გვარი</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $application->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">ელ-ფოსტა</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">
                    <a href="mailto:{{ $application->email }}" class="text-primary-600 dark:text-primary-400 hover:underline">
                        {{ $application->email }}
                    </a>
                </p>
            </div>
            @if($application->phone)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">ტელეფონი</p>
                    <p class="text-base font-medium text-gray-900 dark:text-white">
                        <a href="tel:{{ $application->phone }}" class="text-primary-600 dark:text-primary-400 hover:underline">
                            {{ $application->phone }}
                        </a>
                    </p>
                </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">გაგზავნის თარიღი</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">
                    {{ $application->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <!-- CV File -->
    @if($application->cv_path)
        <div>
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">CV ფაილი</h3>
            <div class="flex items-center gap-4">
                <a 
                    href="{{ Storage::disk('public')->url($application->cv_path) }}" 
                    target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    CV-ს ნახვა
                </a>
                <a 
                    href="{{ Storage::disk('public')->url($application->cv_path) }}" 
                    download
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    CV-ს ჩამოტვირთვა
                </a>
            </div>
        </div>
    @endif

    <!-- Cover Letter -->
    @if($application->cover_letter)
        <div>
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">დამატებითი ინფორმაცია</h3>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <p class="text-base text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $application->cover_letter }}</p>
            </div>
        </div>
    @endif

    <!-- Vacancy Information -->
    <div>
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">ვაკანსიის ინფორმაცია</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">ვაკანსია</p>
                <p class="text-base font-medium text-gray-900 dark:text-white">{{ $application->vacancy->title }}</p>
            </div>
            @if($application->vacancy->company)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">კომპანია</p>
                    <p class="text-base font-medium text-gray-900 dark:text-white">{{ $application->vacancy->company->name }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

