@props([
    'src',
    'alt' => '',
    'size' => 'md',
])

@php
    $sizes = [
        'xs' => ['box' => 'w-6 h-6 rounded-md', 'img' => 'w-4 h-4'],
        'sm' => ['box' => 'w-8 h-8 rounded-lg', 'img' => 'w-5 h-5'],
        'md' => ['box' => 'w-10 h-10 rounded-xl', 'img' => 'w-7 h-7'],
        'lg' => ['box' => 'w-14 h-14 rounded-2xl', 'img' => 'w-9 h-9'],
    ];
    $dimensions = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center shrink-0 bg-white border border-gray-200 shadow-sm {$dimensions['box']}"]) }}>
    <img src="{{ $src }}" alt="{{ $alt }}" class="{{ $dimensions['img'] }} object-contain" loading="lazy" decoding="async" />
</span>
