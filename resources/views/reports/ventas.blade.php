
<x-app-layout>

    <!-- ========================================================= -->
    <!-- TÍTULO DE LA PÁGINA -->
    <!-- ========================================================= -->

    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                <span>📊</span> Reporte General de Ventas
            </h2>

        </div>
    </x-slot>


    <!-- ========================================================= -->
    <!-- CONTENEDOR PRINCIPAL -->
    <!-- ========================================================= -->

    <div class="pt-0 pb-6 px-3 sm:px-4 lg:px-6 w-full mx-auto space-y-6">


        <!-- ===================================================== -->
        <!-- RESUMEN + FILTROS -->
        <!-- ===================================================== -->

        <div class="bg-white dark:bg-gray-800
                    p-2.5 sm:p-3 md:p-2.5 lg:p-3 xl:p-4
                    rounded-2xl shadow-xs
                    border border-gray-100 dark:border-gray-700/80
                    mb-4
                    contenedor-reporte-header">


            <div class="flex flex-col
                        md:flex-row
                        md:items-center
                        md:justify-between
                        gap-2 md:gap-2 lg:gap-3 xl:gap-4
                        reporte-header-interno">


                <!-- ================================================= -->
                <!-- TARJETAS DE RESUMEN -->
                <!-- ================================================= -->

                <div class="grid grid-cols-3
                            gap-1.5 sm:gap-2 md:gap-1.5 lg:gap-2 xl:gap-2.5
                            w-full md:w-auto
                            flex-1 md:flex-none
                            min-w-0
                            tarjetas-resumen">


                    <!-- ============================= -->
                    <!-- MERCANCÍA -->
                    <!-- ============================= -->

                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600
                                text-white
                                p-2 sm:p-2.5 md:p-1.5 lg:p-2 xl:p-2.5
                                rounded-xl shadow-xs
                                relative overflow-hidden
                                flex items-center justify-between
                                min-w-0">

                        <div class="min-w-0">

                            <div class="flex items-center gap-1 min-w-0">

                                <span class="text-[8px] sm:text-[9px] md:text-[7px] lg:text-[8px] xl:text-[9px]
                                             font-black uppercase tracking-wider
                                             text-emerald-100 truncate">
                                    Mercancía
                                </span>

                                <span class="hidden lg:inline-block
                                             text-[7px] xl:text-[8px]
                                             font-bold uppercase tracking-wider
                                             bg-emerald-700/40
                                             px-1 py-0.5 rounded
                                             text-emerald-200 whitespace-nowrap">
                                    Store Credit
                                </span>

                            </div>

                            <div class="text-xs sm:text-sm md:text-[11px] lg:text-xs xl:text-sm
                                        font-black mt-0.5 whitespace-nowrap">

                                ${{ number_format($totalMercancia, 2) }}

                            </div>

                        </div>


                        <div class="text-sm sm:text-base md:text-xs lg:text-sm xl:text-base
                                    opacity-75 ml-1 lg:ml-2 shrink-0">
                            📦
                        </div>

                    </div>


                    <!-- ============================= -->
                    <!-- EFECTIVO -->
                    <!-- ============================= -->

                    <div class="bg-gradient-to-br from-indigo-500 to-blue-600
                                text-white
                                p-2 sm:p-2.5 md:p-1.5 lg:p-2 xl:p-2.5
                                rounded-xl shadow-xs
                                relative overflow-hidden
                                flex items-center justify-between
                                min-w-0">

                        <div class="min-w-0">

                            <div class="flex items-center gap-1 min-w-0">

                                <span class="text-[8px] sm:text-[9px] md:text-[7px] lg:text-[8px] xl:text-[9px]
                                             font-black uppercase tracking-wider
                                             text-indigo-100 truncate">
                                    Efectivo
                                </span>

                                <span class="hidden lg:inline-block
                                             text-[7px] xl:text-[8px]
                                             font-bold uppercase tracking-wider
                                             bg-indigo-700/40
                                             px-1 py-0.5 rounded
                                             text-indigo-200 whitespace-nowrap">
                                    Cash Loan
                                </span>

                            </div>

                            <div class="text-xs sm:text-sm md:text-[11px] lg:text-xs xl:text-sm
                                        font-black mt-0.5 whitespace-nowrap">

                                ${{ number_format($totalPrestamos, 2) }}

                            </div>

                        </div>


                        <div class="text-sm sm:text-base md:text-xs lg:text-sm xl:text-base
                                    opacity-75 ml-1 lg:ml-2 shrink-0">
                            💵
                        </div>

                    </div>


                    <!-- ============================= -->
                    <!-- VENTA TOTAL -->
                    <!-- ============================= -->

                    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900
                                text-white
                                p-2 sm:p-2.5 md:p-1.5 lg:p-2 xl:p-2.5
                                rounded-xl shadow-xs
                                relative overflow-hidden
                                border border-gray-800
                                flex items-center justify-between
                                min-w-0">

                        <div class="min-w-0">

                            <div class="flex items-center gap-1 min-w-0">

                                <span class="text-[8px] sm:text-[9px] md:text-[7px] lg:text-[8px] xl:text-[9px]
                                             font-black uppercase tracking-wider
                                             text-gray-400 truncate">
                                    Venta Total
                                </span>

                                <span class="hidden lg:inline-block
                                             text-[7px] xl:text-[8px]
                                             font-bold uppercase tracking-wider
                                             bg-gray-800
                                             px-1 py-0.5 rounded
                                             text-gray-300
                                             border border-gray-700
                                             whitespace-nowrap">
                                    Acumulado
                                </span>

                            </div>

                            <div class="text-xs sm:text-sm md:text-[11px] lg:text-xs xl:text-sm
                                        font-black mt-0.5 whitespace-nowrap">

                                ${{ number_format($granTotal, 2) }}

                            </div>

                        </div>


                        <div class="text-sm sm:text-base md:text-xs lg:text-sm xl:text-base
                                    opacity-75 ml-1 lg:ml-2 shrink-0">
                            📊
                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- FILTROS -->
                <!-- ================================================= -->

                <form method="GET"
                      action="{{ route('reports.ventas') }}"
                      class="flex items-center
                             gap-1.5 sm:gap-2 md:gap-1.5 lg:gap-2 xl:gap-2.5
                             w-full md:w-auto
                             md:shrink-0
                             filtros-reporte">


                    <!-- ============================= -->
                    <!-- CLIENTE -->
                    <!-- ============================= -->

                    <div class="flex-1 min-w-0
                                md:w-[135px] md:flex-none
                                lg:w-[150px]
                                xl:w-44">

                        <select name="client_id"
                                class="bg-gray-50 dark:bg-gray-700/50
                                       text-[10px] sm:text-xs md:text-[9px] lg:text-[10px] xl:text-xs
                                       text-gray-800 dark:text-gray-100
                                       border border-gray-200 dark:border-gray-600
                                       rounded-xl
                                       px-2 sm:px-3
                                       py-2 md:py-1.5 lg:py-2 xl:py-2.5
                                       focus:ring-2 focus:ring-indigo-500
                                       focus:outline-none
                                       cursor-pointer
                                       w-full">

                            <option value="">Todos los clientes</option>

                            @foreach($clientes as $client)

                                <option value="{{ $client->id }}"
                                    {{ (isset($clientId) && $clientId == $client->id) ? 'selected' : '' }}>

                                    {{ $client->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- ============================= -->
                    <!-- FECHA INICIO -->
                    <!-- ============================= -->

                    <div class="flex-1 min-w-0
                                md:w-[105px] md:flex-none
                                lg:w-[120px]
                                xl:w-36">

                        <input type="date"
                               name="fecha_inicio"
                               value="{{ $fechaInicio }}"
                               class="bg-gray-50 dark:bg-gray-700/50
                                      text-[10px] sm:text-xs md:text-[9px] lg:text-[10px] xl:text-xs
                                      text-gray-800 dark:text-gray-100
                                      border border-gray-200 dark:border-gray-600
                                      rounded-xl
                                      px-2 sm:px-3
                                      py-2 md:py-1.5 lg:py-2 xl:py-2.5
                                      focus:ring-2 focus:ring-indigo-500
                                      focus:outline-none
                                      w-full">

                    </div>


                    <!-- ============================= -->
                    <!-- FECHA FIN -->
                    <!-- ============================= -->

                    <div class="flex-1 min-w-0
                                md:w-[105px] md:flex-none
                                lg:w-[120px]
                                xl:w-36">

                        <input type="date"
                               name="fecha_fin"
                               value="{{ $fechaFin }}"
                               class="bg-gray-50 dark:bg-gray-700/50
                                      text-[10px] sm:text-xs md:text-[9px] lg:text-[10px] xl:text-xs
                                      text-gray-800 dark:text-gray-100
                                      border border-gray-200 dark:border-gray-600
                                      rounded-xl
                                      px-2 sm:px-3
                                      py-2 md:py-1.5 lg:py-2 xl:py-2.5
                                      focus:ring-2 focus:ring-indigo-500
                                      focus:outline-none
                                      w-full">

                    </div>


                    <!-- ============================= -->
                    <!-- BOTÓN CONSULTAR -->
                    <!-- ============================= -->

                    <div class="shrink-0
                                md:w-[100px]
                                lg:w-[110px]
                                xl:w-auto">

                        <button type="submit"
                                class="h-full
                                       inline-flex items-center justify-center
                                       gap-1
                                       bg-indigo-600 hover:bg-indigo-700
                                       text-white
                                       px-2 sm:px-3 lg:px-3 xl:px-4
                                       py-2 md:py-1.5 lg:py-2 xl:py-2.5
                                       text-[10px] sm:text-xs md:text-[9px] lg:text-[10px] xl:text-xs
                                       font-bold
                                       rounded-xl
                                       shadow-xs
                                       transition">

                            <span class="text-sm sm:text-base">
                                🔍
                            </span>

                            <span class="texto-consultar">
                                Consultar
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>



        <!-- ===================================================== -->
        <!-- TABLA -->
        <!-- ===================================================== -->

        <div class="bg-white dark:bg-gray-800
                    rounded-2xl
                    shadow-xs
                    border border-gray-200 dark:border-gray-700
                    overflow-hidden
                    flex flex-col
                    contenedor-tabla-reporte">


            <!-- ================================================= -->
            <!-- ENCABEZADO DE TABLA -->
            <!-- SIEMPRE UNA SOLA FILA -->
            <!-- ================================================= -->

            <div class="encabezado-tabla-reporte
                        px-3 sm:px-4
                        py-2.5 sm:py-3
                        border-b border-gray-100 dark:border-gray-700
                        flex items-center justify-between
                        gap-2
                        bg-white dark:bg-gray-800
                        z-10
                        min-w-0">


                <!-- ============================= -->
                <!-- TÍTULO -->
                <!-- ============================= -->

                <div class="titulo-tabla-reporte
                            min-w-0
                            flex-1
                            overflow-hidden">

                    <h3 class="font-black uppercase tracking-wider
                               text-gray-800 dark:text-gray-200
                               flex items-center gap-1.5
                               whitespace-nowrap
                               overflow-hidden
                               text-ellipsis">

                        <span class="shrink-0">
                            ⏱️
                        </span>

                        <span class="titulo-texto-truncado
                                     overflow-hidden
                                     text-ellipsis
                                     whitespace-nowrap">

                            Historial de ventas y créditos

                        </span>

                    </h3>

                </div>


                <!-- ============================= -->
                <!-- BOTÓN EXPORTAR -->
                <!-- ============================= -->

                <div class="boton-exportar-reporte shrink-0">

                    <button type="button"
                            onclick="exportarExcel()"
                            class="inline-flex items-center justify-center
                                   gap-1.5
                                   bg-emerald-600 hover:bg-emerald-700
                                   text-white
                                   px-3 sm:px-4
                                   py-2
                                   text-xs
                                   font-bold
                                   rounded-xl
                                   shadow-xs
                                   transition
                                   cursor-pointer
                                   whitespace-nowrap">

                        <span>
                            📥
                        </span>

                        <span class="texto-exportar">
                            Exportar Excel
                        </span>

                    </button>

                </div>

            </div>



            <!-- ================================================= -->
            <!-- CONTENEDOR SCROLL DE TABLA -->
            <!-- ================================================= -->

            <div class="max-h-[500px]
                        overflow-y-auto
                        overflow-x-auto
                        relative">

                <table class="w-full
                              text-left
                              border-collapse">


                    <!-- ================================================= -->
                    <!-- CABECERA -->
                    <!-- ================================================= -->

                    <thead class="bg-gray-50 dark:bg-gray-900/50
                                  sticky top-0 z-10">

                        <tr class="text-[10px] sm:text-xs
                                   uppercase
                                   tracking-wider
                                   font-black
                                   text-gray-500 dark:text-gray-400">


                            <th class="py-3.5 px-4 columna-fecha whitespace-nowrap">
                                Fecha
                            </th>

                            <th class="py-3.5 px-4 columna-tipo whitespace-nowrap">
                                Tipo
                            </th>

                            <th class="py-3.5 px-4 columna-cliente whitespace-nowrap">
                                Cliente
                            </th>

                            <th class="py-3.5 px-4 columna-direccion">
                                Dirección
                            </th>

                            <th class="py-3.5 px-4 columna-concepto">
                                Concepto / Descripción
                            </th>

                            <th class="py-3.5 px-4 columna-estado text-center whitespace-nowrap">
                                Estado
                            </th>

                            <th class="py-3.5 px-4 columna-usuario whitespace-nowrap">
                                Usuario
                            </th>

                            <th class="py-3.5 px-4 columna-monto text-right whitespace-nowrap">
                                Monto
                            </th>

                        </tr>

                    </thead>


                    <!-- ================================================= -->
                    <!-- CUERPO -->
                    <!-- ================================================= -->

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/70">

                        @php

                            $movimientos = $ventasMercancia
                                ->concat($prestamosEfectivo)
                                ->sortByDesc('created_at');

                        @endphp


                        @forelse($movimientos as $item)

                            @php

                                $clientAddress = optional($item->client)->address;

                                $userName = optional($item->user)->name ?? 'N/A';

                                $valMonto = $item->total_amount ?? 0;

                            @endphp


                            <tr class="fila-cuenta
                                       hover:bg-gray-50/50
                                       dark:hover:bg-gray-700/20
                                       transition">


                                <!-- ============================= -->
                                <!-- FECHA -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-fecha
                                           text-gray-600 dark:text-gray-300
                                           whitespace-nowrap">

                                    {{ $item->created_at->format('d/m/Y H:i') }}

                                </td>


                                <!-- ============================= -->
                                <!-- TIPO -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-tipo
                                           whitespace-nowrap">

                                    @if($item->type === 'store_credit')

                                        <span class="inline-flex items-center gap-1
                                                     px-2.5 py-0.5
                                                     rounded-full
                                                     text-[10px]
                                                     font-black
                                                     bg-emerald-100
                                                     text-emerald-800
                                                     dark:bg-emerald-950/60
                                                     dark:text-emerald-300">

                                            📦 Mercancía

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1
                                                     px-2.5 py-0.5
                                                     rounded-full
                                                     text-[10px]
                                                     font-black
                                                     bg-blue-100
                                                     text-blue-800
                                                     dark:bg-blue-950/60
                                                     dark:text-blue-300">

                                            💵 Efectivo

                                        </span>

                                    @endif

                                </td>


                                <!-- ============================= -->
                                <!-- CLIENTE -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-cliente
                                           font-bold
                                           text-gray-900 dark:text-white">

                                    {{ $item->client->name ?? 'General' }}

                                </td>


                                <!-- ============================= -->
                                <!-- DIRECCIÓN -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-direccion
                                           text-gray-600 dark:text-gray-400">

                                    {{ $clientAddress ?: 'S/D' }}

                                </td>


                                <!-- ============================= -->
                                <!-- CONCEPTO -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-concepto
                                           text-gray-700 dark:text-gray-300">

                                    {{ '#' . $item->id }} -

                                    {{ $item->concept
                                        ?? ($item->type === 'cash_loan'
                                            ? 'Préstamo en efectivo'
                                            : 'Venta de mercancía') }}

                                </td>


                                <!-- ============================= -->
                                <!-- ESTADO -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-estado
                                           text-center
                                           whitespace-nowrap">

                                    @if(isset($item->status) && $item->status === 'paid')

                                        <span class="px-2 py-0.5
                                                     text-[10px]
                                                     font-bold
                                                     bg-green-100
                                                     text-green-700
                                                     dark:bg-green-950/60
                                                     dark:text-green-300
                                                     rounded-md">

                                            Liquidado

                                        </span>

                                    @else

                                        <span class="px-2 py-0.5
                                                     text-[10px]
                                                     font-bold
                                                     bg-amber-100
                                                     text-amber-700
                                                     dark:bg-amber-950/60
                                                     dark:text-amber-300
                                                     rounded-md">

                                            Pendiente

                                        </span>

                                    @endif

                                </td>


                                <!-- ============================= -->
                                <!-- USUARIO -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-usuario
                                           text-gray-600 dark:text-gray-300">

                                    {{ $userName }}

                                </td>


                                <!-- ============================= -->
                                <!-- MONTO -->
                                <!-- ============================= -->

                                <td class="py-3.5 px-4
                                           columna-monto
                                           text-right
                                           font-black
                                           text-gray-900 dark:text-white
                                           monto-total"
                                    data-valor="{{ $valMonto }}">

                                    ${{ number_format($valMonto, 2) }}

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center
                                           py-12
                                           text-gray-400">

                                    <div class="text-3xl mb-2">
                                        📂
                                    </div>

                                    <p class="font-medium">
                                        No se encontraron movimientos registrados con estos filtros.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- CSS RESPONSIVE -->
    <!-- ========================================================= -->

    <style>


        /* ========================================================= */
        /* HEADER SUPERIOR */
        /* ========================================================= */

        .contenedor-reporte-header {
            container-type: inline-size;
        }


        /* ========================================================= */
        /* HEADER SUPERIOR - ESPACIO REDUCIDO */
        /* ========================================================= */

        @container (max-width: 1100px) {

            .reporte-header-interno {

                flex-direction: column !important;

                align-items: stretch !important;

                justify-content: flex-start !important;

            }


            .tarjetas-resumen {

                width: 100% !important;

                flex: none !important;

                min-width: 0 !important;

            }


            .filtros-reporte {

                width: 100% !important;

                flex: none !important;

                flex-shrink: 1 !important;

                display: grid !important;

                grid-template-columns:
                    minmax(0, 1fr)
                    minmax(0, 1fr)
                    minmax(0, 1fr)
                    auto;

                align-items: stretch;

                gap: 6px;

            }


            .filtros-reporte > div {

                min-width: 0 !important;

                width: auto !important;

            }


            .filtros-reporte select,
            .filtros-reporte input[type="date"] {

                width: 100% !important;

                min-width: 0 !important;

            }


            .filtros-reporte button {

                width: 42px !important;

                min-width: 42px !important;

                height: 100% !important;

                padding-left: 8px !important;

                padding-right: 8px !important;

                display: inline-flex !important;

                align-items: center;

                justify-content: center;

            }


            .filtros-reporte button .texto-consultar {

                display: none !important;

            }


            .filtros-reporte button span:first-child {

                margin: 0 !important;

            }

        }


        /* ========================================================= */
        /* HEADER SUPERIOR - ESPACIO GRANDE */
        /* ========================================================= */

        @container (min-width: 1101px) {

            .reporte-header-interno {

                flex-direction: row !important;

                align-items: center !important;

                justify-content: space-between !important;

            }


            .tarjetas-resumen {

                width: auto;

                flex: none;

            }


            .filtros-reporte {

                width: auto;

                display: flex !important;

                flex-direction: row;

                align-items: center;

            }


            .filtros-reporte > div {

                width: auto;

            }


            .filtros-reporte button {

                width: auto !important;

                min-width: 0 !important;

                height: auto !important;

            }


            .filtros-reporte button .texto-consultar {

                display: inline !important;

            }

        }



        /* ========================================================= */
        /* CONTENEDOR DE TABLA */
        /* ========================================================= */

        .contenedor-tabla-reporte {

            container-type: inline-size;

        }



        /* ========================================================= */
        /* ENCABEZADO DE TABLA */
        /* SIEMPRE UNA SOLA FILA */
        /* ========================================================= */

        .encabezado-tabla-reporte {

            container-type: inline-size;

        }


        .encabezado-tabla-reporte .titulo-tabla-reporte {

            min-width: 0;

            flex: 1 1 auto;

            overflow: hidden;

        }


        .encabezado-tabla-reporte h3 {

            min-width: 0;

            overflow: hidden;

            white-space: nowrap;

            text-overflow: ellipsis;

        }


        .encabezado-tabla-reporte .titulo-texto-truncado {

            min-width: 0;

            overflow: hidden;

            white-space: nowrap;

            text-overflow: ellipsis;

        }


        .encabezado-tabla-reporte .boton-exportar-reporte {

            flex-shrink: 0;

        }



        /* ========================================================= */
        /* TABLA GRANDE */
        /* MÁS DE 1100 PX */
        /* ========================================================= */

        @container (min-width: 1101px) {

            .columna-fecha,
            .columna-tipo,
            .columna-cliente,
            .columna-direccion,
            .columna-concepto,
            .columna-estado,
            .columna-usuario,
            .columna-monto {

                display: table-cell;

            }

        }



        /* ========================================================= */
        /* TABLA MEDIANA */
        /* 701 - 1100 PX */
        /* OCULTAR TIPO + USUARIO */
        /* ========================================================= */

        @container (min-width: 701px) and (max-width: 1100px) {

            .columna-tipo,
            .columna-usuario {

                display: none !important;

            }

        }



        /* ========================================================= */
        /* TABLA PEQUEÑA */
        /* HASTA 700 PX */
        /* ========================================================= */

        @container (max-width: 700px) {

            .columna-tipo,
            .columna-usuario,
            .columna-direccion,
            .columna-estado {

                display: none !important;

            }


            tbody {

                font-size: 10px !important;

            }


            thead tr {

                font-size: 9px !important;

            }


            .columna-fecha {

                font-size: 9px !important;

                width: 85px;

            }


            .columna-cliente {

                font-size: 10px !important;

                min-width: 95px;

            }


            .columna-concepto {

                font-size: 10px !important;

                min-width: 140px;

            }


            .columna-monto {

                font-size: 10px !important;

                width: 85px;

            }


            .columna-fecha,
            .columna-cliente,
            .columna-concepto,
            .columna-monto {

                padding-top: 8px !important;

                padding-bottom: 8px !important;

                padding-left: 6px !important;

                padding-right: 6px !important;

            }


            .columna-estado span,
            .columna-tipo span {

                font-size: 8px !important;

            }

        }



        /* ========================================================= */
        /* ENCABEZADO TABLA - MEDIANO */
        /* ========================================================= */

        @container (max-width: 700px) {

            .encabezado-tabla-reporte {

                gap: 6px !important;

                padding-left: 10px !important;

                padding-right: 10px !important;

            }


            .encabezado-tabla-reporte h3 {

                font-size: 10px !important;

                line-height: 1.2 !important;

            }


            .encabezado-tabla-reporte .titulo-tabla-reporte {

                min-width: 0 !important;

                flex: 1 1 auto !important;

            }


            .encabezado-tabla-reporte .boton-exportar-reporte {

                flex-shrink: 0 !important;

            }


            .encabezado-tabla-reporte button {

                font-size: 10px !important;

                padding: 7px 9px !important;

                gap: 4px !important;

            }

        }



        /* ========================================================= */
        /* ENCABEZADO TABLA - PEQUEÑO */
        /* ========================================================= */

        @container (max-width: 450px) {

            .encabezado-tabla-reporte {

                gap: 5px !important;

                padding-left: 8px !important;

                padding-right: 8px !important;

            }


            .encabezado-tabla-reporte h3 {

                font-size: 9px !important;

                letter-spacing: 0.04em !important;

            }


            .encabezado-tabla-reporte button {

                font-size: 9px !important;

                padding: 6px 8px !important;

            }


            .encabezado-tabla-reporte button span:first-child {

                font-size: 11px !important;

            }

        }



        /* ========================================================= */
        /* ENCABEZADO TABLA - MUY PEQUEÑO */
        /* ========================================================= */

        @container (max-width: 350px) {

            .encabezado-tabla-reporte {

                gap: 4px !important;

                padding-left: 6px !important;

                padding-right: 6px !important;

            }


            .encabezado-tabla-reporte h3 {

                font-size: 8px !important;

            }


            .encabezado-tabla-reporte button {

                font-size: 8px !important;

                padding: 6px 7px !important;

            }


            .encabezado-tabla-reporte button span:first-child {

                font-size: 10px !important;

            }

        }



        /* ========================================================= */
        /* ESPACIO EXTREMADAMENTE PEQUEÑO */
        /* EL BOTÓN PASA A SOLO ICONO */
        /* ========================================================= */

        @container (max-width: 300px) {

            .encabezado-tabla-reporte h3 {

                font-size: 8px !important;

            }


            .encabezado-tabla-reporte .texto-exportar {

                display: none !important;

            }


            .encabezado-tabla-reporte button {

                width: 34px !important;

                min-width: 34px !important;

                height: 30px !important;

                padding: 4px !important;

            }

        }

    </style>



    <!-- ========================================================= -->
    <!-- EXPORTAR EXCEL -->
    <!-- ========================================================= -->

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

                        <td colspan="7"
                            class="titulo"
                            style="font-size: 14pt; height: 35px;">

                            REPORTE GENERAL DE VENTAS

                        </td>

                    </tr>

                    <tr style="height: 10px;"></tr>

                    <tr style="height: 25px;">

                        <td class="header">
                            Fecha
                        </td>

                        <td class="header">
                            Tipo
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
                            Estado
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
                            fila
                                .querySelector('.monto-total')
                                .getAttribute('data-valor')
                        ) || 0;


                    sumaMonto += valMonto;


                    tablaHtml += "<tr>";


                    tablaHtml +=
                        `<td class="celda centro">
                            ${celdas[0].innerText.trim()}
                        </td>`;


                    tablaHtml +=
                        `<td class="celda centro">
                            ${celdas[1].innerText.trim()}
                        </td>`;


                    tablaHtml +=
                        `<td class="celda texto">
                            ${celdas[2].innerText.trim()}
                        </td>`;


                    tablaHtml +=
                        `<td class="celda texto">
                            ${celdas[3].innerText.trim()}
                        </td>`;


                    tablaHtml +=
                        `<td class="celda texto">
                            ${celdas[4].innerText.trim()}
                        </td>`;


                    tablaHtml +=
                        `<td class="celda centro">
                            ${celdas[5].innerText.trim()}
                        </td>`;


                    tablaHtml +=
                        `<td class="celda numero"
                            x:num="${valMonto}"
                            style="font-weight: bold;
                                   mso-number-format:'\\$#,##0.00';">

                            ${valMonto}

                        </td>`;


                    tablaHtml += "</tr>";

                }

            });


            tablaHtml +=
                `<tr class="totales" style="height: 28px;">

                    <td colspan="6"
                        class="celda-vacia"
                        style="text-align: right;
                               font-weight: bold;">

                        TOTAL GENERAL:

                    </td>

                    <td class="celda-vacia numero"
                        x:num="${sumaMonto}"
                        style="font-weight: bold;
                               border-top: 1px solid #374151;
                               border-bottom: 1px solid #374151;
                               mso-number-format:'\\$#,##0.00';">

                        ${sumaMonto}

                    </td>

                </tr>`;


            tablaHtml +=
                `</table>
                </body>
                </html>`;


            let blob = new Blob(
                [tablaHtml],
                {
                    type: 'application/vnd.ms-excel;charset=utf-8;'
                }
            );


            let downloadLink =
                document.createElement("a");


            downloadLink.href =
                window.URL.createObjectURL(blob);


            downloadLink.download =
                "reporte_ventas_" +
                new Date().toISOString().slice(0, 10) +
                ".xls";


            document.body.appendChild(downloadLink);

            downloadLink.click();

            document.body.removeChild(downloadLink);

        }

    </script>

</x-app-layout>
