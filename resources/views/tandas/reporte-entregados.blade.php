<x-app-layout>

    <x-slot name="header">
        <div class="reporte-entregados-header w-full min-w-0">
            <div class="min-w-0 w-full">

                <h2
                    class="titulo-reporte-entregados font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Clientes que ya Recibieron Tanda
                </h2>

                <p
                    class="subtitulo-reporte-entregados text-xs text-gray-500 mt-0.5">
                    Listado de entregas realizadas y montos pagados por cliente
                </p>

            </div>
        </div>
    </x-slot>


    @php
        $busquedaRealizada =
            !empty($fechaInicio) ||
            !empty($fechaFin) ||
            request('filtro') === 'todos';
    @endphp


    <!-- ========================================================= -->
    <!-- ESTILOS RESPONSIVOS -->
    <!-- ========================================================= -->

    <style>

        /* =========================================================
           TITULO
           ========================================================= */

        .reporte-entregados-header {
            width: 100%;
            min-width: 0;
        }

        .reporte-entregados-header .titulo-reporte-entregados {
            white-space: normal;
            overflow-wrap: break-word;
            word-break: normal;
        }


        /* =========================================================
           VENTANAS CHICAS
           ========================================================= */

        @media (max-width: 799px) {

            /* =====================================================
               TITULO
               ===================================================== */

            .reporte-entregados-header {
                width: 100% !important;
                min-width: 0 !important;
                padding-right: 0.25rem !important;
            }

            .reporte-entregados-header
            .titulo-reporte-entregados {
                font-size: 17px !important;
                line-height: 1.15 !important;
            }

            .reporte-entregados-header
            .subtitulo-reporte-entregados {
                font-size: 10px !important;
                line-height: 1.15 !important;
            }


            /* =====================================================
               CONTENEDOR GENERAL
               ===================================================== */

            .reporte-entregados-responsive {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }

            .reporte-entregados-responsive
            .contenedor-general {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }


            /* =====================================================
               TARJETAS
               3 EN UNA SOLA FILA
               ===================================================== */

            .reporte-entregados-responsive
            .tarjetas-totales {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;

                gap: 0.35rem !important;

                width: 100% !important;

                margin-bottom: 0.6rem !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total {

                width: auto !important;
                min-width: 0 !important;

                flex: 1 1 0 !important;

                padding: 0.5rem 0.4rem !important;

                border-radius: 0.5rem !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .titulo-tarjeta {

                font-size: 9px !important;

                line-height: 1.1 !important;

                letter-spacing: 0.025em !important;

                white-space: normal !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .valor-tarjeta {

                font-size: 18px !important;

                line-height: 1 !important;

                margin-top: 0.35rem !important;
                margin-bottom: 0.35rem !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .descripcion-tarjeta {

                font-size: 9px !important;

                line-height: 1.1 !important;
            }


            /* =====================================================
               CONTENEDOR PRINCIPAL
               ===================================================== */

            .reporte-entregados-responsive
            .contenedor-reporte {

                padding: 0.5rem !important;

                border-radius: 0.5rem !important;
            }


            /* =====================================================
               PARTE SUPERIOR DE LA TABLA
               TODO EN UNA SOLA FILA
               ===================================================== */

            .reporte-entregados-responsive
            .controles-superiores {

                width: 100% !important;

                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;

                align-items: center !important;
                justify-content: space-between !important;

                gap: 0.2rem !important;

                margin-bottom: 0.45rem !important;
                padding-bottom: 0.35rem !important;

                min-width: 0 !important;
            }


            /* =====================================================
               FORMULARIO DE FECHAS
               ===================================================== */

            .reporte-entregados-responsive
            .controles-superiores form {

                width: auto !important;

                flex: 1 1 auto !important;
                min-width: 0 !important;

                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;

                align-items: center !important;

                gap: 0.18rem !important;
            }


            /* Quitamos el grid original */

            .reporte-entregados-responsive
            .controles-superiores form > div {

                display: block !important;

                min-width: 0 !important;

                margin: 0 !important;
            }


            /* =====================================================
               CAMPOS DE FECHA
               ===================================================== */

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(1),

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(2) {

                flex: 0 1 105px !important;

                width: 105px !important;

                min-width: 0 !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            input[type="date"] {

                width: 100% !important;

                min-width: 0 !important;

                height: 26px !important;

                padding: 0.15rem 0.25rem !important;

                font-size: 9px !important;

                line-height: 1 !important;

                border-radius: 0.35rem !important;
            }


            /* =====================================================
               BOTONES FILTRAR / TODOS
               ===================================================== */

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) {

                display: flex !important;

                align-items: center !important;

                gap: 0.15rem !important;

                margin: 0 !important;

                flex-shrink: 0 !important;

                width: auto !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) button,

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) a {

                width: 26px !important;
                min-width: 26px !important;
                max-width: 26px !important;

                height: 26px !important;
                min-height: 26px !important;
                max-height: 26px !important;

                padding: 0 !important;

                display: inline-flex !important;

                align-items: center !important;
                justify-content: center !important;

                flex-shrink: 0 !important;

                border-radius: 0.35rem !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) svg {

                width: 12px !important;
                height: 12px !important;

                margin: 0 !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) span {

                display: none !important;
            }


            /* =====================================================
               BOTONES EXCEL / PDF
               ICONO + TEXTO
               ===================================================== */

            .reporte-entregados-responsive
            .botones-exportacion {

                display: flex !important;

                flex-direction: row !important;
                flex-wrap: nowrap !important;

                align-items: center !important;

                gap: 0.18rem !important;

                flex-shrink: 0 !important;

                width: auto !important;
            }

            .reporte-entregados-responsive
            .botones-exportacion a {

                width: auto !important;

                display: inline-flex !important;

                align-items: center !important;
                justify-content: center !important;

                white-space: nowrap !important;

                padding: 0.3rem 0.4rem !important;

                font-size: 9px !important;

                line-height: 1 !important;

                gap: 0.2rem !important;

                border-radius: 0.4rem !important;
            }

            .reporte-entregados-responsive
            .botones-exportacion svg {

                width: 12px !important;
                height: 12px !important;

                flex-shrink: 0 !important;
            }


            /* =====================================================
               TABLA
               ===================================================== */

            .reporte-entregados-responsive
            .tabla-contenedor {

                width: 100% !important;

                overflow-x: hidden !important;
                overflow-y: auto !important;

                max-height: 64vh !important;
            }

            .reporte-entregados-responsive
            .tabla-contenedor table {

                width: 100% !important;

                min-width: 0 !important;

                table-layout: fixed !important;

                font-size: 11.5px !important;
            }


            /* =====================================================
               ENCABEZADO DE TABLA
               ===================================================== */

            .reporte-entregados-responsive
            .tabla-contenedor thead th {

                padding: 0.45rem 0.25rem !important;

                font-size: 9px !important;

                line-height: 1.1 !important;

                white-space: normal !important;
            }


            /* =====================================================
               ANCHOS DE COLUMNAS
               ===================================================== */

            .reporte-entregados-responsive
            .col-cliente {

                width: 30% !important;
            }

            .reporte-entregados-responsive
            .col-tanda {

                width: 27% !important;
            }

            .reporte-entregados-responsive
            .col-turno {

                width: 13% !important;
            }

            .reporte-entregados-responsive
            .col-fecha {

                width: 15% !important;
            }

            .reporte-entregados-responsive
            .col-monto {

                width: 15% !important;
            }


            /* =====================================================
               CELDAS
               ===================================================== */

            .reporte-entregados-responsive
            .tabla-contenedor tbody td {

                padding: 0.45rem 0.25rem !important;

                font-size: 11px !important;

                line-height: 1.15 !important;

                overflow: hidden !important;
            }


            /* =====================================================
               CLIENTE
               ===================================================== */

            .reporte-entregados-responsive
            .cliente-nombre {

                font-size: 11.5px !important;

                line-height: 1.15 !important;

                white-space: normal !important;

                word-break: break-word !important;
            }

            .reporte-entregados-responsive
            .cliente-telefono {

                font-size: 8.5px !important;

                line-height: 1.1 !important;
            }


            /* =====================================================
               TANDA
               ===================================================== */

            .reporte-entregados-responsive
            .tanda-nombre {

                font-size: 10.5px !important;

                line-height: 1.15 !important;

                white-space: normal !important;

                word-break: break-word !important;
            }


            /* =====================================================
               TURNO
               ===================================================== */

            .reporte-entregados-responsive
            .turno-badge {

                font-size: 9px !important;

                padding: 0.18rem 0.3rem !important;

                white-space: nowrap !important;
            }


            /* =====================================================
               FECHA
               ===================================================== */

            .reporte-entregados-responsive
            .fecha-entrega {

                font-size: 9px !important;

                line-height: 1.1 !important;

                white-space: nowrap !important;
            }


            /* =====================================================
               MONTO
               ===================================================== */

            .reporte-entregados-responsive
            .monto-entregado {

                font-size: 11.5px !important;

                line-height: 1.1 !important;

                white-space: nowrap !important;
            }
        }


        /* =========================================================
           MOVILES MUY PEQUEÑOS
           ========================================================= */

        @media (max-width: 500px) {

            /* =====================================================
               TITULO
               ===================================================== */

            .reporte-entregados-header {
                padding-right: 0 !important;
            }

            .reporte-entregados-header
            .titulo-reporte-entregados {

                font-size: 15px !important;

                line-height: 1.15 !important;
            }

            .reporte-entregados-header
            .subtitulo-reporte-entregados {

                font-size: 9px !important;

                line-height: 1.1 !important;

                margin-top: 0.15rem !important;
            }


            /* =====================================================
               TARJETAS
               ===================================================== */

            .reporte-entregados-responsive
            .tarjetas-totales {

                gap: 0.25rem !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total {

                padding: 0.4rem 0.3rem !important;

                border-radius: 0.4rem !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .titulo-tarjeta {

                font-size: 8.5px !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .valor-tarjeta {

                font-size: 16px !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .descripcion-tarjeta {

                font-size: 8px !important;
            }


            /* =====================================================
               CONTROLES
               ===================================================== */

            .reporte-entregados-responsive
            .controles-superiores {

                gap: 0.12rem !important;
            }

            .reporte-entregados-responsive
            .controles-superiores form {

                gap: 0.1rem !important;
            }


            /* =====================================================
               FECHAS
               ===================================================== */

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(1),

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(2) {

                flex: 0 1 82px !important;

                width: 82px !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            input[type="date"] {

                height: 24px !important;

                font-size: 8px !important;

                padding-left: 0.15rem !important;
                padding-right: 0.15rem !important;
            }


            /* =====================================================
               BOTONES BUSCAR / TODOS
               ===================================================== */

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) {

                gap: 0.1rem !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) button,

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) a {

                width: 24px !important;
                min-width: 24px !important;
                max-width: 24px !important;

                height: 24px !important;
                min-height: 24px !important;
                max-height: 24px !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(3) svg {

                width: 11px !important;
                height: 11px !important;
            }


            /* =====================================================
               EXCEL / PDF
               ===================================================== */

            .reporte-entregados-responsive
            .botones-exportacion {

                gap: 0.1rem !important;
            }

            .reporte-entregados-responsive
            .botones-exportacion a {

                padding: 0.25rem 0.3rem !important;

                font-size: 8px !important;

                gap: 0.15rem !important;
            }

            .reporte-entregados-responsive
            .botones-exportacion svg {

                width: 10px !important;
                height: 10px !important;
            }


            /* =====================================================
               TABLA
               ===================================================== */

            .reporte-entregados-responsive
            .tabla-contenedor table {

                font-size: 10.5px !important;
            }

            .reporte-entregados-responsive
            .tabla-contenedor thead th {

                padding: 0.4rem 0.18rem !important;

                font-size: 8px !important;
            }

            .reporte-entregados-responsive
            .tabla-contenedor tbody td {

                padding: 0.4rem 0.18rem !important;

                font-size: 10px !important;
            }

            .reporte-entregados-responsive
            .cliente-nombre {

                font-size: 10.8px !important;
            }

            .reporte-entregados-responsive
            .cliente-telefono {

                font-size: 7.8px !important;
            }

            .reporte-entregados-responsive
            .tanda-nombre {

                font-size: 10px !important;
            }

            .reporte-entregados-responsive
            .turno-badge {

                font-size: 8px !important;

                padding: 0.15rem 0.25rem !important;
            }

            .reporte-entregados-responsive
            .fecha-entrega {

                font-size: 8px !important;
            }

            .reporte-entregados-responsive
            .monto-entregado {

                font-size: 10.5px !important;
            }
        }


        /* =========================================================
           MOVILES EXTREMADAMENTE PEQUEÑOS
           ========================================================= */

        @media (max-width: 380px) {

            .reporte-entregados-header
            .titulo-reporte-entregados {

                font-size: 14px !important;
            }

            .reporte-entregados-header
            .subtitulo-reporte-entregados {

                font-size: 8px !important;
            }


            .reporte-entregados-responsive
            .tarjeta-total .titulo-tarjeta {

                font-size: 8px !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .valor-tarjeta {

                font-size: 15px !important;
            }

            .reporte-entregados-responsive
            .tarjeta-total .descripcion-tarjeta {

                font-size: 7.5px !important;
            }


            /* Fechas todavía un poco más compactas */

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(1),

            .reporte-entregados-responsive
            .controles-superiores
            form > div:nth-child(2) {

                flex-basis: 76px !important;

                width: 76px !important;
            }

            .reporte-entregados-responsive
            .controles-superiores
            input[type="date"] {

                font-size: 7px !important;
            }


            .reporte-entregados-responsive
            .botones-exportacion a {

                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;

                font-size: 7.5px !important;
            }


            .reporte-entregados-responsive
            .tabla-contenedor thead th {

                font-size: 7.5px !important;
            }

            .reporte-entregados-responsive
            .tabla-contenedor tbody td {

                font-size: 9.5px !important;
            }

            .reporte-entregados-responsive
            .cliente-nombre {

                font-size: 10px !important;
            }

            .reporte-entregados-responsive
            .tanda-nombre {

                font-size: 9.5px !important;
            }

            .reporte-entregados-responsive
            .monto-entregado {

                font-size: 10px !important;
            }
        }

    </style>


    <!-- ========================================================= -->
    <!-- CONTENIDO -->
    <!-- ========================================================= -->

    <div class="reporte-entregados-responsive py-3 sm:py-4">

        <div
            class="contenedor-general max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-3">


            <!-- ===================================================== -->
            <!-- TARJETAS SUPERIORES -->
            <!-- ===================================================== -->

            <div
                class="tarjetas-totales grid grid-cols-3 gap-2 sm:gap-4 mb-4">


                <!-- =================================================
                     CLIENTES
                     ================================================= -->

                <div
                    class="tarjeta-total bg-white p-2.5 sm:p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between transition-all">

                    <div
                        class="titulo-tarjeta text-[9px] sm:text-xs font-bold text-gray-500 uppercase tracking-wider">

                        Clientes

                    </div>

                    <div
                        class="valor-tarjeta text-sm sm:text-2xl font-black text-gray-900 my-1">

                        {{ $busquedaRealizada ? $clientesEntregados->count() : 0 }}

                    </div>

                    <div
                        class="descripcion-tarjeta text-[9px] sm:text-xs text-gray-400">

                        Beneficiarios

                    </div>

                </div>


                <!-- =================================================
                     TANDAS
                     ================================================= -->

                <div
                    class="tarjeta-total bg-emerald-50/50 p-2.5 sm:p-5 rounded-xl border border-emerald-100 shadow-sm flex flex-col justify-between transition-all">

                    <div
                        class="titulo-tarjeta text-[9px] sm:text-xs font-bold text-emerald-700 uppercase tracking-wider">

                        Tandas

                    </div>

                    <div
                        class="valor-tarjeta text-sm sm:text-2xl font-black text-emerald-600 my-1">

                        {{ $busquedaRealizada ? $clientesEntregados->sum('total_tandas') : 0 }}

                    </div>

                    <div
                        class="descripcion-tarjeta text-[9px] sm:text-xs text-emerald-600/80">

                        Entregadas

                    </div>

                </div>


                <!-- =================================================
                     MONTO
                     ================================================= -->

                <div
                    class="tarjeta-total bg-indigo-50/50 p-2.5 sm:p-5 rounded-xl border border-indigo-100 shadow-sm flex flex-col justify-between transition-all">

                    <div
                        class="titulo-tarjeta text-[9px] sm:text-xs font-bold text-indigo-700 uppercase tracking-wider">

                        Monto

                    </div>

                    <div
                        class="valor-tarjeta text-xs sm:text-2xl font-black text-indigo-600 my-1 truncate">

                        ${{ $busquedaRealizada ? number_format($clientesEntregados->sum('monto_entregado'), 0) : '0.00' }}

                    </div>

                    <div
                        class="descripcion-tarjeta text-[9px] sm:text-xs text-indigo-600/80">

                        Total pagado

                    </div>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- CONTENEDOR PRINCIPAL -->
            <!-- ===================================================== -->

            <div
                class="contenedor-reporte bg-white overflow-hidden shadow-sm sm:rounded-xl p-3 sm:p-4">


                <!-- =================================================
                     CONTROLES SUPERIORES
                     ================================================= -->

                <div
                    class="controles-superiores flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2 mb-3 pb-2 border-b border-gray-100">


                    <!-- =================================================
                         FILTRO
                         ================================================= -->

                    <form
                        method="GET"
                        action="{{ route('tandas.reporte.entregados') }}"
                        class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-1.5 w-full sm:w-auto">


                        <!-- Fecha inicio -->

                        <div class="col-span-1">

                            <input
                                type="date"
                                name="fecha_inicio"
                                value="{{ $fechaInicio ?? request('fecha_inicio') }}"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">

                        </div>


                        <!-- Fecha fin -->

                        <div class="col-span-1">

                            <input
                                type="date"
                                name="fecha_fin"
                                value="{{ $fechaFin ?? request('fecha_fin') }}"
                                class="w-full text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">

                        </div>


                        <!-- Botones -->

                        <div
                            class="col-span-2 flex items-center gap-1.5 mt-1 sm:mt-0">


                            <!-- Buscar -->

                            <button
                                type="submit"
                                title="Filtrar por fechas"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">

                                <svg
                                    class="w-4 h-4 mr-1 sm:mr-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                                </svg>

                                <span class="sm:hidden">
                                    Filtrar
                                </span>

                            </button>


                            <!-- Ver todos -->

                            <a
                                href="{{ route('tandas.reporte.entregados', ['filtro' => 'todos']) }}"
                                title="Ver todos sin filtro"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">

                                <svg
                                    class="w-4 h-4 mr-1 sm:mr-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />

                                </svg>

                                <span class="sm:hidden">
                                    Ver todos
                                </span>

                            </a>

                        </div>

                    </form>


                    <!-- =================================================
                         EXPORTAR
                         ================================================= -->

                    <div
                        class="botones-exportacion grid grid-cols-2 sm:flex items-center gap-1.5">


                        <!-- Excel -->

                        <a
                            href="{{ route('tandas.reporte.entregados.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold shadow-xs transition">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />

                            </svg>

                            Excel

                        </a>


                        <!-- PDF -->

                        <a
                            href="{{ route('tandas.reporte.entregados.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold shadow-xs transition">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                            </svg>

                            PDF

                        </a>

                    </div>

                </div>


                <!-- ===================================================== -->
                <!-- TABLA -->
                <!-- ===================================================== -->

                <div
                    class="tabla-contenedor overflow-y-auto overflow-x-auto relative rounded-xl border border-gray-200"
                    style="max-height: 64vh;">

                    <table
                        class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">


                        <!-- =================================================
                             CABECERA
                             ================================================= -->

                        <thead
                            class="bg-gray-50 text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider sticky top-0 z-10 shadow-sm">

                            <tr>

                                <th
                                    class="col-cliente px-2 sm:px-4 py-2.5 text-left bg-gray-50">

                                    Cliente

                                </th>


                                <!-- Origen oculto en móvil -->

                                <th
                                    class="hidden sm:table-cell px-4 py-2.5 text-left bg-gray-50">

                                    Origen

                                </th>


                                <th
                                    class="col-tanda px-2 sm:px-4 py-2.5 text-left bg-gray-50">

                                    Tanda

                                </th>


                                <th
                                    class="col-turno px-2 sm:px-4 py-2.5 text-center bg-gray-50">

                                    Turno

                                </th>


                                <th
                                    class="col-fecha px-2 sm:px-4 py-2.5 text-center bg-gray-50">

                                    Fecha

                                </th>


                                <th
                                    class="col-monto px-2 sm:px-4 py-2.5 text-right bg-gray-50">

                                    Monto

                                </th>

                            </tr>

                        </thead>


                        <!-- =================================================
                             CUERPO
                             ================================================= -->

                        <tbody class="divide-y divide-gray-100 bg-white">


                            @if(!$busquedaRealizada)

                                <tr>

                                    <td
                                        colspan="6"
                                        class="px-4 py-10 text-center text-gray-400 text-xs sm:text-sm">

                                        🔍 Seleccione un rango de fechas o presione listar todo.

                                    </td>

                                </tr>


                            @else

                                @forelse($clientesEntregados as $item)

                                    <tr
                                        class="hover:bg-gray-50/75 transition-colors">


                                        <!-- Cliente -->

                                        <td
                                            class="col-cliente px-2 sm:px-4 py-2 whitespace-nowrap">

                                            <div
                                                class="cliente-nombre font-bold text-gray-900 text-xs sm:text-sm leading-tight">

                                                {{ $item['cliente_nombre'] }}

                                            </div>

                                            <div
                                                class="cliente-telefono text-[10px] sm:text-xs text-gray-400">

                                                Tel: {{ $item['telefono'] }}

                                            </div>

                                        </td>


                                        <!-- Origen -->

                                        <td
                                            class="hidden sm:table-cell px-4 py-2 whitespace-nowrap">

                                            <div
                                                class="text-xs text-gray-600 font-medium">

                                                {{ $item['origen'] ?? 'N/D' }}

                                            </div>

                                        </td>


                                        <!-- Tanda -->

                                        <td
                                            class="col-tanda px-2 sm:px-4 py-2 whitespace-nowrap">

                                            <div
                                                class="tanda-nombre text-xs sm:text-sm text-indigo-600 font-semibold truncate max-w-[120px] sm:max-w-none">

                                                {{ $item['tanda_nombre'] }}

                                            </div>

                                        </td>


                                        <!-- Turno -->

                                        <td
                                            class="col-turno px-2 sm:px-4 py-2 whitespace-nowrap text-center">

                                            <span
                                                class="turno-badge px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded text-[11px] font-medium">

                                                #{{ $item['turno'] }}

                                            </span>

                                        </td>


                                        <!-- Fecha -->

                                        <td
                                            class="col-fecha px-2 sm:px-4 py-2 whitespace-nowrap text-center">

                                            <span
                                                class="fecha-entrega text-[11px] sm:text-xs font-semibold text-gray-600">

                                                {{ \Carbon\Carbon::parse($item['fecha_entrega'])->format('d/m/Y') }}

                                            </span>

                                        </td>


                                        <!-- Monto -->

                                        <td
                                            class="col-monto px-2 sm:px-4 py-2 whitespace-nowrap text-right font-bold text-indigo-600 text-xs sm:text-sm">

                                            <span class="monto-entregado">

                                                ${{ number_format($item['monto_entregado'], 0) }}

                                            </span>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="px-4 py-10 text-center text-gray-500 text-xs sm:text-sm">

                                            📭 No hay registros para este periodo.

                                        </td>

                                    </tr>

                                @endforelse

                            @endif

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>