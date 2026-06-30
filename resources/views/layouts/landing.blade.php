<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Perks - ბენეფიტები თანამშრომლებისთვის')</title>
    <meta name="description" content="@yield('meta_description', 'აღმოაჩინეთ პლატფორმა, რომელიც ქმნის დაზოგვის შესაძლებლობას თანამშრომლებისთვის და უნიკალურ ბენეფიტს კომპანიისთვის')">

    @include('partials.favicon')

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @stack('head')
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-300 font-sans antialiased">
    @yield('content')

    @include('layouts.scripts')
</body>
</html>
