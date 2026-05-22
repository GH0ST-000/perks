@props(['icon', 'value', 'label', 'days', 'color'])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 p-3 md:p-5 rounded-2xl md:rounded-3xl shadow-sm border border-slate-50 dark:border-slate-800 flex flex-col justify-between group']) }}>
    <div class="flex justify-between items-start mb-2 md:mb-4">
        <div class="p-1.5 md:p-2 rounded-lg md:rounded-xl {{ $color }} transition-transform group-hover:scale-105">
            <i data-lucide="{{ $icon }}" class="w-[18px] h-[18px]"></i>
        </div>
        @if($days)
            <span class="text-[8px] md:text-[10px] font-bold text-slate-300 dark:text-slate-600 uppercase">{{ $days }}</span>
        @endif
    </div>
    <div>
        <div class="text-lg md:text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $value }}</div>
        <div class="text-[9px] md:text-[11px] font-semibold text-slate-400 dark:text-slate-500 truncate">{{ $label }}</div>
    </div>
</div>
