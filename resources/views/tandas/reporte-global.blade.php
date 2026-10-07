<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reporte Global de Tandas
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    Resumen financiero consolidado de todas las tandas activas e históricas
                </p>

            </div>

        </div>

    </x-slot>


    <style>

        /* =========================================================
           RESPONSIVE GENERAL
           ========================================================= */

        .reporte-responsive {
            width: 100%;
        }

        .reporte-tabla-container {
            container-type: inline-size;
            container-name: reporteTandas;
        }


        /* =========================================================
           TARJETAS KPI
           ========================================================= */

        .kpi-titulo-movil {
            display: none;
        }

        .kpi-valor-movil {
            display: none;
        }

        .kpi-descripcion {
            line-height: 1.2;
            min-height: 14px;
        }


        /* =========================================================
           MEDIANAS
           ========================================================= */

        @media (max-width: 1200px) {

            .reporte-responsive .kpi-card {
                padding: 0.65rem;
            }

            .reporte-responsive .kpi-titulo {
                font-size: 9px;
                line-height: 1.15;
            }

            .reporte-responsive .kpi-valor {
                font-size: 0.9rem;
            }

            .reporte-responsive .kpi-descripcion {
                font-size: 9px;
                line-height: 1.15;
            }

            .reporte-responsive .control-responsive {
                font-size: 10px;
            }

            .reporte-responsive table {
                font-size: 11px;
            }

            .reporte-responsive th,
            .reporte-responsive td {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
                padding-top: 0.55rem;
                padding-bottom: 0.55rem;
            }

            .reporte-responsive .accion-responsive {
                font-size: 10px;
                padding: 0.3rem 0.5rem;
            }

        }


        /* =========================================================
           TABLA MEDIANA
           ========================================================= */

        @container reporteTandas (max-width: 1099px) {

            .col-inicio,
            .col-fondo,
            .col-utilidad {
                display: none !important;
            }

        }


        /* =========================================================
           VENTANAS CHICAS / MOVIL
           ========================================================= */

        @container reporteTandas (max-width: 799px) {

            .col-participantes,
            .col-inicio,
            .col-fondo,
            .col-entregado,
            .col-utilidad {
                display: none !important;
            }


            /* =====================================================
               TÍTULO TANDA
               ===================================================== */

            .titulo-tanda-completo {
                display: none !important;
            }

            .titulo-tanda-movil {
                display: inline !important;
            }


            /* =====================================================
               TÍTULOS CORTOS KPI
               ===================================================== */

            .kpi-titulo {
                display: none !important;
            }

            .kpi-titulo-movil {
                display: block !important;
            }


            /* =====================================================
               DESCRIPCIONES
               ===================================================== */

            .kpi-descripcion {
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: unset !important;
                line-height: 1.1 !important;
                min-height: 0 !important;
            }


            /* =====================================================
               TABLA COMPACTA
               ===================================================== */

            .tabla-responsive th,
            .tabla-responsive td {
                padding-left: 0.45rem !important;
                padding-right: 0.45rem !important;
                padding-top: 0.45rem !important;
                padding-bottom: 0.45rem !important;
            }

            .tabla-responsive thead {
                font-size: 9px !important;
            }

            .tabla-responsive tbody {
                font-size: 10px !important;
            }

            .tabla-responsive .tanda-nombre {
                font-size: 11px !important;
                line-height: 1.15 !important;
            }

            .tabla-responsive .tanda-detalle {
                font-size: 9px !important;
                line-height: 1.1 !important;
            }

            .tabla-responsive .estado-tanda {
                font-size: 9px !important;
                padding-left: 0.4rem !important;
                padding-right: 0.4rem !important;
            }

            .tabla-responsive .accion-responsive {
                font-size: 9px !important;
                padding: 0.25rem 0.4rem !important;
                white-space: nowrap;
            }

        }


        /* =========================================================
           MOVILES / VENTANAS MUY CHICAS
           5 TARJETAS EN UNA SOLA FILA
           ========================================================= */

        @media (max-width: 650px) {

            .reporte-responsive .grid.grid-cols-5 {
                gap: 0.35rem !important;
            }


            /* =====================================================
               TARJETAS KPI
               ===================================================== */

            .reporte-responsive .kpi-card {

                padding: 0.35rem 0.2rem !important;

                border-radius: 0.55rem !important;

                min-width: 0 !important;

                overflow: hidden;
            }


            /* =====================================================
               TÍTULOS CORTOS
               ===================================================== */

            .reporte-responsive .kpi-titulo {
                display: none !important;
            }

            .reporte-responsive .kpi-titulo-movil {

                display: block !important;

                font-size: 7px !important;

                line-height: 1 !important;

                letter-spacing: 0 !important;

                margin-bottom: 2px !important;

                white-space: nowrap !important;

                overflow: hidden !important;

                text-overflow: clip !important;

                text-align: center;
            }


            /* =====================================================
               VALOR ESCRITORIO OCULTO
               ===================================================== */

            .reporte-responsive .kpi-valor-escritorio {
                display: none !important;
            }


            /* =====================================================
               VALOR MOVIL SIN DECIMALES
               ===================================================== */

            .reporte-responsive .kpi-valor-movil {

                display: block !important;

                font-size: 0.68rem !important;

                line-height: 1.05 !important;

                margin-bottom: 0 !important;

                white-space: nowrap !important;

                text-align: center;
            }


            /* =====================================================
               DESCRIPCIONES OCULTAS
               ===================================================== */

            .reporte-responsive .kpi-descripcion {
                display: none !important;
            }


            /* =====================================================
               CONTENEDOR GENERAL
               ===================================================== */

            .reporte-responsive .max-w-7xl {

                padding-left: 0.4rem;

                padding-right: 0.4rem;
            }


            /* =====================================================
               CONTENEDOR TABLA
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal {

                padding: 0.4rem !important;
            }


            /* =====================================================
               TABLA
               ===================================================== */

            .reporte-responsive .tabla-responsive th,
            .reporte-responsive .tabla-responsive td {

                padding-left: 0.3rem !important;

                padding-right: 0.3rem !important;

                padding-top: 0.35rem !important;

                padding-bottom: 0.35rem !important;
            }


            .reporte-responsive .tabla-responsive thead {

                font-size: 8px !important;

                letter-spacing: 0.02em !important;
            }


            .reporte-responsive .tabla-responsive tbody {

                font-size: 9px !important;
            }


            .reporte-responsive .tanda-nombre {

                font-size: 10px !important;
            }


            .reporte-responsive .tanda-detalle {

                font-size: 8px !important;
            }


            .reporte-responsive .estado-tanda {

                font-size: 8px !important;
            }


            .reporte-responsive .accion-responsive {

                font-size: 8px !important;

                padding: 0.2rem 0.3rem !important;
            }

        }


        /* =========================================================
           FILTROS Y EXPORTACIÓN
           VENTANAS CHICAS / MÓVILES
           TODO EN UNA SOLA FILA
           ========================================================= */

        @media (max-width: 799px) {

            /* =====================================================
               CONTENEDOR DE LA BARRA
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal > div:first-child {

                width: 100% !important;

                margin-bottom: 0.5rem !important;

                padding-bottom: 0.4rem !important;
            }


            /* =====================================================
               FORMULARIO COMPLETO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            > div:first-child form {

                width: 100% !important;

                display: flex !important;

                flex-direction: row !important;

                flex-wrap: nowrap !important;

                align-items: center !important;

                justify-content: space-between !important;

                gap: 0.18rem !important;

                min-width: 0 !important;
            }


            /* =====================================================
               FECHAS + BUSCAR + VER TODO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            > div:first-child form > div:first-child {

                display: flex !important;

                flex-direction: row !important;

                flex-wrap: nowrap !important;

                align-items: center !important;

                gap: 0.15rem !important;

                min-width: 0 !important;

                flex: 1 1 auto !important;
            }


            /* =====================================================
               CONTENEDOR INDIVIDUAL DE CADA FECHA
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            > div:first-child form > div:first-child > div {

                min-width: 0 !important;

                flex: 0 1 115px !important;

                width: 115px !important;
            }


            /* =====================================================
               INPUTS DE FECHA
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            input[type="date"] {

                width: 100% !important;

                min-width: 0 !important;

                height: 25px !important;

                padding: 0.1rem 0.2rem !important;

                font-size: 8px !important;

                line-height: 1 !important;

                border-radius: 0.35rem !important;
            }


            /* =====================================================
               BUSCAR / VER TODO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            form > div:first-child button,

            .reporte-responsive .tabla-contenedor-principal
            form > div:first-child a {

                width: 25px !important;

                min-width: 25px !important;

                max-width: 25px !important;

                height: 25px !important;

                min-height: 25px !important;

                max-height: 25px !important;

                padding: 0 !important;

                display: inline-flex !important;

                align-items: center !important;

                justify-content: center !important;

                flex-shrink: 0 !important;

                border-radius: 0.35rem !important;
            }


            /* =====================================================
               ICONOS BUSCAR / VER TODO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            form > div:first-child svg {

                width: 12px !important;

                height: 12px !important;
            }


            /* =====================================================
               EXCEL / PDF
               TEXTO + ICONO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            form > div:last-child {

                display: flex !important;

                flex-direction: row !important;

                flex-wrap: nowrap !important;

                align-items: center !important;

                gap: 0.15rem !important;

                flex-shrink: 0 !important;

                width: auto !important;
            }


            .reporte-responsive .tabla-contenedor-principal
            form > div:last-child a {

                width: auto !important;

                min-width: 0 !important;

                max-width: none !important;

                height: 25px !important;

                min-height: 25px !important;

                max-height: 25px !important;

                padding: 0 0.35rem !important;

                display: inline-flex !important;

                align-items: center !important;

                justify-content: center !important;

                gap: 0.2rem !important;

                border-radius: 0.35rem !important;

                font-size: 8px !important;

                white-space: nowrap !important;
            }


            .reporte-responsive .tabla-contenedor-principal
            form > div:last-child svg {

                width: 11px !important;

                height: 11px !important;

                flex-shrink: 0 !important;
            }

        }


        /* =========================================================
           MÓVILES MUY PEQUEÑOS
           ========================================================= */

        @media (max-width: 500px) {

            .reporte-responsive .tabla-contenedor-principal
            form {

                gap: 0.1rem !important;
            }


            .reporte-responsive .tabla-contenedor-principal
            form > div:first-child {

                gap: 0.1rem !important;
            }


            /* =====================================================
               FECHAS MÁS ANGOSTAS
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            > div:first-child form > div:first-child > div {

                flex: 0 1 85px !important;

                width: 85px !important;
            }


            .reporte-responsive .tabla-contenedor-principal
            input[type="date"] {

                height: 23px !important;

                font-size: 7px !important;

                padding-left: 0.1rem !important;

                padding-right: 0.1rem !important;
            }


            /* =====================================================
               BUSCAR / VER TODO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            form > div:first-child button,

            .reporte-responsive .tabla-contenedor-principal
            form > div:first-child a {

                width: 23px !important;

                min-width: 23px !important;

                max-width: 23px !important;

                height: 23px !important;

                min-height: 23px !important;

                max-height: 23px !important;
            }


            /* =====================================================
               ICONOS BUSCAR / VER TODO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            form > div:first-child svg {

                width: 11px !important;

                height: 11px !important;
            }


            /* =====================================================
               EXCEL / PDF
               MÁS PEQUEÑOS PERO CONSERVAN TEXTO
               ===================================================== */

            .reporte-responsive .tabla-contenedor-principal
            form > div:last-child {

                gap: 0.1rem !important;
            }


            .reporte-responsive .tabla-contenedor-principal
            form > div:last-child a {

                height: 23px !important;

                min-height: 23px !important;

                max-height: 23px !important;

                padding: 0 0.25rem !important;

                gap: 0.15rem !important;

                font-size: 7px !important;

                border-radius: 0.3rem !important;
            }


            .reporte-responsive .tabla-contenedor-principal
            form > div:last-child svg {

                width: 10px !important;

                height: 10px !important;
            }

        }

    </style>


    <div class="reporte-responsive py-2">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-3">


            <!-- =====================================================
                 TARJETAS DE MÉTRICAS GLOBALES
                 ===================================================== -->

            <div class="grid grid-cols-5 gap-1.5 sm:gap-3">


                <!-- =================================================
                     FONDO
                     ================================================= -->

                <div class="kpi-card bg-white p-3 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">

                    <div class="kpi-titulo text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-0.5">
                        Fondo Global Total
                    </div>

                    <div class="kpi-titulo-movil text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-0.5">
                        Fondo
                    </div>

                    <div class="kpi-valor-escritorio kpi-valor text-base font-bold text-gray-900 mb-0.5">
                        ${{ number_format($totalFondoGlobal, 0) }}
                    </div>

                    <div class="kpi-valor-movil kpi-valor text-base font-bold text-gray-900 mb-0.5">
                        ${{ number_format($totalFondoGlobal, 0) }}
                    </div>

                    <div class="kpi-descripcion text-[11px] text-gray-400 truncate">
                        Suma total de todas las tandas
                    </div>

                </div>


                <!-- =================================================
                     COBRADO
                     ================================================= -->

                <div class="kpi-card bg-emerald-50/40 p-3 rounded-xl border border-emerald-100 shadow-sm flex flex-col justify-between">

                    <div class="kpi-titulo text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-0.5">
                        Total Cobrado
                    </div>

                    <div class="kpi-titulo-movil text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-0.5">
                        Cobrado
                    </div>

                    <div class="kpi-valor-escritorio kpi-valor text-base font-bold text-emerald-600 mb-0.5">
                        ${{ number_format($totalRecaudadoGlobal, 0) }}
                    </div>

                    <div class="kpi-valor-movil kpi-valor text-base font-bold text-emerald-600 mb-0.5">
                        ${{ number_format($totalRecaudadoGlobal, 0) }}
                    </div>

                    <div class="kpi-descripcion text-[11px] text-emerald-600/80 truncate">
                        Ingresos reales en caja
                    </div>

                </div>


                <!-- =================================================
                     ENTREGADO
                     ================================================= -->

                <div class="kpi-card bg-blue-50/40 p-3 rounded-xl border border-blue-100 shadow-sm flex flex-col justify-between">

                    <div class="kpi-titulo text-[10px] font-bold text-blue-700 uppercase tracking-wider mb-0.5">
                        Total Entregado
                    </div>

                    <div class="kpi-titulo-movil text-[10px] font-bold text-blue-700 uppercase tracking-wider mb-0.5">
                        Entregado
                    </div>

                    <div class="kpi-valor-escritorio kpi-valor text-base font-bold text-blue-600 mb-0.5">
                        ${{ number_format($totalEntregadoGlobal, 0) }}
                    </div>

                    <div class="kpi-valor-movil kpi-valor text-base font-bold text-blue-600 mb-0.5">
                        ${{ number_format($totalEntregadoGlobal, 0) }}
                    </div>

                    <div class="kpi-descripcion text-[11px] text-blue-600/80 truncate">
                        Pozos entregados a participantes
                    </div>

                </div>


                <!-- =================================================
                     UTILIDAD
                     ================================================= -->

                <div class="kpi-card bg-indigo-50/40 p-3 rounded-xl border border-indigo-100 shadow-sm flex flex-col justify-between">

                    <div class="kpi-titulo text-[10px] font-bold text-indigo-700 uppercase tracking-wider mb-0.5">
                        Balance / Utilidad
                    </div>

                    <div class="kpi-titulo-movil text-[10px] font-bold text-indigo-700 uppercase tracking-wider mb-0.5">
                        Utilidad
                    </div>

                    <div class="kpi-valor-escritorio kpi-valor text-base font-bold {{ $balanceNeto >= 0 ? 'text-indigo-600' : 'text-rose-600' }} mb-0.5">
                        ${{ number_format($balanceNeto, 0) }}
                    </div>

                    <div class="kpi-valor-movil kpi-valor text-base font-bold {{ $balanceNeto >= 0 ? 'text-indigo-600' : 'text-rose-600' }} mb-0.5">
                        ${{ number_format($balanceNeto, 0) }}
                    </div>

                    <div class="kpi-descripcion text-[11px] text-indigo-600/80 truncate">
                        Flujo neto acumulado
                    </div>

                </div>


                <!-- =================================================
                     VENCIDO
                     ================================================= -->

                <div class="kpi-card bg-rose-50/40 p-3 rounded-xl border border-rose-100 shadow-sm flex flex-col justify-between">

                    <div class="kpi-titulo text-[10px] font-bold text-rose-700 uppercase tracking-wider mb-0.5">
                        Vencido Global
                    </div>

                    <div class="kpi-titulo-movil text-[10px] font-bold text-rose-700 uppercase tracking-wider mb-0.5">
                        Vencido
                    </div>

                    <div class="kpi-valor-escritorio kpi-valor text-base font-bold text-rose-600 mb-0.5">
                        ${{ number_format($totalVencidoGlobal, 0) }}
                    </div>

                    <div class="kpi-valor-movil kpi-valor text-base font-bold text-rose-600 mb-0.5">
                        ${{ number_format($totalVencidoGlobal, 0) }}
                    </div>

                    <div class="kpi-descripcion text-[11px] text-rose-600/80 truncate">
                        Deuda retrasada total
                    </div>

                </div>

            </div>


            <!-- =====================================================
                 TABLA Y CONTROLES
                 ===================================================== -->

            <div class="tabla-contenedor-principal bg-white overflow-hidden shadow-sm sm:rounded-xl p-4">


                <!-- =================================================
                     FILTRO DE FECHAS Y BOTONES
                     ================================================= -->

                <div class="flex flex-col lg:flex-row justify-between items-center gap-2 mb-3 pb-2 border-b border-gray-100">

                    <form method="GET"
                        action="{{ route('tandas.reporte.global') }}"
                        class="flex flex-wrap items-center gap-2 w-full justify-between">


                        <!-- =================================================
                             FILTROS
                             ================================================= -->

                        <div class="flex flex-wrap items-center gap-2">


                            <!-- FECHA INICIO -->

                            <div>

                                <input type="date"
                                    name="fecha_inicio"
                                    id="fecha_inicio"
                                    value="{{ request('fecha_inicio') }}"
                                    required
                                    class="control-responsive text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">

                            </div>


                            <!-- FECHA FIN -->

                            <div>

                                <input type="date"
                                    name="fecha_fin"
                                    id="fecha_fin"
                                    value="{{ request('fecha_fin') }}"
                                    required
                                    class="control-responsive text-xs border-gray-300 rounded-lg shadow-xs focus:border-indigo-500 focus:ring-indigo-500 py-1 px-2">

                            </div>


                            <!-- BUSCAR -->

                            <button type="submit"
                                title="Filtrar por fechas"
                                class="inline-flex items-center justify-center p-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">

                                <svg class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                                </svg>

                            </button>


                            <!-- VER TODO -->

                            <a href="{{ route('tandas.reporte.global', ['todo' => 1]) }}"
                                title="Ver todo (sin fechas)"
                                class="inline-flex items-center justify-center p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">

                                <svg class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />

                                </svg>

                            </a>

                        </div>


                        <!-- =================================================
                             EXPORTACIONES
                             ================================================= -->

                        <div class="flex items-center gap-2">


                            <!-- EXCEL -->

                            <a href="{{ route('tandas.reporte.global.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold shadow-xs transition">

                                <svg class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />

                                </svg>

                                <span>Excel</span>

                            </a>


                            <!-- PDF -->

                            <a href="{{ route('tandas.reporte.global.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold shadow-xs transition">

                                <svg class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01-2 2V19a2 2 0 01-2 2z" />

                                </svg>

                                <span>PDF</span>

                            </a>

                        </div>

                    </form>

                </div>


                <!-- =====================================================
                     CONTENEDOR RESPONSIVE DE TABLA
                     ===================================================== -->

                <div class="reporte-tabla-container bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">


                    <table class="tabla-responsive w-full text-left border-collapse">


                        <thead>

                            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">


                                <!-- TANDA -->

                                <th class="col-tanda px-4 py-3">

                                    <span class="titulo-tanda-completo">
                                        Nombre de la Tanda
                                    </span>

                                    <span class="titulo-tanda-movil hidden">
                                        Tanda
                                    </span>

                                </th>


                                <!-- ESTADO -->

                                <th class="px-4 py-3 text-center">
                                    Estado
                                </th>


                                <!-- PARTICIPANTES -->

                                <th class="col-participantes px-4 py-3 text-center">
                                    Participantes
                                </th>


                                <!-- INICIO -->

                                <th class="col-inicio px-4 py-3 text-center">
                                    Inicio
                                </th>


                                <!-- TERMINO -->

                                <th class="col-termino px-4 py-3 text-center">
                                    Término
                                </th>


                                <!-- FONDO -->

                                <th class="col-fondo px-4 py-3 text-right">
                                    Fondo Total
                                </th>


                                <!-- COBRADO -->

                                <th class="col-cobrado px-4 py-3 text-right">
                                    Cobrado
                                </th>


                                <!-- ENTREGADO -->

                                <th class="col-entregado px-4 py-3 text-right">
                                    Entregado
                                </th>


                                <!-- UTILIDAD -->

                                <th class="col-utilidad px-4 py-3 text-right">
                                    Utilidad
                                </th>


                                <!-- ACCION -->

                                <th class="px-4 py-3 text-center">
                                    Acción
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100 bg-white">


                            @forelse($tandas as $tanda)


                                @php

                                    $participantesNormales = $tanda->participantes->filter(function ($p) {
                                        return $p->turno !== 0;
                                    });

                                    $numIntegrantes = $participantesNormales->count();

                                    if ($numIntegrantes === 0) {
                                        $numIntegrantes = $tanda->participantes->count();
                                    }

                                    $totalConCero = $tanda->participantes->count();


                                    $fondoTanda = $tanda->participantes->sum(function ($p) {
                                        return $p->cuotas->sum('monto_esperado');
                                    });


                                    $cobradoTanda = $tanda->participantes->sum(function ($p) {
                                        return $p->cuotas
                                            ->where('estado', 'pagado')
                                            ->sum('monto_pagado');
                                    });


                                    $cuotasPorCiclo = $tanda->cuotas_por_entrega ?? 4;


                                    $montoPozoCiclo =
                                        $cuotasPorCiclo *
                                        $tanda->monto_cuota *
                                        $numIntegrantes;


                                    $entregadoTanda = 0;


                                    foreach ($tanda->participantes as $p) {

                                        if ($p->entregado) {

                                            if ($p->turno === 0) {

                                                $entregadoTanda +=
                                                    $p->cuotas->sum('monto_esperado');

                                            } else {

                                                $entregadoTanda +=
                                                    $montoPozoCiclo;

                                            }

                                        }

                                    }


                                    $utilidadTanda =
                                        $cobradoTanda -
                                        $entregadoTanda;


                                    $estadoTanda =
                                        $tanda->estado ?? 'Activa';


                                    $todasLasCuotas =
                                        $tanda->participantes
                                            ->flatMap->cuotas
                                            ->sortBy('fecha_limite');


                                    $fechaInicio =
                                        $todasLasCuotas->isNotEmpty()
                                            ? \Carbon\Carbon::parse(
                                                $todasLasCuotas->first()->fecha_limite
                                            )->format('d/m/Y')
                                            : '-';


                                    $fechaTermino =
                                        $todasLasCuotas->isNotEmpty()
                                            ? \Carbon\Carbon::parse(
                                                $todasLasCuotas->last()->fecha_limite
                                            )->format('d/m/Y')
                                            : '-';

                                @endphp


                                <tr class="hover:bg-gray-50/75 transition-colors">


                                    <!-- TANDA -->

                                    <td class="px-4 py-2.5 font-bold text-gray-900">

                                        <div class="tanda-nombre">
                                            {{ $tanda->nombre }}
                                        </div>

                                        <div class="tanda-detalle text-xs font-normal text-gray-400">

                                            Cuota:
                                            ${{ number_format($tanda->monto_cuota, 2) }}

                                            ({{ ucfirst($tanda->frecuencia) }})

                                        </div>

                                    </td>


                                    <!-- ESTADO -->

                                    <td class="px-4 py-2.5 text-center">

                                        <span class="estado-tanda px-2.5 py-0.5 text-xs font-semibold rounded-full
                                            {{ strtolower($estadoTanda) == 'activa'
                                                ? 'bg-emerald-50 text-emerald-700'
                                                : 'bg-gray-100 text-gray-600' }}">

                                            {{ ucfirst($estadoTanda) }}

                                        </span>

                                    </td>


                                    <!-- PARTICIPANTES -->

                                    <td class="col-participantes px-4 py-2.5 text-center font-medium text-gray-700">

                                        {{ $numIntegrantes }}

                                        <span class="text-xs text-gray-400">
                                            ({{ $totalConCero }} con 0)
                                        </span>

                                    </td>


                                    <!-- INICIO -->

                                    <td class="col-inicio px-4 py-2.5 text-center text-xs text-gray-600 font-medium">

                                        {{ $fechaInicio }}

                                    </td>


                                    <!-- TERMINO -->

                                    <td class="col-termino px-4 py-2.5 text-center text-xs text-gray-600 font-medium">

                                        {{ $fechaTermino }}

                                    </td>


                                    <!-- FONDO -->

                                    <td class="col-fondo px-4 py-2.5 text-right font-semibold text-gray-900">

                                        ${{ number_format($fondoTanda, 2) }}

                                    </td>


                                    <!-- COBRADO -->

                                    <td class="col-cobrado px-4 py-2.5 text-right font-medium text-emerald-600">

                                        ${{ number_format($cobradoTanda, 2) }}

                                    </td>


                                    <!-- ENTREGADO -->

                                    <td class="col-entregado px-4 py-2.5 text-right font-medium text-blue-600">

                                        ${{ number_format($entregadoTanda, 2) }}

                                    </td>


                                    <!-- UTILIDAD -->

                                    <td class="col-utilidad px-4 py-2.5 text-right font-bold
                                        {{ $utilidadTanda >= 0
                                            ? 'text-indigo-600'
                                            : 'text-rose-600' }}">

                                        ${{ number_format($utilidadTanda, 2) }}

                                    </td>


                                    <!-- ACCION -->

                                    <td class="px-4 py-2.5 text-center">

                                        <a href="{{ route('tandas.show', $tanda->id) }}?origen=reporte"
                                            class="accion-responsive inline-flex items-center px-2.5 py-1 bg-gray-50 hover:bg-indigo-600 text-gray-700 hover:text-white border border-gray-200 hover:border-indigo-600 rounded-lg text-xs font-bold transition">

                                            Ver Tanda

                                        </a>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="10"
                                        class="px-4 py-6 text-center text-gray-400 text-xs">

                                        No hay tandas registradas en el sistema para este periodo.

                                    </td>

                                </tr>

                            @endforelse


                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>