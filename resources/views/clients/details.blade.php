<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex flex-row items-center justify-between gap-2">
                <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                    Detalle del Préstamo: <span class="text-indigo-500">#{{ $loan->id }}</span>
                </h2>
                <div class="shrink-0">
                    <a href="{{ request('from') === 'historial' ? route('reports.historial-cuentas') : route('clients.show', $loan->client_id) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-xl shadow-xs transition-all duration-200 group">
                        <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">&larr;</span>
                        <span>Volver</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 p-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

@php
    $modalidad = $loan->loan_modal ?? 'fixed_installments';
    $esCuotaFija = ($modalidad === 'fixed_installments');

    if ($esCuotaFija) {
        $prestadoMasIntereses = $loan->installments->sum('amount_due');
        if ($prestadoMasIntereses <= 0) {
            $prestadoMasIntereses = $loan->total_amount ?? 0;
        }

        $tasaPorcentaje = $loan->interest_rate ?? 0;
        $capitalTotal = $prestadoMasIntereses / (1 + ($tasaPorcentaje / 100));
        $interesTotal = $prestadoMasIntereses - $capitalTotal;

        $totalAbonado = $loan->installments->where('status', 'paid')->sum('amount_due');
        $saldoRestante = max(0, $prestadoMasIntereses - $totalAbonado);

        $totalCuotas = $loan->installments->count();
        $cuotasPagadasCount = $loan->installments->where('status', 'paid')->count();

        $esLiquidado = ($loan->status == 'paid') || ($saldoRestante <= 0 && $totalCuotas > 0 && $loan->installments->where('status', '!=', 'paid')->count() == 0);
        $ultimoPago = $loan->payments()->latest('payment_date')->first();
        $fechaLiquidacion = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date)->format('d/m/Y H:i') : now()->format('d/m/Y');

    } else {
        $capitalTotal = $loan->total_amount ?? $loan->amount ?? 0;
        $tasaPorcentaje = $loan->interest_rate ?? 0;

        $interesTotal = $capitalTotal * ($tasaPorcentaje / 100);

        $totalAbonadoInteres = $loan->payments->sum('interest_covered');
        $totalAbonadoCapital = $loan->payments->sum('capital_covered');

        $prestadoMasIntereses = $capitalTotal + $totalAbonadoInteres;
        $totalAbonado = $totalAbonadoCapital;
        $saldoRestante = max(0, $capitalTotal - $totalAbonado);

        $totalCuotas = $loan->installments->count();
        $cuotasPagadasCount = $loan->installments->where('status', 'paid')->count();

        $esLiquidado = ($loan->status == 'paid') || ($saldoRestante <= 0);
        $ultimoPago = $loan->payments()->latest('payment_date')->first();
        $fechaLiquidacion = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date)->format('d/m/Y H:i') : now()->format('d/m/Y');
    }

    $tasaFijaPorcentaje = $tasaPorcentaje;
    $interesFijoTotal = $interesTotal;
    $capitalFijo = $capitalTotal;
    $prestadoMasInteresesFijo = $prestadoMasIntereses;
    $totalAbonadoFijo = $totalAbonado;
    $saldoRestanteFijo = $saldoRestante;

    $nombreTipoCredito = $esCuotaFija ? 'Cuotas Fijas' : 'Interés Fijo';

    $frecuenciaRaw = $loan->frequency ?? $loan->payment_frequency ?? '';
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
            $frecuenciaTexto = ucfirst($frecuenciaRaw);
            break;
    }

    $tipoCreditoTexto = $nombreTipoCredito;
    if (!empty($frecuenciaTexto)) {
        $tipoCreditoTexto .= ' - ' . $frecuenciaTexto;
    }
@endphp

            <!-- Tarjeta Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-3 sm:p-5 md:p-6 border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200">

                <!-- ENCABEZADO DE CLIENTE Y ACCIONES -->
                <div class="flex justify-between items-center pb-2.5 sm:pb-3 border-b dark:border-gray-700 gap-2 mb-2.5 sm:mb-3">
                    <div class="min-w-0 flex-1">
                        <span class="text-[9px] sm:text-xs text-gray-400 uppercase font-bold tracking-wider">Cliente</span>
                        <h3 class="text-xs sm:text-lg font-bold text-gray-900 dark:text-white truncate">
                            {{ $loan->client->name ?? 'Cliente General' }}
                        </h3>
                    </div>

                    <div class="hidden sm:block text-right">
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Concepto</span>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $loan->concept }}</p>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('debts.pdf', $loan->id) }}"
                            class="px-2 py-1 sm:px-3 sm:py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-md sm:rounded-xl text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-xs">
                            📄 <span>Exportar PDF</span>
                        </a>

                        @unless($esLiquidado)
                            <form action="{{ route('debts.destroy', $loan->id) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de eliminar este préstamo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-2 py-1 sm:px-3 sm:py-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/50 dark:text-red-300 rounded-md sm:rounded-xl text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-xs">
                                    🗑️ <span>Eliminar</span>
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>

                <!-- Resumen Detallado -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 mb-3 sm:mb-4 bg-gray-50/50 dark:bg-gray-900/20 px-2.5 py-2 rounded-xl border border-gray-100 dark:border-gray-700/50 text-[11px] md:text-xs lg:text-base">
                    
                    <div class="space-y-1 sm:space-y-0.5">
                        <div class="flex justify-between sm:flex-row sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Tipo de crédito</span>
                            <span class="font-bold text-indigo-600 dark:text-indigo-400 leading-tight text-right">
                                {{ $tipoCreditoTexto }}
                            </span>
                        </div>

                        <div class="hidden sm:flex sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-[11px] md:text-xs lg:text-sm text-gray-500 dark:text-gray-400 font-medium">Fecha del crédito</span>
                            <span class="font-bold text-gray-900 dark:text-white text-xs md:text-sm lg:text-base leading-tight">
                                {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : ($loan->created_at ? $loan->created_at->format('d M Y') : 'N/A') }}
                            </span>
                        </div>
                        <div class="hidden sm:flex sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-[11px] md:text-xs lg:text-sm text-gray-500 dark:text-gray-400 font-medium">Interés aplicado</span>
                            <span class="font-bold text-gray-900 dark:text-white text-xs md:text-sm lg:text-base leading-tight">{{ number_format($tasaPorcentaje, 1) }} %</span>
                        </div>
                        <div class="hidden sm:flex sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-[11px] md:text-xs lg:text-sm text-gray-500 dark:text-gray-400 font-medium">Total intereses</span>
                            <span class="font-bold text-gray-900 dark:text-white text-xs md:text-sm lg:text-base leading-tight">${{ number_format($interesTotal, 2) }}</span>
                        </div>
                        <div class="hidden sm:flex sm:justify-between sm:items-center py-0.5">
                            <span class="text-[11px] md:text-xs lg:text-sm text-gray-500 dark:text-gray-400 font-medium">Total prestado (Capital)</span>
                            <span class="font-bold text-gray-900 dark:text-white text-xs md:text-sm lg:text-base leading-tight">${{ number_format($capitalTotal, 2) }}</span>
                        </div>
                    </div>

                    <div class="space-y-1 sm:space-y-0.5">
                        <div class="flex justify-between sm:flex-row sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Prestado + intereses</span>
                            <span class="font-bold text-gray-900 dark:text-white leading-tight">${{ number_format($prestadoMasIntereses, 2) }}</span>
                        </div>
                        <div class="flex justify-between sm:flex-row sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Total abonado</span>
                            <span class="font-bold text-green-600 dark:text-green-400 leading-tight">${{ number_format($totalAbonado, 2) }}</span>
                        </div>
                        <div class="flex justify-between sm:flex-row sm:justify-between sm:items-center py-0.5">
                            <span class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">SALDO RESTANTE</span>
                            <span class="font-black text-xs md:text-sm lg:text-lg text-emerald-600 dark:text-emerald-400 leading-tight">${{ number_format($saldoRestante, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($esLiquidado)
                    <div class="mb-3 sm:mb-4 p-2.5 sm:p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 min-w-[24px] sm:min-w-[28px] rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                ✓
                            </div>
                            <div>
                                <h5 class="font-bold text-[11px] sm:text-xs text-emerald-800 dark:text-emerald-300 uppercase tracking-wide">Crédito Liquidado</h5>
                                <p class="text-[11px] sm:text-xs text-emerald-600 dark:text-emerald-400">Este crédito se encuentra completamente pagado.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- BOTONES DE ACCIÓN -->
                    <div class="mb-3 sm:mb-4 flex flex-col sm:flex-row sm:flex-wrap justify-end gap-2">
                        @if($loan->loan_modal === 'interest_only')
                            <button type="button" onclick="document.getElementById('modalAbonoCapital').classList.remove('hidden')"
                                class="w-full sm:w-auto justify-center px-3 py-2 sm:px-3.5 sm:py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md flex items-center gap-2">
                                + Abono a Capital
                            </button>
                        @endif

                        <form action="{{ route('debts.liquidar', $loan->id) }}" method="POST" class="w-full sm:w-auto"
                            onsubmit="return confirm('¿Liquidar este préstamo por completo?');">
                            @csrf
                            <button type="submit"
                                class="w-full justify-center px-3 py-2 sm:px-3.5 sm:py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md flex items-center gap-2">
                                LIQUIDAR PRÉSTAMO COMPLETO
                            </button>
                        </form>
                    </div>
                @endif

                <!-- CONTENEDOR INFERIOR: 1 columna en móvil, 2 columnas en pantallas medianas y grandes -->
                @if($loan->installments && $loan->installments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pt-3 sm:pt-4 border-t dark:border-gray-700">

                        <!-- Columna Izquierda: Calendario de Cuotas (Compacto en mediano, grande solo en pantallas xl+) -->
                        <div class="space-y-2">
                            <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-white">Calendario de Cuotas</h4>
                            <div class="space-y-1.5 max-h-[360px] overflow-y-auto pr-1">
                                @foreach($loan->installments as $installment)
                                    @php
                                        $esPagada = $installment->status == 'paid';
                                        $esVencida = !$esPagada && $installment->due_date && \Carbon\Carbon::parse($installment->due_date)->startOfDay()->isPast();
                                    @endphp

                                    <div class="flex flex-row justify-between items-center py-1.5 px-2 md:px-2.5 rounded-xl border text-xs transition gap-1.5
                                            @if($esPagada) bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-100 dark:border-emerald-900/40
                                            @elseif($esVencida) bg-red-50/70 dark:bg-red-950/30 border-red-200 dark:border-red-900/50
                                            @else bg-gray-50/80 dark:bg-gray-700/40 border-gray-100 dark:border-gray-700 @endif">

                                        <!-- Izquierda de la cuota: Número y Fecha -->
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="font-bold text-gray-900 dark:text-white text-[11px] md:text-xs xl:text-sm">
                                                #{{ $installment->installment_number }}
                                            </span>
                                            <span class="text-[10px] md:text-[11px] xl:text-xs text-gray-500 dark:text-gray-400">
                                                {{ $installment->due_date ? \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </div>

                                        <!-- Derecha de la cuota: Compacto en mediano, crecen de tamaño SOLO en pantallas xl+ -->
                                        <div class="flex items-center gap-1 shrink-0">
                                            <span class="font-bold text-gray-900 dark:text-white text-[11px] md:text-xs xl:text-sm">${{ number_format($installment->amount_due, 2) }}</span>

                                            @if($esPagada)
                                                <span class="px-1 py-0.5 xl:px-2 xl:py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 rounded text-[9px] xl:text-xs font-bold">PAGADA</span>
                                                @if(!$esLiquidado)
                                                    <form action="{{ route('installments.destroyPayment', [$loan->id, $installment->id]) }}" method="POST"
                                                        onsubmit="return confirm('¿Eliminar el pago de esta cuota?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-1 py-0.5 xl:px-2 xl:py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded text-[9px] xl:text-xs font-bold transition">Eliminar</button>
                                                    </form>
                                                @endif
                                            @else
                                                @if($esVencida)
                                                    <span class="px-1 py-0.5 xl:px-2 xl:py-1 bg-red-100 text-red-700 rounded text-[9px] xl:text-xs font-bold">VENCIDA</span>
                                                @else
                                                    <span class="px-1 py-0.5 xl:px-2 xl:py-1 bg-amber-100 text-amber-800 rounded text-[9px] xl:text-xs font-bold">PENDIENTE</span>
                                                @endif
                                                <form action="{{ route('installments.pay', [$loan->id, $installment->id]) }}" method="POST"
                                                    onsubmit="return confirm('¿Registrar pago de esta cuota?');" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-1.5 py-0.5 xl:px-3 xl:py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[10px] xl:text-sm font-bold transition shadow-xs">Pagar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Columna Derecha: Estado de Cuenta (Tabla) -->
                        <div class="space-y-2 mt-2 md:mt-0">
                            <div class="text-center font-bold text-xs sm:text-sm text-gray-900 dark:text-white mb-1.5 sm:mb-2 tracking-wide">
                                Estado de Cuenta (Tabla)
                            </div>

                            <div class="w-full max-h-[360px] border border-gray-100 dark:border-gray-700 rounded-lg overflow-hidden">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead class="bg-gray-50/80 dark:bg-gray-800/80 sticky top-0 z-10 backdrop-blur-sm">
                                        <tr class="text-gray-400 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                            <th class="py-1.5 px-1 sm:px-2 font-semibold text-[9px] sm:text-xs uppercase tracking-wider">Fecha</th>
                                            <th class="py-1.5 px-1 sm:px-2 font-semibold text-[9px] sm:text-xs uppercase tracking-wider hidden xl:table-cell">Concepto / Detalle</th>
                                            <th class="py-1.5 px-1 sm:px-2 font-semibold text-right text-[9px] sm:text-xs uppercase tracking-wider">Interés</th>
                                            <th class="py-1.5 px-1 sm:px-2 font-semibold text-right text-[9px] sm:text-xs uppercase tracking-wider">Abono (Cap)</th>
                                            <th class="py-1.5 px-1 sm:px-2 font-semibold text-right text-[9px] sm:text-xs uppercase tracking-wider">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-gray-800 dark:text-gray-200">
                                        @php
                                            $isInterestOnly = ($loan->loan_modal === 'interest_only');
                                            $montoInicial = $isInterestOnly ? ($loan->total_amount ?? $loan->amount ?? 0) : $prestadoMasInteresesFijo;

                                            $saldoTablaAcumulado = $montoInicial;
                                            $movimientos = collect();

                                            $payments = \App\Models\Payment::where('debt_id', $loan->id)->get();

                                            foreach ($payments as $index => $payment) {
                                                $fechaMovimiento = $payment->payment_date ?? $payment->created_at;
                                                $movimientos->push([
                                                    'date' => $fechaMovimiento,
                                                    'timestamp' => \Carbon\Carbon::parse($fechaMovimiento)->timestamp,
                                                    'id' => $payment->id,
                                                    'index' => $index,
                                                    'payment' => $payment
                                                ]);
                                            }

                                            $movimientos = $movimientos->sort(function ($a, $b) {
                                                if ($a['timestamp'] === $b['timestamp']) {
                                                    return $a['id'] <=> $b['id'];
                                                }
                                                return $a['timestamp'] <=> $b['timestamp'];
                                            });

                                            $interesContador = 1;
                                            $cuotaContador = 1;
                                        @endphp

                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="py-1.5 px-1 sm:px-2 text-[10px] sm:text-xs">
                                                {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : ($loan->created_at ? $loan->created_at->format('d/m/Y') : 'N/A') }}
                                            </td>
                                            <td class="py-1.5 px-1 sm:px-2 text-[10px] sm:text-xs font-normal hidden xl:table-cell">Crédito Inicial ({{ $loan->concept }})</td>
                                            <td class="py-1.5 px-1 sm:px-2 text-right text-gray-400 text-[10px] sm:text-xs">-</td>
                                            <td class="py-1.5 px-1 sm:px-2 text-right text-gray-400 text-[10px] sm:text-xs">-</td>
                                            <td class="py-1.5 px-1 sm:px-2 text-right font-bold text-gray-900 dark:text-white text-[10px] sm:text-xs">
                                                ${{ number_format($montoInicial, 2) }}
                                            </td>
                                        </tr>

                                        @foreach($movimientos as $mov)
                                            @php
                                                $pay = $mov['payment'];
                                                $fechaFila = \Carbon\Carbon::parse($mov['date'])->format('d/m/Y');

                                                $montoInteresFila = 0;
                                                $montoAbonoFila = 0;
                                                $detalleFila = '';

                                                if ($isInterestOnly) {
                                                    $interesPagado = $pay->interest_covered ?? ($pay->interest ?? 0);
                                                    $capitalPagado = $pay->capital_covered ?? ($pay->capital ?? 0);

                                                    if ($interesPagado == 0 && $capitalPagado == 0) {
                                                        $montoTotalPago = $pay->amount ?? 0;
                                                        if ($pay->installment_id) {
                                                            $interesPagado = $montoTotalPago;
                                                        } else {
                                                            $capitalPagado = $montoTotalPago;
                                                        }
                                                    }

                                                    if ($interesPagado > 0) {
                                                        $montoInteresFila = $interesPagado;
                                                        $installment = $pay->installment_id ? $loan->installments->firstWhere('id', $pay->installment_id) : null;
                                                        $numCuota = $installment->installment_number ?? $interesContador;
                                                        $detalleFila = "Interés #{$numCuota}";
                                                        $interesContador++;
                                                    }

                                                    if ($capitalPagado > 0) {
                                                        $montoAbonoFila = $capitalPagado;
                                                        $saldoTablaAcumulado -= $montoAbonoFila;
                                                        $detalleFila = $pay->notes ?: "Abono a Capital";
                                                    }

                                                    if ($montoInteresFila == 0 && $montoAbonoFila == 0) {
                                                        $montoAbonoFila = $pay->amount ?? 0;
                                                        $saldoTablaAcumulado -= $montoAbonoFila;
                                                        $detalleFila = $pay->notes ?: "Abono a Capital";
                                                    }
                                                } else {
                                                    $montoAbonoFila = $pay->amount ?? $pay->capital_covered ?? 0;
                                                    $saldoTablaAcumulado -= $montoAbonoFila;

                                                    $installment = $pay->installment_id ? $loan->installments->firstWhere('id', $pay->installment_id) : null;
                                                    $numCuota = $installment->installment_number ?? $cuotaContador;
                                                    $detalleFila = "Cuota #{$numCuota}";
                                                    $cuotaContador++;
                                                }
                                            @endphp

                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                                <td class="py-1.5 px-1 sm:px-2 text-[10px] sm:text-xs">{{ $fechaFila }}</td>
                                                <td class="py-1.5 px-1 sm:px-2 text-[10px] sm:text-xs font-normal hidden xl:table-cell">{{ $detalleFila }}</td>
                                                
                                                <td class="py-1.5 px-1 sm:px-2 text-right font-bold text-blue-600 dark:text-blue-400 text-[10px] sm:text-xs">
                                                    {{ $montoInteresFila > 0 ? '$' . number_format($montoInteresFila, 2) : '-' }}
                                                </td>

                                                <td class="py-1.5 px-1 sm:px-2 text-right font-bold text-emerald-600 dark:text-emerald-400 text-[10px] sm:text-xs">
                                                    {{ $montoAbonoFila > 0 ? '$' . number_format($montoAbonoFila, 2) : '-' }}
                                                </td>

                                                <td class="py-1.5 px-1 sm:px-2 text-right font-bold text-gray-900 dark:text-white text-[10px] sm:text-xs">
                                                    ${{ number_format(max(0, $saldoTablaAcumulado), 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if($movimientos->isEmpty())
                                    <div class="text-center py-5 text-gray-400 text-[10px] sm:text-xs italic bg-gray-50/50 dark:bg-gray-800/20">
                                        Sin movimientos registrados.
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between sm:justify-end items-center gap-2 sm:gap-6 pt-2.5 mt-1 text-xs sm:text-sm font-bold bg-gray-50 dark:bg-gray-900/30 p-2 sm:p-2.5 rounded-lg border border-gray-100 dark:border-gray-700/50">
                                <span class="text-gray-900 dark:text-white text-[10px] sm:text-xs font-bold uppercase tracking-wider">Saldo Pendiente Actual:</span>
                                <span class="text-amber-500 text-xs sm:text-sm font-black">
                                    ${{ number_format($isInterestOnly ? max(0, ($loan->total_amount ?? $loan->amount ?? 0) - \App\Models\Payment::where('debt_id', $loan->id)->sum('capital_covered')) : $saldoRestanteFijo, 2) }}
                                </span>
                            </div>
                        </div>

                    </div>
                @endif

            </div>

        </div>
    </div>

   <div id="modalAbonoCapital" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 sm:p-6 w-full max-w-md shadow-xl border border-gray-100 dark:border-gray-700 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Registrar Abono a Capital</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Este monto reducirá directamente la deuda principal del crédito.</p>
            
            <form action="{{ route('debts.store.capital', $loan->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Monto del abono ($)</label>
                    <input type="number" step="0.01" name="capital_covered" required 
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500 shadow-xs transition">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Fecha del pago</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required 
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2.5 shadow-xs transition">
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Notas / Detalle (Opcional)</label>
                    <input type="text" name="notes" placeholder="Ej. Abono directo a capital" 
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2.5 shadow-xs transition">
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalAbonoCapital').classList.add('hidden')" 
                            class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl transition cursor-pointer text-center">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition cursor-pointer text-center">
                        Guardar Abono
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>