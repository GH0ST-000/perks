@php
    $statePath = $getStatePath();
    $icons = \App\Support\CategoryIcons::presets();
    $current = $getState();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @if(count($icons) === 0)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            პრესეტ აიკონები ვერ მოიძებნა. გამოიყენეთ ქვემოთ ატვირთვა.
        </p>
    @else
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
            @foreach($icons as $path => $icon)
                <label
                    @class([
                        'flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all',
                        'border-primary-600 bg-primary-50 dark:bg-primary-500/10 ring-1 ring-primary-600/30' => $current === $path,
                        'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 bg-white dark:bg-gray-900' => $current !== $path,
                    ])
                >
                    <input
                        type="radio"
                        class="sr-only"
                        value="{{ $path }}"
                        {{ $applyStateBindingModifiers('wire:model.live') }}="{{ $statePath }}"
                    />
                    <span class="flex items-center justify-center w-12 h-12 rounded-lg bg-white border border-gray-200 shadow-sm pointer-events-none">
                        <img
                            src="{{ $icon['url'] }}"
                            alt="{{ $icon['label'] }}"
                            class="w-8 h-8 object-contain"
                        />
                    </span>
                    <span class="text-xs text-center leading-tight text-gray-700 dark:text-gray-300 pointer-events-none">
                        {{ $icon['label'] }}
                    </span>
                </label>
            @endforeach
        </div>
    @endif
</x-dynamic-component>
