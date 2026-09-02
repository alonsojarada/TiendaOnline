<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle del Crédito: <span class="text-indigo-500">#{{ $loan->id }}-VJB</span>
            </h2>
            <a href="{{ route('clients.show', $loan->client_id) }}"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Volver al perfil del cliente
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
$totalPagado = $loan->payments->sum('amount');
$montoTotalConInteres = $loan->total_amount;
$porcentajePagado = $montoTotalConInteres > 0 ? min(100, ($totalPagado / $montoTotalConInteres) * 100) : 0;

$totalCuotas = $loan->installments->count();
$cuotasPagadas = $loan->installments->where('status', 'paid')->count();

$tasa = $loan->interest_rate ?? 0;
$capitalPrestado = $loan->capital_amount ?? ($tasa > 0 ? $montoTotalConInteres / (1 + ($tasa / 100)) : $montoTotalConInteres);

$valorInteresTotal = $montoTotalConInteres - $capitalPrestado;
$saldoTotalRestante = max(0, $montoTotalConInteres - $totalPagado);
            @endphp

            <!-- Tarjeta Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 sm:p-6 border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200">

                <!-- Encabezado con Cliente, Concepto, Botón PDF y Botón Eliminar -->
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
                            <!-- Botón Exportar PDF -->
                           

                            <a href="{{ route('debts.pdf', $loan->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md text-xs font-semibold transition print:hidden shadow-sm">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Exportar PDF
                            </a>

                            <!-- Botón Eliminar Préstamo -->
                            <form action="{{ route('debts.destroy', $loan->id) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar este préstamo? Se borrarán todos sus abonos y cuotas asociadas.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/50 dark:hover:bg-red-900/80 dark:text-red-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm"
                                    title="Eliminar Préstamo">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Resumen Detallado en Grid Compacto con Fuentes Más Grandes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-0.5 mb-4 bg-gray-50/50 dark:bg-gray-900/20 px-3.5 py-2 rounded-xl border border-gray-100 dark:border-gray-700/50">
                    <div class="space-y-0.5">
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Fecha del crédito</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">
                                {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : ($loan->created_at ? $loan->created_at->format('d M Y') : 'N/A') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Interés aplicado</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">{{ number_format($loan->interest_rate ?? 0, 1) }} %</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total intereses</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">${{ number_format($valorInteresTotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total prestado (Capital)</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">${{ number_format($capitalPrestado, 2) }}</span>
                        </div>
                    </div>

                    <div class="space-y-0.5">
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Cuotas pagadas</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">{{ $cuotasPagadas }} / {{ $totalCuotas }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Prestado + intereses</span>
                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight">${{ number_format($montoTotalConInteres, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total abonado</span>
                            <span class="font-bold text-green-600 dark:text-green-400 text-base leading-tight">${{ number_format($totalPagado, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Saldo Restante</span>
                            <span class="font-black text-lg text-emerald-600 dark:text-emerald-400 leading-tight">${{ number_format($saldoTotalRestante, 2) }}</span>
                        </div>
                    </div>
                </div>

                @php
$esLiquidado = ($loan->status == 'paid') || ($saldoTotalRestante <= 0 && $totalCuotas > 0 && $loan->installments->where('status', '!=', 'paid')->count() == 0);
$ultimoPago = $loan->payments()->latest('payment_date')->first();
$fechaLiquidacion = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date)->format('d/m/Y H:i') : now()->format('d/m/Y');
                @endphp

                @if($esLiquidado)
                    <div class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                ✓
                            </div>
                            <div>
                                <h5 class="font-bold text-xs text-emerald-800 dark:text-emerald-300 uppercase tracking-wide">
                                    Préstamo Liquidado</h5>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400">Este crédito ha llegado a su etapa final y se encuentra completamente pagado.</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase text-gray-400 block">Fecha de Liquidación</span>
                            <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300">{{ $fechaLiquidacion }}</span>
                        </div>
                    </div>
                @else
                    <div class="mb-4 flex justify-end">
                        <form action="{{ route('debts.liquidar', $loan->id) }}" method="POST"
                            onsubmit="return confirm('¿Estás totalmente seguro de liquidar este préstamo? Se marcarán todas las cuotas pendientes como pagadas automáticamente.');">
                            @csrf
                            <button type="submit"
                                class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Liquidar Préstamo Completo
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

                        $diasRetraso = 0;
                        if (!$esPagada && $installment->due_date) {
                            $fechaVencimiento = \Carbon\Carbon::parse($installment->due_date)->startOfDay();
                            $hoy = \Carbon\Carbon::now()->startOfDay();

                            if ($hoy->greaterThan($fechaVencimiento)) {
                                $diasRetraso = $fechaVencimiento->diffInDays($hoy);
                            }
                        }
                                                    @endphp

                                                    <div class="flex justify-between items-center py-1.5 px-3 rounded-xl border text-sm transition
                                                        @if($esPagada) bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-100 dark:border-emerald-900/40
                                                        @elseif($esVencida) bg-red-50/70 dark:bg-red-950/30 border-red-200 dark:border-red-900/50
                                                        @else bg-gray-50/80 dark:bg-gray-700/40 border-gray-100 dark:border-gray-700
                                                        @endif">

                                                        <!-- Izquierda: Cuota y Vencimiento -->
                                                        <div class="flex flex-col">
                                                            <span class="font-bold text-gray-900 dark:text-white text-sm leading-tight">
                                                                Cuota #{{ $installment->installment_number }}
                                                            </span>
                                                            <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                                                Vence: {{ $installment->due_date ? \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') : 'N/A' }}
                                                            </span>
                                                        </div>

                                                        <!-- Derecha: Monto, Estatus y Botones -->
                                                        <div class="flex items-center gap-2.5">
                                                            <span class="font-bold text-gray-900 dark:text-white text-sm">${{ number_format($installment->amount_due, 2) }}</span>

                                                            @if($esPagada)
                                                                <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 rounded text-[10px] font-bold">PAGADA</span>

                                                                @if(!$esLiquidado)
                                                                    <form action="{{ route('installments.destroyPayment', [$loan->id, $installment->id]) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('¿Estás seguro de eliminar el pago de la Cuota #{{ $installment->installment_number }}?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="px-1.5 py-0.5 bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/40 dark:text-red-300 rounded text-[10px] font-bold uppercase tracking-wider transition">
                                                                            Eliminar
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @else
                                                                @if($esVencida)
                                                                    <div class="flex items-center gap-1">
                                                                        @if($diasRetraso > 0)
                                                                            <span class="font-bold text-red-600 dark:text-red-400 text-[11px]">
                                                                                ({{ $diasRetraso }}d)
                                                                            </span>
                                                                        @endif
                                                                        <span class="px-1.5 py-0.5 bg-red-100 text-red-700 dark:bg-red-900/60 dark:text-red-300 rounded text-[10px] font-bold">VENCIDA</span>
                                                                    </div>
                                                                @else
                                                                    <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 rounded text-[10px] font-bold">PENDIENTE</span>
                                                                @endif

                                                                <form action="{{ route('installments.pay', [$loan->id, $installment->id]) }}" method="POST"
                                                                    onsubmit="return confirm('¿Desea registrar el pago de la Cuota #{{ $installment->installment_number }}?');">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-bold uppercase tracking-wider transition shadow-sm">
                                                                        Pagar
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Columna Derecha: Estado de Cuenta (Compacto y Plano) -->
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
                                                            <th class="py-1.5 pb-2 font-normal text-right text-xs uppercase tracking-wider">Abono</th>
                                                            <th class="py-1.5 pb-2 font-normal text-right text-xs uppercase tracking-wider">Saldo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-gray-800 dark:text-gray-200">
                                                        <!-- Fila Inicial del Crédito -->
                                                        <tr>
                                                            <td class="py-2 text-xs">
                                                                {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : ($loan->created_at ? $loan->created_at->format('d/m/Y') : 'N/A') }}
                                                            </td>
                                                            <td class="py-2 text-xs font-normal">
                                                                Crédito Inicial ({{ $loan->concept }})
                                                            </td>
                                                            <td class="py-2 text-right text-gray-400 dark:text-gray-500 text-xs">-</td>
                                                            <td class="py-2 text-right font-bold text-gray-900 dark:text-white text-xs">
                                                                ${{ number_format($montoTotalConInteres, 2) }}
                                                            </td>
                                                        </tr>

                                                        @php
                    $saldoTablaAcumulado = $montoTotalConInteres;
                    $tieneMovimientos = false;
                                                        @endphp

                                                        @foreach($loan->installments as $installment)
    @php
        $esPagada = $installment->status == 'paid';

        if ($esPagada) {
            $tieneMovimientos = true;
            $pagoCuota = $loan->payments->where('installment_id', $installment->id)->first();
            $montoAbonado = $pagoCuota ? $pagoCuota->amount : $installment->amount_due;
            $saldoTablaAcumulado -= $montoAbonado;
            
            // Mantiene tu estructura, cambiando únicamente el uso de la fecha por updated_at cuando está pagada
            $fechaFila = $installment->updated_at ? \Carbon\Carbon::parse($installment->updated_at)->format('d/m/Y') : ($installment->due_date ? \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') : 'N/A');
    @endphp
        <tr>
            <td class="py-2 text-xs">
                {{ $fechaFila }}
            </td>
            <td class="py-2 text-xs font-normal">
                Abono #{{ $installment->installment_number }}
            </td>
            <td class="py-2 text-right font-bold text-emerald-600 dark:text-emerald-400 text-xs">
                ${{ number_format($montoAbonado, 2) }}
            </td>
            <td class="py-2 text-right font-bold text-gray-900 dark:text-white text-xs">
                ${{ number_format(max(0, $saldoTablaAcumulado), 2) }}
            </td>
        </tr>
    @php
        }
    @endphp
@endforeach
                                                    </tbody>
                                                </table>

                                                @php
                                                    $pagosSueltos = $loan->payments->whereNull('installment_id');
                                                    if ($pagosSueltos->count() > 0) {
                                                        $tieneMovimientos = true;
                                                    }
                                                @endphp

                                                @if(!$tieneMovimientos)
                                                    <div class="text-center py-4 text-gray-400 dark:text-gray-500 text-xs italic border-t border-gray-100 dark:border-gray-800/60 mt-1">
                                                        Sin movimientos registrados.
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Saldo Pendiente Actual al Pie de la Tabla -->
                                            <div class="flex justify-end items-center gap-6 pt-2 mt-1 text-sm font-bold">
                                                <span class="text-gray-900 dark:text-white text-xs font-bold">Saldo Pendiente Actual:</span>
                                                <span class="text-amber-500 text-sm font-black">${{ number_format($saldoTotalRestante, 2) }}</span>
                                            </div>
                                        </div>

                                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>