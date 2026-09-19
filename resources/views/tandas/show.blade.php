<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de Tanda: {{ $tanda->nombre }}
            </h2>
            <a href="{{ 
                match($origen ?? 'index') {
                    'reporte' => route('tandas.reporte.global'),
                    default => route('tandas.index')
                } }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-xl shadow-xs transition-all duration-200 group">
                <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">&larr;</span>
                Volver
            </a>
        </div>
    </x-slot>

    @php
// Cálculo global de retrasos y monto total retrasado para la tarjeta superior
$montoRetrasadoGlobal = 0;
foreach ($tanda->participantes as $p) {
    foreach ($p->cuotas as $c) {
        if ($c->estado !== 'pagado' && \Carbon\Carbon::parse($c->fecha_limite)->isPast()) {
            $montoRetrasadoGlobal += ($c->monto_esperado - $c->monto_pagado);
        }
    }
}
    @endphp

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tarjeta de Resumen / Panel de Métricas Unificado -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-2 mb-3 flex flex-wrap items-center justify-between gap-3 text-gray-800">

                <!-- Cuota / Frec. -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Cuota / Frec.</span>
                    <div class="flex items-baseline space-x-1.5">
                        <span
                            class="text-xl font-bold text-gray-900">${{ number_format($tanda->monto_cuota, 2) }}</span>
                        <span class="text-xs font-semibold text-indigo-600 capitalize">({{ $tanda->frecuencia }})</span>
                    </div>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Participantes -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Participantes</span>
                    <span class="text-xl font-bold text-gray-900">
                        @php
$integrantesSinCero = $tanda->participantes->where('turno', '!=', 0)->count();
                        @endphp
                        {{ $integrantesSinCero }}
                    </span>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Modalidad -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Modalidad</span>
                    <span class="text-xl font-bold text-gray-900">
                        @php
$tieneOrganizadorEnCero = $tanda->participantes->contains('turno', 0) || ($tanda->incluye_organizador ?? false);
                        @endphp
                        {{ $tieneOrganizadorEnCero ? 'Con Cero' : 'Normal' }}
                    </span>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Entregados -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Entregados</span>
                    <div class="flex items-baseline gap-1">
                        <span
                            class="text-xl font-bold text-indigo-600">{{ $tanda->participantes->where('entregado', true)->count() }}</span>
                        <span class="text-sm font-medium text-gray-500">de
                            {{ $tanda->numero_participantes ?? $tanda->participantes->count() }}</span>
                    </div>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Monto Retrasado -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Monto Retrasado</span>
                    <span class="text-xl font-bold text-red-600">
                        ${{ number_format($montoRetrasadoGlobal, 2) }}
                    </span>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Inicio -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Inicio</span>
                    <span class="text-sm font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($tanda->fecha_inicio)->format('d/m/Y') }}
                    </span>
                </div>

                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>

                <!-- Término -->
                <div class="flex flex-col">
                    <span class="text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">Término</span>
                    <span class="text-sm font-semibold text-gray-800">
                        @php
$ultimaCuota = $tanda->participantes->flatMap->cuotas->max('fecha_limite');
                        @endphp
                        {{ $ultimaCuota ? \Carbon\Carbon::parse($ultimaCuota)->format('d/m/Y') : 'N/A' }}
                    </span>
                </div>

            </div>

            <!-- Lista de Participantes con control Alpine.js para Edición -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 text-gray-900"
                x-data="{ modoEdicion: false }">

                <!-- Cabecera de la sección con el Botón Eliminar y el Checkbox Editar alineados a la derecha -->
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Calendario de Entregas</h3>

                    <div class="flex items-center gap-3">
                        <!-- Botón de Eliminar Tanda Completa (Visible solo al activar modo edición) -->
                        <form action="{{ route('tandas.destroy', $tanda->id) }}" method="POST"
                            onsubmit="return confirm('¿Estás seguro de eliminar esta tanda? Se borrarán todos sus participantes y cuotas asociadas.');"
                            x-show="modoEdicion" x-cloak class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold hover:bg-red-700 transition flex items-center gap-1.5 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar Tanda
                            </button>
                        </form>

                        <!-- Checkbox de Editar -->
                        <label
                            class="inline-flex items-center cursor-pointer bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                            <input type="checkbox" x-model="modoEdicion"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                            <span
                                class="ml-2 text-xs font-semibold text-gray-700 uppercase tracking-wider">Editar</span>
                        </label>
                    </div>
                </div>

                <!-- Contenedor con altura máxima adaptada a la pantalla (max-h-[calc(100vh-330px)]) -->
                <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-330px)]">
                    <table class="min-w-full divide-y divide-gray-200 relative">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-2.5 bg-gray-50">Turno</th>
                                <th class="px-4 py-2.5 bg-gray-50">Participante</th>
                                <th class="px-4 py-2.5 bg-gray-50">Ciclo de Entrega</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-center">Cuotas Cubiertas</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-right">Monto Cubierto</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-center">Estado Pagos</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-center">Entrega Física</th>
                                <th class="px-4 py-2.5 bg-gray-50 text-center">Pagos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm">
                            @foreach($tanda->participantes->sortBy('turno') as $participante)
                                @php
    $cuotasTotales = $participante->cuotas->count();
    $cuotasPagadas = $participante->cuotas->where('estado', 'pagado')->count();
    $montoCubierto = $participante->cuotas->where('estado', 'pagado')->sum('monto_esperado');

    // Cálculo de cuotas retrasadas sin pagar para este participante
    $cuotasRetrasadasCount = $participante->cuotas->filter(function ($c) {
        return $c->estado !== 'pagado' && \Carbon\Carbon::parse($c->fecha_limite)->isPast();
    })->count();

    $cuotaDelCiclo = $participante->cuotas->where('ciclo', $participante->ciclo_entrega)->first();
    $fechaEntregaProgramada = $cuotaDelCiclo ? $cuotaDelCiclo->fecha_limite : null;
                                @endphp
                                <tr class="hover:bg-gray-50/75 transition-colors">
                                    <!-- Turno -->
                                    <td class="px-4 py-2.5 whitespace-nowrap font-bold text-gray-700">
                                        @if($participante->turno === 0)
                                            <span
                                                class="px-2.5 py-1 text-xs font-bold bg-indigo-50 text-indigo-700 rounded-md">Org
                                                (0)</span>
                                        @else
                                            <span class="text-gray-700">{{ $participante->turno }}</span>
                                        @endif
                                    </td>

                                    <!-- Participante -->
                                    <td class="px-4 py-2.5 whitespace-nowrap">
                                        @if($participante->cliente_id)
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="font-medium text-gray-900">{{ $participante->cliente->name }}</span>

                                                <!-- Botón Quitar Cliente (Protegido por modoEdicion) -->
                                                @if(!$participante->entregado)
                                                    <form
                                                        action="{{ route('tandas.participantes.quitar-cliente', [$tanda->id, $participante->id]) }}"
                                                        method="POST" class="inline" x-show="modoEdicion" x-cloak>
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center p-1 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition"
                                                            title="Quitar cliente">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @elseif($participante->turno === 0)
                                            <span class="text-gray-500 italic">Organizador</span>
                                        @else
                                            <!-- Selector de cliente SIEMPRE VISIBLE si el turno está vacío -->
                                            <form
                                                action="{{ route('tandas.participantes.asignar', [$tanda->id, $participante->id]) }}"
                                                method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="cliente_id"
                                                    class="text-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-1 px-2"
                                                    required>
                                                    <option value="">Seleccionar cliente...</option>
                                                    @foreach($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center p-1 bg-indigo-600 border border-transparent rounded-md text-white hover:bg-indigo-700 transition"
                                                    title="Guardar / Asignar cliente">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                    <!-- Ciclo de Entrega -->
                                    <td class="px-4 py-2.5 whitespace-nowrap text-gray-700">
                                        <div class="font-medium text-gray-800">Ciclo {{ $participante->ciclo_entrega }}
                                        </div>
                                        @if($fechaEntregaProgramada)
                                            <div class="text-xs text-gray-400">
                                                ({{ \Carbon\Carbon::parse($fechaEntregaProgramada)->format('d/m/Y') }})</div>
                                        @endif
                                    </td>

                                    <!-- Cuotas Cubiertas -->
                                    <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                        <span
                                            class="px-2.5 py-1 text-xs font-bold {{ $cuotasPagadas === $cuotasTotales && $cuotasTotales > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-indigo-50 text-indigo-700' }} rounded-full">
                                            {{ $cuotasPagadas }} / {{ $cuotasTotales }}
                                        </span>
                                    </td>

                                    <!-- Monto Cubierto -->
                                    <td class="px-4 py-2.5 whitespace-nowrap text-right font-medium text-emerald-600">
                                        ${{ number_format($montoCubierto, 2) }}
                                    </td>

                                    <!-- Estado Pagos -->
                                    <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                        @if($cuotasRetrasadasCount > 0)
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $cuotasRetrasadasCount }}
                                                {{ $cuotasRetrasadasCount === 1 ? 'Retrasado' : 'Retrasados' }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Al corriente
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Entrega Física -->
                                    <td class="px-4 py-2.5 whitespace-nowrap text-center text-sm">
                                        @if($participante->entregado)
                                            <div class="inline-flex items-center justify-center gap-1.5">
                                                <!-- Etiqueta visual de Entregado -->
                                                <div class="inline-flex flex-col items-center">
                                                    <span
                                                        class="px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                                        Entregado
                                                    </span>
                                                    <span
                                                        class="text-[11px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($participante->fecha_entrega)->format('d/m/Y') }}</span>
                                                </div>

                                                <!-- Botón para Anular Entrega (Visible únicamente cuando modoEdicion está activo) -->
                                                <form
                                                    action="{{ route('tandas.participantes.anular-entrega', [$tanda->id, $participante->id]) }}"
                                                    method="POST" class="inline" x-show="modoEdicion" x-cloak
                                                    onsubmit="return confirm('¿Estás seguro de anular la entrega de este turno?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center p-1 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition"
                                                        title="Anular entrega">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <!-- Bloqueado / Asignar fecha protegido por modoEdicion -->
                                            <div x-show="!modoEdicion">
                                                <span class="text-gray-400 italic text-xs">Bloqueado</span>
                                            </div>
                                            <form action="{{ route('tandas.participantes.entregar', $participante->id) }}"
                                                method="POST" class="inline-flex items-center space-x-1.5" x-show="modoEdicion"
                                                x-cloak>
                                                @csrf
                                                <input type="date" name="fecha_entrega" value="{{ date('Y-m-d') }}"
                                                    class="text-xs border-gray-300 rounded-md shadow-sm py-1 px-1.5 w-28">
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center p-1 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 transition"
                                                    title="Marcar como entregado">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                    <!-- Pagos / Ver Cuotas -->
                                    <td class="px-4 py-2.5 whitespace-nowrap text-center">
                                        <a href="{{ route('tandas.participante.cuotas', [$tanda->id, $participante->id]) }}?origen=tanda"
                                            class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors inline-block">
                                            Ver Cuotas
                                        </a>
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