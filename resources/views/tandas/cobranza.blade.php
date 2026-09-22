<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cobranza de Tandas') }}
        </h2>
    </x-slot>

    <div class="py-4" x-data="{ 
        modalOpen: false, 
        participanteId: '', 
        clienteNombre: '', 
        tandaNombre: '', 
        cuotasPendientesCount: 0,
        valorCuota: 0,
        cantidadCuotasAPagar: 1,
        
        filtroGeneral: '',
        ordenColumna: '',
        ordenDireccion: 'asc',

        ordenarPor(columna) {
            if (this.ordenColumna === columna) {
                this.ordenDireccion = this.ordenDireccion === 'asc' ? 'desc' : 'asc';
            } else {
                this.ordenColumna = columna;
                this.ordenDireccion = 'asc';
            }
            this.ejecutarOrdenamiento();
        },

        ejecutarOrdenamiento() {
            const tbody = this.$refs.tablaCuerpo;
            if (!tbody) return;
            const filas = Array.from(tbody.querySelectorAll('.fila-item'));
            const col = this.ordenColumna;
            const dir = this.ordenDireccion;

            filas.sort((a, b) => {
                let valA, valB;

                if (col === 'pendiente') {
                    valA = parseFloat(a.dataset.pendiente) || 0;
                    valB = parseFloat(b.dataset.pendiente) || 0;
                    return dir === 'asc' ? valA - valB : valB - valA;
                } else {
                    valA = a.dataset[col] || '';
                    valB = b.dataset[col] || '';
                    return dir === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
                }
            });

            filas.forEach(fila => tbody.appendChild(fila));
        },

        get totalAPagar() {
            return Number(this.cantidadCuotasAPagar) * Number(this.valorCuota);
        },
        abrir(pId, cNom, tNom, cCount, vCuota) {
            this.participanteId = pId;
            this.clienteNombre = cNom;
            this.tandaNombre = tNom;
            this.cuotasPendientesCount = Number(cCount);
            this.valorCuota = Number(vCuota);
            this.cantidadCuotasAPagar = 1;
            this.modalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- TARJETAS SUPERIORES -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <!-- Total Vencido -->
                <div class="relative w-full sm:w-52 bg-red-50 border border-red-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-red-100 rounded-lg text-red-600 flex items-center justify-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-red-700">Total Vencido</div>
                        <div class="text-xl font-black text-red-700 leading-none my-1">
                            ${{ number_format($cuotasVencidas->sum('total_pendiente'), 2) }}
                        </div>
                        <div class="text-xs text-red-600 font-bold">
                            {{ $cuotasVencidas->sum('cantidad_atrasadas') }} cuotas atrasadas
                        </div>
                    </div>
                </div>

                <!-- Vencen Esta Semana -->
                <div class="relative w-full sm:w-52 bg-amber-50 border border-amber-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-amber-100 rounded-lg text-amber-600 flex items-center justify-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Vencen Esta Semana</div>
                        <div class="text-xl font-black text-amber-700 leading-none my-1">
                            ${{ number_format($cuotasEstaSemana->sum('total_pendiente'), 2) }}
                        </div>
                        <div class="text-xs text-amber-700 font-bold">
                            {{ $cuotasEstaSemana->sum('cantidad_proximas') }} cuotas próximas
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENEDOR DE LA TABLA, BUSCADOR Y BOTONES -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                
                <!-- ACCESOS SUPERIORES -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-3">
                    <!-- Buscador Reducido -->
                    <div class="w-full sm:w-80">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" x-model="filtroGeneral" placeholder="Buscar participante u origen..." 
                                class="w-full pl-9 pr-4 py-1.5 text-xs border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Botones de Exportar -->
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Excel
                        </a>
                        <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>

                <div class="overflow-y-auto overflow-x-auto relative rounded-lg border border-gray-100" style="max-height: 64vh;">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th @click="ordenarPor('nombre')" class="px-4 py-2.5 text-left bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-1">
                                        Participante
                                        <span x-show="ordenColumna === 'nombre'" x-text="ordenDireccion === 'asc' ? '▲' : '▼'" class="text-[10px] text-indigo-600"></span>
                                    </div>
                                </th>
                                <th @click="ordenarPor('origen')" class="px-4 py-2.5 text-left bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-1">
                                        Origen
                                        <span x-show="ordenColumna === 'origen'" x-text="ordenDireccion === 'asc' ? '▲' : '▼'" class="text-[10px] text-indigo-600"></span>
                                    </div>
                                </th>
                                <th @click="ordenarPor('tanda')" class="px-4 py-2.5 text-left bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-1">
                                        Tanda
                                        <span x-show="ordenColumna === 'tanda'" x-text="ordenDireccion === 'asc' ? '▲' : '▼'" class="text-[10px] text-indigo-600"></span>
                                    </div>
                                </th>
                                <th class="px-4 py-2.5 text-center bg-gray-50">Turno</th>
                                <th class="px-4 py-2.5 text-center bg-gray-50">Estado / Cuotas</th>
                                <th @click="ordenarPor('fecha')" class="px-4 py-2.5 text-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <div class="flex items-center justify-center gap-1">
                                        Fecha Límite
                                        <span x-show="ordenColumna === 'fecha'" x-text="ordenDireccion === 'asc' ? '▲' : '▼'" class="text-[10px] text-indigo-600"></span>
                                    </div>
                                </th>
                                <th @click="ordenarPor('pendiente')" class="px-4 py-2.5 text-right bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                    <div class="flex items-center justify-end gap-1">
                                        Pendiente Total
                                        <span x-show="ordenColumna === 'pendiente'" x-text="ordenDireccion === 'asc' ? '▲' : '▼'" class="text-[10px] text-indigo-600"></span>
                                    </div>
                                </th>
                                <th class="px-4 py-2.5 text-center bg-gray-50">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white" x-ref="tablaCuerpo">
                            @php
                                $todasLasCuotas = $cuotasVencidas->concat($cuotasEstaSemana);
                            @endphp

                            @forelse($todasLasCuotas as $cuota)
                                @php
                                    $esVencida = isset($cuota->cantidad_atrasadas);
                                    $nombreCliente = $cuota->participante->cliente->nombre ?? $cuota->participante->cliente->name ?? 'Sin nombre';
                                    $nombreTanda = $cuota->participante->tanda->nombre ?? 'Tanda';
                                    $origenCliente = $cuota->participante->cliente->address ?? 'N/D';

                                    $totalPendiente = $cuota->total_pendiente;
                                    $cantidadCuotas = $esVencida ? $cuota->cantidad_atrasadas : $cuota->cantidad_proximas;
                                    $fechaStr = $esVencida ? $cuota->fecha_mas_antigua : $cuota->fecha_proxima;
                                    $fechaFormateada = \Carbon\Carbon::parse($fechaStr)->format('d/m/Y');
                                    $fechaOrdenable = \Carbon\Carbon::parse($fechaStr)->format('Y-m-d');

                                    $valorUnitarioCuota = $cantidadCuotas > 0 ? ($totalPendiente / $cantidadCuotas) : 0;

                                    if ($esVencida) {
                                        $textoEstadoCuotas = $cantidadCuotas . ($cantidadCuotas === 1 ? ' Vencida' : ' Vencidas');
                                    } else {
                                        $textoEstadoCuotas = $cantidadCuotas . ($cantidadCuotas === 1 ? ' Por Vencer' : ' Por Vencer');
                                    }

                                    $tandaId = $cuota->participante->tanda_id ?? $cuota->participante->tanda->id ?? null;
                                    $participanteId = $cuota->tanda_participante_id;
                                @endphp
                                <tr class="{{ $esVencida ? 'hover:bg-red-50/50' : 'hover:bg-amber-50/50' }} transition-colors fila-item"
                                    data-nombre="{{ strtolower($nombreCliente) }}"
                                    data-origen="{{ strtolower($origenCliente) }}"
                                    data-tanda="{{ strtolower($nombreTanda) }}"
                                    data-fecha="{{ $fechaOrdenable }}"
                                    data-pendiente="{{ $totalPendiente }}"
                                    x-show="filtroGeneral === '' || 
                                        {{ json_encode(strtolower($nombreCliente)) }}.includes(filtroGeneral.toLowerCase()) || 
                                        {{ json_encode(strtolower($origenCliente)) }}.includes(filtroGeneral.toLowerCase())">
                                    
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 text-sm leading-tight">{{ $nombreCliente }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-600 font-medium">{{ $origenCliente }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-sm text-indigo-600 font-semibold leading-tight">{{ $nombreTanda }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium">
                                            Turno {{ $cuota->participante->turno ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        @if($esVencida)
                                            <span class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded-full inline-block">
                                                {{ $textoEstadoCuotas }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-bold bg-amber-100 text-amber-800 rounded-full inline-block">
                                                {{ $textoEstadoCuotas }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center {{ $esVencida ? 'text-red-600' : 'text-amber-700' }} font-bold text-sm">
                                        {{ $fechaFormateada }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right font-black {{ $esVencida ? 'text-red-600' : 'text-amber-700' }} text-base">
                                        ${{ number_format($totalPendiente, 2) }}
                                    </td>

                                    <!-- ACCIONES CON NUEVO DISEÑO MEJORADO -->
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            
                                            <!-- Botón Pagar (Estilo Esmeralda Limpio) -->
                                            <button @click="abrir({{ json_encode($participanteId) }}, {{ json_encode($nombreCliente) }}, {{ json_encode($nombreTanda) }}, {{ json_encode($cantidadCuotas) }}, {{ json_encode($valorUnitarioCuota) }})"
                                                title="Registrar Pago de Cuotas"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-lg text-xs font-bold transition-all shadow-xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Pagar
                                            </button>

                                            <!-- Botón Ir al Detalle con el parámetro de origen -->
<a href="{{ route('tandas.participante.cuotas', ['tanda' => $tandaId, 'participante' => $participanteId]) }}?origen=cobranza"
    title="Ir al detalle del participante"
    class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 hover:bg-indigo-600 text-gray-700 hover:text-white border border-gray-200 hover:border-indigo-600 rounded-lg text-xs font-bold transition-all shadow-xs">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
    Ver detalle
</a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500 text-sm">
                                        ¡Excelente! No hay cuotas vencidas ni próximas para esta semana.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- MODAL DE PAGO -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            style="display: none;" x-cloak>
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 mx-4" @click.stop>
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Registrar Pago por Cuotas</h3>
                    <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                </div>

                <form method="POST" action="{{ route('tandas.pagar.lote') }}">
                    @csrf
                    <input type="hidden" name="tanda_participante_id" x-model="participanteId">

                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Cliente:</p>
                        <p class="text-base font-bold text-gray-900" x-text="clienteNombre"></p>
                        <p class="text-xs text-indigo-600 font-semibold" x-text="tandaNombre"></p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1">¿Cuántas cuotas va a pagar?</label>
                        <select name="cantidad_cuotas" x-model.number="cantidadCuotasAPagar"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base font-semibold">
                            <template x-for="i in cuotasPendientesCount" :key="i">
                                <option :value="i" x-text="i + (i === 1 ? ' cuota' : ' cuotas') + ' — $' + (i * valorCuota).toLocaleString('en-US', {minimumFractionDigits: 2})">
                                </option>
                            </template>
                        </select>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-600">Valor por cuota:</span>
                            <span class="text-sm font-bold text-gray-800" x-text="'$' + Number(valorUnitarioCuota).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                        </div>
                        <div class="flex justify-between items-center border-t pt-2 mt-2">
                            <span class="text-sm font-bold text-gray-700">Total a Pagar:</span>
                            <span class="text-xl font-black text-emerald-600" x-text="'$' + Number(totalAPagar).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow">
                            Confirmar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>