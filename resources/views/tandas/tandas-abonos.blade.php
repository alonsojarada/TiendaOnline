<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                <span>🎯</span> Reporte de Cuotas Pagadas de Tandas
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Historial detallado de abonos y cuotas liquidadas</p>
        </div>
    </x-slot>

    <!-- Contenedor principal -->
    <div class="pt-0 pb-6 px-3 sm:px-4 lg:px-6 w-full max-w-full mx-auto flex flex-col h-[calc(100vh-140px)] overflow-x-hidden">

        <!-- TARJETA DE RESUMEN COMPACTA -->
        <div class="w-72 mb-3 shrink-0">
            <div class="bg-emerald-50/60 dark:bg-emerald-950/30 py-2.5 px-4 rounded-2xl shadow-xs border border-emerald-200/80 dark:border-emerald-800/60">
                <span class="text-[11px] font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300 block leading-tight">TOTAL COBRADO</span>
                <div class="text-2xl font-black text-emerald-950 dark:text-emerald-50 leading-none my-1">${{ number_format($totalCobrado, 2) }}</div>
                <span class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 leading-none">Ingresos reales en caja</span>
            </div>
        </div>

        <!-- CONTENEDOR UNIFICADO: FILTROS + TABLA -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col flex-1 min-h-0 w-full">

            <!-- BARRA DE FILTROS RESPONSIVA -->
            <div class="p-3 border-b border-gray-100 dark:border-gray-700/80 shrink-0">
                <form method="GET" action="{{ route('tandas.tandas.abonos') }}"
                    class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">

                    <!-- SECCIÓN DE CAMPOS DE FILTRO -->
                    <div class="flex flex-wrap items-center gap-2 w-full">
                        <div class="w-full sm:w-auto">
                            <select name="client_id"
                                class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer w-full sm:w-44">
                                <option value="">Todos los clientes</option>
                                <?php foreach($clientes as$client): ?>
                                    <option value="<?php echo $client->id; ?>" <?php echo (isset($clientId) && $clientId ==$client->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($client->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="w-full sm:w-auto flex items-center gap-1.5">
                            <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fechaInicio ?? ''); ?>"
                                class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full sm:w-36">
                            <span class="text-gray-400 text-xs hidden sm:inline">-</span>
                            <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fechaFin ?? ''); ?>"
                                class="bg-gray-50 dark:bg-gray-700/50 text-xs text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full sm:w-36">
                        </div>

                        <!-- Botón Buscar -->
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700/80 px-3.5 sm:px-4 py-2 text-xs font-bold rounded-full shadow-xs transition cursor-pointer">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Buscar</span>
                        </button>
                    </div>

                    <!-- SECCIÓN DE BOTÓN EXCEL -->
                    <div class="flex items-center justify-end w-full lg:w-auto shrink-0 pt-1 lg:pt-0 border-t lg:border-t-0 border-gray-100 dark:border-gray-700/50">
                        <button type="button" onclick="exportarExcel()"
                            class="inline-flex items-center justify-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700/80 px-4 py-2 text-xs font-bold rounded-full shadow-xs transition cursor-pointer w-full sm:w-auto">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span>Excel</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TABLA DE CUOTAS PAGADAS -->
            <div class="overflow-y-auto overflow-x-auto relative w-full flex-1">
                <table class="w-full text-left border-collapse min-w-[880px]">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-gray-50 dark:bg-gray-700 text-[11px] font-bold text-gray-500 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 shadow-xs">
                            <th class="py-3 px-4 w-[13%]">Fecha de Pago</th>
                            <th class="py-3 px-4 w-[13%]">Fecha Límite</th>
                            <th class="py-3 px-4 w-[16%]">Tanda</th>
                            <th class="py-3 px-4 w-[18%]">Cliente</th>
                            <th class="py-3 px-4 w-[9%]">Ciclo</th>
                            <th class="py-3 px-4 w-[11%] text-center">Estatus</th>
                            <th class="py-3 px-4 w-[11%]">Cobrado por</th>
                            <th class="py-3 px-4 w-[9%] text-right">Monto Pagado</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100 dark:divide-gray-700/50">
                        <?php if(!empty($abonos) && count($abonos) > 0): ?>
                            <?php foreach($abonos as$cuota): ?>
                                <?php 
                                    $valMonto =$cuota->monto_pagado ?? 0;
                                    $userName = optional($cuota->usuario)->name ?? 'N/A';
                                    $clientName = optional(optional($cuota->participante)->client)->name ?? 'General';
                                    $tandaName = optional($cuota->tanda)->name ?? ('Tanda #' . $cuota->tanda_id);
                                    $ciclo =$cuota->ciclo ?? 'N/A';
                                    $fechaFormateada = $cuota->fecha_pago ? \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y H:i') : 'N/A';
                                    
                                    // Ajusta aquí el campo correspondiente a tu base de datos para la fecha límite (ej. fecha_limite o fecha_programada)
                                    $fechaLimite = !empty($cuota->fecha_limite) ? \Carbon\Carbon::parse($cuota->fecha_limite)->format('d/m/Y') : (!empty($cuota->fecha_programada) ? \Carbon\Carbon::parse($cuota->fecha_programada)->format('d/m/Y') : 'N/A');
                                ?>
                                <tr class="fila-cuenta hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        <?php echo $fechaFormateada; ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        <?php echo $fechaLimite; ?>
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($tandaName); ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-700 dark:text-gray-300">
                                        <?php echo htmlspecialchars($clientName); ?>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 rounded-md">
                                            Ciclo <?php echo htmlspecialchars($ciclo); ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-950/60 dark:text-green-300 rounded-md">
                                            <?php echo htmlspecialchars(ucfirst($cuota->estado ?? '')); ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        <?php echo htmlspecialchars($userName); ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-black text-gray-900 dark:text-white monto-total whitespace-nowrap"
                                        data-valor="<?php echo $valMonto; ?>">
                                        $<?php echo number_format($valMonto, 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <div class="text-3xl mb-2">🏷️</div>
                                    <p class="font-medium">No se encontraron cuotas pagadas en este rango de fechas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script de Exportación a Excel -->
    <script>
        function exportarExcel() {
            let filas = document.querySelectorAll('.fila-cuenta');

            let tablaHtml = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset="UTF-8">
                <style>
                    .titulo { font-weight: bold; background-color: #059669; color: #ffffff; text-align: center; vertical-align: middle; }
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
                        <td colspan="8" class="titulo" style="font-size: 14pt; height: 35px;">REPORTE DE CUOTAS PAGADAS DE TANDAS</td>
                    </tr>
                    <tr style="height: 10px;"></tr>
                    <tr style="height: 25px;">
                        <td class="header">Fecha de Pago</td>
                        <td class="header">Fecha Límite</td>
                        <td class="header">Tanda</td>
                        <td class="header">Cliente</td>
                        <td class="header">Ciclo</td>
                        <td class="header">Estatus</td>
                        <td class="header">Cobrado por</td>
                        <td class="header">Monto Pagado</td>
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
                    tablaHtml += `<td class="celda centro">${celdas[4].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda centro">${celdas[5].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda texto">${celdas[6].innerText.trim()}</td>`;
                    tablaHtml += `<td class="celda numero" x:num="${valMonto}" style="font-weight: bold; mso-number-format:'\\$#,##0.00';">${valMonto}</td>`;
                    tablaHtml += "</tr>";
                }
            });

            tablaHtml += `<tr class="totales" style="height: 28px;">
                <td colspan="7" class="celda-vacia" style="text-align: right; font-weight: bold;">TOTAL COBRADO:</td>
                <td class="celda-vacia numero" x:num="${sumaMonto}" style="font-weight: bold; border-top: 1px solid #374151; border-bottom: 1px solid #374151; mso-number-format:'\\$#,##0.00';">${sumaMonto}</td>
            </tr>`;

            tablaHtml += `</table></body></html>`;

            let blob = new Blob([tablaHtml], { type: 'application/vnd.ms-excel;charset=utf-8;' });
            let downloadLink = document.createElement("a");
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = "reporte_cuotas_tandas_" + new Date().toISOString().slice(0, 10) + ".xls";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
</x-app-layout>