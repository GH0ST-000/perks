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

        <style>
            html, body {
                background-color: #0f172a !important;
                margin: 0;
                padding: 0;
            }
            input::placeholder {
                color: #B9BBBE !important;
            }
        </style>
    </head>
    <body class="antialiased font-sans" style="font-family: 'Inter', sans-serif; background-color: #0f172a !important; margin: 0; padding: 0;">
        <div class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0" style="background-color: #0f172a !important; width: 100%;">
            <!-- Card -->
            <div class="w-full sm:max-w-md px-8 py-10 rounded-2xl bg-[#1e293b]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
