<div class="fixed top-0 left-0 h-screen w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col justify-between z-40">

    <div class="px-4 py-6">
        <!-- PARTE SUPERIOR: Bloque de Login / Usuario -->
        <div class="p-6 border-b border-gray-100 bg-gray-50 relative">
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center w-full justify-between text-sm font-semibold text-gray-700 hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                        <span class="truncate">Alonso</span>
                        <svg class="fill-current h-4 w-4 ms-2 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
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

            <!-- Enlace simple: Inicio / Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1/1 0 001 1h3m10-11l2 2m-2-2v10a1/1 0 01-1 1h-3m-6 0a1/1 0 001-1v-4a1/1 0 011-1h2a1/1 0 011 1v4a1/1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <!-- Usuarios (Manejado con Alpine.js) -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Usuarios
                    </div>
                    <!-- Flecha indicadora de cascada -->
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Submenú desplegable -->
                <div x-show="open" x-cloak x-collapse
                    class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                    <a href="{{ route('usuarios.index') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        • Ver Usuarios
                    </a>
                    <a href="{{ route('usuarios.crear') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        • Nueva Usuario
                    </a>
                </div>
            </div>

            <!-- MENÚ EN CASCADA: Categorías (Manejado con Alpine.js) -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Categorías
                    </div>
                    <!-- Flecha indicadora de cascada -->
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Submenú desplegable -->
                <div x-show="open" x-cloak x-collapse
                    class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                    <a href="{{ route('categorias.index') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        • Ver todas
                    </a>
                    <a href="{{ route('categorias.crear') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        • Nueva Categoría
                    </a>
                </div>
            </div>

            <!--  "Productos" -->
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Productos
                    </div>
                    <!-- Flecha indicadora de cascada -->
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 text-gray-500 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Submenú desplegable -->
                <div x-show="open" x-cloak x-collapse
                    class="pl-8 space-y-1 bg-gray-50 dark:bg-gray-950/40 rounded-lg py-1">
                    <a href="{{ route('categorias.index') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        • Ver todas
                    </a>
                    <a href="{{ route('categorias.crear') }}"
                        class="block px-4 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        • Nueva Categoría
                    </a>
                </div>
            </div>


            <!-- Puedes duplicar el bloque de arriba para "Productos" u otros módulos -->

        </nav>
    </div>

    <!-- Pie de la barra lateral (Opcional) -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-400 text-center">
        AJL
    </div>
</div>