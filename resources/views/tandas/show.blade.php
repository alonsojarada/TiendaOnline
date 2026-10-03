<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center gap-2">

            <h2 class="font-semibold
                       text-base
                       sm:text-lg
                       lg:text-xl
                       text-gray-800
                       leading-tight
                       truncate">

                Detalle de Tanda: {{ $tanda->nombre }}

            </h2>


            <a href="{{ 
                match($origen ?? 'index') {
                    'reporte' => route('tandas.reporte.global'),
                    default => route('tandas.index')
                } }}"
                class="inline-flex
                       items-center
                       gap-1
                       sm:gap-2
                       px-2
                       sm:px-4
                       py-1.5
                       sm:py-2
                       text-[10px]
                       sm:text-xs
                       font-bold
                       text-indigo-600
                       dark:text-indigo-400
                       bg-indigo-50
                       dark:bg-indigo-950/50
                       hover:bg-indigo-100
                       dark:hover:bg-indigo-900/50
                       border
                       border-indigo-200
                       dark:border-indigo-800
                       rounded-lg
                       sm:rounded-xl
                       shadow-xs
                       transition-all
                       duration-200
                       group
                       shrink-0">

                <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">
                    &larr;
                </span>

                Volver

            </a>

        </div>

    </x-slot>


    @php

        // =========================================================
        // CÁLCULO GLOBAL DE RETRASOS
        // =========================================================

        $montoRetrasadoGlobal = 0;

        foreach ($tanda->participantes as $p) {

            foreach ($p->cuotas as $c) {

                if (
                    $c->estado !== 'pagado' &&
                    \Carbon\Carbon::parse($c->fecha_limite)->isPast()
                ) {

                    $montoRetrasadoGlobal +=
                        ($c->monto_esperado - $c->monto_pagado);

                }

            }

        }

    @endphp



    <!-- ========================================================= -->
    <!-- CONTENEDOR PRINCIPAL CON DETECTOR DE MENÚ LATERAL -->
    <!-- ========================================================= -->

    <div id="detalle-tanda-contenedor"
         class="py-1.5 sm:py-2"
         x-data="{
             init() {
                 const verificarMenu = () => {
                     // Buscamos el elemento del menú lateral en el layout principal
                     const sideMenu = document.getElementById('side-menu') || document.querySelector('aside') || document.querySelector('nav');
                     
                     if (sideMenu) {
                         // Obtenemos el ancho y visibilidad computada del menú lateral
                         const computedStyle = window.getComputedStyle(sideMenu);
                         const isVisible = computedStyle.display !== 'none' && computedStyle.visibility !== 'hidden' && sideMenu.offsetWidth > 0;
                         const anchoVentana = window.innerWidth;

                         // Si estamos en pantallas medianas (ej. entre 768px y 1280px) y el menú lateral está visible:
                         if (anchoVentana >= 768 && anchoVentana < 1280 && isVisible) {
                             document.body.classList.add('menu-lateral-activo');
                         } else {
                             document.body.classList.remove('menu-lateral-activo');
                         }
                     }
                 };

                 verificarMenu();
                 window.addEventListener('resize', verificarMenu);
                 
                 // Observador por si el menú se abre o cierra mediante botones de toggle
                 const observer = new MutationObserver(verificarMenu);
                 const sideMenu = document.getElementById('side-menu') || document.querySelector('aside');
                 if (sideMenu) {
                     observer.observe(sideMenu, { attributes: true, attributeFilter: ['class', 'style'] });
                 }
             }
         }">

        <div class="max-w-7xl mx-auto px-1 sm:px-4 lg:px-8">



            <!-- ===================================================== -->
            <!-- PANEL DE MÉTRICAS -->
            <!-- ===================================================== -->

            <div class="bg-white
                        rounded-lg
                        sm:rounded-xl
                        shadow-sm
                        border border-gray-100
                        px-2
                        sm:px-4
                        py-1.5
                        sm:py-2
                        mb-2
                        sm:mb-3
                        flex
                        flex-wrap
                        items-center
                        justify-between
                        gap-2
                        sm:gap-3
                        text-gray-800">


                <!-- ================================================= -->
                <!-- CUOTA / FRECUENCIA -->
                <!-- ================================================= -->

                <div class="flex flex-col
                            min-w-0
                            resumen-cuota">

                    <span class="text-[9px]
                                 sm:text-xs
                                 font-bold
                                 tracking-wider
                                 text-gray-600
                                 uppercase
                                 mb-0.5
                                 truncate">

                        Cuota / Frec.

                    </span>


                    <div class="flex items-baseline
                                space-x-1
                                sm:space-x-1.5
                                min-w-0">

                        <span class="text-base
                                     sm:text-lg
                                     lg:text-xl
                                     font-bold
                                     text-gray-900
                                     truncate">

                            ${{ number_format($tanda->monto_cuota, 2) }}

                        </span>


                        <span class="text-[9px]
                                     sm:text-xs
                                     font-semibold
                                     text-indigo-600
                                     capitalize
                                     truncate">

                            ({{ $tanda->frecuencia }})

                        </span>

                    </div>

                </div>


                <div class="hidden lg:block h-7 w-px bg-gray-200 separador-m-1"></div>



                <!-- ================================================= -->
                <!-- PARTICIPANTES -->
                <!-- ================================================= -->

                <div class="flex flex-col resumen-participantes">

                    <span class="text-[9px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">

                        Participantes

                    </span>

                    <span class="text-base sm:text-lg lg:text-xl font-bold text-gray-900">

                        @php

                            $integrantesSinCero =
                                $tanda->participantes
                                    ->where('turno', '!=', 0)
                                    ->count();

                        @endphp

                        {{ $integrantesSinCero }}

                    </span>

                </div>


                <div class="hidden lg:block h-7 w-px bg-gray-200 separador-m-2"></div>



                <!-- ================================================= -->
                <!-- MODALIDAD -->
                <!-- ================================================= -->

                <div class="flex flex-col resumen-modalidad">

                    <span class="text-[9px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">

                        Modalidad

                    </span>

                    <span class="text-base sm:text-lg lg:text-xl font-bold text-gray-900">

                        @php

                            $tieneOrganizadorEnCero =
                                $tanda->participantes->contains('turno', 0) ||
                                ($tanda->incluye_organizador ?? false);

                        @endphp

                        {{ $tieneOrganizadorEnCero ? 'Con Cero' : 'Normal' }}

                    </span>

                </div>


                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>



                <!-- ================================================= -->
                <!-- ENTREGADOS -->
                <!-- ================================================= -->

                <div class="flex flex-col resumen-entregados">

                    <span class="text-[9px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">

                        Entregados

                    </span>

                    <div class="flex items-baseline gap-1">

                        <span class="text-base sm:text-lg lg:text-xl font-bold text-indigo-600">

                            {{ $tanda->participantes->where('entregado', true)->count() }}

                        </span>

                        <span class="text-[10px] sm:text-sm font-medium text-gray-500">

                            de {{ $tanda->numero_participantes ?? $tanda->participantes->count() }}

                        </span>

                    </div>

                </div>


                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>



                <!-- ================================================= -->
                <!-- MONTO RETRASADO -->
                <!-- ================================================= -->

                <div class="flex flex-col min-w-0 resumen-retrasado">

                    <span class="text-[9px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5 truncate">

                        Monto Retrasado

                    </span>

                    <span class="text-base sm:text-lg lg:text-xl font-bold text-red-600 truncate">

                        ${{ number_format($montoRetrasadoGlobal, 2) }}

                    </span>

                </div>


                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>



                <!-- ================================================= -->
                <!-- INICIO -->
                <!-- ================================================= -->

                <div class="flex flex-col min-w-0 resumen-fecha">

                    <span class="text-[9px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">

                        Inicio

                    </span>

                    <span class="text-[10px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">

                        {{ \Carbon\Carbon::parse($tanda->fecha_inicio)->format('d/m/Y') }}

                    </span>

                </div>


                <div class="hidden lg:block h-7 w-px bg-gray-200"></div>



                <!-- ================================================= -->
                <!-- TÉRMINO -->
                <!-- ================================================= -->

                <div class="flex flex-col min-w-0 resumen-fecha">

                    <span class="text-[9px] sm:text-xs font-bold tracking-wider text-gray-600 uppercase mb-0.5">

                        Término

                    </span>

                    <span class="text-[10px] sm:text-sm font-semibold text-gray-800 whitespace-nowrap">

                        @php

                            $ultimaCuota =
                                $tanda->participantes
                                    ->flatMap->cuotas
                                    ->max('fecha_limite');

                        @endphp

                        {{ $ultimaCuota
                            ? \Carbon\Carbon::parse($ultimaCuota)->format('d/m/Y')
                            : 'N/A' }}

                    </span>

                </div>

            </div>



            <!-- ===================================================== -->
            <!-- LISTA DE PARTICIPANTES -->
            <!-- ===================================================== -->

            <div class="bg-white
                        overflow-hidden
                        shadow-sm
                        sm:rounded-lg
                        p-2
                        sm:p-3
                        lg:p-4
                        text-gray-900"
                 x-data="{ modoEdicion: false }">



                <!-- ================================================= -->
                <!-- CABECERA DE LA TABLA -->
                <!-- ================================================= -->

                <div class="flex justify-between items-center gap-2 mb-2 sm:mb-3">

                    <h3 class="text-[10px] sm:text-xs lg:text-sm font-semibold text-gray-700 uppercase tracking-wider truncate">

                        Calendario de Entregas

                    </h3>


                    <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">


                        <!-- ELIMINAR TANDA -->

                        <form action="{{ route('tandas.destroy', $tanda->id) }}"
                              method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar esta tanda? Se borrarán todos sus participantes y cuotas asociadas.');"
                              x-show="modoEdicion"
                              x-cloak
                              class="inline">

                            @csrf
                            @method('DELETE')


                            <button type="submit"
                                    class="bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md text-[9px] sm:text-xs font-semibold hover:bg-red-700 transition flex items-center gap-1 sm:gap-1.5 shadow-sm">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-3 w-3 sm:h-3.5 sm:w-3.5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 01-1 1h-4a1 1 0 01-1 1v3M4 7h16" />

                                </svg>

                                Eliminar Tanda

                            </button>

                        </form>



                        <!-- EDITAR -->

                        <label class="inline-flex items-center cursor-pointer bg-gray-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg border border-gray-200 hover:bg-gray-100 transition">

                            <input type="checkbox"
                                   x-model="modoEdicion"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3.5 h-3.5 sm:w-4 sm:h-4">

                            <span class="ml-1.5 sm:ml-2 text-[9px] sm:text-xs font-semibold text-gray-700 uppercase tracking-wider">

                                Editar

                            </span>

                        </label>

                    </div>

                </div>



                <!-- ================================================= -->
                <!-- CONTENEDOR TABLA -->
                <!-- ================================================= -->

                <div class="overflow-x-auto
                            overflow-y-auto
                            max-h-[calc(100vh-300px)]
                            sm:max-h-[calc(100vh-330px)]">

                    <table class="tabla-detalle min-w-full divide-y divide-gray-200 relative">

                        <thead class="bg-gray-50 sticky top-0 z-10">

                            <tr class="text-left font-semibold text-gray-500 uppercase tracking-wider">


                                <!-- TURNO -->

                                <th class="col-turno px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50">

                                    Turno

                                </th>


                                <!-- PARTICIPANTE -->

                                <th class="col-participante px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50">

                                    Participante

                                </th>


                                <!-- CICLO -->

                                <th class="col-ciclo px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50">

                                    Ciclo de Entrega

                                </th>


                                <!-- CUOTAS CUBIERTAS -->

                                <th class="col-cuotas-cubiertas px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50 text-center">

                                    Cuotas Cubiertas

                                </th>


                                <!-- MONTO CUBIERTO -->

                                <th class="col-monto-cubierto px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50 text-right">

                                    Monto Cubierto

                                </th>


                                <!-- ESTADO PAGOS -->

                                <th class="col-estado-pagos px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50 text-center">

                                    Estado Pagos

                                </th>


                                <!-- ENTREGA FÍSICA -->

                                <th class="col-entrega-fisica px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50 text-center">

                                    Entrega Física

                                </th>


                                <!-- PAGOS -->

                                <th class="col-pagos px-2 sm:px-4 py-1.5 sm:py-2.5 bg-gray-50 text-center">

                                    Pagos

                                </th>

                            </tr>

                        </thead>



                        <tbody class="bg-white divide-y divide-gray-100 text-xs sm:text-sm">


                            @foreach($tanda->participantes->sortBy('turno') as $participante)


                                @php

                                    $cuotasTotales =
                                        $participante->cuotas->count();

                                    $cuotasPagadas =
                                        $participante->cuotas
                                            ->where('estado', 'pagado')
                                            ->count();

                                    $montoCubierto =
                                        $participante->cuotas
                                            ->where('estado', 'pagado')
                                            ->sum('monto_esperado');

                                    $cuotasRetrasadasCount =
                                        $participante->cuotas
                                            ->filter(function ($c) {

                                                return
                                                    $c->estado !== 'pagado' &&
                                                    \Carbon\Carbon::parse(
                                                        $c->fecha_limite
                                                    )->isPast();

                                            })
                                            ->count();

                                    $cuotaDelCiclo =
                                        $participante->cuotas
                                            ->where(
                                                'ciclo',
                                                $participante->ciclo_entrega
                                            )
                                            ->first();

                                    $fechaEntregaProgramada =
                                        $cuotaDelCiclo
                                            ? $cuotaDelCiclo->fecha_limite
                                            : null;

                                @endphp



                                <tr class="hover:bg-gray-50/75 transition-colors">


                                    <!-- TURNO -->

                                    <td class="col-turno px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap font-bold text-gray-700">

                                        @if($participante->turno === 0)

                                            <span class="px-1.5 sm:px-2.5 py-0.5 sm:py-1 text-[9px] sm:text-xs font-bold bg-indigo-50 text-indigo-700 rounded-md">

                                                Org (0)

                                            </span>

                                        @else

                                            <span class="text-gray-700">

                                                {{ $participante->turno }}

                                            </span>

                                        @endif

                                    </td>



                                    <!-- PARTICIPANTE -->

                                    <td class="col-participante px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap">

                                        @if($participante->cliente_id)

                                            <div class="flex items-center space-x-1.5 sm:space-x-2">

                                                <span class="font-medium text-gray-900 text-[10px] sm:text-sm">

                                                    {{ $participante->cliente->name }}

                                                </span>


                                                @if(!$participante->entregado)

                                                    <form
                                                        action="{{ route('tandas.participantes.quitar-cliente', [$tanda->id, $participante->id]) }}"
                                                        method="POST"
                                                        class="inline"
                                                        x-show="modoEdicion"
                                                        x-cloak>

                                                        @csrf
                                                        @method('PATCH')


                                                        <button type="submit"
                                                                class="inline-flex items-center justify-center p-0.5 sm:p-1 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition"
                                                                title="Quitar cliente">

                                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5"
                                                                 fill="none"
                                                                 stroke="currentColor"
                                                                 viewBox="0 0 24 24">

                                                                <path stroke-linecap="round"
                                                                      stroke-linejoin="round"
                                                                      stroke-width="2.5"
                                                                      d="M6 18L18 6M6 6l12 12">
                                                                </path>

                                                            </svg>

                                                        </button>

                                                    </form>

                                                @endif

                                            </div>


                                        @elseif($participante->turno === 0)

                                            <span class="text-gray-500 italic text-[10px] sm:text-sm">

                                                Organizador

                                            </span>


                                        @else

                                            <form
                                                action="{{ route('tandas.participantes.asignar', [$tanda->id, $participante->id]) }}"
                                                method="POST"
                                                class="flex items-center space-x-1">

                                                @csrf
                                                @method('PATCH')


                                                <select name="cliente_id"
                                                        class="text-[10px] sm:text-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-0.5 sm:py-1 px-1 sm:px-2"
                                                        required>

                                                    <option value="">
                                                        Seleccionar cliente...
                                                    </option>


                                                    @foreach($clientes as $cliente)

                                                        <option value="{{ $cliente->id }}">

                                                            {{ $cliente->name }}

                                                        </option>

                                                    @endforeach

                                                </select>


                                                <button type="submit"
                                                        class="inline-flex items-center justify-center p-0.5 sm:p-1 bg-indigo-600 border border-transparent rounded-md text-white hover:bg-indigo-700 transition"
                                                        title="Guardar / Asignar cliente">

                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2.5"
                                                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0v1H3v-1z">
                                                        </path>

                                                    </svg>

                                                </button>

                                            </form>

                                        @endif

                                    </td>



                                    <!-- CICLO -->

                                    <td class="col-ciclo px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap text-gray-700">

                                        <div class="font-medium text-gray-800 text-[10px] sm:text-sm">

                                            Ciclo {{ $participante->ciclo_entrega }}

                                        </div>


                                        @if($fechaEntregaProgramada)

                                            <div class="text-[9px] sm:text-xs text-gray-400">

                                                ({{ \Carbon\Carbon::parse($fechaEntregaProgramada)->format('d/m/Y') }})

                                            </div>

                                        @endif

                                    </td>



                                    <!-- CUOTAS CUBIERTAS -->

                                    <td class="col-cuotas-cubiertas px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap text-center">

                                        <span class="px-1.5 sm:px-2.5 py-0.5 sm:py-1 text-[9px] sm:text-xs font-bold {{ $cuotasPagadas === $cuotasTotales && $cuotasTotales > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-indigo-50 text-indigo-700' }} rounded-full">

                                            {{ $cuotasPagadas }} / {{ $cuotasTotales }}

                                        </span>

                                    </td>



                                    <!-- MONTO CUBIERTO -->

                                    <td class="col-monto-cubierto px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap text-right font-medium text-emerald-600 text-[10px] sm:text-sm">

                                        ${{ number_format($montoCubierto, 2) }}

                                    </td>



                                    <!-- ESTADO PAGOS -->

                                    <td class="col-estado-pagos px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap text-center">

                                        @if($cuotasRetrasadasCount > 0)

                                            <span class="px-1.5 sm:px-2.5 py-0.5 sm:py-1 inline-flex text-[9px] sm:text-xs leading-4 sm:leading-5 font-semibold rounded-full bg-red-100 text-red-800">

                                                {{ $cuotasRetrasadasCount }}

                                                {{ $cuotasRetrasadasCount === 1
                                                    ? 'Retrasado'
                                                    : 'Retrasados' }}

                                            </span>

                                        @else

                                            <span class="px-1.5 sm:px-2.5 py-0.5 sm:py-1 inline-flex text-[9px] sm:text-xs leading-4 sm:leading-5 font-semibold rounded-full bg-green-100 text-green-800">

                                                Al corriente

                                            </span>

                                        @endif

                                    </td>



                                    <!-- ENTREGA FÍSICA -->

                                    <td class="col-entrega-fisica px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap text-center text-[10px] sm:text-sm">

                                        @if($participante->entregado)

                                            <div class="inline-flex items-center justify-center gap-1 sm:gap-1.5">

                                                <div class="inline-flex flex-col items-center">

                                                    <span class="px-1.5 sm:px-2.5 py-0.5 text-[9px] sm:text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">

                                                        Entregado

                                                    </span>

                                                    <span class="text-[8px] sm:text-[11px] text-gray-400 mt-0.5">

                                                        {{ \Carbon\Carbon::parse($participante->fecha_entrega)->format('d/m/Y') }}

                                                    </span>

                                                </div>


                                                <form
                                                    action="{{ route('tandas.participantes.anular-entrega', [$tanda->id, $participante->id]) }}"
                                                    method="POST"
                                                    class="inline"
                                                    x-show="modoEdicion"
                                                    x-cloak
                                                    onsubmit="return confirm('¿Estás seguro de anular la entrega de este turno?');">

                                                    @csrf
                                                    @method('PATCH')


                                                    <button type="submit"
                                                            class="inline-flex items-center justify-center p-0.5 sm:p-1 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition"
                                                            title="Anular entrega">

                                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             viewBox="0 0 24 24">

                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2.5"
                                                                  d="M6 18L18 6M6 6l12 12">
                                                            </path>

                                                        </svg>

                                                    </button>

                                                </form>

                                            </div>

                                        @else

                                            <div x-show="!modoEdicion">

                                                <span class="text-gray-400 italic text-[9px] sm:text-xs">

                                                    Bloqueado

                                                </span>

                                            </div>


                                            <form
                                                action="{{ route('tandas.participantes.entregar', $participante->id) }}"
                                                method="POST"
                                                class="inline-flex items-center space-x-1"
                                                x-show="modoEdicion"
                                                x-cloak>

                                                @csrf


                                                <input type="date"
                                                       name="fecha_entrega"
                                                       value="{{ date('Y-m-d') }}"
                                                       class="text-[9px] sm:text-xs border-gray-300 rounded-md shadow-sm py-0.5 sm:py-1 px-1 sm:px-1.5 w-[92px] sm:w-28">


                                                <button type="submit"
                                                        class="inline-flex items-center justify-center p-0.5 sm:p-1 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 transition"
                                                        title="Marcar como entregado">

                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5"
                                                         fill="none"
                                                         stroke="currentColor"
                                                         viewBox="0 0 24 24">

                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M5 13l4 4L19 7">
                                                        </path>

                                                    </svg>

                                                </button>

                                            </form>

                                        @endif

                                    </td>



                                    <!-- PAGOS -->

                                    <td class="col-pagos px-2 sm:px-4 py-1.5 sm:py-2.5 whitespace-nowrap text-center">

                                        <a href="{{ route('tandas.participante.cuotas', [$tanda->id, $participante->id]) }}?origen=tanda"
                                           class="text-indigo-600 hover:text-indigo-900 text-[9px] sm:text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 px-1.5 sm:px-3 py-1 sm:py-1.5 rounded-md transition-colors inline-block">

                                            Ver Cuotas

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>



    <!-- ========================================================= -->
    <!-- CSS RESPONSIVE Y CONFIGURACIÓN DE MENÚ LATERAL -->
    <!-- ========================================================= -->

    <style>

        /* ========================================================= */
        /* PANTALLAS GRANDES (ESCRITORIO) - REDUCCIÓN LEVE DE TÍTULOS */
        /* ========================================================= */
        @media screen and (min-width: 1024px) {
            .tabla-detalle thead th {
                font-size: 11px !important;
            }
        }



        /* ========================================================= */
        /* CUANDO EL MENÚ LATERAL ESTÁ ACTIVO EN PANTALLAS MEDIANAS */
        /* ========================================================= */

        body.menu-lateral-activo .resumen-modalidad,
        body.menu-lateral-activo .resumen-participantes,
        body.menu-lateral-activo .separador-m-1,
        body.menu-lateral-activo .separador-m-2,
        body.menu-lateral-activo .col-cuotas-cubiertas,
        body.menu-lateral-activo .col-monto-cubierto {

            display: none !important;

        }

        body.menu-lateral-activo .resumen-cuota,
        body.menu-lateral-activo .resumen-entregados,
        body.menu-lateral-activo .resumen-retrasado,
        body.menu-lateral-activo .resumen-fecha {

            transform: scale(0.85);
            transform-origin: center;

        }

        body.menu-lateral-activo .tabla-detalle {

            font-size: 9.5px !important;

        }

        body.menu-lateral-activo .tabla-detalle thead th {

            font-size: 7.5px !important;
            line-height: 1.05;
            letter-spacing: 0;
            white-space: nowrap;

        }

        body.menu-lateral-activo .tabla-detalle tbody td {

            font-size: 9.5px !important;

        }

        body.menu-lateral-activo .tabla-detalle th,
        body.menu-lateral-activo .tabla-detalle td {

            padding-left: 3px !important;
            padding-right: 3px !important;
            padding-top: 3.5px !important;
            padding-bottom: 3.5px !important;

        }



        /* ========================================================= */
        /* VENTANAS CHICAS (HASTA 767 PX) */
        /* ========================================================= */

        @media screen and (max-width: 767px) {

            .resumen-participantes,
            .resumen-modalidad,
            .resumen-entregados {

                display: none !important;

            }


            .resumen-cuota,
            .resumen-retrasado,
            .resumen-fecha {

                transform: scale(0.95);

                transform-origin: center;

            }


            .tabla-detalle {

                font-size: 9px !important;

            }


            .tabla-detalle thead th {

                font-size: 7px !important;

                line-height: 1.05;

                letter-spacing: 0;

                white-space: nowrap;

            }


            .tabla-detalle tbody td {

                font-size: 9px !important;

            }


            .col-cuotas-cubiertas,
            .col-monto-cubierto,
            .col-estado-pagos {

                display: none !important;

            }


            .tabla-detalle th,
            .tabla-detalle td {

                padding-left: 4px !important;

                padding-right: 4px !important;

                padding-top: 5px !important;

                padding-bottom: 5px !important;

            }


            .col-turno {

                width: 45px;

            }


            .col-participante {

                max-width: 120px;

            }


            .col-participante span {

                font-size: 9px !important;

            }


            .col-ciclo {

                width: 70px;

            }


            .col-entrega-fisica {

                width: 100px;

            }


            .col-pagos {

                width: 65px;

            }


            .col-pagos a {

                font-size: 8px !important;

                padding-left: 5px !important;

                padding-right: 5px !important;

                padding-top: 3px !important;

                padding-bottom: 3px !important;

            }


            .col-participante select {

                max-width: 100px;

                font-size: 8px !important;

            }


            .col-entrega-fisica input[type="date"] {

                width: 88px !important;

                font-size: 8px !important;

            }


            .col-entrega-fisica span {

                font-size: 8px !important;

            }

        }



        /* ========================================================= */
        /* MÓVILES MUY PEQUEÑOS (HASTA 400 PX) */
        /* ========================================================= */

        @media screen and (max-width: 400px) {

            .resumen-cuota,
            .resumen-retrasado,
            .resumen-fecha {

                transform: scale(0.9);

            }


            .tabla-detalle thead th {

                font-size: 6.5px !important;

            }


            .tabla-detalle tbody td {

                font-size: 8px !important;

            }


            .tabla-detalle th,
            .tabla-detalle td {

                padding-left: 3px !important;

                padding-right: 3px !important;

                padding-top: 4px !important;

                padding-bottom: 4px !important;

            }


            .col-participante {

                max-width: 100px;

            }


            .col-participante span {

                font-size: 8px !important;

            }


            .col-ciclo {

                width: 62px;

            }


            .col-entrega-fisica {

                width: 90px;

            }


            .col-pagos {

                width: 58px;

            }


            .col-pagos a {

                font-size: 7.5px !important;

                padding-left: 4px !important;

                padding-right: 4px !important;

            }


            .col-entrega-fisica input[type="date"] {

                width: 82px !important;

                font-size: 7.5px !important;

            }

        }



        /* ========================================================= */
        /* MÓVILES EXTREMADAMENTE PEQUEÑOS (HASTA 320 PX) */
        /* ========================================================= */

        @media screen and (max-width: 320px) {

            .tabla-detalle thead th {

                font-size: 6px !important;

            }


            .tabla-detalle tbody td {

                font-size: 7.5px !important;

            }


            .tabla-detalle th,
            .tabla-detalle td {

                padding-left: 2px !important;

                padding-right: 2px !important;

                padding-top: 3px !important;

                padding-bottom: 3px !important;

            }


            .col-turno {

                width: 38px;

            }


            .col-participante {

                max-width: 85px;

            }


            .col-ciclo {

                width: 55px;

            }


            .col-entrega-fisica {

                width: 82px;

            }


            .col-pagos {

                width: 52px;

            }


            .col-pagos a {

                font-size: 7px !important;

                padding-left: 3px !important;

                padding-right: 3px !important;

                padding-top: 2px !important;

                padding-bottom: 2px !important;

            }


            .col-entrega-fisica input[type="date"] {

                width: 76px !important;

                font-size: 7px !important;

            }

        }

    </style>

</x-app-layout>