<x-partner-layout :partner="$partner" title="პანელი" headerTitle="პანელი">
    <div class="space-y-4 md:space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
            <x-partner.stat-card
                icon="users"
                :value="number_format($stats['visits_count'])"
                label="ვიზიტები"
                :days="$stats['period_label']"
                color="bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400"
            />
            <x-partner.stat-card
                icon="star"
                :value="$stats['rating'] > 0 ? number_format($stats['rating'], 1) : '—'"
                label="რეიტინგი"
                :days="$stats['period_label']"
                color="bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400"
            />
            <x-partner.stat-card
                icon="zap"
                :value="($stats['growth'] >= 0 ? '+' : '') . $stats['growth'] . '%'"
                label="ზრდა"
                :days="$stats['period_label']"
                color="bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400"
            />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-4 md:p-5 rounded-2xl md:rounded-3xl shadow-sm border border-slate-50 dark:border-slate-800">
                <div class="flex justify-between items-center mb-4 md:mb-6">
                    <h3 class="font-bold text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest">ვიზიტების დინამიკა</h3>
                </div>
                <div class="h-[200px] md:h-[240px] w-full">
                    <canvas id="partner-visits-chart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-4 md:p-5 rounded-2xl md:rounded-3xl shadow-sm border border-slate-50 dark:border-slate-800">
                <h3 class="font-bold text-slate-400 dark:text-slate-500 text-[10px] uppercase tracking-widest mb-6 md:mb-8">მომხმარებელთა დემოგრაფია</h3>
                <div class="space-y-4 md:space-y-6">
                    @foreach($stats['demographics'] as $row)
                        <div class="space-y-1 md:space-y-2">
                            <div class="flex justify-between text-xs md:text-sm font-bold text-slate-700 dark:text-slate-300">
                                <span>{{ $row['label'] }}</span>
                                <span>{{ $row['percent'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $row['label'] === 'ქალი' ? 'bg-pink-500 dark:bg-pink-400' : 'bg-blue-600 dark:bg-blue-500' }}"
                                     style="width: {{ $row['percent'] }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? '#334155' : '#e2e8f0';
                const tickColor = '#94a3b8';

                const ctx = document.getElementById('partner-visits-chart');
                if (!ctx) return;

                window.partnerVisitChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($stats['chart_labels']),
                        datasets: [{
                            data: @json($stats['chart_values']),
                            borderColor: '#2563eb',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 5,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderWidth: 0,
                                cornerRadius: 12,
                                padding: 12,
                            },
                        },
                        scales: {
                            x: {
                                display: false,
                            },
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: {
                                    color: gridColor,
                                    borderDash: [3, 3],
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: tickColor,
                                    font: { size: 10 },
                                    maxTicksLimit: 4,
                                },
                            },
                        },
                    },
                });

                lucide.createIcons();
            });
        </script>
    @endpush
</x-partner-layout>
