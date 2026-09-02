<x-app-layout>
    <div class="pt-0 pb-3 px-3 sm:px-4 lg:px-6 w-full mx-auto">

        <!-- ENCABEZADO Y TARJETAS DE RESUMEN RÁPIDO -->
        <div class="mb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Panel General
                    de Cobranza</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Vista rápida de saldos pendientes, mercancía fiada y
                    control de cobros.</p>
            </div>
        </div>

        <!-- TARJETAS SUPERIORES (KPIs) COMPACTAS -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 mb-3">
            <!-- Por Cobrar Total -->
            <div
                class="bg-white dark:bg-gray-800 py-2 px-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-extrabold uppercase tracking-wider text-gray-400">Por Cobrar Total</span>
                <span
                    class="text-sm sm:text-base font-black text-gray-900 dark:text-white mt-0.5">${{ number_format($totalGlobalPendiente, 2) }}</span>
            </div>
            <!-- Total Préstamos -->
            <div
                class="bg-white dark:bg-gray-800 py-2 px-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-extrabold uppercase tracking-wider text-indigo-500">Total Préstamos</span>
                <span
                    class="text-sm sm:text-base font-black text-indigo-600 dark:text-indigo-400 mt-0.5">${{ number_format($totalPrestamos, 2) }}</span>
            </div>
            <!-- Total Mercancía -->
            <div
                class="bg-white dark:bg-gray-800 py-2 px-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col items-center justify-center text-center">
                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-500">Total Mercancía</span>
                <span
                    class="text-sm sm:text-base font-black text-emerald-600 dark:text-emerald-400 mt-0.5">${{ number_format($totalMercancia, 2) }}</span>
            </div>
            <!-- Clientes con Retraso -->
            <div
                class="bg-white dark:bg-gray-800 py-2 px-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 flex flex-col items-center justify-center text-center col-span-2 sm:col-span-1">
                <span class="text-xs font-extrabold uppercase tracking-wider text-red-500">Con Retraso (>7d)</span>
                <span
                    class="text-sm sm:text-base font-black text-red-600 dark:text-red-400 mt-0.5">{{ $clientesConRetrasoCount }}
                    Clientes</span>
            </div>
        </div>

        <!-- FILTROS, BUSCADOR Y BOTONES DE EXPORTACIÓN -->
        <div
            class="bg-white dark:bg-gray-800 p-2.5 sm:p-3 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700/80 mb-3 flex flex-col lg:flex-row items-center justify-between gap-3">

            <!-- Buscador y Filtros agrupados -->
            <div class="flex flex-col md:flex-row items-center gap-2.5 w-full lg:w-auto">
                <!-- Buscador -->
                <div class="relative w-full md:w-80">
                    <span
                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-xs">🔍</span>
                    <input type="text" id="buscadorDashboard"
                        placeholder="Buscar cliente por nombre, alias o dirección..."
                        class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600/60 rounded-lg text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>

                <!-- Botones de Filtro (Selección Múltiple) -->
                <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto" id="filtrosEstado">
                    <button type="button" onclick="toggleFiltro('todos')" id="btn-todos"
                        class="filtro-btn px-2.5 py-1 bg-indigo-600 text-white rounded-lg text-xs font-bold shadow-xs transition">
                        Todos
                    </button>
                    <button type="button" onclick="toggleFiltro('retraso')" id="btn-retraso"
                        class="filtro-btn px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg text-xs font-semibold transition">
                        ⚠️ Con Retraso
                    </button>
                    <button type="button" onclick="toggleFiltro('proximos')" id="btn-proximos"
                        class="filtro-btn px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg text-xs font-semibold transition">
                        ⏰ Próximos a Vencer
                    </button>
                    <button type="button" onclick="toggleFiltro('al_dia')" id="btn-al_dia"
                        class="filtro-btn px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg text-xs font-semibold transition">
                        ✓ Al Corriente
                    </button>
                </div>
            </div>

            <!-- Botones de Exportar (Excel y PDF) -->
            <div style="display: flex; gap: 8px; align-items: center;">
                <!-- Botón Excel -->
                <a href="{{ route('dashboard.export.excel') }}"
                    style="display: inline-flex; align-items: center; gap: 6px; background-color: #16a34a; color: white; padding: 6px 12px; font-size: 13px; font-weight: 500; border-radius: 6px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#15803d'"
                    onmouseout="this.style.backgroundColor='#16a34a'">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Excel
                </a>

                <!-- Botón PDF -->
                <a href="{{ route('dashboard.export.pdf') }}"
                    style="display: inline-flex; align-items: center; gap: 6px; background-color: #dc2626; color: white; padding: 6px 12px; font-size: 13px; font-weight: 500; border-radius: 6px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#b91c1c'"
                    onmouseout="this.style.backgroundColor='#dc2626'">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Descargar PDF
                </a>
            </div>
        </div>

        <!-- TABLA DE COBRANZA -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-2 border-indigo-200/60 dark:border-indigo-900/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-indigo-100/70 dark:bg-indigo-950/60 text-xs font-black text-indigo-950 dark:text-indigo-200 tracking-wider border-b-2 border-indigo-200 dark:border-indigo-900">
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-3">Dirección</th>
                            <th class="py-3 px-3">Concepto</th>
                            <th class="py-3 px-3">Última Actividad</th>
                            <th class="py-3 px-3">Próximo Abono / Antigüedad</th>
                            <th class="py-3 px-4 text-right">Saldo Pendiente</th>
                            <th class="py-3 px-4 text-center whitespace-nowrap">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm" id="tablaCobranzaBody">
                        @forelse($deudasGlobales as $deuda)
                            @php
                                $rowStyleClass = '';
                                if ($deuda->is_atrasado) {
                                    $rowStyleClass = 'bg-red-100/70 hover:bg-red-200/80 dark:bg-red-950/40 dark:hover:bg-red-900/50 border-b border-b-red-400 dark:border-b-red-700';
                                } elseif ($deuda->is_proximo) {
                                    $rowStyleClass = 'bg-amber-100/60 hover:bg-amber-200/80 dark:bg-amber-950/40 dark:hover:bg-amber-900/50 border-b border-b-amber-400 dark:border-b-amber-700';
                                } else {
                                    $rowStyleClass = 'bg-white dark:bg-gray-800 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30 border-b border-gray-100 dark:border-gray-700/50';
                                }
                            @endphp

                            <tr onclick="window.location.href='{{ route('clients.show', $deuda->client_id) }}'"
                                class="transition-colors fila-cobranza cursor-pointer group {{ $rowStyleClass }}"
                                data-estado="{{ $deuda->is_atrasado ? 'retraso' : ($deuda->is_proximo ? 'proximos' : 'al_dia') }}">

                                <!-- Nombre del Cliente -->
                                <td class="py-2.5 px-4">
                                    <div
                                        class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $deuda->client->name ?? 'Cliente General' }}
                                    </div>
                                    @if(!empty($deuda->client->alias))
                                        <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 mt-0.5">
                                            "{{ $deuda->client->alias }}"
                                        </div>
                                    @endif
                                </td>

                                <!-- Dirección -->
                                <td class="py-2.5 px-3 text-gray-600 dark:text-gray-300 font-normal">
                                    {{ $deuda->client->address ?? 'Sin dirección' }}
                                </td>

                                <!-- Concepto -->
                                <td class="py-2.5 px-3">
                                    <span class="font-medium text-gray-800 dark:text-gray-200">
                                        {{ $deuda->concept }}
                                    </span>
                                </td>

                                <!-- Última Actividad -->
                                <td class="py-2.5 px-3">
                                    <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $deuda->fecha_ref }}
                                    </div>
                                    @if($deuda->is_loan)
                                        <div
                                            class="text-xs font-medium {{ $deuda->cuotas_vencidas > 0 ? 'text-red-600 font-semibold' : 'text-emerald-600' }} mt-0.5">
                                            {{ $deuda->cuotas_vencidas > 0 ? $deuda->cuotas_vencidas . ' abonos pendientes' : 'Al corriente' }}
                                        </div>
                                    @else
                                        <div
                                            class="text-xs font-medium {{ $deuda->dias_sin_abonar > 7 ? 'text-red-600 font-semibold' : 'text-emerald-600' }} mt-0.5">
                                            {{ $deuda->dias_sin_abonar }} días sin abonar
                                        </div>
                                    @endif
                                </td>

                                <!-- Próximo Abono / Antigüedad -->
                                <td class="py-2.5 px-3">
                                    @if($deuda->is_loan)
                                        <div
                                            class="font-medium {{ $deuda->dias_proximo_abono < 0 ? 'text-red-600 font-semibold' : ($deuda->dias_proximo_abono <= 2 ? 'text-amber-600 font-semibold' : 'text-gray-700 dark:text-gray-300') }}">
                                            {{ $deuda->texto_proximo_abono }}
                                        </div>
                                    @else
                                        <div
                                            class="font-medium {{ $deuda->dias_sin_abonar > 4 ? 'text-amber-600 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $deuda->dias_sin_abonar }} días desde movimiento
                                        </div>
                                    @endif
                                </td>

                                <!-- Saldo Pendiente -->
                                <td class="py-2.5 px-4 text-right">
                                    <span class="text-base font-black text-gray-900 dark:text-white">
                                        ${{ number_format($deuda->saldo_pendiente, 2) }}
                                    </span>
                                </td>

                                <!-- Estado Visual -->
                                <td class="py-2.5 px-4 text-center whitespace-nowrap">
                                    @if($deuda->is_atrasado)
                                        <span
                                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1 bg-red-200 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-md font-bold text-xs tracking-wide w-32 text-center shadow-2xs">
                                            ⚠️ ATRASADO
                                        </span>
                                    @elseif($deuda->is_proximo)
                                        <span
                                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1 bg-amber-200 dark:bg-amber-900 text-amber-800 dark:text-amber-200 rounded-md font-bold text-xs tracking-wide w-32 text-center shadow-2xs">
                                            ⏰ PRÓXIMO
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-md font-bold text-xs tracking-wide w-32 text-center">
                                            ✓ AL DÍA
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">
                                    <div class="text-3xl mb-2">🎉</div>
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-300">¡Excelente trabajo!</p>
                                    <p class="text-xs text-gray-400 mt-0.5">No hay saldos pendientes por cobrar en este
                                        momento.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- SCRIPT PARA FILTRADO MULTI-SELECCIÓN Y BÚSQUEDA -->
    <script>
        let filtrosSeleccionados = new Set(['todos']);

        function toggleFiltro(tipo) {
            const btnTodos = document.getElementById('btn-todos');

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

            // Si se deseleccionan todos, activar 'todos' por defecto
            if (filtrosSeleccionados.size === 0) {
                filtrosSeleccionados.add('todos');
            }

            actualizarInterfazFiltros();
            aplicarFiltros();
        }

        function actualizarInterfazFiltros() {
            ['todos', 'retraso', 'proximos', 'al_dia'].forEach(tipo => {
                const btn = document.getElementById(`btn-${tipo}`);
                if (filtrosSeleccionados.has(tipo)) {
                    btn.className = "filtro-btn px-2.5 py-1 bg-indigo-600 text-white rounded-lg text-xs font-bold shadow-xs transition";
                } else {
                    btn.className = "filtro-btn px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg text-xs font-semibold transition";
                }
            });
        }

        function aplicarFiltros() {
            const filas = document.querySelectorAll('.fila-cobranza');
            filas.forEach(fila => {
                const estadoFila = fila.getAttribute('data-estado');
                if (filtrosSeleccionados.has('todos') || filtrosSeleccionados.has(estadoFila)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        document.getElementById('buscadorDashboard').addEventListener('input', function (e) {
            const texto = e.target.value.toLowerCase().trim();
            const filas = document.querySelectorAll('.fila-cobranza');

            filas.forEach(fila => {
                const clienteCelda = fila.querySelector('td:nth-child(1)');
                const textoCliente = clienteCelda ? clienteCelda.innerText.toLowerCase() : '';

                // Mostrar solo si cumple el buscador Y los filtros de estado
                const estadoFila = fila.getAttribute('data-estado');
                const coincideEstado = filtrosSeleccionados.has('todos') || filtrosSeleccionados.has(estadoFila);

                if (coincideEstado && textoCliente.includes(texto)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        function abrirPdfModal(url) {
            // Evita duplicar iframes si ya existe uno
            let iframeExistente = document.getElementById('pdf-print-frame');
            if (iframeExistente) {
                iframeExistente.remove();
            }

            // Crea un iframe oculto
            const iframe = document.createElement('iframe');
            iframe.id = 'pdf-print-frame';
            iframe.style.display = 'none';
            iframe.src = url;

            document.body.appendChild(iframe);

            // Cuando el iframe cargue el contenido, lanza el cuadro de impresión automáticamente
            iframe.onload = function () {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (e) {
                    console.error("No se pudo iniciar la impresión automática", e);
                }
            };
        }
    </script>
</x-app-layout>