<x-dashboard-layout>
    <!-- Page Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <span class="material-icons" style="font-size: 32px; color: #3b82f6;">history</span>
            <h1 style="font-size: 32px; font-weight: 700; color: var(--text-primary); margin: 0;">ისტორია</h1>
        </div>
        <p style="font-size: 16px; color: var(--text-secondary); margin: 0;">თქვენი ტრანზაქციებისა და ვიზიტების ისტორია</p>
    </div>

    @if($history->count() > 0)
        <!-- History Timeline -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            @foreach($history as $item)
                @if($item['type'] === 'visit')
                    @php
                        $visit = $item['data'];
                        $partner = $visit->partner;
                    @endphp
                    <!-- Visit Item -->
                    <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 24px; border: none; box-shadow: var(--shadow-card); transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0, 0, 0, 0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <!-- Icon -->
                            <div style="width: 56px; height: 56px; background-color: rgba(59, 130, 246, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-icons" style="font-size: 28px; color: #3b82f6;">location_on</span>
                            </div>

                            <!-- Content -->
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: start; justify-content: space-between; gap: 16px;">
                                    <div style="flex: 1; min-width: 0;">
                                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px 0;">
                                            {{ $partner ? $partner->name : 'Partner Location' }}
                                        </h3>
                                        <p style="font-size: 14px; color: var(--text-tertiary); margin: 0;">
                                            {{ $visit->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>

                                    <!-- Visit Badge -->
                                    <div style="text-align: right;">
                                        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background-color: rgba(59, 130, 246, 0.1); border-radius: 12px;">
                                            <span class="material-icons" style="font-size: 16px; color: #3b82f6;">check_circle</span>
                                            <span style="font-size: 13px; font-weight: 600; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px;">ვიზიტი</span>
                                        </div>
                                    </div>
                                </div>

                                @if($visit->notes)
                                    <p style="font-size: 13px; color: var(--text-secondary); margin: 8px 0 0 0;">{{ $visit->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                    @php
                        $transaction = $item['data'];
                        $isCredit = $transaction->amount > 0;
                    @endphp
                    <!-- Transaction Item -->
                    <div style="background-color: var(--bg-card); border-radius: var(--card-radius); padding: 24px; border: none; box-shadow: var(--shadow-card); transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0, 0, 0, 0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-card)';">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <!-- Icon -->
                            <div style="width: 56px; height: 56px; background-color: rgba({{ $isCredit ? '16, 185, 129' : '239, 68, 68' }}, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                @if($transaction->type === 'credit' || $transaction->type === 'reward')
                                    <span class="material-icons" style="font-size: 28px; color: #10b981;">add_circle</span>
                                @elseif($transaction->type === 'debit' || $transaction->type === 'purchase')
                                    <span class="material-icons" style="font-size: 28px; color: #ef4444;">remove_circle</span>
                                @elseif($transaction->type === 'refund')
                                    <span class="material-icons" style="font-size: 28px; color: #3b82f6;">replay</span>
                                @else
                                    <span class="material-icons" style="font-size: 28px; color: var(--text-tertiary);">sync_alt</span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: start; justify-content: space-between; gap: 16px;">
                                    <div style="flex: 1; min-width: 0;">
                                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0 0 4px 0;">
                                            {{ $transaction->description ?? ucfirst($transaction->type) }}
                                        </h3>
                                        <p style="font-size: 14px; color: var(--text-tertiary); margin: 0;">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        
                                        <!-- Transaction Type Badge -->
                                        <div style="margin-top: 6px;">
                                            @if($transaction->type === 'credit')
                                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 11px; font-weight: 600; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    ჩარიცხვა
                                                </span>
                                            @elseif($transaction->type === 'debit')
                                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background-color: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 11px; font-weight: 600; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    ჩამოწერა
                                                </span>
                                            @elseif($transaction->type === 'reward')
                                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background-color: rgba(168, 85, 247, 0.1); color: #a855f7; font-size: 11px; font-weight: 600; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    ჯილდო
                                                </span>
                                            @elseif($transaction->type === 'purchase')
                                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; font-size: 11px; font-weight: 600; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    შეძენა
                                                </span>
                                            @elseif($transaction->type === 'refund')
                                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; font-size: 11px; font-weight: 600; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    დაბრუნება
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Amount Badge -->
                                    <div style="text-align: right;">
                                        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: linear-gradient(135deg, {{ $isCredit ? '#10b981 0%, #059669' : '#ef4444 0%, #dc2626' }} 100%); border-radius: 20px;">
                                            <span style="font-size: 18px; font-weight: 700; color: #ffffff;">{{ $isCredit ? '+' : '' }}{{ $transaction->amount }} P</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Load More / Pagination Info -->
        <div style="margin-top: 32px; text-align: center; padding: 20px;">
            <p style="font-size: 14px; color: var(--text-tertiary); margin: 0;">
                სულ ნაჩვენებია {{ $history->count() }} ჩანაწერი
            </p>
        </div>
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 80px 20px;">
            <div style="width: 120px; height: 120px; background-color: var(--bg-card); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                <span class="material-icons" style="font-size: 64px; color: var(--text-tertiary);">history</span>
            </div>
            <h3 style="font-size: 24px; font-weight: 600; color: var(--text-primary); margin: 0 0 12px 0;">ისტორია ცარიელია</h3>
            <p style="font-size: 16px; color: var(--text-secondary); margin: 0;">თქვენ ჯერ არ გაქვთ ტრანზაქციების ან ვიზიტების ისტორია</p>
        </div>
    @endif
</x-dashboard-layout>

