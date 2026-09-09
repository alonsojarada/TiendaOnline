<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle del Préstamo: <span class="text-indigo-500">#{{ $loan->id }}</span>
            </h2>
            <a href="{{ request('from') === 'historial' ? route('reports.historial-cuentas') : route('clients.show', $loan->client_id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-xl shadow-xs transition-all duration-200 group">
                <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">&larr;</span>
                <span>Volver</span>
            </a>
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
        // --- MODALIDAD 2: OTRA MODALIDAD (Usando interest_covered y capital_covered) ---
        $capitalTotal = $loan->total_amount ?? $loan->amount ?? 0;
        $tasaPorcentaje = $loan->interest_rate ?? 0;

        $interesTotal = $capitalTotal * ($tasaPorcentaje / 100);

        // Suma de los abonos de interés registrados en la tabla payments
        $totalAbonadoInteres = $loan->payments->sum('interest_covered');

        // Suma de los abonos a capital registrados en la tabla payments
        $totalAbonadoCapital = $loan->payments->sum('capital_covered');

        // Prestado + intereses = Capital prestado + Abonos de interés acumulados
        $prestadoMasIntereses = $capitalTotal + $totalAbonadoInteres;

        // Total abonado = Únicamente la suma de abonos a capital
        $totalAbonado = $totalAbonadoCapital;

        // Saldo restante = Capital prestado - Abonos a capital
        $saldoRestante = max(0, $capitalTotal - $totalAbonado);

        $totalCuotas = $loan->installments->count();
        $cuotasPagadasCount = $loan->installments->where('status', 'paid')->count();

        $esLiquidado = ($loan->status == 'paid') || ($saldoRestante <= 0);
        $ultimoPago = $loan->payments()->latest('payment_date')->first();
        $fechaLiquidacion = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date)->format('d/m/Y H:i') : now()->format('d/m/Y');
    }

    // Alias de respaldo para mantener compatibilidad con las etiquetas de la vista
    $tasaFijaPorcentaje = $tasaPorcentaje;
    $interesFijoTotal = $interesTotal;
    $capitalFijo = $capitalTotal;
    $prestadoMasInteresesFijo = $prestadoMasIntereses;
    $totalAbonadoFijo = $totalAbonado;
    $saldoRestanteFijo = $saldoRestante;

    // Nombre base del tipo de crédito
    $nombreTipoCredito = $esCuotaFija ? 'Cuotas Fijas' : 'Interés Fijo';

    // Traducción o mapeo de la frecuencia (frequency / payment_frequency)
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

    // Construir el texto final combinado
    $tipoCreditoTexto = $nombreTipoCredito;
    if (!empty($frecuenciaTexto)) {
        $tipoCreditoTexto .= ' - ' . $frecuenciaTexto;
    }
@endphp

            <!-- Tarjeta Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 sm:p-6 border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200">

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b dark:border-gray-700 gap-3 mb-4">
                    <div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Cliente</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $loan->client->name ?? 'Cliente General' }}
                        </h3>
                    </div>
                    <div class="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                        <div class="text-left sm:text-right">
                            <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Concepto</span>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $loan->concept }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('debts.pdf', $loan->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md text-xs font-semibold transition print:hidden shadow-sm">
                                Exportar PDF
                            </a>

                            @unless($esLiquidado)
                                <form action="{{ route('debts.destroy', $loan->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este préstamo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/50 dark:hover:bg-red-900/80 dark:text-red-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </div>

                <!-- Resumen Detallado -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-0.5 mb-4 bg-gray-50/50 dark:bg-gray-900/20 px-3.5 py-2 rounded-xl border border-gray-100 dark:border-gray-700/50">
                    <div class="space-y-0.5">
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Tipo de crédito</span>
                            <span class="font-bold text-indigo-600 dark:text-indigo-400 text-base leading-tight">
                                {{ $tipoCreditoTexto }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Fecha del crédito</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">
                                {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : ($loan->created_at ? $loan->created_at->format('d M Y') : 'N/A') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Interés aplicado</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">{{ number_format($tasaPorcentaje, 1) }} %</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total intereses</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">${{ number_format($interesTotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total prestado (Capital)</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">${{ number_format($capitalTotal, 2) }}</span>
                        </div>
                    </div>

                    <div class="space-y-0.5">
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Cuotas pagadas</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">{{ $cuotasPagadasCount }} / {{ $totalCuotas }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Prestado + intereses</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">${{ number_format($prestadoMasIntereses, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total abonado</span>
                            <span class="font-bold text-green-600 dark:text-green-400 text-base leading-tight">${{ number_format($totalAbonado, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">SALDO RESTANTE</span>
                            <span class="font-black text-lg text-emerald-600 dark:text-emerald-400 leading-tight">${{ number_format($saldoRestante, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($esLiquidado)
                    <div
                        class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                ✓</div>
                            <div>
                                <h5 class="font-bold text-xs text-emerald-800 dark:text-emerald-300 uppercase tracking-wide">Crédito
                                    Liquidado</h5>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400">Este crédito se encuentra completamente pagado.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- BOTONES DE ACCIÓN (LIQUIDAR Y REGISTRAR ABONO A CAPITAL) -->
                    <div class="mb-4 flex flex-wrap justify-end gap-2">
                        <!-- Botón para abrir el modal de Abono a Capital -->
                        @if($loan->loan_modal === 'interest_only')
                            <div class="flex justify-end mb-4">
                                <button type="button" onclick="document.getElementById('modalAbonoCapital').classList.remove('hidden')"
                                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md flex items-center gap-2">
                                    + Abono a Capital
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('debts.liquidar', $loan->id) }}" method="POST"
                            onsubmit="return confirm('¿Liquidar este préstamo por completo?');">
                            @csrf
                            <button type="submit"
                                class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md flex items-center gap-2">
                                LIQUIDAR PRÉSTAMO COMPLETO
                            </button>
                        </form>
                    </div>
                @endif

                <!-- CONTENEDOR EN DOS COLUMNAS -->
                @if($loan->installments && $loan->installments->count() > 0)
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 pt-4 border-t dark:border-gray-700">

                        <!-- Columna Izquierda: Calendario de Cuotas -->
                        <div class="space-y-2.5">
                            <h4 class="font-bold text-sm text-gray-900 dark:text-white">Calendario de Cuotas</h4>
                            <div class="space-y-1.5 max-h-[360px] overflow-y-auto pr-1">
                                @foreach($loan->installments as $installment)
                                    @php
                                        $esPagada = $installment->status == 'paid';
                                        $esVencida = !$esPagada && $installment->due_date && \Carbon\Carbon::parse($installment->due_date)->startOfDay()->isPast();
                                    @endphp

                                    <div class="flex justify-between items-center py-1.5 px-3 rounded-xl border text-sm transition
                                            @if($esPagada) bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-100 dark:border-emerald-900/40
                                            @elseif($esVencida) bg-red-50/70 dark:bg-red-950/30 border-red-200 dark:border-red-900/50
                                            @else bg-gray-50/80 dark:bg-gray-700/40 border-gray-100 dark:border-gray-700 @endif">

                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white text-sm leading-tight">
                                                Cuota #{{ $installment->installment_number }}
                                            </span>
                                            <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                                Vence: {{ $installment->due_date ? \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2.5">
                                            <span class="font-bold text-gray-900 dark:text-white text-sm">${{ number_format($installment->amount_due, 2) }}</span>

                                            @if($esPagada)
                                                <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 rounded text-[10px] font-bold">PAGADA</span>
                                                @if(!$esLiquidado)
                                                    <form action="{{ route('installments.destroyPayment', [$loan->id, $installment->id]) }}" method="POST"
                                                        onsubmit="return confirm('¿Eliminar el pago de esta cuota?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-bold">Eliminar</button>
                                                    </form>
                                                @endif
                                            @else
                                                @if($esVencida)
                                                    <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-bold">VENCIDA</span>
                                                @else
                                                    <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 rounded text-[10px] font-bold">PENDIENTE</span>
                                                @endif
                                                <form action="{{ route('installments.pay', [$loan->id, $installment->id]) }}" method="POST"
                                                    onsubmit="return confirm('¿Registrar pago de esta cuota?');">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-bold">Pagar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

<div class="space-y-2">
    <div class="text-center font-bold text-base text-gray-900 dark:text-white mb-2 tracking-wide">
        Estado de Cuenta (Tabla)
    </div>

    <div class="overflow-x-auto max-h-[360px] overflow-y-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="text-gray-400 dark:text-gray-500 border-b border-gray-200 dark:border-gray-700">
                    <th class="py-1.5 pb-2 font-normal text-xs uppercase tracking-wider">Fecha</th>
                    <th class="py-1.5 pb-2 font-normal text-xs uppercase tracking-wider">Concepto / Detalle</th>
                    <th class="py-1.5 pb-2 font-normal text-right text-xs uppercase tracking-wider">Interés</th>
                    <th class="py-1.5 pb-2 font-normal text-right text-xs uppercase tracking-wider">Abono (Capital)</th>
                    <th class="py-1.5 pb-2 font-normal text-right text-xs uppercase tracking-wider">Saldo</th>
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

                <!-- Fila 0: Crédito Inicial -->
                <tr>
                    <td class="py-2 text-xs">
                        {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : ($loan->created_at ? $loan->created_at->format('d/m/Y') : 'N/A') }}
                    </td>
                    <td class="py-2 text-xs font-normal">Crédito Inicial ({{ $loan->concept }})</td>
                    <td class="py-2 text-right text-gray-400 text-xs">-</td>
                    <td class="py-2 text-right text-gray-400 text-xs">-</td>
                    <td class="py-2 text-right font-bold text-gray-900 dark:text-white text-xs">
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

                    <tr>
                        <td class="py-2 text-xs">{{ $fechaFila }}</td>
                        <td class="py-2 text-xs font-normal">{{ $detalleFila }}</td>
                        
                        <td class="py-2 text-right font-bold text-blue-600 dark:text-blue-400 text-xs">
                            {{ $montoInteresFila > 0 ? '$' . number_format($montoInteresFila, 2) : '-' }}
                        </td>

                        <td class="py-2 text-right font-bold text-emerald-600 dark:text-emerald-400 text-xs">
                            {{ $montoAbonoFila > 0 ? '$' . number_format($montoAbonoFila, 2) : '-' }}
                        </td>

                        <td class="py-2 text-right font-bold text-gray-900 dark:text-white text-xs">
                            ${{ number_format(max(0, $saldoTablaAcumulado), 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($movimientos->isEmpty())
            <div class="text-center py-4 text-gray-400 text-xs italic border-t border-gray-100 dark:border-gray-800/60 mt-1">
                Sin movimientos registrados.
            </div>
        @endif
    </div>

    <div class="flex justify-end items-center gap-6 pt-2 mt-1 text-sm font-bold">
        <span class="text-gray-900 dark:text-white text-xs font-bold">Saldo Pendiente Actual:</span>
        <span class="text-amber-500 text-sm font-black">
            ${{ number_format($isInterestOnly ? max(0, ($loan->total_amount ?? $loan->amount ?? 0) - \App\Models\Payment::where('debt_id', $loan->id)->sum('capital_covered')) : $saldoRestanteFijo, 2) }}
        </span>
    </div>
</div>
                    </div>
                @endif

            </div>

        </div>
    </div>

   <div id="modalAbonoCapital" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md shadow-xl border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Registrar Abono a Capital</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Este monto reducirá directamente la deuda principal del crédito.</p>
        
        <form action="{{ route('debts.store.capital', $loan->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Monto del abono ($)</label>
                <input type="number" step="0.01" name="capital_covered" required 
                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-xs">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Fecha del pago</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required 
                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2 shadow-xs">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Notas / Detalle (Opcional)</label>
                <input type="text" name="notes" placeholder="Ej. Abono directo a capital" 
                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2 shadow-xs">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalAbonoCapital').classList.add('hidden')" 
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl transition cursor-pointer">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">
                    Guardar Abono
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>