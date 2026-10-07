<x-app-layout>

    <!-- ========================================================= -->
    <!-- ESTILOS RESPONSIVOS -->
    <!-- ========================================================= -->

    <style>

        /* =========================================================
           ESCALA GENERAL DE LA VISTA
           ========================================================= */

        .cobranza-responsive {
            --escala-fuente: clamp(0.82rem, 0.72rem + 0.35vw, 0.95rem);
            --padding-componentes: clamp(0.45rem, 0.35rem + 0.25vw, 0.75rem);
            --padding-tabla: clamp(0.35rem, 0.25rem + 0.20vw, 0.75rem);
        }


        /* =========================================================
           VENTANAS MEDIANAS
           ========================================================= */

        @media (max-width: 1200px) {

            .cobranza-responsive .texto-responsive {
                font-size: 0.82rem !important;
            }

            .cobranza-responsive .componente-responsive {
                padding: 0.45rem !important;
            }

            .cobranza-responsive .tabla-responsive th,
            .cobranza-responsive .tabla-responsive td {
                padding-left: 0.45rem !important;
                padding-right: 0.45rem !important;
            }
        }


        /* =========================================================
           VENTANAS PEQUEÑAS
           ========================================================= */

        @media (max-width: 900px) {

            .cobranza-responsive .texto-responsive {
                font-size: 0.76rem !important;
            }

            .cobranza-responsive .componente-responsive {
                padding: 0.35rem !important;
            }

            .cobranza-responsive .tabla-responsive th,
            .cobranza-responsive .tabla-responsive td {
                padding-top: 0.35rem !important;
                padding-bottom: 0.35rem !important;
                padding-left: 0.35rem !important;
                padding-right: 0.35rem !important;
            }

            .cobranza-responsive .boton-responsive {
                font-size: 0.68rem !important;
                padding: 0.30rem 0.45rem !important;
            }
        }


        /* =========================================================
           EL CONTENEDOR DE LA TABLA ES EL QUE MANDA
           ========================================================= */

        .tabla-container-responsive {
            container-type: inline-size;
            container-name: tablaCobranza;
        }


        /* =========================================================
           CONTENEDOR GRANDE
           1100 PX O MÁS
           
           TODAS LAS COLUMNAS VISIBLES
           ========================================================= */

        @container tablaCobranza (min-width: 1100px) {

            .col-origen,
            .col-turno,
            .col-cuotas,
            .col-fecha {
                display: table-cell !important;
            }
        }


        /* =========================================================
           CONTENEDOR MEDIANO
           800 - 1099 PX
           
           SE OCULTAN:
           - Origen
           - Fecha límite
           ========================================================= */

        @container tablaCobranza (max-width: 1099px) {

            .col-origen,
            .col-fecha {
                display: none !important;
            }
        }


        /* =========================================================
           CONTENEDOR PEQUEÑO
           600 - 799 PX
           
           TAMBIÉN SE OCULTAN:
           - Turno
           - Cuotas
           ========================================================= */

        @container tablaCobranza (max-width: 799px) {

            .col-turno,
            .col-cuotas {
                display: none !important;
            }
        }


        /* =========================================================
           CONTENEDOR MUY PEQUEÑO
           MENOS DE 600 PX
           ========================================================= */

        @container tablaCobranza (max-width: 600px) {

            .tabla-responsive {
                font-size: 0.70rem !important;
            }

            .tabla-responsive th,
            .tabla-responsive td {
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
            }

            .tabla-responsive .btn-texto {
                display: none !important;
            }

            .tabla-responsive .boton-accion {
                padding: 0.30rem !important;
            }

            .tabla-responsive svg {
                width: 0.75rem !important;
                height: 0.75rem !important;
            }
        }

    </style>


    <x-slot name="header">

        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Cobranza de Tandas') }}
        </h2>

    </x-slot>


    <!-- ========================================================= -->
    <!-- CONTENEDOR GLOBAL -->
    <!-- ========================================================= -->

    <div
        class="cobranza-responsive py-3 sm:py-4 text-xs sm:text-sm"
        x-data="{

            modalOpen: false,

            participanteId: '',
            clienteNombre: '',
            tandaNombre: '',

            cuotasPendientesCount: 0,
            valorCuota: 0,
            cantidadCuotasAPagar: 1,

            filtroGeneral: '',

            ordenColumna: '',
            ordenDireccion: 'asc',


            /* =====================================================
               ORDENAMIENTO
               ===================================================== */

            ordenarPor(columna) {

                if (this.ordenColumna === columna) {

                    this.ordenDireccion =
                        this.ordenDireccion === 'asc'
                            ? 'desc'
                            : 'asc';

                } else {

                    this.ordenColumna = columna;
                    this.ordenDireccion = 'asc';
                }

                this.ejecutarOrdenamiento();
            },


            ejecutarOrdenamiento() {

                const tbody = this.$refs.tablaCuerpo;

                if (!tbody) return;

                const filas =
                    Array.from(
                        tbody.querySelectorAll('.fila-item')
                    );

                const col = this.ordenColumna;
                const dir = this.ordenDireccion;


                filas.sort((a, b) => {

                    let valA;
                    let valB;


                    if (col === 'pendiente') {

                        valA =
                            parseFloat(a.dataset.pendiente) || 0;

                        valB =
                            parseFloat(b.dataset.pendiente) || 0;

                        return dir === 'asc'
                            ? valA - valB
                            : valB - valA;

                    } else {

                        valA = a.dataset[col] || '';
                        valB = b.dataset[col] || '';

                        return dir === 'asc'
                            ? valA.localeCompare(valB)
                            : valB.localeCompare(valA);
                    }

                });


                filas.forEach(fila => {

                    tbody.appendChild(fila);

                });
            },


            /* =====================================================
               TOTAL A PAGAR
               ===================================================== */

            get totalAPagar() {

                return Number(this.cantidadCuotasAPagar)
                    * Number(this.valorCuota);

            },


            /* =====================================================
               ABRIR MODAL
               ===================================================== */

            abrir(
                pId,
                cNom,
                tNom,
                cCount,
                vCuota
            ) {

                this.participanteId = pId;

                this.clienteNombre = cNom;

                this.tandaNombre = tNom;

                this.cuotasPendientesCount =
                    Number(cCount);

                this.valorCuota =
                    Number(vCuota);

                this.cantidadCuotasAPagar = 1;

                this.modalOpen = true;
            }

        }"
    >


        <!-- ========================================================= -->
        <!-- CONTENEDOR PRINCIPAL -->
        <!-- ========================================================= -->

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2 sm:px-3">


            <!-- ===================================================== -->
            <!-- TARJETAS SUPERIORES -->
            <!-- ===================================================== -->

            <div
                class="
                    grid grid-cols-2
                    sm:flex sm:flex-wrap
                    items-center
                    gap-2.5 sm:gap-3
                    mb-3.5 sm:mb-4
                "
            >


                <!-- ================================================= -->
                <!-- TOTAL VENCIDO -->
                <!-- ================================================= -->

                <div
                    class="
                        relative
                        w-full sm:w-52
                        bg-red-50
                        border border-red-200
                        overflow-hidden
                        shadow-sm
                        sm:rounded-lg
                        px-2.5 py-2
                        sm:px-3 sm:py-2.5
                        pr-8 sm:pr-9
                        flex flex-col justify-between
                        componente-responsive
                    "
                >

                    <div
                        class="
                            absolute
                            right-2
                            top-1/2
                            -translate-y-1/2
                            p-1.5
                            bg-red-100
                            rounded-lg
                            text-red-600
                            flex items-center justify-center
                            pointer-events-none
                        "
                    >

                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />

                        </svg>

                    </div>


                    <div>

                        <div
                            class="
                                text-[10px] sm:text-[11px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-red-700
                            "
                        >
                            Total Vencido
                        </div>


                        <div
                            class="
                                text-sm sm:text-xl
                                font-black
                                text-red-700
                                leading-tight
                                my-0.5
                            "
                        >
                            ${{ number_format($cuotasVencidas->sum('total_pendiente'), 2) }}
                        </div>


                        <div
                            class="
                                text-[10px] sm:text-xs
                                text-red-600
                                font-bold
                            "
                        >
                            {{ $cuotasVencidas->sum('cantidad_atrasadas') }}
                            cuotas atrasadas
                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- VENCEN ESTA SEMANA -->
                <!-- ================================================= -->

                <div
                    class="
                        relative
                        w-full sm:w-52
                        bg-amber-50
                        border border-amber-200
                        overflow-hidden
                        shadow-sm
                        sm:rounded-lg
                        px-2.5 py-2
                        sm:px-3 sm:py-2.5
                        pr-8 sm:pr-9
                        flex flex-col justify-between
                        componente-responsive
                    "
                >

                    <div
                        class="
                            absolute
                            right-2
                            top-1/2
                            -translate-y-1/2
                            p-1.5
                            bg-amber-100
                            rounded-lg
                            text-amber-600
                            flex items-center justify-center
                            pointer-events-none
                        "
                    >

                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v12a2 2 0 002 2z"
                            />

                        </svg>

                    </div>


                    <div>

                        <div
                            class="
                                text-[10px] sm:text-[11px]
                                font-bold
                                uppercase
                                tracking-wider
                                text-amber-700
                            "
                        >
                            Vencen Esta Semana
                        </div>


                        <div
                            class="
                                text-sm sm:text-xl
                                font-black
                                text-amber-700
                                leading-tight
                                my-0.5
                            "
                        >
                            ${{ number_format($cuotasEstaSemana->sum('total_pendiente'), 2) }}
                        </div>


                        <div
                            class="
                                text-[10px] sm:text-xs
                                text-amber-700
                                font-bold
                            "
                        >
                            {{ $cuotasEstaSemana->sum('cantidad_proximas') }}
                            cuotas próximas
                        </div>

                    </div>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- CONTENEDOR TABLA -->
            <!-- ===================================================== -->

            <div
                class="
                    bg-white
                    overflow-hidden
                    shadow-sm
                    sm:rounded-lg
                    p-2.5 sm:p-4
                    componente-responsive
                "
            >


                <!-- ================================================= -->
                <!-- BUSCADOR Y BOTONES -->
                <!-- ================================================= -->

                <div
                    class="
                        flex
                        flex-col
                        sm:flex-row
                        justify-between
                        items-stretch
                        sm:items-center
                        gap-2.5 sm:gap-3
                        mb-3
                    "
                >


                    <!-- BUSCADOR -->

                    <div class="w-full sm:w-80">

                        <div class="relative">

                            <div
                                class="
                                    absolute
                                    inset-y-0
                                    left-0
                                    pl-3
                                    flex
                                    items-center
                                    pointer-events-none
                                    text-gray-400
                                "
                            >

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />

                                </svg>

                            </div>


                            <input
                                type="text"
                                x-model="filtroGeneral"
                                placeholder="Buscar participante u origen..."
                                class="
                                    w-full
                                    pl-9
                                    pr-3
                                    py-1.5
                                    text-xs
                                    border-gray-300
                                    rounded-lg
                                    shadow-sm
                                    focus:border-indigo-500
                                    focus:ring-indigo-500
                                    texto-responsive
                                "
                            >

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- BOTONES EXPORTAR -->
                    <!-- ================================================= -->

                    <div
                        class="
                            grid
                            grid-cols-2
                            sm:flex
                            sm:items-center
                            gap-2
                            w-full
                            sm:w-auto
                        "
                    >

                        <a
                            href="#"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                gap-1.5
                                px-3
                                py-1.5
                                bg-emerald-600
                                hover:bg-emerald-700
                                text-white
                                rounded-lg
                                text-xs
                                font-semibold
                                shadow-sm
                                transition
                                boton-responsive
                            "
                        >

                            <svg
                                class="w-3.5 h-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                />

                            </svg>

                            Excel

                        </a>


                        <a
                            href="#"
                            class="
                                inline-flex
                                items-center
                                justify-center
                                gap-1.5
                                px-3
                                py-1.5
                                bg-rose-600
                                hover:bg-rose-700
                                text-white
                                rounded-lg
                                text-xs
                                font-semibold
                                shadow-sm
                                transition
                                boton-responsive
                            "
                        >

                            <svg
                                class="w-3.5 h-3.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />

                            </svg>

                            PDF

                        </a>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- CONTENEDOR REAL DE LA TABLA -->
                <!-- ================================================= -->

                <div
                    class="
                        tabla-container-responsive
                        overflow-y-auto
                        overflow-x-auto
                        relative
                        rounded-lg
                        border border-gray-100
                    "
                    style="max-height: 64vh;"
                >


                    <table
                        class="
                            tabla-responsive
                            w-full
                            divide-y
                            divide-gray-200
                        "
                    >


                        <!-- ========================================= -->
                        <!-- ENCABEZADO -->
                        <!-- ========================================= -->

                        <thead
                            class="
                                bg-gray-50
                                text-[10px]
                                sm:text-xs
                                font-semibold
                                text-gray-500
                                uppercase
                                tracking-wider
                                sticky
                                top-0
                                z-10
                                shadow-sm
                            "
                        >

                            <tr>


                                <!-- ================================= -->
                                <!-- PARTICIPANTE -->
                                <!-- ================================= -->

                                <th
                                    @click="ordenarPor('nombre')"
                                    class="
                                        px-2 sm:px-4
                                        py-2
                                        text-left
                                        bg-gray-50
                                        cursor-pointer
                                        hover:bg-gray-100
                                        transition
                                    "
                                >

                                    <div class="flex items-center gap-1">

                                        Participante

                                        <span
                                            x-show="ordenColumna === 'nombre'"
                                            x-text="
                                                ordenDireccion === 'asc'
                                                    ? '▲'
                                                    : '▼'
                                            "
                                            class="text-[9px] text-indigo-600"
                                        ></span>

                                    </div>

                                </th>


                                <!-- ================================= -->
                                <!-- ORIGEN -->
                                <!-- ================================= -->

                                <th
                                    @click="ordenarPor('origen')"
                                    class="
                                        col-origen
                                        px-4
                                        py-2
                                        text-left
                                        bg-gray-50
                                        cursor-pointer
                                        hover:bg-gray-100
                                        transition
                                    "
                                >

                                    <div class="flex items-center gap-1">

                                        Origen

                                        <span
                                            x-show="ordenColumna === 'origen'"
                                            x-text="
                                                ordenDireccion === 'asc'
                                                    ? '▲'
                                                    : '▼'
                                            "
                                            class="text-[9px] text-indigo-600"
                                        ></span>

                                    </div>

                                </th>


                                <!-- ================================= -->
                                <!-- TANDA -->
                                <!-- ================================= -->

                                <th
                                    @click="ordenarPor('tanda')"
                                    class="
                                        px-2 sm:px-4
                                        py-2
                                        text-left
                                        bg-gray-50
                                        cursor-pointer
                                        hover:bg-gray-100
                                        transition
                                    "
                                >

                                    <div class="flex items-center gap-1">

                                        Tanda

                                        <span
                                            x-show="ordenColumna === 'tanda'"
                                            x-text="
                                                ordenDireccion === 'asc'
                                                    ? '▲'
                                                    : '▼'
                                            "
                                            class="text-[9px] text-indigo-600"
                                        ></span>

                                    </div>

                                </th>


                                <!-- ================================= -->
                                <!-- TURNO -->
                                <!-- ================================= -->

                                <th
                                    class="
                                        col-turno
                                        px-3 sm:px-4
                                        py-2
                                        text-center
                                        bg-gray-50
                                    "
                                >
                                    Turno
                                </th>


                                <!-- ================================= -->
                                <!-- CUOTAS -->
                                <!-- ================================= -->

                                <th
                                    class="
                                        col-cuotas
                                        px-3 sm:px-4
                                        py-2
                                        text-center
                                        bg-gray-50
                                    "
                                >
                                    Cuotas
                                </th>


                                <!-- ================================= -->
                                <!-- FECHA LIMITE -->
                                <!-- ================================= -->

                                <th
                                    @click="ordenarPor('fecha')"
                                    class="
                                        col-fecha
                                        px-4
                                        py-2
                                        text-center
                                        bg-gray-50
                                        cursor-pointer
                                        hover:bg-gray-100
                                        transition
                                    "
                                >

                                    <div
                                        class="
                                            flex
                                            items-center
                                            justify-center
                                            gap-1
                                        "
                                    >

                                        Fecha Límite

                                        <span
                                            x-show="ordenColumna === 'fecha'"
                                            x-text="
                                                ordenDireccion === 'asc'
                                                    ? '▲'
                                                    : '▼'
                                            "
                                            class="text-[9px] text-indigo-600"
                                        ></span>

                                    </div>

                                </th>


                                <!-- ================================= -->
                                <!-- PENDIENTE -->
                                <!-- ================================= -->

                                <th
                                    @click="ordenarPor('pendiente')"
                                    class="
                                        px-2 sm:px-4
                                        py-2
                                        text-right
                                        bg-gray-50
                                        cursor-pointer
                                        hover:bg-gray-100
                                        transition
                                    "
                                >

                                    <div
                                        class="
                                            flex
                                            items-center
                                            justify-end
                                            gap-1
                                        "
                                    >

                                        Pendiente

                                        <span
                                            x-show="ordenColumna === 'pendiente'"
                                            x-text="
                                                ordenDireccion === 'asc'
                                                    ? '▲'
                                                    : '▼'
                                            "
                                            class="text-[9px] text-indigo-600"
                                        ></span>

                                    </div>

                                </th>


                                <!-- ================================= -->
                                <!-- ACCIONES -->
                                <!-- ================================= -->

                                <th
                                    class="
                                        px-2 sm:px-4
                                        py-2
                                        text-center
                                        bg-gray-50
                                    "
                                >
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <!-- ========================================= -->
                        <!-- CUERPO -->
                        <!-- ========================================= -->

                        <tbody
                            class="
                                divide-y
                                divide-gray-100
                                bg-white
                            "
                            x-ref="tablaCuerpo"
                        >

                            @php

                                $todasLasCuotas =
                                    $cuotasVencidas->concat(
                                        $cuotasEstaSemana
                                    );

                            @endphp


                            @forelse($todasLasCuotas as $cuota)

                                @php

                                    $esVencida =
                                        isset(
                                            $cuota->cantidad_atrasadas
                                        );


                                    $nombreCliente =
                                        $cuota->participante->cliente->nombre
                                        ??
                                        $cuota->participante->cliente->name
                                        ??
                                        'Sin nombre';


                                    $nombreTanda =
                                        $cuota->participante->tanda->nombre
                                        ??
                                        'Tanda';


                                    $origenCliente =
                                        $cuota->participante->cliente->address
                                        ??
                                        'N/D';


                                    $totalPendiente =
                                        $cuota->total_pendiente;


                                    $cantidadCuotas =
                                        $esVencida
                                            ? $cuota->cantidad_atrasadas
                                            : $cuota->cantidad_proximas;


                                    $fechaStr =
                                        $esVencida
                                            ? $cuota->fecha_mas_antigua
                                            : $cuota->fecha_proxima;


                                    $fechaFormateada =
                                        \Carbon\Carbon::parse(
                                            $fechaStr
                                        )->format('d/m/Y');


                                    $fechaOrdenable =
                                        \Carbon\Carbon::parse(
                                            $fechaStr
                                        )->format('Y-m-d');


                                    $valorUnitarioCuota =
                                        $cantidadCuotas > 0
                                            ? (
                                                $totalPendiente
                                                /
                                                $cantidadCuotas
                                            )
                                            : 0;


                                    if ($esVencida) {

                                        $textoEstadoCuotas =
                                            $cantidadCuotas
                                            .
                                            (
                                                $cantidadCuotas === 1
                                                    ? ' Vencida'
                                                    : ' Vencidas'
                                            );

                                    } else {

                                        $textoEstadoCuotas =
                                            $cantidadCuotas
                                            .
                                            ' Por Vencer';

                                    }


                                    $tandaId =
                                        $cuota->participante->tanda_id
                                        ??
                                        $cuota->participante->tanda->id
                                        ??
                                        null;


                                    $participanteId =
                                        $cuota->tanda_participante_id;

                                @endphp


                                <tr
                                    class="
                                        {{
                                            $esVencida
                                                ? 'hover:bg-red-50/50'
                                                : 'hover:bg-amber-50/50'
                                        }}
                                        transition-colors
                                        fila-item
                                    "
                                    data-nombre="{{ strtolower($nombreCliente) }}"
                                    data-origen="{{ strtolower($origenCliente) }}"
                                    data-tanda="{{ strtolower($nombreTanda) }}"
                                    data-fecha="{{ $fechaOrdenable }}"
                                    data-pendiente="{{ $totalPendiente }}"
                                    x-show="
                                        filtroGeneral === '' ||
                                        {{ json_encode(strtolower($nombreCliente)) }}
                                            .includes(
                                                filtroGeneral.toLowerCase()
                                            ) ||
                                        {{ json_encode(strtolower($origenCliente)) }}
                                            .includes(
                                                filtroGeneral.toLowerCase()
                                            )
                                    "
                                >


                                    <!-- ================================= -->
                                    <!-- PARTICIPANTE -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            px-2 sm:px-4
                                            py-2
                                            whitespace-nowrap
                                        "
                                    >

                                        <div
                                            class="
                                                font-bold
                                                text-gray-900
                                                text-[11px]
                                                sm:text-sm
                                                leading-tight
                                            "
                                        >
                                            {{ $nombreCliente }}
                                        </div>

                                    </td>


                                    <!-- ================================= -->
                                    <!-- ORIGEN -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            col-origen
                                            px-4
                                            py-2
                                            whitespace-nowrap
                                        "
                                    >

                                        <div
                                            class="
                                                text-xs
                                                text-gray-600
                                                font-medium
                                            "
                                        >
                                            {{ $origenCliente }}
                                        </div>

                                    </td>


                                    <!-- ================================= -->
                                    <!-- TANDA -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            px-2 sm:px-4
                                            py-2
                                            whitespace-nowrap
                                        "
                                    >

                                        <div
                                            class="
                                                text-[11px]
                                                sm:text-sm
                                                text-indigo-600
                                                font-semibold
                                                leading-tight
                                            "
                                        >
                                            {{ $nombreTanda }}
                                        </div>

                                    </td>


                                    <!-- ================================= -->
                                    <!-- TURNO -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            col-turno
                                            px-3 sm:px-4
                                            py-2
                                            whitespace-nowrap
                                            text-center
                                        "
                                    >

                                        <span
                                            class="
                                                px-2
                                                py-0.5
                                                bg-gray-100
                                                text-gray-700
                                                rounded
                                                text-xs
                                                font-medium
                                            "
                                        >
                                            Turno
                                            {{ $cuota->participante->turno ?? 'N/A' }}
                                        </span>

                                    </td>


                                    <!-- ================================= -->
                                    <!-- CUOTAS -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            col-cuotas
                                            px-3 sm:px-4
                                            py-2
                                            whitespace-nowrap
                                            text-center
                                        "
                                    >

                                        @if($esVencida)

                                            <span
                                                class="
                                                    px-2
                                                    py-0.5
                                                    text-xs
                                                    font-bold
                                                    bg-red-100
                                                    text-red-700
                                                    rounded-full
                                                    inline-block
                                                "
                                            >
                                                {{ $textoEstadoCuotas }}
                                            </span>

                                        @else

                                            <span
                                                class="
                                                    px-2
                                                    py-0.5
                                                    text-xs
                                                    font-bold
                                                    bg-amber-100
                                                    text-amber-800
                                                    rounded-full
                                                    inline-block
                                                "
                                            >
                                                {{ $textoEstadoCuotas }}
                                            </span>

                                        @endif

                                    </td>


                                    <!-- ================================= -->
                                    <!-- FECHA LIMITE -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            col-fecha
                                            px-4
                                            py-2
                                            whitespace-nowrap
                                            text-center
                                            {{
                                                $esVencida
                                                    ? 'text-red-600'
                                                    : 'text-amber-700'
                                            }}
                                            font-bold
                                            text-xs
                                            sm:text-sm
                                        "
                                    >

                                        {{ $fechaFormateada }}

                                    </td>


                                    <!-- ================================= -->
                                    <!-- PENDIENTE -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            px-2 sm:px-4
                                            py-2
                                            whitespace-nowrap
                                            text-right
                                            font-black
                                            {{
                                                $esVencida
                                                    ? 'text-red-600'
                                                    : 'text-amber-700'
                                            }}
                                            text-[11px]
                                            sm:text-base
                                        "
                                    >

                                        ${{ number_format($totalPendiente, 2) }}

                                    </td>


                                    <!-- ================================= -->
                                    <!-- ACCIONES -->
                                    <!-- ================================= -->

                                    <td
                                        class="
                                            px-2 sm:px-4
                                            py-2
                                            whitespace-nowrap
                                            text-center
                                        "
                                    >

                                        <div
                                            class="
                                                flex
                                                items-center
                                                justify-center
                                                gap-1
                                            "
                                        >


                                            <!-- ================================= -->
                                            <!-- PAGAR -->
                                            <!-- ================================= -->

                                            <button
                                                @click="
                                                    abrir(
                                                        {{ json_encode($participanteId) }},
                                                        {{ json_encode($nombreCliente) }},
                                                        {{ json_encode($nombreTanda) }},
                                                        {{ json_encode($cantidadCuotas) }},
                                                        {{ json_encode($valorUnitarioCuota) }}
                                                    )
                                                "
                                                title="Registrar Pago de Cuotas"
                                                class="
                                                    boton-accion
                                                    inline-flex
                                                    items-center
                                                    gap-1
                                                    px-1.5 sm:px-2.5
                                                    py-1
                                                    bg-emerald-50
                                                    hover:bg-emerald-600
                                                    text-emerald-700
                                                    hover:text-white
                                                    border
                                                    border-emerald-200
                                                    rounded-md
                                                    text-[10px]
                                                    sm:text-xs
                                                    font-bold
                                                    transition-all
                                                    shadow-xs
                                                "
                                            >

                                                <svg
                                                    class="w-3 h-3"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />

                                                </svg>

                                                <span class="btn-texto">
                                                    Pagar
                                                </span>

                                            </button>


                                            <!-- ================================= -->
                                            <!-- VER DETALLE -->
                                            <!-- ================================= -->

                                            <a
                                                href="{{ route('tandas.participante.cuotas', ['tanda' => $tandaId, 'participante' => $participanteId]) }}?origen=cobranza"
                                                title="Ir al detalle del participante"
                                                class="
                                                    boton-accion
                                                    inline-flex
                                                    items-center
                                                    gap-1
                                                    px-1.5 sm:px-2.5
                                                    py-1
                                                    bg-gray-50
                                                    hover:bg-indigo-600
                                                    text-gray-700
                                                    hover:text-white
                                                    border
                                                    border-gray-200
                                                    hover:border-indigo-600
                                                    rounded-md
                                                    text-[10px]
                                                    sm:text-xs
                                                    font-bold
                                                    transition-all
                                                    shadow-xs
                                                "
                                            >

                                                <svg
                                                    class="w-3 h-3"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    />

                                                </svg>

                                                <span class="btn-texto hidden sm:inline">
                                                    Ver detalle
                                                </span>

                                            </a>

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="
                                            px-4
                                            py-4
                                            text-center
                                            text-gray-500
                                            text-xs
                                            sm:text-sm
                                        "
                                    >
                                        ¡Excelente! No hay cuotas vencidas ni próximas para esta semana.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- MODAL DE PAGO -->
        <!-- ========================================================= -->

        <div
            x-show="modalOpen"
            class="
                fixed
                inset-0
                z-50
                flex
                items-center
                justify-center
                bg-black
                bg-opacity-50
            "
            style="display: none;"
            x-cloak
        >

            <div
                class="
                    bg-white
                    rounded-lg
                    shadow-xl
                    max-w-md
                    w-full
                    p-5 sm:p-6
                    mx-4
                "
                @click.stop
            >


                <!-- ================================================ -->
                <!-- ENCABEZADO MODAL -->
                <!-- ================================================ -->

                <div
                    class="
                        flex
                        justify-between
                        items-center
                        border-b
                        pb-2.5
                        mb-3.5
                    "
                >

                    <h3
                        class="
                            text-base
                            sm:text-lg
                            font-bold
                            text-gray-900
                        "
                    >
                        Registrar Pago por Cuotas
                    </h3>


                    <button
                        type="button"
                        @click="modalOpen = false"
                        class="
                            text-gray-400
                            hover:text-gray-600
                            font-bold
                            text-xl
                        "
                    >
                        &times;
                    </button>

                </div>


                <!-- ================================================ -->
                <!-- FORMULARIO -->
                <!-- ================================================ -->

                <form
                    method="POST"
                    action="{{ route('tandas.pagar.lote') }}"
                >

                    @csrf


                    <input
                        type="hidden"
                        name="tanda_participante_id"
                        x-model="participanteId"
                    >


                    <!-- ============================================= -->
                    <!-- CLIENTE -->
                    <!-- ============================================= -->

                    <div class="mb-3.5">

                        <p
                            class="
                                text-[11px]
                                sm:text-xs
                                text-gray-500
                                uppercase
                                font-semibold
                            "
                        >
                            Cliente:
                        </p>

                        <p
                            class="
                                text-sm
                                sm:text-base
                                font-bold
                                text-gray-900
                            "
                            x-text="clienteNombre"
                        ></p>

                        <p
                            class="
                                text-xs
                                text-indigo-600
                                font-semibold
                            "
                            x-text="tandaNombre"
                        ></p>

                    </div>


                    <!-- ============================================= -->
                    <!-- CANTIDAD DE CUOTAS -->
                    <!-- ============================================= -->

                    <div class="mb-3.5">

                        <label
                            class="
                                block
                                text-[11px]
                                sm:text-xs
                                font-bold
                                uppercase
                                text-gray-700
                                mb-1
                            "
                        >
                            ¿Cuántas cuotas va a pagar?
                        </label>


                        <select
                            name="cantidad_cuotas"
                            x-model.number="cantidadCuotasAPagar"
                            class="
                                w-full
                                border-gray-300
                                rounded-lg
                                shadow-sm
                                focus:border-indigo-500
                                focus:ring-indigo-500
                                text-sm
                                sm:text-base
                                font-semibold
                            "
                        >

                            <template
                                x-for="i in cuotasPendientesCount"
                                :key="i"
                            >

                                <option
                                    :value="i"
                                    x-text="
                                        i +
                                        (i === 1
                                            ? ' cuota'
                                            : ' cuotas'
                                        ) +
                                        ' — $' +
                                        (
                                            i * valorCuota
                                        ).toLocaleString(
                                            'en-US',
                                            {
                                                minimumFractionDigits: 2
                                            }
                                        )
                                    "
                                ></option>

                            </template>

                        </select>

                    </div>


                    <!-- ============================================= -->
                    <!-- RESUMEN -->
                    <!-- ============================================= -->

                    <div
                        class="
                            bg-gray-50
                            p-3.5
                            rounded-lg
                            mb-3.5
                            border
                            border-gray-200
                        "
                    >

                        <div
                            class="
                                flex
                                justify-between
                                items-center
                                mb-1
                            "
                        >

                            <span class="text-xs text-gray-600">
                                Valor por cuota:
                            </span>

                            <span
                                class="
                                    text-xs
                                    sm:text-sm
                                    font-bold
                                    text-gray-800
                                "
                                x-text="
                                    '$' +
                                    Number(valorUnitarioCuota)
                                        .toLocaleString(
                                            'en-US',
                                            {
                                                minimumFractionDigits: 2
                                            }
                                        )
                                "
                            ></span>

                        </div>


                        <div
                            class="
                                flex
                                justify-between
                                items-center
                                border-t
                                pt-2
                                mt-2
                            "
                        >

                            <span
                                class="
                                    text-xs
                                    sm:text-sm
                                    font-bold
                                    text-gray-700
                                "
                            >
                                Total a Pagar:
                            </span>

                            <span
                                class="
                                    text-lg
                                    sm:text-xl
                                    font-black
                                    text-emerald-600
                                "
                                x-text="
                                    '$' +
                                    Number(totalAPagar)
                                        .toLocaleString(
                                            'en-US',
                                            {
                                                minimumFractionDigits: 2
                                            }
                                        )
                                "
                            ></span>

                        </div>

                    </div>


                    <!-- ============================================= -->
                    <!-- BOTONES MODAL -->
                    <!-- ============================================= -->

                    <div
                        class="
                            flex
                            justify-end
                            gap-2.5
                            mt-5
                        "
                    >

                        <button
                            type="button"
                            @click="modalOpen = false"
                            class="
                                px-3.5
                                py-1.5
                                sm:px-4
                                sm:py-2
                                bg-gray-200
                                text-gray-700
                                rounded-lg
                                text-xs
                                sm:text-sm
                                font-semibold
                                hover:bg-gray-300
                                transition
                            "
                        >
                            Cancelar
                        </button>


                        <button
                            type="submit"
                            class="
                                px-3.5
                                py-1.5
                                sm:px-4
                                sm:py-2
                                bg-indigo-600
                                text-white
                                rounded-lg
                                text-xs
                                sm:text-sm
                                font-semibold
                                hover:bg-indigo-700
                                transition
                                shadow
                            "
                        >
                            Confirmar Pago
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>