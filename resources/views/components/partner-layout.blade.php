@props([
    'partner',
    'headerTitle' => 'პარტნიორთა ცენტრი',
    'title' => null,
    'showManagementHeading' => true,
])

@php
    $categoryName = $partner->categories->first()?->name ?? 'პარტნიორი';
    $logoUrl = $partner->logo ? \Illuminate\Support\Facades\Storage::url($partner->logo) : null;

    $navItems = [
        ['route' => 'partner.dashboard', 'label' => 'პანელი', 'icon' => 'layout-dashboard'],
        ['route' => 'partner.scanner', 'label' => 'სკანერი', 'icon' => 'scan-line'],
        ['route' => 'partner.offers', 'label' => 'შეთავაზებები', 'icon' => 'tag'],
        ['route' => 'partner.marketing', 'label' => 'მარკეტინგი', 'icon' => 'megaphone'],
        ['route' => 'partner.history', 'label' => 'ისტორია', 'icon' => 'history'],
        ['route' => 'partner.settings', 'label' => 'პარამეტრები', 'icon' => 'settings'],
    ];
@endphp

<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Perks') }} - {{ ($title ?? $headerTitle) }}</title>
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/partner-portal.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-[#fcfdfe] text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
    <div id="partner-app" class="flex min-h-screen">
        <div id="partner-overlay"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden transition-opacity hidden"
             onclick="closePartnerSidebar()"></div>

        <aside id="partner-sidebar"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:block">
            <div class="flex flex-col h-full">
                <div class="p-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-600 dark:bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">P</div>
                        <span class="font-bold text-xl tracking-wider text-slate-800 dark:text-slate-100 uppercase">პარტნიორი</span>
                    </div>
                    <button type="button" onclick="closePartnerSidebar()" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <nav class="flex-1 px-4 mt-2 space-y-1 overflow-y-auto">
                    @foreach($navItems as $item)
                        @php $active = request()->routeIs($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                           onclick="closePartnerSidebar()"
                           class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 group {{ $active ? 'bg-blue-600 dark:bg-blue-500 text-white shadow-lg shadow-blue-200/50 dark:shadow-none' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px]"></i>
                            <span class="font-medium text-[14px]">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="p-4 border-t border-slate-50 dark:border-slate-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-colors">
                            <i data-lucide="log-out" class="w-[18px] h-[18px]"></i>
                            <span class="font-medium text-[14px]">გასვლა</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="h-14 md:h-16 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between px-3 md:px-8 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-30 transition-colors duration-300">
                <div class="flex items-center gap-2 md:gap-3">
                    <button type="button"
                            onclick="togglePartnerSidebar()"
                            class="lg:hidden p-1.5 -ml-1 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
                            aria-label="Menu">
                        <i data-lucide="menu" class="w-[18px] h-[18px]"></i>
                    </button>
                    <div class="font-bold text-sm md:text-lg text-slate-800 dark:text-slate-100 truncate max-w-[120px] xs:max-w-[180px] md:max-w-none">
                        {{ $headerTitle }}
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-6">
                    <button type="button"
                            onclick="togglePartnerTheme()"
                            class="p-1.5 md:p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors text-slate-600 dark:text-slate-400"
                            aria-label="Toggle dark mode">
                        <i data-lucide="moon" id="partner-theme-icon-moon" class="w-4 h-4"></i>
                        <i data-lucide="sun" id="partner-theme-icon-sun" class="w-4 h-4 hidden"></i>
                    </button>
                    <div class="flex items-center gap-2 md:gap-3">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs md:text-sm font-bold text-slate-900 dark:text-slate-100">{{ $partner->name }}</div>
                            <div class="text-[10px] font-bold text-blue-600 dark:text-blue-400 tracking-widest uppercase">{{ $categoryName }}</div>
                        </div>
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl object-cover border-2 border-slate-50 dark:border-slate-800">
                        @else
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-blue-600 dark:bg-blue-500 flex items-center justify-center text-white font-bold text-sm border-2 border-slate-50 dark:border-slate-800">
                                {{ mb_substr($partner->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <main class="p-3 md:p-8 flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    @if($showManagementHeading)
                        <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-6 tracking-tight">
                            მართვა: <span class="text-blue-600 dark:text-blue-400">{{ $partner->name }}</span>
                        </h1>
                    @endif
                    <div class="animate-fade-in">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        function togglePartnerSidebar() {
            const sidebar = document.getElementById('partner-sidebar');
            const overlay = document.getElementById('partner-overlay');
            const open = sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full', !open);
            sidebar.classList.toggle('translate-x-0', open);
            overlay.classList.toggle('hidden', !open);
        }

        function closePartnerSidebar() {
            document.getElementById('partner-sidebar').classList.add('-translate-x-full');
            document.getElementById('partner-sidebar').classList.remove('translate-x-0');
            document.getElementById('partner-overlay').classList.add('hidden');
        }

        function togglePartnerTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('partner-theme', isDark ? 'dark' : 'light');
            document.getElementById('partner-theme-icon-moon').classList.toggle('hidden', isDark);
            document.getElementById('partner-theme-icon-sun').classList.toggle('hidden', !isDark);
            if (window.partnerVisitChart) {
                window.partnerVisitChart.options.scales.x.ticks.color = isDark ? '#94a3b8' : '#94a3b8';
                window.partnerVisitChart.options.scales.y.ticks.color = isDark ? '#94a3b8' : '#94a3b8';
                window.partnerVisitChart.options.scales.y.grid.color = isDark ? '#334155' : '#e2e8f0';
                window.partnerVisitChart.update();
            }
            lucide.createIcons();
        }

        (function () {
            const saved = localStorage.getItem('partner-theme') || 'light';
            if (saved === 'dark') {
                document.documentElement.classList.add('dark');
                document.getElementById('partner-theme-icon-moon').classList.add('hidden');
                document.getElementById('partner-theme-icon-sun').classList.remove('hidden');
            }
            lucide.createIcons();
        })();
    </script>
    @stack('scripts')
</body>
</html>
