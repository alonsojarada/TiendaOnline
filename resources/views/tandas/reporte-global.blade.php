<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reporte Global de Tandas
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Resumen financiero consolidado de todas las tandas activas e
                    históricas</p>
            </div>

        </div>
    </x-slot>

    <!-- Margen superior reducido -->
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-3">

            <!-- TARJETAS DE MÉTRICAS GLOBALES (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Fondo Global Total -->
                <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-4 flex flex-col justify-between">
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-500">Fondo Global Total</div>
                    <div class="text-xl font-black text-gray-900 my-1">${{ number_format($totalFondoGlobal, 2) }}</div>
                    <div class="text-xs text-gray-400">Suma total de todas las tandas</div>
                </div>

                <!-- Total Recaudado (Ingresos) -->
                <div
                    class="bg-emerald-50 border border-emerald-200 shadow-sm rounded-xl p-4 flex flex-col justify-between">
                    <div class="text-xs font-bold uppercase tracking-wider text-emerald-700">Total Cobrado</div>
                    <div class="text-xl font-black text-emerald-700 my-1">${{ number_format($totalRecaudadoGlobal, 2) }}
                    </div>
                    <div class="text-xs text-emerald-600 font-medium">Ingresos reales en caja</div>
                </div>

                <!-- Total Entregado (Salidas) -->
                <div class="bg-blue-50 border border-blue-200 shadow-sm rounded-xl p-4 flex flex-col justify-between">
                    <div class="text-xs font-bold uppercase tracking-wider text-blue-700">Total Entregado</div>
                    <div class="text-xl font-black text-blue-700 my-1">${{ number_format($totalEntregadoGlobal, 2) }}
                    </div>
                    <div class="text-xs text-blue-600 font-medium">Pozos entregados a participantes</div>
                </div>

                <!-- Balance Neto -->
                <div
                    class="bg-indigo-50 border border-indigo-200 shadow-sm rounded-xl p-4 flex flex-col justify-between">
                    <div class="text-xs font-bold uppercase tracking-wider text-indigo-700">Balance / Utilidad</div>
                    <div class="text-xl font-black text-indigo-700 my-1">${{ number_format($balanceNeto, 2) }}</div>
                    <div class="text-xs text-indigo-600 font-medium">Flujo neto acumulado</div>
                </div>

                <!-- Cartera Vencida Global -->
                <div class="bg-red-50 border border-red-200 shadow-sm rounded-xl p-4 flex flex-col justify-between">
                    <div class="text-xs font-bold uppercase tracking-wider text-red-700">Vencido Global</div>
                    <div class="text-xl font-black text-red-700 my-1">${{ number_format($totalVencidoGlobal, 2) }}</div>
                    <div class="text-xs text-red-600 font-medium">Deuda retrasada total</div>
                </div>
            </div>

            <!-- TABLA Y CONTROLES -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-4">

                <!-- Filtro de Fechas y Botones de Exportación Compactos -->
                <div
                    class="flex flex-col lg:flex-row justify-between items-center gap-2 mb-3 pb-2 border-b border-gray-100">

                    <form method="GET" action="{{ route('tandas.reporte.global') }}"
                        class="flex flex-wrap items-center gap-2 w-full justify-between">
                        <!-- Filtros de Fecha y Botones de Acción -->
                        <div class="flex flex-wrap items-center gap-2">
                            <div>
                                <input type="date" name="fecha_inicio" id="fecha_inicio"
                                    value="{{ request('fecha_inicio') }}" required
                                    class="text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">
                            </div>

                            <div>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}"
                                    required
                                    class="text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">
                            </div>

                            <!-- Botón de Búsqueda (Icono de Lupa) -->
                            <button type="submit" title="Filtrar por fechas"
                                class="inline-flex items-center justify-center p-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>

                            <a href="{{ route('tandas.reporte.global', ['todo' => 1]) }}" title="Ver todo (sin fechas)"
                                class="inline-flex items-center justify-center p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </a>
                        </div>

                        <!-- Botones de Exportación con el nuevo estilo -->
                        <div class="flex items-center gap-2">
                            <!-- Botón Excel -->
                            <a href="{{ route('tandas.reporte.global.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold shadow-xs transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Excel
                            </a>

                            <!-- Botón PDF -->
                            <a href="{{ route('tandas.reporte.global.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold shadow-xs transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF
                            </a>
                        </div>
                    </form>
                </div>

                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Nombre de la Tanda</th>
                                <th class="px-4 py-3 text-center">Estado</th>
                                <th class="px-4 py-3 text-center">Participantes</th>
                                <th class="px-4 py-3 text-center">Inicio</th>
                                <th class="px-4 py-3 text-center">Término</th>
                                <th class="px-4 py-3 text-right">Fondo Total</th>
                                <th class="px-4 py-3 text-right">Cobrado</th>
                                <th class="px-4 py-3 text-right">Entregado</th>
                                <th class="px-4 py-3 text-right">Utilidad</th>
                                <th class="px-4 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($tandas as $tanda)
                                @php
                                    $participantesNormales = $tanda->participantes->filter(function ($p) {
                                        return $p->turno !== 0;
                                    });
                                    $numIntegrantes = $participantesNormales->count();
                                    $totalConCero = $tanda->participantes->count();

                                    $fondoTanda = $tanda->participantes->sum(function ($p) {
                                        return $p->cuotas->sum('monto_esperado');
                                    });
                                    $cobradoTanda = $tanda->participantes->sum(function ($p) {
                                        return $p->cuotas->where('estado', 'pagado')->sum('monto_pagado');
                                    });

                                    $cuotasPorCiclo = 4;
                                    $montoPozoCiclo = $cuotasPorCiclo * $tanda->monto_cuota * $numIntegrantes;

                                    $entregadoTanda = 0;
                                    foreach ($tanda->participantes as $p) {
                                        if ($p->entregado) {
                                            if ($p->turno === 0) {
                                                $entregadoTanda += $p->cuotas->sum('monto_esperado');
                                            } else {
                                                $entregadoTanda += $montoPozoCiclo;
                                            }
                                        }
                                    }

                                    $utilidadTanda = $cobradoTanda - $entregadoTanda;
                                    $estadoTanda = $tanda->estado ?? 'Activa';

                                    $todasLasCuotas = $tanda->participantes->flatMap->cuotas->sortBy('fecha_limite');

                                    $fechaInicio = $todasLasCuotas->isNotEmpty() ? \Carbon\Carbon::parse($todasLasCuotas->first()->fecha_limite)->format('d/m/Y') : '-';
                                    $fechaTermino = $todasLasCuotas->isNotEmpty() ? \Carbon\Carbon::parse($todasLasCuotas->last()->fecha_limite)->format('d/m/Y') : '-';
                                @endphp
                                <tr class="hover:bg-gray-50/75 transition-colors">
                                    <td class="px-4 py-2.5 font-bold text-gray-900">
                                        {{ $tanda->nombre }}
                                        <div class="text-xs font-normal text-gray-400">Cuota:
                                            ${{ number_format($tanda->monto_cuota, 2) }} ({{ ucfirst($tanda->frecuencia) }})
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <span
                                            class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ strtolower($estadoTanda) == 'activa' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($estadoTanda) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 text-center font-medium text-gray-700">
                                        {{ $numIntegrantes }} <span class="text-xs text-gray-400">({{ $totalConCero }} con
                                            0)</span>
                                    </td>
                                    <td class="px-4 py-2.5 text-center text-xs text-gray-600 font-medium">
                                        {{ $fechaInicio }}
                                    </td>
                                    <td class="px-4 py-2.5 text-center text-xs text-gray-600 font-medium">
                                        {{ $fechaTermino }}
                                    </td>
                                    <td class="px-4 py-2.5 text-right font-semibold text-gray-900">
                                        ${{ number_format($fondoTanda, 2) }}
                                    </td>
                                    <td class="px-4 py-2.5 text-right font-medium text-emerald-600">
                                        ${{ number_format($cobradoTanda, 2) }}
                                    </td>
                                    <td class="px-4 py-2.5 text-right font-medium text-blue-600">
                                        ${{ number_format($entregadoTanda, 2) }}
                                    </td>
                                    <td
                                        class="px-4 py-2.5 text-right font-bold {{ $utilidadTanda >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">
                                        ${{ number_format($utilidadTanda, 2) }}
                                    </td>
                                    <td class="px-4 py-2.5 text-center">
                                        <a href="{{ route('tandas.show', $tanda->id) }}?origen=reporte"
                                            class="inline-flex items-center px-2.5 py-1 bg-gray-50 hover:bg-indigo-600 text-gray-700 hover:text-white border border-gray-200 hover:border-indigo-600 rounded-lg text-xs font-bold transition">
                                            Ver Tanda
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-6 text-center text-gray-400 text-xs">
                                        No hay tandas registradas en el sistema para este periodo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>