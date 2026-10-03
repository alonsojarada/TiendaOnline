<x-app-layout>
    <x-slot name="header">
        @php
            $origen = request()->get('origen');
            
            if ($origen === 'cobranza') {
                $rutaVolver = route('tandas.cobranza');
            } elseif ($origen === 'tanda') {
                $rutaVolver = route('tandas.show', $tanda->id);
            } else {
                $rutaVolver = url()->previous();
            }
        @endphp

        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Cuotas de: <span class="text-indigo-600">
                    @if($participante->turno === 0)
                        Turno 0 - Organizador
                    @else
                        Turno {{ $participante->turno }} - {{ optional($participante->cliente)->name ?? 'Sin Asignar' }}
                    @endif
                </span>
            </h2>
            <a href="{{ $rutaVolver }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-xl shadow-xs transition-all duration-200 group">
                <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">&larr;</span>
                Volver
            </a>
        </div>
    </x-slot>

    @php
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
                    class="mb-3 bg-green-100 border border-green-400 text-green-700 px-4 py-2.5 rounded relative text-xs sm:text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tarjeta de Resumen Adaptativa -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 px-3 py-2 sm:px-4 sm:py-3 mb-3 flex flex-wrap items-center justify-between gap-2 sm:gap-3 text-gray-800">

                <!-- Turno Asignado -->
                <div class="flex flex-col">
                    <span class="text-[10px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Turno</span>
                    <span class="text-sm sm:text-xl font-bold text-gray-900">
                        @if($participante->turno === 0)
                            Org (0)
                        @else
                            T. {{ $participante->turno }}
                        @endif
                    </span>
                </div>

                <div class="hidden sm:block h-7 w-px bg-gray-200"></div>

                <!-- Cuotas Pagadas (Oculto en pantallas donde el menú reduce el espacio) -->
                <div class="hidden xl:flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Cuotas Pagadas</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-xl font-bold text-indigo-600">{{ $participante->cuotas->where('estado', 'pagado')->count() }}</span>
                        <span class="text-sm font-medium text-gray-500">de {{ $participante->cuotas->count() }}</span>
                    </div>
                </div>

                <div class="hidden xl:block h-7 w-px bg-gray-200"></div>

                <!-- Total Abonado -->
                <div class="flex flex-col">
                    <span class="text-[10px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Abonado</span>
                    <span class="text-sm sm:text-xl font-bold text-emerald-600">${{ number_format($participante->cuotas->sum('monto_pagado'), 2) }}</span>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Total Pendiente -->
                <div class="hidden lg:flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Total Pendiente</span>
                    <span class="text-xl font-bold text-amber-600">${{ number_format($participante->cuotas->sum('monto_esperado') - $participante->cuotas->sum('monto_pagado'), 2) }}</span>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Monto Retrasado (Oculto cuando el menú lateral está activo y reduce el ancho) -->
                <div class="hidden lg:flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Monto Retrasado</span>
                    <span class="text-xl font-bold text-red-600">${{ number_format($montoRetrasadoParticipante, 2) }}</span>
                </div>

                <div class="hidden sm:block h-7 w-px bg-gray-200"></div>

                <!-- Entrega Física -->
                <div class="flex flex-col">
                    <span class="text-[10px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Entrega Física</span>
                    <span class="text-xs sm:text-sm font-semibold">
                        @if(isset($participante->entregado) && $participante->entregado)
                            <span class="inline-flex items-center px-1.5 py-0.5 sm:px-2 rounded-full text-[10px] sm:text-xs font-semibold bg-blue-100 text-blue-800">
                                Entregado
                                {{ isset($participante->fecha_entrega) ? '(' . \Carbon\Carbon::parse($participante->fecha_entrega)->format('d/m') . ')' : '' }}
                            </span>
                        @else
                            <span class="text-gray-500 italic font-normal text-xs sm:text-sm">Pendiente</span>
                        @endif
                    </span>
                </div>

            </div>

            <!-- Tabla de Cuotas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3 sm:p-4 text-gray-900">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Historial de Cuotas</h3>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('tandas.participante.excel', [$tanda->id, $participante->id]) }}"
                            class="inline-flex items-center px-2.5 py-1 sm:px-3 sm:py-1.5 bg-emerald-50 border border-emerald-200 rounded-lg font-semibold text-[11px] sm:text-xs text-emerald-700 hover:bg-emerald-100 transition shadow-xs">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </a>
                        <a href="{{ route('tandas.participante.pdf', [$tanda->id, $participante->id]) }}"
                            class="inline-flex items-center px-2.5 py-1 sm:px-3 sm:py-1.5 bg-rose-50 border border-rose-200 rounded-lg font-semibold text-[11px] sm:text-xs text-rose-700 hover:bg-rose-100 transition shadow-xs">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 text-rose-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-320px)]">
                    <table class="min-w-full divide-y divide-gray-200 relative text-xs sm:text-sm">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr class="text-left font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-3 py-2 sm:px-4 sm:py-2.5 bg-gray-50">Ciclo</th>
                                <th class="px-3 py-2 sm:px-4 sm:py-2.5 bg-gray-50">Fecha</th>
                                <th class="px-3 py-2 sm:px-4 sm:py-2.5 bg-gray-50 text-right">Monto</th>
                                <th class="hidden lg:table-cell px-3 py-2 sm:px-4 sm:py-2.5 bg-gray-50 text-center">Estatus</th>
                                <!-- Fecha de pago se oculta en cuanto el menú lateral compacta el espacio (lg) -->
                                <th class="hidden lg:table-cell px-3 py-2 sm:px-4 sm:py-2.5 bg-gray-50 text-center">Fecha de Pago</th>
                                <th class="px-3 py-2 sm:px-4 sm:py-2.5 bg-gray-50 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($participante->cuotas->sortBy('ciclo') as $cuota)
                                @php
                                    $esRetrasado = $cuota->estado !== 'pagado' && \Carbon\Carbon::parse($cuota->fecha_limite)->isPast();
                                @endphp
                                <tr class="hover:bg-gray-50/75 transition-colors">
                                    <td class="px-3 py-2 sm:px-4 sm:py-2.5 whitespace-nowrap font-bold text-gray-700">
                                        Ciclo {{ $cuota->ciclo }}
                                    </td>
                                    <td class="px-3 py-2 sm:px-4 sm:py-2.5 whitespace-nowrap text-gray-600 text-[11px] sm:text-sm">
                                        {{ \Carbon\Carbon::parse($cuota->fecha_limite)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-3 py-2 sm:px-4 sm:py-2.5 whitespace-nowrap text-right font-medium text-gray-800">
                                        ${{ number_format($cuota->monto_esperado, 2) }}
                                    </td>
                                    <td class="hidden lg:table-cell px-3 py-2 sm:px-4 sm:py-2.5 whitespace-nowrap text-center">
                                        @if($cuota->estado === 'pagado')
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Pagado
                                            </span>
                                        @elseif($esRetrasado)
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Retrasado
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($cuota->estado) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="hidden lg:table-cell px-3 py-2 sm:px-4 sm:py-2.5 whitespace-nowrap text-center text-gray-600 text-xs">
                                        @if($cuota->estado === 'pagado')
                                            {{ \Carbon\Carbon::parse($cuota->updated_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 sm:px-4 sm:py-2.5 whitespace-nowrap text-center">
                                        @if($cuota->monto_esperado > 0)
                                            @if($cuota->estado === 'pagado')
                                                <form action="{{ route('tandas.cuotas.eliminar', $cuota->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este pago? Volverá a estar pendiente.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 sm:px-3 sm:py-1 bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 rounded-lg text-[11px] sm:text-xs font-bold transition-all shadow-xs">
                                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Anular
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('tandas.cuotas.pagar', $cuota->id) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    <input type="hidden" name="monto_pagado" value="{{ $cuota->monto_esperado }}">
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 sm:px-3 sm:py-1 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-lg text-[11px] sm:text-xs font-bold transition-all shadow-xs">
                                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Pagar
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-[11px] sm:text-xs text-gray-400 italic">Hito</span>
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