<x-dashboard-layout>
    <div style="max-width: 800px; margin: 0 auto; padding: 40px 0;">
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 48px; border: none; box-shadow: var(--shadow-card); text-align: center;">
            <!-- Error Icon -->
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>

            <h1 style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 12px 0;">გადახდა ვერ განხორციელდა</h1>
            <p style="font-size: 16px; color: var(--text-secondary); margin: 0 0 32px 0;">სამწუხაროდ, თქვენი გადახდა ვერ დასრულდა წარმატებით</p>

            @if($payment)
            <!-- Payment Details -->
            <div style="background-color: var(--bg-secondary); border-radius: 12px; padding: 24px; margin-bottom: 32px; text-align: left;">
                <h3 style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0 0 16px 0;">გადახდის დეტალები</h3>
                
                <div style="display: grid; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                        <span style="color: var(--text-secondary);">შეკვეთის ID:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ $payment->external_order_id }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                        <span style="color: var(--text-secondary);">თანხა:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-secondary);">სტატუსი:</span>
                        <span style="font-weight: 600; color: #ef4444;">{{ $payment->status === 'failed' ? 'წარუმატებელი' : ucfirst($payment->status) }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Help Text -->
            <div style="background-color: #fef3c7; border-radius: 12px; padding: 16px; margin-bottom: 32px; text-align: left; border-left: 4px solid #f59e0b;">
                <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.6;">
                    <strong>რჩევები:</strong><br>
                    • დარწმუნდით, რომ თქვენს ბარათზე საკმარისი თანხაა<br>
                    • შეამოწმეთ ბარათის დეტალები<br>
                    • თუ პრობლემა გრძელდება, დაუკავშირდით თქვენს ბანკს
                </p>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px; justify-content: center;">
                <a href="{{ route('payments.index') }}" style="padding: 14px 28px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: 600; transition: all 0.3s; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); display: inline-block;" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)';">
                    ხელახლა ცდა
                </a>
                <a href="{{ route('dashboard') }}" style="padding: 14px 28px; background-color: var(--bg-secondary); color: var(--text-primary); text-decoration: none; border-radius: 10px; font-weight: 600; transition: all 0.3s; border: 1px solid var(--border-color); display: inline-block;" onmouseover="this.style.backgroundColor='var(--bg-card)';" onmouseout="this.style.backgroundColor='var(--bg-secondary)';">
                    მთავარ გვერდზე
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout>

