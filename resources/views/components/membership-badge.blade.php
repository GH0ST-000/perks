@props(['size' => 'md'])

@php
    $membership = app(\App\Services\MembershipService::class);
    $user = auth()->user();
    $plan = $user ? $membership->plan($user) : null;
    $subscription = $user ? $membership->activeSubscription($user) : null;

    $styles = match ($plan) {
        'limited' => 'background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #1f2937;',
        'member' => 'background-color: #c4c4c4; color: #1f2937;',
        default => 'background-color: rgba(239, 68, 68, 0.15); color: #ef4444;',
    };

    $label = match ($plan) {
        'limited' => 'Limited',
        'member' => 'Member',
        default => 'არააქტიური',
    };

    $fontSize = $size === 'sm' ? '11px' : '12px';
    $padding = $size === 'sm' ? '4px 10px' : '4px 12px';
@endphp

@if($plan)
    <span {{ $attributes->merge([
        'style' => "display: inline-flex; align-items: center; gap: 4px; font-size: {$fontSize}; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: {$padding}; border-radius: 999px; {$styles}",
    ]) }}>
        @if($plan === 'limited')
            <svg xmlns="http://www.w3.org/2000/svg" width="{{ $size === 'sm' ? '12' : '14' }}" height="{{ $size === 'sm' ? '12' : '14' }}" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        @endif
        {{ $label }}
    </span>
@else
    <a href="{{ route('subscriptions.index') }}" {{ $attributes->merge([
        'style' => "display: inline-flex; align-items: center; font-size: {$fontSize}; font-weight: 600; padding: {$padding}; border-radius: 999px; text-decoration: none; {$styles}",
    ]) }}>
        გაწევრიანება
    </a>
@endif
