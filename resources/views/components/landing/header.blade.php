<!-- Header -->
<header class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 transition-colors duration-300 w-full">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg group-hover:scale-105 transition-transform">
                P
            </div>
            <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">
                Perks
            </span>
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center gap-8">
            <a href="{{ route('offers.index') }}" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">შეთავაზებები</a>
            <a href="#companies" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">კომპანიებისთვის</a>
            <a href="#partners" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">პარტნიორებისთვის</a>
            <a href="#shop" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">აუქციონი</a>
            <a href="#resources" class="text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">რესურსები</a>
        </nav>

        <!-- Desktop Actions -->
        <div class="hidden lg:flex items-center gap-4">
            <button onclick="toggleTheme()" class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-lg font-medium transition-all duration-200 active:scale-95 text-sm flex items-center justify-center gap-2 bg-primary-600 text-white hover:bg-primary-700 shadow-md shadow-primary-600/20">
                        კაბინეტი
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-lg font-medium transition-all duration-200 text-sm bg-primary-600 text-white hover:bg-primary-700 shadow-md">
                        შესვლა
                    </a>
                @endauth
            @endif
        </div>

        <!-- Mobile Menu Button -->
        <button onclick="toggleMobileMenu()" class="lg:hidden p-2 text-gray-600 dark:text-gray-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden lg:hidden absolute top-20 left-0 w-full bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 p-4 flex flex-col gap-4 shadow-xl animate-fade-in-down">
        <a href="{{ route('offers.index') }}" class="text-lg font-bold text-gray-800 dark:text-gray-200 py-2 border-b border-gray-50 dark:border-gray-800">შეთავაზებები</a>
        <a href="#companies" class="text-lg font-bold text-gray-800 dark:text-gray-200 py-2 border-b border-gray-50 dark:border-gray-800">კომპანიებისთვის</a>
        <a href="#partners" class="text-lg font-bold text-gray-800 dark:text-gray-200 py-2 border-b border-gray-50 dark:border-gray-800">პარტნიორებისთვის</a>
        <a href="#shop" class="text-lg font-bold text-gray-800 dark:text-gray-200 py-2 border-b border-gray-50 dark:border-gray-800">აუქციონი</a>

        <div class="flex items-center justify-between pt-2">
            <button onclick="toggleTheme()" class="p-2 bg-gray-100 dark:bg-gray-800 rounded-full">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-primary-600 font-bold text-sm">კაბინეტი</a>
                @else
                    <a href="{{ route('login') }}" class="text-primary-600 font-bold text-sm">შესვლა</a>
                @endauth
            @endif
        </div>
    </div>
</header>
