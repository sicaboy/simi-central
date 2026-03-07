<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', config('shared-saas.central.name')) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Favicons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
        <link rel="icon" type="image/png" sizes="70x70" href="/favicon-70.png">
        <link rel="icon" type="image/png" sizes="128x128" href="/favicon-128.png">
        <link rel="icon" type="image/png" sizes="144x144" href="/favicon-144.png">
        <link rel="icon" type="image/png" sizes="152x152" href="/favicon-152.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png">
        <link rel="icon" type="image/png" sizes="196x196" href="/favicon-196.png">
        <link rel="icon" type="image/png" sizes="228x228" href="/favicon-228.png">

        <!-- Theme Initialization Script -->
        <script>
            // Initialize theme from localStorage
            const theme = localStorage.getItem('theme') || 'system';
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Apply dark class if:
            // 1. Theme is explicitly set to 'dark', or
            // 2. Theme is 'system' and system preference is dark
            if (theme === 'dark' || (theme === 'system' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Listen for system preference changes
            if (theme === 'system') {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                });
            }
        </script>

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/manifest.js') }}" defer></script>
        <script src="{{ mix('js/vendor.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 dark:text-gray-100 min-h-screen">
        @inertia
    </body>
</html>
