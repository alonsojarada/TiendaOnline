<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SICOF</title>

    <!-- Favicon del Cubo de Hielo -->
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg viewBox='0 0 32 32' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M16 3L27 9.5V22.5L16 29L5 22.5V9.5L16 3Z' fill='%23e0f2fe' stroke='%230284c7' stroke-width='1.2' stroke-linejoin='round'/><path d='M16 3V16L27 22.5' stroke='%2338bdf8' stroke-width='1' stroke-linejoin='round'/><path d='M16 16L5 22.5' stroke='%237dd3fc' stroke-width='1' stroke-linejoin='round'/><path d='M11 9L15 11.5' stroke='white' stroke-width='1.8' stroke-linecap='round'/></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">

        <!-- Cubo de Hielo Estilo Neumórfico / Minimalista con efecto flotante -->
        <div class="mb-6">
            <a href="{{ url('/') }}"
                class="group relative inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-b from-white via-sky-50 to-sky-100/60 border border-sky-200/60 shadow-xl shadow-sky-500/10 hover:border-sky-300 hover:scale-105 transition-all duration-300"
                title="Volver a la página principal">
                <svg class="w-12 h-12 transition-transform group-hover:-translate-y-0.5 duration-300 drop-shadow-sm"
                    viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Estructura principal de cubo con esquinas redondeadas suaves -->
                    <path d="M16 3L27 9.5V22.5L16 29L5 22.5V9.5L16 3Z" fill="url(#ice-soft-grad)" stroke="#0284c7"
                        stroke-width="1.2" stroke-linejoin="round" />
                    <!-- Líneas internas de profundidad -->
                    <path d="M16 3V16L27 22.5" stroke="#38bdf8" stroke-width="1" stroke-linejoin="round" />
                    <path d="M16 16L5 22.5" stroke="#7dd3fc" stroke-width="1" stroke-linejoin="round" />
                    <!-- Destello superior elegante -->
                    <path d="M11 9L15 11.5" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                    <defs>
                        <linearGradient id="ice-soft-grad" x1="5" y1="3" x2="27" y2="29" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#f0f9ff" stop-opacity="0.95" />
                            <stop offset="0.5" stop-color="#bae6fd" stop-opacity="0.7" />
                            <stop offset="1" stop-color="#0284c7" stop-opacity="0.85" />
                        </linearGradient>
                    </defs>
                </svg>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-2 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>