<x-partner-layout :partner="$partner">
    <div class="bg-white dark:bg-slate-900 rounded-[32px] overflow-hidden custom-shadow border border-slate-50 dark:border-slate-800 transition-colors duration-300">
        <div class="overflow-x-auto">
            @forelse($visits as $visit)
                <div class="flex justify-between items-center px-6 md:px-8 py-4 border-b border-slate-50 dark:border-slate-800 last:border-b-0 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <div>
                        <div class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $visit->user?->name ?? 'მომხმარებელი' }}</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                            {{ $visit->offerClaim?->premiumOffer?->name ?? $visit->notes }}
                            · {{ $visit->visited_at?->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300 dark:text-slate-600"></i>
                </div>
            @empty
                <div class="px-8 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                    ვიზიტების ისტორია ცარიელია.
                </div>
            @endforelse
        </div>
    </div>
</x-partner-layout>
