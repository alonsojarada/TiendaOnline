<x-app-layout>

    <x-slot name="header">
        <div class="reporte-cuotas-header max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2
                class="reporte-cuotas-titulo font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                <span>🎯</span> Reporte de Cuotas Pagadas de Tandas
            </h2>

            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                Historial detallado de abonos y cuotas liquidadas
            </p>

        </div>
    </x-slot>


    <!-- ========================================================= -->
    <!-- ESTILOS RESPONSIVOS -->
    <!-- ========================================================= -->

    <style>
        /* ========================================================= */
/* SOLO TÍTULO RESPONSIVE */
/* ========================================================= */

.reporte-cuotas-header {
    container-type: inline-size;
}

.reporte-cuotas-titulo {
    font-size: 1.25rem;
    line-height: 1.25;
    overflow-wrap: break-word;
}

/* Ventanas chicas */
@container (max-width: 799px) {
    .reporte-cuotas-titulo {
        font-size: 0.95rem;
        line-height: 1.2;
    }
}

/* Móviles */
@container (max-width: 600px) {
    .reporte-cuotas-titulo {
        font-size: 0.85rem;
        line-height: 1.15;
    }
}

/* Móviles muy pequeños */
@container (max-width: 450px) {
    .reporte-cuotas-titulo {
        font-size: 0.75rem;
        line-height: 1.1;
    }
}
@container reporte-cuotas (max-width: 600px) {

    .total-cobrado-descripcion {
        display: none;
    }

}
        /*
        ============================================================
        CONTENEDOR DEL TITULO
        ============================================================
        */

        .header-reporte-cuotas {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding-left: 1rem;
            padding-right: 1rem;
            overflow: hidden;
        }

        .titulo-reporte-cuotas {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin: 0;
            padding: 0;
            font-weight: 600;
            font-size: 1.25rem;
            line-height: 1.35;
            color: rgb(31 41 55);
            min-width: 0;
        }

        .texto-titulo {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .emoji-titulo {
            flex-shrink: 0;
            line-height: 1;
        }

        .subtitulo-reporte-cuotas {
            margin: 0.125rem 0 0 0;
            font-size: 0.75rem;
            line-height: 1.2;
            color: rgb(107 114 128);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /*
        MODO OSCURO
        */

        .dark .titulo-reporte-cuotas {
            color: rgb(229 231 235);
        }

        .dark .subtitulo-reporte-cuotas {
            color: rgb(156 163 175);
        }


        /*
        ============================================================
        CONTENEDOR RESPONSIVO
        ============================================================
        */

        .reporte-cuotas-responsive {
            container-type: inline-size;
            container-name: reporte-cuotas;
        }


        /*
        ============================================================
        TABLA
        ============================================================
        */

        .tabla-cuotas {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .tabla-cuotas th,
        .tabla-cuotas td {
            transition:
                padding 0.15s ease,
                font-size 0.15s ease;
        }


        /*
        ============================================================
        BARRA SUPERIOR
        ============================================================
        */

        .barra-filtros-cuotas {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            width: 100%;
        }

        .filtros-cuotas {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 0.4rem;
            min-width: 0;
            flex: 1 1 auto;
        }

        .filtros-cuotas .campo-cliente {
            flex: 0 1 175px;
            min-width: 110px;
        }

        .filtros-cuotas .campo-fechas {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            flex: 0 1 auto;
            min-width: 0;
        }

        .filtros-cuotas input[type="date"] {
            width: 140px;
            min-width: 0;
        }

        .botones-exportacion {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-shrink: 0;
        }


        /*
        ============================================================
        VENTANA GRANDE
        1200px O MÁS DE ESPACIO REAL
        ============================================================
        */

        @container reporte-cuotas (min-width: 1200px) {

            .tabla-cuotas {
                min-width: 880px;
            }

            .tabla-cuotas th,
            .tabla-cuotas td {
                font-size: 12px;
            }

            .tabla-cuotas th {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .tabla-cuotas td {
                padding-top: 0.875rem;
                padding-bottom: 0.875rem;
            }
        }


        /*
        ============================================================
        VENTANA MEDIANA
        800px - 1199px
        ============================================================
        */

        @container reporte-cuotas (min-width: 800px) and (max-width: 1199px) {

            .tabla-cuotas {
                min-width: 0 !important;
                width: 100% !important;
            }

            .tabla-cuotas .col-fecha-limite,
            .tabla-cuotas .col-estatus {
                display: none !important;
            }

            .tabla-cuotas th,
            .tabla-cuotas td {
                font-size: 11px;
            }

            .tabla-cuotas th {
                padding: 0.55rem 0.6rem;
            }

            .tabla-cuotas td {
                padding: 0.65rem 0.6rem;
            }

            .tabla-cuotas .col-fecha-pago {
                width: 14%;
            }

            .tabla-cuotas .col-tanda {
                width: 20%;
            }

            .tabla-cuotas .col-cliente {
                width: 22%;
            }

            .tabla-cuotas .col-ciclo {
                width: 12%;
            }

            .tabla-cuotas .col-cobrado {
                width: 18%;
            }

            .tabla-cuotas .col-monto {
                width: 14%;
            }

            .barra-filtros-cuotas {
                gap: 0.3rem;
            }

            .filtros-cuotas {
                gap: 0.25rem;
            }

            .filtros-cuotas .campo-cliente {
                flex-basis: 145px;
            }

            .filtros-cuotas input[type="date"] {
                width: 115px;
                font-size: 10px;
                padding-left: 0.45rem;
                padding-right: 0.45rem;
            }

            .filtros-cuotas button {
                font-size: 10px;
                padding-left: 0.7rem;
                padding-right: 0.7rem;
            }

            .botones-exportacion button {
                font-size: 10px;
                padding-left: 0.7rem;
                padding-right: 0.7rem;
            }


            /*
            TITULO
            */

            .header-reporte-cuotas {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .titulo-reporte-cuotas {
                font-size: 1.1rem;
            }

            .subtitulo-reporte-cuotas {
                font-size: 0.7rem;
            }
        }


        /*
        ============================================================
        VENTANA PEQUEÑA
        MENOS DE 800px
        ============================================================
        */

        @container reporte-cuotas (max-width: 799px) {

            /*
            ========================================================
            TARJETA TOTAL COBRADO
            ========================================================
            */

            .reporte-cuotas-responsive>div:first-child {
                width: 210px !important;
                margin-bottom: 0.5rem !important;
            }

            .reporte-cuotas-responsive>div:first-child>div {
                padding: 0.45rem 0.75rem !important;
                border-radius: 0.9rem !important;
            }

            .reporte-cuotas-responsive>div:first-child span:first-child {
                font-size: 8px !important;
                letter-spacing: 0.04em !important;
            }

            .reporte-cuotas-responsive>div:first-child div.text-2xl {
                font-size: 1.15rem !important;
                margin-top: 0.2rem !important;
                margin-bottom: 0.2rem !important;
            }

            .reporte-cuotas-responsive>div:first-child span:last-child {
                font-size: 8px !important;
            }


            /*
            ========================================================
            TITULO
            ========================================================
            */

            .header-reporte-cuotas {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .titulo-reporte-cuotas {
                font-size: 1rem;
                line-height: 1.2;
                gap: 0.3rem;
            }

            .subtitulo-reporte-cuotas {
                font-size: 0.65rem;
                margin-top: 0.1rem;
            }


            /*
            ========================================================
            TABLA
            ========================================================
            */

            .tabla-cuotas {
                min-width: 0 !important;
                width: 100% !important;
                table-layout: fixed !important;
            }

            .tabla-cuotas .col-fecha-limite,
            .tabla-cuotas .col-ciclo,
            .tabla-cuotas .col-estatus,
            .tabla-cuotas .col-cobrado {
                display: none !important;
            }

            .tabla-cuotas .col-fecha-pago {
                width: 22%;
            }

            .tabla-cuotas .col-tanda {
                width: 28%;
            }

            .tabla-cuotas .col-cliente {
                width: 31%;
            }

            .tabla-cuotas .col-monto {
                width: 19%;
            }

            .tabla-cuotas th {
                padding: 0.5rem 0.4rem !important;
                font-size: 10px !important;
                line-height: 1.15 !important;
                white-space: normal;
            }

            .tabla-cuotas td {
                padding: 0.6rem 0.4rem !important;
                font-size: 11px !important;
                line-height: 1.2 !important;
            }

            .tabla-cuotas td,
            .tabla-cuotas th {
                overflow: hidden;
                text-overflow: ellipsis;
            }


            /*
            ========================================================
            BARRA SUPERIOR
            ========================================================
            */

            .barra-filtros-cuotas {
                width: 100%;
                gap: 0.2rem;
                flex-wrap: nowrap;
            }

            .filtros-cuotas {
                width: 100%;
                flex-wrap: nowrap;
                gap: 0.18rem;
            }

            .filtros-cuotas .campo-cliente {
                flex: 1 1 105px;
                width: 105px;
                min-width: 70px;
            }

            .filtros-cuotas .campo-fechas {
                flex: 1 1 auto;
                gap: 0.15rem;
            }

            .filtros-cuotas input[type="date"] {
                width: 90px;
                height: 27px;
                padding: 0.15rem 0.25rem;
                font-size: 8px;
                border-radius: 0.45rem;
            }

            .filtros-cuotas .separador-fechas {
                font-size: 9px;
            }

            .filtros-cuotas .btn-buscar {
                width: 28px;
                min-width: 28px;
                max-width: 28px;
                height: 27px;
                padding: 0;
                border-radius: 0.45rem;
            }

            .filtros-cuotas .btn-buscar span {
                display: none !important;
            }

            .filtros-cuotas .btn-buscar svg {
                width: 13px;
                height: 13px;
            }

            .botones-exportacion {
                gap: 0.15rem;
            }

            .botones-exportacion button {
                width: auto;
                height: 27px;
                padding: 0 0.45rem;
                font-size: 8px;
                gap: 0.2rem;
                border-radius: 0.45rem;
            }

            .botones-exportacion svg {
                width: 12px;
                height: 12px;
            }
        }


        /*
        ============================================================
        MUY PEQUEÑO
        MENOS DE 600px
        ============================================================
        */

        @container reporte-cuotas (max-width: 600px) {

            /*
            TARJETA
            */

            .reporte-cuotas-responsive>div:first-child {
                width: 190px !important;
                margin-bottom: 0.45rem !important;
            }

            .reporte-cuotas-responsive>div:first-child>div {
                padding: 0.4rem 0.65rem !important;
                border-radius: 0.8rem !important;
            }

            .reporte-cuotas-responsive>div:first-child span:first-child {
                font-size: 7px !important;
            }

            .reporte-cuotas-responsive>div:first-child div.text-2xl {
                font-size: 1rem !important;
            }

            .reporte-cuotas-responsive>div:first-child span:last-child {
                font-size: 7px !important;
            }


            /*
            TITULO
            */

            .header-reporte-cuotas {
                padding-left: 0.35rem;
                padding-right: 0.35rem;
            }

            .titulo-reporte-cuotas {
                font-size: 0.9rem;
                gap: 0.25rem;
            }

            .subtitulo-reporte-cuotas {
                font-size: 0.6rem;
            }


            /*
            TABLA
            */

            .tabla-cuotas th {
                padding: 0.4rem 0.3rem !important;
                font-size: 9px !important;
            }

            .tabla-cuotas td {
                padding: 0.5rem 0.3rem !important;
                font-size: 10px !important;
            }


            /*
            CAMPOS SUPERIORES
            */

            .filtros-cuotas .campo-cliente {
                flex-basis: 82px;
                width: 82px;
            }

            .filtros-cuotas input[type="date"] {
                width: 75px;
                height: 25px;
                font-size: 7px;
            }

            .filtros-cuotas .btn-buscar {
                width: 25px;
                min-width: 25px;
                max-width: 25px;
                height: 25px;
            }

            .filtros-cuotas .btn-buscar svg {
                width: 11px;
                height: 11px;
            }

            .botones-exportacion button {
                height: 25px;
                padding: 0 0.3rem;
                font-size: 7px;
            }

            .botones-exportacion svg {
                width: 11px;
                height: 11px;
            }
        }


        /*
        ============================================================
        CELULARES MUY PEQUEÑOS
        MENOS DE 450px
        ============================================================
        */

        @container reporte-cuotas (max-width: 450px) {

            /*
            TARJETA
            */

            .reporte-cuotas-responsive>div:first-child {
                width: 170px !important;
                margin-bottom: 0.4rem !important;
            }

            .reporte-cuotas-responsive>div:first-child>div {
                padding: 0.35rem 0.55rem !important;
            }

            .reporte-cuotas-responsive>div:first-child span:first-child {
                font-size: 6.5px !important;
            }

            .reporte-cuotas-responsive>div:first-child div.text-2xl {
                font-size: 0.9rem !important;
            }

            .reporte-cuotas-responsive>div:first-child span:last-child {
                font-size: 6.5px !important;
            }


            /*
            TITULO
            */

            .header-reporte-cuotas {
                padding-left: 0.2rem;
                padding-right: 0.2rem;
            }

            .titulo-reporte-cuotas {
                font-size: 0.78rem;
                line-height: 1.1;
                gap: 0.2rem;
            }

            .subtitulo-reporte-cuotas {
                font-size: 0.52rem;
                margin-top: 0.05rem;
            }


            /*
            TABLA
            */

            .tabla-cuotas th {
                padding: 0.35rem 0.2rem !important;
                font-size: 8px !important;
            }

            .tabla-cuotas td {
                padding: 0.45rem 0.2rem !important;
                font-size: 9px !important;
            }


            /*
            PARTE SUPERIOR
            */

            .filtros-cuotas {
                gap: 0.1rem;
            }

            .filtros-cuotas .campo-cliente {
                flex-basis: 70px;
                width: 70px;
            }

            .filtros-cuotas input[type="date"] {
                width: 66px;
                height: 24px;
                font-size: 6.5px;
            }

            .filtros-cuotas .btn-buscar {
                width: 24px;
                min-width: 24px;
                max-width: 24px;
                height: 24px;
            }

            .botones-exportacion button {
                height: 24px;
                font-size: 6.5px;
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
        }
    </style>


    <!-- ========================================================= -->
    <!-- CONTENEDOR PRINCIPAL -->
    <!-- ========================================================= -->

    <div class="pt-0 pb-6 px-3 sm:px-4 lg:px-6
               w-full max-w-full mx-auto
               flex flex-col
               h-[calc(100vh-140px)]
               overflow-x-hidden
               reporte-cuotas-responsive">


        <!-- ===================================================== -->
        <!-- TARJETA TOTAL -->
        <!-- ===================================================== -->

       <div class="w-72 mb-3 shrink-0">

    <div class="bg-emerald-50/60
               dark:bg-emerald-950/30
               py-2.5 px-4
               rounded-2xl
               shadow-xs
               border
               border-emerald-200/80
               dark:border-emerald-800/60">

        <span class="text-[11px]
                   font-black
                   uppercase
                   tracking-wider
                   text-emerald-800
                   dark:text-emerald-300
                   block
                   leading-tight">
            TOTAL COBRADO
        </span>

        <div class="text-2xl
                   font-black
                   text-emerald-950
                   dark:text-emerald-50
                   leading-none
                   my-1">
            ${{ number_format($totalCobrado, 2) }}
        </div>

        <span class="total-cobrado-descripcion
                   text-[11px]
                   font-semibold
                   text-emerald-700
                   dark:text-emerald-400
                   leading-none">
            Ingresos reales en caja
        </span>

    </div>

</div>


        <!-- ===================================================== -->
        <!-- CONTENEDOR TABLA -->
        <!-- ===================================================== -->

        <div class="bg-white
                   dark:bg-gray-800
                   rounded-2xl
                   shadow-xs
                   border
                   border-gray-200
                   dark:border-gray-700
                   overflow-hidden
                   flex
                   flex-col
                   flex-1
                   min-h-0
                   w-full">


            <!-- ================================================= -->
            <!-- BARRA DE FILTROS -->
            <!-- ================================================= -->

            <div class="p-3
                       border-b
                       border-gray-100
                       dark:border-gray-700/80
                       shrink-0">

                <form method="GET" action="{{ route('tandas.tandas.abonos') }}" class="barra-filtros-cuotas">

                    <div class="filtros-cuotas">

                        <!-- CLIENTE -->

                        <div class="campo-cliente">

                            <select name="client_id" class="bg-gray-50
                                       dark:bg-gray-700/50
                                       text-xs
                                       text-gray-800
                                       dark:text-gray-100
                                       border
                                       border-gray-200
                                       dark:border-gray-600
                                       rounded-xl
                                       px-3
                                       py-2
                                       focus:ring-2
                                       focus:ring-indigo-500
                                       focus:outline-none
                                       cursor-pointer
                                       w-full">

                                <option value="">
                                    Todos los clientes
                                </option>

                                <?php foreach ($clientes as $client): ?>

                                <option value="<?php    echo $client->id; ?>" <?php    echo (
        isset($clientId)
        &&
        $clientId == $client->id
    )
        ? 'selected'
        : ''; ?>>
                                    <?php    echo htmlspecialchars($client->name); ?>
                                </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- FECHAS -->

                        <div class="campo-fechas">

                            <input type="date" name="fecha_inicio"
                                value="<?php echo htmlspecialchars($fechaInicio ?? ''); ?>" class="bg-gray-50
                                       dark:bg-gray-700/50
                                       text-xs
                                       text-gray-800
                                       dark:text-gray-100
                                       border
                                       border-gray-200
                                       dark:border-gray-600
                                       rounded-xl
                                       px-3
                                       py-2
                                       focus:ring-2
                                       focus:ring-indigo-500
                                       focus:outline-none">

                            <span class="separador-fechas
                                       text-gray-400
                                       text-xs">
                                -
                            </span>

                            <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fechaFin ?? ''); ?>"
                                class="bg-gray-50
                                       dark:bg-gray-700/50
                                       text-xs
                                       text-gray-800
                                       dark:text-gray-100
                                       border
                                       border-gray-200
                                       dark:border-gray-600
                                       rounded-xl
                                       px-3
                                       py-2
                                       focus:ring-2
                                       focus:ring-indigo-500
                                       focus:outline-none">

                        </div>


                        <!-- BUSCAR -->

                        <button type="submit" class="btn-buscar
                                   inline-flex
                                   items-center
                                   justify-center
                                   gap-1.5
                                   bg-emerald-50
                                   hover:bg-emerald-100
                                   text-emerald-700
                                   dark:bg-emerald-950/40
                                   dark:hover:bg-emerald-900/50
                                   dark:text-emerald-300
                                   border
                                   border-emerald-300
                                   dark:border-emerald-700/80
                                   px-3.5
                                   py-2
                                   text-xs
                                   font-bold
                                   rounded-full
                                   shadow-xs
                                   transition
                                   cursor-pointer
                                   shrink-0">

                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                            </svg>

                            <span>
                                Buscar
                            </span>

                        </button>

                    </div>


                    <!-- EXCEL -->

                    <div class="botones-exportacion">

                        <button type="button" onclick="exportarExcel()" class="inline-flex
                                   items-center
                                   justify-center
                                   gap-1.5
                                   bg-emerald-50
                                   hover:bg-emerald-100
                                   text-emerald-700
                                   dark:bg-emerald-950/40
                                   dark:hover:bg-emerald-900/50
                                   dark:text-emerald-300
                                   border
                                   border-emerald-300
                                   dark:border-emerald-700/80
                                   px-4
                                   py-2
                                   text-xs
                                   font-bold
                                   rounded-full
                                   shadow-xs
                                   transition
                                   cursor-pointer">

                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />

                            </svg>

                            <span>
                                Excel
                            </span>

                        </button>

                    </div>

                </form>

            </div>


            <!-- ===================================================== -->
            <!-- TABLA -->
            <!-- ===================================================== -->

            <div class="overflow-y-auto
                       overflow-x-hidden
                       relative
                       w-full
                       flex-1">

                <table class="tabla-cuotas
                           w-full
                           text-left
                           border-collapse">

                    <thead class="sticky
                               top-0
                               z-10">

                        <tr class="bg-gray-50
                                   dark:bg-gray-700
                                   text-[11px]
                                   font-bold
                                   text-gray-500
                                   dark:text-gray-300
                                   border-b
                                   border-gray-200
                                   dark:border-gray-700
                                   shadow-xs">

                            <th class="col-fecha-pago py-3 px-4 whitespace-nowrap">
                                Fecha de Pago
                            </th>

                            <th class="col-fecha-limite py-3 px-4 whitespace-nowrap">
                                Fecha Límite
                            </th>

                            <th class="col-tanda py-3 px-4">
                                Tanda
                            </th>

                            <th class="col-cliente py-3 px-4">
                                Cliente
                            </th>

                            <th class="col-ciclo py-3 px-4 whitespace-nowrap">
                                Ciclo
                            </th>

                            <th class="col-estatus py-3 px-4 text-center">
                                Estatus
                            </th>

                            <th class="col-cobrado py-3 px-4 whitespace-nowrap">
                                Cobrado por
                            </th>

                            <th class="col-monto py-3 px-4 text-right whitespace-nowrap">
                                Monto Pagado
                            </th>

                        </tr>

                    </thead>


                    <tbody class="text-xs
                               divide-y
                               divide-gray-100
                               dark:divide-gray-700/50">

                        <?php if (!empty($abonos) && count($abonos) > 0): ?>

                        <?php    foreach ($abonos as $cuota): ?>

                        <?php

        $valMonto =
            $cuota->monto_pagado ?? 0;

        $userName =
            optional($cuota->usuario)->name
            ?? 'N/A';

        $clientName =
            optional(
                optional(
                    $cuota->participante
                )->client
            )->name
            ?? 'General';

        $tandaName =
            optional(
                $cuota->tanda
            )->name
            ?? (
                'Tanda #' .
                $cuota->tanda_id
            );

        $ciclo =
            $cuota->ciclo ?? 'N/A';

        $fechaFormateada =
            $cuota->fecha_pago
            ? \Carbon\Carbon::parse(
                $cuota->fecha_pago
            )->format('d/m/Y')
            : 'N/A';

        $fechaLimite =
            !empty($cuota->fecha_limite)
            ? \Carbon\Carbon::parse(
                $cuota->fecha_limite
            )->format('d/m/Y')
            : (
                !empty($cuota->fecha_programada)
                ? \Carbon\Carbon::parse(
                    $cuota->fecha_programada
                )->format('d/m/Y')
                : 'N/A'
            );

                                ?>

                        <tr class="fila-cuenta
                                           hover:bg-gray-50/50
                                           dark:hover:bg-gray-700/20
                                           transition">

                            <td class="col-fecha-pago py-3.5 px-4
                                               text-gray-600 dark:text-gray-300
                                               whitespace-nowrap">
                                <?php        echo $fechaFormateada; ?>
                            </td>

                            <td class="col-fecha-limite py-3.5 px-4
                                               text-gray-600 dark:text-gray-300
                                               whitespace-nowrap">
                                <?php        echo $fechaLimite; ?>
                            </td>

                            <td class="col-tanda py-3.5 px-4
                                               font-bold text-gray-900 dark:text-white">
                                <?php        echo htmlspecialchars($tandaName); ?>
                            </td>

                            <td class="col-cliente py-3.5 px-4
                                               text-gray-700 dark:text-gray-300">
                                <?php        echo htmlspecialchars($clientName); ?>
                            </td>

                            <td class="col-ciclo py-3.5 px-4 whitespace-nowrap">

                                <span class="px-2 py-0.5 text-[10px]
                                                   font-bold
                                                   bg-blue-100 text-blue-700
                                                   dark:bg-blue-950/60
                                                   dark:text-blue-300
                                                   rounded-md">
                                    Ciclo
                                    <?php        echo htmlspecialchars($ciclo); ?>
                                </span>

                            </td>

                            <td class="col-estatus py-3.5 px-4
                                               text-center whitespace-nowrap">

                                <span class="px-2 py-0.5 text-[10px]
                                                   font-bold
                                                   bg-green-100 text-green-700
                                                   dark:bg-green-950/60
                                                   dark:text-green-300
                                                   rounded-md">
                                    <?php
        echo htmlspecialchars(
            ucfirst(
                $cuota->estado ?? ''
            )
        );
                                            ?>
                                </span>

                            </td>

                            <td class="col-cobrado py-3.5 px-4
                                               text-gray-600 dark:text-gray-300
                                               whitespace-nowrap">
                                <?php        echo htmlspecialchars($userName); ?>
                            </td>

                            <td class="col-monto py-3.5 px-4
                                               text-right font-black
                                               text-gray-900 dark:text-white
                                               monto-total whitespace-nowrap" data-valor="<?php        echo $valMonto; ?>">
                                $<?php        echo number_format($valMonto, 2); ?>
                            </td>

                        </tr>

                        <?php    endforeach; ?>

                        <?php else: ?>

                        <tr>

                            <td colspan="8" class="text-center py-12 text-gray-400">

                                <div class="text-3xl mb-2">
                                    🏷️
                                </div>

                                <p class="font-medium">
                                    No se encontraron cuotas pagadas
                                    en este rango de fechas.
                                </p>

                            </td>

                        </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- EXPORTACIÓN A EXCEL -->
    <!-- ========================================================= -->

    <script>

        function exportarExcel() {

            let filas =
                document.querySelectorAll('.fila-cuenta');

            let tablaHtml = `
                <html
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:x="urn:schemas-microsoft-com:office:excel"
                    xmlns="http://www.w3.org/TR/REC-html40"
                >

                <head>

                    <meta charset="UTF-8">

                    <style>

                        .titulo {
                            font-weight: bold;
                            background-color: #059669;
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
                                colspan="8"
                                class="titulo"
                                style="
                                    font-size: 14pt;
                                    height: 35px;
                                "
                            >
                                REPORTE DE CUOTAS PAGADAS DE TANDAS
                            </td>

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

                        </tr>
            `;

            let sumaMonto = 0;

            filas.forEach(fila => {

                if (fila.style.display !== 'none') {

                    let celdas =
                        fila.querySelectorAll('td');

                    let valMonto =
                        parseFloat(
                            fila
                                .querySelector('.monto-total')
                                .getAttribute('data-valor')
                        ) || 0;

                    sumaMonto += valMonto;

                    tablaHtml += "<tr>";

                    tablaHtml += `
                        <td class="celda centro">
                            ${celdas[0].innerText.trim()}
                        </td>
                    `;

                    tablaHtml += `
                        <td class="celda centro">
                            ${celdas[1].innerText.trim()}
                        </td>
                    `;

                    tablaHtml += `
                        <td class="celda texto">
                            ${celdas[2].innerText.trim()}
                        </td>
                    `;

                    tablaHtml += `
                        <td class="celda texto">
                            ${celdas[3].innerText.trim()}
                        </td>
                    `;

                    tablaHtml += `
                        <td class="celda centro">
                            ${celdas[4].innerText.trim()}
                        </td>
                    `;

                    tablaHtml += `
                        <td class="celda centro">
                            ${celdas[5].innerText.trim()}
                        </td>
                    `;

                    tablaHtml += `
                        <td class="celda texto">
                            ${celdas[6].innerText.trim()}
                        </td>
                    `;

                    tablaHtml += `
                        <td
                            class="celda numero"
                            x:num="${valMonto}"
                            style="
                                font-weight: bold;
                                mso-number-format:'\\$#,##0.00';
                            "
                        >
                            ${valMonto}
                        </td>
                    `;

                    tablaHtml += "</tr>";
                }

            });

            tablaHtml += `

                <tr
                    class="totales"
                    style="height: 28px;"
                >

                    <td
                        colspan="7"
                        class="celda-vacia"
                        style="
                            text-align: right;
                            font-weight: bold;
                        "
                    >
                        TOTAL COBRADO:
                    </td>

                    <td
                        class="celda-vacia numero"
                        x:num="${sumaMonto}"
                        style="
                            font-weight: bold;
                            border-top: 1px solid #374151;
                            border-bottom: 1px solid #374151;
                            mso-number-format:'\\$#,##0.00';
                        "
                    >
                        ${sumaMonto}
                    </td>

                </tr>

                </table>

                </body>

                </html>
            `;

            let blob =
                new Blob(
                    [tablaHtml],
                    {
                        type:
                            'application/vnd.ms-excel;charset=utf-8;'
                    }
                );

            let downloadLink =
                document.createElement("a");

            downloadLink.href =
                window.URL.createObjectURL(blob);

            downloadLink.download =
                "reporte_cuotas_tandas_" +
                new Date()
                    .toISOString()
                    .slice(0, 10) +
                ".xls";

            document.body.appendChild(downloadLink);

            downloadLink.click();

            document.body.removeChild(downloadLink);
        }

    </script>

</x-app-layout>