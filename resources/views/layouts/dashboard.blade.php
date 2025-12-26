<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perks') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            font-family: 'Google Sans', 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
    </style>
</head>
<body class="antialiased" style="font-family: 'Google Sans', 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background-color: #ffffff;">
    <div class="flex h-screen overflow-hidden" style="background-color: #ffffff;">
        <!-- Left Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col" style="background-color: #ffffff; border-right: 1px solid #e8eaed;">
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-gray-200" style="border-bottom: 1px solid #e8eaed;">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-lg font-bold">P</span>
                    </div>
                    <span class="text-xl font-medium text-gray-900" style="color: #202124; font-weight: 500;">Perks</span>
                </a>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-3 py-4 overflow-y-auto">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}" style="color: {{ request()->routeIs('dashboard') ? '#202124' : '#5f6368' }}; background-color: {{ request()->routeIs('dashboard') ? '#f1f3f4' : 'transparent' }};">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>დაშბორდი</span>
                    </a>

                    <!-- Offers -->
                    <a href="{{ route('offers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('offers.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}" style="color: {{ request()->routeIs('offers.*') ? '#202124' : '#5f6368' }}; background-color: {{ request()->routeIs('offers.*') ? '#f1f3f4' : 'transparent' }};">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span>შეთავაზებები</span>
                    </a>

                    <!-- Vacancies -->
                    <a href="{{ route('vacancies.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('vacancies.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}" style="color: {{ request()->routeIs('vacancies.*') ? '#202124' : '#5f6368' }}; background-color: {{ request()->routeIs('vacancies.*') ? '#f1f3f4' : 'transparent' }};">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>ვაკანსიები</span>
                    </a>

                    <!-- Blog -->
                    <a href="{{ route('blog.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('blog.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}" style="color: {{ request()->routeIs('blog.*') ? '#202124' : '#5f6368' }}; background-color: {{ request()->routeIs('blog.*') ? '#f1f3f4' : 'transparent' }};">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4 13H5a2 2 0 01-2-2V6a2 2 0 012-2h4m6 13a2 2 0 01-2-2V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>ბლოგი</span>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}" style="color: {{ request()->routeIs('profile.*') ? '#202124' : '#5f6368' }}; background-color: {{ request()->routeIs('profile.*') ? '#f1f3f4' : 'transparent' }};">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>პროფილი</span>
                    </a>
                </div>
            </nav>

            <!-- User Section -->
            <div class="px-3 py-4 border-t border-gray-200" style="border-top: 1px solid #e8eaed;">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate" style="color: #202124;">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate" style="color: #5f6368;">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors" style="color: #5f6368;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>გასვლა</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden" style="background-color: #ffffff;">
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center px-6" style="background-color: #ffffff; border-bottom: 1px solid #e8eaed;">
                <h1 class="text-xl font-normal text-gray-900" style="color: #202124; font-weight: 400;">
                    @isset($header)
                        {{ $header }}
                    @else
                        დაშბორდი
                    @endisset
                </h1>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto" style="background-color: #ffffff;">
                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>

