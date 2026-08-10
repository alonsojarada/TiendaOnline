<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalle del Crédito: <span class="text-indigo-500">#{{ $loan->id }}-VJB</span>
            </h2>
            <a href="{{ route('clients.show', $loan->client_id) }}"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Volver al perfil del cliente
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div
                    class="font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $totalPagado = $loan->payments->sum('amount');
                $montoTotalConInteres = $loan->total_amount;
                $porcentajePagado = $montoTotalConInteres > 0 ? min(100, ($totalPagado / $montoTotalConInteres) * 100) : 0;

                $totalCuotas = $loan->installments->count();
                $cuotasPagadas = $loan->installments->where('status', 'paid')->count();

                // Cálculo seguro del capital basado en el total con interés y el porcentaje aplicado
                $tasa = $loan->interest_rate ?? 0;
                $capitalPrestado = $loan->capital_amount ?? ($tasa > 0 ? $montoTotalConInteres / (1 + ($tasa / 100)) : $montoTotalConInteres);

                $valorInteresTotal = $montoTotalConInteres - $capitalPrestado;
                $saldoTotalRestante = max(0, $montoTotalConInteres - $totalPagado);
            @endphp

            <!-- Tarjeta Principal de Resumen a Fondo -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200">

                <!-- Encabezado con Cliente, Concepto y Botón Eliminar -->
                <div class="flex justify-between items-center pb-4 border-b dark:border-gray-700 mb-6">
                    <div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Cliente</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $loan->client->name ?? 'Luis Martinez' }}
                        </h3>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Concepto</span>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $loan->concept }}</p>
                        </div>

                        <!-- Botón Eliminar Préstamo -->
                        <form action="{{ route('debts.destroy', $loan->id) }}" method="POST"
                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este préstamo? Se borrarán todos sus abonos y cuotas asociadas.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/50 dark:hover:bg-red-900/80 dark:text-red-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm"
                                title="Eliminar Préstamo">
                                🗑️ Eliminar
                            </button>
                        </form>

                        {{--
                        <!-- El botón de eliminar solo aparece si el préstamo NO está liquidado -->
                        @if(!$esLiquidado)
                        <form action="{{ route('debts.destroy', $loan->id) }}" method="POST"
                            onsubmit="return confirm('¿Estás seguro de eliminar este préstamo por completo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-300 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar Préstamo
                            </button>
                        </form>
                        @else
                        <!-- Etiqueta opcional si deseas mostrar algo en su lugar, o se deja vacío para que quede limpio -->
                        <span
                            class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-bold uppercase tracking-wider">
                            Crédito Concluido
                        </span>
                        @endif
                        --}}
                    </div>
                </div>

                <!-- Resumen Detallado Estilo Reporte -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-medium mb-6">
                    <div class="space-y-2">
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400">Fecha del crédito</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white">{{ $loan->created_at ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400">Interés aplicado</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white">{{ number_format($loan->interest_rate ?? 0, 1) }}
                                %</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400">Total intereses</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white">${{ number_format($valorInteresTotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400">Total prestado (Capital)</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white">${{ number_format($capitalPrestado, 2) }}</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400">Cuotas pagadas</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $cuotasPagadas }} /
                                {{ $totalCuotas }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400">Prestado + intereses</span>
                            <span
                                class="font-bold text-gray-900 dark:text-white">${{ number_format($montoTotalConInteres, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400">Total abonado</span>
                            <span
                                class="font-bold text-green-600 dark:text-green-400">${{ number_format($totalPagado, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                            <span class="text-gray-400 font-bold">Saldo Restante</span>
                            <span
                                class="font-black text-sm text-emerald-600 dark:text-emerald-400">${{ number_format($saldoTotalRestante, 2) }}</span>
                        </div>
                    </div>
                </div>

                @php
                    // Verificamos si el préstamo está completamente pagado
                    $esLiquidado = ($loan->status == 'paid') || ($loan->saldo_restante <= 0 && $loan->installments->where('status', '!=', 'paid')->count() == 0);

                    // Obtenemos la fecha del último pago registrado
                    $ultimoPago = $loan->payments()->latest('payment_date')->first();
                    $fechaLiquidacion = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date)->format('d/m/Y H:i') : now()->format('d/m/Y');
                @endphp

                <!-- Si ya está liquidado, mostramos la leyenda informativa. Si no, mostramos el botón de liquidar completo -->
                @if($esLiquidado)
                    <div
                        class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                ✓
                            </div>
                            <div>
                                <h5
                                    class="font-bold text-sm text-emerald-800 dark:text-emerald-300 uppercase tracking-wide">
                                    Préstamo Liquidado</h5>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400">Este crédito ha llegado a su etapa
                                    final y se encuentra completamente pagado.</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase text-gray-400 block">Fecha de Liquidación</span>
                            <span
                                class="text-xs font-bold text-emerald-700 dark:text-emerald-300">{{ $fechaLiquidacion }}</span>
                        </div>
                    </div>
                @else
                    <!-- Botón para Liquidar Préstamo Completo -->
                    <div class="mb-6 flex justify-end">
                        <form action="{{ route('debts.liquidar', $loan->id) }}" method="POST"
                            onsubmit="return confirm('¿Estás totalmente seguro de liquidar este préstamo? Se marcarán todas las cuotas pendientes como pagadas automáticamente.');">
                            @csrf
                            <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Liquidar Préstamo Completo
                            </button>
                        </form>
                    </div>
                @endif

                @if($loan->installments && $loan->installments->count() > 0)
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-3">Calendario de Cuotas</h4>
                    <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                        @foreach($loan->installments as $installment)
                            @php
                                $esPagada = $installment->status == 'paid';
                                $esVencida = !$esPagada && $installment->due_date && \Carbon\Carbon::parse($installment->due_date)->startOfDay()->isPast();

                                // Buscamos el pago asociado a esta cuota
                                $pagoCuota = $esPagada ? $loan->payments->where('installment_id', $installment->id)->first() : null;
                                $fechaPago = $pagoCuota ? \Carbon\Carbon::parse($pagoCuota->payment_date) : ($esPagada ? \Carbon\Carbon::parse($loan->updated_at) : null);

                                // Calculamos los días de retraso estrictos
                                $diasRetraso = 0;
                                if ($fechaPago && $installment->due_date) {
                                    $fechaVencimiento = \Carbon\Carbon::parse($installment->due_date)->startOfDay();
                                    $fechaRealPago = $fechaPago->copy()->startOfDay();

                                    if ($fechaRealPago->greaterThan($fechaVencimiento)) {
                                        $diasRetraso = $fechaVencimiento->diffInDays($fechaRealPago);
                                    }
                                }
                            @endphp

                            <div class="flex justify-between items-center py-2.5 px-3 rounded-xl border text-xs transition
                                @if($esPagada) bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-100 dark:border-emerald-900/40
                                @elseif($esVencida) bg-red-50/70 dark:bg-red-950/30 border-red-200 dark:border-red-900/50
                                @else bg-gray-50 dark:bg-gray-700/40 border-gray-100 dark:border-gray-700
                                @endif">

                                <!-- Lado Izquierdo: Cuota y Vencimiento -->
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    Cuota #{{ $installment->installment_number }}
                                    <span class="text-[10px] text-gray-400 ml-1">(Vence:
                                        {{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }})</span>
                                </span>

                                <!-- Lado Derecho: Elementos organizados horizontalmente -->
                                <div class="flex items-center gap-3">
                                    <span
                                        class="font-bold text-gray-900 dark:text-white">${{ number_format($installment->amount_due, 2) }}</span>

                                    @if($esPagada)
                                        <span
                                            class="px-2 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 rounded-md text-[10px] font-bold">PAGADA</span>

                                        <!-- Fecha de pago y días de retraso juntos -->
                                        @if($fechaPago)
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400 flex items-center gap-1.5">                                                
                                                @if($diasRetraso > 0)
                                                    <span class="font-bold text-red-600 dark:text-red-400">({{ $diasRetraso }}
                                                        {{ $diasRetraso == 1 ? 'día retraso' : 'días retraso' }})</span>
                                                @endif
                                            </span>
                                        @endif

                                        <!-- Botón Eliminar Pago (si el préstamo no está liquidado) -->
                                        @if(!$esLiquidado)
                                            <form action="{{ route('installments.destroyPayment', [$loan->id, $installment->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Estás seguro de eliminar el pago de la Cuota #{{ $installment->installment_number }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2.5 py-1 bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/40 dark:text-red-300 rounded-lg text-[11px] font-bold uppercase tracking-wider transition shadow-sm">
                                                    Eliminar Pago
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        @if($esVencida)
                                            <span
                                                class="px-2 py-0.5 bg-red-100 text-red-700 dark:bg-red-900/60 dark:text-red-300 rounded-md text-[10px] font-bold">⚠️
                                                VENCIDA</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 rounded-md text-[10px] font-bold">PENDIENTE</span>
                                        @endif

                                        <!-- Botón Pagar Individual -->
                                        <form action="{{ route('installments.pay', [$loan->id, $installment->id]) }}" method="POST"
                                            onsubmit="return confirm('¿Desea registrar el pago de la Cuota #{{ $installment->installment_number }}?');">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold uppercase tracking-wider transition shadow-sm">
                                                Pagar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>