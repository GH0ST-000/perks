@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Perks') }} - User Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #1a1d29;
            color: #ffffff;
        }
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #overlay {
                display: none;
            }
            #overlay.show {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            #user-portal-text {
                display: none;
            }
        }
    </style>
</head>
<body style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background-color: #1a1d29; margin: 0; padding: 0; color: #ffffff;">
    <div style="display: flex; height: 100vh; overflow: hidden; background-color: #1a1d29; position: relative;">
        <!-- Mobile Overlay -->
        <div id="overlay" onclick="toggleSidebar()" style="display: none;"></div>

        <!-- Left Sidebar - Dark Theme -->
        <aside id="sidebar" style="width: 280px; background-color: #252836; border-right: 1px solid #2d3142; display: flex; flex-direction: column; z-index: 50; position: fixed; height: 100vh; left: 0; top: 0;">
            <!-- Header in Sidebar -->
            <div style="padding: 20px 16px; border-bottom: 1px solid #2d3142;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <span style="color: #ffffff; font-size: 20px; font-weight: 700;">P</span>
                        </div>
                        <span style="font-size: 18px; font-weight: 700; color: #ffffff;">User Portal</span>
                    </div>
                    <!-- Close button for mobile -->
                    <button onclick="toggleSidebar()" style="display: none; background: none; border: none; color: #a0aec0; cursor: pointer; padding: 8px;" id="close-sidebar-btn">
                        <span class="material-icons" style="font-size: 24px;">close</span>
                    </button>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav style="flex: 1; padding: 16px 0; overflow-y: auto;">
                <div style="padding: 0 12px;">
                    <!-- ჩემი გვერდი (My Page) -->
                    <a href="{{ route('dashboard') }}" onclick="closeSidebarOnMobile()" style="display: flex; align-items: center; height: 48px; padding: 0 16px; text-decoration: none; border-radius: 12px; margin: 4px 0; {{ request()->routeIs('dashboard') ? 'background-color: #374151; color: #3b82f6;' : 'color: #a0aec0;' }} transition: all 0.2s;">
                        <span class="material-icons" style="margin-right: 16px; font-size: 20px; {{ request()->routeIs('dashboard') ? 'color: #3b82f6;' : 'color: #a0aec0;' }}">dashboard</span>
                        <span style="font-size: 14px; font-weight: 500; {{ request()->routeIs('dashboard') ? 'color: #3b82f6;' : 'color: #a0aec0;' }}">ჩემი გვერდი</span>
                    </a>

                    <!-- ჩემი საფულე (My Wallet) -->
                    <a href="{{ route('wallet.index') }}" onclick="closeSidebarOnMobile()" style="display: flex; align-items: center; height: 48px; padding: 0 16px; text-decoration: none; border-radius: 12px; margin: 4px 0; {{ request()->routeIs('wallet.*') ? 'background-color: #374151; color: #3b82f6;' : 'color: #a0aec0;' }} transition: all 0.2s;">
                        <span class="material-icons" style="margin-right: 16px; font-size: 20px; {{ request()->routeIs('wallet.*') ? 'color: #3b82f6;' : 'color: #a0aec0;' }}">account_balance_wallet</span>
                        <span style="font-size: 14px; font-weight: 500; {{ request()->routeIs('wallet.*') ? 'color: #3b82f6;' : 'color: #a0aec0;' }}">ჩემი საფულე</span>
                    </a>

                    <!-- საჩუქრები (Gifts) -->
                    <a href="#" onclick="closeSidebarOnMobile()" style="display: flex; align-items: center; height: 48px; padding: 0 16px; text-decoration: none; border-radius: 12px; margin: 4px 0; color: #a0aec0; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#374151'; this.style.color='#3b82f6';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#a0aec0';">
                        <span class="material-icons" style="margin-right: 16px; font-size: 20px; color: #a0aec0;">card_giftcard</span>
                        <span style="font-size: 14px; font-weight: 500;">საჩუქრები</span>
                    </a>

                    <!-- ისტორია (History) -->
                    <a href="#" onclick="closeSidebarOnMobile()" style="display: flex; align-items: center; height: 48px; padding: 0 16px; text-decoration: none; border-radius: 12px; margin: 4px 0; color: #a0aec0; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#374151'; this.style.color='#3b82f6';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#a0aec0';">
                        <span class="material-icons" style="margin-right: 16px; font-size: 20px; color: #a0aec0;">history</span>
                        <span style="font-size: 14px; font-weight: 500;">ისტორია</span>
                    </a>

                    <!-- Perks Family -->
                    <a href="#" onclick="closeSidebarOnMobile()" style="display: flex; align-items: center; height: 48px; padding: 0 16px; text-decoration: none; border-radius: 12px; margin: 4px 0; color: #a0aec0; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#374151'; this.style.color='#3b82f6';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#a0aec0';">
                        <span class="material-icons" style="margin-right: 16px; font-size: 20px; color: #a0aec0;">people</span>
                        <span style="font-size: 14px; font-weight: 500;">Perks Family</span>
                    </a>
                </div>
            </nav>

            <!-- Logout - RED -->
            <div style="padding: 16px 12px; border-top: 1px solid #2d3142;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" onclick="closeSidebarOnMobile()" style="display: flex; align-items: center; width: 100%; height: 48px; padding: 0 16px; text-decoration: none; border: none; background: transparent; border-radius: 12px; cursor: pointer; color: #ef4444; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#2d3142';" onmouseout="this.style.backgroundColor='transparent';">
                        <span class="material-icons" style="margin-right: 16px; font-size: 20px; color: #ef4444;">exit_to_app</span>
                        <span style="font-size: 14px; font-weight: 500; color: #ef4444;">გასვლა</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden; background-color: #1a1d29; margin-left: 0;">
            <!-- Top Header Bar -->
            <header style="height: 64px; background-color: #252836; border-bottom: 1px solid #2d3142; display: flex; align-items: center; justify-content: space-between; padding: 0 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleSidebar()" id="mobile-menu-btn" style="display: none; background: none; border: none; color: #ffffff; cursor: pointer; padding: 8px; margin-right: 8px;">
                        <span class="material-icons" style="font-size: 24px;">menu</span>
                    </button>
                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #ffffff; font-size: 18px; font-weight: 600;">P</span>
                    </div>
                    <span id="user-portal-text" style="font-size: 18px; font-weight: 600; color: #ffffff;">User Portal</span>
                </div>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background-color: #2d3142; border-radius: 24px;">
                        <div style="width: 24px; height: 24px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span style="color: #ffffff; font-size: 12px; font-weight: 600;">P</span>
                        </div>
                        <span style="font-size: 14px; font-weight: 500; color: #ffffff;">ბალანსი {{ auth()->user()->p_coins ?? 0 }} P</span>
                    </div>
                    
                    <!-- User Profile Icon with Dropdown -->
                    <div style="position: relative; display: flex; align-items: center; gap: 8px;" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" style="width: 48px; height: 48px; border-radius: 50%; background-color: #374151; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; border: none;">
                            @php
                                $user = auth()->user();
                                $profilePhoto = $user->profile_photo;
                                $photoUrl = null;
                                if ($profilePhoto) {
                                    if (str_starts_with($profilePhoto, 'http://') || str_starts_with($profilePhoto, 'https://')) {
                                        $photoUrl = $profilePhoto;
                                    } elseif (str_starts_with($profilePhoto, 'data:image')) {
                                        $photoUrl = $profilePhoto;
                                    } else {
                                        $photoUrl = asset('storage/' . ltrim($profilePhoto, '/'));
                                    }
                                }
                            @endphp
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'; this.parentElement.querySelector('.default-user-icon').style.display='flex';">
                            @endif
                            <svg class="default-user-icon lucide lucide-user" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 100%; height: 100%; padding: 8px; color: #9ca3af; {{ $photoUrl ? 'display: none;' : 'display: flex;' }}" aria-hidden="true">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </button>
                        <!-- Chevron Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor: pointer; transition: transform 0.2s;" :style="open ? 'transform: rotate(180deg);' : ''" @click="open = !open">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             style="position: absolute; top: calc(100% + 8px); right: 0; background-color: #252836; border-radius: 12px; border: 1px solid #2d3142; min-width: 240px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); z-index: 1000; overflow: hidden;"
                             x-cloak>
                            <!-- User Info Header -->
                            <div style="padding: 16px; border-bottom: 1px solid #2d3142;">
                                <p style="font-size: 14px; font-weight: 700; color: #ffffff; margin: 0 0 4px 0;">{{ auth()->user()->name }}</p>
                                <p style="font-size: 12px; color: #9ca3af; margin: 0; word-break: break-all;">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- Menu Items -->
                            <div style="padding: 8px 0;">
                                <!-- მთავარი გვერდი -->
                                <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #ffffff; text-decoration: none; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#2d3142';" onmouseout="this.style.backgroundColor='transparent';">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span style="font-size: 14px; font-weight: 500;">მთავარი გვერდი</span>
                                </a>

                                <!-- შეთავაზებები -->
                                <a href="{{ route('offers.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #ffffff; text-decoration: none; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#2d3142';" onmouseout="this.style.backgroundColor='transparent';">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;">
                                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5"></path>
                                        <path d="M2 12l10 5 10-5"></path>
                                    </svg>
                                    <span style="font-size: 14px; font-weight: 500;">შეთავაზებები</span>
                                </a>

                                <!-- პროფილის რეგისტრაცია -->
                                <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #ffffff; text-decoration: none; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#2d3142';" onmouseout="this.style.backgroundColor='transparent';">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span style="font-size: 14px; font-weight: 500;">პროფილის რეგისტრაცია</span>
                                </a>

                                <!-- Divider -->
                                <div style="height: 1px; background-color: #2d3142; margin: 8px 0;"></div>

                                <!-- გასვლა (Logout) -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; width: 100%; background: none; border: none; color: #ef4444; text-align: left; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#2d3142';" onmouseout="this.style.backgroundColor='transparent';">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ef4444;">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <polyline points="16 8 20 12 16 16"></polyline>
                                            <line x1="20" y1="12" x2="8" y2="12"></line>
                                        </svg>
                                        <span style="font-size: 14px; font-weight: 500; color: #ef4444;">გასვლა</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main style="flex: 1; overflow-y: auto; background-color: #1a1d29; padding: 24px;">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const closeBtn = document.getElementById('close-sidebar-btn');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
            
            // Show/hide close button on mobile
            if (window.innerWidth <= 768) {
                closeBtn.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
            }
        }

        function closeSidebarOnMobile() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                const closeBtn = document.getElementById('close-sidebar-btn');
                
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                closeBtn.style.display = 'none';
            }
        }

        // Show/hide mobile menu button and adjust sidebar based on screen size
        function handleResize() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeBtn = document.getElementById('close-sidebar-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const mainContent = document.querySelector('div[style*="flex: 1"]');
            
            if (window.innerWidth <= 768) {
                mobileMenuBtn.style.display = 'block';
                closeBtn.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
                sidebar.style.position = 'fixed';
                mainContent.style.marginLeft = '0';
            } else {
                mobileMenuBtn.style.display = 'none';
                closeBtn.style.display = 'none';
                sidebar.style.position = 'relative';
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                mainContent.style.marginLeft = '0';
            }
        }

        // Initial check
        handleResize();
        
        // Listen for resize events
        window.addEventListener('resize', handleResize);
    </script>
</body>
</html>
