<x-dashboard-layout>
    <div style="max-width: 1400px; margin: 0 auto; padding: 0;">
        <!-- Success Message -->
        @if(session('success'))
            <div id="success-message" style="background-color: #10b981; color: #ffffff; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('success-message').style.display='none'" style="background: none; border: none; color: #ffffff; font-size: 20px; cursor: pointer;">×</button>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div id="error-message" style="background-color: #ef4444; color: #ffffff; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <span>{{ session('error') }}</span>
                <button onclick="document.getElementById('error-message').style.display='none'" style="background: none; border: none; color: #ffffff; font-size: 20px; cursor: pointer;">×</button>
            </div>
        @endif

        <!-- Page Header -->
        <div style="border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500">
                <rect width="20" height="14" x="2" y="5" rx="2"/>
                <line x1="2" x2="22" y1="10" y2="10"/>
            </svg>
            <span style="font-size: 18px; font-weight: 600; color: var(--text-primary);">P-Coin-ების შეძენა</span>
        </div>

        <!-- P-Coin Packages Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; border: none; box-shadow: var(--shadow-card); margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 8px 0;">აირჩიეთ პაკეტი</h2>
            <p style="color: var(--text-secondary); margin: 0 0 24px 0;">შეიძინეთ P-Coin-ები და ისარგებლეთ ჩვენი პლატფორმის შესაძლებლობებით</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                @forelse($packages as $package)
                    <div style="background-color: var(--bg-card); border-radius: 18px; padding: 28px; border: {{ $package->is_popular ? '2px solid #3b82f6' : '1px solid var(--border-color)' }}; box-shadow: {{ $package->is_popular ? '0 4px 16px rgba(59, 130, 246, 0.15)' : 'var(--shadow-card)' }}; position: relative; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='{{ $package->is_popular ? '0 8px 24px rgba(59, 130, 246, 0.25)' : '0 6px 20px rgba(0, 0, 0, 0.1)' }}';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='{{ $package->is_popular ? '0 4px 16px rgba(59, 130, 246, 0.15)' : 'var(--shadow-card)' }}';">
                        @if($package->is_popular)
                            <div style="position: absolute; top: -1px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; padding: 6px 20px; border-radius: 0 0 12px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);">
                                ყველაზე პოპულარული
                            </div>
                        @endif
                        <div style="text-align: center; padding-top: {{ $package->is_popular ? '20px' : '0' }};">
                            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);">
                                <span style="color: #ffffff; font-size: 28px; font-weight: 700;">P</span>
                            </div>
                            <p style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">{{ number_format($package->p_coins) }}</p>
                            <p style="font-size: 14px; color: var(--text-secondary); margin: 0 0 4px 0;">P-Coin</p>
                            <p style="font-size: 24px; font-weight: 600; color: #3b82f6; margin: 0 0 20px 0;">{{ number_format($package->price, 2) }} ₾</p>
                            
                            <form action="{{ route('payments.initiate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)';">
                                    შეძენა
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-secondary);">
                        <p>პაკეტები ამჟამად არ არის ხელმისაწვდომი</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Payment Info Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; border: none; box-shadow: var(--shadow-card); margin-bottom: 24px;">
            <h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 16px 0;">გადახდის ინფორმაცია</h3>
            <div style="display: grid; gap: 12px;">
                <div style="display: flex; align-items: start; gap: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <p style="margin: 0; color: var(--text-secondary); line-height: 1.6;">უსაფრთხო გადახდა Bank of Georgia-ს საშუალებით</p>
                </div>
                <div style="display: flex; align-items: start; gap: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <p style="margin: 0; color: var(--text-secondary); line-height: 1.6;">მხარდაჭერილია ყველა ძირითადი საბანკო ბარათი</p>
                </div>
                <div style="display: flex; align-items: start; gap: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <p style="margin: 0; color: var(--text-secondary); line-height: 1.6;">P-Coin-ები დაუყოვნებლივ ჩაირიცხება თქვენს ანგარიშზე</p>
                </div>
            </div>
        </div>

        @if($paymentMethods->count() > 0)
        <!-- Saved Payment Methods -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; border: none; box-shadow: var(--shadow-card);">
            <h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 16px 0;">შენახული გადახდის მეთოდები</h3>
            <div style="display: grid; gap: 12px;">
                @foreach($paymentMethods as $method)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background-color: var(--bg-secondary); border-radius: 10px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #3b82f6;">
                                <rect width="20" height="14" x="2" y="5" rx="2"/>
                                <line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                            <div>
                                <p style="margin: 0; font-weight: 600; color: var(--text-primary);">{{ strtoupper($method->brand ?? 'Card') }} •••• {{ $method->last_four }}</p>
                                @if($method->expiry_month && $method->expiry_year)
                                    <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-secondary);">ვადა: {{ $method->expiry_month }}/{{ $method->expiry_year }}</p>
                                @endif
                            </div>
                            @if($method->is_default)
                                <span style="background-color: #3b82f6; color: #ffffff; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">ძირითადი</span>
                            @endif
                        </div>
                        <form action="{{ route('payment-methods.delete', $method) }}" method="POST" onsubmit="return confirm('დარწმუნებული ხართ, რომ გსურთ ამ გადახდის მეთოდის წაშლა?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding: 8px 16px; background-color: #ef4444; color: #ffffff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#dc2626';" onmouseout="this.style.backgroundColor='#ef4444';">
                                წაშლა
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-dashboard-layout>

