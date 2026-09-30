<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Tandas') }}
        </h2>
    </x-slot>

    <div class="py-2 sm:py-4">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

            @php
                // INICIALIZACIÓN DE ACUMULADORES GLOBALES
                $fondoGlobalTotal = 0;
                $totalCobradoGlobal = 0;
                $totalEntregadoGlobal = 0;
                $vencidoGlobalTotal = 0;

                foreach ($tandas as $tanda) {
                    $integrantesSinCero = $tanda->participantes->where('turno', '!=', 0)->count();
                    if ($integrantesSinCero === 0) {
                        $integrantesSinCero = $tanda->participantes->count();
                    }
                    $cuotasPorCiclo = $tanda->cuotas_por_entrega ?? 4;
                    $montoPozoCiclo = $cuotasPorCiclo * $tanda->monto_cuota * $integrantesSinCero;

                    foreach ($tanda->participantes as $p) {
                        $fondoGlobalTotal += $p->cuotas->sum('monto_esperado');
                        $totalCobradoGlobal += $p->cuotas->where('estado', 'pagado')->sum('monto_pagado');

                        foreach ($p->cuotas as $c) {
                            if ($c->estado !== 'pagado' && \Carbon\Carbon::parse($c->fecha_limite)->isPast()) {
                                $vencidoGlobalTotal += ($c->monto_esperado - $c->monto_pagado);
                            }
                        }

                        if ($p->entregado) {
                            if ($p->turno === 0) {
                                $totalEntregadoGlobal += $p->cuotas->sum('monto_esperado');
                            } else {
                                $totalEntregadoGlobal += $montoPozoCiclo;
                            }
                        }
                    }
                }
                $balanceGlobal = $totalCobradoGlobal - $totalEntregadoGlobal;
            @endphp

            <!-- CONTENEDOR DE LA TABLA Y TARJETAS -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-2.5 sm:p-4 text-gray-900">
                
                <!-- TARJETAS GLOBALES -->
                <div class="grid grid-cols-3 lg:grid-cols-5 gap-2 mb-3">
                    
                    <!-- Fondo Global Total -->
                    <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                        <div class="text-[9px] sm:text-[10px] font-bold text-gray-500 uppercase tracking-wider truncate">Fondo Global</div>
                        <div class="text-xs sm:text-base font-bold text-gray-900 my-0.5">${{ number_format($fondoGlobalTotal, 2) }}</div>
                        <div class="hidden sm:block text-[11px] text-gray-400 truncate">Suma total de tandas</div>
                    </div>

                    <!-- Total Cobrado -->
                    <div class="bg-emerald-50/40 p-2.5 sm:p-3 rounded-xl border border-emerald-100 shadow-sm flex flex-col justify-between">
                        <div class="text-[9px] sm:text-[10px] font-bold text-emerald-700 uppercase tracking-wider truncate">Cobrado</div>
                        <div class="text-xs sm:text-base font-bold text-emerald-600 my-0.5">${{ number_format($totalCobradoGlobal, 2) }}</div>
                        <div class="hidden sm:block text-[11px] text-emerald-600/80 truncate">Ingresos reales en caja</div>
                    </div>

                    <!-- Total Entregado -->
                    <div class="bg-blue-50/40 p-2.5 sm:p-3 rounded-xl border border-blue-100 shadow-sm flex flex-col justify-between">
                        <div class="text-[9px] sm:text-[10px] font-bold text-blue-700 uppercase tracking-wider truncate">Entregado</div>
                        <div class="text-xs sm:text-base font-bold text-blue-600 my-0.5">${{ number_format($totalEntregadoGlobal, 2) }}</div>
                        <div class="hidden sm:block text-[11px] text-blue-600/80 truncate">Pozos entregados</div>
                    </div>

                    <!-- CONTENEDOR INFERIOR PARA MÓVIL (Balance y Vencido Global) -->
                    <div class="col-span-3 lg:col-span-2 grid grid-cols-2 gap-2">
                        <!-- Balance / Utilidad -->
                        <div class="bg-indigo-50/40 p-2.5 sm:p-3 rounded-xl border border-indigo-100 shadow-sm flex flex-col justify-between">
                            <div class="text-[9px] sm:text-[10px] font-bold text-indigo-700 uppercase tracking-wider truncate">Balance</div>
                            <div class="text-xs sm:text-base font-bold {{ $balanceGlobal >= 0 ? 'text-indigo-600' : 'text-rose-600' }} my-0.5">
                                ${{ number_format($balanceGlobal, 2) }}
                            </div>
                            <div class="hidden sm:block text-[11px] text-indigo-600/80 truncate">Flujo neto acumulado</div>
                        </div>

                        <!-- Vencido Global -->
                        <div class="bg-rose-50/40 p-2.5 sm:p-3 rounded-xl border border-rose-100 shadow-sm flex flex-col justify-between">
                            <div class="text-[9px] sm:text-[10px] font-bold text-rose-700 uppercase tracking-wider truncate">Vencido Global</div>
                            <div class="text-xs sm:text-base font-bold text-rose-600 my-0.5">${{ number_format($vencidoGlobalTotal, 2) }}</div>
                            <div class="hidden sm:block text-[11px] text-rose-600/80 truncate">Deuda retrasada total</div>
                        </div>
                    </div>
                </div>

                <!-- ENCABEZADO DE TABLA Y BOTÓN RESPONSIVO -->
                <div class="flex flex-row justify-between items-center gap-2 mb-3 bg-gray-50/50 p-2 sm:p-0 rounded-lg">
                    <h3 class="text-xs sm:text-sm font-bold text-gray-700 uppercase tracking-wider truncate">
                        Tandas Registradas
                    </h3>
                    <a href="{{ route('tandas.create') }}"
                        class="inline-flex justify-center items-center px-3 py-1.5 sm:px-3.5 sm:py-1.5 bg-indigo-600 border border-transparent rounded-xl font-semibold text-[11px] sm:text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm whitespace-nowrap">
                        <span class="sm:hidden">+ Nueva</span>
                        <span class="hidden sm:inline">Crear Nueva Tanda</span>
                    </a>
                </div>

                <!-- CONTENEDOR DE TABLA ADAPTATIVO -->
                <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-260px)] rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 relative text-xs sm:text-sm">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr class="text-left font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-3 sm:px-4 py-2.5 bg-gray-50">Nombre</th>
                                <th class="px-3 sm:px-4 py-2.5 bg-gray-50 text-right">Monto Entrega</th>
                                <th class="px-3 sm:px-4 py-2.5 bg-gray-50">Cuota</th>
                                <th class="hidden sm:table-cell px-3 sm:px-4 py-2.5 bg-gray-50">Frecuencia</th>
                                <th class="hidden md:table-cell px-3 sm:px-4 py-2.5 bg-gray-50 text-center">Cuotas atrasadas</th>
                                <th class="px-3 sm:px-4 py-2.5 bg-gray-50 text-center">#Ctes.</th>
                                <th class="hidden md:table-cell px-3 sm:px-4 py-2.5 bg-gray-50 text-center">Duración</th>
                                <th class="px-3 sm:px-4 py-2.5 bg-gray-50 text-center">Cobranza</th>
                                <th class="hidden sm:table-cell px-3 sm:px-4 py-2.5 bg-gray-50 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($tandas as $tanda)
                                @php
                                    $integrantesSinCero = $tanda->participantes->where('turno', '!=', 0)->count();
                                    if ($integrantesSinCero === 0) {
                                        $integrantesSinCero = $tanda->participantes->count();
                                    }

                                    $cuotasPorEntrega = $tanda->cuotas_por_entrega ?? 1;
                                    $totalRecibir = $tanda->monto_cuota * $cuotasPorEntrega * $integrantesSinCero;

                                    $primeraCuotaFecha = $tanda->cuotas->min('fecha_limite');
                                    $ultimaCuotaFecha = $tanda->cuotas->max('fecha_limite');

                                    $cuotasAtrasadasTotal = 0;
                                    $montoRetrasadoTotal = 0;

                                    $todasCuotas = $tanda->participantes->flatMap->cuotas;
                                    $montoEsperadoTotal = $todasCuotas->sum('monto_esperado');
                                    $montoPagadoTotal = $todasCuotas->sum('monto_pagado');
                                    $progresoCobranza = $montoEsperadoTotal > 0 ? min(100, round(($montoPagadoTotal / $montoEsperadoTotal) * 100, 1)) : 0;

                                    foreach ($tanda->participantes as $p) {
                                        foreach ($p->cuotas as $c) {
                                            if ($c->estado !== 'pagado' && \Carbon\Carbon::parse($c->fecha_limite)->isPast()) {
                                                $cuotasAtrasadasTotal++;
                                                $montoRetrasadoTotal += ($c->monto_esperado - $c->monto_pagado);
                                            }
                                        }
                                    }
                                @endphp

                                <tr onclick="window.location.href='{{ route('tandas.show', $tanda->id) }}?origen=index'"
                                    class="hover:bg-gray-50/75 transition-colors cursor-pointer">

                                    <td class="px-3 sm:px-4 py-2.5 whitespace-nowrap">
                                        <span class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                            {{ $tanda->nombre }}
                                        </span>
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 whitespace-nowrap text-right font-bold text-emerald-600">
                                        ${{ number_format($totalRecibir, 2) }}
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 whitespace-nowrap font-medium text-gray-700">
                                        ${{ number_format($tanda->monto_cuota, 2) }}
                                    </td>

                                    <td class="hidden sm:table-cell px-3 sm:px-4 py-2.5 whitespace-nowrap capitalize text-gray-600">
                                        {{ $tanda->frecuencia }}
                                    </td>

                                    <td class="hidden md:table-cell px-3 sm:px-4 py-2.5 whitespace-nowrap text-center">
                                        @if($cuotasAtrasadasTotal > 0)
                                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 text-[11px] sm:text-xs font-bold bg-red-100 text-red-700 rounded-full inline-flex items-center gap-1">
                                                <span>{{ $cuotasAtrasadasTotal }}</span>
                                                <span class="text-red-400 font-normal">/</span>
                                                <span>${{ number_format($montoRetrasadoTotal, 2) }}</span>
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 text-[11px] sm:text-xs font-bold bg-green-50 text-green-700 rounded-full">
                                                0 / $0.00
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 whitespace-nowrap text-center">
                                        <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 text-[11px] sm:text-xs font-bold bg-indigo-50 text-indigo-700 rounded-full">
                                            {{ $integrantesSinCero }}
                                        </span>
                                    </td>

                                    <td class="hidden md:table-cell px-3 sm:px-4 py-2.5 whitespace-nowrap text-center text-[11px] sm:text-xs text-gray-600">
                                        {{ $primeraCuotaFecha ? \Carbon\Carbon::parse($primeraCuotaFecha)->format('d/m/Y') : 'N/A' }}
                                        <span class="text-gray-400 mx-0.5">-</span>
                                        {{ $ultimaCuotaFecha ? \Carbon\Carbon::parse($ultimaCuotaFecha)->format('d/m/Y') : 'N/A' }}
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-1.5 sm:space-x-2">
                                            <div class="w-16 sm:w-24 bg-gray-200 rounded-full h-1.5 sm:h-2 overflow-hidden">
                                                <div class="bg-indigo-600 h-1.5 sm:h-2 rounded-full" style="width: {{ $progresoCobranza }}%"></div>
                                            </div>
                                            <span class="text-[11px] sm:text-xs font-semibold text-gray-700">{{ $progresoCobranza }}%</span>
                                        </div>
                                    </td>

                                    <td class="hidden sm:table-cell px-3 sm:px-4 py-2.5 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-0.5 sm:py-1 inline-flex text-[11px] sm:text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800 capitalize">
                                            {{ $tanda->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                        No hay tandas activas registradas todavía.
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