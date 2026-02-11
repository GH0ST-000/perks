<x-dashboard-layout>
    <div style="max-width: 1400px; margin: 0 auto; padding: 0;">
        <!-- Success Message -->
        @if(session('success'))
            <div id="success-message" style="background-color: #10b981; color: #ffffff; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('success-message').style.display='none'" style="background: none; border: none; color: #ffffff; font-size: 20px; cursor: pointer;">×</button>
            </div>
        @endif

        <!-- My Wallet Section Header -->
        <div style="border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet text-blue-500" aria-hidden="true"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path></svg>
            <span style="font-size: 18px; font-weight: 600; color: var(--text-primary);">ჩემი საფულე</span>
        </div>

        <!-- P-Coin Packages Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; border: none; box-shadow: var(--shadow-card); margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 24px 0;">P-Coin პაკეტები</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                @forelse($packages as $package)
                    <div style="background-color: var(--bg-card); border-radius: 18px; padding: 24px; border: {{ $package->is_popular ? '2px solid #3b82f6' : 'none' }}; box-shadow: {{ $package->is_popular ? '0 4px 16px rgba(59, 130, 246, 0.15)' : 'var(--shadow-card)' }}; position: relative; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='{{ $package->is_popular ? '0 8px 24px rgba(59, 130, 246, 0.2)' : '0 6px 20px rgba(0, 0, 0, 0.08)' }}';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='{{ $package->is_popular ? '0 4px 16px rgba(59, 130, 246, 0.15)' : 'var(--shadow-card)' }}';">
                        @if($package->is_popular)
                            <div style="position: absolute; top: -1px; left: 50%; transform: translateX(-50%); background-color: #3b82f6; color: #ffffff; padding: 4px 16px; border-radius: 0 0 8px 8px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                MOST POPULAR
                            </div>
                        @endif
                        <div style="text-align: center; padding-top: {{ $package->is_popular ? '16px' : '0' }};">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <span style="color: #ffffff; font-size: 24px; font-weight: 700;">P</span>
                            </div>
                            <p style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">{{ $package->p_coins }} P</p>
                            <p style="font-size: 16px; color: var(--text-secondary); margin: 0 0 16px 0;">{{ number_format($package->price, 0) }} ₾</p>
                            <button style="width: 100%; padding: 10px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2563eb';" onmouseout="this.style.backgroundColor='#3b82f6';">
                                ყიდვა
                            </button>
                        </div>
                    </div>
                @empty
                    <!-- Default packages if none in database -->
                    <div style="background-color: var(--bg-card); border-radius: 18px; padding: 24px; border: none; box-shadow: var(--shadow-card); position: relative; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
                        <div style="text-align: center;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <span style="color: #ffffff; font-size: 24px; font-weight: 700;">P</span>
                            </div>
                            <p style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">10 ₾</p>
                            <p style="font-size: 16px; color: var(--text-secondary); margin: 0 0 18px 0;">50 P</p>
                            <button style="width: 100%; padding: 12px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='scale(1.02)';" onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='scale(1)';">ყიდვა</button>
                        </div>
                    </div>
                    <div style="background-color: var(--bg-card); border-radius: 18px; padding: 24px; border: 2px solid #3b82f6; box-shadow: 0 4px 16px rgba(59, 130, 246, 0.15); position: relative; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(59, 130, 246, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(59, 130, 246, 0.15)';">
                        <div style="position: absolute; top: -1px; left: 50%; transform: translateX(-50%); background-color: #3b82f6; color: #ffffff; padding: 4px 16px; border-radius: 0 0 10px 10px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            MOST POPULAR
                        </div>
                        <div style="text-align: center; padding-top: 16px;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <span style="color: #ffffff; font-size: 24px; font-weight: 700;">P</span>
                            </div>
                            <p style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">20 ₾</p>
                            <p style="font-size: 16px; color: var(--text-secondary); margin: 0 0 18px 0;">100 P</p>
                            <button style="width: 100%; padding: 12px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='scale(1.02)';" onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='scale(1)';">ყიდვა</button>
                        </div>
                    </div>
                    <div style="background-color: var(--bg-card); border-radius: 18px; padding: 24px; border: none; box-shadow: var(--shadow-card); position: relative; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
                        <div style="text-align: center;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <span style="color: #ffffff; font-size: 24px; font-weight: 700;">P</span>
                            </div>
                            <p style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">50 ₾</p>
                            <p style="font-size: 16px; color: var(--text-secondary); margin: 0 0 18px 0;">300 P</p>
                            <button style="width: 100%; padding: 12px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='scale(1.02)';" onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='scale(1)';">ყიდვა</button>
                        </div>
                    </div>
                    <div style="background-color: var(--bg-card); border-radius: 18px; padding: 24px; border: none; box-shadow: var(--shadow-card); position: relative; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
                        <div style="text-align: center;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <span style="color: #ffffff; font-size: 24px; font-weight: 700;">P</span>
                            </div>
                            <p style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">150 ₾</p>
                            <p style="font-size: 16px; color: var(--text-secondary); margin: 0 0 18px 0;">500 P</p>
                            <button style="width: 100%; padding: 12px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'; this.style.transform='scale(1.02)';" onmouseout="this.style.backgroundColor='#3b82f6'; this.style.transform='scale(1)';">ყიდვა</button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Payment Methods Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; border: none; box-shadow: var(--shadow-card); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0;">გადახდის მეთოდები</h2>
                <button id="open-add-card-modal" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2563eb';" onmouseout="this.style.backgroundColor='#3b82f6';">
                    <span style="font-size: 18px;">+</span>
                    <span>ბარათის დამატება</span>
                </button>
            </div>

            @if($paymentMethods->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($paymentMethods as $method)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--bg-secondary); border-radius: 12px; border: 1px solid var(--border-color);">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="width: 48px; height: 32px; background-color: var(--bg-hover); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 12px; font-weight: 600; color: var(--text-primary); text-transform: uppercase;">{{ $method->brand ?? 'VISA' }}</span>
                                </div>
                                <div>
                                    <p style="font-size: 16px; font-weight: 500; color: var(--text-primary); margin: 0 0 4px 0;">
                                        .... .... .... {{ $method->last_four }}
                                    </p>
                                    @if($method->expiry_month && $method->expiry_year)
                                        <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">
                                            Expires {{ str_pad($method->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ substr($method->expiry_year, -2) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($method->is_default)
                                <button style="padding: 6px 12px; background-color: #ffffff; color: #000000; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                    Default
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Default card if none exists -->
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--bg-secondary); border-radius: 12px; border: 1px solid var(--border-color);">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 32px; background-color: var(--bg-hover); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 12px; font-weight: 600; color: var(--text-primary); text-transform: uppercase;">VISA</span>
                        </div>
                        <div>
                            <p style="font-size: 16px; font-weight: 500; color: var(--text-primary); margin: 0 0 4px 0;">
                                .... .... .... 4242
                            </p>
                            <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">
                                Expires 09/55
                            </p>
                        </div>
                    </div>
                    <button style="padding: 6px 12px; background-color: #ffffff; color: #000000; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                        Default
                    </button>
                </div>
            @endif
        </div>

        <!-- History Section -->
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 28px; border: none; box-shadow: var(--shadow-card);">
            <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 24px 0;">ისტორია</h2>

            @if($transactions->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($transactions as $transaction)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--bg-secondary); border-radius: 12px; border: 1px solid var(--border-color); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--bg-hover)';" onmouseout="this.style.borderColor='var(--border-color)';">
                            <div style="display: flex; align-items: center; gap: 16px; flex: 1;">
                                <div style="width: 48px; height: 48px; border-radius: 12px; background-color: {{ $transaction->amount > 0 ? '#10b981' : '#ef4444' }}20; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-icons" style="font-size: 24px; color: {{ $transaction->amount > 0 ? '#10b981' : '#ef4444' }};">
                                        {{ $transaction->amount > 0 ? 'add' : 'remove' }}
                                    </span>
                                </div>
                                <div style="flex: 1;">
                                    <p style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0;">
                                        {{ $transaction->description ?? 'ტრანზაქცია' }}
                                    </p>
                                    <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 18px; font-weight: 700; color: {{ $transaction->amount > 0 ? '#10b981' : '#ef4444' }}; margin: 0 0 4px 0;">
                                    {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }} P
                                </p>
                                <p style="font-size: 12px; color: var(--text-secondary); margin: 0;">
                                    ბალანსი: {{ number_format($transaction->balance_after) }} P
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div style="margin-top: 24px; display: flex; justify-content: center;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            @if ($transactions->onFirstPage())
                                <span style="padding: 8px 12px; background-color: var(--bg-hover); border-radius: 8px; color: var(--text-tertiary); cursor: not-allowed;">← წინა</span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" style="padding: 8px 12px; background-color: var(--bg-hover); border-radius: 8px; color: var(--text-primary); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--bg-tertiary)';" onmouseout="this.style.backgroundColor='var(--bg-hover)';">← წინა</a>
                            @endif

                            <span style="padding: 8px 12px; color: var(--text-secondary); font-size: 14px;">
                                გვერდი {{ $transactions->currentPage() }} / {{ $transactions->lastPage() }}
                            </span>

                            @if ($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" style="padding: 8px 12px; background-color: var(--bg-hover); border-radius: 8px; color: var(--text-primary); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--bg-tertiary)';" onmouseout="this.style.backgroundColor='var(--bg-hover)';">შემდეგი →</a>
                            @else
                                <span style="padding: 8px 12px; background-color: var(--bg-hover); border-radius: 8px; color: var(--text-tertiary); cursor: not-allowed;">შემდეგი →</span>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div style="text-align: center; padding: 48px 24px;">
                    <span class="material-icons" style="font-size: 64px; color: var(--text-tertiary); margin-bottom: 16px; display: block;">history</span>
                    <p style="font-size: 16px; color: var(--text-secondary); margin: 0;">ტრანზაქციები ჯერ არ არის</p>
                    <p style="font-size: 14px; color: var(--text-tertiary); margin: 8px 0 0 0;">თქვენი ტრანზაქციების ისტორია აქ გამოჩნდება</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Card Modal -->
    <div id="add-card-modal" style="display: none; position: fixed; inset: 0; z-index: 1000; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
        <div style="background-color: var(--bg-card); border-radius: 16px; padding: 24px; width: 90%; max-width: 500px; position: relative; border: 1px solid var(--border-color);">
            <!-- Close Button -->
            <button id="close-add-card-modal" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: var(--text-secondary); font-size: 24px; cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--bg-hover)'; this.style.color='var(--text-primary)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                ×
            </button>

            <!-- Title -->
            <h2 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0 0 24px 0;">ბარათის დამატება</h2>

            <!-- Form -->
            <form id="add-card-form" method="POST" action="{{ route('wallet.payment-methods.store') }}">
                @csrf
                
                <!-- Cardholder Name -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">მფლობელის სახელი</label>
                    <input type="text" name="cardholder_name" required style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 14px; box-sizing: border-box;" placeholder="მფლობელის სახელი">
                    @error('cardholder_name')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Card Number -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">ბარათის ნომერი</label>
                    <input type="text" name="card_number" id="card_number" required maxlength="19" style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 14px; box-sizing: border-box;" placeholder="0000 0000 0000 0000">
                    @error('card_number')
                        <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiration Date and CVV -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">ვადა</label>
                        <input type="text" name="expiry_date" id="expiry_date" required maxlength="5" style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 14px; box-sizing: border-box;" placeholder="MM/YY">
                        @error('expiry_date')
                            <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">CVV</label>
                        <input type="text" name="cvv" required maxlength="4" style="width: 100%; padding: 12px 16px; background-color: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 14px; box-sizing: border-box;" placeholder="123">
                        @error('cvv')
                            <p style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Save Button -->
                <button type="submit" style="width: 100%; padding: 14px; background-color: #3b82f6; color: #ffffff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2563eb';" onmouseout="this.style.backgroundColor='#3b82f6';">
                    შენახვა
                </button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('add-card-modal');
        const openBtn = document.getElementById('open-add-card-modal');
        const closeBtn = document.getElementById('close-add-card-modal');
        const form = document.getElementById('add-card-form');

        function openModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Format card number input
        const cardNumberInput = document.getElementById('card_number');
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Format expiry date input
        const expiryInput = document.getElementById('expiry_date');
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'იხდება...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
                    }
                });

                let data;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    // If not JSON, it might be a redirect or HTML response
                    if (response.ok) {
                        window.location.reload();
                        return;
                    }
                    data = { message: 'დაფიქსირდა შეცდომა' };
                }

                if (response.ok) {
                    // Success - close modal, reset form, and reload page
                    closeModal();
                    form.reset();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    // Show errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat();
                        alert(errorMessages.join('\n'));
                    } else {
                        alert(data.message || 'დაფიქსირდა შეცდომა');
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('დაფიქსირდა შეცდომა: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    </script>
</x-dashboard-layout>
