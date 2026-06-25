<x-partner-layout :partner="$partner" headerTitle="მარკეტინგი">
    <div class="space-y-4 md:space-y-8" x-data="{ ordering: null }">

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl text-sm animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl text-sm animate-fade-in">
                {{ session('error') }}
            </div>
        @endif

        @if($activeSubscription)
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-5 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-1">აქტიური გამოწერა</p>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $activeSubscription->package_title }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-2">
                            ყოველთვიური ღირებულება: <strong>₾{{ number_format($activeSubscription->amount, 0) }}</strong>
                        </p>
                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <div>
                                <span class="text-slate-400 dark:text-slate-500 block text-xs">დაწყება</span>
                                {{ $activeSubscription->started_at?->format('d.m.Y') ?? '—' }}
                            </div>
                            <div>
                                <span class="text-slate-400 dark:text-slate-500 block text-xs">ბოლო ჩამოჭრა</span>
                                {{ $activeSubscription->last_billed_at?->format('d.m.Y H:i') ?? '—' }}
                            </div>
                            <div>
                                <span class="text-slate-400 dark:text-slate-500 block text-xs">შემდეგი ჩამოჭრა</span>
                                {{ $activeSubscription->next_billing_date?->format('d.m.Y') ?? '—' }}
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('partner.marketing.cancel', $activeSubscription) }}" method="POST" onsubmit="return confirm('გსურთ გამოწერის გაუქმება?');">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 rounded-xl border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            გამოწერის გაუქმება
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8 items-end pt-2 md:pt-4">
            @foreach($packages as $package)
                @php
                    $isFeatured = $package['featured'] ?? false;
                    $iconClass = match ($package['id']) {
                        'social' => 'partner-marketing-icon--social',
                        'platinum' => 'partner-marketing-icon--platinum',
                        default => 'partner-marketing-icon--executive',
                    };
                    $hasActive = (bool) $activeSubscription;
                    $isCurrent = $hasActive && $activeSubscription->package_id === $package['id'];
                @endphp
                <div class="partner-marketing-card-wrap h-full">
                <div @class([
                    'partner-marketing-card relative flex flex-col bg-white dark:bg-slate-900 rounded-2xl md:rounded-[32px] p-6 md:p-8 custom-shadow border min-h-[380px] md:min-h-[440px]',
                    'partner-marketing-card--featured border-2 border-blue-500 dark:border-blue-500 z-10' => $isFeatured,
                    'border-slate-100 dark:border-slate-800' => ! $isFeatured,
                ])>
                    @if($isFeatured)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 z-20">
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-blue-600/30 whitespace-nowrap">
                                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                {{ $package['badge'] }}
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-between items-start mb-6 md:mb-8 {{ $isFeatured ? 'mt-3' : '' }}">
                        <div @class([
                            'w-12 h-12 md:w-14 md:h-14 rounded-2xl flex items-center justify-center flex-shrink-0',
                            $iconClass,
                        ])>
                            <i data-lucide="{{ $package['icon'] }}" class="w-6 h-6 md:w-7 md:h-7"></i>
                        </div>
                        <div class="text-right leading-none">
                            <span class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight">₾{{ $package['price'] }}</span>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-1">ყოველთვიურად</p>
                        </div>
                    </div>

                    <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white mb-5 md:mb-6">
                        {{ $package['title'] }}
                    </h3>

                    <ul class="space-y-3 md:space-y-3.5 flex-1 mb-8 md:mb-10">
                        @foreach($package['features'] as $feature)
                            <li class="flex items-start gap-3 text-sm md:text-[15px] text-slate-600 dark:text-slate-300">
                                <span class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center">
                                    <i data-lucide="check" class="w-3 h-3 text-blue-600 dark:text-blue-400 stroke-[3]"></i>
                                </span>
                                <span class="font-medium leading-snug">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if($isCurrent)
                        <div class="mt-auto w-full py-3.5 md:py-4 rounded-xl md:rounded-2xl text-center text-sm font-bold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            აქტიური პაკეტი
                        </div>
                    @elseif($hasActive)
                        <button type="button" disabled class="mt-auto w-full py-3.5 md:py-4 rounded-xl md:rounded-2xl text-sm font-bold opacity-50 cursor-not-allowed partner-btn-finish">
                            ჯერ გააუქმეთ მიმდინარე გამოწერა
                        </button>
                    @else
                        <form action="{{ route('partner.marketing.order') }}" method="POST" class="mt-auto"
                            @submit="ordering = '{{ $package['id'] }}'">
                            @csrf
                            <input type="hidden" name="package" value="{{ $package['id'] }}">
                            <button type="submit"
                                @class([
                                    'w-full font-bold py-3.5 md:py-4 rounded-xl md:rounded-2xl transition-all flex items-center justify-center gap-2 min-h-[52px] disabled:opacity-70',
                                    'partner-btn-primary shadow-lg shadow-blue-600/25 hover:shadow-xl hover:shadow-blue-600/30 active:scale-[0.98]' => ($package['button'] ?? 'finish') === 'primary',
                                    'partner-btn-finish shadow-md active:scale-[0.98]' => ($package['button'] ?? 'finish') !== 'primary',
                                ])
                                :disabled="ordering === '{{ $package['id'] }}'">
                                <span x-show="ordering === '{{ $package['id'] }}'" x-cloak
                                    class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span x-text="ordering === '{{ $package['id'] }}' ? 'მუშავდება...' : 'გამოწერა'">გამოწერა</span>
                            </button>
                        </form>
                    @endif
                </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('head')
        <style>[x-cloak] { display: none !important; }</style>
    @endpush
</x-partner-layout>
