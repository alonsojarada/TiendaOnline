<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-hidden">
            <h2 class="font-semibold text-sm sm:text-lg md:text-xl text-gray-800 dark:text-gray-200 leading-tight text-left truncate max-w-full" title="Historial de Cuentas y Préstamos">
                Historial de Cuentas y Préstamos
            </h2>
            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 truncate max-w-full">Vista rápida de saldos pendientes, mercancía fiada y control de cobros.</p>
        </div>
    </x-slot>
    <div class="py-4 md:py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 sm:p-4 md:p-6 text-gray-900 dark:text-gray-100">

                    <!-- Formulario de Filtros Principales -->
                    <form method="GET" action="{{ route('reports.historial-cuentas') }}"
                        class="flex flex-col md:flex-row md:items-center justify-start gap-2 mb-3 sm:mb-4 bg-gray-50 dark:bg-gray-700/50 p-2 md:p-3 rounded-lg border border-gray-200 dark:border-gray-700">

                        <!-- Cliente -->
                        <div class="w-full md:w-52 shrink-0">
                            <select name="client_id"
                                class="w-full text-[11px] sm:text-xs py-1.5 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Todos los clientes --</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}" {{ (request('client_id') == $c->id) ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Controles de Fechas y Botón -->
                        <div class="flex items-center gap-1.5 w-full md:w-auto shrink-0 overflow-x-auto">
                            <!-- Checkbox Fechas -->
                            <div class="flex items-center shrink-0">
                                <label class="flex items-center gap-1 text-[10px] sm:text-[11px] font-medium text-gray-600 dark:text-gray-400 cursor-pointer whitespace-nowrap">
                                    <input type="checkbox" id="activarFechas" name="activar"
                                        {{ (request('fecha_inicio') || request('fecha_fin')) ? 'checked' : '' }}
                                        onchange="toggleFechasCalendario();"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3.5 h-3.5">
                                    <span>Fechas</span>
                                </label>
                            </div>

                            <!-- Desde -->
                            <div class="w-24 sm:w-26 md:w-28 shrink-0">
                                <input type="date" name="fecha_inicio" id="inputFechaInicio"
                                    value="{{ request('fecha_inicio') }}"
                                    {{ (request('fecha_inicio') || request('fecha_fin')) ? '' : 'disabled' }}
                                    class="w-full text-[10px] sm:text-[11px] py-1.5 px-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400">
                            </div>

                            <!-- Hasta -->
                            <div class="w-24 sm:w-26 md:w-28 shrink-0">
                                <input type="date" name="fecha_fin" id="inputFechaFin"
                                    value="{{ request('fecha_fin') }}"
                                    {{ (request('fecha_inicio') || request('fecha_fin')) ? '' : 'disabled' }}
                                    class="w-full text-[10px] sm:text-[11px] py-1.5 px-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400">
                            </div>

                            <!-- Botón Buscar -->
                            <div class="shrink-0">
                                <button type="submit"
                                    title="Buscar"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold p-1.5 rounded-md text-xs shadow-sm transition flex items-center justify-center w-[30px] h-[30px] sm:w-[32px] sm:h-[32px]">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </form>

                    <!-- Barra Secundaria -->
                    <div class="bg-white dark:bg-gray-800 p-2 md:p-3 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-4 md:mb-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-2">

                        <!-- Buscador -->
                        <div class="relative w-full md:w-56 shrink-0">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-indigo-500">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" id="searchInput" onkeyup="aplicarFiltros()"
                                placeholder="Buscar cliente..."
                                class="w-full pl-7 sm:pl-8 pr-2.5 py-1.5 text-[11px] sm:text-xs rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Píldoras y Botones de Exportar -->
                        <div class="flex flex-row items-center justify-between gap-2 w-full md:w-auto overflow-x-auto">
                            <!-- Botones de Píldora (Estatus) -->
                            <div class="flex items-center gap-1 shrink-0" id="filtrosEstatusContainer">
                                <button type="button" onclick="cambiarFiltroEstatus('todos', this)"
                                    class="filtro-btn px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-semibold bg-indigo-600 text-white shadow-sm transition whitespace-nowrap">
                                    Todos
                                </button>
                                <button type="button" onclick="cambiarFiltroEstatus('pendiente', this)"
                                    class="filtro-btn px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 transition whitespace-nowrap">
                                    ⏳ Pendientes
                                </button>
                                <button type="button" onclick="cambiarFiltroEstatus('liquidado', this)"
                                    class="filtro-btn px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-[11px] font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 transition whitespace-nowrap">
                                    ✓ Liquidados
                                </button>
                            </div>

                            <!-- Botones de Exportar -->
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" onclick="exportarExcel()"
                                    class="inline-flex items-center justify-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-1 px-2 sm:px-2.5 rounded-md text-[10px] sm:text-[11px] shadow-sm transition whitespace-nowrap">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Excel
                                </button>

                                <button type="button" onclick="exportarPDF()"
                                    class="inline-flex items-center justify-center gap-1 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-1 px-2 sm:px-2.5 rounded-md text-[10px] sm:text-[11px] shadow-sm transition whitespace-nowrap">
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    PDF
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Tabla de Resultados -->
                    <div class="overflow-auto max-h-[450px] bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm relative">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-[10px] sm:text-xs">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase sticky top-0 z-10">
                                <tr>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 bg-gray-50 dark:bg-gray-700">Cliente</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 bg-gray-50 dark:bg-gray-700 hidden md:table-cell">Dirección</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 bg-gray-50 dark:bg-gray-700">Concepto</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-right bg-gray-50 dark:bg-gray-700 hidden md:table-cell">Monto Inicial</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-right bg-gray-50 dark:bg-gray-700">Abonado</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-right bg-gray-50 dark:bg-gray-700">Saldo Actual</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-center bg-gray-50 dark:bg-gray-700 hidden md:table-cell">F. Apertura</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-center bg-gray-50 dark:bg-gray-700 hidden md:table-cell">F. Liquidación</th>
                                    <th class="px-2 py-2 sm:px-4 sm:py-3 text-center bg-gray-50 dark:bg-gray-700 hidden md:table-cell">Estatus</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCuentas" class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-600 dark:text-gray-300">

                                @forelse($loans as $debt)
                                    @php
                                        $isLiquidated = $debt->current_capital <= 0;

                                        $montoAbonadoDebt = $debt->payments->sum('amount')
                                            ?? ($debt->payments->sum('capital_covered') + $debt->payments->sum('interest_covered'));

                                        $montoInicial = $debt->total_amount;
                                        $sumaAbonosCapital = $debt->payments->sum('capital_covered') ?? 0;

                                        $cuotasPendientes = \App\Models\LoanInstallment::where('debt_id', $debt->id)
                                            ->where('status', 'pending')
                                            ->sum('amount_due');

                                        if (!$isLiquidated && $cuotasPendientes > 0 && (str_contains(strtolower($debt->loan_modal), 'interest_only'))) {
                                            $saldoTotal = ($montoInicial - $sumaAbonosCapital) + $cuotasPendientes;
                                        } else {
                                            $saldoTotal = $debt->current_balance ?? $debt->current_capital;
                                        }

                                        $estatusTexto = $isLiquidated ? 'liquidado' : 'pendiente';

                                        $clientName = $debt->client->name ?? '';
                                        $clientAddress = $debt->client->address ?? '';
                                        $textoBusqueda = strtolower($clientName . ' ' . $clientAddress);

                                        $urlDetalle = ($debt->type === 'store_credit')
                                            ? route('store-details', ['id' => $debt->id, 'from' => 'historial'])
                                            : route('loan-details', ['id' => $debt->id, 'from' => 'historial']);
                                    @endphp

                                    <tr class="fila-cuenta hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition"
                                        data-status="{{ $estatusTexto }}" data-search="{{ $textoBusqueda }}"
                                        onclick="window.location.href='{{ $urlDetalle }}'" title="Clic para ver el detalle">

                                        <!-- Cliente -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 font-bold text-gray-900 dark:text-white">
                                            {{ $debt->client->name ?? 'Cliente Desconocido' }}
                                        </td>
                                        <!-- Dirección -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell">
                                            {{ $debt->client->address ?? 'Sin dirección' }}
                                        </td>
                                        <!-- Concepto -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 font-medium text-gray-700 dark:text-gray-300">
                                            {{ '#' . $debt->id }} - {{ $debt->concept ?? 'Cuenta' }}
                                        </td>
                                        <!-- Monto Inicial -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-right monto-total hidden md:table-cell" data-valor="{{ $debt->total_amount }}">
                                            ${{ number_format($debt->total_amount, 2) }}
                                        </td>
                                        <!-- Abonado -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-right text-emerald-600 monto-abonado" data-valor="{{ $montoAbonadoDebt }}">
                                            ${{ number_format($montoAbonadoDebt, 2) }}
                                        </td>
                                        <!-- Saldo Actual -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-right font-bold monto-saldo" data-valor="{{ $saldoTotal }}">
                                            ${{ number_format($saldoTotal, 2) }}
                                        </td>
                                        <!-- F. Apertura -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-center hidden md:table-cell">
                                            {{ $debt->created_at ? $debt->created_at->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <!-- F. Liquidación -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-center hidden md:table-cell">
                                            @if($isLiquidated)
                                                {{ optional($debt->payments->last())->created_at ? optional($debt->payments->last())->created_at->format('d/m/Y') : 'Liquidado' }}
                                            @else
                                                <span class="text-gray-400"></span>
                                            @endif
                                        </td>
                                        <!-- Estatus -->
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-center hidden md:table-cell">
                                            <span class="px-2 py-1 text-[10px] sm:text-xs font-semibold rounded-full {{ $isLiquidated ? 'text-emerald-700 bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400' : 'text-amber-700 bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                                {{ ucfirst($estatusTexto) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                            No se encontraron cuentas registradas en el sistema con los filtros seleccionados.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                            @if($loans->isNotEmpty())
                                <tfoot class="bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-white border-t-2 border-gray-200 dark:border-gray-600 sticky bottom-0 z-10">
                                    <tr>
                                        <td colspan="2" class="px-2 py-2 sm:px-4 sm:py-3 text-right bg-gray-50 dark:bg-gray-750 hidden md:table-cell">TOTALES:</td>
                                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-left bg-gray-50 dark:bg-gray-700 md:hidden">TOTALES:</td>
                                        <td class="bg-gray-50 dark:bg-gray-700 hidden md:table-cell"></td>
                                        <td id="totalMontoView" class="px-2 py-2 sm:px-4 sm:py-3 text-right bg-gray-50 dark:bg-gray-700 hidden md:table-cell">$0.00</td>
                                        <td id="totalAbonadoView" class="px-2 py-2 sm:px-4 sm:py-3 text-right text-emerald-600 bg-gray-50 dark:bg-gray-700">$0.00</td>
                                        <td id="totalSaldoView" class="px-2 py-2 sm:px-4 sm:py-3 text-right bg-gray-50 dark:bg-gray-700">$0.00</td>
                                        <td colspan="3" class="bg-gray-50 dark:bg-gray-700 hidden md:table-cell"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de Control -->
    <script>
        let estatusActual = 'todos';

        function cambiarFiltroEstatus(estatus, boton) {
            if (estatusActual === estatus && estatus !== 'todos') {
                estatusActual = 'todos';
                const botonTodos = document.querySelector('.filtro-btn');
                actualizarEstilosBotones(botonTodos);
            } else {
                estatusActual = estatus;
                actualizarEstilosBotones(boton);
            }

            aplicarFiltros();
        }

        function actualizarEstilosBotones(botonActivo) {
            document.querySelectorAll('.filtro-btn').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm');
                btn.classList.add('bg-gray-100', 'text-gray-600', 'dark:bg-gray-700', 'dark:text-gray-300');
            });

            if (botonActivo) {
                botonActivo.classList.remove('bg-gray-100', 'text-gray-600', 'dark:bg-gray-700', 'dark:text-gray-300');
                botonActivo.classList.add('bg-indigo-600', 'text-white', 'shadow-sm');
            }
        }

        function aplicarFiltros() {
            const searchInput = document.getElementById('searchInput');
            const textoBusqueda = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const filas = document.querySelectorAll('.fila-cuenta');

            let sumaMonto = 0;
            let sumaAbonado = 0;
            let sumaSaldo = 0;

            filas.forEach(fila => {
                const estatusFila = fila.getAttribute('data-status');
                const textoFila = fila.getAttribute('data-search') || '';

                const coincideEstatus = (estatusActual === 'todos' || estatusFila === estatusActual);
                const coincideTexto = (textoFila.toLowerCase().includes(textoBusqueda));

                if (coincideEstatus && coincideTexto) {
                    fila.style.display = '';

                    const elMonto = fila.querySelector('.monto-total');
                    const elAbonado = fila.querySelector('.monto-abonado');
                    const elSaldo = fila.querySelector('.monto-saldo');

                    if (elMonto) sumaMonto += parseFloat(elMonto.getAttribute('data-valor')) || 0;
                    if (elAbonado) sumaAbonado += parseFloat(elAbonado.getAttribute('data-valor')) || 0;
                    if (elSaldo) sumaSaldo += parseFloat(elSaldo.getAttribute('data-valor')) || 0;
                } else {
                    fila.style.display = 'none';
                }
            });

            const viewMonto = document.getElementById('totalMontoView');
            const viewAbonado = document.getElementById('totalAbonadoView');
            const viewSaldo = document.getElementById('totalSaldoView');

            if (viewMonto) viewMonto.innerText = '$' + sumaMonto.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            if (viewAbonado) viewAbonado.innerText = '$' + sumaAbonado.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            if (viewSaldo) viewSaldo.innerText = '$' + sumaSaldo.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function exportarExcel() {
            let filas = document.querySelectorAll('.fila-cuenta');

            let tablaHtml = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <style>
                .titulo { font-weight: bold; background-color: #4f46e5; color: #ffffff; text-align: center; vertical-align: middle; }
                .header { font-weight: bold; background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db; text-align: center; vertical-align: middle; }
                .celda { border: 1px solid #d1d5db; vertical-align: middle; padding: 4px; }
                .texto { text-align: left; }
                .numero { text-align: right; mso-number-format:"\\$#,##0.00"; }
                .centro { text-align: center; }
                .totales { font-weight: bold; }
                .celda-vacia { border: none; background: transparent; }
            </style>
        </head>
        <body>
            <table>
                <tr>
                    <td colspan="9" class="titulo" style="font-size: 14pt; height: 35px;">HISTORIAL GENERAL DE CUENTAS Y PRÉSTAMOS</td>
                </tr>
                <tr style="height: 10px;"></tr>
                <tr style="height: 25px;">
                    <td class="header">Cliente</td>
                    <td class="header">Dirección</td>
                    <td class="header">Concepto</td>
                    <td class="header">Monto Inicial</td>
                    <td class="header">Abonado</td>
                    <td class="header">Saldo Actual</td>
                    <td class="header">F. Apertura</td>
                    <td class="header">F. Liquidación</td>
                    <td class="header">Estatus</td>
                </tr>`;

            let sumaMonto = 0;
            let sumaAbonado = 0;
            let sumaSaldo = 0;

            filas.forEach(fila => {
                if (fila.style.display !== 'none') {
                    let celdas = fila.querySelectorAll('td');

                    let valMonto = parseFloat(fila.querySelector('.monto-total').getAttribute('data-valor')) || 0;
                    let valAbonado = parseFloat(fila.querySelector('.monto-abonado').getAttribute('data-valor')) || 0;
                    let valSaldo = parseFloat(fila.querySelector('.monto-saldo').getAttribute('data-valor')) || 0;

                    sumaMonto += valMonto;
                    sumaAbonado += valAbonado;
                    sumaSaldo += valSaldo;

                    tablaHtml += "<tr>";
                    tablaHtml += `<td class="celda texto">${celdas[0].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda texto">${celdas[1].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda texto">${celdas[2].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda numero" x:num="${valMonto}" style="mso-number-format:'\\$#,##0.00';">${valMonto}</td>`;
                    tablaHtml += `<td class="celda numero" x:num="${valAbonado}" style="color: #059669; mso-number-format:'\\$#,##0.00';">${valAbonado}</td>`;
                    tablaHtml += `<td class="celda numero" x:num="${valSaldo}" style="font-weight: bold; mso-number-format:'\\$#,##0.00';">${valSaldo}</td>`;
                    tablaHtml += `<td class="celda centro">${celdas[6].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda centro">${celdas[7].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda centro">${celdas[8].innerText.trim()}</td>`;
                    tablaHtml += "</tr>";
                }
            });

            tablaHtml += `<tr class="totales" style="height: 28px;">
            <td colspan="3" class="celda-vacia" style="text-align: right; font-weight: bold;">TOTALES:</td>
            <td class="celda-vacia numero" x:num="${sumaMonto}" style="font-weight: bold; border-top: 1px solid #374151; border-bottom: 1px solid #374151; mso-number-format:'\\$#,##0.00';">${sumaMonto}</td>
            <td class="celda-vacia numero" x:num="${sumaAbonado}" style="font-weight: bold; color: #059669; border-top: 1px solid #374151; border-bottom: 1px solid #374151; mso-number-format:'\\$#,##0.00';">${sumaAbonado}</td>
            <td class="celda-vacia numero" x:num="${sumaSaldo}" style="font-weight: bold; border-top: 1px solid #374151; border-bottom: 1px solid #374151; mso-number-format:'\\$#,##0.00';">${sumaSaldo}</td>
            <td colspan="3" class="celda-vacia"></td>
        </tr>`;

            tablaHtml += `</table></body></html>`;

            let blob = new Blob([tablaHtml], { type: 'application/vnd.ms-excel;charset=utf-8;' });
            let downloadLink = document.createElement("a");
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = "historial_cuentas_" + new Date().toISOString().slice(0, 10) + ".xls";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        function toggleFechasCalendario() {
            const activar = document.getElementById('activarFechas').checked;
            const inputInicio = document.getElementById('inputFechaInicio');
            const inputFin = document.getElementById('inputFechaFin');

            if (activar) {
                inputInicio.removeAttribute('disabled');
                inputFin.removeAttribute('disabled');
            } else {
                inputInicio.setAttribute('disabled', 'true');
                inputFin.setAttribute('disabled', 'true');
                inputInicio.value = '';
                inputFin.value = '';
            }
        }

        function exportarPDF() {
            const urlParams = new URLSearchParams();
            const urlActualParams = new URLSearchParams(window.location.search);
            if (urlActualParams.has('client_id')) {
                urlParams.append('client_id', urlActualParams.get('client_id'));
            }

            const inputInicio = document.getElementById('inputFechaInicio');
            const inputFin = document.getElementById('inputFechaFin');

            if (inputInicio && inputInicio.value) {
                urlParams.append('fecha_inicio', inputInicio.value);
            }
            if (inputFin && inputFin.value) {
                urlParams.append('fecha_fin', inputFin.value);
            }

            let url = "{{ route('reports.historial.pdf-download') }}";
            if (urlParams.toString()) {
                url += '?' + urlParams.toString();
            }

            let downloadLink = document.createElement('a');
            downloadLink.href = url;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const inputFechaInicio = document.getElementById('inputFechaInicio');
            const inputFechaFin = document.getElementById('inputFechaFin');

            const fechaInicioVal = inputFechaInicio ? inputFechaInicio.value : '';
            const fechaFinVal = inputFechaFin ? inputFechaFin.value : '';

            if (fechaInicioVal || fechaFinVal) {
                const activarFechas = document.getElementById('activarFechas');
                if (activarFechas) activarFechas.checked = true;
                toggleFechasCalendario();
            }

            aplicarFiltros();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</x-app-layout>