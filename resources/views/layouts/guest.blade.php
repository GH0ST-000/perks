<!DOCTYPE html>
<html lang="ka">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perks') }} - {{ $title ?? 'შესვლა' }}</title>

        @include('partials.favicon')

        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            
            function toggleTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            }

            function toggleMobileMenu() {
                document.getElementById('mobileMenu')?.classList.toggle('hidden');
            }
        </script>
    </head>
    <body class="antialiased font-sans bg-white dark:bg-gray-900 transition-colors duration-300" style="font-family: 'Inter', sans-serif; margin: 0; padding: 0;">
        @include('components.landing.header')

        <div class="min-h-[calc(100vh-5rem)] flex flex-col items-center justify-center px-4 sm:px-6 py-8 sm:py-12 bg-white dark:bg-gray-900 transition-colors duration-300 w-full">
            <!-- Card -->
            <div class="w-full sm:max-w-md px-8 py-10 sm:px-10 sm:py-12 rounded-2xl bg-gray-50 dark:bg-slate-800 transition-colors duration-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
