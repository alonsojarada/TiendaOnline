<aside
    class="w-64 h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col justify-between">

    <div class="px-4 py-6 overflow-y-auto">
        <!-- PARTE SUPERIOR: Bloque de Empresa y Usuario Mejorado -->
        <div class="mb-6">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button
                        class="w-full text-left p-3.5 bg-gradient-to-r from-blue-50 to-white dark:from-gray-800 dark:to-gray-900 border border-blue-100 dark:border-gray-700 rounded-xl shadow-sm hover:shadow transition-all duration-150 focus:outline-none group">

                        <div class="flex items-center justify-between">
                            <div class="truncate pr-2">
                                <!-- Nombre de la Empresa -->
                                <span
                                    class="block text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 truncate">
                                    {{ auth()->user()->company->name ?? 'Sin Empresa' }}
                                </span>
                                <!-- Usuario actual -->
                                <span
                                    class="block text-sm font-semibold text-gray-800 dark:text-gray-200 truncate mt-0.5">
                                    {{ auth()->user()->name }}
                                </span>
                            </div>

                            <!-- Flechita del Dropdown -->
                            <svg class="fill-current h-4 w-4 text-gray-400 group-hover:text-blue-600 transition-colors flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Perfil') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Cerrar Sesión') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <nav class="space-y-2">

            <!-- Enlace simple: Panel Cobranza -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Panel Cobranza
            </a>

            <!-- MÓDULO ADMINISTRATIVO -->
            @if(auth()->user()->company_id === null)
                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold uppercase tracking-wider text-gray-400">Administración</p>
                </div>

                <!-- Usuarios -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Usuarios
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                        <a href="{{ route('usuarios.index') }}"
                            class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            • Ver Usuarios
                        </a>
                        <a href="{{ route('usuarios.crear') }}"
                            class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            • Nuevo Usuario
                        </a>
                    </div>
                </div>

                <!-- Empresas -->
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Empresas
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                        <a href="{{ route('companies.index') }}"
                            class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            • Ver todas
                        </a>
                        <a href="{{ route('companies.create') }}"
                            class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            • Nueva Empresa
                        </a>
                    </div>
                </div>

                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold uppercase tracking-wider text-gray-400">Operación Tienda</p>
                </div>
            @endif

            <!-- Clientes -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Clientes
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-cloak class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                    <a href="{{ route('clients.index') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        • Ver Clientes
                    </a>
                </div>
            </div>

            <!-- Flujo de Caja -->
            <a href="{{ route('reports.reporte-mensual') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Flujo de Caja
            </a>

            <!-- Reportes -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Reportes
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-cloak class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                    <a href="{{ route('reports.historial-cuentas') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        • Historial Cuentas
                    </a>
                </div>
                <div x-show="open" x-cloak class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                    <a href="{{ route('reports.ventas') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        • Reporte de Ventas
                    </a>
                </div>
                <div x-show="open" x-cloak class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                    <a href="{{ route('reports.abonos') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        • Reporte de abonos
                    </a>
                </div>

            </div>


        </nav>
    </div>

    <!-- Pie de la barra lateral -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-400 text-center">
        <span class="text-xs font-bold tracking-wider text-slate-500">AJL Solutions</span>
    </div>
</aside>