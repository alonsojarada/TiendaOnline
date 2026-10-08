<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                Panel General de Cobranza
            </h2>

            <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">
                Vista rápida de saldos pendientes, mercancía fiada y control de cobros.
            </p>
        </div>
    </x-slot>


    <div class="pt-2 pb-6 px-2 sm:px-6 lg:px-8 w-full mx-auto">


        <!-- ========================================================= -->
        <!-- TARJETAS SUPERIORES (KPIs)                                -->
        <!-- SIEMPRE 4 EN UNA SOLA FILA                                -->
        <!-- ========================================================= -->

        <div class="grid grid-cols-4 gap-1.5 sm:gap-2 md:gap-2.5 mb-3 w-full">


            <!-- POR COBRAR TOTAL -->
            <div class="bg-white dark:bg-gray-800
                        px-1.5 py-1.5
                        sm:px-2.5 sm:py-2
                        md:px-3 md:py-2
                        rounded-lg sm:rounded-xl
                        shadow-xs
                        border border-gray-100 dark:border-gray-700/80
                        flex flex-col justify-between
                        min-w-0 overflow-hidden">

                <span class="hidden sm:block
                             text-[10px] md:text-[11px]
                             font-bold uppercase tracking-wider
                             text-gray-400
                             leading-tight truncate">
                    Por Cobrar Total
                </span>

                <span class="block sm:hidden
                             text-[8px]
                             font-bold uppercase tracking-wide
                             text-gray-400
                             leading-tight truncate">
                    Por Cobrar
                </span>

                <span class="text-[10px] sm:text-sm md:text-base lg:text-lg
                             font-black
                             text-gray-900 dark:text-white
                             mt-0.5
                             truncate
                             leading-tight">
                    ${{ number_format($totalGlobalPendiente, 0) }}
                </span>

            </div>


            <!-- TOTAL PRÉSTAMOS -->
            <div class="bg-white dark:bg-gray-800
                        px-1.5 py-1.5
                        sm:px-2.5 sm:py-2
                        md:px-3 md:py-2
                        rounded-lg sm:rounded-xl
                        shadow-xs
                        border border-gray-100 dark:border-gray-700/80
                        flex flex-col justify-between
                        min-w-0 overflow-hidden">

                <span class="hidden sm:block
                             text-[10px] md:text-[11px]
                             font-bold uppercase tracking-wider
                             text-indigo-500
                             leading-tight truncate">
                    Total Préstamos
                </span>

                <span class="block sm:hidden
                             text-[8px]
                             font-bold uppercase tracking-wide
                             text-indigo-500
                             leading-tight truncate">
                    Préstamos
                </span>

                <span class="text-[10px] sm:text-sm md:text-base lg:text-lg
                             font-black
                             text-indigo-600 dark:text-indigo-400
                             mt-0.5
                             truncate
                             leading-tight">
                    ${{ number_format($totalPrestamos, 0) }}
                </span>

            </div>


            <!-- TOTAL MERCANCÍA -->
            <div class="bg-white dark:bg-gray-800
                        px-1.5 py-1.5
                        sm:px-2.5 sm:py-2
                        md:px-3 md:py-2
                        rounded-lg sm:rounded-xl
                        shadow-xs
                        border border-gray-100 dark:border-gray-700/80
                        flex flex-col justify-between
                        min-w-0 overflow-hidden">

                <span class="hidden sm:block
                             text-[10px] md:text-[11px]
                             font-bold uppercase tracking-wider
                             text-emerald-500
                             leading-tight truncate">
                    Total Mercancía
                </span>

                <span class="block sm:hidden
                             text-[8px]
                             font-bold uppercase tracking-wide
                             text-emerald-500
                             leading-tight truncate">
                    Mercancía
                </span>

                <span class="text-[10px] sm:text-sm md:text-base lg:text-lg
                             font-black
                             text-emerald-600 dark:text-emerald-400
                             mt-0.5
                             truncate
                             leading-tight">
                    ${{ number_format($totalMercancia, 0) }}
                </span>

            </div>


            <!-- CLIENTES CON RETRASO -->
            <div class="bg-white dark:bg-gray-800
                        px-1.5 py-1.5
                        sm:px-2.5 sm:py-2
                        md:px-3 md:py-2
                        rounded-lg sm:rounded-xl
                        shadow-xs
                        border border-gray-100 dark:border-gray-700/80
                        flex flex-col justify-between
                        min-w-0 overflow-hidden">

                <span class="hidden sm:block
                             text-[10px] md:text-[11px]
                             font-bold uppercase tracking-wider
                             text-red-500
                             leading-tight truncate">
                    Con Retraso (&gt;7d)
                </span>

                <span class="block sm:hidden
                             text-[8px]
                             font-bold uppercase tracking-wide
                             text-red-500
                             leading-tight truncate">
                    Retraso
                </span>

                <span class="text-[10px] sm:text-sm md:text-base lg:text-lg
                             font-black
                             text-red-600 dark:text-red-400
                             mt-0.5
                             truncate
                             leading-tight">

                    {{ $clientesConRetrasoCount }}

                    <span class="text-[9px] sm:text-xs md:text-sm">
                        Clientes
                    </span>

                </span>

            </div>

        </div>



        <!-- ========================================================= -->
        <!-- BARRA DE ACCIONES                                         -->
        <!-- ========================================================= -->

        <div id="barraAccionesCobranza"
            class="bg-white dark:bg-gray-800
                   p-3 sm:p-4
                   rounded-xl sm:rounded-2xl
                   shadow-xs
                   border border-gray-100 dark:border-gray-700/80
                   mb-3
                   w-full">


            <div id="contenidoAccionesCobranza"
                class="w-full">


                <!-- BUSCADOR -->
                <div id="bloqueBusquedaCobranza"
                    class="relative min-w-0">

                    <span class="absolute inset-y-0 left-0
                                 flex items-center pl-3
                                 pointer-events-none
                                 text-gray-400 text-xs">
                        🔍
                    </span>

                    <input type="text"
                        id="buscadorDashboard"
                        placeholder="Buscar cliente o alias..."
                        oninput="sincronizarBusqueda(this.value)"
                        class="w-full
                               pl-9 pr-3
                               py-1.5
                               bg-gray-50 dark:bg-gray-700/40
                               border border-gray-200 dark:border-gray-600/60
                               rounded-xl
                               text-xs
                               text-gray-800 dark:text-gray-100
                               focus:outline-none
                               focus:ring-2 focus:ring-indigo-500
                               transition">
                </div>



                <!-- FILTROS -->
                <div id="filtrosEstadoPrincipal"
                    class="flex items-center gap-1.5 min-w-0">


                    <button type="button"
                        onclick="toggleFiltro('todos')"
                        id="btn-todos-principal"
                        class="filtro-btn
                               px-2.5 py-1.5
                               bg-indigo-600 text-white
                               rounded-xl
                               text-xs font-bold
                               shadow-xs
                               transition
                               whitespace-nowrap">
                        Todos
                    </button>


                    <button type="button"
                        onclick="toggleFiltro('retraso')"
                        id="btn-retraso-principal"
                        class="filtro-btn
                               px-2.5 py-1.5
                               bg-gray-100 dark:bg-gray-700/60
                               text-gray-600 dark:text-gray-300
                               hover:bg-gray-200 dark:hover:bg-gray-600
                               rounded-xl
                               text-xs font-semibold
                               transition
                               whitespace-nowrap">
                        ⚠️ Retraso
                    </button>


                    <button type="button"
                        onclick="toggleFiltro('proximos')"
                        id="btn-proximos-principal"
                        class="filtro-btn
                               px-2.5 py-1.5
                               bg-gray-100 dark:bg-gray-700/60
                               text-gray-600 dark:text-gray-300
                               hover:bg-gray-200 dark:hover:bg-gray-600
                               rounded-xl
                               text-xs font-semibold
                               transition
                               whitespace-nowrap">
                        ⏰ Próximos
                    </button>


                    <button type="button"
                        onclick="toggleFiltro('al_dia')"
                        id="btn-al_dia-principal"
                        class="filtro-btn
                               px-2.5 py-1.5
                               bg-gray-100 dark:bg-gray-700/60
                               text-gray-600 dark:text-gray-300
                               hover:bg-gray-200 dark:hover:bg-gray-600
                               rounded-xl
                               text-xs font-semibold
                               transition
                               whitespace-nowrap">
                        ✓ Al Día
                    </button>

                </div>



                <!-- EXPORTACIÓN -->
                <div id="botonesExportarCobranza"
                    class="flex items-center gap-2 shrink-0">

                    <a href="{{ route('dashboard.export.excel') }}"
                        title="Exportar Excel"
                        class="boton-exportar inline-flex items-center justify-center
                               bg-emerald-600 hover:bg-emerald-700
                               text-white
                               px-3 py-1.5
                               text-xs font-semibold
                               rounded-xl
                               shadow-xs
                               transition
                               whitespace-nowrap">

                        <svg class="icono-exportar w-3.5 h-3.5 mr-1"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>

                        </svg>

                        <span class="texto-exportar">
                            Excel
                        </span>

                    </a>


                    <a href="{{ route('dashboard.export.pdf') }}"
                        title="Exportar PDF"
                        class="boton-exportar inline-flex items-center justify-center
                               bg-red-600 hover:bg-red-700
                               text-white
                               px-3 py-1.5
                               text-xs font-semibold
                               rounded-xl
                               shadow-xs
                               transition
                               whitespace-nowrap">

                        <svg class="icono-exportar w-3.5 h-3.5 mr-1"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>

                        </svg>

                        <span class="texto-exportar">
                            PDF
                        </span>

                    </a>

                </div>

            </div>

        </div>



        <!-- ========================================================= -->
        <!-- TABLA                                                     -->
        <!-- ========================================================= -->

        <div id="contenedorTablaCobranza"
            class="bg-white dark:bg-gray-800
                   rounded-xl sm:rounded-2xl
                   shadow-sm
                   border border-gray-200 dark:border-gray-700
                   overflow-hidden">

            <div id="scrollTablaCobranza"
                class="overflow-y-auto overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead>

                        <tr class="bg-gray-50 dark:bg-gray-900/50
                                   text-[11px] sm:text-xs
                                   font-black
                                   text-gray-500 dark:text-gray-400
                                   tracking-wider
                                   border-b border-gray-200 dark:border-gray-700">

                            <th class="py-2.5 px-2.5 sm:px-4">
                                Cliente
                            </th>

                            <th class="py-2.5 px-2.5 hidden md:table-cell">
                                Dirección
                            </th>

                            <th class="py-2.5 px-2.5">
                                Concepto
                            </th>

                            <th class="py-2.5 px-2.5">
                                Última Actividad
                            </th>

                            <th class="py-2.5 px-2.5 hidden md:table-cell">
                                Próximo Abono / Antigüedad
                            </th>

                            <th class="py-2.5 px-2.5 sm:px-4 text-right">
                                Saldo Pendiente
                            </th>

                            <th class="py-2.5 px-3
                                       text-center
                                       whitespace-nowrap
                                       hidden md:table-cell">
                                Estado
                            </th>

                        </tr>

                    </thead>


                    <tbody class="text-xs sm:text-sm
                                  divide-y
                                  divide-gray-100 dark:divide-gray-700/50"
                        id="tablaCobranzaBody">

                        @forelse($deudasGlobales ?? [] as $deuda)

                            @php

                                $rowStyleClass = '';

                                if ($deuda->is_atrasado) {

                                    $rowStyleClass =
                                        'bg-red-50/60 hover:bg-red-100/70
                                         dark:bg-red-950/20
                                         dark:hover:bg-red-900/35
                                         border-l-4 border-l-red-500';

                                } elseif ($deuda->is_proximo) {

                                    $rowStyleClass =
                                        'bg-amber-50/50 hover:bg-amber-100/70
                                         dark:bg-amber-950/20
                                         dark:hover:bg-amber-900/35
                                         border-l-4 border-l-amber-500';

                                } else {

                                    $rowStyleClass =
                                        'hover:bg-gray-50/80
                                         dark:hover:bg-gray-700/30
                                         border-l-4
                                         border-l-transparent';

                                }

                            @endphp


                            <tr onclick="window.location.href='{{ route('clients.show', $deuda->client_id) }}'"
                                class="transition-colors
                                       fila-item
                                       cursor-pointer
                                       {{ $rowStyleClass }}"
                                data-estado="{{ $deuda->is_atrasado ? 'retraso' : ($deuda->is_proximo ? 'proximos' : 'al_dia') }}">

                                <!-- CLIENTE -->
                                <td class="py-2.5 px-2.5 sm:px-4">

                                    <div class="font-bold
                                                text-gray-900 dark:text-white
                                                text-[11px] sm:text-sm
                                                leading-tight">

                                        {{ $deuda->client->name ?? 'Cliente General' }}

                                    </div>

                                    @if(!empty($deuda->client->alias))

                                        <!-- ALIAS:
                                             Se mantiene en sm y superiores.
                                             En móvil se oculta para compactar la fila. -->
                                        <div class="hidden sm:block
                                                    text-[10px] sm:text-[11px]
                                                    font-semibold
                                                    text-indigo-600 dark:text-indigo-400
                                                    mt-0.5">

                                            "{{ $deuda->client->alias }}"

                                        </div>

                                    @endif

                                </td>


                                <!-- DIRECCIÓN -->
                                <td class="py-2.5 px-2.5
                                           text-[11px] sm:text-xs
                                           text-gray-600 dark:text-gray-300
                                           hidden md:table-cell">

                                    {{ $deuda->client->address ?? 'Sin dirección' }}

                                </td>


                                <!-- CONCEPTO -->
                                <td class="py-2.5 px-2.5">

                                    <span class="font-medium
                                                 text-[11px] sm:text-xs
                                                 text-gray-800 dark:text-gray-200">

                                        {{ $deuda->concept }}

                                    </span>

                                </td>


                                <!-- ÚLTIMA ACTIVIDAD -->
                                <td class="py-2.5 px-2.5">

                                    <div class="hidden sm:block
                                                font-semibold
                                                text-[11px] sm:text-xs
                                                text-gray-800 dark:text-gray-200">

                                        {{ $deuda->fecha_ref }}

                                    </div>


                                    @if($deuda->is_loan)

                                        <div class="block
                                                    sm:text-[11px]
                                                    text-[9px]
                                                    font-medium
                                                    {{ $deuda->cuotas_vencidas > 0
                                                        ? 'text-red-600 font-semibold'
                                                        : 'text-emerald-600' }}
                                                    mt-0.5">

                                            ({{ $deuda->cuotas_vencidas > 0
                                                ? $deuda->cuotas_vencidas . ' abonos pend.'
                                                : 'Al corriente' }})

                                        </div>

                                    @else

                                        <div class="block
                                                    sm:text-[11px]
                                                    text-[9px]
                                                    font-medium
                                                    {{ $deuda->dias_sin_abonar > 7
                                                        ? 'text-red-600 font-semibold'
                                                        : 'text-emerald-600' }}
                                                    mt-0.5">

                                            ({{ $deuda->dias_sin_abonar }} días sin abonar)

                                        </div>

                                    @endif

                                </td>


                                <!-- PRÓXIMO ABONO -->
                                <td class="py-2.5 px-2.5
                                           hidden md:table-cell">

                                    @if($deuda->is_loan)

                                        <div class="text-xs font-medium
                                                    {{ $deuda->dias_proximo_abono < 0
                                                        ? 'text-red-600 font-semibold'
                                                        : ($deuda->dias_proximo_abono <= 2
                                                            ? 'text-amber-600 font-semibold'
                                                            : 'text-gray-700 dark:text-gray-300') }}">

                                            {{ $deuda->texto_proximo_abono }}

                                        </div>

                                    @else

                                        <div class="text-xs font-medium
                                                    {{ $deuda->dias_sin_abonar > 4
                                                        ? 'text-amber-600 font-semibold'
                                                        : 'text-gray-700 dark:text-gray-300' }}">

                                            {{ $deuda->dias_sin_abonar }}
                                            días desde movimiento

                                        </div>

                                    @endif

                                </td>


                                <!-- SALDO -->
                                <td class="py-2.5 px-2.5 sm:px-4
                                           text-right">

                                    <span class="text-[11px] sm:text-sm
                                                 font-black
                                                 text-gray-900 dark:text-white">

                                        ${{ number_format($deuda->saldo_pendiente, 2) }}

                                    </span>

                                </td>


                                <!-- ESTADO -->
                                <td class="py-2.5 px-3
                                           text-center
                                           whitespace-nowrap
                                           hidden md:table-cell">

                                    @if($deuda->is_atrasado)

                                        <span class="inline-flex
                                                     items-center
                                                     justify-center
                                                     px-2 py-0.5
                                                     bg-red-100 dark:bg-red-900/60
                                                     text-red-700 dark:text-red-300
                                                     rounded-md
                                                     font-bold
                                                     text-[9px]
                                                     tracking-wide
                                                     w-24
                                                     text-center">

                                            ⚠️ ATRASADO

                                        </span>

                                    @elseif($deuda->is_proximo)

                                        <span class="inline-flex
                                                     items-center
                                                     justify-center
                                                     px-2 py-0.5
                                                     bg-amber-100 dark:bg-amber-900/60
                                                     text-amber-700 dark:text-amber-300
                                                     rounded-md
                                                     font-bold
                                                     text-[9px]
                                                     tracking-wide
                                                     w-24
                                                     text-center">

                                            ⏰ PRÓXIMO

                                        </span>

                                    @else

                                        <span class="inline-flex
                                                     items-center
                                                     justify-center
                                                     px-2 py-0.5
                                                     bg-emerald-50 dark:bg-emerald-950/60
                                                     text-emerald-600 dark:text-emerald-400
                                                     rounded-md
                                                     font-bold
                                                     text-[9px]
                                                     tracking-wide
                                                     w-24
                                                     text-center">

                                            ✓ AL DÍA

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-10 text-gray-400">

                                    <div class="text-2xl mb-1">
                                        🎉
                                    </div>

                                    <p class="text-xs sm:text-sm
                                              font-bold
                                              text-gray-700 dark:text-gray-300">

                                        ¡Excelente trabajo!

                                    </p>

                                    <p class="text-[11px]
                                              text-gray-400
                                              mt-0.5">

                                        No hay saldos pendientes por cobrar
                                        en este momento.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    <!-- ============================================================= -->
    <!-- ESTILOS RESPONSIVOS                                          -->
    <!-- ============================================================= -->

    <style>

        /*
         * ==========================================================
         * NIVEL NORMAL
         * ==========================================================
         *
         * Buscar | Filtros | Excel/PDF
         */

        #contenidoAccionesCobranza {
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr) auto;
            grid-template-areas: "busqueda filtros exportar";
            align-items: center;
            gap: 0.75rem;
        }


        #bloqueBusquedaCobranza {
            grid-area: busqueda;
            width: 240px;
            min-width: 0;
        }


        #filtrosEstadoPrincipal {
            grid-area: filtros;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            flex-wrap: nowrap;
            min-width: 0;
            overflow: hidden;
        }


        #botonesExportarCobranza {
            grid-area: exportar;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
            white-space: nowrap;
        }


        #filtrosEstadoPrincipal button {
            flex-shrink: 0;
        }



        /*
         * ==========================================================
         * MODO MEDIANO
         * ==========================================================
         *
         * Se reduce ligeramente el tamaño de los componentes,
         * pero todos siguen en una sola fila.
         *
         * Solo se activa entre 650 y 789 px reales de ancho
         * del contenedor.
         */

        #contenidoAccionesCobranza.modo-mediano {
            grid-template-columns: 215px minmax(0, 1fr) auto;
            grid-template-areas: "busqueda filtros exportar";
            column-gap: 0.55rem;
        }


        #contenidoAccionesCobranza.modo-mediano
        #bloqueBusquedaCobranza {
            width: 215px;
            max-width: 215px;
        }


        #contenidoAccionesCobranza.modo-mediano
        #bloqueBusquedaCobranza input {

            font-size: 11px;

            padding-top: 0.35rem;
            padding-bottom: 0.35rem;

            padding-left: 2rem;
            padding-right: 0.55rem;

        }


        #contenidoAccionesCobranza.modo-mediano
        #bloqueBusquedaCobranza span {

            padding-left: 0.65rem;
            font-size: 10px;

        }


        #contenidoAccionesCobranza.modo-mediano
        #filtrosEstadoPrincipal {

            gap: 0.3rem;

        }


        #contenidoAccionesCobranza.modo-mediano
        #filtrosEstadoPrincipal button {

            padding-left: 0.55rem;
            padding-right: 0.55rem;

            padding-top: 0.35rem;
            padding-bottom: 0.35rem;

            font-size: 10px;

            border-radius: 0.65rem;

        }


        #contenidoAccionesCobranza.modo-mediano
        #botonesExportarCobranza {

            gap: 0.35rem;

        }


        #contenidoAccionesCobranza.modo-mediano
        .boton-exportar {

            padding-left: 0.55rem;
            padding-right: 0.55rem;

            padding-top: 0.35rem;
            padding-bottom: 0.35rem;

            font-size: 10px;

            border-radius: 0.65rem;

        }


        #contenidoAccionesCobranza.modo-mediano
        .icono-exportar {

            width: 0.8rem;
            height: 0.8rem;

            margin-right: 0.25rem;

        }



        /*
         * ==========================================================
         * MODO COMPACTO
         * ==========================================================
         */

        #contenidoAccionesCobranza.modo-compacto {
            grid-template-columns: minmax(0, 1fr) auto;
            grid-template-areas:
                "busqueda exportar"
                "filtros filtros";

            row-gap: 0.625rem;
            column-gap: 0.75rem;
        }


        #contenidoAccionesCobranza.modo-compacto
        #bloqueBusquedaCobranza {
            width: auto;
            max-width: none;
        }


        #contenidoAccionesCobranza.modo-compacto
        #filtrosEstadoPrincipal {
            width: 100%;
            overflow: visible;
            flex-wrap: nowrap;
            justify-content: flex-start;
        }



        /*
         * ==========================================================
         * MODO MUY PEQUEÑO
         * ==========================================================
         */

        #contenidoAccionesCobranza.modo-muy-pequeno {
            grid-template-columns: minmax(0, 1fr) auto;
            grid-template-areas:
                "busqueda exportar"
                "filtros filtros";

            column-gap: 0.4rem;
            row-gap: 0.45rem;
        }


        #contenidoAccionesCobranza.modo-muy-pequeno
        #bloqueBusquedaCobranza input {

            font-size: 10px;

            padding-top: 0.30rem;
            padding-bottom: 0.30rem;

            padding-left: 1.85rem;
            padding-right: 0.5rem;

        }


        #contenidoAccionesCobranza.modo-muy-pequeno
        #bloqueBusquedaCobranza span {

            padding-left: 0.55rem;
            font-size: 9px;

        }


        #contenidoAccionesCobranza.modo-muy-pequeno
        #filtrosEstadoPrincipal {

            gap: 0.25rem;

        }


        #contenidoAccionesCobranza.modo-muy-pequeno
        #filtrosEstadoPrincipal button {

            padding-left: 0.45rem;
            padding-right: 0.45rem;

            padding-top: 0.30rem;
            padding-bottom: 0.30rem;

            font-size: 9px;
            border-radius: 0.5rem;

        }


        #contenidoAccionesCobranza.modo-muy-pequeno
        #botonesExportarCobranza {

            gap: 0.25rem;

        }


        #contenidoAccionesCobranza.modo-muy-pequeno
        .boton-exportar {

            padding-left: 0.45rem;
            padding-right: 0.45rem;

            padding-top: 0.30rem;
            padding-bottom: 0.30rem;

            font-size: 9px;
            border-radius: 0.5rem;

        }


        #contenidoAccionesCobranza.modo-muy-pequeno
        .icono-exportar {

            width: 0.7rem;
            height: 0.7rem;

            margin-right: 0.2rem;

        }



        /*
         * ==========================================================
         * MODO EXTREMO
         * ==========================================================
         */

        #barraAccionesCobranza.scroll-acciones {

            overflow-x: auto;
            overflow-y: hidden;

        }


        #barraAccionesCobranza.scroll-acciones
        #contenidoAccionesCobranza {

            min-width: 320px;

        }


        #barraAccionesCobranza.scroll-acciones::-webkit-scrollbar {

            height: 5px;

        }


        #barraAccionesCobranza.scroll-acciones::-webkit-scrollbar-thumb {

            background: rgba(107, 114, 128, 0.45);
            border-radius: 10px;

        }


        #barraAccionesCobranza.scroll-acciones::-webkit-scrollbar-track {

            background: transparent;

        }



        /*
         * ==========================================================
         * TABLA — SCROLL INTERNO
         * ==========================================================
         */

        #scrollTablaCobranza {

            max-height: 65vh;

            overflow-y: auto;
            overflow-x: auto;

            scrollbar-width: thin;

        }


        #scrollTablaCobranza thead {

            position: sticky;
            top: 0;
            z-index: 10;

        }


        #scrollTablaCobranza::-webkit-scrollbar {

            width: 7px;
            height: 7px;

        }


        #scrollTablaCobranza::-webkit-scrollbar-thumb {

            background: rgba(107, 114, 128, 0.45);
            border-radius: 10px;

        }


        #scrollTablaCobranza::-webkit-scrollbar-track {

            background: transparent;

        }



        /*
         * ==========================================================
         * PANTALLAS PEQUEÑAS
         * ==========================================================
         */

        @media (max-width: 640px) {

            #scrollTablaCobranza {

                max-height: 62vh;

            }

        }



        /*
         * ==========================================================
         * PANTALLAS MUY PEQUEÑAS
         * ==========================================================
         */

        @media (max-width: 480px) {

            #contenidoAccionesCobranza {
                gap: 0.5rem;
            }

            #contenidoAccionesCobranza.modo-compacto {
                column-gap: 0.5rem;
                row-gap: 0.5rem;
            }

            #botonesExportarCobranza {
                gap: 0.35rem;
            }

        }


    </style>



    <!-- ============================================================= -->
    <!-- SCRIPT                                                        -->
    <!-- ============================================================= -->

    <script>


        /* ========================================================= */
        /* FILTROS                                                   */
        /* ========================================================= */

        let filtrosSeleccionados =
            new Set(['todos']);



        function toggleFiltro(tipo) {


            if (tipo === 'todos') {

                filtrosSeleccionados =
                    new Set(['todos']);

            } else {

                filtrosSeleccionados.delete('todos');


                if (filtrosSeleccionados.has(tipo)) {

                    filtrosSeleccionados.delete(tipo);

                } else {

                    filtrosSeleccionados.add(tipo);

                }

            }


            if (filtrosSeleccionados.size === 0) {

                filtrosSeleccionados.add('todos');

            }


            actualizarInterfazFiltros();

            aplicarFiltros();

        }



        /* ========================================================= */
        /* ACTUALIZAR ESTADO VISUAL DE LOS FILTROS                  */
        /* ========================================================= */

        function actualizarInterfazFiltros() {


            [
                'todos',
                'retraso',
                'proximos',
                'al_dia'
            ].forEach(tipo => {


                const btn =
                    document.getElementById(
                        `btn-${tipo}-principal`
                    );


                if (!btn) return;



                if (filtrosSeleccionados.has(tipo)) {


                    btn.className =
                        "filtro-btn " +
                        "px-2.5 py-1.5 " +
                        "bg-indigo-600 text-white " +
                        "rounded-xl " +
                        "text-xs font-bold " +
                        "shadow-xs transition " +
                        "whitespace-nowrap";


                } else {


                    btn.className =
                        "filtro-btn " +
                        "px-2.5 py-1.5 " +
                        "bg-gray-100 dark:bg-gray-700/60 " +
                        "text-gray-600 dark:text-gray-300 " +
                        "hover:bg-gray-200 dark:hover:bg-gray-600 " +
                        "rounded-xl " +
                        "text-xs font-semibold " +
                        "transition " +
                        "whitespace-nowrap";

                }

            });

        }



        /* ========================================================= */
        /* APLICAR FILTROS Y BÚSQUEDA                               */
        /* ========================================================= */

        function aplicarFiltros() {


            const input =
                document.getElementById(
                    'buscadorDashboard'
                );


            const texto =
                input
                    ? input.value.toLowerCase().trim()
                    : '';


            const elementos =
                document.querySelectorAll(
                    '.fila-item'
                );


            elementos.forEach(el => {


                const contenidoTexto =
                    el.innerText.toLowerCase();


                const estado =
                    el.getAttribute(
                        'data-estado'
                    );


                const coincideEstado =
                    filtrosSeleccionados.has('todos') ||
                    filtrosSeleccionados.has(estado);


                if (
                    coincideEstado &&
                    contenidoTexto.includes(texto)
                ) {

                    el.style.display = '';

                } else {

                    el.style.display = 'none';

                }

            });

        }



        /* ========================================================= */
        /* SINCRONIZAR BÚSQUEDA                                     */
        /* ========================================================= */

        function sincronizarBusqueda(valor) {


            const input =
                document.getElementById(
                    'buscadorDashboard'
                );


            if (
                input &&
                input.value !== valor
            ) {

                input.value = valor;

            }


            aplicarFiltros();

        }



        /* ========================================================= */
        /* AJUSTE DINÁMICO DE LA BARRA                              */
        /* ========================================================= */

        function ajustarBarraAccionesCobranza() {


            const barra =
                document.getElementById(
                    'barraAccionesCobranza'
                );


            const contenido =
                document.getElementById(
                    'contenidoAccionesCobranza'
                );


            const busqueda =
                document.getElementById(
                    'bloqueBusquedaCobranza'
                );


            const filtros =
                document.getElementById(
                    'filtrosEstadoPrincipal'
                );


            const exportar =
                document.getElementById(
                    'botonesExportarCobranza'
                );


            if (
                !barra ||
                !contenido ||
                !busqueda ||
                !filtros ||
                !exportar
            ) {

                return;

            }



            /*
             * Reiniciar todos los modos antes de volver
             * a calcular el tamaño correspondiente.
             */

            contenido.classList.remove(
                'modo-mediano',
                'modo-compacto',
                'modo-muy-pequeno'
            );

            barra.classList.remove(
                'scroll-acciones'
            );



            /*
             * Valores normales.
             */

            busqueda.style.width =
                '240px';

            busqueda.style.maxWidth =
                '240px';

            filtros.style.width =
                'auto';

            filtros.style.flexWrap =
                'nowrap';

            filtros.style.justifyContent =
                'flex-start';

            exportar.style.marginLeft =
                '0';



            const anchoDisponible =
                barra.clientWidth;



            /*
             * UMBRALES
             *
             * >= 790
             *      Normal
             *
             * 650 - 789
             *      Mediano
             *
             * 470 - 649
             *      Compacto
             *
             * 350 - 469
             *      Muy pequeño
             *
             * < 350
             *      Scroll
             */

            const ANCHO_MINIMO_NORMAL =
                790;


            const ANCHO_MINIMO_MEDIANO =
                650;


            const ANCHO_MINIMO_COMPACTO =
                470;


            const ANCHO_MINIMO_MUY_PEQUENO =
                350;



            /*
             * =====================================================
             * TAMAÑO NORMAL
             * =====================================================
             */

            if (
                anchoDisponible >=
                ANCHO_MINIMO_NORMAL
            ) {

                return;

            }



            /*
             * =====================================================
             * MODO MEDIANO
             * =====================================================
             *
             * Aquí solamente reducimos el tamaño.
             * Todo continúa en una sola fila.
             */

            contenido.classList.add(
                'modo-mediano'
            );


            busqueda.style.width =
                '215px';

            busqueda.style.maxWidth =
                '215px';



            if (
                anchoDisponible >=
                ANCHO_MINIMO_MEDIANO
            ) {

                return;

            }



            /*
             * =====================================================
             * MODO COMPACTO
             * =====================================================
             *
             * Buscador + exportación arriba.
             * Filtros abajo.
             */

            contenido.classList.add(
                'modo-compacto'
            );


            busqueda.style.width =
                'auto';

            busqueda.style.maxWidth =
                'none';



            if (
                anchoDisponible >=
                ANCHO_MINIMO_COMPACTO
            ) {

                return;

            }



            /*
             * =====================================================
             * MODO MUY PEQUEÑO
             * =====================================================
             */

            contenido.classList.add(
                'modo-muy-pequeno'
            );



            if (
                anchoDisponible >=
                ANCHO_MINIMO_MUY_PEQUENO
            ) {

                return;

            }



            /*
             * =====================================================
             * MODO EXTREMO
             * =====================================================
             *
             * Solo aquí se permite el scroll horizontal
             * de la barra de acciones.
             */

            barra.classList.add(
                'scroll-acciones'
            );

        }



        /* ========================================================= */
        /* OBSERVADOR DEL ANCHO REAL                                 */
        /* ========================================================= */

        function iniciarObservadorBarraCobranza() {


            const barra =
                document.getElementById(
                    'barraAccionesCobranza'
                );


            if (!barra) return;



            ajustarBarraAccionesCobranza();



            if (
                typeof ResizeObserver !==
                'undefined'
            ) {


                const observer =
                    new ResizeObserver(() => {

                        ajustarBarraAccionesCobranza();

                    });


                observer.observe(barra);


            } else {


                window.addEventListener(
                    'resize',
                    ajustarBarraAccionesCobranza
                );

            }

        }



        /* ========================================================= */
        /* INICIALIZACIÓN                                            */
        /* ========================================================= */

        document.addEventListener(
            'DOMContentLoaded',
            function () {


                actualizarInterfazFiltros();


                iniciarObservadorBarraCobranza();


            }
        );


    </script>

</x-app-layout>
