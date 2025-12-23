<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'eVuba Connect') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Turbo (SPA Navigation) -->
        <script type="module">
            import hotwireTurbo from 'https://cdn.skypack.dev/@hotwire/turbo';
        </script>
        <style>
            .turbo-progress-bar {
                height: 3px;
                background-color: var(--primary, #4f46e5);
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 9999;
            }

            /* GLOBAL THEME VARIABLES */
            :root {
                --primary: #4f46e5;
                --body-bg: #f3f4f6;   /* Tailwind gray-100 */
                --surface: #ffffff;
                --header-border: #e5e7eb;
                --text-primary: #1f2937;
                --text-muted: #6b7280;
            }

            [data-theme='dark'] {
                --body-bg: #0f172a;   /* Slate 900 */
                --surface: #1e293b;   /* Slate 800 */
                --header-border: #334155;
                --text-primary: #f8fafc;
                --text-muted: #94a3b8;
            }

            body {
                background-color: var(--body-bg);
                color: var(--text-primary);
                transition: background-color 0.3s ease, color 0.3s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" style="background-color: var(--body-bg);">
            <!-- Defaults Removed per User Request (Using Custom Header/Sidebar) -->

            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </body>
</html>
