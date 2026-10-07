<x-app-layout>
<x-slot name="header">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-hidden">

        <h2
            class="font-semibold
                   text-sm sm:text-lg md:text-xl
                   text-gray-800 dark:text-gray-200
                   leading-tight
                   text-left
                   truncate">

            Flujo de Ventas y Cobranza

        </h2>

    </div>

</x-slot>


<div class="flujo-responsive space-y-4">

    @php

        $totalVendidoAnual = $reporte->sum('total_vendido');

        $totalCobradoAnual = $reporte->sum('total_cobrado');

        $balanceAnual =
            $totalCobradoAnual - $totalVendidoAnual;

        $eficienciaAnual =
            $totalVendidoAnual > 0
                ? ($totalCobradoAnual / $totalVendidoAnual) * 100
                : ($totalCobradoAnual > 0 ? 100 : 0);

    @endphp


    <!-- ===================================================== -->
    <!-- TARJETAS RESUMEN -->
    <!-- ===================================================== -->

    <div
        class="tarjetas-flujo
               grid
               grid-cols-1
               sm:grid-cols-2
               lg:grid-cols-3
               xl:grid-cols-5
               gap-3">


        <!-- ================================================= -->
        <!-- 1. TOTAL VENDIDO -->
        <!-- ================================================= -->

        <div
            class="tarjeta-flujo
                   bg-white dark:bg-gray-800
                   px-3.5 py-2.5
                   rounded-xl
                   shadow-xs
                   border border-gray-200 dark:border-gray-700
                   flex items-center justify-between
                   min-w-0">

            <div class="min-w-0">

                <p
                    class="titulo-tarjeta
                           text-xs
                           font-extrabold
                           text-gray-500 dark:text-gray-400
                           uppercase
                           tracking-wider
                           truncate">

                    <span class="titulo-tarjeta-completo">
                        Total Vendido / Prestado
                    </span>

                    <span class="titulo-tarjeta-corto">
                        Vendido
                    </span>

                </p>

                <p
                    class="valor-tarjeta
                           text-lg
                           font-black
                           text-indigo-600 dark:text-indigo-400
                           mt-1
                           leading-none
                           whitespace-nowrap">

                    ${{ number_format($totalVendidoAnual, 0) }}

                </p>

            </div>

            <div
                class="icono-tarjeta
                       p-2
                       bg-indigo-50 dark:bg-indigo-900/30
                       text-indigo-600 dark:text-indigo-400
                       rounded-lg
                       text-sm
                       shrink-0">

                🛍️

            </div>

        </div>


        <!-- ================================================= -->
        <!-- 2. TOTAL COBRADO -->
        <!-- ================================================= -->

        <div
            class="tarjeta-flujo
                   bg-white dark:bg-gray-800
                   px-3.5 py-2.5
                   rounded-xl
                   shadow-xs
                   border border-gray-200 dark:border-gray-700
                   flex items-center justify-between
                   min-w-0">

            <div class="min-w-0">

                <p
                    class="titulo-tarjeta
                           text-xs
                           font-extrabold
                           text-gray-500 dark:text-gray-400
                           uppercase
                           tracking-wider
                           truncate">

                    <span class="titulo-tarjeta-completo">
                        Total Cobrado (Real)
                    </span>

                    <span class="titulo-tarjeta-corto">
                        Cobrado
                    </span>

                </p>

                <p
                    class="valor-tarjeta
                           text-lg
                           font-black
                           text-emerald-600 dark:text-emerald-400
                           mt-1
                           leading-none
                           whitespace-nowrap">

                    ${{ number_format($totalCobradoAnual, 0) }}

                </p>

            </div>

            <div
                class="icono-tarjeta
                       p-2
                       bg-emerald-50 dark:bg-emerald-900/30
                       text-emerald-600 dark:text-emerald-400
                       rounded-lg
                       text-sm
                       shrink-0">

                💵

            </div>

        </div>


        <!-- ================================================= -->
        <!-- 3. BALANCE NETO -->
        <!-- ================================================= -->

        <div
            class="tarjeta-flujo
                   bg-white dark:bg-gray-800
                   px-3.5 py-2.5
                   rounded-xl
                   shadow-xs
                   border border-gray-200 dark:border-gray-700
                   flex items-center justify-between
                   min-w-0">

            <div class="min-w-0">

                <p
                    class="titulo-tarjeta
                           text-xs
                           font-extrabold
                           text-gray-500 dark:text-gray-400
                           uppercase
                           tracking-wider
                           truncate">

                    <span class="titulo-tarjeta-completo">
                        Balance Neto Anual
                    </span>

                    <span class="titulo-tarjeta-corto">
                        Balance
                    </span>

                </p>

                <p
                    class="valor-tarjeta
                           text-lg
                           font-black
                           {{ $balanceAnual >= 0
                                ? 'text-blue-600 dark:text-blue-400'
                                : 'text-amber-600 dark:text-amber-400' }}
                           mt-1
                           leading-none
                           whitespace-nowrap">

                    {{ $balanceAnual < 0 ? '-' : '' }}${{
                        number_format(abs($balanceAnual), 0)
                    }}

                </p>

            </div>

            <div
                class="icono-tarjeta
                       p-2
                       {{ $balanceAnual >= 0
                            ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
                            : 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' }}
                       rounded-lg
                       text-sm
                       shrink-0">

                ⚖️

            </div>

        </div>


        <!-- ================================================= -->
        <!-- 4. EFICIENCIA -->
        <!-- ================================================= -->

        <div
            class="tarjeta-flujo
                   bg-white dark:bg-gray-800
                   px-3.5 py-2.5
                   rounded-xl
                   shadow-xs
                   border border-gray-200 dark:border-gray-700
                   flex items-center justify-between
                   min-w-0">

            <div class="min-w-0">

                <p
                    class="titulo-tarjeta
                           text-xs
                           font-extrabold
                           text-gray-500 dark:text-gray-400
                           uppercase
                           tracking-wider
                           truncate">

                    <span class="titulo-tarjeta-completo">
                        Eficiencia de Cobranza
                    </span>

                    <span class="titulo-tarjeta-corto">
                        Eficiencia
                    </span>

                </p>

                <p
                    class="valor-tarjeta
                           text-lg
                           font-black
                           text-purple-600 dark:text-purple-400
                           mt-1
                           leading-none
                           whitespace-nowrap">

                    {{ number_format($eficienciaAnual, 0) }}%

                </p>

            </div>

            <div
                class="icono-tarjeta
                       p-2
                       bg-purple-50 dark:bg-purple-900/30
                       text-purple-600 dark:text-purple-400
                       rounded-lg
                       text-sm
                       shrink-0">

                🎯

            </div>

        </div>


        <!-- ================================================= -->
        <!-- 5. ESTADO + AÑO -->
        <!-- ================================================= -->

        <div
            class="tarjeta-flujo
                   tarjeta-estado
                   bg-white dark:bg-gray-800
                   px-3.5 py-2.5
                   rounded-xl
                   shadow-xs
                   border border-gray-200 dark:border-gray-700
                   flex items-center justify-between
                   min-w-0">

            <div class="flex items-center min-w-0">

                @if($eficienciaAnual >= 80)

                    <span
                        class="estado-badge
                               text-xs
                               font-bold
                               bg-emerald-50 dark:bg-emerald-900/40
                               text-emerald-600 dark:text-emerald-400
                               px-2.5 py-1
                               rounded-full
                               border border-emerald-200 dark:border-emerald-800
                               whitespace-nowrap">

                        <span class="estado-completo">
                            ✅ Saludable
                        </span>

                        <span class="estado-corto">
                            ✅ Salud.
                        </span>

                    </span>

                @elseif($eficienciaAnual >= 50)

                    <span
                        class="estado-badge
                               text-xs
                               font-bold
                               bg-blue-50 dark:bg-blue-900/40
                               text-blue-600 dark:text-blue-400
                               px-2.5 py-1
                               rounded-full
                               border border-blue-200 dark:border-blue-800
                               whitespace-nowrap">

                        <span class="estado-completo">
                            ℹ️ Estable
                        </span>

                        <span class="estado-corto">
                            ℹ️ Estable
                        </span>

                    </span>

                @else

                    <span
                        class="estado-badge
                               text-xs
                               font-bold
                               bg-amber-50 dark:bg-amber-900/40
                               text-amber-600 dark:text-amber-400
                               px-2.5 py-1
                               rounded-full
                               border border-amber-200 dark:border-amber-800
                               whitespace-nowrap">

                        <span class="estado-completo">
                            ⚠️ Atención a Cartera
                        </span>

                        <span class="estado-corto">
                            ⚠️ Atención
                        </span>

                    </span>

                @endif

            </div>


            <form
                method="GET"
                action="{{ route('reports.reporte-mensual') }}"
                class="form-anio flex items-center gap-2 shrink-0">

                <label
                    class="label-anio text-xs font-bold text-gray-500 dark:text-gray-400">

                    Año:

                </label>

                <select
                    name="year"
                    onchange="this.form.submit()"
                    class="select-anio
                           w-20
                           text-sm
                           font-bold
                           rounded-lg
                           border-gray-300 dark:border-gray-600
                           bg-gray-50 dark:bg-gray-700
                           text-gray-800 dark:text-white
                           shadow-xs
                           focus:ring-indigo-500
                           focus:border-indigo-500
                           py-1.5 px-2.5">

                    @foreach($aniosDisponibles as $anio)

                        <option
                            value="{{ $anio }}"
                            {{ $year == $anio ? 'selected' : '' }}>

                            {{ $anio }}

                        </option>

                    @endforeach

                </select>

            </form>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- GRÁFICA -->
    <!-- ===================================================== -->

    <div
        class="grafica-flujo
               bg-white dark:bg-gray-800
               p-4
               rounded-xl
               shadow-xs
               border border-gray-200 dark:border-gray-700">

        <div
            class="flex justify-between items-center gap-2 mb-2">

            <h4
                class="text-[11px]
                       font-bold
                       text-gray-400
                       uppercase
                       tracking-wider
                       truncate">

                Comportamiento Anual

            </h4>

            <span
                class="shrink-0
                       text-[10px]
                       font-semibold
                       bg-gray-100 dark:bg-gray-700
                       text-gray-600 dark:text-gray-300
                       px-2 py-0.5
                       rounded-md">

                Flujo de Caja

            </span>

        </div>

        <div
            class="relative h-52 w-full">

            <canvas id="graficaFlujoCaja"></canvas>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- TABLA -->
    <!-- ===================================================== -->

    <div
        class="tabla-flujo-responsive
               bg-white dark:bg-gray-800
               rounded-xl
               shadow-xs
               border border-gray-200 dark:border-gray-700
               overflow-hidden
               flex flex-col">


        <!-- ================================================= -->
        <!-- CABECERA TABLA -->
        <!-- ================================================= -->

        <div
            class="cabecera-tabla-flujo
                   px-4 py-3
                   border-b border-gray-100 dark:border-gray-700
                   flex flex-row
                   justify-between
                   items-center
                   gap-2
                   overflow-x-auto
                   overflow-y-hidden">

            <h4
                class="titulo-tabla-flujo
                       text-[11px]
                       font-bold
                       text-gray-500 dark:text-gray-400
                       uppercase
                       tracking-wider
                       whitespace-nowrap">

                Flujo de Ventas y Cobranza Mensual

            </h4>


            <!-- BOTONES -->

            <div
                class="botones-tabla-flujo
                       flex items-center gap-2
                       shrink-0">

                <button
                    onclick="window.print()"
                    class="text-[11px]
                           font-bold
                           bg-gray-50 hover:bg-gray-100
                           dark:bg-gray-700 dark:hover:bg-gray-600
                           text-gray-700 dark:text-gray-200
                           px-3 py-1.5
                           rounded-lg
                           border border-gray-200 dark:border-gray-600
                           transition
                           flex items-center gap-1.5
                           cursor-pointer
                           whitespace-nowrap">

                    <span>🖨️</span>

                    <span class="texto-boton">
                        Imprimir / PDF
                    </span>

                </button>


                <button
                    onclick="exportarTablaExcel()"
                    class="text-[11px]
                           font-bold
                           bg-emerald-50 hover:bg-emerald-100
                           dark:bg-emerald-900/40 dark:hover:bg-emerald-900/60
                           text-emerald-700 dark:text-emerald-300
                           px-3 py-1.5
                           rounded-lg
                           border border-emerald-200 dark:border-emerald-800
                           transition
                           flex items-center gap-1.5
                           cursor-pointer
                           whitespace-nowrap">

                    <span>📊</span>

                    <span class="texto-boton">
                        Exportar CSV
                    </span>

                </button>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- CONTENEDOR TABLA -->
        <!-- ================================================= -->

        <div
            class="contenedor-tabla-flujo
                   max-h-72
                   overflow-y-auto
                   overflow-x-hidden
                   w-full">

            <table
                id="tablaReporteFlujo"
                class="w-full text-left border-collapse table-auto">


                <!-- ================================================= -->
                <!-- THEAD -->
                <!-- ================================================= -->

                <thead
                    class="sticky top-0 z-10 shadow-xs">

                    <tr
                        class="bg-gray-50 dark:bg-gray-700
                               text-[11px]
                               font-bold
                               text-gray-500 dark:text-gray-300
                               border-b border-gray-200 dark:border-gray-700">


                        <!-- MES -->

                        <th
                            class="col-mes
                                   py-2.5 px-4
                                   whitespace-nowrap">

                            Mes

                        </th>


                        <!-- VENDIDO -->

                        <th
                            class="col-vendido
                                   py-2.5 px-4
                                   text-right
                                   whitespace-nowrap">

                            <span class="titulo-completo">
                                Total Vendido / Prestado
                            </span>

                            <span class="titulo-corto">
                                Vendido
                            </span>

                        </th>


                        <!-- COBRADO -->

                        <th
                            class="col-cobrado
                                   py-2.5 px-4
                                   text-right
                                   whitespace-nowrap">

                            <span class="titulo-completo">
                                Dinero Cobrado (Real)
                            </span>

                            <span class="titulo-corto">
                                Cobrado
                            </span>

                        </th>


                        <!-- BALANCE -->

                        <th
                            class="col-balance
                                   py-2.5 px-4
                                   text-right
                                   whitespace-nowrap">

                            <span class="titulo-completo">
                                Diferencia / Balance
                            </span>

                            <span class="titulo-corto">
                                Balance
                            </span>

                        </th>


                        <!-- EFICIENCIA -->

                        <th
                            class="col-eficiencia
                                   py-2.5 px-4
                                   text-right
                                   whitespace-nowrap">

                            Eficiencia

                        </th>

                    </tr>

                </thead>


                <!-- ================================================= -->
                <!-- TBODY -->
                <!-- ================================================= -->

                <tbody
                    class="text-xs
                           divide-y divide-gray-100
                           dark:divide-gray-700/50">

                    @forelse($reporte as $fila)

                        @php

                            $diferencia =
                                $fila['total_cobrado']
                                - $fila['total_vendido'];

                            $eficienciaMes =
                                $fila['total_vendido'] > 0
                                    ? ($fila['total_cobrado']
                                        / $fila['total_vendido']) * 100
                                    : ($fila['total_cobrado'] > 0 ? 100 : 0);

                        @endphp


                        <tr
                            class="hover:bg-gray-50/50
                                   dark:hover:bg-gray-700/20
                                   transition">


                            <!-- MES -->

                            <td
                                class="col-mes
                                       py-3 px-4
                                       font-bold
                                       text-gray-900 dark:text-white
                                       capitalize
                                       whitespace-nowrap">

                                {{ $fila['mes_nombre'] }}

                            </td>


                            <!-- VENDIDO -->

                            <td
                                class="col-vendido
                                       py-3 px-4
                                       text-right
                                       font-medium
                                       text-gray-700 dark:text-gray-300
                                       whitespace-nowrap">

                                ${{ number_format($fila['total_vendido'], 2) }}

                            </td>


                            <!-- COBRADO -->

                            <td
                                class="col-cobrado
                                       py-3 px-4
                                       text-right
                                       font-black
                                       text-emerald-600 dark:text-emerald-400
                                       whitespace-nowrap">

                                ${{ number_format($fila['total_cobrado'], 2) }}

                            </td>


                            <!-- BALANCE -->

                            <td
                                class="col-balance
                                       py-3 px-4
                                       text-right
                                       font-bold
                                       {{ $diferencia >= 0
                                            ? 'text-blue-600 dark:text-blue-400'
                                            : 'text-amber-600 dark:text-amber-400' }}
                                       whitespace-nowrap">

                                {{ $diferencia < 0 ? '-' : '' }}${{
                                    number_format(abs($diferencia), 2)
                                }}

                            </td>


                            <!-- EFICIENCIA -->

                            <td
                                class="col-eficiencia
                                       py-3 px-4
                                       text-right
                                       font-black
                                       text-purple-600 dark:text-purple-400
                                       whitespace-nowrap">

                                {{ number_format($eficienciaMes, 1) }}%

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-8 text-gray-400">

                                <div class="text-2xl mb-1">
                                    📭
                                </div>

                                <p class="font-medium">
                                    No hay registros financieros para este año.
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
<!-- CHART.JS -->
<!-- ========================================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

    document.addEventListener("DOMContentLoaded", function () {

        const canvas =
            document.getElementById('graficaFlujoCaja');

        if (!canvas) return;


        const datosReporte =
            @json($reporte->values());


        const labels =
            datosReporte.map(
                item => item.mes_nombre
            );


        const ventasData =
            datosReporte.map(
                item => item.total_vendido
            );


        const cobrosData =
            datosReporte.map(
                item => item.total_cobrado
            );


        new Chart(
            canvas.getContext('2d'),
            {

                type: 'bar',

                data: {

                    labels: labels,

                    datasets: [

                        {

                            label: 'Vendido / Prestado',

                            data: ventasData,

                            backgroundColor:
                                'rgba(99, 102, 241, 0.85)',

                            borderWidth: 0,

                            borderRadius: 4,

                            barPercentage: 0.5,

                            categoryPercentage: 0.6

                        },

                        {

                            label: 'Cobrado (Dinero Real)',

                            data: cobrosData,

                            backgroundColor:
                                'rgba(16, 185, 129, 0.85)',

                            borderWidth: 0,

                            borderRadius: 4,

                            barPercentage: 0.5,

                            categoryPercentage: 0.6

                        }

                    ]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {

                            position: 'bottom',

                            labels: {

                                boxWidth: 10,

                                boxHeight: 10,

                                borderRadius: 3,

                                font: {

                                    family:
                                        'Figtree, sans-serif',

                                    size: 10,

                                    weight: 'bold'

                                },

                                color:
                                    document.documentElement
                                        .classList
                                        .contains('dark')
                                        ? '#9ca3af'
                                        : '#4b5563'

                            }

                        },


                        tooltip: {

                            backgroundColor:
                                'rgba(17, 24, 39, 0.9)',

                            titleFont: {
                                size: 11,
                                weight: 'bold'
                            },

                            bodyFont: {
                                size: 10
                            },

                            padding: 8,

                            cornerRadius: 6,

                            callbacks: {

                                label: function (context) {

                                    let label =
                                        context.dataset.label || '';

                                    if (label)
                                        label += ': ';

                                    if (context.parsed.y !== null) {

                                        label +=
                                            new Intl.NumberFormat(
                                                'es-MX',
                                                {
                                                    style: 'currency',
                                                    currency: 'MXN'
                                                }
                                            ).format(
                                                context.parsed.y
                                            );

                                    }

                                    return label;

                                }

                            }

                        }

                    },


                    scales: {

                        y: {

                            beginAtZero: true,

                            grid: {

                                color:
                                    document.documentElement
                                        .classList
                                        .contains('dark')
                                        ? 'rgba(255, 255, 255, 0.05)'
                                        : 'rgba(0, 0, 0, 0.04)'

                            },

                            ticks: {

                                font: {
                                    size: 9
                                },

                                color:
                                    document.documentElement
                                        .classList
                                        .contains('dark')
                                        ? '#9ca3af'
                                        : '#6b7280',

                                callback: function (value) {

                                    return '$' +
                                        value.toLocaleString();

                                }

                            }

                        },


                        x: {

                            grid: {
                                display: false
                            },

                            ticks: {

                                font: {
                                    size: 10,
                                    weight: 'bold'
                                },

                                color:
                                    document.documentElement
                                        .classList
                                        .contains('dark')
                                        ? '#9ca3af'
                                        : '#4b5563'

                            }

                        }

                    }

                }

            }

        );

    });


    // =========================================================
    // EXPORTAR CSV
    // =========================================================

    function exportarTablaExcel() {

        let tabla =
            document.getElementById(
                "tablaReporteFlujo"
            );


        let csv = [];


        let filas =
            tabla.querySelectorAll("tr");


        for (
            let i = 0;
            i < filas.length;
            i++
        ) {

            let fila = [];


            let celdas =
                filas[i].querySelectorAll(
                    "th, td"
                );


            for (
                let j = 0;
                j < celdas.length;
                j++
            ) {

                let texto =
                    celdas[j]
                        .innerText
                        .replace(/[\n\r]+/g, "")
                        .trim();


                fila.push(
                    '"' + texto + '"'
                );

            }


            csv.push(
                fila.join(",")
            );

        }


        let blob =
            new Blob(
                ["\ufeff" + csv.join("\n")],
                {
                    type:
                        'text/csv;charset=utf-8;'
                }
            );


        let enlace =
            document.createElement("a");


        enlace.href =
            URL.createObjectURL(blob);


        enlace.download =
            "reporte_flujo_ventas_cobranza_{{ $year }}.csv";


        enlace.style.display =
            "none";


        document.body.appendChild(
            enlace
        );


        enlace.click();


        document.body.removeChild(
            enlace
        );

    }

</script>


<!-- ========================================================= -->
<!-- RESPONSIVE POR ANCHO REAL DEL CONTENEDOR -->
<!-- ========================================================= -->

<style>

    .flujo-responsive {
        container-type: inline-size;
    }


    .titulo-corto {
        display: none;
    }


    /* ===================================================== */
    /* TITULOS CORTOS DE TARJETAS */
    /* ===================================================== */

    .titulo-tarjeta-corto {
        display: none;
    }


    .estado-corto {
        display: none;
    }


    /* ===================================================== */
    /* CONTENEDOR MUY PEQUEÑO / MÓVIL */
    /* ===================================================== */

    @container (max-width: 767px) {

        /*
         * LAS 4 TARJETAS DE TOTALES
         * PERMANECEN EN LA PRIMERA FILA
         */

        .tarjetas-flujo {
            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 5px;
        }


        .tarjeta-flujo {
            padding: 7px 6px !important;
            border-radius: 9px;
        }


        .titulo-tarjeta {
            font-size: 8px !important;
            letter-spacing: 0.01em;
            text-align: center;
        }


        .titulo-tarjeta-completo {
            display: none;
        }


        .titulo-tarjeta-corto {
            display: inline;
        }


        .valor-tarjeta {
            font-size: 13px !important;
            margin-top: 3px !important;
            text-align: center;
        }


        .icono-tarjeta {
            display: none;
        }


        /*
         * =====================================================
         * SOLO EN MÓVIL:
         * ESTADO + AÑO PASAN A SEGUNDA FILA
         * OCUPANDO TODO EL ANCHO
         * =====================================================
         */

        .tarjeta-estado {
            grid-column: 1 / -1 !important;
        }


        .estado-badge {
            font-size: 8px !important;
            padding: 4px 5px !important;
        }


        .estado-completo {
            display: none;
        }


        .estado-corto {
            display: inline;
        }


        .form-anio {
            gap: 2px !important;
        }


        .label-anio {
            display: none;
        }


        .select-anio {
            width: 48px !important;
            font-size: 9px !important;
            padding: 3px 2px !important;
        }


        /*
         * GRÁFICA
         */

        .grafica-flujo {
            padding: 10px !important;
        }


        .grafica-flujo .relative {
            height: 190px !important;
        }


        /*
         * CABECERA TABLA
         */

        .cabecera-tabla-flujo {
            padding: 7px 8px !important;
        }


        .titulo-tabla-flujo {
            font-size: 9px !important;
        }


        .botones-tabla-flujo {
            gap: 4px !important;
        }


        .botones-tabla-flujo button {
            padding: 4px 6px !important;
            font-size: 9px !important;
        }


        .texto-boton {
            display: none;
        }


        /*
         * TABLA
         */

        .contenedor-tabla-flujo th,
        .contenedor-tabla-flujo td {
            padding: 7px 6px !important;
            font-size: 9px !important;
        }


        .col-balance {
            display: none !important;
        }


        .titulo-completo {
            display: none;
        }


        .titulo-corto {
            display: inline;
        }

    }


    /* ===================================================== */
    /* CONTENEDOR MEDIANO */
    /* ===================================================== */

    @container (min-width: 768px) and (max-width: 1049px) {

        /*
         * AQUÍ NO SE MODIFICA LA DISTRIBUCIÓN ACTUAL
         */

        .tarjetas-flujo {
            grid-template-columns:
                repeat(5, minmax(0, 1fr));

            gap: 7px;
        }


        .tarjeta-flujo {
            padding: 8px 7px !important;
        }


        .titulo-tarjeta {
            font-size: 9px !important;
            letter-spacing: 0.01em;
        }


        .titulo-tarjeta-completo {
            display: none;
        }


        .titulo-tarjeta-corto {
            display: inline;
        }


        .valor-tarjeta {
            font-size: 15px !important;
            margin-top: 3px !important;
        }


        .icono-tarjeta {
            padding: 5px !important;
            font-size: 10px !important;
        }


        /*
         * ESTADO + AÑO SE QUEDA COMO ESTABA
         */

        .tarjeta-estado {
            grid-column:
                auto !important;
        }


        .estado-badge {
            font-size: 8px !important;
            padding: 4px 5px !important;
        }


        .estado-completo {
            display: none;
        }


        .estado-corto {
            display: inline;
        }


        .label-anio {
            font-size: 8px !important;
        }


        .select-anio {
            width: 52px !important;
            font-size: 9px !important;
            padding: 3px 4px !important;
        }


        /*
         * TABLA
         */

        .contenedor-tabla-flujo th,
        .contenedor-tabla-flujo td {
            padding-left: 8px !important;
            padding-right: 8px !important;
            font-size: 10px !important;
        }


        .titulo-completo {
            display: none;
        }


        .titulo-corto {
            display: inline;
        }

    }


    /* ===================================================== */
    /* CONTENEDOR GRANDE */
    /* ===================================================== */

    @container (min-width: 1050px) {

        .titulo-completo {
            display: inline;
        }


        .titulo-corto {
            display: none;
        }


        /*
         * TITULOS COMPLETOS DE TARJETAS
         */

        .titulo-tarjeta-completo {
            display: inline;
        }


        .titulo-tarjeta-corto {
            display: none;
        }


        .estado-completo {
            display: inline;
        }


        .estado-corto {
            display: none;
        }

    }


    /* ===================================================== */
    /* IMPRESIÓN */
    /* ===================================================== */

    @media print {

        header,
        nav,
        aside,
        footer,
        button,
        form select {
            display: none !important;
        }


        .relative.h-52 {
            height: 220px !important;
            width: 100% !important;
            page-break-inside: avoid;
        }


        canvas {
            max-width: 100% !important;
            height: auto !important;
        }


        .grid {
            display: grid !important;
            grid-template-columns:
                repeat(4, minmax(0, 1fr)) !important;
            gap: 8px !important;
            page-break-inside: avoid;
        }


        div,
        table {
            page-break-inside: avoid;
        }


        body {
            background-color: white !important;
            color: black !important;
        }

    }

</style>

</x-app-layout>