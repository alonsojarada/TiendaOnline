<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                <span>📊</span> Reporte General de Abonos
            </h2>
        </div>
    </x-slot>

    <div class="pt-0 pb-6 px-3 sm:px-4 lg:px-6 w-full mx-auto space-y-6">
        <!-- CONTENEDOR GENERAL UNIFICADO: Tarjetas a la izquierda y Búsqueda a la derecha -->
        <div
            class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700/80 mb-4">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                <!-- Cálculo de Totales -->
                @php
                    $totalMercancia = $pagosDelPeriodo->filter(function ($p) {
                        return optional($p->debt)->type === 'store_credit';
                    })->sum('amount');

                    $totalEfectivo = $pagosDelPeriodo->filter(function ($p) {
                        return optional($p->debt)->type !== 'store_credit';
                    })->sum('amount');

                    $totalGeneral = $pagosDelPeriodo->sum('amount');
                @endphp

                <!-- 1. Grupo de 3 Tarjetas de Resumen (Lado Izquierdo) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 w-full xl:w-auto shrink-0">
                    <!-- Tarjeta 1: Mercancía -->
                    <div
                        class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white p-2.5 rounded-xl shadow-xs relative overflow-hidden flex items-center justify-between min-w-[170px]">
                        <div>
                            <div class="flex items-center gap-1">
                                <span
                                    class="text-[9px] font-black uppercase tracking-wider text-emerald-100">Mercancía</span>
                                <span
                                    class="text-[8px] font-bold uppercase tracking-wider bg-emerald-800/80 px-1 py-0.5 rounded text-emerald-200">Store
                                    Credit</span>
                            </div>
                            <div class="text-sm font-black mt-0.5">${{ number_format($totalMercancia, 2) }}</div>
                        </div>
                        <div class="text-base opacity-80 ml-3">📦</div>
                    </div>

                    <!-- Tarjeta 2: Efectivo -->
                    <div
                        class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white p-2.5 rounded-xl shadow-xs relative overflow-hidden flex items-center justify-between min-w-[170px]">
                        <div>
                            <div class="flex items-center gap-1">
                                <span
                                    class="text-[9px] font-black uppercase tracking-wider text-indigo-100">Efectivo</span>
                                <span
                                    class="text-[8px] font-bold uppercase tracking-wider bg-indigo-800/80 px-1 py-0.5 rounded text-indigo-200">Cash
                                    Loan</span>
                            </div>
                            <div class="text-sm font-black mt-0.5">${{ number_format($totalEfectivo, 2) }}</div>
                        </div>
                        <div class="text-base opacity-80 ml-3">💵</div>
                    </div>

                    <!-- Tarjeta 3: Venta Total -->
                    <div
                        class="bg-gray-900 dark:bg-gray-900 text-white p-2.5 rounded-xl shadow-xs relative overflow-hidden flex items-center justify-between min-w-[170px]">
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="text-[9px] font-black uppercase tracking-wider text-gray-300">Venta
                                    Total</span>
                                <span
                                    class="text-[8px] font-bold uppercase tracking-wider bg-gray-800 px-1 py-0.5 rounded text-gray-300">Acumulado</span>
                            </div>
                            <div class="text-sm font-black mt-0.5">${{ number_format($totalGeneral, 2) }}</div>
                        </div>
                        <div class="text-base opacity-80 ml-3">📊</div>
                    </div>
                </div>

                <!-- 2. Filtros y Búsqueda (Lado Derecho) -->
                <form method="GET" action="{{ route('reports.abonos') }}"
                    class="flex flex-wrap items-center gap-2.5 justify-start xl:justify-end">
                    <!-- Selector de Cliente -->
                    <div class="w-full sm:w-auto">
                        <select name="client_id"
                            class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer w-full sm:w-48">
                            <option value="">Todos los clientes</option>
                            @foreach($clientes as $client)
                                <option value="{{ $client->id }}" {{ (isset($clientId) && $clientId == $client->id) ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="w-full sm:w-auto">
                        <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}"
                            class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full sm:w-36">
                    </div>

                    <!-- Fecha Fin -->
                    <div class="w-full sm:w-auto">
                        <input type="date" name="fecha_fin" value="{{ $fechaFin }}"
                            class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full sm:w-36">
                    </div>

                    <!-- Botón Consultar -->
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 text-xs font-bold rounded-xl shadow-xs transition">
                        <span>🔍</span> Consultar
                    </button>
                </form>

            </div>
        </div>

        <!-- TABLA DE HISTORIAL COMBINADO DE MOVIMIENTOS -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
            <!-- Cabecera con Título, Subtítulo y tu Botón de Exportar Excel -->
            <div
                class="p-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white dark:bg-gray-800 z-10">
                <div>
                    <h3
                        class="text-xs font-black uppercase tracking-wider text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                        <span>⏱️</span> Historial de abonos y pagos registrados
                    </h3>
                </div>

                <!-- Botón Exportar Excel modificado para invocar función JS -->
                <div class="flex items-center justify-end w-full sm:w-auto">
                    <button type="button" onclick="exportarExcel()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                        <span>📥</span> Exportar Excel
                    </button>
                </div>
            </div>

            <!-- Contenedor con scroll interno vertical responsivo adaptado a pantalla grande -->
            <div
                class="overflow-y-auto overflow-x-hidden max-h-[550px] xl:h-[calc(100vh-300px)] bg-white dark:bg-gray-800 relative">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-xs table-auto">
                    <thead
                        class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase sticky top-0 z-10 shadow-xs">
                        <tr>
                            <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700">Fecha</th>
                            <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700">Tipo de Crédito</th>
                            <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700">Cliente</th>
                            <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700">Dirección</th>
                            <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700">Concepto / Descripción</th>
                            <th class="px-4 py-3 text-right bg-gray-50 dark:bg-gray-700">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-600 dark:text-gray-300">

                        @forelse($pagosDelPeriodo as $payment)
                            @php
                                $client = optional($payment->debt)->client;
                                $clientName = $client ? trim($client->name . ' ' . $client->alias) : 'Cliente General';
                                $clientAddress = $client && $client->address ? $client->address : 'S/D';

                                $debtType =  optional($payment->debt)->type;
                                $isStoreCredit = $debtType === 'store_credit';
                                $tipoTexto = $isStoreCredit ? 'Mercancía' : 'Crédito Efectivo';

                                $badgeClass = $isStoreCredit
                                    ? 'text-purple-700 bg-purple-100 dark:bg-purple-900/30 dark:text-purple-400'
                                    : 'text-blue-700 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400';

                                $valMonto = $payment->amount ?? 0;
                            @endphp

                            <!-- Se agregó la clase 'fila-cuenta' y el atributo 'data-valor' -->
                            <tr class="fila-cuenta hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <!-- Fecha -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </td>

                                <!-- Tipo de Crédito -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                        {{ $tipoTexto }}
                                    </span>
                                </td>

                                <!-- Cliente -->
                                <td class="px-4 py-3 font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $clientName }}
                                </td>

                                <!-- Dirección del Cliente -->
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                    {{ $clientAddress }}
                                </td>

                                <!-- Concepto -->
                                <td class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">
                                    {{  '#' . $payment->debt_id }} - {{ ($payment->debt)->concept }}
                                </td>

                                <!-- Monto -->
                                <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white whitespace-nowrap monto-total" data-valor="{{ $valMonto }}">
                                    ${{ number_format($valMonto, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No se encontraron abonos registrados en el sistema con los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                    <!-- Totales Desglosados en el Footer (Fijo abajo) -->
                    @if($pagosDelPeriodo->isNotEmpty())
                        @php
                            $totalMercancia = $pagosDelPeriodo->filter(function ($p) {
                                return optional($p->debt)->type === 'store_credit';
                            })->sum('amount');

                            $totalEfectivo = $pagosDelPeriodo->filter(function ($p) {
                                return optional($p->debt)->type !== 'store_credit';
                            })->sum('amount');
                        @endphp
                        <tfoot
                            class="bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-white border-t-2 border-gray-200 dark:border-gray-600 sticky bottom-0 z-10 shadow-xs">
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right bg-gray-50 dark:bg-gray-700 whitespace-nowrap">
                                    <span class="mr-4 text-purple-700 dark:text-purple-400">Mercancía:
                                        ${{ number_format($totalMercancia, 2) }}</span>
                                    <span class="mr-4 text-blue-700 dark:text-blue-400">Efectivo:
                                        ${{ number_format($totalEfectivo, 2) }}</span>
                                    TOTAL GENERAL:
                                </td>
                                <td
                                    class="px-4 py-3 text-right text-emerald-600 bg-gray-50 dark:bg-gray-700 whitespace-nowrap">
                                    ${{ number_format($totalCobrado, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Script JavaScript para generar y descargar el archivo de Excel optimizado -->
    <script>
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
                        <td colspan="6" class="titulo" style="font-size: 14pt; height: 35px;">REPORTE GENERAL DE ABONOS</td>
                    </tr>
                    <tr style="height: 10px;"></tr>
                    <tr style="height: 25px;">
                        <td class="header">Fecha</td>
                        <td class="header">Tipo de Crédito</td>
                        <td class="header">Cliente</td>
                        <td class="header">Dirección</td>
                        <td class="header">Concepto / Descripción</td>
                        <td class="header">Monto</td>
                    </tr>`;

            let sumaMonto = 0;

            filas.forEach(fila => {
                if (fila.style.display !== 'none') {
                    let celdas = fila.querySelectorAll('td');
                    let valMonto = parseFloat(fila.querySelector('.monto-total').getAttribute('data-valor')) || 0;

                    sumaMonto += valMonto;

                    tablaHtml += "<tr>";
                    tablaHtml += `<td class="celda centro">${celdas[0].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda centro">${celdas[1].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda texto">${celdas[2].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda texto">${celdas[3].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda texto">${celdas[4].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda numero" x:num="${valMonto}" style="font-weight: bold; mso-number-format:'\\$#,##0.00';">${valMonto}</td>`;
                    tablaHtml += "</tr>";
                }
            });

            tablaHtml += `<tr class="totales" style="height: 28px;">
                <td colspan="5" class="celda-vacia" style="text-align: right; font-weight: bold;">TOTAL GENERAL:</td>
                <td class="celda-vacia numero" x:num="${sumaMonto}" style="font-weight: bold; border-top: 1px solid #374151; border-bottom: 1px solid #374151; mso-number-format:'\\$#,##0.00';">${sumaMonto}</td>
            </tr>`;

            tablaHtml += `</table></body></html>`;

            let blob = new Blob([tablaHtml], { type: 'application/vnd.ms-excel;charset=utf-8;' });
            let downloadLink = document.createElement("a");
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = "reporte_abonos_" + new Date().toISOString().slice(0, 10) + ".xls";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
</x-app-layout>