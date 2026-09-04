<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">

        <!-- 1. Fondo oscuro translúcido (Overlay) -->
        <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-40 invisible opacity-0 transition-opacity duration-300 md:hidden"></div>

        <!-- 2. Sidebar -->
        <div id="side-menu" class="fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            @include('layouts.sidebar')
        </div>

        <!-- 3. Contenido Principal -->
        <div class="flex-1 flex flex-col min-w-0 md:pl-64 transition-all duration-300">
            
            <!-- 4. BARRA SUPERIOR FIJA -->
            <header class="fixed top-0 right-0 left-0 md:left-64 z-30 bg-white dark:bg-gray-800 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    
                    <div class="flex items-center space-x-3 w-full">
                        <button id="menu-btn" class="p-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none md:hidden shrink-0">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M3 6h18M3 18h18"></path>
                            </svg>
                        </button>

                        <div class="flex-1 min-w-0">
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>
                    </div>

                </div>
            </header>

            <!-- 5. Page Content -->
            <main class="flex-1 p-6 pt-20 md:pt-24">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- 6. Script para alternar la apertura/cierre del menú -->
    <script>
        const menuBtn = document.getElementById('menu-btn');
        const sideMenu = document.getElementById('side-menu');
        const menuOverlay = document.getElementById('menu-overlay');

        function toggleMenu() {
            sideMenu.classList.toggle('-translate-x-full');
            menuOverlay.classList.toggle('invisible');
            menuOverlay.classList.toggle('opacity-0');
        }

        menuBtn.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);
    </script>

    <!-- Pila de scripts para inyectar gráficos y componentes específicos -->
    @stack('scripts')
</body>

</html>