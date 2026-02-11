<x-dashboard-layout>
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span class="material-icons" style="font-size: 32px; color: #10b981;">inventory</span>
                <h1 style="font-size: 32px; font-weight: 700; color: #ffffff; margin: 0;">ჩემი საჩუქრები</h1>
            </div>
            <a href="{{ route('gifts.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background-color: #252836; color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; text-decoration: none; border: 1px solid #2d3142; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2d3142';" onmouseout="this.style.backgroundColor='#252836';">
                <span class="material-icons" style="font-size: 20px;">arrow_back</span>
                საჩუქრებზე დაბრუნება
            </a>
        </div>
        <p style="font-size: 16px; color: #a0aec0; margin: 0;">თქვენი გადაცვლილი საჩუქრების ისტორია</p>
    </div>

    @if($redemptions->count() > 0)
        <!-- Redemptions List -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($redemptions as $redemption)
                <div style="background-color: #252836; border-radius: 16px; padding: 24px; border: 1px solid #2d3142;">
                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: start;">
                        <!-- Left Side: Gift Info -->
                        <div style="display: flex; gap: 20px;">
                            <!-- Gift Image -->
                            <div style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; background-color: #1a1d29; flex-shrink: 0;">
                                @if($redemption->gift->image)
                                    <img src="{{ asset('storage/' . $redemption->gift->image) }}" alt="{{ $redemption->gift->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <span class="material-icons" style="font-size: 40px; color: rgba(255,255,255,0.5);">card_giftcard</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Gift Details -->
                            <div style="flex: 1;">
                                <h3 style="font-size: 18px; font-weight: 700; color: #ffffff; margin: 0 0 8px 0;">{{ $redemption->gift->name }}</h3>
                                <p style="font-size: 14px; color: #a0aec0; margin: 0 0 12px 0;">{{ $redemption->gift->description }}</p>
                                
                                <!-- Redemption Code -->
                                @if($redemption->redemption_code)
                                    <div style="background-color: #1a1d29; border: 1px solid #2d3142; border-radius: 8px; padding: 12px; display: inline-flex; align-items: center; gap: 12px;">
                                        <span class="material-icons" style="font-size: 20px; color: #3b82f6;">qr_code</span>
                                        <div>
                                            <p style="font-size: 11px; color: #9ca3af; margin: 0 0 2px 0; text-transform: uppercase; letter-spacing: 0.5px;">პრომო კოდი</p>
                                            <p style="font-size: 16px; font-weight: 700; color: #ffffff; margin: 0; font-family: monospace;">{{ $redemption->redemption_code }}</p>
                                        </div>
                                        <button onclick="copyCode('{{ $redemption->redemption_code }}')" style="background: none; border: none; cursor: pointer; padding: 8px;">
                                            <span class="material-icons" style="font-size: 20px; color: #9ca3af;">content_copy</span>
                                        </button>
                                    </div>
                                @endif

                                <!-- Redemption Info -->
                                <div style="display: flex; gap: 16px; margin-top: 12px; flex-wrap: wrap;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span class="material-icons" style="font-size: 16px; color: #9ca3af;">calendar_today</span>
                                        <span style="font-size: 13px; color: #9ca3af;">{{ $redemption->redeemed_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    @if($redemption->expires_at)
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <span class="material-icons" style="font-size: 16px; color: #9ca3af;">schedule</span>
                                            <span style="font-size: 13px; color: #9ca3af;">ვადა: {{ $redemption->expires_at->format('d.m.Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Status & Price -->
                        <div style="text-align: right;">
                            <!-- Status Badge -->
                            @if($redemption->status === 'completed')
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 12px; font-weight: 600; border-radius: 12px; margin-bottom: 12px;">
                                    <span class="material-icons" style="font-size: 16px;">check_circle</span>
                                    აქტიური
                                </div>
                            @elseif($redemption->status === 'used')
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background-color: rgba(156, 163, 175, 0.1); color: #9ca3af; font-size: 12px; font-weight: 600; border-radius: 12px; margin-bottom: 12px;">
                                    <span class="material-icons" style="font-size: 16px;">done_all</span>
                                    გამოყენებული
                                </div>
                            @elseif($redemption->status === 'expired')
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background-color: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 12px; font-weight: 600; border-radius: 12px; margin-bottom: 12px;">
                                    <span class="material-icons" style="font-size: 16px;">event_busy</span>
                                    ვადაგასული
                                </div>
                            @endif

                            <!-- Price -->
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 20px;">
                                <span style="font-size: 16px; font-weight: 700; color: #ffffff;">{{ $redemption->p_coins_spent }} P</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 80px 20px;">
            <div style="width: 120px; height: 120px; background-color: #252836; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                <span class="material-icons" style="font-size: 64px; color: #9ca3af;">inventory</span>
            </div>
            <h3 style="font-size: 24px; font-weight: 600; color: #ffffff; margin: 0 0 12px 0;">არ გაქვთ გადაცვლილი საჩუქრები</h3>
            <p style="font-size: 16px; color: #a0aec0; margin: 0 0 24px 0;">დაიწყეთ თქვენი P ქულების გადაცვლა საჩუქრებზე</p>
            <a href="{{ route('gifts.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; font-size: 14px; font-weight: 600; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <span class="material-icons" style="font-size: 20px;">card_giftcard</span>
                საჩუქრების ნახვა
            </a>
        </div>
    @endif

    <script>
        function copyCode(code) {
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

