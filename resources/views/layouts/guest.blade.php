<!DOCTYPE html>
<html lang="ka">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perks') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        
        <script>
            tailwind.config = {
                darkMode: 'class',
            }
        </script>

        <style>
            html, body {
                margin: 0;
                padding: 0;
            }
            input::placeholder {
                color: #B9BBBE !important;
            }
        </style>
        
        <script>
            // Load theme immediately to prevent flash
            (function() {
                const theme = localStorage.getItem('theme');
                // Default to dark mode to match design
                if (theme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else {
                    document.documentElement.classList.add('dark');
                }
            })();
            
            // Theme toggle function
            function toggleTheme() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        </script>
    </head>
    <body class="antialiased font-sans bg-white dark:bg-slate-900 transition-colors duration-200" style="font-family: 'Inter', sans-serif; margin: 0; padding: 0;">
        <!-- Header -->
        <header class="w-full bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-gray-800 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Logo and Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                        P
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white transition-colors">Perks</span>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('offers.index') }}" class="text-sm font-medium text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300 transition-colors">შეთავაზებები</a>
                    <a href="{{ route('companies') }}" class="text-sm font-medium text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300 transition-colors">კომპანიებისთვის</a>
                    <a href="{{ route('partners') }}" class="text-sm font-medium text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300 transition-colors">პარტნიორებისთვის</a>
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300 transition-colors">ბლოგი</a>
                    <a href="#" class="text-sm font-medium text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300 transition-colors">კავშირი</a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <button onclick="toggleTheme()" class="p-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                    
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-white hover:text-gray-900 dark:hover:text-gray-300 transition-colors">
                            შესვლა
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors">
                            რეგისტრაცია
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-sm transition-colors">
                            დაშბორდი
                        </a>
                    @endguest
                </div>
            </div>
        </header>

        <div class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0 bg-white dark:bg-slate-900 transition-colors duration-200 w-full">
            <!-- Card -->
            <div class="w-full sm:max-w-md px-8 py-10 rounded-2xl bg-gray-50 dark:bg-slate-800 transition-colors duration-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
