<x-app-layout>

<style>


    @container (min-width: 700px) and (max-width: 799px) {

        .loan-detail-medium-scale {
            zoom: 0.72;
        }

        .loan-header-medium-scale {
            zoom: 0.72;
        }
    }


    @container (min-width: 800px) and (max-width: 899px) {

        .loan-detail-medium-scale {
            zoom: 0.76;
        }

        .loan-header-medium-scale {
            zoom: 0.76;
        }
    }


    @container (min-width: 900px) and (max-width: 999px) {

        .loan-detail-medium-scale {
            zoom: 0.82;
        }

        .loan-header-medium-scale {
            zoom: 0.82;
        }
    }


    @container (min-width: 1000px) and (max-width: 1099px) {

        .loan-detail-medium-scale {
            zoom: 0.88;
        }

        .loan-header-medium-scale {
            zoom: 0.88;
        }
    }


    /*
     * =========================================================
     * RESUMEN SUPERIOR
     *
     * NORMALMENTE DOS COLUMNAS.
     * =========================================================
     */

    .loan-summary {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }


    /*
     * =========================================================
     * PANTALLAS CHICAS / MÓVILES
     *
     * EL RESUMEN PASA A UNA SOLA COLUMNA.
     *
     * TODO LO DE LA COLUMNA IZQUIERDA APROVECHA
     * TODO EL ANCHO DISPONIBLE.
     *
     * LOS 4 CAMPOS DE LA PARTE DERECHA SE OCULTAN.
     * =========================================================
     */

    @media (max-width: 699px) {

        .loan-summary {
            grid-template-columns: 1fr !important;
        }

        .loan-mobile-hide-summary {
            display: none !important;
        }
    }


    @container (max-width: 699px) {

        .loan-summary {
            grid-template-columns: 1fr !important;
        }

        .loan-mobile-hide-summary {
            display: none !important;
        }
    }


    /*
     * =========================================================
     * DESDE 700 PX
     *
     * REGRESA A DOS COLUMNAS.
     * =========================================================
     */

    @media (min-width: 700px) {

        .loan-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }


    /*
     * =========================================================
     * TABLA ESTADO DE CUENTA
     *
     * < 700px:
     * OCULTAR DETALLE.
     *
     * 700-1099px:
     * JAVASCRIPT DECIDE SEGÚN SI EXISTE SCROLL HORIZONTAL.
     *
     * >= 1100px:
     * MOSTRAR SIEMPRE.
     * =========================================================
     */

    @container (max-width: 699px) {

        #estadoCuentaScroll .loan-detail-column {
            display: none !important;
        }
    }


    @container (min-width: 1100px) {

        #estadoCuentaScroll .loan-detail-column {
            display: table-cell !important;
        }
    }


    /*
     * JS AGREGA ESTAS CLASES SEGÚN EL DESBORDAMIENTO REAL
     */

    #estadoCuentaScroll.hide-detail-on-overflow {
        overflow-x: hidden !important;
    }

    #estadoCuentaScroll.hide-detail-on-overflow .loan-detail-column {
        display: none !important;
    }

    #estadoCuentaScroll.hide-detail-on-overflow table {
        min-width: 100% !important;
        width: 100% !important;
    }

    #estadoCuentaScroll.shrink-table-text table {
        font-size: 7px !important;
    }

    #estadoCuentaScroll.shrink-table-text th,
    #estadoCuentaScroll.shrink-table-text td {
        padding-left: 2px !important;
        padding-right: 2px !important;
    }


    /*
     * =========================================================
     * AJUSTE EXTRA PARA DOS COLUMNAS
     * =========================================================
     */

    @container (min-width: 1000px) and (max-width: 1099px) {

        .loan-two-column-medium {
            gap: 0.75rem !important;
        }

        .loan-two-column-medium > div {
            min-width: 0;
        }
    }

</style>


<x-slot name="header">

    <div class="flex justify-between items-center
                max-w-7xl mx-auto
                px-2 sm:px-4 lg:px-8
                @container
                loan-header-medium-scale">

        <h2 class="font-semibold text-sm sm:text-lg lg:text-xl
                   text-gray-800 dark:text-gray-200
                   leading-tight

                   @[700px]:text-sm
                   @[800px]:text-base
                   @[900px]:text-lg">

            Detalle del Préstamo:
            <span class="text-indigo-500">#{{ $loan->id }}</span>

        </h2>


        <a href="{{ request('from') === 'historial' ? route('reports.historial-cuentas') : route('clients.show', $loan->client_id) }}"
            class="inline-flex items-center
                   gap-1.5 sm:gap-2
                   px-2 sm:px-4
                   py-1 sm:py-2
                   text-[9px] sm:text-xs
                   font-bold
                   text-indigo-600 dark:text-indigo-400
                   bg-indigo-50 dark:bg-indigo-950/50
                   hover:bg-indigo-100 dark:hover:bg-indigo-900/50
                   border border-indigo-200 dark:border-indigo-800
                   rounded-lg sm:rounded-xl
                   shadow-xs
                   transition-all duration-200
                   group shrink-0

                   @[700px]:px-1.5
                   @[700px]:py-0.5
                   @[700px]:text-[8px]

                   @[800px]:px-2
                   @[800px]:py-1
                   @[800px]:text-[9px]

                   @[900px]:px-2.5
                   @[900px]:py-1
                   @[900px]:text-[10px]

                   @[1000px]:px-3
                   @[1000px]:py-1.5
                   @[1000px]:text-xs">

            <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">
                &larr;
            </span>

            <span>Volver</span>

        </a>

    </div>

</x-slot>


<div class="py-2 sm:py-4">

    <div class="max-w-7xl mx-auto
                px-2 sm:px-4 lg:px-8
                space-y-2 sm:space-y-4
                @container">


        <div class="loan-detail-medium-scale">


            @if (session('success'))

                <div class="font-medium
                            text-xs sm:text-sm
                            text-green-600 dark:text-green-400
                            bg-green-100 dark:bg-green-900/30
                            p-2 sm:p-3
                            rounded-xl

                            @[700px]:text-[9px]
                            @[700px]:p-1.5

                            @[800px]:text-[10px]
                            @[800px]:p-2

                            @[900px]:text-xs
                            @[900px]:p-2.5

                            @[1000px]:text-sm
                            @[1000px]:p-3">

                    {{ session('success') }}

                </div>

            @endif


@php

// ==========================================
// SEPARACIÓN DE LÓGICA SEGÚN `loan_modal`
// ==========================================

$modalidad = $loan->loan_modal ?? 'fixed_installments';

$esCuotaFija = ($modalidad === 'fixed_installments');


if ($esCuotaFija) {

    // --- MODALIDAD 1: CUOTAS FIJAS ---

    $prestadoMasIntereses = $loan->installments->sum('amount_due');

    if ($prestadoMasIntereses <= 0) {
        $prestadoMasIntereses = $loan->total_amount ?? 0;
    }

    $tasaPorcentaje = $loan->interest_rate ?? 0;

    $capitalTotal =
        $prestadoMasIntereses /
        (1 + ($tasaPorcentaje / 100));

    $interesTotal =
        $prestadoMasIntereses -
        $capitalTotal;

    $totalAbonado =
        $loan->installments
            ->where('status', 'paid')
            ->sum('amount_due');

    $saldoRestante =
        max(
            0,
            $prestadoMasIntereses - $totalAbonado
        );

    $totalCuotas =
        $loan->installments->count();

    $cuotasPagadasCount =
        $loan->installments
            ->where('status', 'paid')
            ->count();

    $esLiquidado =
        ($loan->status == 'paid') ||
        (
            $saldoRestante <= 0 &&
            $totalCuotas > 0 &&
            $loan->installments
                ->where('status', '!=', 'paid')
                ->count() == 0
        );

    $ultimoPago =
        $loan->payments()
            ->latest('payment_date')
            ->first();

    $fechaLiquidacion =
        $ultimoPago
            ? \Carbon\Carbon::parse(
                $ultimoPago->payment_date
              )->format('d/m/Y H:i')
            : now()->format('d/m/Y');


} else {

    // --- MODALIDAD 2: INTERÉS FIJO ---

    $capitalTotal =
        $loan->total_amount ??
        $loan->amount ??
        0;

    $tasaPorcentaje =
        $loan->interest_rate ??
        0;

    $interesTotal =
        $capitalTotal *
        ($tasaPorcentaje / 100);

    $totalAbonadoInteres =
        $loan->payments
            ->sum('interest_covered');

    $totalAbonadoCapital =
        $loan->payments
            ->sum('capital_covered');

    $prestadoMasIntereses =
        $capitalTotal +
        $totalAbonadoInteres;

    $totalAbonado =
        $totalAbonadoCapital;

    $saldoRestante =
        max(
            0,
            $capitalTotal - $totalAbonado
        );

    $totalCuotas =
        $loan->installments->count();

    $cuotasPagadasCount =
        $loan->installments
            ->where('status', 'paid')
            ->count();

    $esLiquidado =
        ($loan->status == 'paid') ||
        ($saldoRestante <= 0);

    $ultimoPago =
        $loan->payments()
            ->latest('payment_date')
            ->first();

    $fechaLiquidacion =
        $ultimoPago
            ? \Carbon\Carbon::parse(
                $ultimoPago->payment_date
              )->format('d/m/Y H:i')
            : now()->format('d/m/Y');
}


// ==========================================
// ALIAS DE RESPALDO
// ==========================================

$tasaFijaPorcentaje =
    $tasaPorcentaje;

$interesFijoTotal =
    $interesTotal;

$capitalFijo =
    $capitalTotal;

$prestadoMasInteresesFijo =
    $prestadoMasIntereses;

$totalAbonadoFijo =
    $totalAbonado;

$saldoRestanteFijo =
    $saldoRestante;


// ==========================================
// TIPO DE CRÉDITO
// ==========================================

$nombreTipoCredito =
    $esCuotaFija
        ? 'Cuotas Fijas'
        : 'Interés Fijo';


// ==========================================
// FRECUENCIA
// ==========================================

$frecuenciaRaw =
    $loan->frequency ??
    $loan->payment_frequency ??
    '';

$frecuenciaTexto = '';


switch (strtolower($frecuenciaRaw)) {

    case 'weekly':
    case 'semanal':

        $frecuenciaTexto = 'Semanal';

        break;


    case 'biweekly':
    case 'quincenal':

        $frecuenciaTexto = 'Quincenal';

        break;


    case 'monthly':
    case 'mensual':

        $frecuenciaTexto = 'Mensual';

        break;


    default:

        $frecuenciaTexto =
            ucfirst($frecuenciaRaw);

        break;
}


$tipoCreditoTexto =
    $nombreTipoCredito;


if (!empty($frecuenciaTexto)) {

    $tipoCreditoTexto .=
        ' - ' .
        $frecuenciaTexto;
}

@endphp


        <!-- =========================================================
             TARJETA PRINCIPAL
        ========================================================== -->

        <div class="bg-white dark:bg-gray-800
                    shadow-sm
                    rounded-xl sm:rounded-2xl
                    p-2 sm:p-5 lg:p-6
                    border border-gray-100 dark:border-gray-700
                    text-gray-800 dark:text-gray-200

                    @[700px]:p-2
                    @[700px]:rounded-lg

                    @[800px]:p-2.5

                    @[900px]:p-3
                    @[900px]:rounded-xl

                    @[1000px]:p-4">


            <!-- =====================================================
                 ENCABEZADO CLIENTE / CONCEPTO / ACCIONES
            ====================================================== -->

            <div class="flex flex-row
                        justify-between
                        items-center
                        pb-2 sm:pb-4
                        border-b dark:border-gray-700
                        gap-1 sm:gap-3
                        mb-2 sm:mb-4
                        min-w-0

                        @[700px]:pb-1.5
                        @[700px]:gap-1
                        @[700px]:mb-2

                        @[800px]:pb-2
                        @[800px]:gap-1.5
                        @[800px]:mb-2.5

                        @[900px]:pb-2
                        @[900px]:gap-2
                        @[900px]:mb-3">


                <!-- CLIENTE -->

                <div class="min-w-0
                            flex-1
                            overflow-hidden">

                    <span class="hidden sm:block
                                 text-xs
                                 text-gray-400
                                 uppercase
                                 font-bold
                                 tracking-wider
                                 @[700px]:text-[8px]
                                 @[800px]:text-[9px]
                                 @[900px]:text-[10px]">

                        Cliente

                    </span>


                    <h3 class="text-[10px] sm:text-lg
                               font-bold
                               text-gray-900 dark:text-white
                               truncate
                               leading-tight
                               max-w-full
                               @[700px]:text-xs
                               @[800px]:text-[13px]
                               @[900px]:text-sm
                               @[1000px]:text-base">

                        {{ $loan->client->name ?? 'Cliente General' }}

                    </h3>

                </div>


                <!-- CONCEPTO -->

                <div class="hidden sm:block
                            text-left sm:text-right
                            min-w-0
                            max-w-[42%]">

                    <span class="text-xs
                                 text-gray-400
                                 uppercase
                                 font-bold
                                 tracking-wider
                                 @[700px]:text-[8px]
                                 @[800px]:text-[9px]
                                 @[900px]:text-[10px]">

                        Concepto

                    </span>


                    <p class="text-sm
                              font-semibold
                              text-gray-700 dark:text-gray-300
                              truncate
                              @[700px]:text-[9px]
                              @[800px]:text-[10px]
                              @[900px]:text-xs">

                        {{ $loan->concept }}

                    </p>

                </div>


                <!-- BOTONES -->

                <div class="flex items-center
                            gap-1 sm:gap-2
                            shrink-0
                            @[700px]:gap-1
                            @[800px]:gap-1.5
                            @[900px]:gap-2">


                    <a href="{{ route('debts.pdf', $loan->id) }}"
                        class="inline-flex items-center justify-center
                               px-1.5 sm:px-3
                               py-1 sm:py-1.5
                               bg-gray-100 dark:bg-gray-700
                               hover:bg-gray-200 dark:hover:bg-gray-600
                               text-gray-700 dark:text-gray-200
                               rounded-md sm:rounded-lg
                               text-[8px] sm:text-xs
                               font-semibold
                               transition
                               print:hidden
                               shadow-sm
                               whitespace-nowrap
                               shrink-0
                               @[700px]:px-1
                               @[700px]:py-0.5
                               @[700px]:text-[7px]
                               @[800px]:px-1.5
                               @[800px]:text-[8px]
                               @[900px]:px-2
                               @[900px]:py-1
                               @[900px]:text-[9px]
                               @[1000px]:text-[10px]">

                        <span class="sm:hidden">
                            PDF
                        </span>

                        <span class="hidden sm:inline">
                            Exportar PDF
                        </span>

                    </a>


                    @unless($esLiquidado)

                        <form action="{{ route('debts.destroy', $loan->id) }}"
                              method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar este préstamo?');"
                              class="shrink-0">

                            @csrf
                            @method('DELETE')


                            <button type="submit"
                                class="px-1.5 sm:px-3
                                       py-1 sm:py-1.5
                                       bg-red-50 hover:bg-red-100
                                       text-red-600
                                       dark:bg-red-950/50
                                       dark:hover:bg-red-900/80
                                       dark:text-red-300
                                       rounded-md sm:rounded-lg
                                       text-[8px] sm:text-xs
                                       font-bold
                                       transition
                                       flex items-center justify-center
                                       gap-0.5 sm:gap-1.5
                                       shadow-sm
                                       whitespace-nowrap
                                       @[700px]:px-1
                                       @[700px]:py-0.5
                                       @[700px]:text-[7px]
                                       @[700px]:gap-0.5
                                       @[800px]:px-1.5
                                       @[800px]:text-[8px]
                                       @[900px]:px-2
                                       @[900px]:py-1
                                       @[900px]:text-[9px]
                                       @[1000px]:text-[10px]">

                                <span>
                                    🗑️
                                </span>

                                <span>
                                    Eliminar
                                </span>

                            </button>

                        </form>

                    @endunless

                </div>

            </div>


            <!-- =====================================================
                 RESUMEN DETALLADO

                 EN ESCRITORIO: 2 COLUMNAS
                 EN MÓVIL: 1 COLUMNA
            ====================================================== -->

            <div class="loan-summary
                        grid grid-cols-2
                        gap-x-3 sm:gap-x-6
                        gap-y-0.5
                        mb-4
                        bg-gray-50/50 dark:bg-gray-900/20
                        px-2 sm:px-3.5
                        py-1.5 sm:py-2
                        rounded-xl
                        border border-gray-100 dark:border-gray-700/50

                        @[700px]:gap-x-2
                        @[700px]:mb-2
                        @[700px]:px-1.5
                        @[700px]:py-1

                        @[800px]:gap-x-2.5
                        @[800px]:px-2
                        @[800px]:py-1.5

                        @[900px]:gap-x-3
                        @[900px]:mb-3

                        @[1000px]:gap-x-5
                        @[1000px]:px-3
                        @[1000px]:py-2">


                <!-- COLUMNA 1 -->

                <div class="space-y-0.5">

                    <div class="flex justify-between items-center
                                py-0.5
                                border-b border-gray-200/40 dark:border-gray-700/40">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Tipo de crédito

                        </span>

                        <span class="font-bold
                                     text-indigo-600 dark:text-indigo-400
                                     text-xs sm:text-base
                                     leading-tight
                                     text-right
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            {{ $tipoCreditoTexto }}

                        </span>

                    </div>


                    <div class="flex justify-between items-center
                                py-0.5
                                border-b border-gray-200/40 dark:border-gray-700/40">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Fecha del crédito

                        </span>

                        <span class="font-bold
                                     text-gray-900 dark:text-white
                                     text-xs sm:text-base
                                     leading-tight
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            {{ $loan->loan_date
                                ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y')
                                : ($loan->created_at
                                    ? $loan->created_at->format('d M Y')
                                    : 'N/A') }}

                        </span>

                    </div>


                    <div class="flex justify-between items-center
                                py-0.5
                                border-b border-gray-200/40 dark:border-gray-700/40">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Interés aplicado

                        </span>

                        <span class="font-bold
                                     text-gray-900 dark:text-white
                                     text-xs sm:text-base
                                     leading-tight
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            {{ number_format($tasaPorcentaje, 1) }} %

                        </span>

                    </div>


                    <div class="flex justify-between items-center
                                py-0.5
                                border-b border-gray-200/40 dark:border-gray-700/40">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Total intereses

                        </span>

                        <span class="font-bold
                                     text-gray-900 dark:text-white
                                     text-xs sm:text-base
                                     leading-tight
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            ${{ number_format($interesTotal, 2) }}

                        </span>

                    </div>


                    <div class="flex justify-between items-center py-0.5">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Total prestado (Capital)

                        </span>

                        <span class="font-bold
                                     text-gray-900 dark:text-white
                                     text-xs sm:text-base
                                     leading-tight
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            ${{ number_format($capitalTotal, 2) }}

                        </span>

                    </div>

                </div>


                <!-- =====================================================
                     COLUMNA 2

                     EXISTE EN ESCRITORIO.
                     EN MÓVIL SUS 4 CAMPOS SE OCULTAN.
                ====================================================== -->

                <div class="space-y-0.5">


                    <div class="loan-mobile-hide-summary
                                flex justify-between items-center
                                py-0.5
                                border-b border-gray-200/40 dark:border-gray-700/40">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Cuotas pagadas

                        </span>

                        <span class="font-bold
                                     text-gray-900 dark:text-white
                                     text-xs sm:text-base
                                     leading-tight
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            {{ $cuotasPagadasCount }} / {{ $totalCuotas }}

                        </span>

                    </div>


                    <div class="loan-mobile-hide-summary
                                flex justify-between items-center
                                py-0.5
                                border-b border-gray-200/40 dark:border-gray-700/40">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Prestado + intereses

                        </span>

                        <span class="font-bold
                                     text-gray-900 dark:text-white
                                     text-xs sm:text-base
                                     leading-tight
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            ${{ number_format($prestadoMasIntereses, 2) }}

                        </span>

                    </div>


                    <div class="loan-mobile-hide-summary
                                flex justify-between items-center
                                py-0.5
                                border-b border-gray-200/40 dark:border-gray-700/40">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-medium
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            Total abonado

                        </span>

                        <span class="font-bold
                                     text-green-600 dark:text-green-400
                                     text-xs sm:text-base
                                     leading-tight
                                     @[700px]:text-[9px]
                                     @[800px]:text-[10px]
                                     @[900px]:text-[11px]
                                     @[1000px]:text-sm
                                     @[1100px]:text-base">

                            ${{ number_format($totalAbonado, 2) }}

                        </span>

                    </div>


                    <div class="loan-mobile-hide-summary
                                flex justify-between items-center py-0.5">

                        <span class="text-[10px] sm:text-sm
                                     text-gray-500 dark:text-gray-400
                                     font-bold
                                     uppercase
                                     tracking-wider
                                     @[700px]:text-[8px]
                                     @[800px]:text-[9px]
                                     @[900px]:text-[10px]
                                     @[1000px]:text-xs
                                     @[1100px]:text-sm">

                            SALDO RESTANTE

                        </span>

                        <span class="font-black
                                     text-base sm:text-lg
                                     text-emerald-600 dark:text-emerald-400
                                     leading-tight
                                     @[700px]:text-sm
                                     @[800px]:text-[15px]
                                     @[900px]:text-base
                                     @[1000px]:text-[17px]
                                     @[1100px]:text-lg">

                            ${{ number_format($saldoRestante, 2) }}

                        </span>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 CRÉDITO LIQUIDADO
            ====================================================== -->

            @if($esLiquidado)

                <div class="mb-4
                            p-2 sm:p-3
                            rounded-xl
                            bg-emerald-50 dark:bg-emerald-950/30
                            border border-emerald-200 dark:border-emerald-800/50
                            flex items-center justify-between
                            shadow-sm

                            @[700px]:mb-2
                            @[700px]:p-1.5

                            @[800px]:mb-2.5
                            @[800px]:p-2

                            @[900px]:mb-3
                            @[900px]:p-2.5

                            @[1000px]:mb-4
                            @[1000px]:p-3">


                    <div class="flex items-center gap-2 sm:gap-3
                                @[700px]:gap-1.5
                                @[800px]:gap-2
                                @[900px]:gap-2.5">


                        <div class="w-7 h-7
                                    rounded-lg
                                    bg-emerald-500
                                    text-white
                                    flex items-center justify-center
                                    font-bold text-sm
                                    shadow-sm

                                    @[700px]:w-5
                                    @[700px]:h-5
                                    @[700px]:text-[10px]

                                    @[800px]:w-6
                                    @[800px]:h-6
                                    @[800px]:text-xs

                                    @[900px]:w-6
                                    @[900px]:h-6

                                    @[1000px]:w-7
                                    @[1000px]:h-7
                                    @[1000px]:text-sm">

                            ✓

                        </div>


                        <div>

                            <h5 class="font-bold text-xs
                                       text-emerald-800 dark:text-emerald-300
                                       uppercase tracking-wide
                                       @[700px]:text-[9px]
                                       @[800px]:text-[10px]
                                       @[900px]:text-[11px]
                                       @[1000px]:text-xs">

                                Crédito Liquidado

                            </h5>


                            <p class="text-[10px] sm:text-xs
                                      text-emerald-600 dark:text-emerald-400
                                      @[700px]:text-[8px]
                                      @[800px]:text-[9px]
                                      @[900px]:text-[10px]">

                                Este crédito se encuentra completamente pagado.

                            </p>

                        </div>

                    </div>

                </div>

            @else


                <!-- =================================================
                     BOTONES DE ACCIÓN
                ================================================== -->

                <div class="mb-2 sm:mb-4
                            flex flex-wrap justify-end
                            gap-1.5 sm:gap-2

                            @[700px]:mb-2
                            @[700px]:gap-1

                            @[800px]:gap-1.5

                            @[900px]:mb-3

                            @[1000px]:mb-4
                            @[1000px]:gap-2">


                    @if($loan->loan_modal === 'interest_only')

                        <div class="flex justify-end mb-2 sm:mb-4

                                    @[700px]:mb-1
                                    @[800px]:mb-1.5
                                    @[900px]:mb-2
                                    @[1000px]:mb-4">

                            <button type="button"
                                onclick="document.getElementById('modalAbonoCapital').classList.remove('hidden')"
                                class="px-2 sm:px-3.5
                                       py-1 sm:py-1.5
                                       bg-emerald-600 hover:bg-emerald-700
                                       text-white
                                       rounded-lg sm:rounded-xl
                                       text-[9px] sm:text-xs
                                       font-bold uppercase tracking-wider
                                       transition shadow-md
                                       flex items-center gap-1 sm:gap-2

                                       @[700px]:px-1.5
                                       @[700px]:py-0.5
                                       @[700px]:text-[8px]
                                       @[700px]:gap-1

                                       @[800px]:px-2
                                       @[800px]:text-[9px]

                                       @[900px]:px-2.5
                                       @[900px]:py-1
                                       @[900px]:text-[10px]

                                       @[1000px]:px-3
                                       @[1000px]:py-1.5
                                       @[1000px]:text-xs">

                                + Abono a Capital

                            </button>

                        </div>

                    @endif


                    <form action="{{ route('debts.liquidar', $loan->id) }}"
                          method="POST"
                          onsubmit="return confirm('¿Liquidar este préstamo por completo?');">

                        @csrf


                        <button type="submit"
                            class="px-2 sm:px-3.5
                                   py-1 sm:py-1.5
                                   bg-indigo-600 hover:bg-indigo-700
                                   text-white
                                   rounded-lg sm:rounded-xl
                                   text-[9px] sm:text-xs
                                   font-bold uppercase tracking-wider
                                   transition shadow-md
                                   flex items-center gap-1 sm:gap-2

                                   @[700px]:px-1.5
                                   @[700px]:py-0.5
                                   @[700px]:text-[8px]
                                   @[700px]:gap-1

                                   @[800px]:px-2
                                   @[800px]:text-[9px]

                                   @[900px]:px-2.5
                                   @[900px]:py-1
                                   @[900px]:text-[10px]

                                   @[1000px]:px-3
                                   @[1000px]:py-1.5
                                   @[1000px]:text-xs">

                            LIQUIDAR PRÉSTAMO COMPLETO

                        </button>

                    </form>

                </div>

            @endif


            <!-- =================================================
                 CONTENEDOR EN DOS COLUMNAS
            ================================================== -->

            @if($loan->installments && $loan->installments->count() > 0)

                <div class="grid grid-cols-1 xl:grid-cols-2
                            gap-3 sm:gap-6
                            pt-2 sm:pt-4
                            border-t dark:border-gray-700

                            @[700px]:gap-2
                            @[700px]:pt-1.5

                            @[800px]:gap-2.5
                            @[800px]:pt-2

                            @[900px]:gap-3
                            @[900px]:pt-2.5

                            @[1000px]:gap-3
                            @[1000px]:pt-3
                            @[1000px]:grid-cols-2

                            @[1100px]:gap-6
                            @[1100px]:pt-4

                            loan-two-column-medium">


                    <!-- =================================================
                         COLUMNA IZQUIERDA: CALENDARIO
                    ================================================== -->

                    <div class="space-y-1.5 sm:space-y-2.5

                                @[700px]:space-y-1
                                @[800px]:space-y-1
                                @[900px]:space-y-1.5
                                @[1000px]:space-y-1.5">


                        <h4 class="font-bold text-sm
                                   text-gray-900 dark:text-white
                                   @[700px]:text-[10px]
                                   @[800px]:text-[11px]
                                   @[900px]:text-xs
                                   @[1000px]:text-[13px]">

                            Calendario de Cuotas

                        </h4>


                        <div class="space-y-1
                                    max-h-[300px] sm:max-h-[360px]
                                    overflow-y-auto pr-1

                                    @[700px]:max-h-[280px]
                                    @[800px]:max-h-[300px]
                                    @[900px]:max-h-[320px]
                                    @[1000px]:max-h-[320px]
                                    @[1100px]:max-h-[360px]">


                            @foreach($loan->installments as $installment)

                                @php

                                    $esPagada =
                                        $installment->status == 'paid';

                                    $esVencida =
                                        !$esPagada &&
                                        $installment->due_date &&
                                        \Carbon\Carbon::parse(
                                            $installment->due_date
                                        )
                                        ->startOfDay()
                                        ->isPast();

                                @endphp


                                <div class="flex justify-between items-center
                                            py-1 sm:py-1.5
                                            px-1.5 sm:px-3
                                            rounded-lg sm:rounded-xl
                                            border
                                            text-[11px] sm:text-sm
                                            transition

                                            @[700px]:py-0.5
                                            @[700px]:px-1
                                            @[700px]:text-[9px]

                                            @[800px]:py-0.5
                                            @[800px]:px-1.5
                                            @[800px]:text-[10px]

                                            @[900px]:py-1
                                            @[900px]:px-2
                                            @[900px]:text-[11px]

                                            @[1000px]:py-0.5
                                            @[1000px]:px-1.5
                                            @[1000px]:text-[10px]

                                            @[1100px]:py-1.5
                                            @[1100px]:px-3
                                            @[1100px]:text-sm

                                        @if($esPagada)

                                            bg-emerald-50/50
                                            dark:bg-emerald-950/20
                                            border-emerald-100
                                            dark:border-emerald-900/40

                                        @elseif($esVencida)

                                            bg-red-50/70
                                            dark:bg-red-950/30
                                            border-red-200
                                            dark:border-red-900/50

                                        @else

                                            bg-gray-50/80
                                            dark:bg-gray-700/40
                                            border-gray-100
                                            dark:border-gray-700

                                        @endif">


                                    <div class="flex flex-col min-w-0">

                                        <span class="font-bold
                                                     text-gray-900 dark:text-white
                                                     text-[11px] sm:text-sm
                                                     leading-tight

                                                     @[700px]:text-[9px]
                                                     @[800px]:text-[10px]
                                                     @[900px]:text-[11px]
                                                     @[1000px]:text-[10px]
                                                     @[1100px]:text-sm">

                                            Cuota #{{ $installment->installment_number }}

                                        </span>


                                        <span class="text-[10px] sm:text-[11px]
                                                     text-gray-500 dark:text-gray-400

                                                     @[700px]:text-[8px]
                                                     @[800px]:text-[8px]
                                                     @[900px]:text-[9px]
                                                     @[1000px]:text-[8px]">

                                            Vence:
                                            {{ $installment->due_date
                                                ? \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y')
                                                : 'N/A' }}

                                        </span>

                                    </div>


                                    <div class="flex items-center
                                                gap-1 sm:gap-2.5
                                                shrink-0

                                                @[700px]:gap-0.5
                                                @[800px]:gap-1
                                                @[900px]:gap-1.5
                                                @[1000px]:gap-1">


                                        <span class="font-bold
                                                     text-gray-900 dark:text-white
                                                     text-xs sm:text-sm

                                                     @[700px]:text-[9px]
                                                     @[800px]:text-[10px]
                                                     @[900px]:text-[11px]
                                                     @[1000px]:text-[10px]
                                                     @[1100px]:text-sm">

                                            ${{ number_format($installment->amount_due, 2) }}

                                        </span>


                                        @if($esPagada)

                                            <span class="px-1.5 py-0.5
                                                         bg-emerald-100
                                                         text-emerald-800
                                                         dark:bg-emerald-900/40
                                                         dark:text-emerald-300
                                                         rounded
                                                         text-[9px] sm:text-[10px]
                                                         font-bold

                                                         @[700px]:px-0.5
                                                         @[700px]:text-[7px]

                                                         @[800px]:px-1
                                                         @[800px]:text-[8px]

                                                         @[900px]:px-1
                                                         @[900px]:text-[9px]

                                                         @[1000px]:px-1
                                                         @[1000px]:text-[8px]">

                                                PAGADA

                                            </span>


                                            @if(!$esLiquidado)

                                                <form action="{{ route('installments.destroyPayment', [$loan->id, $installment->id]) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Eliminar el pago de esta cuota?');">

                                                    @csrf
                                                    @method('DELETE')


                                                    <button type="submit"
                                                        class="px-1.5 py-0.5
                                                               bg-red-100
                                                               text-red-700
                                                               rounded
                                                               text-[9px] sm:text-[10px]
                                                               font-bold

                                                               @[700px]:px-0.5
                                                               @[700px]:text-[7px]

                                                               @[800px]:px-1
                                                               @[800px]:text-[8px]

                                                               @[900px]:text-[9px]

                                                               @[1000px]:text-[8px]">

                                                        Eliminar

                                                    </button>

                                                </form>

                                            @endif


                                        @else


                                            @if($esVencida)

                                                <span class="px-1.5 py-0.5
                                                             bg-red-100
                                                             text-red-700
                                                             rounded
                                                             text-[9px] sm:text-[10px]
                                                             font-bold

                                                             @[700px]:px-0.5
                                                             @[700px]:text-[7px]

                                                             @[800px]:px-1
                                                             @[800px]:text-[8px]

                                                             @[900px]:text-[9px]

                                                             @[1000px]:text-[8px]">

                                                    VENCIDA

                                                </span>

                                            @else

                                                <span class="px-1.5 py-0.5
                                                             bg-amber-100
                                                             text-amber-800
                                                             rounded
                                                             text-[9px] sm:text-[10px]
                                                             font-bold

                                                             @[700px]:px-0.5
                                                             @[700px]:text-[7px]

                                                             @[800px]:px-1
                                                             @[800px]:text-[8px]

                                                             @[900px]:text-[9px]

                                                             @[1000px]:text-[8px]">

                                                    PENDIENTE

                                                </span>

                                            @endif


                                            <form action="{{ route('installments.pay', [$loan->id, $installment->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('¿Registrar pago de esta cuota?');">

                                                @csrf


                                                <button type="submit"
                                                    class="px-2 sm:px-2.5
                                                           py-1
                                                           bg-emerald-600
                                                           hover:bg-emerald-700
                                                           text-white
                                                           rounded-md
                                                           text-[10px] sm:text-xs
                                                           font-bold

                                                           @[700px]:px-1
                                                           @[700px]:py-0.5
                                                           @[700px]:text-[8px]

                                                           @[800px]:px-1.5
                                                           @[800px]:text-[9px]

                                                           @[900px]:px-1.5
                                                           @[900px]:py-0.5
                                                           @[900px]:text-[10px]

                                                           @[1000px]:px-1.5
                                                           @[1000px]:py-0.5
                                                           @[1000px]:text-[8px]">

                                                    Pagar

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>


                    <!-- =================================================
                         COLUMNA DERECHA: ESTADO DE CUENTA
                    ================================================== -->

                    <div class="space-y-2

                                @[700px]:space-y-1

                                @[800px]:space-y-1.5

                                @[900px]:space-y-1.5

                                @[1000px]:space-y-1.5">


                        <div class="text-center
                                    font-bold
                                    text-sm sm:text-base
                                    text-gray-900 dark:text-white
                                    mb-2
                                    tracking-wide

                                    @[700px]:text-[10px]
                                    @[700px]:mb-1

                                    @[800px]:text-[11px]

                                    @[900px]:text-xs
                                    @[900px]:mb-1.5

                                    @[1000px]:text-[13px]
                                    @[1000px]:mb-1.5

                                    @[1100px]:text-base">

                            Estado de Cuenta (Tabla)

                        </div>


                        <div id="estadoCuentaScroll"
                             class="overflow-x-auto
                                    max-h-[300px] sm:max-h-[360px]
                                    overflow-y-auto

                                    @[700px]:max-h-[280px]

                                    @[800px]:max-h-[300px]

                                    @[900px]:max-h-[320px]

                                    @[1000px]:max-h-[320px]

                                    @[1100px]:max-h-[360px]">


                            <table class="w-full
                                          min-w-[520px]
                                          text-left
                                          text-[10px] sm:text-xs
                                          border-collapse

                                          @[700px]:min-w-[400px]
                                          @[700px]:text-[8px]

                                          @[800px]:min-w-[420px]
                                          @[800px]:text-[8px]

                                          @[900px]:min-w-[450px]
                                          @[900px]:text-[9px]

                                          @[1000px]:min-w-[430px]
                                          @[1000px]:text-[8px]

                                          @[1100px]:min-w-[520px]
                                          @[1100px]:text-xs">


                                <thead>

                                    <tr class="text-gray-400 dark:text-gray-500
                                               border-b border-gray-200 dark:border-gray-700">


                                        <th class="py-1.5 pb-2
                                                   font-normal
                                                   text-[10px] sm:text-xs
                                                   uppercase
                                                   tracking-wider

                                                   @[700px]:py-0.5
                                                   @[700px]:pb-1
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]

                                                   @[1100px]:text-xs">

                                            Fecha

                                        </th>


                                        <th class="loan-detail-column
                                                   py-1.5 pb-2
                                                   font-normal
                                                   text-[10px] sm:text-xs
                                                   uppercase
                                                   tracking-wider

                                                   @[700px]:py-0.5
                                                   @[700px]:pb-1
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]

                                                   @[1100px]:text-xs">

                                            Concepto / Detalle

                                        </th>


                                        <th class="py-1.5 pb-2
                                                   font-normal
                                                   text-right
                                                   text-[10px] sm:text-xs
                                                   uppercase
                                                   tracking-wider

                                                   @[700px]:py-0.5
                                                   @[700px]:pb-1
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]

                                                   @[1100px]:text-xs">

                                            Interés

                                        </th>


                                        <th class="py-1.5 pb-2
                                                   font-normal
                                                   text-right
                                                   text-[10px] sm:text-xs
                                                   uppercase
                                                   tracking-wider

                                                   @[700px]:py-0.5
                                                   @[700px]:pb-1
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]

                                                   @[1100px]:text-xs">

                                            Abono (Capital)

                                        </th>


                                        <th class="py-1.5 pb-2
                                                   font-normal
                                                   text-right
                                                   text-[10px] sm:text-xs
                                                   uppercase
                                                   tracking-wider

                                                   @[700px]:py-0.5
                                                   @[700px]:pb-1
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]

                                                   @[1100px]:text-xs">

                                            Saldo

                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y
                                             divide-gray-100
                                             dark:divide-gray-800/60
                                             text-gray-800
                                             dark:text-gray-200">


                                    @php

                                        $isInterestOnly =
                                            ($loan->loan_modal === 'interest_only');


                                        $montoInicial =
                                            $isInterestOnly
                                                ? (
                                                    $loan->total_amount ??
                                                    $loan->amount ??
                                                    0
                                                  )
                                                : $prestadoMasInteresesFijo;


                                        $saldoTablaAcumulado =
                                            $montoInicial;


                                        $movimientos =
                                            collect();


                                        $payments =
                                            \App\Models\Payment::where(
                                                'debt_id',
                                                $loan->id
                                            )->get();


                                        foreach (
                                            $payments as $index => $payment
                                        ) {

                                            $fechaMovimiento =
                                                $payment->payment_date ??
                                                $payment->created_at;


                                            $movimientos->push([

                                                'date' =>
                                                    $fechaMovimiento,

                                                'timestamp' =>
                                                    \Carbon\Carbon::parse(
                                                        $fechaMovimiento
                                                    )->timestamp,

                                                'id' =>
                                                    $payment->id,

                                                'index' =>
                                                    $index,

                                                'payment' =>
                                                    $payment
                                            ]);
                                        }


                                        $movimientos =
                                            $movimientos->sort(
                                                function ($a, $b) {

                                                    if (
                                                        $a['timestamp'] ===
                                                        $b['timestamp']
                                                    ) {

                                                        return
                                                            $a['id'] <=>
                                                            $b['id'];
                                                    }

                                                    return
                                                        $a['timestamp'] <=>
                                                        $b['timestamp'];
                                                }
                                            );


                                        $interesContador = 1;

                                        $cuotaContador = 1;

                                    @endphp


                                    <!-- CRÉDITO INICIAL -->

                                    <tr>

                                        <td class="py-1 sm:py-2
                                                   text-[10px] sm:text-xs

                                                   @[700px]:py-0.5
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:py-1
                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]

                                                   @[1100px]:py-2
                                                   @[1100px]:text-xs">

                                            {{ $loan->loan_date
                                                ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y')
                                                : ($loan->created_at
                                                    ? $loan->created_at->format('d/m/Y')
                                                    : 'N/A') }}

                                        </td>


                                        <td class="loan-detail-column
                                                   py-1 sm:py-2
                                                   text-[10px] sm:text-xs
                                                   font-normal

                                                   @[700px]:py-0.5
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:py-1
                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]

                                                   @[1100px]:py-2
                                                   @[1100px]:text-xs">

                                            Crédito Inicial ({{ $loan->concept }})

                                        </td>


                                        <td class="py-1 sm:py-2
                                                   text-right
                                                   text-gray-400
                                                   text-xs

                                                   @[700px]:py-0.5
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]">

                                            -

                                        </td>


                                        <td class="py-1 sm:py-2
                                                   text-right
                                                   text-gray-400
                                                   text-xs

                                                   @[700px]:py-0.5
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]">

                                            -

                                        </td>


                                        <td class="py-1 sm:py-2
                                                   text-right
                                                   font-bold
                                                   text-gray-900 dark:text-white
                                                   text-xs

                                                   @[700px]:py-0.5
                                                   @[700px]:text-[7px]

                                                   @[800px]:text-[8px]

                                                   @[900px]:text-[9px]

                                                   @[1000px]:text-[8px]">

                                            ${{ number_format(
                                                $montoInicial,
                                                2
                                            ) }}

                                        </td>

                                    </tr>


                                    @foreach($movimientos as $mov)

                                        @php

                                            $pay =
                                                $mov['payment'];


                                            $fechaFila =
                                                \Carbon\Carbon::parse(
                                                    $mov['date']
                                                )->format('d/m/Y');


                                            $montoInteresFila = 0;

                                            $montoAbonoFila = 0;

                                            $detalleFila = '';


                                            if ($isInterestOnly) {

                                                $interesPagado =
                                                    $pay->interest_covered ??
                                                    ($pay->interest ?? 0);


                                                $capitalPagado =
                                                    $pay->capital_covered ??
                                                    ($pay->capital ?? 0);


                                                if (
                                                    $interesPagado == 0 &&
                                                    $capitalPagado == 0
                                                ) {

                                                    $montoTotalPago =
                                                        $pay->amount ?? 0;


                                                    if (
                                                        $pay->installment_id
                                                    ) {

                                                        $interesPagado =
                                                            $montoTotalPago;

                                                    } else {

                                                        $capitalPagado =
                                                            $montoTotalPago;
                                                    }
                                                }


                                                if ($interesPagado > 0) {

                                                    $montoInteresFila =
                                                        $interesPagado;


                                                    $installment =
                                                        $pay->installment_id
                                                            ? $loan->installments
                                                                ->firstWhere(
                                                                    'id',
                                                                    $pay->installment_id
                                                                )
                                                            : null;


                                                    $numCuota =
                                                        $installment
                                                            ->installment_number ??
                                                        $interesContador;


                                                    $detalleFila =
                                                        "Interés #{$numCuota}";


                                                    $interesContador++;
                                                }


                                                if ($capitalPagado > 0) {

                                                    $montoAbonoFila =
                                                        $capitalPagado;


                                                    $saldoTablaAcumulado -=
                                                        $montoAbonoFila;


                                                    $detalleFila =
                                                        $pay->notes ?:
                                                        "Abono a Capital";
                                                }


                                                if (
                                                    $montoInteresFila == 0 &&
                                                    $montoAbonoFila == 0
                                                ) {

                                                    $montoAbonoFila =
                                                        $pay->amount ?? 0;


                                                    $saldoTablaAcumulado -=
                                                        $montoAbonoFila;


                                                    $detalleFila =
                                                        $pay->notes ?:
                                                        "Abono a Capital";
                                                }


                                            } else {

                                                $montoAbonoFila =
                                                    $pay->amount ??
                                                    $pay->capital_covered ??
                                                    0;


                                                $saldoTablaAcumulado -=
                                                    $montoAbonoFila;


                                                $installment =
                                                    $pay->installment_id
                                                        ? $loan->installments
                                                            ->firstWhere(
                                                                'id',
                                                                $pay->installment_id
                                                            )
                                                        : null;


                                                $numCuota =
                                                    $installment
                                                        ->installment_number ??
                                                    $cuotaContador;


                                                $detalleFila =
                                                    "Cuota #{$numCuota}";


                                                $cuotaContador++;
                                            }

                                        @endphp


                                        <tr>

                                            <td class="py-1 sm:py-2
                                                       text-[10px] sm:text-xs

                                                       @[700px]:py-0.5
                                                       @[700px]:text-[7px]

                                                       @[800px]:text-[8px]

                                                       @[900px]:py-1
                                                       @[900px]:text-[9px]

                                                       @[1000px]:text-[8px]

                                                       @[1100px]:py-2
                                                       @[1100px]:text-xs">

                                                {{ $fechaFila }}

                                            </td>


                                            <td class="loan-detail-column
                                                       py-1 sm:py-2
                                                       text-[10px] sm:text-xs
                                                       font-normal

                                                       @[700px]:py-0.5
                                                       @[700px]:text-[7px]

                                                       @[800px]:text-[8px]

                                                       @[900px]:py-1
                                                       @[900px]:text-[9px]

                                                       @[1000px]:text-[8px]

                                                       @[1100px]:py-2
                                                       @[1100px]:text-xs">

                                                {{ $detalleFila }}

                                            </td>


                                            <td class="py-1 sm:py-2
                                                       text-right
                                                       font-bold
                                                       text-blue-600
                                                       dark:text-blue-400
                                                       text-xs

                                                       @[700px]:py-0.5
                                                       @[700px]:text-[7px]

                                                       @[800px]:text-[8px]

                                                       @[900px]:text-[9px]

                                                       @[1000px]:text-[8px]">

                                                {{
                                                    $montoInteresFila > 0
                                                        ? '$' . number_format($montoInteresFila, 2)
                                                        : '-'
                                                }}

                                            </td>


                                            <td class="py-1 sm:py-2
                                                       text-right
                                                       font-bold
                                                       text-emerald-600
                                                       dark:text-emerald-400
                                                       text-xs

                                                       @[700px]:py-0.5
                                                       @[700px]:text-[7px]

                                                       @[800px]:text-[8px]

                                                       @[900px]:text-[9px]

                                                       @[1000px]:text-[8px]">

                                                {{
                                                    $montoAbonoFila > 0
                                                        ? '$' . number_format($montoAbonoFila, 2)
                                                        : '-'
                                                }}

                                            </td>


                                            <td class="py-1 sm:py-2
                                                       text-right
                                                       font-bold
                                                       text-gray-900
                                                       dark:text-white
                                                       text-xs

                                                       @[700px]:py-0.5
                                                       @[700px]:text-[7px]

                                                       @[800px]:text-[8px]

                                                       @[900px]:text-[9px]

                                                       @[1000px]:text-[8px]">

                                                ${{ number_format(
                                                    max(
                                                        0,
                                                        $saldoTablaAcumulado
                                                    ),
                                                    2
                                                ) }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>


                            @if($movimientos->isEmpty())

                                <div class="text-center
                                            py-4
                                            text-gray-400
                                            text-xs italic
                                            border-t border-gray-100
                                            dark:border-gray-800/60
                                            mt-1

                                            @[700px]:py-2
                                            @[700px]:text-[9px]

                                            @[800px]:py-2.5
                                            @[800px]:text-[10px]

                                            @[900px]:py-3
                                            @[900px]:text-[11px]

                                            @[1000px]:py-2
                                            @[1000px]:text-[9px]">

                                    Sin movimientos registrados.

                                </div>

                            @endif

                        </div>


                        <!-- =================================================
                             SALDO PENDIENTE
                        ================================================== -->

                        <div class="flex justify-end items-center
                                    gap-2 sm:gap-6
                                    pt-2 mt-1
                                    text-xs sm:text-sm
                                    font-bold

                                    @[700px]:gap-2
                                    @[700px]:pt-1
                                    @[700px]:text-[9px]

                                    @[800px]:gap-2.5
                                    @[800px]:text-[10px]

                                    @[900px]:gap-3
                                    @[900px]:pt-1.5
                                    @[900px]:text-[11px]

                                    @[1000px]:gap-3
                                    @[1000px]:pt-1.5
                                    @[1000px]:text-[10px]

                                    @[1100px]:gap-6
                                    @[1100px]:text-sm">


                            <span class="text-gray-900 dark:text-white
                                         text-[10px] sm:text-xs
                                         font-bold

                                         @[700px]:text-[8px]

                                         @[800px]:text-[9px]

                                         @[900px]:text-[10px]

                                         @[1000px]:text-[9px]">

                                Saldo Pendiente Actual:

                            </span>


                            <span class="text-amber-500
                                         text-xs sm:text-sm
                                         font-black

                                         @[700px]:text-[9px]

                                         @[800px]:text-[10px]

                                         @[900px]:text-[11px]

                                         @[1000px]:text-[10px]">

                                ${{ number_format(
                                    $isInterestOnly
                                        ? max(
                                            0,
                                            (
                                                $loan->total_amount ??
                                                $loan->amount ??
                                                0
                                            )
                                            -
                                            \App\Models\Payment::where(
                                                'debt_id',
                                                $loan->id
                                            )->sum('capital_covered')
                                        )
                                        : $saldoRestanteFijo,
                                    2
                                ) }}

                            </span>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


<!-- =============================================================
     MODAL ABONO A CAPITAL
============================================================= -->

<div id="modalAbonoCapital"
     class="fixed inset-0 z-50
            flex items-center justify-center
            bg-black/50
            backdrop-blur-sm
            hidden
            p-2 sm:p-4">


    <div class="bg-white dark:bg-gray-800
                rounded-xl sm:rounded-2xl
                p-3 sm:p-6
                w-full max-w-md
                max-h-[94vh]
                overflow-y-auto
                shadow-xl
                border border-gray-100 dark:border-gray-700

                @[700px]:p-2.5

                @[800px]:p-3

                @[900px]:p-4

                @[1000px]:p-5

                @[1100px]:p-6">


        <h3 class="text-sm sm:text-lg
                   font-bold
                   text-gray-900 dark:text-white
                   mb-1.5 sm:mb-2

                   @[700px]:text-[11px]

                   @[800px]:text-xs

                   @[900px]:text-sm

                   @[1000px]:text-base

                   @[1100px]:text-lg">

            Registrar Abono a Capital

        </h3>


        <p class="text-[10px] sm:text-xs
                  text-gray-500 dark:text-gray-400
                  mb-2 sm:mb-4

                  @[700px]:text-[8px]
                  @[700px]:mb-2

                  @[800px]:text-[9px]

                  @[900px]:text-[10px]
                  @[900px]:mb-3

                  @[1000px]:text-[11px]

                  @[1100px]:text-xs
                  @[1100px]:mb-4">

            Este monto reducirá directamente la deuda principal del crédito.

        </p>


        <form action="{{ route('debts.store.capital', $loan->id) }}"
              method="POST">

            @csrf


            <div class="mb-3 sm:mb-4

                        @[700px]:mb-2

                        @[800px]:mb-2.5

                        @[900px]:mb-3

                        @[1000px]:mb-3.5

                        @[1100px]:mb-4">


                <label class="block
                              text-[10px] sm:text-xs
                              font-bold
                              text-gray-700 dark:text-gray-300
                              uppercase
                              mb-1

                              @[700px]:text-[8px]

                              @[800px]:text-[9px]

                              @[900px]:text-[10px]

                              @[1000px]:text-[11px]">

                    Monto del abono ($)

                </label>


                <input type="number"
                       step="0.01"
                       name="capital_covered"
                       required
                       class="w-full
                              rounded-xl
                              border-gray-300
                              dark:border-gray-600
                              dark:bg-gray-900
                              dark:text-white
                              text-xs sm:text-sm
                              px-2.5 sm:px-3
                              py-1.5 sm:py-2
                              focus:ring-emerald-500
                              focus:border-emerald-500
                              shadow-xs

                              @[700px]:text-[9px]
                              @[700px]:px-2
                              @[700px]:py-1

                              @[800px]:text-[10px]

                              @[900px]:text-[11px]
                              @[900px]:py-1.5

                              @[1000px]:text-xs

                              @[1100px]:text-sm
                              @[1100px]:px-3
                              @[1100px]:py-2">

            </div>


            <div class="mb-3 sm:mb-4

                        @[700px]:mb-2

                        @[800px]:mb-2.5

                        @[900px]:mb-3

                        @[1000px]:mb-3.5

                        @[1100px]:mb-4">


                <label class="block
                              text-[10px] sm:text-xs
                              font-bold
                              text-gray-700 dark:text-gray-300
                              uppercase
                              mb-1

                              @[700px]:text-[8px]

                              @[800px]:text-[9px]

                              @[900px]:text-[10px]

                              @[1000px]:text-[11px]">

                    Fecha del pago

                </label>


                <input type="date"
                       name="payment_date"
                       value="{{ date('Y-m-d') }}"
                       required
                       class="w-full
                              rounded-xl
                              border-gray-300
                              dark:border-gray-600
                              dark:bg-gray-900
                              dark:text-white
                              text-xs sm:text-sm
                              px-2.5 sm:px-3
                              py-1.5 sm:py-2
                              shadow-xs

                              @[700px]:text-[9px]
                              @[700px]:px-2
                              @[700px]:py-1

                              @[800px]:text-[10px]

                              @[900px]:text-[11px]
                              @[900px]:py-1.5

                              @[1000px]:text-xs

                              @[1100px]:text-sm
                              @[1100px]:px-3
                              @[1100px]:py-2">

            </div>


            <div class="mb-3 sm:mb-4

                        @[700px]:mb-2

                        @[800px]:mb-2.5

                        @[900px]:mb-3

                        @[1000px]:mb-3.5

                        @[1100px]:mb-4">


                <label class="block
                              text-[10px] sm:text-xs
                              font-bold
                              text-gray-700 dark:text-gray-300
                              uppercase
                              mb-1

                              @[700px]:text-[8px]

                              @[800px]:text-[9px]

                              @[900px]:text-[10px]

                              @[1000px]:text-[11px]">

                    Notas / Detalle (Opcional)

                </label>


                <input type="text"
                       name="notes"
                       placeholder="Ej. Abono directo a capital"
                       class="w-full
                              rounded-xl
                              border-gray-300
                              dark:border-gray-600
                              dark:bg-gray-900
                              dark:text-white
                              text-xs sm:text-sm
                              px-2.5 sm:px-3
                              py-1.5 sm:py-2
                              shadow-xs

                              @[700px]:text-[9px]
                              @[700px]:px-2
                              @[700px]:py-1

                              @[800px]:text-[10px]

                              @[900px]:text-[11px]
                              @[900px]:py-1.5

                              @[1000px]:text-xs

                              @[1100px]:text-sm
                              @[1100px]:px-3
                              @[1100px]:py-2">

            </div>


            <div class="flex justify-end
                        gap-2 sm:gap-3

                        @[700px]:gap-1.5

                        @[800px]:gap-2

                        @[900px]:gap-2.5

                        @[1000px]:gap-3">


                <button type="button"
                        onclick="document.getElementById('modalAbonoCapital').classList.add('hidden')"
                        class="px-3 sm:px-4
                               py-1.5 sm:py-2
                               bg-gray-100 dark:bg-gray-700
                               text-gray-700 dark:text-gray-300
                               text-[10px] sm:text-xs
                               font-bold
                               rounded-xl
                               transition
                               cursor-pointer

                               @[700px]:px-2
                               @[700px]:py-1
                               @[700px]:text-[8px]

                               @[800px]:px-2.5
                               @[800px]:text-[9px]

                               @[900px]:px-3
                               @[900px]:text-[10px]

                               @[1000px]:px-3.5
                               @[1000px]:py-1.5
                               @[1000px]:text-[11px]">

                    Cancelar

                </button>


                <button type="submit"
                        class="px-3 sm:px-4
                               py-1.5 sm:py-2
                               bg-emerald-600
                               hover:bg-emerald-700
                               text-white
                               text-[10px] sm:text-xs
                               font-bold
                               rounded-xl
                               shadow-md
                               transition
                               cursor-pointer

                               @[700px]:px-2
                               @[700px]:py-1
                               @[700px]:text-[8px]

                               @[800px]:px-2.5
                               @[800px]:text-[9px]

                               @[900px]:px-3
                               @[900px]:text-[10px]

                               @[1000px]:px-3.5
                               @[1000px]:text-[11px]">

                    Guardar Abono

                </button>

            </div>

        </form>

    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollContainer = document.getElementById('estadoCuentaScroll');
        if (!scrollContainer) return;

        function checkTableOverflow() {
            // 1. Limpiamos clases para medir ancho natural
            scrollContainer.classList.remove('hide-detail-on-overflow');
            scrollContainer.classList.remove('shrink-table-text');

            // 2. Si desborda con detalle visible, ocultamos el detalle y desactivamos el scroll horizontal
            if (scrollContainer.scrollWidth > scrollContainer.clientWidth) {
                scrollContainer.classList.add('hide-detail-on-overflow');
            }

            // 3. Si aun ocultando el detalle sigue desbordando, reducimos el tamaño de la letra y paddings automáticamente
            if (scrollContainer.scrollWidth > scrollContainer.clientWidth) {
                scrollContainer.classList.add('shrink-table-text');
            }
        }

        checkTableOverflow();
        window.addEventListener('resize', checkTableOverflow);
    });
</script>

</x-app-layout>