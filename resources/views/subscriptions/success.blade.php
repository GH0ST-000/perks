<x-dashboard-layout>
    <div style="max-width: 800px; margin: 0 auto; padding: 40px 0;">
        <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 48px; border: none; box-shadow: var(--shadow-card); text-align: center;">
            <!-- Success Icon -->
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>

            <h1 style="font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0 0 12px 0;">გამოწერა წარმატებით გააქტიურდა!</h1>
            <p style="font-size: 16px; color: var(--text-secondary); margin: 0 0 32px 0;">თქვენი გამოწერა აქტიურია და ავტომატურად განახლდება</p>

            <!-- Subscription Details -->
            <div style="background-color: var(--bg-secondary); border-radius: 12px; padding: 24px; margin-bottom: 32px; text-align: left;">
                <h3 style="font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0 0 16px 0;">გამოწერის დეტალები</h3>
                
                <div style="display: grid; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                        <span style="color: var(--text-secondary);">გეგმა:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ $subscription->name }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                        <span style="color: var(--text-secondary);">თანხა:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ number_format($subscription->amount, 2) }} {{ $subscription->currency }} / {{ $subscription->type === 'monthly' ? 'თვე' : 'წელი' }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                        <span style="color: var(--text-secondary);">მიმდინარე პერიოდი:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ $subscription->current_period_start->format('d/m/Y') }} - {{ $subscription->current_period_end->format('d/m/Y') }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid var(--border-color);">
                        <span style="color: var(--text-secondary);">შემდეგი გადახდა:</span>
                        <span style="font-weight: 600; color: var(--text-primary);">{{ $subscription->next_billing_date->format('d/m/Y') }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-secondary);">სტატუსი:</span>
                        <span style="font-weight: 600; color: #10b981;">{{ $subscription->status === 'active' ? 'აქტიური' : ucfirst($subscription->status) }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div style="background-color: #dbeafe; border-radius: 12px; padding: 16px; margin-bottom: 32px; text-align: left; border-left: 4px solid #3b82f6;">
                <p style="margin: 0; color: #1e40af; font-size: 14px; line-height: 1.6;">
                    <strong>მნიშვნელოვანი:</strong><br>
                    თქვენი გამოწერა ავტომატურად განახლდება თითოეული პერიოდის ბოლოს. შეგიძლიათ ნებისმიერ დროს გააუქმოთ გამოწერა თქვენი ანგარიშის პარამეტრებიდან.
                </p>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px; justify-content: center;">
                <a href="{{ route('subscriptions.index') }}" style="padding: 14px 28px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: 600; transition: all 0.3s; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); display: inline-block;" onmouseover="this.style.transform='scale(1.03)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.3)';">
                    გამოწერების მართვა
                </a>
                <a href="{{ route('dashboard') }}" style="padding: 14px 28px; background-color: var(--bg-secondary); color: var(--text-primary); text-decoration: none; border-radius: 10px; font-weight: 600; transition: all 0.3s; border: 1px solid var(--border-color); display: inline-block;" onmouseover="this.style.backgroundColor='var(--bg-card)';" onmouseout="this.style.backgroundColor='var(--bg-secondary)';">
                    მთავარ გვერდზე
                </a>
            </div>
        </div>
    </div>
</x-dashboard-layout>

