<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reporte de Clientes que ya Recibieron Tanda
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Listado de entregas realizadas y montos pagados por cliente</p>
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

                <!-- Tarjeta 1: Clientes Servidos -->
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
                        <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Clientes Entregados
                        </div>
                        <div class="text-xl font-black text-gray-900 leading-none my-1">
                            {{ $busquedaRealizada ? $clientesEntregados->count() : 0 }}
                        </div>
                        <div class="text-xs text-gray-500 font-bold">
                            Total de beneficiarios
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Tandas Entregadas -->
                <div
                    class="relative w-full sm:w-52 bg-emerald-50 border border-emerald-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">
                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-emerald-100 rounded-lg text-emerald-600 flex items-center justify-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Tandas Entregadas
                        </div>
                        <div class="text-xl font-black text-emerald-600 leading-none my-1">
                            {{ $busquedaRealizada ? $clientesEntregados->sum('total_tandas') : 0 }}
                        </div>
                        <div class="text-xs text-emerald-700 font-bold">
                            Total de entregas
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 3: Monto Total Entregado -->
                <div
                    class="relative w-full sm:w-52 bg-indigo-50 border border-indigo-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">
                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-indigo-100 rounded-lg text-indigo-600 flex items-center justify-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Monto Entregado
                        </div>
                        <div class="text-xl font-black text-indigo-700 leading-none my-1">
                            ${{ $busquedaRealizada ? number_format($clientesEntregados->sum('monto_entregado'), 2) : '0.00' }}
                        </div>
                        <div class="text-xs text-indigo-600 font-bold">
                            Suma total pagada
                        </div>
                    </div>
                </div>

            </div>

            <!-- CONTENEDOR DE LA TABLA, BUSCADOR Y BOTONES -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">

                <!-- ACCESOS SUPERIORES (Filtro por fechas y Botones de Exportar) -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-3">

                    <!-- Filtro por Fechas -->
                    <form method="GET" action="{{ route('tandas.reporte.entregados') }}"
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
                        <a href="{{ route('tandas.reporte.entregados', ['filtro' => 'todos']) }}"
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
                        <a href="{{ route('tandas.reporte.entregados.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </a>

                        <!-- Botón PDF -->
                        <a href="{{ route('tandas.reporte.entregados.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
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
                                <th class="px-4 py-2.5 text-center bg-gray-50">Turno / Sorteo</th>
                                <th class="px-4 py-2.5 text-center bg-gray-50">Fecha de Entrega</th>
                                <th class="px-4 py-2.5 text-right bg-gray-50">Monto Entregado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @if(!$busquedaRealizada)
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                                        🔍 Seleccione un rango de fechas o presione el botón de listar todo para mostrar el
                                        reporte de entregas.
                                    </td>
                                </tr>
                            @else
                                @forelse($clientesEntregados as $item)
                                    <tr class="hover:bg-indigo-50/50 transition-colors">
                                        <!-- Cliente -->
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="font-bold text-gray-900 text-sm leading-tight">
                                                {{ $item['cliente_nombre'] }}
                                            </div>
                                            <div class="text-xs text-gray-400 font-normal">Tel: {{ $item['telefono'] }}</div>
                                        </td>
                                        <!-- Origen -->
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="text-xs text-gray-600 font-medium">{{ $item['origen'] ?? 'N/D' }}</div>
                                        </td>
                                        <!-- Tanda -->
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="text-sm text-indigo-600 font-semibold leading-tight">
                                                {{ $item['tanda_nombre'] }}
                                            </div>
                                        </td>
                                        <!-- Turno -->
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                                                Turno {{ $item['turno'] }}
                                            </span>
                                        </td>
                                        <!-- Fecha de Entrega -->
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            <span class="text-xs font-semibold text-gray-600">
                                                {{ \Carbon\Carbon::parse($item['fecha_entrega'])->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <!-- Monto Entregado -->
                                        <td class="px-4 py-2 whitespace-nowrap text-right font-black text-indigo-600 text-base">
                                            ${{ number_format($item['monto_entregado'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-gray-500 text-sm">
                                            📭 No hay registros de tandas entregadas para este periodo.
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