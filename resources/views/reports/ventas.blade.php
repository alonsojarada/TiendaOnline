<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                <span>📊</span> Reporte General de Ventas
            </h2>
        </div>
    </x-slot>

    <div class="pt-0 pb-6 px-3 sm:px-4 lg:px-6 w-full mx-auto space-y-6">

        <!-- ENCABEZADO Y FILTROS UNIFICADOS: Tarjetas a la izquierda y Búsqueda a la derecha -->
        <div
            class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700/80 mb-4">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                <!-- 1. Grupo de 3 Tarjetas de Resumen (Lado Izquierdo) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 w-full xl:w-auto shrink-0">
                    <!-- Tarjeta 1: Mercancía -->
                    <div
                        class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-2.5 rounded-xl shadow-xs relative overflow-hidden flex items-center justify-between min-w-[170px]">
                        <div>
                            <div class="flex items-center gap-1">
                                <span
                                    class="text-[9px] font-black uppercase tracking-wider text-emerald-100">Mercancía</span>
                                <span
                                    class="text-[8px] font-bold uppercase tracking-wider bg-emerald-700/40 px-1 py-0.5 rounded text-emerald-200">Store
                                    Credit</span>
                            </div>
                            <div class="text-sm font-black mt-0.5">${{ number_format($totalMercancia, 2) }}</div>
                        </div>
                        <div class="text-base opacity-75 ml-3">📦</div>
                    </div>

                    <!-- Tarjeta 2: Efectivo -->
                    <div
                        class="bg-gradient-to-br from-indigo-500 to-blue-600 text-white p-2.5 rounded-xl shadow-xs relative overflow-hidden flex items-center justify-between min-w-[170px]">
                        <div>
                            <div class="flex items-center gap-1">
                                <span
                                    class="text-[9px] font-black uppercase tracking-wider text-indigo-100">Efectivo</span>
                                <span
                                    class="text-[8px] font-bold uppercase tracking-wider bg-indigo-700/40 px-1 py-0.5 rounded text-indigo-200">Cash
                                    Loan</span>
                            </div>
                            <div class="text-sm font-black mt-0.5">${{ number_format($totalPrestamos, 2) }}</div>
                        </div>
                        <div class="text-base opacity-75 ml-3">💵</div>
                    </div>

                    <!-- Tarjeta 3: Venta Total -->
                    <div
                        class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white p-2.5 rounded-xl shadow-xs relative overflow-hidden border border-gray-800 flex items-center justify-between min-w-[170px]">
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="text-[9px] font-black uppercase tracking-wider text-gray-400">Venta
                                    Total</span>
                                <span
                                    class="text-[8px] font-bold uppercase tracking-wider bg-gray-800 px-1 py-0.5 rounded text-gray-300 border border-gray-700">Acumulado</span>
                            </div>
                            <div class="text-sm font-black mt-0.5">${{ number_format($granTotal, 2) }}</div>
                        </div>
                        <div class="text-base opacity-75 ml-3">📊</div>
                    </div>
                </div>

                <!-- 2. Filtros y Búsqueda (Lado Derecho) -->
                <form method="GET" action="{{ route('reports.ventas') }}"
                    class="flex flex-wrap items-center gap-2.5 justify-start xl:justify-end w-full xl:w-auto">
                    <!-- Selector de Cliente -->
                    <div class="w-full sm:w-auto">
                        <select name="client_id"
                            class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer w-full sm:w-44">
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

        <!-- TABLA UNIFICADA CRONOLÓGICA CON ESTADO DE LIQUIDACIÓN Y SCROLL INTERNO -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">

            <div
                class="p-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white dark:bg-gray-800 z-10">
                <div>
                    <h3
                        class="text-xs font-black uppercase tracking-wider text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                        <span>⏱️</span> Historial de ventas y creditos
                    </h3>
                </div>

                <!-- Botón Exportar Excel Modificado para invocar la función JS -->
                <div class="flex items-center justify-end w-full sm:w-auto">
                    <button type="button" onclick="exportarExcel()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-xs font-bold rounded-xl shadow-xs transition cursor-pointer">
                        <span>📥</span> Exportar Excel
                    </button>
                </div>
            </div>

            <!-- Contenedor con Scroll Interno -->
            <div class="max-h-[500px] overflow-y-auto overflow-x-auto relative">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-10">
                        <tr
                            class="bg-gray-50 dark:bg-gray-700 text-[11px] font-bold text-gray-500 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 shadow-xs">
                            <th class="py-3 px-4">Fecha</th>
                            <th class="py-3 px-4">Tipo</th>
                            <th class="py-3 px-4">Cliente</th>
                            <th class="py-3 px-4">Dirección</th>
                            <th class="py-3 px-4">Concepto / Descripción</th>
                            <th class="py-3 px-4 text-center">Estado</th>
                            <th class="py-3 px-4">Usuario</th> <!-- Nueva columna de Usuario -->
                            <th class="py-3 px-4 text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100 dark:divide-gray-700/50">
                        @php
                            $movimientos = $ventasMercancia->concat($prestamosEfectivo)->sortByDesc('created_at');
                        @endphp

                        @forelse($movimientos as $item)
                            @php
                                $clientAddress = optional($item->client)->address;
                                $userName = optional($item->user)->name ?? 'N/A'; // Obtiene el nombre del usuario relacionado
                                $valMonto = $item->total_amount ?? 0;
                            @endphp
                            <tr class="fila-cuenta hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                                <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($item->type === 'store_credit')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            📦 Mercancía
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300">
                                            💵 Efectivo
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-bold text-gray-900 dark:text-white">
                                    {{ $item->client->name ?? 'General' }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 dark:text-gray-400">
                                    {{ $clientAddress ?: 'S/D' }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-700 dark:text-gray-300">
                                    {{ '#' . $item->id }} -
                                    {{ $item->concept ?? ($item->type === 'cash_loan' ? 'Préstamo en efectivo' : 'Venta de mercancía') }}
                                </td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if(isset($item->status) && $item->status === 'paid')
                                        <span
                                            class="px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-950/60 dark:text-green-300 rounded-md">
                                            Liquidado
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 rounded-md">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <!-- Celda de Usuario -->
                                <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">
                                    {{ $userName }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-black text-gray-900 dark:text-white monto-total"
                                    data-valor="{{ $valMonto }}">
                                    ${{ number_format($valMonto, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <div class="text-3xl mb-2">📂</div>
                                    <p class="font-medium">No se encontraron movimientos registrados con estos filtros.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script de Exportación a Excel adaptado a las columnas de esta vista -->
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
                        <td colspan="7" class="titulo" style="font-size: 14pt; height: 35px;">REPORTE GENERAL DE VENTAS</td>
                    </tr>
                    <tr style="height: 10px;"></tr>
                    <tr style="height: 25px;">
                        <td class="header">Fecha</td>
                        <td class="header">Tipo</td>
                        <td class="header">Cliente</td>
                        <td class="header">Dirección</td>
                        <td class="header">Concepto / Descripción</td>
                        <td class="header">Estado</td>
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
                    tablaHtml += `<td class="celda centro">${celdas[5].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda numero" x:num="${valMonto}" style="font-weight: bold; mso-number-format:'\\$#,##0.00';">${valMonto}</td>`;
                    tablaHtml += "</tr>";
                }
            });

            tablaHtml += `<tr class="totales" style="height: 28px;">
                <td colspan="6" class="celda-vacia" style="text-align: right; font-weight: bold;">TOTAL GENERAL:</td>
                <td class="celda-vacia numero" x:num="${sumaMonto}" style="font-weight: bold; border-top: 1px solid #374151; border-bottom: 1px solid #374151; mso-number-format:'\\$#,##0.00';">${sumaMonto}</td>
            </tr>`;

            tablaHtml += `</table></body></html>`;

            let blob = new Blob([tablaHtml], { type: 'application/vnd.ms-excel;charset=utf-8;' });
            let downloadLink = document.createElement("a");
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = "reporte_ventas_" + new Date().toISOString().slice(0, 10) + ".xls";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
</x-app-layout>