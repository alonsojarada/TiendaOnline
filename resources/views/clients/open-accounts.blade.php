<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 min-w-0">
                <!-- Icono de Cuentas / Libreta -->
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600 dark:text-indigo-400 shrink-0" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h2 class="font-semibold text-sm sm:text-xl text-gray-800 dark:text-gray-200 leading-tight truncate min-w-0">
                    Cuentas Abiertas
                </h2>
            </div>
            <p class="text-[11px] sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                Vista rápida de clientes que mantienen saldo pendiente en mercancía o préstamos.
            </p>
        </div>
    </x-slot>

    <!-- Contenedor principal con Alpine.js para el filtro -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-3 sm:py-6" x-data="{ search: '' }">
        <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden">

            <!-- Barra superior en una SOLA línea, con ancho controlado para el buscador -->
            <div class="p-2 sm:p-4 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                <div class="flex flex-row items-center justify-between gap-3 min-w-[320px]">
                    
                    <!-- Input de Búsqueda con tamaño limitado en pantallas medianas/grandes (max-w-xs) -->
                    <div class="relative flex-1 sm:flex-initial sm:w-64 max-w-xs min-w-[140px]">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input x-model="search" type="text" placeholder="Filtrar cliente..."
                            class="w-full pl-8 pr-2.5 py-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg text-xs text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <!-- Botones Exportar en línea -->
                    <div class="flex items-center gap-1.5 shrink-0">
                        <!-- Botón Excel -->
                        <a href="{{ route('clients.open-accounts.export.excel') }}"
                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-full text-[11px] font-bold shadow-xs transition whitespace-nowrap">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </a>

                        <!-- Botón PDF -->
                        <a href="{{ route('clients.open-accounts.export.pdf') }}"
                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-full text-[11px] font-bold shadow-xs transition whitespace-nowrap">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenedor con scroll interno -->
            <div id="scrollTablaCuentas" class="max-h-[64vh] overflow-y-auto overflow-x-auto relative rounded-b-2xl">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-20 shadow-xs">
                        <tr class="bg-gray-100 dark:bg-gray-900 text-[9px] sm:text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-gray-200 dark:border-gray-700">
                            <th class="py-2.5 px-2.5 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                                Cliente
                            </th>
                            <th class="py-2.5 px-2.5 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 hidden md:table-cell">
                                Teléfono
                            </th>
                            <th class="py-2.5 px-2 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-right">
                                Mercancía
                            </th>
                            <th class="py-2.5 px-2 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-right">
                                Préstamos
                            </th>
                            <th class="py-2.5 px-2 sm:px-5 bg-gray-100 dark:bg-gray-900 text-right">
                                Adeudo
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($clients as $client)
                            <tr x-show="search === '' || '{{ strtolower($client->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($client->alias) }}'.includes(search.toLowerCase())"
                                onclick="window.location.href='{{ route('clients.show', ['id' => $client->id, 'from' => 'cuentas-abiertas']) }}'"
                                class="hover:bg-gray-50/75 dark:hover:bg-gray-700/50 transition cursor-pointer">
                                <td class="py-2 px-2.5 sm:px-5 border-r border-gray-100 dark:border-gray-700/50">
                                    <div class="font-bold text-gray-900 dark:text-white text-[11px] sm:text-sm truncate max-w-[120px] sm:max-w-none">
                                        {{ $client->name }}
                                    </div>
                                    @if($client->alias)
                                        <div class="text-[9px] sm:text-[10px] text-indigo-500 font-medium hidden sm:block">
                                            "{{ $client->alias }}"</div>
                                    @endif
                                </td>
                                <td class="py-2 px-2.5 sm:px-5 border-r border-gray-100 dark:border-gray-700/50 font-mono text-[11px] sm:text-xs hidden md:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $client->phone ?? 'N/A' }}
                                </td>
                                <td class="py-2 px-2 sm:px-5 border-r border-gray-100 dark:border-gray-700/50 text-right font-semibold text-indigo-600 dark:text-indigo-400 text-[11px] sm:text-sm whitespace-nowrap">
                                    ${{ number_format($client->montoMercanciaRestante, 0) }}
                                </td>
                                <td class="py-2 px-2 sm:px-5 border-r border-gray-100 dark:border-gray-700/50 text-right font-semibold text-emerald-600 dark:text-emerald-400 text-[11px] sm:text-sm whitespace-nowrap">
                                    ${{ number_format($client->montoPrestamoRestante, 0) }}
                                </td>
                                <td class="py-2 px-2 sm:px-5 text-right font-black text-rose-600 dark:text-rose-400 text-[11px] sm:text-sm whitespace-nowrap">
                                    ${{ number_format($client->adeudoGlobal, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500 dark:text-gray-400 text-xs sm:text-sm">
                                    No hay clientes con cuentas abiertas o saldos pendientes en este momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    <!-- Totales Fijos (Sticky Footer) -->
                    <tfoot class="sticky bottom-0 z-20 bg-gray-50 dark:bg-gray-900 font-bold text-xs sm:text-sm border-t-2 border-gray-200 dark:border-gray-700 shadow-inner">
                        <tr>
                            <td class="py-2.5 px-3 sm:px-5 text-gray-900 dark:text-gray-100">
                                Total ({{ $clients->count() }})
                            </td>
                            <td class="py-2.5 px-3 sm:px-5 hidden md:table-cell"></td>
                            <td class="py-2.5 px-3 sm:px-5 text-right text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                ${{ number_format($clients->sum('montoMercanciaRestante'), 0) }}
                            </td>
                            <td class="py-2.5 px-3 sm:px-5 text-right text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                ${{ number_format($clients->sum('montoPrestamoRestante'), 0) }}
                            </td>
                            <td class="py-2.5 px-3 sm:px-5 text-right text-rose-600 dark:text-rose-400 whitespace-nowrap">
                                ${{ number_format($clients->sum('adeudoGlobal'), 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Estilos personalizados para la barra de scroll -->
    <style>
        #scrollTablaCuentas::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        #scrollTablaCuentas::-webkit-scrollbar-thumb {
            background: rgba(107, 114, 128, 0.4);
            border-radius: 10px;
        }
        #scrollTablaCuentas::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</x-app-layout>