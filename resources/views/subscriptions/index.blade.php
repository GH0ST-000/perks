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
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                <line x1="16" x2="16" y1="2" y2="6"/>
                <line x1="8" x2="8" y1="2" y2="6"/>
                <line x1="3" x2="21" y1="10" y2="10"/>
            </svg>
            <span style="font-size: 18px; font-weight: 600; color: var(--text-primary);">გამოწერები</span>
        </div>

        @if($activeSubscription)
        <!-- Active Subscription -->
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: var(--card-radius); padding: 32px; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3); margin-bottom: 24px; color: #ffffff;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 24px;">
                <div>
                    <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 8px 0;">{{ $activeSubscription->name }}</h2>
                    <p style="margin: 0; opacity: 0.9;">აქტიური გამოწერა</p>
                </div>
                <div style="text-align: right;">
                    <p style="font-size: 32px; font-weight: 700; margin: 0;">{{ number_format($activeSubscription->amount, 2) }} ₾</p>
                    <p style="margin: 4px 0 0 0; opacity: 0.9;">/ {{ $activeSubscription->type === 'monthly' ? 'თვე' : 'წელი' }}</p>
                </div>
            </div>

            <div style="background-color: rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 20px; margin-bottom: 20px; backdrop-filter: blur(10px);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <p style="margin: 0 0 4px 0; opacity: 0.9; font-size: 13px;">მიმდინარე პერიოდი</p>
                        <p style="margin: 0; font-weight: 600; font-size: 15px;">{{ $activeSubscription->current_period_start->format('d/m/Y') }} - {{ $activeSubscription->current_period_end->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 4px 0; opacity: 0.9; font-size: 13px;">შემდეგი გადახდა</p>
                        <p style="margin: 0; font-weight: 600; font-size: 15px;">{{ $activeSubscription->next_billing_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p style="margin: 0 0 4px 0; opacity: 0.9; font-size: 13px;">სტატუსი</p>
                        <p style="margin: 0; font-weight: 600; font-size: 15px;">{{ $activeSubscription->status === 'active' ? 'აქტიური' : ucfirst($activeSubscription->status) }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('subscriptions.cancel', $activeSubscription) }}" method="POST" onsubmit="return confirm('დარწმუნებული ხართ, რომ გსურთ გამოწერის გაუქმება?');">
                @csrf
                <button type="submit" style="padding: 12px 24px; background-color: rgba(255, 255, 255, 0.2); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; backdrop-filter: blur(10px);" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.3)';" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.2)';">
                    გამოწერის გაუქმება
                </button>
            </form>
        </div>
        @else
        <!-- Subscription Plans -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; border: none; box-shadow: var(--shadow-card); margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 8px 0;">აირჩიეთ გეგმა</h2>
            <p style="color: var(--text-secondary); margin: 0 0 24px 0;">გამოიწერეთ და მიიღეთ რეგულარული P-Coin-ები</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                <!-- Monthly Plan -->
                <div style="background-color: var(--bg-card); border-radius: 18px; padding: 32px; border: 1px solid var(--border-color); box-shadow: var(--shadow-card); transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
                    <div style="text-align: center;">
                        <h3 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">თვიური გეგმა</h3>
                        <p style="color: var(--text-secondary); margin: 0 0 24px 0;">იდეალური რეგულარული გამოყენებისთვის</p>
                        
                        <div style="margin-bottom: 24px;">
                            <p style="font-size: 48px; font-weight: 700; color: #3b82f6; margin: 0; line-height: 1;">1</p>
                            <p style="color: var(--text-secondary); margin: 8px 0 0 0;">₾ / თვე</p>
                        </div>

                        <div style="text-align: left; margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span style="color: var(--text-secondary);">500 P-Coin ყოველთვიურად</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span style="color: var(--text-secondary);">ავტომატური განახლება</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span style="color: var(--text-secondary);">ნებისმიერ დროს გაუქმება</span>
                            </div>
                        </div>

                        <form action="{{ route('subscriptions.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="monthly">
                            <input type="hidden" name="amount" value="1">
                            <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)';">
                                გამოწერა
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Yearly Plan -->
                <div style="background-color: var(--bg-card); border-radius: 18px; padding: 32px; border: 2px solid #3b82f6; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.15); position: relative; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(59, 130, 246, 0.25)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(59, 130, 246, 0.15)';">
                    <div style="position: absolute; top: -1px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; padding: 6px 20px; border-radius: 0 0 12px 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);">
                        ეკონომიური
                    </div>
                    
                    <div style="text-align: center; padding-top: 20px;">
                        <h3 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">წლიური გეგმა</h3>
                        <p style="color: var(--text-secondary); margin: 0 0 24px 0;">დაზოგეთ 20% წლიურ გამოწერაზე</p>
                        
                        <div style="margin-bottom: 24px;">
                            <p style="font-size: 48px; font-weight: 700; color: #3b82f6; margin: 0; line-height: 1;">1</p>
                            <p style="color: var(--text-secondary); margin: 8px 0 0 0;">₾ / წელი</p>
                            <p style="color: #10b981; font-size: 14px; font-weight: 600; margin: 8px 0 0 0;">ტესტირების რეჟიმი</p>
                        </div>

                        <div style="text-align: left; margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span style="color: var(--text-secondary);">6000 P-Coin წელიწადში</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span style="color: var(--text-secondary);">ავტომატური განახლება</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <span style="color: var(--text-secondary);">ნებისმიერ დროს გაუქმება</span>
                            </div>
                        </div>

                        <form action="{{ route('subscriptions.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="yearly">
                            <input type="hidden" name="amount" value="1">
                            <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)';">
                                გამოწერა
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

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

