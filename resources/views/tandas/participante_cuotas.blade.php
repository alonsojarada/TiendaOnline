<x-app-layout>
    <x-slot name="header">
        @php
            // Determinamos a dónde debe regresar el botón "Volver"
            $origen = request()->get('origen');
            
            if ($origen === 'cobranza') {
                $rutaVolver = route('tandas.cobranza');
            } elseif ($origen === 'tanda') {
                $rutaVolver = route('tandas.show', $tanda->id);
            } else {
                // Si por alguna razón no viene el parámetro, usamos el historial anterior como respaldo seguro
                $rutaVolver = url()->previous();
            }
        @endphp

        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cuotas de: <span class="text-indigo-600">
                    @if($participante->turno === 0)
                        Turno 0 - Organizador
                    @else
                        Turno {{ $participante->turno }} - {{ optional($participante->cliente)->name ?? 'Sin Asignar' }}
                    @endif
                </span>
            </h2>
           <!-- Botón Volver Dinámico -->
            <a href="{{ $rutaVolver }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-xl shadow-xs transition-all duration-200 group">
                <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">&larr;</span>
                Volver
            </a>
        </div>
    </x-slot>

    @php
        // Cálculo del monto retrasado para este participante específico
        $montoRetrasadoParticipante = 0;
        foreach ($participante->cuotas as $c) {
            if ($c->estado !== 'pagado' && \Carbon\Carbon::parse($c->fecha_limite)->isPast()) {
                $montoRetrasadoParticipante += ($c->monto_esperado - $c->monto_pagado);
            }
        }
    @endphp

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div
                    class="mb-3 bg-green-100 border border-green-400 text-green-700 px-4 py-2.5 rounded relative text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tarjeta de Resumen con títulos más oscuros y legibles -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-2 mb-3 flex flex-wrap items-center justify-between gap-3 text-gray-800">

                <!-- Turno Asignado -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Turno Asignado</span>
                    <span class="text-xl font-bold text-gray-900">
                        @if($participante->turno === 0)
                            Org (0)
                        @else
                            Turno {{ $participante->turno }}
                        @endif
                    </span>
                </div>

                <div class="hidden md:block h-7 w-px bg-gray-200"></div>

                <!-- Cuotas Pagadas -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Cuotas Pagadas</span>
                    <div class="flex items-baseline gap-1">
                        <span
                            class="text-xl font-bold text-indigo-600">{{ $participante->cuotas->where('estado', 'pagado')->count() }}</span>
                        <span class="text-sm font-medium text-gray-500">de {{ $participante->cuotas->count() }}</span>
                    </div>
                </div>

                <div class="hidden md:block h-7 w-px bg-gray-200"></div>

                <!-- Total Abonado -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Total Abonado</span>
                    <span
                        class="text-xl font-bold text-emerald-600">${{ number_format($participante->cuotas->sum('monto_pagado'), 2) }}</span>
                </div>

                <div class="hidden md:block h-7 w-px bg-gray-200"></div>

                <!-- Total Pendiente -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Total Pendiente</span>
                    <span
                        class="text-xl font-bold text-amber-600">${{ number_format($participante->cuotas->sum('monto_esperado') - $participante->cuotas->sum('monto_pagado'), 2) }}</span>
                </div>

                <div class="hidden md:block h-7 w-px bg-gray-200"></div>

                <!-- Monto Retrasado -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Monto Retrasado</span>
                    <span
                        class="text-xl font-bold text-red-600">${{ number_format($montoRetrasadoParticipante, 2) }}</span>
                </div>

                <div class="hidden md:block h-7 w-px bg-gray-200"></div>

                <!-- Entrega Física -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Entrega Física</span>
                    <span class="text-sm font-semibold">
                        @if(isset($participante->entregado) && $participante->entregado)
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                Entregado
                                {{ isset($participante->fecha_entrega) ? '(' . \Carbon\Carbon::parse($participante->fecha_entrega)->format('d/m/Y') . ')' : '' }}
                            </span>
                        @else
                            <span class="text-gray-500 italic font-normal text-sm">Aún no entregado</span>
                        @endif
                    </span>
                </div>

            </div>

            <!-- Tabla de Cuotas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-gray-900">
                <!-- Cabecera de la sección con botones de Exportar Excel y PDF -->
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Historial de Cuotas</h3>
                    <div class="flex items-center space-x-2">
                        <!-- Botón Excel -->
                        <a href="{{ route('tandas.participante.excel', [$tanda->id, $participante->id]) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-lg font-semibold text-xs text-emerald-700 hover:bg-emerald-100 transition shadow-xs">
                            <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </a>
                        <!-- Botón PDF -->
                        <a href="{{ route('tandas.participante.pdf', [$tanda->id, $participante->id]) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-rose-50 border border-rose-200 rounded-lg font-semibold text-xs text-rose-700 hover:bg-rose-100 transition shadow-xs">
                            <svg class="w-4 h-4 mr-1.5 text-rose-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>

                <!-- Contenedor con altura adaptativa a la pantalla -->
                <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-320px)]">
                    <table class="min-w-full divide-y divide-gray-200 relative">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-2.5 bg-gray-50">Ciclo</th>
                                <th class="px-4 py-2.5 bg-gray-50">Fecha Límite</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-right">Monto</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-center">Estatus</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-center">Fecha de Pago</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-center">Acciones / Pagar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @foreach($participante->cuotas->sortBy('ciclo') as $cuota)
                                @php
                                    $esRetrasado = $cuota->estado !== 'pagado' && \Carbon\Carbon::parse($cuota->fecha_limite)->isPast();
                                @endphp
                                <tr class="hover:bg-gray-50/75 transition-colors">
                                    <td class="px-4 py-2.5 whitespace-nowrap font-bold text-gray-700">
                                        Ciclo {{ $cuota->ciclo }}
                                    </td>
                                    <td class="px-4 py-2.5 whitespace-nowrap text-gray-600">
                                        {{ \Carbon\Carbon::parse($cuota->fecha_limite)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-2.5 whitespace-nowrap text-right font-medium text-gray-800">
                                        ${{ number_format($cuota->monto_esperado, 2) }}
                                    </td>
                                    <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                        @if($cuota->estado === 'pagado')
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Pagado
                                            </span>
                                        @elseif($esRetrasado)
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Retrasado
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($cuota->estado) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 whitespace-nowrap text-center text-gray-600 text-xs">
                                        @if($cuota->estado === 'pagado')
                                            {{ \Carbon\Carbon::parse($cuota->updated_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                        @if($cuota->monto_esperado > 0)
                                            @if($cuota->estado === 'pagado')
                                                <!-- Botón para Anular/Eliminar Pago (Estilo Minimalista Rojo) -->
                                                <form action="{{ route('tandas.cuotas.eliminar', $cuota->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este pago? Volverá a estar pendiente.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 rounded-lg text-xs font-bold transition-all shadow-xs">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Anular
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Botón para Pagar Total (Estilo Minimalista Esmeralda/Verde) -->
                                                <form action="{{ route('tandas.cuotas.pagar', $cuota->id) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="monto_pagado" value="{{ $cuota->monto_esperado }}">
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-lg text-xs font-bold transition-all shadow-xs">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Pagar
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400 italic">Hito de Entrega</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>