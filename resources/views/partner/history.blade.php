<x-partner-layout :partner="$partner" title="ისტორია" headerTitle="ისტორია">
    <div class="space-y-4 md:space-y-6">

        {{-- Period filters --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 md:gap-4">
            <div class="flex items-center gap-2 text-slate-400 dark:text-slate-500 shrink-0">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span class="text-xs font-bold uppercase tracking-widest">ფილტრი:</span>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($periodFilters as $key => $filter)
                    <a href="{{ route('partner.history', ['period' => $key]) }}"
                       @class([
                           'partner-history-filter px-4 py-2 rounded-full text-xs md:text-sm font-bold transition-all duration-200',
                           'partner-history-filter--active' => $period === (string) $key,
                       ])>
                        {{ $filter['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- History list --}}
        <div class="partner-history-panel bg-slate-50 dark:bg-slate-900/50 rounded-2xl md:rounded-[32px] p-5 md:p-8 custom-shadow border border-slate-100 dark:border-slate-800 transition-colors duration-300">
            <div class="flex justify-between items-center px-2 md:px-4 pb-4 md:pb-6 mb-2 border-b border-slate-200/60 dark:border-slate-700/60">
                <span class="text-[10px] md:text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">თარიღი და დრო</span>
                <span class="text-[10px] md:text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">კოინი (P-COIN)</span>
            </div>

            <div class="space-y-1 md:space-y-2 min-h-[120px]">
                @forelse($visits as $visit)
                    @php
                        $pCoins = (int) ($visit->offerClaim?->premiumOffer?->p_coins_reward ?? 0);
                    @endphp
                    <div class="partner-history-row flex justify-between items-center px-3 md:px-4 py-4 md:py-5 rounded-xl md:rounded-2xl hover:bg-white/80 dark:hover:bg-slate-800/50 transition-colors duration-200">
                        <div class="flex items-center gap-3 md:gap-4 min-w-0">
                            <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500 shrink-0 shadow-sm">
                                <i data-lucide="calendar" class="w-4 h-4 md:w-[18px] md:h-[18px]"></i>
                            </div>
                            <time class="text-sm md:text-base font-medium text-slate-700 dark:text-slate-200 truncate"
                                  datetime="{{ $visit->visited_at?->toIso8601String() }}">
                                {{ $visit->visited_at?->format('n/j/Y, g:i:s A') }}
                            </time>
                        </div>
                        <span class="text-sm md:text-base font-black text-blue-600 dark:text-blue-400 shrink-0 ml-4">
                            +{{ $pCoins }} P
                        </span>
                    </div>
                @empty
                    <div class="py-12 md:py-16 text-center">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-300">
                            <i data-lucide="history" class="w-7 h-7"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">ამ პერიოდში ვიზიტები ვერ მოიძებნა.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:initialized', () => {
                if (window.lucide) lucide.createIcons();
            });
        </script>
    @endpush
</x-partner-layout>
