<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Clientes que ya Recibieron Tanda
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Listado de entregas realizadas y montos pagados por cliente</p>
            </div>
        </div>
    </x-slot>

    @php
        $busquedaRealizada = !empty($fechaInicio) || !empty($fechaFin) || request('filtro') === 'todos';
    @endphp

    <div class="py-3 sm:py-4">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-3">

            <!-- TARJETAS DE MÉTRICAS (Forzadas a 3 columnas horizontales compactas) -->
            <div class="grid grid-cols-3 gap-2">

                <!-- Tarjeta 1: Clientes Entregados -->
                <div class="bg-white p-2.5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                    <div class="text-[9px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider truncate">
                        Clientes</div>
                    <div class="text-sm sm:text-xl font-black text-gray-900 my-0.5">
                        {{ $busquedaRealizada ? $clientesEntregados->count() : 0 }}
                    </div>
                    <div class="text-[9px] sm:text-[11px] text-gray-400 truncate">Beneficiarios</div>
                </div>

                <!-- Tarjeta 2: Tandas Entregadas -->
                <div
                    class="bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-100 shadow-sm flex flex-col justify-between">
                    <div class="text-[9px] sm:text-xs font-bold text-emerald-700 uppercase tracking-wider truncate">
                        Tandas</div>
                    <div class="text-sm sm:text-xl font-black text-emerald-600 my-0.5">
                        {{ $busquedaRealizada ? $clientesEntregados->sum('total_tandas') : 0 }}
                    </div>
                    <div class="text-[9px] sm:text-[11px] text-emerald-600/80 truncate">Entregadas</div>
                </div>

                <!-- Tarjeta 3: Monto Entregado -->
                <div
                    class="bg-indigo-50/50 p-2.5 rounded-xl border border-indigo-100 shadow-sm flex flex-col justify-between">
                    <div class="text-[9px] sm:text-xs font-bold text-indigo-700 uppercase tracking-wider truncate">Monto
                    </div>
                    <div class="text-xs sm:text-lg font-black text-indigo-600 my-0.5 truncate">
                        ${{ $busquedaRealizada ? number_format($clientesEntregados->sum('monto_entregado'), 2) : '0.00' }}
                    </div>
                    <div class="text-[9px] sm:text-[11px] text-indigo-600/80 truncate">Total pagado</div>
                </div>

            </div>

            <!-- CONTENEDOR PRINCIPAL -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-3 sm:p-4">

                <!-- FILTROS Y BOTONES (Se acomodan en columna en celular y en fila en sm+) -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2 mb-3 pb-2 border-b border-gray-100">

                    <!-- Filtro por Fechas -->
                    <form method="GET" action="{{ route('tandas.reporte.entregados') }}"
                        class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-1.5 w-full sm:w-auto">
                        <div class="col-span-1">
                            <input type="date" name="fecha_inicio" value="{{ $fechaInicio ?? request('fecha_inicio') }}"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">
                        </div>
                        <div class="col-span-1">
                            <input type="date" name="fecha_fin" value="{{ $fechaFin ?? request('fecha_fin') }}"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">
                        </div>
                        <div class="col-span-2 flex items-center gap-1.5 mt-1 sm:mt-0">
                            <button type="submit" title="Filtrar por fechas"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                <svg class="w-4 h-4 mr-1 sm:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="sm:hidden">Filtrar</span>
                            </button>
                            <a href="{{ route('tandas.reporte.entregados', ['filtro' => 'todos']) }}"
                                title="Ver todos sin filtro"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                                <svg class="w-4 h-4 mr-1 sm:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <span class="sm:hidden">Ver todos</span>
                            </a>
                        </div>
                    </form>

                    <!-- Botones de Exportar -->
                    <div class="grid grid-cols-2 sm:flex items-center gap-1.5">
                        <a href="{{ route('tandas.reporte.entregados.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </a>
                        <a href="{{ route('tandas.reporte.entregados.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>

                <!-- TABLA RESPONSIVA -->
                <div class="overflow-y-auto overflow-x-auto relative rounded-xl border border-gray-200"
                    style="max-height: 64vh;">
                    <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                        <thead
                            class="bg-gray-50 text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-2 sm:px-4 py-2.5 text-left bg-gray-50">Cliente</th>
                                <!-- Columna 'Origen' oculta en móviles con 'hidden sm:table-cell' -->
                                <th class="hidden sm:table-cell px-4 py-2.5 text-left bg-gray-50">Origen</th>
                                <th class="px-2 sm:px-4 py-2.5 text-left bg-gray-50">Tanda</th>
                                <th class="px-2 sm:px-4 py-2.5 text-center bg-gray-50">Turno</th>
                                <th class="px-2 sm:px-4 py-2.5 text-center bg-gray-50">Fecha</th>
                                <th class="px-2 sm:px-4 py-2.5 text-right bg-gray-50">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @if(!$busquedaRealizada)
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-xs sm:text-sm">
                                        🔍 Seleccione un rango de fechas o presione listar todo.
                                    </td>
                                </tr>
                            @else
                                @forelse($clientesEntregados as $item)
                                    <tr class="hover:bg-gray-50/75 transition-colors">
                                        <!-- Cliente -->
                                        <td class="px-2 sm:px-4 py-2 whitespace-nowrap">
                                            <div class="font-bold text-gray-900 text-xs sm:text-sm leading-tight">
                                                {{ $item['cliente_nombre'] }}
                                            </div>
                                            <div class="text-[10px] sm:text-xs text-gray-400">Tel: {{ $item['telefono'] }}</div>
                                        </td>
                                        <!-- Origen (Oculto en móvil) -->
                                        <td class="hidden sm:table-cell px-4 py-2 whitespace-nowrap">
                                            <div class="text-xs text-gray-600 font-medium">{{ $item['origen'] ?? 'N/D' }}</div>
                                        </td>
                                        <!-- Tanda -->
                                        <td class="px-2 sm:px-4 py-2 whitespace-nowrap">
                                            <div
                                                class="text-xs sm:text-sm text-indigo-600 font-semibold truncate max-w-[120px] sm:max-w-none">
                                                {{ $item['tanda_nombre'] }}
                                            </div>
                                        </td>
                                        <!-- Turno -->
                                        <td class="px-2 sm:px-4 py-2 whitespace-nowrap text-center">
                                            <span
                                                class="px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded text-[11px] font-medium">
                                                #{{ $item['turno'] }}
                                            </span>
                                        </td>
                                        <!-- Fecha -->
                                        <td class="px-2 sm:px-4 py-2 whitespace-nowrap text-center">
                                            <span class="text-[11px] sm:text-xs font-semibold text-gray-600">
                                                {{ \Carbon\Carbon::parse($item['fecha_entrega'])->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <!-- Monto -->
                                        <td
                                            class="px-2 sm:px-4 py-2 whitespace-nowrap text-right font-bold text-indigo-600 text-xs sm:text-sm">
                                            ${{ number_format($item['monto_entregado'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-10 text-center text-gray-500 text-xs sm:text-sm">
                                            📭 No hay registros para este periodo.
                                        </td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>