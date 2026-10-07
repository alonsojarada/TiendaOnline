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

<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900 h-[100svh] overflow-hidden">

    <!-- 
        CONTENEDOR PRINCIPAL
        h-[100svh] y overflow-hidden fuerzan a que nada rebase la pantalla.
        justify-between reparte el espacio de forma uniforme entre logo, contenido y footer.
    -->
    <div class="h-full w-full flex flex-col justify-between items-center py-2 sm:py-6">

        <!-- =====================================================
            LOGOTIPO (Tamaño ligeramente reducido en móvil para ganar espacio)
        ====================================================== -->
        <div class="shrink-0">
            <a href="{{ url('/') }}"
                class="group relative inline-flex items-center justify-center
                       w-12 h-12 sm:w-20 sm:h-20
                       rounded-2xl sm:rounded-3xl
                       bg-gradient-to-b from-white via-sky-50 to-sky-100/60
                       border border-sky-200/60
                       shadow-lg shadow-sky-500/10
                       hover:border-sky-300 hover:scale-105
                       transition-all duration-300"
                title="Volver a la página principal">

                <svg class="w-7 h-7 sm:w-12 sm:h-12
                            transition-transform group-hover:-translate-y-0.5
                            duration-300 drop-shadow-sm"
                    viewBox="0 0 32 32"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">

                    <!-- Estructura principal del cubo -->
                    <path d="M16 3L27 9.5V22.5L16 29L5 22.5V9.5L16 3Z"
                        fill="url(#ice-soft-grad)"
                        stroke="#0284c7"
                        stroke-width="1.2"
                        stroke-linejoin="round" />

                    <!-- Líneas internas -->
                    <path d="M16 3V16L27 22.5"
                        stroke="#38bdf8"
                        stroke-width="1"
                        stroke-linejoin="round" />

                    <path d="M16 16L5 22.5"
                        stroke="#7dd3fc"
                        stroke-width="1"
                        stroke-linejoin="round" />

                    <!-- Destello -->
                    <path d="M11 9L15 11.5"
                        stroke="white"
                        stroke-width="1.8"
                        stroke-linecap="round" />

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


        <!-- =====================================================
            ZONA CENTRAL
            flex-1 y overflow-hidden aseguran que la tarjeta se comprima 
            o ajuste al espacio sobrante exacto.
        ====================================================== -->
        <main class="w-full flex-1 flex items-center justify-center px-3 sm:px-6 my-1 overflow-hidden">
            <div class="w-full sm:max-w-md
                        bg-white dark:bg-gray-800
                        shadow-md
                        overflow-y-auto max-h-full
                        rounded-2xl sm:rounded-lg">

                {{ $slot }}

            </div>
        </main>


        <!-- =====================================================
            FOOTER (Compacto para asegurar que no empuje fuera)
        ====================================================== -->
        <footer class="w-full shrink-0 text-center px-3 py-1">
            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 leading-tight">
                © 2026 ERP SICOF. Todos los derechos reservados por AJL Solutions.
            </p>
        </footer>

    </div>

</body>

</html>