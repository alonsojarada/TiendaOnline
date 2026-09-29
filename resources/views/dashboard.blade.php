<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                Panel General de Cobranza
            </h2>
            <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">Vista rápida de saldos pendientes, mercancía fiada y control de cobros.</p>
        </div>
    </x-slot>

    <div class="pt-2 pb-6 px-2 sm:px-6 lg:px-8 w-full mx-auto">

        <!-- TARJETAS SUPERIORES (KPIs) - Compactas -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-3">
            <!-- Por Cobrar Total -->
            <div class="bg-white dark:bg-gray-800 px-3 py-2 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col justify-between">
                <span class="text-[10px] md:text-[11px] font-bold uppercase tracking-wider text-gray-400 leading-tight">Por Cobrar Total</span>
                <span class="text-sm sm:text-base md:text-lg font-black text-gray-900 dark:text-white mt-0.5 truncate">${{ number_format($totalGlobalPendiente, 2) }}</span>
            </div>
            <!-- Total Préstamos -->
            <div class="bg-white dark:bg-gray-800 px-3 py-2 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col justify-between">
                <span class="text-[10px] md:text-[11px] font-bold uppercase tracking-wider text-indigo-500 leading-tight">Total Préstamos</span>
                <span class="text-sm sm:text-base md:text-lg font-black text-indigo-600 dark:text-indigo-400 mt-0.5 truncate">${{ number_format($totalPrestamos, 2) }}</span>
            </div>
            <!-- Total Mercancía -->
            <div class="bg-white dark:bg-gray-800 px-3 py-2 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col justify-between">
                <span class="text-[10px] md:text-[11px] font-bold uppercase tracking-wider text-emerald-500 leading-tight">Total Mercancía</span>
                <span class="text-sm sm:text-base md:text-lg font-black text-emerald-600 dark:text-emerald-400 mt-0.5 truncate">${{ number_format($totalMercancia, 2) }}</span>
            </div>
            <!-- Clientes con Retraso -->
            <div class="bg-white dark:bg-gray-800 px-3 py-2 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col justify-between">
                <span class="text-[10px] md:text-[11px] font-bold uppercase tracking-wider text-red-500 leading-tight">Con Retraso (>7d)</span>
                <span class="text-sm sm:text-base md:text-lg font-black text-red-600 dark:text-red-400 mt-0.5 truncate">{{ $clientesConRetrasoCount }} Clientes</span>
            </div>
        </div>

        <!-- BARRA DE ACCIÓN: BUSCADOR, FILTROS Y EXPORTACIÓN -->
        <div class="bg-white dark:bg-gray-800 p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700/80 mb-3 flex flex-col gap-2.5">
            
            <!-- 1. VISTA ESCRITORIO GRANDE (lg en adelante): Todo en una sola línea (Buscador izq -> Filtros al lado -> Exportar der) -->
            <div class="hidden lg:flex items-center justify-between gap-3 w-full">
                <!-- Bloque Izquierda: Buscador y Filtros juntos -->
                <div class="flex items-center gap-2.5 flex-1 min-w-0">
                    <!-- Buscador -->
                    <div class="relative w-60 shrink-0">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">🔍</span>
                        <input type="text" id="buscadorDashboard-lg" placeholder="Buscar cliente o alias..." oninput="sincronizarBusqueda(this.value)"
                            class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600/60 rounded-xl text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>

                    <!-- Filtros (Directamente a un lado del buscador) -->
                    <div class="flex items-center gap-1.5 flex-wrap" id="filtrosEstado-desktop">
                        <button type="button" onclick="toggleFiltro('todos')" id="btn-todos-lg"
                            class="filtro-btn px-2.5 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-xs transition">
                            Todos
                        </button>
                        <button type="button" onclick="toggleFiltro('retraso')" id="btn-retraso-lg"
                            class="filtro-btn px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition">
                            ⚠️ Retraso
                        </button>
                        <button type="button" onclick="toggleFiltro('proximos')" id="btn-proximos-lg"
                            class="filtro-btn px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition">
                            ⏰ Próximos
                        </button>
                        <button type="button" onclick="toggleFiltro('al_dia')" id="btn-al_dia-lg"
                            class="filtro-btn px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition">
                            ✓ Al Día
                        </button>
                    </div>
                </div>

                <!-- Bloque Derecha: Botones Excel y PDF -->
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('dashboard.export.excel') }}" title="Exportar Excel"
                        class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 text-xs font-semibold rounded-xl shadow-xs transition">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Excel
                    </a>
                    <a href="{{ route('dashboard.export.pdf') }}" title="Exportar PDF"
                        class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 text-xs font-semibold rounded-xl shadow-xs transition">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                </div>
            </div>

            <!-- 2. VISTA VENTANAS MEDIANAS Y MÓVILES (<lg): Dos filas obligatorias (Filtros abajo) -->
            <div class="flex lg:hidden flex-col gap-2.5 w-full">
                <!-- Fila 1: Buscador a la izquierda y Botones de Exportar a la derecha -->
                <div class="flex items-center justify-between gap-2 w-full">
                    <div class="relative flex-1 min-w-0 max-w-sm">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">🔍</span>
                        <input type="text" id="buscadorDashboard-sm" placeholder="Buscar cliente o alias..." oninput="sincronizarBusqueda(this.value)"
                            class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-600/60 rounded-xl text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('dashboard.export.excel') }}" title="Exportar Excel"
                            class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 text-xs font-semibold rounded-xl shadow-xs transition">
                            <svg class="w-3.5 h-3.5 sm:mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span class="hidden sm:inline">Excel</span>
                        </a>
                        <a href="{{ route('dashboard.export.pdf') }}" title="Exportar PDF"
                            class="inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 text-xs font-semibold rounded-xl shadow-xs transition">
                            <svg class="w-3.5 h-3.5 sm:mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="hidden sm:inline">PDF</span>
                        </a>
                    </div>
                </div>

                <!-- Fila 2: Filtros abajo -->
                <div class="flex flex-wrap items-center gap-1.5 w-full" id="filtrosEstado-mobile">
                    <button type="button" onclick="toggleFiltro('todos')" id="btn-todos-sm"
                        class="filtro-btn flex-1 sm:flex-none px-3 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-xs transition text-center">
                        Todos
                    </button>
                    <button type="button" onclick="toggleFiltro('retraso')" id="btn-retraso-sm"
                        class="filtro-btn flex-1 sm:flex-none px-3 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition text-center">
                        ⚠️ Retraso
                    </button>
                    <button type="button" onclick="toggleFiltro('proximos')" id="btn-proximos-sm"
                        class="filtro-btn flex-1 sm:flex-none px-3 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition text-center">
                        ⏰ Próximos
                    </button>
                    <button type="button" onclick="toggleFiltro('al_dia')" id="btn-al_dia-sm"
                        class="filtro-btn flex-1 sm:flex-none px-3 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition text-center">
                        ✓ Al Día
                    </button>
                </div>
            </div>

        </div>

        <!-- TABLA ADAPTABLE COMPACTA -->
        <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 text-[11px] sm:text-xs font-black text-gray-500 dark:text-gray-400 tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2.5 px-2.5 sm:px-4">Cliente</th>
                            <th class="py-2.5 px-2.5 hidden md:table-cell">Dirección</th>
                            <th class="py-2.5 px-2.5">Concepto</th>
                            <th class="py-2.5 px-2.5">Última Actividad</th>
                            <th class="py-2.5 px-2.5 hidden md:table-cell">Próximo Abono / Antigüedad</th>
                            <th class="py-2.5 px-2.5 sm:px-4 text-right">Saldo Pendiente</th>
                            <th class="py-2.5 px-3 text-center whitespace-nowrap hidden md:table-cell">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs sm:text-sm divide-y divide-gray-100 dark:divide-gray-700/50" id="tablaCobranzaBody">
                        @forelse($deudasGlobales ?? [] as $deuda)
                            @php
                                $rowStyleClass = '';
                                if ($deuda->is_atrasado) {
                                    $rowStyleClass = 'bg-red-50/60 hover:bg-red-100/70 dark:bg-red-950/20 dark:hover:bg-red-900/35 border-l-4 border-l-red-500';                                 } elseif ($deuda->is_proximo) {
                                    $rowStyleClass = 'bg-amber-50/50 hover:bg-amber-100/70 dark:bg-amber-950/20 dark:hover:bg-amber-900/35 border-l-4 border-l-amber-500';                                 } else {$rowStyleClass = 'hover:bg-gray-50/80 dark:hover:bg-gray-700/30 border-l-4 border-l-transparent';
                                }
                            @endphp

                            <tr onclick="window.location.href='{{ route('clients.show', $deuda->client_id) }}'"
                                class="transition-colors fila-item cursor-pointer {{ $rowStyleClass }}"
                                data-estado="{{ $deuda->is_atrasado ? 'retraso' : ($deuda->is_proximo ? 'proximos' : 'al_dia') }}">

                                <td class="py-2.5 px-2.5 sm:px-4">
                                    <div class="font-bold text-gray-900 dark:text-white text-[11px] sm:text-sm leading-tight">
                                        {{ $deuda->client->name ?? 'Cliente General' }}
                                    </div>
                                    @if(!empty($deuda->client->alias))
                                        <div class="text-[10px] sm:text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 mt-0.5">
                                            "{{ $deuda->client->alias }}"
                                        </div>
                                    @endif
                                </td>

                                <td class="py-2.5 px-2.5 text-[11px] sm:text-xs text-gray-600 dark:text-gray-300 hidden md:table-cell">
                                    {{ $deuda->client->address ?? 'Sin dirección' }}
                                </td>

                                <td class="py-2.5 px-2.5">
                                    <span class="font-medium text-[11px] sm:text-xs text-gray-800 dark:text-gray-200">
                                        {{ $deuda->concept }}
                                    </span>
                                </td>

                                <td class="py-2.5 px-2.5">
                                    <div class="font-semibold text-[11px] sm:text-xs text-gray-800 dark:text-gray-200">{{ $deuda->fecha_ref }}</div>
                                    @if($deuda->is_loan)
                                        <div class="text-[9px] sm:text-[11px] font-medium {{ $deuda->cuotas_vencidas > 0 ? 'text-red-600 font-semibold' : 'text-emerald-600' }} mt-0.5">
                                            ({{ $deuda->cuotas_vencidas > 0 ?$deuda->cuotas_vencidas . ' abonos pend.' : 'Al corriente' }})
                                        </div>
                                    @else
                                        <div class="text-[9px] sm:text-[11px] font-medium {{ $deuda->dias_sin_abonar > 7 ? 'text-red-600 font-semibold' : 'text-emerald-600' }} mt-0.5">
                                            ({{ $deuda->dias_sin_abonar }} días sin abonar)
                                        </div>
                                    @endif
                                </td>

                                <td class="py-2.5 px-2.5 hidden md:table-cell">
                                    @if($deuda->is_loan)
                                        <div class="text-xs font-medium {{ $deuda->dias_proximo_abono < 0 ? 'text-red-600 font-semibold' : ($deuda->dias_proximo_abono <= 2 ? 'text-amber-600 font-semibold' : 'text-gray-700 dark:text-gray-300') }}">
                                            {{ $deuda->texto_proximo_abono }}
                                        </div>
                                    @else
                                        <div class="text-xs font-medium {{ $deuda->dias_sin_abonar > 4 ? 'text-amber-600 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $deuda->dias_sin_abonar }} días desde movimiento
                                        </div>
                                    @endif
                                </td>

                                <td class="py-2.5 px-2.5 sm:px-4 text-right">
                                    <span class="text-[11px] sm:text-sm font-black text-gray-900 dark:text-white">
                                        ${{ number_format($deuda->saldo_pendiente, 2) }}
                                    </span>
                                </td>

                                <td class="py-2.5 px-3 text-center whitespace-nowrap hidden md:table-cell">
                                    @if($deuda->is_atrasado)
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 bg-red-100 dark:bg-red-900/60 text-red-700 dark:text-red-300 rounded-md font-bold text-[9px] tracking-wide w-24 text-center">
                                            ⚠️ ATRASADO
                                        </span>
                                    @elseif($deuda->is_proximo)
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 rounded-md font-bold text-[9px] tracking-wide w-24 text-center">
                                            ⏰ PRÓXIMO
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-md font-bold text-[9px] tracking-wide w-24 text-center">
                                            ✓ AL DÍA
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-400">
                                    <div class="text-2xl mb-1">🎉</div>
                                    <p class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300">¡Excelente trabajo!</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">No hay saldos pendientes por cobrar en este momento.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- SCRIPT DE FILTRADO Y BÚSQUEDA -->
    <script>
        let filtrosSeleccionados = new Set(['todos']);

        function toggleFiltro(tipo) {
            if (tipo === 'todos') {
                filtrosSeleccionados = new Set(['todos']);
            } else {
                filtrosSeleccionados.delete('todos');
                if (filtrosSeleccionados.has(tipo)) {
                    filtrosSeleccionados.delete(tipo);
                } else {
                    filtrosSeleccionados.add(tipo);
                }
            }

            if (filtrosSeleccionados.size === 0) {
                filtrosSeleccionados.add('todos');
            }

            actualizarInterfazFiltros();
            aplicarFiltros();
        }

        function actualizarInterfazFiltros() {
            ['todos', 'retraso', 'proximos', 'al_dia'].forEach(tipo => {
                // Actualizar versión escritorio (lg)
                const btnLg = document.getElementById(`btn-${tipo}-lg`);
                if (btnLg) {
                    btnLg.className = filtrosSeleccionados.has(tipo)
                        ? "filtro-btn px-2.5 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-xs transition"
                        : "filtro-btn px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition";
                }
                // Actualizar versión móvil / mediana (<lg)
                const btnSm = document.getElementById(`btn-${tipo}-sm`);
                if (btnSm) {
                    btnSm.className = filtrosSeleccionados.has(tipo)
                        ? "filtro-btn flex-1 sm:flex-none px-3 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-xs transition text-center"
                        : "filtro-btn flex-1 sm:flex-none px-3 py-1.5 bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl text-xs font-semibold transition text-center";
                }
            });
        }

        function aplicarFiltros() {
            const inputLg = document.getElementById('buscadorDashboard-lg');
            const inputSm = document.getElementById('buscadorDashboard-sm');
            const texto = ((inputLg ? inputLg.value : '') || (inputSm ? inputSm.value : '')).toLowerCase().trim();
            const elementos = document.querySelectorAll('.fila-item');

            elementos.forEach(el => {
                const contenidoTexto = el.innerText.toLowerCase();
                const estado = el.getAttribute('data-estado');
                const coincideEstado = filtrosSeleccionados.has('todos') || filtrosSeleccionados.has(estado);

                if (coincideEstado && contenidoTexto.includes(texto)) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        }

        function sincronizarBusqueda(valor) {
            const lg = document.getElementById('buscadorDashboard-lg');
            const sm = document.getElementById('buscadorDashboard-sm');
            if (lg && lg.value !== valor) lg.value = valor;
            if (sm && sm.value !== valor) sm.value = valor;
            
            aplicarFiltros();
        }
    </script>
</x-app-layout>