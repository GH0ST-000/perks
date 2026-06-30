<x-partner-layout :partner="$partner" title="გადახდა წარმატებულია" headerTitle="გადახდა წარმატებულია">
    <div class="max-w-lg mx-auto bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-8 text-center shadow-sm">
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
            <i data-lucide="circle-check" class="w-8 h-8 text-emerald-500"></i>
        </div>

        <h1 class="text-2xl font-black text-slate-900 dark:text-white mb-2">გამოწერა აქტიურია</h1>
        <p class="text-slate-600 dark:text-slate-300 mb-6">
            პაკეტი <strong>{{ $subscription->package_title }}</strong> წარმატებით გააქტიურდა.
        </p>

        <div class="text-left space-y-3 text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 mb-6">
            <div class="flex justify-between gap-4">
                <span>ყოველთვიური ღირებულება</span>
                <strong>₾{{ number_format($subscription->amount, 2) }}</strong>
            </div>
            <div class="flex justify-between gap-4">
                <span>შემდეგი ჩამოჭრა</span>
                <strong>{{ $subscription->next_billing_date?->format('d.m.Y') ?? '—' }}</strong>
            </div>
            <div class="flex justify-between gap-4">
                <span>სტატუსი</span>
                <strong>{{ $subscription->status === 'active' ? 'აქტიური' : 'მუშავდება...' }}</strong>
            </div>
        </div>

        <a href="{{ route('partner.marketing') }}" class="partner-btn-primary inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold">
            დაბრუნება მარკეტინგზე
        </a>
    </div>
</x-partner-layout>
