<x-dashboard-layout>
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <span class="material-icons" style="font-size: 32px; color: #a855f7;">card_giftcard</span>
            <h1 style="font-size: 32px; font-weight: 700; color: var(--text-primary); margin: 0;">საჩუქრები</h1>
        </div>
        <p style="font-size: 16px; color: var(--text-secondary); margin: 0;">გადაცვალეთ თქვენი P ქულები საჩუქრებზე</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div style="background-color: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; border-radius: 8px; padding: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <span class="material-icons" style="font-size: 24px; color: #10b981;">check_circle</span>
            <p style="font-size: 14px; color: #10b981; margin: 0;">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; border-radius: 8px; padding: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <span class="material-icons" style="font-size: 24px; color: #ef4444;">error</span>
            <p style="font-size: 14px; color: #ef4444; margin: 0;">{{ session('error') }}</p>
        </div>
    @endif

    <!-- User Balance Card -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 24px; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0 0 4px 0;">თქვენი ბალანსი</p>
            <p style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0; display: flex; align-items: center; gap: 8px;">
                {{ $user->p_coins ?? 0 }}
                <span style="font-size: 24px;">P</span>
            </p>
        </div>
        <div style="width: 64px; height: 64px; background-color: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <span class="material-icons" style="font-size: 36px; color: #ffffff;">account_balance_wallet</span>
        </div>
    </div>

    @if($gifts->count() > 0)
        <!-- Gifts Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            @foreach($gifts as $gift)
                <div style="background-color: var(--bg-card); border-radius: var(--card-radius); overflow: hidden; border: none; box-shadow: var(--shadow-card); transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); position: relative;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
                    <!-- Gift Image -->
                    <div style="position: relative; width: 100%; height: 200px; overflow: hidden; background-color: var(--bg-secondary);">
                        @if($gift->image)
                            <img src="{{ asset('storage/' . $gift->image) }}" alt="{{ $gift->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <span class="material-icons" style="font-size: 64px; color: rgba(255,255,255,0.5);">card_giftcard</span>
                            </div>
                        @endif
                        
                        <!-- Price Badge -->
                        <div style="position: absolute; top: 16px; right: 16px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); padding: 8px 16px; border-radius: 20px; box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);">
                            <span style="font-size: 18px; font-weight: 700; color: #ffffff;">{{ $gift->p_coins_cost }} P</span>
                        </div>

                        <!-- Stock Badge (if low) -->
                        @if($gift->stock > 0 && $gift->stock <= 5)
                            <div style="position: absolute; top: 16px; left: 16px; background-color: #ef4444; padding: 6px 12px; border-radius: 12px;">
                                <span style="font-size: 12px; font-weight: 600; color: #ffffff;">მხოლოდ {{ $gift->stock }} ცალი</span>
                            </div>
                        @endif
                    </div>

                    <!-- Gift Content -->
                    <div style="padding: 20px;">
                        <!-- Gift Title -->
                        <h3 style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 0 0 8px 0;">{{ $gift->name }}</h3>
                        
                        <!-- Gift Description -->
                        <p style="font-size: 14px; color: var(--text-secondary); margin: 0 0 20px 0; line-height: 1.6;">{{ $gift->description }}</p>

                        <!-- Type Badge -->
                        <div style="margin-bottom: 16px;">
                            @if($gift->type === 'voucher')
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; font-size: 12px; font-weight: 600; border-radius: 12px;">
                                    <span class="material-icons" style="font-size: 16px;">receipt_long</span>
                                    ვაუჩერი
                                </span>
                            @elseif($gift->type === 'product')
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background-color: rgba(168, 85, 247, 0.1); color: #a855f7; font-size: 12px; font-weight: 600; border-radius: 12px;">
                                    <span class="material-icons" style="font-size: 16px;">inventory_2</span>
                                    პროდუქტი
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 12px; font-weight: 600; border-radius: 12px;">
                                    <span class="material-icons" style="font-size: 16px;">star</span>
                                    სერვისი
                                </span>
                            @endif
                        </div>

                        <!-- Redeem Button or Redemption Code -->
                        @php
                            $userRedemption = $userRedemptions->get($gift->id);
                        @endphp

                        @if($userRedemption)
                            <!-- Already Redeemed - Show Code -->
                            <div style="background-color: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <span class="material-icons" style="font-size: 18px; color: #10b981;">check_circle</span>
                                    <span style="font-size: 12px; font-weight: 600; color: #10b981; text-transform: uppercase;">გადაცვლილია</span>
                                </div>
                                <div style="background-color: var(--bg-card); border: 1px solid #3b82f6; border-radius: 6px; padding: 12px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                                    <div style="flex: 1;">
                                        <p style="font-size: 10px; color: var(--text-tertiary); margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px;">პრომო კოდი</p>
                                        <p style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin: 0; font-family: monospace; word-break: break-all;">{{ $userRedemption->redemption_code }}</p>
                                    </div>
                                    <button onclick="copyCodeFromCard('{{ $userRedemption->redemption_code }}')" style="background: none; border: none; cursor: pointer; padding: 8px; flex-shrink: 0;">
                                        <span class="material-icons" style="font-size: 20px; color: #3b82f6;">content_copy</span>
                                    </button>
                                </div>
                                @if($userRedemption->expires_at)
                                    <p style="font-size: 11px; color: var(--text-tertiary); margin: 8px 0 0 0; display: flex; align-items: center; gap: 4px;">
                                        <span class="material-icons" style="font-size: 14px;">schedule</span>
                                        ვადა: {{ $userRedemption->expires_at->format('d.m.Y') }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <!-- Not Redeemed Yet - Show Button -->
                            @if($gift->isAvailable())
                                @if($user->p_coins >= $gift->p_coins_cost)
                                    <form method="POST" action="{{ route('gifts.redeem', $gift) }}" style="width: 100%;">
                                        @csrf
                                        <button type="submit" style="width: 100%; padding: 12px 24px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span class="material-icons" style="font-size: 20px;">redeem</span>
                                            გადაცვლა
                                        </button>
                                    </form>
                                @else
                                    <button disabled style="width: 100%; padding: 12px 24px; background-color: var(--bg-hover); color: var(--text-tertiary); font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <span class="material-icons" style="font-size: 20px;">lock</span>
                                        არასაკმარისი ბალანსი
                                    </button>
                                @endif
                            @else
                                <button disabled style="width: 100%; padding: 12px 24px; background-color: var(--bg-hover); color: var(--text-tertiary); font-size: 14px; font-weight: 600; border-radius: 8px; border: none; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <span class="material-icons" style="font-size: 20px;">inventory</span>
                                    მარაგი ამოწურულია
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- My Gifts Link -->
        <div style="margin-top: 40px; text-align: center;">
            <a href="{{ route('gifts.my-gifts') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background-color: var(--bg-card); color: var(--text-primary); font-size: 14px; font-weight: 600; border-radius: 8px; text-decoration: none; border: 1px solid var(--border-color); transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--bg-hover)';" onmouseout="this.style.backgroundColor='var(--bg-card)';">
                <span class="material-icons" style="font-size: 20px;">history</span>
                ჩემი გადაცვლილი საჩუქრები
            </a>
        </div>
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 80px 20px;">
            <div style="width: 120px; height: 120px; background-color: var(--bg-card); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                <span class="material-icons" style="font-size: 64px; color: var(--text-tertiary);">card_giftcard</span>
            </div>
            <h3 style="font-size: 24px; font-weight: 600; color: var(--text-primary); margin: 0 0 12px 0;">საჩუქრები მალე დაემატება</h3>
            <p style="font-size: 16px; color: var(--text-secondary); margin: 0;">ამჟამად ხელმისაწვდომი საჩუქრები არ არის</p>
        </div>
    @endif

    <script>
        function copyCodeFromCard(code) {
            navigator.clipboard.writeText(code).then(function() {
                // Show success message
                const message = document.createElement('div');
                message.style.cssText = 'position: fixed; top: 20px; right: 20px; background-color: #10b981; color: #ffffff; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); z-index: 9999; display: flex; align-items: center; gap: 8px;';
                message.innerHTML = '<span class="material-icons" style="font-size: 20px;">check_circle</span><span>კოდი დაკოპირდა!</span>';
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.style.transition = 'opacity 0.3s';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 300);
                }, 2000);
            });
        }
    </script>
</x-dashboard-layout>

