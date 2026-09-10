<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP SICOF - Sistema de Control Comercial y Financiero</title>
    <!-- Favicon del Cubo de Hielo -->
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg viewBox='0 0 32 32' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M16 3L27 9.5V22.5L16 29L5 22.5V9.5L16 3Z' fill='%23e0f2fe' stroke='%230284c7' stroke-width='1.2' stroke-linejoin='round'/><path d='M16 3V16L27 22.5' stroke='%2338bdf8' stroke-width='1' stroke-linejoin='round'/><path d='M16 16L5 22.5' stroke='%237dd3fc' stroke-width='1' stroke-linejoin='round'/><path d='M11 9L15 11.5' stroke='white' stroke-width='1.8' stroke-linecap='round'/></svg>">
    <!-- Fonts y Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-b from-slate-50 via-white to-blue-50/30 text-slate-800 font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <header class="w-full border-b border-slate-200/60 bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Cubo de Hielo -->
                <a href="{{ url('/') }}"
                    class="group inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-b from-white via-sky-50 to-sky-100/60 border border-sky-200/80 shadow-md shadow-sky-500/10 hover:border-sky-400 transition-all duration-300"
                    title="Volver a la página principal">
                    <svg class="w-6 h-6 transition-transform group-hover:scale-105 duration-300" viewBox="0 0 32 32"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 3L27 9.5V22.5L16 29L5 22.5V9.5L16 3Z" fill="url(#ice-nav-grad)" stroke="#0284c7"
                            stroke-width="1.2" stroke-linejoin="round" />
                        <path d="M16 3V16L27 22.5" stroke="#38bdf8" stroke-width="1" stroke-linejoin="round" />
                        <path d="M16 16L5 22.5" stroke="#7dd3fc" stroke-width="1" stroke-linejoin="round" />
                        <path d="M11 9L15 11.5" stroke="white" stroke-width="1.8" stroke-linecap="round" />
                        <defs>
                            <linearGradient id="ice-nav-grad" x1="5" y1="3" x2="27" y2="29"
                                gradientUnits="userSpaceOnUse">
                                <stop stop-color="#f0f9ff" stop-opacity="0.95" />
                                <stop offset="0.5" stop-color="#bae6fd" stop-opacity="0.7" />
                                <stop offset="1" stop-color="#0284c7" stop-opacity="0.85" />
                            </linearGradient>
                        </defs>
                    </svg>
                </a>
                <span
                    class="font-black text-xl tracking-tight bg-gradient-to-r from-slate-900 to-blue-950 bg-clip-text text-transparent">
                    SICOF
                </span>
            </div>
            <div>
                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 text-sm font-semibold rounded-xl shadow-sm transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Ir al Panel
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 text-sm font-semibold rounded-xl shadow-sm transition-all duration-200">
                                <span class="text-sm">🔐</span>
                                <span>Iniciar Sesión</span>
                            </a>
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Contenido Principal (Hero Section) -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 relative overflow-hidden">

        <!-- Destello de luz ambiental suave -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[350px] bg-blue-100/50 blur-[150px] rounded-full pointer-events-none -z-10">
        </div>

        <div class="max-w-4xl w-full text-center space-y-10">

            <!-- Título Principal y Descripción -->
            <div class="space-y-4">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 leading-[1.12]">
                    Sistema de Control <span
                        class="bg-gradient-to-r from-blue-600 via-sky-600 to-indigo-600 bg-clip-text text-transparent">Comercial
                        y Financiero</span>
                </h1>

                <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-600 font-medium leading-relaxed">
                    Centraliza y automatiza el control de tu negocio con SICOF. Gestiona tus créditos y cuentas, agiliza
                    la cobranza y mantén tus finanzas claras.
                </p>
            </div>

            <!-- Tarjetas de Módulos Operativos -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">

                <!-- Tarjeta 1: Cuentas por Mercancía -->
                <div
                    class="bg-slate-50/80 border border-slate-200/90 p-6 rounded-2xl shadow-lg shadow-slate-200/30 hover:bg-white hover:border-blue-500 hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center gap-4 group cursor-default">
                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-100/70 border border-blue-200 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        🛍️
                    </div>
                    <span
                        class="text-xs font-bold text-slate-700 group-hover:text-blue-600 text-center leading-snug tracking-wide">Cuentas
                        por Mercancía</span>
                </div>

                <!-- Tarjeta 2: Créditos de Efectivo -->
                <div
                    class="bg-slate-50/80 border border-slate-200/90 p-6 rounded-2xl shadow-lg shadow-slate-200/30 hover:bg-white hover:border-emerald-500 hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center gap-4 group cursor-default">
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-100/70 border border-emerald-200 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        💵
                    </div>
                    <span
                        class="text-xs font-bold text-slate-700 group-hover:text-emerald-600 text-center leading-snug tracking-wide">Créditos
                        de Efectivo</span>
                </div>

                <!-- Tarjeta 3: Tandas -->
                <div
                    class="bg-slate-50/80 border border-slate-200/90 p-6 rounded-2xl shadow-lg shadow-slate-200/30 hover:bg-white hover:border-sky-500 hover:shadow-sky-500/10 hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center gap-4 group cursor-default">
                    <div
                        class="w-14 h-14 rounded-2xl bg-sky-100/70 border border-sky-200 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-sky-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        🔄
                    </div>
                    <span
                        class="text-xs font-bold text-slate-700 group-hover:text-sky-600 text-center leading-snug tracking-wide">Tandas</span>
                </div>

                <!-- Tarjeta 4: Control de Cobranza -->
                <div
                    class="bg-slate-50/80 border border-slate-200/90 p-6 rounded-2xl shadow-lg shadow-slate-200/30 hover:bg-white hover:border-indigo-500 hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center gap-4 group cursor-default">
                    <div
                        class="w-14 h-14 rounded-2xl bg-indigo-100/70 border border-indigo-200 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        ⏱️
                    </div>
                    <span
                        class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 text-center leading-snug tracking-wide">Control
                        de Cobranza</span>
                </div>

            </div>

        </div>
    </main>

    <!-- Pie de página -->
    <footer class="py-6 text-center text-xs text-slate-500 border-t border-slate-200/60 bg-white/60">
        <p>© 2026 ERP SICOF. Todos los derechos reservados por AJL Solutions.</p>
    </footer>

</body>

</html>