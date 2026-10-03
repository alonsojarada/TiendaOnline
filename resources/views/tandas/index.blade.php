<x-app-layout>

    <!-- ========================================================= -->
    <!-- HEADER -->
    <!-- ========================================================= -->

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Tandas') }}
        </h2>

    </x-slot>


    <!-- ========================================================= -->
    <!-- CONTENEDOR PRINCIPAL -->
    <!-- ========================================================= -->

    <div class="py-2 sm:py-4">

        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">


            @php

                // =====================================================
                // INICIALIZACIÓN DE ACUMULADORES GLOBALES
                // =====================================================

                $fondoGlobalTotal = 0;
                $totalCobradoGlobal = 0;
                $totalEntregadoGlobal = 0;
                $vencidoGlobalTotal = 0;


                foreach ($tandas as $tanda) {

                    $integrantesSinCero =
                        $tanda->participantes
                            ->where('turno', '!=', 0)
                            ->count();


                    if ($integrantesSinCero === 0) {

                        $integrantesSinCero =
                            $tanda->participantes->count();

                    }


                    $cuotasPorCiclo =
                        $tanda->cuotas_por_entrega ?? 4;


                    $montoPozoCiclo =
                        $cuotasPorCiclo *
                        $tanda->monto_cuota *
                        $integrantesSinCero;


                    foreach ($tanda->participantes as $p) {

                        $fondoGlobalTotal +=
                            $p->cuotas->sum('monto_esperado');


                        $totalCobradoGlobal +=
                            $p->cuotas
                                ->where('estado', 'pagado')
                                ->sum('monto_pagado');


                        foreach ($p->cuotas as $c) {

                            if (
                                $c->estado !== 'pagado' &&
                                \Carbon\Carbon::parse($c->fecha_limite)->isPast()
                            ) {

                                $vencidoGlobalTotal +=
                                    ($c->monto_esperado - $c->monto_pagado);

                            }

                        }


                        if ($p->entregado) {

                            if ($p->turno === 0) {

                                $totalEntregadoGlobal +=
                                    $p->cuotas->sum('monto_esperado');

                            } else {

                                $totalEntregadoGlobal +=
                                    $montoPozoCiclo;

                            }

                        }

                    }

                }


                $balanceGlobal =
                    $totalCobradoGlobal -
                    $totalEntregadoGlobal;

            @endphp



            <!-- ===================================================== -->
            <!-- CONTENEDOR GENERAL -->
            <!-- ===================================================== -->

            <div class="bg-white
                        overflow-hidden
                        shadow-sm
                        sm:rounded-lg
                        p-2 sm:p-4
                        text-gray-900
                        contenedor-tandas">


                <!-- ================================================= -->
                <!-- TARJETAS GLOBALES -->
                <!-- ================================================= -->

                <div class="tarjetas-globales
                            grid
                            grid-cols-5
                            gap-1
                            sm:gap-2
                            mb-3">


                    <!-- ================================================= -->
                    <!-- FONDO GLOBAL -->
                    <!-- ================================================= -->

                    <div class="tarjeta-total
                                tarjeta-fondo
                                bg-white
                                rounded-xl
                                border border-gray-200
                                shadow-sm
                                flex flex-col
                                justify-between
                                min-w-0">

                        <div class="tarjeta-titulo
                                    font-bold
                                    text-gray-500
                                    uppercase
                                    tracking-wider
                                    truncate">

                            Fondo Global

                        </div>


                        <div class="tarjeta-monto
                                    font-bold
                                    text-gray-900
                                    truncate">

                            ${{ number_format($fondoGlobalTotal, 2) }}

                        </div>


                        <div class="tarjeta-descripcion
                                    text-gray-400
                                    truncate">

                            Suma total de tandas

                        </div>

                    </div>



                    <!-- ================================================= -->
                    <!-- TOTAL COBRADO -->
                    <!-- ================================================= -->

                    <div class="tarjeta-total
                                tarjeta-cobrado
                                bg-emerald-50/40
                                rounded-xl
                                border border-emerald-100
                                shadow-sm
                                flex flex-col
                                justify-between
                                min-w-0">

                        <div class="tarjeta-titulo
                                    font-bold
                                    text-emerald-700
                                    uppercase
                                    tracking-wider
                                    truncate">

                            Cobrado

                        </div>


                        <div class="tarjeta-monto
                                    font-bold
                                    text-emerald-600
                                    truncate">

                            ${{ number_format($totalCobradoGlobal, 2) }}

                        </div>


                        <div class="tarjeta-descripcion
                                    text-emerald-600/80
                                    truncate">

                            Ingresos reales en caja

                        </div>

                    </div>



                    <!-- ================================================= -->
                    <!-- TOTAL ENTREGADO -->
                    <!-- ================================================= -->

                    <div class="tarjeta-total
                                tarjeta-entregado
                                bg-blue-50/40
                                rounded-xl
                                border border-blue-100
                                shadow-sm
                                flex flex-col
                                justify-between
                                min-w-0">

                        <div class="tarjeta-titulo
                                    font-bold
                                    text-blue-700
                                    uppercase
                                    tracking-wider
                                    truncate">

                            Entregado

                        </div>


                        <div class="tarjeta-monto
                                    font-bold
                                    text-blue-600
                                    truncate">

                            ${{ number_format($totalEntregadoGlobal, 2) }}

                        </div>


                        <div class="tarjeta-descripcion
                                    text-blue-600/80
                                    truncate">

                            Pozos entregados

                        </div>

                    </div>



                    <!-- ================================================= -->
                    <!-- BALANCE -->
                    <!-- ================================================= -->

                    <div class="tarjeta-total
                                tarjeta-balance
                                bg-indigo-50/40
                                rounded-xl
                                border border-indigo-100
                                shadow-sm
                                flex flex-col
                                justify-between
                                min-w-0">

                        <div class="tarjeta-titulo
                                    font-bold
                                    text-indigo-700
                                    uppercase
                                    tracking-wider
                                    truncate">

                            Balance

                        </div>


                        <div class="tarjeta-monto
                                    font-bold
                                    {{ $balanceGlobal >= 0
                                        ? 'text-indigo-600'
                                        : 'text-rose-600' }}
                                    truncate">

                            ${{ number_format($balanceGlobal, 2) }}

                        </div>


                        <div class="tarjeta-descripcion
                                    text-indigo-600/80
                                    truncate">

                            Flujo neto acumulado

                        </div>

                    </div>



                    <!-- ================================================= -->
                    <!-- VENCIDO GLOBAL -->
                    <!-- ================================================= -->

                    <div class="tarjeta-total
                                tarjeta-vencido
                                bg-rose-50/40
                                rounded-xl
                                border border-rose-100
                                shadow-sm
                                flex flex-col
                                justify-between
                                min-w-0">

                        <div class="tarjeta-titulo
                                    font-bold
                                    text-rose-700
                                    uppercase
                                    tracking-wider
                                    truncate">

                            Vencido Global

                        </div>


                        <div class="tarjeta-monto
                                    font-bold
                                    text-rose-600
                                    truncate">

                            ${{ number_format($vencidoGlobalTotal, 2) }}

                        </div>


                        <div class="tarjeta-descripcion
                                    text-rose-600/80
                                    truncate">

                            Deuda retrasada total

                        </div>

                    </div>

                </div>



                <!-- ================================================= -->
                <!-- ENCABEZADO TABLA -->
                <!-- SIEMPRE UNA SOLA FILA -->
                <!-- ================================================= -->

                <div class="encabezado-tandas
                            flex
                            flex-row
                            justify-between
                            items-center
                            gap-2
                            mb-3
                            bg-gray-50/50
                            p-2 sm:p-0
                            rounded-lg
                            min-w-0">


                    <!-- ================================================= -->
                    <!-- TÍTULO -->
                    <!-- ================================================= -->

                    <div class="titulo-tandas
                                min-w-0
                                flex-1
                                overflow-hidden">

                        <h3 class="text-xs sm:text-sm
                                   font-bold
                                   text-gray-700
                                   uppercase
                                   tracking-wider
                                   truncate">

                            Tandas Registradas

                        </h3>

                    </div>



                    <!-- ================================================= -->
                    <!-- BOTÓN NUEVA TANDA -->
                    <!-- ================================================= -->

                    <div class="boton-nueva-tanda shrink-0">

                        <a href="{{ route('tandas.create') }}"
                           class="inline-flex
                                  justify-center
                                  items-center
                                  px-3
                                  py-1.5
                                  sm:px-3.5
                                  sm:py-1.5
                                  bg-indigo-600
                                  border border-transparent
                                  rounded-xl
                                  font-semibold
                                  text-[11px]
                                  sm:text-xs
                                  text-white
                                  uppercase
                                  tracking-widest
                                  hover:bg-indigo-700
                                  transition
                                  shadow-sm
                                  whitespace-nowrap">

                            <span class="texto-nueva-tanda">
                                Crear Nueva Tanda
                            </span>

                        </a>

                    </div>

                </div>



                <!-- ================================================= -->
                <!-- CONTENEDOR DE TABLA -->
                <!-- ================================================= -->

                <div class="contenedor-tabla-tandas
                            overflow-x-auto
                            overflow-y-auto
                            max-h-[calc(100vh-260px)]
                            rounded-lg
                            border border-gray-100">


                    <table class="tabla-tandas
                                  min-w-full
                                  divide-y
                                  divide-gray-200
                                  relative">


                        <!-- ================================================= -->
                        <!-- CABECERA -->
                        <!-- ================================================= -->

                        <thead class="bg-gray-50
                                      sticky
                                      top-0
                                      z-10">

                            <tr class="text-left
                                       font-semibold
                                       text-gray-500
                                       uppercase
                                       tracking-wider">


                                <th class="columna-nombre
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50">

                                    Nombre

                                </th>


                                <th class="columna-entrega
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50
                                           text-right">

                                    Monto Entrega

                                </th>


                                <th class="columna-cuota
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50">

                                    Cuota

                                </th>


                                <th class="columna-frecuencia
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50">

                                    Frecuencia

                                </th>


                                <th class="columna-atrasadas
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50
                                           text-center">

                                    Cuotas atrasadas

                                </th>


                                <th class="columna-clientes
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50
                                           text-center">

                                    #Ctes.

                                </th>


                                <th class="columna-duracion
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50
                                           text-center">

                                    Duración

                                </th>


                                <th class="columna-cobranza
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50
                                           text-center">

                                    Cobranza

                                </th>


                                <th class="columna-estado
                                           px-3 sm:px-4
                                           py-2.5
                                           bg-gray-50
                                           text-center">

                                    Estado

                                </th>

                            </tr>

                        </thead>



                        <!-- ================================================= -->
                        <!-- CUERPO -->
                        <!-- ================================================= -->

                        <tbody class="bg-white divide-y divide-gray-100">


                            @forelse($tandas as $tanda)


                                @php

                                    $integrantesSinCero =
                                        $tanda->participantes
                                            ->where('turno', '!=', 0)
                                            ->count();


                                    if ($integrantesSinCero === 0) {

                                        $integrantesSinCero =
                                            $tanda->participantes->count();

                                    }


                                    $cuotasPorEntrega =
                                        $tanda->cuotas_por_entrega ?? 1;


                                    $totalRecibir =
                                        $tanda->monto_cuota *
                                        $cuotasPorEntrega *
                                        $integrantesSinCero;


                                    $primeraCuotaFecha =
                                        $tanda->cuotas->min('fecha_limite');


                                    $ultimaCuotaFecha =
                                        $tanda->cuotas->max('fecha_limite');


                                    $cuotasAtrasadasTotal = 0;

                                    $montoRetrasadoTotal = 0;


                                    $todasCuotas =
                                        $tanda->participantes
                                            ->flatMap->cuotas;


                                    $montoEsperadoTotal =
                                        $todasCuotas->sum('monto_esperado');


                                    $montoPagadoTotal =
                                        $todasCuotas->sum('monto_pagado');


                                    $progresoCobranza =
                                        $montoEsperadoTotal > 0
                                            ? min(
                                                100,
                                                round(
                                                    ($montoPagadoTotal /
                                                    $montoEsperadoTotal) * 100,
                                                    1
                                                )
                                            )
                                            : 0;


                                    foreach ($tanda->participantes as $p) {

                                        foreach ($p->cuotas as $c) {

                                            if (
                                                $c->estado !== 'pagado' &&
                                                \Carbon\Carbon::parse(
                                                    $c->fecha_limite
                                                )->isPast()
                                            ) {

                                                $cuotasAtrasadasTotal++;

                                                $montoRetrasadoTotal +=
                                                    ($c->monto_esperado -
                                                     $c->monto_pagado);

                                            }

                                        }

                                    }

                                @endphp



                                <!-- ================================================= -->
                                <!-- FILA -->
                                <!-- ================================================= -->

                                <tr onclick="window.location.href='{{ route('tandas.show', $tanda->id) }}?origen=index'"
                                    class="fila-tanda
                                           hover:bg-gray-50/75
                                           transition-colors
                                           cursor-pointer">


                                    <!-- NOMBRE -->

                                    <td class="columna-nombre
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap">

                                        <span class="text-indigo-600
                                                     hover:text-indigo-900
                                                     font-semibold">

                                            {{ $tanda->nombre }}

                                        </span>

                                    </td>



                                    <!-- MONTO ENTREGA -->

                                    <td class="columna-entrega
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               text-right
                                               font-bold
                                               text-emerald-600">

                                        ${{ number_format($totalRecibir, 2) }}

                                    </td>



                                    <!-- CUOTA -->

                                    <td class="columna-cuota
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               font-medium
                                               text-gray-700">

                                        ${{ number_format($tanda->monto_cuota, 2) }}

                                    </td>



                                    <!-- FRECUENCIA -->

                                    <td class="columna-frecuencia
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               capitalize
                                               text-gray-600">

                                        {{ $tanda->frecuencia }}

                                    </td>



                                    <!-- CUOTAS ATRASADAS -->

                                    <td class="columna-atrasadas
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               text-center">

                                        @if($cuotasAtrasadasTotal > 0)

                                            <span class="px-2
                                                         py-0.5
                                                         sm:px-2.5
                                                         sm:py-1
                                                         text-[11px]
                                                         sm:text-xs
                                                         font-bold
                                                         bg-red-100
                                                         text-red-700
                                                         rounded-full
                                                         inline-flex
                                                         items-center
                                                         gap-1">

                                                <span>
                                                    {{ $cuotasAtrasadasTotal }}
                                                </span>

                                                <span class="text-red-400 font-normal">
                                                    /
                                                </span>

                                                <span>
                                                    ${{ number_format($montoRetrasadoTotal, 2) }}
                                                </span>

                                            </span>

                                        @else

                                            <span class="px-2
                                                         py-0.5
                                                         sm:px-2.5
                                                         sm:py-1
                                                         text-[11px]
                                                         sm:text-xs
                                                         font-bold
                                                         bg-green-50
                                                         text-green-700
                                                         rounded-full">

                                                0 / $0.00

                                            </span>

                                        @endif

                                    </td>



                                    <!-- CLIENTES -->

                                    <td class="columna-clientes
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               text-center">

                                        <span class="px-2
                                                     py-0.5
                                                     sm:px-2.5
                                                     sm:py-1
                                                     text-[11px]
                                                     sm:text-xs
                                                     font-bold
                                                     bg-indigo-50
                                                     text-indigo-700
                                                     rounded-full">

                                            {{ $integrantesSinCero }}

                                        </span>

                                    </td>



                                    <!-- DURACIÓN -->

                                    <td class="columna-duracion
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               text-center
                                               text-[11px]
                                               sm:text-xs
                                               text-gray-600">

                                        {{ $primeraCuotaFecha
                                            ? \Carbon\Carbon::parse($primeraCuotaFecha)->format('d/m/Y')
                                            : 'N/A' }}

                                        <span class="text-gray-400 mx-0.5">
                                            -
                                        </span>

                                        {{ $ultimaCuotaFecha
                                            ? \Carbon\Carbon::parse($ultimaCuotaFecha)->format('d/m/Y')
                                            : 'N/A' }}

                                    </td>



                                    <!-- COBRANZA -->

                                    <td class="columna-cobranza
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               text-center">

                                        <div class="flex
                                                    items-center
                                                    justify-center
                                                    space-x-1.5
                                                    sm:space-x-2">

                                            <div class="barra-cobranza
                                                        w-16
                                                        sm:w-24
                                                        bg-gray-200
                                                        rounded-full
                                                        h-1.5
                                                        sm:h-2
                                                        overflow-hidden">

                                                <div class="bg-indigo-600
                                                            h-1.5
                                                            sm:h-2
                                                            rounded-full"
                                                     style="width: {{ $progresoCobranza }}%">
                                                </div>

                                            </div>


                                            <span class="porcentaje-cobranza
                                                         text-[11px]
                                                         sm:text-xs
                                                         font-semibold
                                                         text-gray-700">

                                                {{ $progresoCobranza }}%

                                            </span>

                                        </div>

                                    </td>



                                    <!-- ESTADO -->

                                    <td class="columna-estado
                                               px-3 sm:px-4
                                               py-2.5
                                               whitespace-nowrap
                                               text-center">

                                        <span class="px-2.5
                                                     py-0.5
                                                     sm:py-1
                                                     inline-flex
                                                     text-[11px]
                                                     sm:text-xs
                                                     leading-5
                                                     font-semibold
                                                     rounded-full
                                                     bg-emerald-100
                                                     text-emerald-800
                                                     capitalize">

                                            {{ $tanda->estado }}

                                        </span>

                                    </td>

                                </tr>


                            @empty


                                <tr>

                                    <td colspan="9"
                                        class="px-6
                                               py-4
                                               text-center
                                               text-gray-500">

                                        No hay tandas activas registradas todavía.

                                    </td>

                                </tr>


                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- CSS RESPONSIVE -->
    <!-- ========================================================= -->

    <style>


        /* ========================================================= */
        /* CONTENEDOR GENERAL */
        /* ========================================================= */

        .contenedor-tandas {

            container-type: inline-size;

        }



        /* ========================================================= */
        /* TARJETAS GLOBALES */
        /* ========================================================= */

        .tarjetas-globales {

            width: 100%;

        }


        .tarjeta-total {

            min-width: 0;

            overflow: hidden;

            padding: 10px;

        }


        .tarjeta-titulo {

            font-size: 10px;

            line-height: 1.2;

        }


        .tarjeta-monto {

            font-size: 16px;

            line-height: 1.25;

            margin-top: 3px;

            margin-bottom: 3px;

        }


        .tarjeta-descripcion {

            font-size: 11px;

            line-height: 1.2;

        }



        /* ========================================================= */
        /* 1101 PX EN ADELANTE */
        /* ========================================================= */

        @container (min-width: 1101px) {

            .tarjetas-globales {

                gap: 8px;

            }


            .tarjeta-total {

                padding: 12px;

            }


            .tarjeta-titulo {

                font-size: 10px;

            }


            .tarjeta-monto {

                font-size: 16px;

            }


            .tarjeta-descripcion {

                font-size: 11px;

            }

        }



        /* ========================================================= */
        /* 901 - 1100 PX */
        /* ========================================================= */

        @container (min-width: 901px) and (max-width: 1100px) {

            .tarjetas-globales {

                gap: 6px;

            }


            .tarjeta-total {

                padding: 9px;

                border-radius: 10px;

            }


            .tarjeta-titulo {

                font-size: 9px;

            }


            .tarjeta-monto {

                font-size: 14px;

            }


            .tarjeta-descripcion {

                font-size: 10px;

            }

        }



        /* ========================================================= */
        /* 701 - 900 PX */
        /* ========================================================= */

        @container (min-width: 701px) and (max-width: 900px) {

            .tarjetas-globales {

                gap: 5px;

            }


            .tarjeta-total {

                padding: 7px;

                border-radius: 9px;

            }


            .tarjeta-titulo {

                font-size: 8px;

                letter-spacing: 0.04em;

            }


            .tarjeta-monto {

                font-size: 12px;

            }


            .tarjeta-descripcion {

                font-size: 9px;

            }

        }



        /* ========================================================= */
        /* 501 - 700 PX */
        /* ========================================================= */

        @container (min-width: 501px) and (max-width: 700px) {

            .tarjetas-globales {

                gap: 4px;

            }


            .tarjeta-total {

                padding: 6px;

                border-radius: 8px;

            }


            .tarjeta-titulo {

                font-size: 7px;

                letter-spacing: 0.03em;

            }


            .tarjeta-monto {

                font-size: 11px;

            }


            .tarjeta-descripcion {

                font-size: 8px;

            }

        }



        /* ========================================================= */
        /* 351 - 500 PX */
        /* ========================================================= */

        @container (min-width: 351px) and (max-width: 500px) {

            .tarjetas-globales {

                gap: 3px;

            }


            .tarjeta-total {

                padding: 5px;

                border-radius: 7px;

            }


            .tarjeta-titulo {

                font-size: 6.5px;

                letter-spacing: 0.02em;

            }


            .tarjeta-monto {

                font-size: 9px;

            }


            .tarjeta-descripcion {

                display: none;

            }

        }



        /* ========================================================= */
        /* HASTA 350 PX */
        /* ========================================================= */

        @container (max-width: 350px) {

            .tarjetas-globales {

                gap: 2px;

            }


            .tarjeta-total {

                padding: 4px;

                border-radius: 6px;

            }


            .tarjeta-titulo {

                font-size: 6px;

                letter-spacing: 0;

            }


            .tarjeta-monto {

                font-size: 8px;

                margin-top: 2px;

                margin-bottom: 0;

            }


            .tarjeta-descripcion {

                display: none;

            }

        }



        /* ========================================================= */
        /* ENCABEZADO DE TABLA */
        /* SIEMPRE UNA SOLA FILA */
        /* ========================================================= */

        .encabezado-tandas {

            container-type: inline-size;

        }


        .titulo-tandas {

            min-width: 0;

            flex: 1 1 auto;

            overflow: hidden;

        }


        .titulo-tandas h3 {

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

        }


        .boton-nueva-tanda {

            flex-shrink: 0;

        }



        /* ========================================================= */
        /* ENCABEZADO MEDIANO */
        /* ========================================================= */

        @container (max-width: 700px) {

            .encabezado-tandas {

                gap: 6px;

            }


            .titulo-tandas h3 {

                font-size: 10px !important;

            }


            .boton-nueva-tanda a {

                font-size: 9px !important;

                padding: 6px 9px !important;

            }

        }



        /* ========================================================= */
        /* ENCABEZADO PEQUEÑO */
        /* ========================================================= */

        @container (max-width: 450px) {

            .encabezado-tandas {

                gap: 5px;

            }


            .titulo-tandas h3 {

                font-size: 9px !important;

            }


            .boton-nueva-tanda a {

                font-size: 8px !important;

                padding: 5px 8px !important;

            }

        }



        /* ========================================================= */
        /* MUY PEQUEÑO */
        /* ========================================================= */

        @container (max-width: 350px) {

            .titulo-tandas h3 {

                font-size: 8px !important;

            }


            .boton-nueva-tanda a {

                font-size: 8px !important;

                padding: 5px 7px !important;

            }

        }



        /* ========================================================= */
        /* TABLA */
        /* ========================================================= */

        .contenedor-tabla-tandas {

            container-type: inline-size;

        }



        /* ========================================================= */
        /* TÍTULOS DE COLUMNAS */
        /* RESPONSIVE SEGÚN EL TAMAÑO DE PANTALLA */
        /* ========================================================= */

        .tabla-tandas thead th {

            font-size: 9px !important;

            line-height: 1.1;

            letter-spacing: 0.01em;

            white-space: nowrap;

        }


        /* ========================================================= */
        /* TÍTULOS - 601 A 900 PX */
        /* ========================================================= */

        @media screen and (min-width: 601px) and (max-width: 900px) {

            .tabla-tandas thead th {

                font-size: 8px !important;

                line-height: 1.1;

                letter-spacing: 0.01em;

            }

        }


        /* ========================================================= */
        /* TÍTULOS - 401 A 600 PX */
        /* ========================================================= */

        @media screen and (min-width: 401px) and (max-width: 600px) {

            .tabla-tandas thead th {

                font-size: 7px !important;

                line-height: 1.1;

                letter-spacing: 0;

            }

        }


        /* ========================================================= */
        /* TÍTULOS - 321 A 400 PX */
        /* ========================================================= */

        @media screen and (min-width: 321px) and (max-width: 400px) {

            .tabla-tandas thead th {

                font-size: 6.5px !important;

                line-height: 1.1;

                letter-spacing: 0;

            }

        }


        /* ========================================================= */
        /* TÍTULOS - HASTA 320 PX */
        /* ========================================================= */

        @media screen and (max-width: 320px) {

            .tabla-tandas thead th {

                font-size: 6px !important;

                line-height: 1.1;

                letter-spacing: 0;

            }

        }



        /* ========================================================= */
        /* TABLA GRANDE */
        /* TODAS LAS COLUMNAS */
        /* ========================================================= */

        @container (min-width: 901px) {

            .columna-nombre,
            .columna-entrega,
            .columna-cuota,
            .columna-frecuencia,
            .columna-atrasadas,
            .columna-clientes,
            .columna-duracion,
            .columna-cobranza,
            .columna-estado {

                display: table-cell;

            }

        }



        /* ========================================================= */
        /* TABLA MEDIANA */
        /* 601 - 900 */
        /* ========================================================= */

        @container (min-width: 601px) and (max-width: 900px) {

            .columna-frecuencia,
            .columna-atrasadas,
            .columna-duracion {

                display: none !important;

            }


            .tabla-tandas {

                font-size: 11px;

            }


            .tabla-tandas th,
            .tabla-tandas td {

                padding-left: 7px !important;

                padding-right: 7px !important;

                padding-top: 8px !important;

                padding-bottom: 8px !important;

            }


            .barra-cobranza {

                width: 55px !important;

            }

        }



        /* ========================================================= */
        /* TABLA PEQUEÑA */
        /* HASTA 600 */
        /* ========================================================= */

        @container (max-width: 600px) {

            .columna-frecuencia,
            .columna-atrasadas,
            .columna-duracion,
            .columna-estado {

                display: none !important;

            }


            .tabla-tandas {

                font-size: 10px;

            }


            .tabla-tandas th,
            .tabla-tandas td {

                padding-left: 5px !important;

                padding-right: 5px !important;

                padding-top: 7px !important;

                padding-bottom: 7px !important;

            }


            .columna-nombre {

                max-width: 100px;

            }


            .columna-entrega {

                width: 80px;

            }


            .columna-cuota {

                width: 65px;

            }


            .columna-clientes {

                width: 55px;

            }


            .columna-cobranza {

                width: 85px;

            }


            .barra-cobranza {

                width: 45px !important;

            }


            .porcentaje-cobranza {

                font-size: 9px !important;

            }

        }



        /* ========================================================= */
        /* TABLA MUY PEQUEÑA */
        /* ========================================================= */

        @container (max-width: 400px) {

            .tabla-tandas {

                font-size: 9px;

            }


            .tabla-tandas th,
            .tabla-tandas td {

                padding-left: 4px !important;

                padding-right: 4px !important;

                padding-top: 6px !important;

                padding-bottom: 6px !important;

            }


            .columna-nombre {

                max-width: 85px;

            }


            .columna-entrega {

                width: 72px;

            }


            .columna-cuota {

                width: 58px;

            }


            .columna-clientes {

                width: 45px;

            }


            .columna-cobranza {

                width: 75px;

            }


            .barra-cobranza {

                width: 35px !important;

                height: 5px !important;

            }


            .porcentaje-cobranza {

                font-size: 8px !important;

            }

        }



        /* ========================================================= */
        /* TABLA EXTREMADAMENTE PEQUEÑA */
        /* ========================================================= */

        @container (max-width: 320px) {

            .tabla-tandas {

                font-size: 8px;

            }


            .tabla-tandas th,
            .tabla-tandas td {

                padding-left: 3px !important;

                padding-right: 3px !important;

                padding-top: 5px !important;

                padding-bottom: 5px !important;

            }


            .columna-nombre {

                max-width: 70px;

            }


            .columna-entrega {

                width: 65px;

            }


            .columna-cuota {

                width: 52px;

            }


            .columna-clientes {

                width: 40px;

            }


            .columna-cobranza {

                width: 68px;

            }


            .barra-cobranza {

                width: 30px !important;

            }

        }

    </style>

</x-app-layout>


