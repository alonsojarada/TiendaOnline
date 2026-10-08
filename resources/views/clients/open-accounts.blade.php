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
                <!-- Título con truncate para abreviar con puntos si no cabe en la pantalla -->
                <h2
                    class="font-semibold text-sm sm:text-xl text-gray-800 dark:text-gray-200 leading-tight truncate min-w-0">
                    Cuentas Abiertas
                </h2>
            </div>
            <p class="text-[11px] sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                Vista rápida de clientes que mantienen saldo pendiente en mercancía o préstamos.
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-3 sm:py-6">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden">

            <!-- Barra superior en una sola fila -->
            <div
                class="p-2.5 sm:p-5 border-b border-gray-200 dark:border-gray-700 flex flex-row items-center justify-between gap-2">
                <span class="text-[11px] sm:text-sm font-bold text-gray-700 dark:text-gray-300 truncate">
                    Activas: {{ $clients->count() }}
                </span>

                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <!-- Botón Exportar Excel (Estilo Píldora Suave) -->
                    <a href="{{ route('clients.open-accounts.export.excel') }}"
                        class="inline-flex items-center justify-center gap-1 px-3 py-1 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-full text-[11px] sm:text-xs font-bold shadow-xs transition whitespace-nowrap">
                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Excel
                    </a>

                    <!-- Botón Exportar PDF (Estilo Píldora Suave) -->
                    <a href="{{ route('clients.open-accounts.export.pdf') }}"
                        class="inline-flex items-center justify-center gap-1 px-3 py-1 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-full text-[11px] sm:text-xs font-bold shadow-xs transition whitespace-nowrap">
                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        PDF
                    </a>
                </div>
            </div>

            <!-- Contenedor con scroll interno propio de la tabla -->
            <div id="scrollTablaCuentas" class="max-h-[64vh] overflow-y-auto overflow-x-auto relative rounded-b-2xl">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-20 shadow-xs">
                        <tr
                            class="bg-gray-100 dark:bg-gray-900 text-[9px] sm:text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-gray-200 dark:border-gray-700">
                            <th
                                class="py-2.5 px-2.5 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                                Cliente</th>
                            <th
                                class="py-2.5 px-2.5 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 hidden md:table-cell">
                                Teléfono</th>
                            <th
                                class="py-2.5 px-2 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-right">
                                Mercancía</th>
                            <th
                                class="py-2.5 px-2 sm:px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 text-right">
                                Préstamos</th>
                            <th class="py-2.5 px-2 sm:px-5 bg-gray-100 dark:bg-gray-900 text-right">
                                Adeudo Global</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($clients as $client)
                            <tr onclick="window.location.href='{{ route('clients.show', ['id' => $client->id, 'from' => 'cuentas-abiertas']) }}'"
                                class="hover:bg-gray-50/75 dark:hover:bg-gray-700/50 transition cursor-pointer">
                                <td class="py-2 px-2.5 sm:px-5 border-r border-gray-100 dark:border-gray-700/50">
                                    <div class="font-bold text-gray-900 dark:text-white text-[11px] sm:text-sm truncate">
                                        {{ $client->name }}
                                    </div>
                                    @if($client->alias)
                                        <div class="text-[9px] sm:text-[10px] text-indigo-500 font-medium hidden sm:block">
                                            "{{ $client->alias }}"</div>
                                    @endif
                                </td>
                                <td
                                    class="py-2 px-2.5 sm:px-5 border-r border-gray-100 dark:border-gray-700/50 font-mono text-[11px] sm:text-xs hidden md:table-cell text-gray-600 dark:text-gray-400">
                                    {{ $client->phone ?? 'N/A' }}
                                </td>
                                <td
                                    class="py-2 px-2 sm:px-5 border-r border-gray-100 dark:border-gray-700/50 text-right font-semibold text-indigo-600 dark:text-indigo-400 text-[11px] sm:text-sm whitespace-nowrap">
                                    ${{ number_format($client->montoMercanciaRestante, 2) }}
                                </td>
                                <td
                                    class="py-2 px-2 sm:px-5 border-r border-gray-100 dark:border-gray-700/50 text-right font-semibold text-emerald-600 dark:text-emerald-400 text-[11px] sm:text-sm whitespace-nowrap">
                                    ${{ number_format($client->montoPrestamoRestante, 2) }}
                                </td>
                                <td
                                    class="py-2 px-2 sm:px-5 text-right font-black text-rose-600 dark:text-rose-400 text-[11px] sm:text-sm whitespace-nowrap">
                                    ${{ number_format($client->adeudoGlobal, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="text-center py-10 text-gray-500 dark:text-gray-400 text-xs sm:text-sm">
                                    No hay clientes con cuentas abiertas o saldos pendientes en este momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!-- Dentro de tu tabla, debajo del </tbody> -->
                    <tfoot
                        class="bg-gray-50 dark:bg-gray-900 font-bold text-xs sm:text-sm border-t-2 border-gray-200 dark:border-gray-700">
                        <tr>
                            <td class="py-2.5 px-3 sm:px-5 text-gray-900 dark:text-gray-100">
                                Total General ({{ $clients->count() }})
                            </td>
                            <td class="py-2.5 px-3 sm:px-5 hidden md:table-cell"></td>
                            <td
                                class="py-2.5 px-3 sm:px-5 text-right text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                ${{ number_format($clients->sum('montoMercanciaRestante'), 2) }}
                            </td>
                            <td
                                class="py-2.5 px-3 sm:px-5 text-right text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                ${{ number_format($clients->sum('montoPrestamoRestante'), 2) }}
                            </td>
                            <td
                                class="py-2.5 px-3 sm:px-5 text-right text-rose-600 dark:text-rose-400 whitespace-nowrap">
                                ${{ number_format($clients->sum('adeudoGlobal'), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Estilos para personalizar la barra de scroll de la tabla -->
    <style>
        #scrollTablaCuentas::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        #scrollTablaCuentas::-webkit-scrollbar-thumb {
            background: rgba(107, 114, 128, 0.45);
            border-radius: 10px;
        }

        #scrollTablaCuentas::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</x-app-layout>