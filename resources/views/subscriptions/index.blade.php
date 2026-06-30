<x-dashboard-layout title="წევრობა">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0;">
        @if(session('success'))
            <div id="success-message" style="background-color: #10b981; color: #ffffff; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('success-message').style.display='none'" style="background: none; border: none; color: #ffffff; font-size: 20px; cursor: pointer;">×</button>
            </div>
        @endif

        @if(session('error'))
            <div id="error-message" style="background-color: #ef4444; color: #ffffff; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <span>{{ session('error') }}</span>
                <button onclick="document.getElementById('error-message').style.display='none'" style="background: none; border: none; color: #ffffff; font-size: 20px; cursor: pointer;">×</button>
            </div>
        @endif

        <div style="border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                <line x1="16" x2="16" y1="2" y2="6"/>
                <line x1="8" x2="8" y1="2" y2="6"/>
                <line x1="3" x2="21" y1="10" y2="10"/>
            </svg>
            <span style="font-size: 18px; font-weight: 600; color: var(--text-primary);">წევრობა</span>
        </div>

        @if($activeSubscription)
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: var(--card-radius); padding: 32px; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3); margin-bottom: 24px; color: #ffffff;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
                <div>
                    <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 8px 0;">{{ $activeSubscription->name }}</h2>
                    <p style="margin: 0; opacity: 0.9;">
                        {{ $activeSubscription->plan === 'limited' ? 'Limited პაკეტი' : 'Member პაკეტი' }} · აქტიური
                    </p>
                </div>
                <div style="text-align: right;">
                    <p style="font-size: 32px; font-weight: 700; margin: 0;">{{ number_format($activeSubscription->amount, 0) }} ₾</p>
                    <p style="margin: 4px 0 0 0; opacity: 0.9;">/ თვე</p>
                </div>
            </div>

            <div style="background-color: rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <p style="margin: 0 0 4px 0; opacity: 0.9; font-size: 13px;">მიმდინარე პერიოდი</p>
                        <p style="margin: 0; font-weight: 600;">{{ $activeSubscription->current_period_start?->format('d/m/Y') }} - {{ $activeSubscription->current_period_end?->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 4px 0; opacity: 0.9; font-size: 13px;">შემდეგი გადახდა</p>
                        <p style="margin: 0; font-weight: 600;">{{ $activeSubscription->next_billing_date?->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('subscriptions.cancel', $activeSubscription) }}" method="POST" onsubmit="return confirm('დარწმუნებული ხართ, რომ გსურთ წევრობის გაუქმება?');">
                @csrf
                <button type="submit" style="padding: 12px 24px; background-color: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 8px; font-weight: 600; cursor: pointer;">
                    გაუქმება
                </button>
            </form>
        </div>
        @else
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; box-shadow: var(--shadow-card); margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 8px 0;">აირჩიე პაკეტი</h2>
            <p style="color: var(--text-secondary); margin: 0 0 24px 0;">ყოველთვიური ბენეფიტების პაკეტი — შეთავაზებების მისაღებად საჭიროა აქტიური წევრობა</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 900px; margin: 0 auto;">
                {{-- Member --}}
                <div style="background-color: var(--bg-card); border-radius: 18px; padding: 32px; border: {{ ($selectedPlan ?? null) === 'member' ? '2px solid #3b82f6' : '1px solid var(--border-color)' }}; box-shadow: var(--shadow-card);">
                    <h3 style="font-size: 22px; font-weight: 700; color: var(--text-primary); margin: 0 0 16px 0;">Member</h3>
                    <p style="font-size: 36px; font-weight: 700; color: var(--text-primary); margin: 0 0 24px 0;">{{ number_format($plans['member']['amount'] ?? 19, 0) }} ₾ <span style="font-size: 16px; font-weight: 500; color: var(--text-secondary);">/ თვე</span></p>
                    <ul style="list-style: none; padding: 0; margin: 0 0 24px 0; display: flex; flex-direction: column; gap: 10px; font-size: 14px; color: var(--text-secondary);">
                        <li>✓ სტანდარტული ფასდაკლებები</li>
                        <li>✓ ექსკლუზიური შეთავაზებები</li>
                        <li>✓ {{ config('perks.membership_plans.member.p_coins_label') }}</li>
                        <li>✓ ოჯახის წევრის დამატება</li>
                    </ul>
                    <form action="{{ route('subscriptions.subscribe') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="member">
                        <button type="submit" style="width: 100%; padding: 14px; background: transparent; color: #3b82f6; border: 2px solid #3b82f6; border-radius: 10px; font-weight: 600; cursor: pointer;">
                            გააქტიურება
                        </button>
                    </form>
                </div>

                {{-- Limited --}}
                <div style="background-color: var(--bg-card); border-radius: 18px; padding: 32px; border: 2px solid #3b82f6; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.15); position: relative;">
                    <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #3b82f6; color: #fff; padding: 4px 16px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase;">ყველაზე პოპულარული</div>
                    <h3 style="font-size: 22px; font-weight: 700; color: var(--text-primary); margin: 16px 0 16px 0;">Limited</h3>
                    <p style="font-size: 36px; font-weight: 700; color: var(--text-primary); margin: 0 0 24px 0;">{{ number_format($plans['limited']['amount'] ?? 29, 0) }} ₾ <span style="font-size: 16px; font-weight: 500; color: var(--text-secondary);">/ თვე</span></p>
                    <ul style="list-style: none; padding: 0; margin: 0 0 24px 0; display: flex; flex-direction: column; gap: 10px; font-size: 14px; color: var(--text-secondary);">
                        <li>✓ პრემიუმ ფასდაკლებები</li>
                        <li>✓ ექსკლუზიური შეთავაზებები</li>
                        <li>✓ {{ config('perks.membership_plans.limited.p_coins_label') }}</li>
                        <li>✓ პერსონალური ასისტენტი</li>
                        <li>✓ ოჯახის წევრის დამატება</li>
                    </ul>
                    <form action="{{ route('subscriptions.subscribe') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="limited">
                        <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                            გააქტიურება
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if($paymentMethods->count() > 0)
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; box-shadow: var(--shadow-card);">
            <h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 16px 0;">შენახული ბარათები</h3>
            <div style="display: grid; gap: 12px;">
                @foreach($paymentMethods as $method)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background-color: var(--bg-secondary); border-radius: 10px;">
                        <div>
                            <p style="margin: 0; font-weight: 600;">{{ strtoupper($method->brand ?? 'Card') }} •••• {{ $method->last_four }}</p>
                        </div>
                        <form action="{{ route('payment-methods.delete', $method) }}" method="POST" onsubmit="return confirm('წავშალოთ ბარათი?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding: 8px 16px; background-color: #ef4444; color: #fff; border: none; border-radius: 6px; font-size: 13px; cursor: pointer;">წაშლა</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-dashboard-layout>
