<x-app-layout>

    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                <span>📊</span> Reporte General de Abonos
            </h2>
        </div>
    </x-slot>


    <div class="pt-0 pb-6 px-2 sm:px-3 md:px-4 lg:px-6 w-full mx-auto space-y-4 sm:space-y-5 lg:space-y-6">

        <!-- ========================================================= -->
        <!-- CONTENEDOR GENERAL: TARJETAS + FILTROS -->
        <!-- ========================================================= -->

        <div class="bg-white dark:bg-gray-800
                   p-2 sm:p-3 md:p-3 lg:p-4
                   rounded-2xl shadow-xs
                   border border-gray-100 dark:border-gray-700/80
                   mb-3 sm:mb-4">

            <div class="flex flex-col xl:flex-row
                       xl:items-center
                       xl:justify-between
                       gap-2 sm:gap-3 lg:gap-4">


                @php

                    $totalMercancia = $pagosDelPeriodo->filter(function ($p) {
                        return optional($p->debt)->type === 'store_credit';
                    })->sum('amount');

                    $totalEfectivo = $pagosDelPeriodo->filter(function ($p) {
                        return optional($p->debt)->type !== 'store_credit';
                    })->sum('amount');

                    $totalGeneral = $pagosDelPeriodo->sum('amount');

                @endphp


                <!-- ================================================= -->
                <!-- 1. TARJETAS DE TOTALES -->
                <!-- ================================================= -->

                <div class="grid grid-cols-3
                           gap-1 sm:gap-1.5 md:gap-2
                           w-full xl:w-auto
                           shrink-0">


                    <!-- ========================= -->
                    <!-- TARJETA MERCANCÍA -->
                    <!-- ========================= -->

                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600
                               text-white
                               p-1.5 sm:p-2 md:p-2.5
                               rounded-lg sm:rounded-xl
                               shadow-xs
                               relative overflow-hidden
                               flex items-center justify-between
                               min-w-0">

                        <div class="min-w-0">

                            <div class="flex items-center gap-0.5 sm:gap-1 min-w-0">

                                <span class="text-[7px] sm:text-[8px] md:text-[9px]
                                           font-black uppercase tracking-wide
                                           text-emerald-100 truncate">
                                    Mercancía
                                </span>

                                <span class="hidden sm:inline-block
                                           text-[6px] md:text-[8px]
                                           font-bold uppercase tracking-wide
                                           bg-emerald-800/80
                                           px-1 py-0.5 rounded
                                           text-emerald-200
                                           whitespace-nowrap">
                                    Store Credit
                                </span>

                            </div>


                            <div class="text-[10px] sm:text-xs md:text-sm
                                       font-black mt-0.5
                                       truncate">

                                ${{ number_format($totalMercancia, 2) }}

                            </div>

                        </div>


                        <div class="text-[11px] sm:text-sm md:text-base
                                   opacity-80
                                   ml-1
                                   shrink-0">

                            📦

                        </div>

                    </div>


                    <!-- ========================= -->
                    <!-- TARJETA EFECTIVO -->
                    <!-- ========================= -->

                    <div class="bg-gradient-to-r from-indigo-600 to-blue-600
                               text-white
                               p-1.5 sm:p-2 md:p-2.5
                               rounded-lg sm:rounded-xl
                               shadow-xs
                               relative overflow-hidden
                               flex items-center justify-between
                               min-w-0">

                        <div class="min-w-0">

                            <div class="flex items-center gap-0.5 sm:gap-1 min-w-0">

                                <span class="text-[7px] sm:text-[8px] md:text-[9px]
                                           font-black uppercase tracking-wide
                                           text-indigo-100 truncate">
                                    Efectivo
                                </span>

                                <span class="hidden sm:inline-block
                                           text-[6px] md:text-[8px]
                                           font-bold uppercase tracking-wide
                                           bg-indigo-800/80
                                           px-1 py-0.5 rounded
                                           text-indigo-200
                                           whitespace-nowrap">
                                    Cash Loan
                                </span>

                            </div>


                            <div class="text-[10px] sm:text-xs md:text-sm
                                       font-black mt-0.5
                                       truncate">

                                ${{ number_format($totalEfectivo, 2) }}

                            </div>

                        </div>


                        <div class="text-[11px] sm:text-sm md:text-base
                                   opacity-80
                                   ml-1
                                   shrink-0">

                            💵

                        </div>

                    </div>


                    <!-- ========================= -->
                    <!-- TARJETA VENTA TOTAL -->
                    <!-- ========================= -->

                    <div class="bg-gray-900 dark:bg-gray-900
                               text-white
                               p-1.5 sm:p-2 md:p-2.5
                               rounded-lg sm:rounded-xl
                               shadow-xs
                               relative overflow-hidden
                               flex items-center justify-between
                               min-w-0">

                        <div class="min-w-0">

                            <div class="flex items-center gap-0.5 sm:gap-1 min-w-0">

                                <span class="text-[7px] sm:text-[8px] md:text-[9px]
                                           font-black uppercase tracking-wide
                                           text-gray-300 truncate">
                                    Venta Total
                                </span>

                                <span class="hidden sm:inline-block
                                           text-[6px] md:text-[8px]
                                           font-bold uppercase tracking-wide
                                           bg-gray-800
                                           px-1 py-0.5 rounded
                                           text-gray-300
                                           whitespace-nowrap">
                                    Acumulado
                                </span>

                            </div>


                            <div class="text-[10px] sm:text-xs md:text-sm
                                       font-black mt-0.5
                                       truncate">

                                ${{ number_format($totalGeneral, 2) }}

                            </div>

                        </div>


                        <div class="text-[11px] sm:text-sm md:text-base
                                   opacity-80
                                   ml-1
                                   shrink-0">

                            📊

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- 2. FILTROS Y BÚSQUEDA -->
                <!-- ================================================= -->

                <form method="GET" action="{{ route('reports.abonos') }}" class="w-full xl:w-auto
                           flex items-center
                           gap-1 sm:gap-1.5 md:gap-2
                           min-w-0">


                    <!-- ========================= -->
                    <!-- CLIENTE -->
                    <!-- ========================= -->

                    <div class="flex-[1.4] min-w-0">

                        <select name="client_id" class="bg-gray-50 dark:bg-gray-700/50
                                   text-[9px] sm:text-[10px] md:text-xs
                                   text-gray-800 dark:text-gray-100
                                   border border-gray-200 dark:border-gray-600
                                   rounded-lg sm:rounded-xl
                                   px-1.5 sm:px-2 md:px-3
                                   py-2 sm:py-2.5
                                   focus:ring-2 focus:ring-indigo-500
                                   focus:outline-none
                                   cursor-pointer
                                   w-full
                                   min-w-0">

                            <option value="">
                                Todos los clientes
                            </option>

                            @foreach($clientes as $client)

                                <option value="{{ $client->id }}" {{ (isset($clientId) && $clientId == $client->id) ? 'selected' : '' }}>

                                    {{ $client->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- ========================= -->
                    <!-- FECHA INICIO -->
                    <!-- ========================= -->

                    <div class="flex-1 min-w-0">

                        <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="bg-gray-50 dark:bg-gray-700/50
                                   text-[9px] sm:text-[10px] md:text-xs
                                   text-gray-800 dark:text-gray-100
                                   border border-gray-200 dark:border-gray-600
                                   rounded-lg sm:rounded-xl
                                   px-1 sm:px-2 md:px-3
                                   py-2 sm:py-2.5
                                   focus:ring-2 focus:ring-indigo-500
                                   focus:outline-none
                                   w-full
                                   min-w-0">

                    </div>


                    <!-- ========================= -->
                    <!-- FECHA FIN -->
                    <!-- ========================= -->

                    <div class="flex-1 min-w-0">

                        <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="bg-gray-50 dark:bg-gray-700/50
                                   text-[9px] sm:text-[10px] md:text-xs
                                   text-gray-800 dark:text-gray-100
                                   border border-gray-200 dark:border-gray-600
                                   rounded-lg sm:rounded-xl
                                   px-1 sm:px-2 md:px-3
                                   py-2 sm:py-2.5
                                   focus:ring-2 focus:ring-indigo-500
                                   focus:outline-none
                                   w-full
                                   min-w-0">

                    </div>


                    <!-- ========================= -->
                    <!-- BOTÓN CONSULTAR -->
                    <!-- ========================= -->

                    <div class="flex-none sm:flex-1 min-w-0">

                        <button type="submit" class="w-full
               inline-flex
               items-center
               justify-center
               gap-1
               bg-indigo-600
               hover:bg-indigo-700
               text-white
               px-3 sm:px-2 md:px-4
               py-2 sm:py-2.5
               text-[9px] sm:text-[10px] md:text-xs
               font-bold
               rounded-lg sm:rounded-xl
               shadow-xs
               transition
               whitespace-nowrap">

                            <!-- ICONO: siempre visible -->
                            <span>🔍</span>

                            <!-- TEXTO: oculto en móvil, visible desde sm -->
                            <span class="hidden sm:inline">
                                Consultar
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- TABLA DE HISTORIAL COMBINADO DE MOVIMIENTOS -->
        <!-- ========================================================= -->

        <div class="bg-white dark:bg-gray-800
                   rounded-2xl shadow-xs
                   border border-gray-200 dark:border-gray-700
                   overflow-hidden
                   flex flex-col">


            <!--=====================================================-->
            <!-- CABECERA DE TABLA -->
            <!-- SIEMPRE EN UNA SOLA LÍNEA -->
            <!-- ===================================================== -->

            <div class="p-3 sm:p-4
           border-b border-gray-100 dark:border-gray-700
           flex flex-row
           items-center
           justify-between
           gap-2
           bg-white dark:bg-gray-800
           z-10
           min-w-0">

                <!-- TÍTULO -->
                <div class="min-w-0 flex-1 overflow-hidden">

                    <h3 class="text-[10px] sm:text-xs
                   font-black uppercase
                   tracking-wider
                   text-gray-800 dark:text-gray-200
                   flex items-center
                   gap-1.5
                   whitespace-nowrap
                   overflow-hidden
                   text-ellipsis">

                        <span class="shrink-0">⏱️</span>

                        <span class="truncate">
                            Historial de abonos y pagos registrados
                        </span>

                    </h3>

                </div>


                <!-- BOTÓN EXPORTAR -->
                <div class="shrink-0">

                    <button type="button" onclick="exportarExcel()" class="inline-flex
                   items-center
                   justify-center
                   gap-1 sm:gap-2
                   bg-emerald-600
                   hover:bg-emerald-700
                   text-white
                   px-2.5 sm:px-4
                   py-2
                   text-[9px] sm:text-xs
                   font-bold
                   rounded-xl
                   shadow-xs
                   transition
                   cursor-pointer
                   whitespace-nowrap">

                        <span>📥</span>

                        <span>
                            Exportar Excel
                        </span>

                    </button>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- CONTENEDOR DE TABLA RESPONSIVE -->
            <!-- ========================================================= -->

            <div class="overflow-y-auto
           overflow-x-hidden
           max-h-[550px]
           xl:h-[calc(100vh-300px)]
           bg-white dark:bg-gray-800
           relative">

                <table class="w-full
               divide-y divide-gray-200 dark:divide-gray-700
               text-left
               text-xs
               table-fixed">


                    <!-- ================================================= -->
                    <!-- CABECERA -->
                    <!-- ================================================= -->

                    <thead class="bg-gray-50 dark:bg-gray-700
                   text-gray-700 dark:text-gray-300
                   uppercase
                   sticky top-0
                   z-10
                   shadow-xs">

                        <tr>

                            <!-- FECHA -->
                            <th class="px-2 sm:px-4
                           py-2.5 sm:py-3
                           bg-gray-50 dark:bg-gray-700
                           w-[24%] sm:w-auto
                           text-[11px] sm:text-xs">

                                Fecha

                            </th>


                            <!-- TIPO DE CRÉDITO (Visible solo en pantallas grandes lg+) -->
                            <th class="hidden lg:table-cell
                           px-4 py-3
                           bg-gray-50 dark:bg-gray-700">

                                Tipo de Crédito

                            </th>


                            <!-- CLIENTE -->
                            <th class="px-2 sm:px-4
                           py-2.5 sm:py-3
                           bg-gray-50 dark:bg-gray-700
                           w-[28%] sm:w-auto
                           text-[11px] sm:text-xs">

                                Cliente

                            </th>


                            <!-- DIRECCIÓN -->
                            <th class="hidden sm:table-cell
                           px-4 py-3
                           bg-gray-50 dark:bg-gray-700">

                                Dirección

                            </th>


                            <!-- CONCEPTO -->
                            <th class="px-2 sm:px-4
                           py-2.5 sm:py-3
                           bg-gray-50 dark:bg-gray-700
                           w-[31%] sm:w-auto
                           text-[11px] sm:text-xs">

                                Concepto

                            </th>


                            <!-- REGISTRADO POR (Visible solo en pantallas grandes lg+) -->
                            <th class="hidden lg:table-cell
                           px-4 py-3
                           bg-gray-50 dark:bg-gray-700">

                                Registrado por

                            </th>


                            <!-- MONTO -->
                            <th class="px-2 sm:px-4
                           py-2.5 sm:py-3
                           text-right
                           bg-gray-50 dark:bg-gray-700
                           w-[17%] sm:w-auto
                           text-[11px] sm:text-xs">

                                Monto

                            </th>

                        </tr>

                    </thead>


                    <!-- ================================================= -->
                    <!-- CUERPO -->
                    <!-- ================================================= -->

                    <tbody class="divide-y divide-gray-200
                   dark:divide-gray-700
                   text-gray-600
                   dark:text-gray-300">


                        @forelse($pagosDelPeriodo as $payment)

                                            @php

                                                $client = optional($payment->debt)->client;

                                                $clientName = $client
                                                    ? trim($client->name . ' ' . $client->alias)
                                                    : 'Cliente General';

                                                $clientAddress = $client && $client->address
                                                    ? $client->address
                                                    : 'S/D';

                                                $debtType = optional($payment->debt)->type;

                                                $isStoreCredit = $debtType === 'store_credit';

                                                $tipoTexto = $isStoreCredit
                                                    ? 'Mercancía'
                                                    : 'Crédito Efectivo';

                                                $badgeClass = $isStoreCredit
                                                    ? 'text-purple-700 bg-purple-100 dark:bg-purple-900/30 dark:text-purple-400'
                                                    : 'text-blue-700 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400';

                                                $valMonto = $payment->amount ?? 0;

                                                $userName = optional($payment->user)->name ?? 'N/A';

                                            @endphp


                                            <tr class="fila-cuenta
                                               hover:bg-gray-50
                                               dark:hover:bg-gray-700/50
                                               transition">


                                                <!-- FECHA -->

                                                <td class="px-2 sm:px-4
                                                   py-2.5 sm:py-3
                                                   whitespace-nowrap
                                                   text-[10px] sm:text-xs">

                                                    {{ $payment->created_at
                            ? $payment->created_at->format('d/m/Y H:i')
                            : 'N/A' }}

                                                </td>


                                                <!-- TIPO DE CRÉDITO -->

                                                <td class="hidden lg:table-cell
                                                   px-4 py-3
                                                   whitespace-nowrap">

                                                    <span class="px-2 py-1
                                                       text-xs
                                                       font-semibold
                                                       rounded-full
                                                       {{ $badgeClass }}">

                                                        {{ $tipoTexto }}

                                                    </span>

                                                </td>


                                                <!-- CLIENTE -->

                                                <td class="px-2 sm:px-4
                                                   py-2.5 sm:py-3
                                                   font-bold
                                                   text-gray-900 dark:text-white
                                                   overflow-hidden">

                                                    <div class="truncate
                                                       text-[11px] sm:text-xs" title="{{ $clientName }}">

                                                        {{ $clientName }}

                                                    </div>

                                                </td>


                                                <!-- DIRECCIÓN -->

                                                <td class="hidden sm:table-cell
                                                   px-4 py-3
                                                   text-gray-600 dark:text-gray-400
                                                   whitespace-nowrap">

                                                    {{ $clientAddress }}

                                                </td>


                                                <!-- CONCEPTO -->

                                                <td class="px-2 sm:px-4
                                                   py-2.5 sm:py-3
                                                   font-medium
                                                   text-gray-700 dark:text-gray-300
                                                   overflow-hidden">

                                                    <div class="truncate
                                                       text-[11px] sm:text-xs"
                                                        title="{{ '#' . $payment->debt_id . ' - ' . optional($payment->debt)->concept }}">

                                                        {{ '#' . $payment->debt_id }}
                                                        -
                                                        {{ optional($payment->debt)->concept }}

                                                    </div>

                                                </td>


                                                <!-- REGISTRADO POR -->

                                                <td class="hidden lg:table-cell
                                                   px-4 py-3
                                                   text-gray-600 dark:text-gray-400
                                                   whitespace-nowrap">

                                                    {{ $userName }}

                                                </td>


                                                <!-- MONTO -->

                                                <td class="px-2 sm:px-4
                                                   py-2.5 sm:py-3
                                                   text-right
                                                   font-bold
                                                   text-gray-900 dark:text-white
                                                   whitespace-nowrap
                                                   monto-total
                                                   text-[11px] sm:text-xs" data-valor="{{ $valMonto }}">

                                                    ${{ number_format($valMonto, 2) }}

                                                </td>

                                            </tr>


                        @empty

                            <tr>

                                <!-- Móvil (< sm) -->
                                <td colspan="4" class="sm:hidden
                                   px-4 py-8
                                   text-center
                                   text-gray-500
                                   text-xs">

                                    No se encontraron abonos registrados en el sistema con los filtros seleccionados.

                                </td>

                                <!-- Tablet (sm a md) -->
                                <td colspan="5" class="hidden sm:table-cell lg:hidden
                                   px-4 py-8
                                   text-center
                                   text-gray-500
                                   text-xs">

                                    No se encontraron abonos registrados en el sistema con los filtros seleccionados.

                                </td>

                                <!-- Escritorio (lg+) -->
                                <td colspan="7" class="hidden lg:table-cell
                                   px-4 py-8
                                   text-center
                                   text-gray-500">

                                    No se encontraron abonos registrados en el sistema con los filtros seleccionados.

                                </td>

                            </tr>

                        @endforelse


                    </tbody>


                    <!-- ================================================= -->
                    <!-- FOOTER DE TOTALES -->
                    <!-- ================================================= -->

                    @if($pagosDelPeriodo->isNotEmpty())

                        @php

                            $totalMercancia = $pagosDelPeriodo->filter(function ($p) {
                                return optional($p->debt)->type === 'store_credit';
                            })->sum('amount');

                            $totalEfectivo = $pagosDelPeriodo->filter(function ($p) {
                                return optional($p->debt)->type !== 'store_credit';
                            })->sum('amount');

                        @endphp


                        <tfoot class="hidden sm:table-footer-group bg-gray-50 dark:bg-gray-700
                           font-bold
                           text-gray-900 dark:text-white
                           border-t-2
                           border-gray-200 dark:border-gray-600
                           sticky bottom-0
                           z-10
                           shadow-xs">

                            <!-- Fila para Tablet (sm y md) - 5 columnas visibles -->
                            <tr class="lg:hidden">

                                <td colspan="4" class="px-3 sm:px-4 py-3
                                   text-right
                                   bg-gray-50 dark:bg-gray-700
                                   whitespace-nowrap">

                                    <span class="mr-4
                                       text-purple-700
                                       dark:text-purple-400">

                                        Mercancía:
                                        ${{ number_format($totalMercancia, 2) }}

                                    </span>

                                    <span class="mr-4
                                       text-blue-700
                                       dark:text-blue-400">

                                        Efectivo:
                                        ${{ number_format($totalEfectivo, 2) }}

                                    </span>

                                    TOTAL GENERAL:

                                </td>

                                <td class="px-3 sm:px-4 py-3
                                   text-right
                                   text-emerald-600
                                   bg-gray-50 dark:bg-gray-700
                                   whitespace-nowrap">

                                    ${{ number_format($totalCobrado, 2) }}

                                </td>

                            </tr>

                            <!-- Fila para Escritorio (lg+) - 7 columnas visibles -->
                            <tr class="hidden lg:table-row">

                                <td colspan="6" class="px-3 sm:px-4 py-3
                                   text-right
                                   bg-gray-50 dark:bg-gray-700
                                   whitespace-nowrap">

                                    <span class="mr-4
                                       text-purple-700
                                       dark:text-purple-400">

                                        Mercancía:
                                        ${{ number_format($totalMercancia, 2) }}

                                    </span>

                                    <span class="mr-4
                                       text-blue-700
                                       dark:text-blue-400">

                                        Efectivo:
                                        ${{ number_format($totalEfectivo, 2) }}

                                    </span>

                                    TOTAL GENERAL:

                                </td>

                                <td class="px-3 sm:px-4 py-3
                                   text-right
                                   text-emerald-600
                                   bg-gray-50 dark:bg-gray-700
                                   whitespace-nowrap">

                                    ${{ number_format($totalCobrado, 2) }}

                                </td>

                            </tr>

                        </tfoot>

                    @endif


                </table>

            </div>

        </div>

    </div>


    <!-- ============================================================= -->
    <!-- JAVASCRIPT PARA EXPORTAR EXCEL -->
    <!-- ============================================================= -->

    <script>

        function exportarExcel() {

            let filas = document.querySelectorAll('.fila-cuenta');


            let tablaHtml = `<html xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:x="urn:schemas-microsoft-com:office:excel"
                xmlns="http://www.w3.org/TR/REC-html40">

            <head>

                <meta charset="UTF-8">

                <style>

                    .titulo {
                        font-weight: bold;
                        background-color: #4f46e5;
                        color: #ffffff;
                        text-align: center;
                        vertical-align: middle;
                    }

                    .header {
                        font-weight: bold;
                        background-color: #f3f4f6;
                        color: #374151;
                        border: 1px solid #d1d5db;
                        text-align: center;
                        vertical-align: middle;
                    }

                    .celda {
                        border: 1px solid #d1d5db;
                        vertical-align: middle;
                        padding: 4px;
                    }

                    .texto {
                        text-align: left;
                    }

                    .numero {
                        text-align: right;
                        mso-number-format:"\\$#,##0.00";
                    }

                    .centro {
                        text-align: center;
                    }

                    .totales {
                        font-weight: bold;
                    }

                    .celda-vacia {
                        border: none;
                        background: transparent;
                    }

                </style>

            </head>

            <body>

                <table>

                    <tr>

                        <td
                            colspan="7"
                            class="titulo"
                            style="font-size: 14pt; height: 35px;">

                            REPORTE GENERAL DE ABONOS

                        </td>

                    </tr>


                    <tr style="height: 10px;"></tr>


                    <tr style="height: 25px;">

                        <td class="header">
                            Fecha
                        </td>

                        <td class="header">
                            Tipo de Crédito
                        </td>

                        <td class="header">
                            Cliente
                        </td>

                        <td class="header">
                            Dirección
                        </td>

                        <td class="header">
                            Concepto / Descripción
                        </td>

                        <td class="header">
                            Registrado por
                        </td>

                        <td class="header">
                            Monto
                        </td>

                    </tr>`;


            let sumaMonto = 0;


            filas.forEach(fila => {

                if (fila.style.display !== 'none') {

                    let celdas = fila.querySelectorAll('td');

                    let valMonto =
                        parseFloat(
                            fila.querySelector('.monto-total')
                                .getAttribute('data-valor')
                        ) || 0;


                    sumaMonto += valMonto;


                    tablaHtml += "<tr>";

                    tablaHtml += `
                        <td class="celda centro">
                            ${celdas[0].innerText.trim()}
                        </td>`;

                    tablaHtml += `
                        <td class="celda centro">
                            ${celdas[1].innerText.trim()}
                        </td>`;

                    tablaHtml += `
                        <td class="celda texto">
                            ${celdas[2].innerText.trim()}
                        </td>`;

                    tablaHtml += `
                        <td class="celda texto">
                            ${celdas[3].innerText.trim()}
                        </td>`;

                    tablaHtml += `
                        <td class="celda texto">
                            ${celdas[4].innerText.trim()}
                        </td>`;

                    tablaHtml += `
                        <td class="celda texto">
                            ${celdas[5].innerText.trim()}
                        </td>`;

                    tablaHtml += `
                        <td
                            class="celda numero"
                            x:num="${valMonto}"
                            style="font-weight: bold;
                                   mso-number-format:'\\$#,##0.00';">

                            ${valMonto}

                        </td>`;

                    tablaHtml += "</tr>";

                }

            });


            tablaHtml += `

                <tr
                    class="totales"
                    style="height: 28px;">

                    <td
                        colspan="6"
                        class="celda-vacia"
                        style="text-align: right;
                               font-weight: bold;">

                        TOTAL GENERAL:

                    </td>

                    <td
                        class="celda-vacia numero"
                        x:num="${sumaMonto}"
                        style="font-weight: bold;
                               border-top: 1px solid #374151;
                               border-bottom: 1px solid #374151;
                               mso-number-format:'\\$#,##0.00';">

                        ${sumaMonto}

                    </td>

                </tr>`;


            tablaHtml += `
                </table>
                </body>
                </html>`;


            let blob = new Blob(
                [tablaHtml],
                {
                    type: 'application/vnd.ms-excel;charset=utf-8;'
                }
            );


            let downloadLink = document.createElement("a");


            downloadLink.href =
                window.URL.createObjectURL(blob);


            downloadLink.download =
                "reporte_abonos_" +
                new Date().toISOString().slice(0, 10) +
                ".xls";


            document.body.appendChild(downloadLink);


            downloadLink.click();


            document.body.removeChild(downloadLink);

        }

    </script>

</x-app-layout>