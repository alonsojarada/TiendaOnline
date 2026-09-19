<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reporte de Clientes con Atrasos
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Listado de morosidad y pagos pendientes por cliente</p>
            </div>
        </div>
    </x-slot>

    @php
        $busquedaRealizada = !empty($fechaInicio) || !empty($fechaFin) || request('filtro') === 'todos';
    @endphp

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- TARJETAS SUPERIORES -->
            <div class="flex flex-wrap items-center gap-3 mb-4">

                <!-- Tarjeta 1: Clientes con Atraso -->
                <div
                    class="relative w-full sm:w-52 bg-white border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">
                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-gray-100 rounded-lg text-gray-600 flex items-center justify-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Clientes con Atraso
                        </div>
                        <div class="text-xl font-black text-gray-900 leading-none my-1">
                            {{ $busquedaRealizada ? $clientesConRetraso->count() : 0 }}
                        </div>
                        <div class="text-xs text-gray-500 font-bold">
                            Total de afectados
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Cuotas Atrasadas -->
                <div
                    class="relative w-full sm:w-52 bg-amber-50 border border-amber-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">
                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-amber-100 rounded-lg text-amber-600 flex items-center justify-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Cuotas Atrasadas
                        </div>
                        <div class="text-xl font-black text-amber-600 leading-none my-1">
                            {{ $busquedaRealizada ? $clientesConRetraso->sum('cuotas_atrasadas') : 0 }}
                        </div>
                        <div class="text-xs text-amber-700 font-bold">
                            Total de pagos vencidos
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3: Monto Total Vencido -->
                <div
                    class="relative w-full sm:w-52 bg-red-50 border border-red-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">
                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-red-100 rounded-lg text-red-600 flex items-center justify-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-red-700">Monto Vencido</div>
                        <div class="text-xl font-black text-red-700 leading-none my-1">
                            ${{ $busquedaRealizada ? number_format($clientesConRetraso->sum('monto_retrasado'), 2) : '0.00' }}
                        </div>
                        <div class="text-xs text-red-600 font-bold">
                            Suma total de deuda
                        </div>
                    </div>
                </div>

            </div>

            <!-- CONTENEDOR DE LA TABLA, BUSCADOR Y BOTONES -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">

                <!-- ACCESOS SUPERIORES (Filtro por fechas y Botones de Exportar) -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-3">

                    <!-- Filtro por Fechas -->
                    <form method="GET" action="{{ route('tandas.reporte.atrasos') }}"
                        class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                        <div>
                            <input type="date" name="fecha_inicio" value="{{ $fechaInicio ?? request('fecha_inicio') }}"
                                class="text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2">
                        </div>
                        <span class="text-gray-300">-</span>
                        <div>
                            <input type="date" name="fecha_fin" value="{{ $fechaFin ?? request('fecha_fin') }}"
                                class="text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2">
                        </div>
                        <button type="submit" title="Filtrar por fechas"
                            class="inline-flex items-center justify-center p-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <a href="{{ route('tandas.reporte.atrasos', ['filtro' => 'todos']) }}"
                            title="Ver todos sin filtro"
                            class="inline-flex items-center justify-center p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </a>
                    </form>

                    <div class="flex items-center gap-2">
                        <!-- Botón Excel -->
                        <a href="{{ route('tandas.reporte.atrasos.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </a>

                        <!-- Botón PDF -->
                        <a href="{{ route('tandas.reporte.atrasos.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>

                <!-- CONTENEDOR CON SCROLL INTERNO Y ALTURA EXACTA (64vh) -->
                <div class="overflow-y-auto overflow-x-auto relative rounded-lg border border-gray-100"
                    style="max-height: 64vh;">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead
                            class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-4 py-2.5 text-left bg-gray-50">Cliente</th>
                                <th class="px-4 py-2.5 text-left bg-gray-50">Origen</th>
                                <th class="px-4 py-2.5 text-left bg-gray-50">Tanda</th>
                                <th class="px-4 py-2.5 text-center bg-gray-50">Turno</th>
                                <th class="px-4 py-2.5 text-center bg-gray-50">Cuotas Vencidas</th>
                                <th class="px-4 py-2.5 text-right bg-gray-50">Monto Vencido</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @if(!$busquedaRealizada)
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                                        🔍 Seleccione un rango de fechas o presione el botón de listar todo para mostrar el
                                        reporte de atrasos.
                                    </td>
                                </tr>
                            @else
                                @forelse($clientesConRetraso as $item)
                                    <tr class="hover:bg-red-50/50 transition-colors">
                                        <!-- Cliente -->
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="font-bold text-gray-900 text-sm leading-tight">
                                                {{ $item['cliente_nombre'] }}</div>
                                            <div class="text-xs text-gray-400 font-normal">Tel: {{ $item['telefono'] }}</div>
                                        </td>
                                        <!-- Origen -->
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="text-xs text-gray-600 font-medium">{{ $item['origen'] ?? 'N/D' }}</div>
                                        </td>
                                        <!-- Tanda -->
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="text-sm text-indigo-600 font-semibold leading-tight">
                                                {{ $item['tanda_nombre'] }}</div>
                                        </td>
                                        <!-- Turno -->
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                                                Turno {{ $item['turno'] }}
                                            </span>
                                        </td>
                                        <!-- Cuotas Vencidas -->
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            <span
                                                class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded-full inline-block">
                                                {{ $item['cuotas_atrasadas'] }}
                                                {{ $item['cuotas_atrasadas'] === 1 ? 'Vencida' : 'Vencidas' }}
                                            </span>
                                        </td>
                                        <!-- Monto Vencido -->
                                        <td class="px-4 py-2 whitespace-nowrap text-right font-black text-red-600 text-base">
                                            ${{ number_format($item['monto_retrasado'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-gray-500 text-sm">
                                            🎉 ¡Excelente noticia! No hay registros de atrasos para este periodo.
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