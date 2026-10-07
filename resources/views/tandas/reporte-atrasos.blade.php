<x-app-layout>

    <x-slot name="header">
        <div class="reporte-atrasos-header">
            <div class="min-w-0 w-full">
                <h2 class="titulo-reporte font-semibold text-xl text-gray-800 leading-tight">
                    Reporte de Clientes con Atrasos
                </h2>

                <p class="subtitulo-reporte text-xs text-gray-500 mt-0.5">
                    Listado de morosidad y pagos pendientes por cliente
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $busquedaRealizada = !empty($fechaInicio)
            || !empty($fechaFin)
            || request('filtro') === 'todos';
    @endphp


    <!-- ========================================================= -->
    <!-- ESTILOS RESPONSIVE -->
    <!-- ========================================================= -->

    <style>
        /* =========================================================
   TITULO RESPONSIVO DE LA VISTA
   ========================================================= */

        .reporte-atrasos-header {
            width: 100%;
            min-width: 0;
        }

        .reporte-atrasos-header .titulo-reporte {
            white-space: normal;
            overflow-wrap: break-word;
            word-break: normal;
        }


        /* =========================================================
   VENTANAS CHICAS
   ========================================================= */

        @media (max-width: 799px) {

            .reporte-atrasos-header {
                width: 100% !important;
                min-width: 0 !important;
                padding-right: 0.25rem !important;
            }

            .reporte-atrasos-header .titulo-reporte {
                font-size: 17px !important;
                line-height: 1.15 !important;
            }

            .reporte-atrasos-header .subtitulo-reporte {
                font-size: 10px !important;
                line-height: 1.15 !important;
            }
        }


        /* =========================================================
   MOVILES PEQUEÑOS
   ========================================================= */

        @media (max-width: 500px) {

            .reporte-atrasos-header {
                padding-right: 0 !important;
            }

            .reporte-atrasos-header .titulo-reporte {
                font-size: 15px !important;
                line-height: 1.15 !important;
            }

            .reporte-atrasos-header .subtitulo-reporte {
                font-size: 9px !important;
                line-height: 1.1 !important;
                margin-top: 0.15rem !important;
            }
        }


        /* =========================================================
   VENTANAS MUY ESTRECHAS
   ========================================================= */

        @media (max-width: 380px) {

            .reporte-atrasos-header .titulo-reporte {
                font-size: 14px !important;
            }

            .reporte-atrasos-header .subtitulo-reporte {
                font-size: 8px !important;
            }
        }

        /* =========================================================
           VENTANAS CHICAS Y MOVILES
           ========================================================= */

        @media (max-width: 799px) {

            /* =====================================================
               CONTENEDOR GENERAL
               ===================================================== */

            .reporte-atrasos-responsive .max-w-7xl {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }


            /* =====================================================
               TARJETAS SUPERIORES
               3 tarjetas en una sola fila
               ===================================================== */

            .reporte-atrasos-responsive .tarjetas-totales {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                gap: 0.35rem !important;
                width: 100% !important;
                margin-bottom: 0.6rem !important;
            }

            .reporte-atrasos-responsive .tarjeta-total {
                width: auto !important;
                min-width: 0 !important;
                flex: 1 1 0 !important;
                padding: 0.45rem 0.35rem !important;
                padding-right: 1.8rem !important;
                border-radius: 0.45rem !important;
            }

            /* Iconos de las tarjetas */

            .reporte-atrasos-responsive .tarjeta-total>div:first-child {
                right: 0.25rem !important;
                padding: 0.25rem !important;
                border-radius: 0.35rem !important;
            }

            .reporte-atrasos-responsive .tarjeta-total>div:first-child svg {
                width: 15px !important;
                height: 15px !important;
            }

            /* Título de tarjeta */

            .reporte-atrasos-responsive .tarjeta-total .titulo-tarjeta {
                font-size: 9px !important;
                line-height: 1.1 !important;
                letter-spacing: 0.025em !important;
                white-space: normal !important;
            }

            /* Número principal */

            .reporte-atrasos-responsive .tarjeta-total .valor-tarjeta {
                font-size: 17px !important;
                line-height: 1 !important;
                margin-top: 0.3rem !important;
                margin-bottom: 0.3rem !important;
            }

            /* Descripción */

            .reporte-atrasos-responsive .tarjeta-total .descripcion-tarjeta {
                font-size: 9px !important;
                line-height: 1.1 !important;
            }


            /* =====================================================
               CONTENEDOR PRINCIPAL
               ===================================================== */

            .reporte-atrasos-responsive .contenedor-reporte {
                padding: 0.5rem !important;
                border-radius: 0.45rem !important;
            }


            /* =====================================================
               PARTE SUPERIOR DE LA TABLA
               TODO EN UNA SOLA FILA
               ===================================================== */

            .reporte-atrasos-responsive .controles-superiores {
                width: 100% !important;
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 0.25rem !important;
                margin-bottom: 0.5rem !important;
                min-width: 0 !important;
            }


            /* =====================================================
               FORMULARIO DE FECHAS
               ===================================================== */

            .reporte-atrasos-responsive .controles-superiores form {
                width: auto !important;
                flex: 1 1 auto !important;
                min-width: 0 !important;

                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                align-items: center !important;

                gap: 0.2rem !important;
            }

            /* Contenedores de fecha */

            .reporte-atrasos-responsive .controles-superiores form>div {
                min-width: 0 !important;
            }

            .reporte-atrasos-responsive .controles-superiores form>div:first-child,
            .reporte-atrasos-responsive .controles-superiores form>div:nth-child(3) {

                flex: 0 1 105px !important;
                width: 105px !important;
                min-width: 0 !important;
            }

            /* Inputs */

            .reporte-atrasos-responsive .controles-superiores input[type="date"] {

                width: 100% !important;
                min-width: 0 !important;

                height: 26px !important;

                padding: 0.15rem 0.25rem !important;

                font-size: 9px !important;
                line-height: 1 !important;

                border-radius: 0.35rem !important;
            }

            /* Guion */

            .reporte-atrasos-responsive .controles-superiores form>span {

                font-size: 10px !important;
                flex-shrink: 0 !important;
            }


            /* =====================================================
               BOTONES BUSCAR / TODOS
               ===================================================== */

            .reporte-atrasos-responsive .controles-superiores form>button,

            .reporte-atrasos-responsive .controles-superiores form>a {

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

            .reporte-atrasos-responsive .controles-superiores form>button svg,

            .reporte-atrasos-responsive .controles-superiores form>a svg {

                width: 13px !important;
                height: 13px !important;
            }


            /* =====================================================
               BOTONES EXCEL / PDF
               CONSERVAR ICONO + TEXTO
               ===================================================== */

            .reporte-atrasos-responsive .botones-exportacion {

                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                align-items: center !important;

                gap: 0.2rem !important;

                flex-shrink: 0 !important;
                width: auto !important;
            }

            .reporte-atrasos-responsive .botones-exportacion a {

                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;

                white-space: nowrap !important;

                padding: 0.3rem 0.45rem !important;

                font-size: 9px !important;

                gap: 0.2rem !important;

                border-radius: 0.4rem !important;
            }

            .reporte-atrasos-responsive .botones-exportacion svg {

                width: 12px !important;
                height: 12px !important;
                flex-shrink: 0 !important;
            }


            /* =====================================================
               TABLA
               ===================================================== */

            .reporte-atrasos-responsive .tabla-contenedor {

                width: 100% !important;
                overflow-x: hidden !important;
                overflow-y: auto !important;

                max-height: 64vh !important;
            }

            .reporte-atrasos-responsive .tabla-contenedor table {

                width: 100% !important;
                min-width: 0 !important;

                table-layout: fixed !important;

                font-size: 12px !important;
            }


            /* =====================================================
               ENCABEZADOS
               ===================================================== */

            .reporte-atrasos-responsive .tabla-contenedor thead th {

                padding: 0.45rem 0.25rem !important;

                font-size: 9px !important;

                line-height: 1.1 !important;

                white-space: normal !important;
            }


            /* =====================================================
               OCULTAR ORIGEN Y TURNO
               ===================================================== */

            .reporte-atrasos-responsive .col-origen,

            .reporte-atrasos-responsive .col-turno {

                display: none !important;
            }


            /* =====================================================
               ANCHOS DE COLUMNAS
               ===================================================== */

            .reporte-atrasos-responsive .col-cliente {

                width: 38% !important;
            }

            .reporte-atrasos-responsive .col-tanda {

                width: 27% !important;
            }

            .reporte-atrasos-responsive .col-cuotas {

                width: 17% !important;
            }

            .reporte-atrasos-responsive .col-monto {

                width: 18% !important;
            }


            /* =====================================================
               CELDAS
               ===================================================== */

            .reporte-atrasos-responsive .tabla-contenedor tbody td {

                padding: 0.45rem 0.25rem !important;

                font-size: 11px !important;

                line-height: 1.2 !important;

                overflow: hidden !important;
            }


            /* Nombre cliente */

            .reporte-atrasos-responsive .cliente-nombre {

                font-size: 12px !important;
                line-height: 1.15 !important;

                white-space: normal !important;
                word-break: break-word !important;
            }

            /* Teléfono */

            .reporte-atrasos-responsive .cliente-telefono {

                font-size: 9px !important;
                line-height: 1.1 !important;
            }

            /* Tanda */

            .reporte-atrasos-responsive .tanda-nombre {

                font-size: 11px !important;
                line-height: 1.15 !important;

                white-space: normal !important;
                word-break: break-word !important;
            }

            /* Cuotas */

            .reporte-atrasos-responsive .cuotas-badge {

                font-size: 9px !important;

                padding: 0.2rem 0.3rem !important;

                white-space: normal !important;

                line-height: 1.1 !important;
            }

            /* Monto */

            .reporte-atrasos-responsive .monto-vencido {

                font-size: 13px !important;

                line-height: 1.1 !important;

                white-space: nowrap !important;
            }
        }


        /* =========================================================
           MOVILES MUY PEQUEÑOS
           ========================================================= */

        @media (max-width: 500px) {

            /* -----------------------------------------------------
               TARJETAS
               ----------------------------------------------------- */

            .reporte-atrasos-responsive .tarjetas-totales {

                gap: 0.25rem !important;
            }

            .reporte-atrasos-responsive .tarjeta-total {

                padding: 0.4rem 0.3rem !important;
                padding-right: 1.65rem !important;
            }

            .reporte-atrasos-responsive .tarjeta-total .titulo-tarjeta {

                font-size: 8.5px !important;
            }

            .reporte-atrasos-responsive .tarjeta-total .valor-tarjeta {

                font-size: 16px !important;
            }

            .reporte-atrasos-responsive .tarjeta-total .descripcion-tarjeta {

                font-size: 8px !important;
            }

            .reporte-atrasos-responsive .tarjeta-total>div:first-child {

                padding: 0.2rem !important;
            }

            .reporte-atrasos-responsive .tarjeta-total>div:first-child svg {

                width: 14px !important;
                height: 14px !important;
            }


            /* -----------------------------------------------------
               CONTROLES SUPERIORES
               ----------------------------------------------------- */

            .reporte-atrasos-responsive .controles-superiores {

                gap: 0.15rem !important;
            }

            .reporte-atrasos-responsive .controles-superiores form {

                gap: 0.12rem !important;
            }

            /* Fechas más pequeñas */

            .reporte-atrasos-responsive .controles-superiores form>div:first-child,

            .reporte-atrasos-responsive .controles-superiores form>div:nth-child(3) {

                flex: 0 1 82px !important;
                width: 82px !important;
            }

            .reporte-atrasos-responsive .controles-superiores input[type="date"] {

                height: 24px !important;

                font-size: 8px !important;

                padding-left: 0.15rem !important;
                padding-right: 0.15rem !important;
            }

            .reporte-atrasos-responsive .controles-superiores form>span {

                font-size: 8px !important;
            }


            /* -----------------------------------------------------
               BOTONES BUSCAR / TODOS
               ----------------------------------------------------- */

            .reporte-atrasos-responsive .controles-superiores form>button,

            .reporte-atrasos-responsive .controles-superiores form>a {

                width: 24px !important;
                min-width: 24px !important;
                max-width: 24px !important;

                height: 24px !important;
                min-height: 24px !important;
                max-height: 24px !important;
            }

            .reporte-atrasos-responsive .controles-superiores form>button svg,

            .reporte-atrasos-responsive .controles-superiores form>a svg {

                width: 11px !important;
                height: 11px !important;
            }


            /* -----------------------------------------------------
               EXCEL / PDF
               ----------------------------------------------------- */

            .reporte-atrasos-responsive .botones-exportacion {

                gap: 0.12rem !important;
            }

            .reporte-atrasos-responsive .botones-exportacion a {

                padding: 0.25rem 0.3rem !important;

                font-size: 8px !important;

                gap: 0.15rem !important;
            }

            .reporte-atrasos-responsive .botones-exportacion svg {

                width: 10px !important;
                height: 10px !important;
            }


            /* -----------------------------------------------------
               TABLA
               ----------------------------------------------------- */

            .reporte-atrasos-responsive .tabla-contenedor table {

                font-size: 11.5px !important;
            }

            .reporte-atrasos-responsive .tabla-contenedor thead th {

                padding: 0.4rem 0.2rem !important;

                font-size: 8px !important;
            }

            .reporte-atrasos-responsive .tabla-contenedor tbody td {

                padding: 0.4rem 0.2rem !important;

                font-size: 10.5px !important;
            }

            .reporte-atrasos-responsive .cliente-nombre {

                font-size: 11.5px !important;
            }

            .reporte-atrasos-responsive .cliente-telefono {

                font-size: 8px !important;
            }

            .reporte-atrasos-responsive .tanda-nombre {

                font-size: 10.5px !important;
            }

            .reporte-atrasos-responsive .cuotas-badge {

                font-size: 8px !important;

                padding: 0.18rem 0.25rem !important;
            }

            .reporte-atrasos-responsive .monto-vencido {

                font-size: 12px !important;
            }
        }
    </style>


    <!-- ========================================================= -->
    <!-- CONTENEDOR PRINCIPAL -->
    <!-- ========================================================= -->

    <div class="py-4 reporte-atrasos-responsive">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- ===================================================== -->
            <!-- TARJETAS SUPERIORES -->
            <!-- ===================================================== -->

            <div class="tarjetas-totales flex flex-wrap items-center gap-3 mb-4">


                <!-- =================================================
                     TARJETA 1
                     ================================================= -->

                <div
                    class="tarjeta-total relative w-full sm:w-52 bg-white border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">

                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-gray-100 rounded-lg text-gray-600 flex items-center justify-center pointer-events-none">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />

                        </svg>

                    </div>

                    <div>

                        <div class="titulo-tarjeta text-[11px] font-bold uppercase tracking-wider text-gray-500">

                            Clientes con Atraso

                        </div>

                        <div class="valor-tarjeta text-xl font-black text-gray-900 leading-none my-1">

                            {{ $busquedaRealizada ? $clientesConRetraso->count() : 0 }}

                        </div>

                        <div class="descripcion-tarjeta text-xs text-gray-500 font-bold">

                            Total de afectados

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     TARJETA 2
                     ================================================= -->

                <div
                    class="tarjeta-total relative w-full sm:w-52 bg-amber-50 border border-amber-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">

                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-amber-100 rounded-lg text-amber-600 flex items-center justify-center pointer-events-none">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                    </div>

                    <div>

                        <div class="titulo-tarjeta text-[11px] font-bold uppercase tracking-wider text-amber-700">

                            Cuotas Atrasadas

                        </div>

                        <div class="valor-tarjeta text-xl font-black text-amber-600 leading-none my-1">

                            {{ $busquedaRealizada ? $clientesConRetraso->sum('cuotas_atrasadas') : 0 }}

                        </div>

                        <div class="descripcion-tarjeta text-xs text-amber-700 font-bold">

                            Total de pagos vencidos

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     TARJETA 3
                     ================================================= -->

                <div
                    class="tarjeta-total relative w-full sm:w-52 bg-red-50 border border-red-200 overflow-hidden shadow-sm sm:rounded-lg px-3 py-2 pr-9 flex flex-col justify-between">

                    <div
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-red-100 rounded-lg text-red-600 flex items-center justify-center pointer-events-none">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                    </div>

                    <div>

                        <div class="titulo-tarjeta text-[11px] font-bold uppercase tracking-wider text-red-700">

                            Monto Vencido

                        </div>

                        <div class="valor-tarjeta text-xl font-black text-red-700 leading-none my-1">

                            ${{ $busquedaRealizada ? number_format($clientesConRetraso->sum('monto_retrasado'), 0) : '0.00' }}

                        </div>

                        <div class="descripcion-tarjeta text-xs text-red-600 font-bold">

                            Suma total de deuda

                        </div>

                    </div>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- CONTENEDOR DE TABLA -->
            <!-- ========================================================= -->

            <div class="contenedor-reporte bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">


                <!-- =====================================================
                     ACCESOS SUPERIORES
                     ===================================================== -->

                <div class="controles-superiores flex flex-col sm:flex-row justify-between items-center gap-3 mb-3">


                    <!-- =================================================
                         FILTRO POR FECHAS
                         ================================================= -->

                    <form method="GET" action="{{ route('tandas.reporte.atrasos') }}"
                        class="flex flex-wrap items-center gap-2 w-full sm:w-auto">


                        <!-- Fecha inicio -->

                        <div>

                            <input type="date" name="fecha_inicio" value="{{ $fechaInicio ?? request('fecha_inicio') }}"
                                class="text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2">

                        </div>


                        <!-- Separador -->

                        <span class="text-gray-300">
                            -
                        </span>


                        <!-- Fecha fin -->

                        <div>

                            <input type="date" name="fecha_fin" value="{{ $fechaFin ?? request('fecha_fin') }}"
                                class="text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1.5 px-2">

                        </div>


                        <!-- Buscar -->

                        <button type="submit" title="Filtrar por fechas"
                            class="inline-flex items-center justify-center p-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                            </svg>

                        </button>


                        <!-- Ver todos -->

                        <a href="{{ route('tandas.reporte.atrasos', ['filtro' => 'todos']) }}"
                            title="Ver todos sin filtro"
                            class="inline-flex items-center justify-center p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />

                            </svg>

                        </a>

                    </form>


                    <!-- =================================================
                         BOTONES DE EXPORTACION
                         ================================================= -->

                    <div class="botones-exportacion flex items-center gap-2">


                        <!-- Excel -->

                        <a href="{{ route('tandas.reporte.atrasos.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold shadow-xs transition">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />

                            </svg>

                            Excel

                        </a>


                        <!-- PDF -->

                        <a href="{{ route('tandas.reporte.atrasos.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold shadow-xs transition">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                            </svg>

                            PDF

                        </a>

                    </div>

                </div>


                <!-- ========================================================= -->
                <!-- TABLA -->
                <!-- ========================================================= -->

                <div class="tabla-contenedor overflow-y-auto overflow-x-auto relative rounded-lg border border-gray-100"
                    style="max-height: 64vh;">

                    <table class="min-w-full divide-y divide-gray-200 text-sm">


                        <!-- =================================================
                             CABECERA
                             ================================================= -->

                        <thead
                            class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider sticky top-0 z-10 shadow-sm">

                            <tr>

                                <th class="col-cliente px-4 py-2.5 text-left bg-gray-50">

                                    Cliente

                                </th>


                                <th class="col-origen px-4 py-2.5 text-left bg-gray-50">

                                    Origen

                                </th>


                                <th class="col-tanda px-4 py-2.5 text-left bg-gray-50">

                                    Tanda

                                </th>


                                <th class="col-turno px-4 py-2.5 text-center bg-gray-50">

                                    Turno

                                </th>


                                <th class="col-cuotas px-4 py-2.5 text-center bg-gray-50">

                                    Cuotas Vencidas

                                </th>


                                <th class="col-monto px-4 py-2.5 text-right bg-gray-50">

                                    Monto Vencido

                                </th>

                            </tr>

                        </thead>


                        <!-- =================================================
                             CUERPO
                             ================================================= -->

                        <tbody class="divide-y divide-gray-100 bg-white">


                            @if(!$busquedaRealizada)

                                <tr>

                                    <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">

                                        🔍 Seleccione un rango de fechas o presione el botón de listar todo para mostrar el
                                        reporte de atrasos.

                                    </td>

                                </tr>


                            @else

                                @forelse($clientesConRetraso as $item)

                                    <tr class="hover:bg-red-50/50 transition-colors">


                                        <!-- =================================================
                                                             CLIENTE
                                                             ================================================= -->

                                        <td class="col-cliente px-4 py-2 whitespace-nowrap">

                                            <div class="cliente-nombre font-bold text-gray-900 text-sm leading-tight">

                                                {{ $item['cliente_nombre'] }}

                                            </div>

                                            <div class="cliente-telefono text-xs text-gray-400 font-normal">

                                                Tel: {{ $item['telefono'] }}

                                            </div>

                                        </td>


                                        <!-- =================================================
                                                             ORIGEN
                                                             ================================================= -->

                                        <td class="col-origen px-4 py-2 whitespace-nowrap">

                                            <div class="text-xs text-gray-600 font-medium">

                                                {{ $item['origen'] ?? 'N/D' }}

                                            </div>

                                        </td>


                                        <!-- =================================================
                                                             TANDA
                                                             ================================================= -->

                                        <td class="col-tanda px-4 py-2 whitespace-nowrap">

                                            <div class="tanda-nombre text-sm text-indigo-600 font-semibold leading-tight">

                                                {{ $item['tanda_nombre'] }}

                                            </div>

                                        </td>


                                        <!-- =================================================
                                                             TURNO
                                                             ================================================= -->

                                        <td class="col-turno px-4 py-2 whitespace-nowrap text-center">

                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium">

                                                Turno {{ $item['turno'] }}

                                            </span>

                                        </td>


                                        <!-- =================================================
                                                             CUOTAS VENCIDAS
                                                             ================================================= -->

                                        <td class="col-cuotas px-4 py-2 whitespace-nowrap text-center">

                                            <span
                                                class="cuotas-badge px-2 py-0.5 text-xs font-bold bg-red-100 text-red-700 rounded-full inline-block">

                                                {{ $item['cuotas_atrasadas'] }}

                                                {{ $item['cuotas_atrasadas'] === 1 ? 'Vencida' : 'Vencidas' }}

                                            </span>

                                        </td>


                                        <!-- =================================================
                                                             MONTO VENCIDO
                                                             ================================================= -->

                                        <td
                                            class="col-monto px-4 py-2 whitespace-nowrap text-right font-black text-red-600 text-base">

                                            <span class="monto-vencido">

                                                ${{ number_format($item['monto_retrasado'], 0) }}

                                            </span>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td colspan="6" class="px-4 py-12 text-center text-gray-500 text-sm">

                                            🎉 ¡Excelente noticia! No hay registros de atrasos para este periodo.

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