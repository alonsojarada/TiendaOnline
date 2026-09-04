<x-app-layout>
    <div class="pt-0 pb-6 px-3 sm:px-4 lg:px-6 w-full mx-auto space-y-6">

        <!-- ENCABEZADO Y FILTROS ADAPTATIVOS -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700/80">
            <form method="GET" action="{{ route('reports.ventas') }}"
                class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">

                <!-- Título y subtítulo -->
                <div>
                    <h1
                        class="text-base sm:text-lg font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>📊</span> Reporte General de Ventas
                    </h1>
                </div>

                <!-- Filtros responsivos -->
                <div class="grid grid-cols-1 sm:grid-cols-3 xl:flex xl:items-center gap-2 w-full xl:w-auto">

                    <!-- Selector de Cliente -->
                    <div class="w-full">
                        <select name="client_id"
                            class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer w-full xl:w-44">
                            <option value="">Todos los clientes</option>
                            @foreach($clientes as $client)
                                <option value="{{ $client->id }}" {{ (isset($clientId) && $clientId == $client->id) ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="w-full">
                        <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}"
                            class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full xl:w-36">
                    </div>

                    <!-- Fecha Fin -->
                    <div class="w-full">
                        <input type="date" name="fecha_fin" value="{{ $fechaFin }}"
                            class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full xl:w-36">
                    </div>

                    <!-- Botón Consultar -->
                    <div class="w-full sm:col-span-3 xl:col-span-auto">
                        <button type="submit"
                            class="w-full xl:w-auto inline-flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-xs font-bold rounded-xl shadow-xs transition">
                            🔍 Consultar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- SECCIÓN DE TOTALES Y BOTÓN EXCEL (ALINEADOS) -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">

            <!-- Tarjetas de Totales adaptativas -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full xl:w-auto">
                <!-- Mercancía -->
                <div
                    class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white py-2.5 px-3.5 rounded-xl shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span
                                class="text-[9px] font-bold uppercase tracking-wider text-emerald-100">Mercancía</span>
                            <span
                                class="text-[8px] uppercase font-bold tracking-wider bg-emerald-700/40 px-1.5 py-0.5 rounded">Store
                                Credit</span>
                        </div>
                        <div class="text-base sm:text-lg font-black mt-0.5">${{ number_format($totalMercancia, 2) }}
                        </div>
                    </div>
                    <div class="text-base opacity-75 ml-3">📦</div>
                </div>

                <!-- Efectivo -->
                <div
                    class="bg-gradient-to-br from-indigo-500 to-blue-600 text-white py-2.5 px-3.5 rounded-xl shadow-xs relative overflow-hidden flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-indigo-100">Efectivo</span>
                            <span
                                class="text-[8px] uppercase font-bold tracking-wider bg-indigo-700/40 px-1.5 py-0.5 rounded">Cash
                                Loan</span>
                        </div>
                        <div class="text-base sm:text-lg font-black mt-0.5">${{ number_format($totalPrestamos, 2) }}
                        </div>
                    </div>
                    <div class="text-base opacity-75 ml-3">💵</div>
                </div>

                <!-- Total General -->
                <div
                    class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-2.5 px-3.5 rounded-xl shadow-xs relative overflow-hidden border border-gray-800 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Venta Total</span>
                            <span
                                class="text-[8px] uppercase font-bold tracking-wider bg-gray-800 px-1.5 py-0.5 rounded text-gray-300 border border-gray-700">Acumulado</span>
                        </div>
                        <div class="text-base sm:text-lg font-black mt-0.5">${{ number_format($granTotal, 2) }}</div>
                    </div>
                    <div class="text-base opacity-75 ml-3">📊</div>
                </div>
            </div>
        </div>

        <!-- TABLA UNIFICADA CRONOLÓGICA CON ESTADO DE LIQUIDACIÓN Y SCROLL INTERNO -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">

            
            <div
                class="p-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white dark:bg-gray-800 z-10">
                <div>
                    <h3
                        class="text-xs font-black uppercase tracking-wider text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                        <span>⏱️</span> Historial de ventas y creditos
                    </h3>
                </div>

                <!-- Tu Botón Exportar Excel -->
                <div class="flex items-center justify-end w-full sm:w-auto">
                    <a  href="{{ route('reports.ventas', array_merge(request()->all(), ['export' => 'excel'])) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                        <span>📥</span> Exportar Excel
                    </a>
                </div>
            </div>

            <!-- Contenedor con Scroll Interno -->
            <div class="max-h-[500px] overflow-y-auto overflow-x-auto relative">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-10">
                        <tr
                            class="bg-gray-50 dark:bg-gray-700 text-[11px] font-bold text-gray-500 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 shadow-xs">
                            <th class="py-3 px-4">Fecha</th>
                            <th class="py-3 px-4">Tipo</th>
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-4">Dirección</th>
                            <th class="py-3 px-4">Concepto / Descripción</th>
                            <th class="py-3 px-4 text-center">Estado</th>
                            <th class="py-3 px-4 text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100 dark:divide-gray-700/50">
                        @php
                            $movimientos = $ventasMercancia->concat($prestamosEfectivo)->sortByDesc('created_at');
                        @endphp

                        @forelse($movimientos as $item)
                            @php
                                $clientAddress = optional($item->client)->address;
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                                <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($item->type === 'store_credit')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            📦 Mercancía
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300">
                                            💵 Efectivo
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-bold text-gray-900 dark:text-white">
                                    {{ $item->client->name ?? 'General' }}
                                </td>
                                <!-- Columna de Dirección -->
                                <td class="py-3.5 px-4 text-gray-600 dark:text-gray-400">
                                    {{ $clientAddress ?: 'S/D' }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-700 dark:text-gray-300">
                                    {{ $item->concept ?? ($item->type === 'cash_loan' ? 'Préstamo en efectivo' : 'Venta de mercancía') }}
                                </td>
                                <!-- Columna de Estado / Liquidación -->
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if(isset($item->status) && $item->status === 'paid')
                                        <span
                                            class="px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-950/60 dark:text-green-300 rounded-md">
                                            Liquidado
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 rounded-md">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right font-black text-gray-900 dark:text-white">
                                    ${{ number_format($item->total_amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">
                                    <div class="text-3xl mb-2">📂</div>
                                    <p class="font-medium">No se encontraron movimientos registrados con estos filtros.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>