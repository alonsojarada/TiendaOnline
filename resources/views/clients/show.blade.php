<x-app-layout>
    <x-slot name="header">
        <!-- Cambiamos pl-14 por pl-16 para separar más el texto del botón -->
        <div class="flex justify-between items-center pl-16 sm:pl-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $client->name }} <span
                    class="text-sm text-indigo-500 font-normal">({{ $client->alias ? '"' . $client->alias . '"' : 'Sin alias' }})</span>
            </h2>
            <a href="{{ route('clients.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Volver
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div
                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <!-- Teléfono y Dirección -->
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Teléfono</span>
                        <span
                            class="text-gray-800 dark:text-gray-200 font-medium mb-2 block">{{ $client->phone ?? 'N/A' }}</span>
                        <span
                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Dirección</span>
                        <span
                            class="text-gray-800 dark:text-gray-200 font-medium">{{ $client->address ?? 'N/A' }}</span>
                    </div>

                    <!-- Desglose de Mercancía Fiada -->
                    <div
                        class="border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-700 pt-3 md:pt-0 md:pl-4">
                        <span
                            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block">Mercancía
                            Fiada</span>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Abonado: <span
                                class="font-semibold text-green-600">${{ number_format($totalAbonosMercancia, 2) }}</span>
                        </div>
                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                            Restante: ${{ number_format($totalMercanciaRestante, 2) }}
                        </div>
                    </div>

                    <!-- Desglose de Préstamos en Efectivo -->
                    <div
                        class="border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-700 pt-3 md:pt-0 md:pl-4">
                        <span
                            class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">Préstamos
                            Efectivo</span>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Abonado: <span
                                class="font-semibold text-green-600">${{ number_format($totalAbonosPrestamos, 2) }}</span>
                        </div>
                        <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                            Restante: ${{ number_format($totalPrestamosRestante, 2) }}
                        </div>
                    </div>

                    <!-- Total Adeudo Global -->
                    <div
                        class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg border border-red-100 dark:border-red-800/30 text-right">
                        <span
                            class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider block">Adeudo
                            Global Restante</span>
                        <span
                            class="text-xl font-extrabold text-red-700 dark:text-red-300">${{ number_format($totalAdeudoGlobal, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ================= COLUMNA 1: MERCANCÍA FIADA ================= -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b dark:border-gray-700">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Ropa y Mercancía Fiada</h3>
                        <button type="button" onclick="document.getElementById('modalFiado').classList.remove('hidden')"
                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-xs font-semibold uppercase tracking-wider transition">
                            + Fiar Artículo
                        </button>
                    </div>

                    @php
                        // Forzamos el ordenamiento directamente en la vista por si acaso la variable no llega ordenada
                        $creditosOrdenados = $storeCredits->sortByDesc(function ($credit) {
                            $ultimoPago = $credit->payments()->latest('payment_date')->first();
                            $fechaRef = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date) : \Carbon\Carbon::parse($credit->created_at);
                            return $fechaRef->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                        });
                    @endphp

                    @forelse($creditosOrdenados as $credit)
                        @php
                            $ultimoPago = $credit->payments()->latest('payment_date')->first();
                            $fechaReferencia = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date) : \Carbon\Carbon::parse($credit->created_at);
                            $diasTranscurridos = $fechaReferencia->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                            $textoFecha = $ultimoPago ? 'Abono: ' . $fechaReferencia->format('d/m/Y') : 'Fiado: ' . $fechaReferencia->format('d/m/Y');

                            $saldoPendiente = $credit->total_amount - $credit->payments->sum('amount');
                            $alertaInactivo = ($diasTranscurridos >= 7 && $saldoPendiente > 0);
                        @endphp

                        <!-- Contenedor con borde y fondo resaltado si supera los 7 días -->
                        <div
                            class="p-3 rounded-xl mb-3 border transition {{ $alertaInactivo ? 'border-red-300 dark:border-red-800/80 bg-red-50/30 dark:bg-red-950/10' : 'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-700' }}">

                            <!-- Cabecera de la tarjeta: Concepto y Botón de Detalle -->
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $credit->concept }}</h4>
                                    <p
                                        class="text-xs text-gray-700 dark:text-gray-300 font-medium flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $textoFecha }}</span>
                                        <span>•</span>
                                        <span>${{ number_format($credit->total_amount, 2) }}</span>
                                        @if($diasTranscurridos >= 7)
                                            <span class="font-bold text-red-600 dark:text-red-400">
                                                ({{ $diasTranscurridos }} días sin abonar)
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Botón para ir al detalle completo en otra ventana -->
                                <a href="{{ route('store-details', $credit->id) }}"
                                    class="text-[10px] uppercase font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 flex items-center gap-0.5 transition">
                                    Detalle ➔
                                </a>
                            </div>

                            <!-- Monto Restante -->
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-right ml-auto">
                                    <span class="text-[10px] uppercase text-amber-500 font-bold block">Debe</span>
                                    <span class="text-sm font-bold text-amber-600 dark:text-amber-400">
                                        ${{ number_format($saldoPendiente, 2) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Formulario de Abono Rápido -->
                            <form action="{{ route('payments.store', $credit->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="number" step="0.01" name="amount" placeholder="$ Monto a abonar" required
                                    class="w-full text-xs px-3 py-1.5 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="submit"
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md uppercase tracking-wider transition">
                                    Abonar
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No tiene mercancía fiada
                            pendiente.</p>
                    @endforelse
                </div>

                <!-- ================= COLUMNA 2: PRÉSTAMOS EN EFECTIVO ================= -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 sm:p-5">
                    <div class="flex justify-between items-center mb-3 pb-2 border-b dark:border-gray-700">
                        <h3 class="font-bold text-base text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            📈 Créditos Activos <span
                                class="text-xs bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 px-2 py-0.5 rounded-full">({{ $cashLoans->count() }})</span>
                        </h3>
                        <button type="button"
                            onclick="document.getElementById('modalPrestamo').classList.remove('hidden')"
                            class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-semibold uppercase tracking-wider transition">
                            + NUEVO
                        </button>
                    </div>

                    @php
                        // Ordenamos primero los que tengan cuotas vencidas y luego por la cantidad de cuotas pendientes
                        $prestamosOrdenados = $cashLoans->sort(function ($a, $b) {
                            $vencidasA = $a->installments->where('status', '!=', 'paid')->where('due_date', '<', now())->count();
                            $vencidasB = $b->installments->where('status', '!=', 'paid')->where('due_date', '<', now())->count();

                            if ($vencidasA !== $vencidasB) {
                                return $vencidasB <=> $vencidasA;
                            }

                            $pendientesA = $a->installments->where('status', 'pending')->count();
                            $pendientesB = $b->installments->where('status', 'pending')->count();

                            return $pendientesB <=> $pendientesA;
                        });
                    @endphp

                    @forelse($prestamosOrdenados as $loan)
                        @php
                            $totalPagado = $loan->payments->sum('amount');
                            $montoTotalConInteres = $loan->total_amount;
                            $porcentajePagado = $montoTotalConInteres > 0 ? min(100, ($totalPagado / $montoTotalConInteres) * 100) : 0;

                            $valorCuota = $loan->installments->first()->amount_due ?? 0;
                            $totalCuotas = $loan->installments->count();
                            $cuotasPagadas = $loan->installments->where('status', 'paid')->count();

                            $cuotasVencidas = $loan->installments->where('status', '!=', 'paid')
                                ->where('due_date', '<', now())
                                ->count();

                            $frecuenciaTexto = match ($loan->payment_frequency) {
                                'weekly' => 'Semanal',
                                'biweekly' => 'Quincenal',
                                'monthly' => 'Mensual',
                                default => 'Libre'
                            };

                            $tasa = $loan->interest_rate ?? 0;
                            $capitalPrestado = $loan->capital_amount ?? ($loan->amount > 0 ? $loan->amount : ($tasa > 0 ? $montoTotalConInteres / (1 + ($tasa / 100)) : $montoTotalConInteres));

                            $interesPorcentaje = $loan->interest_rate ?? 0;
                            $valorTotalIntereses = $montoTotalConInteres - $capitalPrestado;
                            $fechaCredito = optional($loan->created_at)->format('d M Y') ?? 'N/A';

                            $firstUnpaid = $loan->installments->where('status', '!=', 'paid')->first();
                            $fechaProxima = optional($firstUnpaid)->due_date ? \Carbon\Carbon::parse($firstUnpaid->due_date)->format('d M Y') : 'Completado';
                            $fechaVencimiento = optional($loan->installments->last())->due_date ? \Carbon\Carbon::parse($loan->installments->last()->due_date)->format('d M Y') : 'N/A';
                        @endphp

                        <!-- Contenedor de la Tarjeta -->
                        <div
                            class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md rounded-xl p-3 mb-3 border {{ $cuotasVencidas > 0 ? 'border-red-300 dark:border-red-800/80 bg-red-50/30 dark:bg-red-950/10' : 'border-gray-100 dark:border-gray-700' }} transition relative">

                            <!-- Fila Superior -->
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs font-mono font-bold">
                                        #{{ $loan->id }}
                                    </span>
                                    <div>
                                        <span class="text-lg font-black text-gray-900 dark:text-white">
                                            ${{ number_format($montoTotalConInteres, 2) }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-medium ml-1">({{ $loan->concept }})</span>
                                    </div>
                                </div>

                                <!-- Botones de Acción Rápida -->
                                <div class="flex items-center gap-1.5">
                                    <button type="button"
                                        onclick="document.getElementById('modal-detalle-{{ $loan->id }}').classList.remove('hidden')"
                                        class="px-2 py-1 bg-gray-100 hover:bg-emerald-50 dark:bg-gray-700 dark:hover:bg-emerald-950 text-gray-600 dark:text-gray-300 hover:text-emerald-600 rounded-lg text-xs font-semibold flex items-center gap-1 transition"
                                        title="Vista Rápida">
                                        👁️ Detalle
                                    </button>

                                    <a href="{{ route('debts.details', $loan->id) }}"
                                        class="w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-emerald-500 hover:text-white flex items-center justify-center text-gray-400 transition text-xs"
                                        title="Ir a página completa">
                                        ➔
                                    </a>
                                </div>
                            </div>

                            <!-- Barra de Progreso -->
                            <div class="mb-2">
                                <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-500"
                                        style="width: {{ $porcentajePagado }}%;"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-xs px-1 my-0.5">
                                <span class="text-gray-500 dark:text-gray-400">
                                    Pagado: <span
                                        class="font-medium text-green-600">${{ number_format($totalPagado, 2) }}</span>
                                </span>
                                <!-- Cambio a color naranja (amber) -->
                                <span class="font-bold text-amber-600 dark:text-amber-400 text-base tracking-tight">
                                    Restante: ${{ number_format($montoTotalConInteres - $totalPagado, 2) }}
                                </span>
                            </div>

                            <!-- Fila Inferior -->
                            <div
                                class="grid grid-cols-4 gap-1 pt-2 border-t border-gray-100 dark:border-gray-700 text-center items-center">
                                <div>
                                    <span
                                        class="block text-gray-400 text-[10px] uppercase font-bold tracking-tight">Cuota</span>
                                    <span
                                        class="font-bold text-gray-800 dark:text-gray-200 text-sm">${{ number_format($valorCuota, 2) }}</span>
                                </div>
                                <div class="border-l border-gray-100 dark:border-gray-700">
                                    <span
                                        class="block text-gray-400 text-[10px] uppercase font-bold tracking-tight">Avance</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">{{ $cuotasPagadas }} /
                                        #{{ $totalCuotas > 0 ? $totalCuotas : 'N/A' }}</span>
                                </div>
                                <div class="border-l border-gray-100 dark:border-gray-700">
                                    <span
                                        class="block text-gray-400 text-[10px] uppercase font-bold tracking-tight">Frecuencia</span>
                                    <span
                                        class="font-semibold text-gray-700 dark:text-gray-200 text-xs">{{ $frecuenciaTexto }}</span>
                                </div>
                                <div class="border-l border-gray-100 dark:border-gray-700 flex justify-center">
                                    @if($cuotasVencidas > 0)
                                        <span
                                            class="px-3 py-1.5 bg-red-600 text-white dark:bg-red-700 rounded-lg font-black text-xs md:text-sm shadow-md scale-105 flex items-center gap-1.5">
                                            🚨 {{ $cuotasVencidas }} Venc.
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 rounded font-bold text-xs">
                                            Al corriente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ================= MODAL DE VISTA RÁPIDA ================= -->
                        <div id="modal-detalle-{{ $loan->id }}" role="dialog" aria-modal="true"
                            class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100 dark:border-gray-700 animate-in fade-in zoom-in duration-200">

                                <div
                                    class="flex justify-between items-center px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b dark:border-gray-700">
                                    <h3
                                        class="font-bold text-gray-900 dark:text-gray-100 text-base flex items-center gap-2">
                                        📋 Detalle del Crédito <span
                                            class="text-emerald-600 font-mono">#{{ $loan->id }}</span>
                                    </h3>
                                    <button type="button"
                                        onclick="document.getElementById('modal-detalle-{{ $loan->id }}').classList.add('hidden')"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg font-bold">
                                        ✕
                                    </button>
                                </div>

                                <div class="p-6 space-y-3 text-sm max-h-[70vh] overflow-y-auto">
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">ID del crédito</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200 font-mono">#{{ $loan->id }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Fecha del crédito</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">{{ $fechaCredito }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Fecha próxima cuota</span>
                                        <span
                                            class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $fechaProxima }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Vencimiento del crédito</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">{{ $fechaVencimiento }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Cuotas vencidas</span>
                                        <span
                                            class="font-bold {{ $cuotasVencidas > 0 ? 'text-red-600' : 'text-gray-800 dark:text-gray-200' }}">{{ $cuotasVencidas }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Interés</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">{{ number_format($interesPorcentaje, 1) }}%</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Valor total intereses</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($valorTotalIntereses, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Cuotas pagadas</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $cuotasPagadas }} /
                                            {{ $totalCuotas }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Frecuencia de Pago</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">{{ $frecuenciaTexto }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Valor cuota</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($valorCuota, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Total prestado</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($capitalPrestado, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Prestado + intereses</span>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($montoTotalConInteres, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Total abonado</span>
                                        <span
                                            class="font-semibold text-emerald-600">${{ number_format($totalPagado, 2) }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between py-1 pt-2 font-bold text-base bg-gray-50 dark:bg-gray-900/30 px-3 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300">Saldo Total Restante</span>
                                        <span
                                            class="text-amber-600">${{ number_format($montoTotalConInteres - $totalPagado, 2) }}</span>
                                    </div>
                                </div>

                                <div
                                    class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-700 flex justify-end">
                                    <button type="button"
                                        onclick="document.getElementById('modal-detalle-{{ $loan->id }}').classList.add('hidden')"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl text-xs font-semibold transition">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No tiene créditos activos.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- ================= MODAL: FIAR MERCANCÍA ================= -->
    <div id="modalFiado" role="dialog" aria-modal="true"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl">
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Fiar Ropa o Mercancía</h3>
                <button type="button" onclick="document.getElementById('modalFiado').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <!-- Se removió el onsubmit por seguridad para asegurar que envíe los datos -->
            <form action="{{ route('debts.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="type" value="store_credit">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Concepto /
                        Descripción</label>
                    <input type="text" name="concept" placeholder="Ej. Pantalón de mezclilla talla 32" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Costo Total ($)</label>
                    <input type="number" step="0.01" name="total_amount" placeholder="0.00" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('modalFiado').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-semibold">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-semibold">Guardar
                        Fiado</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: NUEVO PRÉSTAMO EN EFECTIVO ================= -->
    <div id="modalPrestamo" role="dialog" aria-modal="true"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl">
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Otorgar Préstamo en Efectivo</h3>
                <button type="button" onclick="document.getElementById('modalPrestamo').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form action="{{ route('debts.store') }}" method="POST" class="mt-4" onsubmit="disableSubmit(this)">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="type" value="cash_loan">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Concepto / Motivo</label>
                    <input type="text" name="concept" placeholder="Ej. Préstamo personal" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capital ($)</label>
                        <input type="number" step="0.01" name="total_amount" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Interés (%)</label>
                        <input type="number" step="0.01" name="interest_rate" placeholder="Ej. 10" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Modalidad de Cobro</label>
                    <select name="loan_modal" id="loan_modal" onchange="toggleInstallments()"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="interest_only">Solo Interés (Abonos libres a capital)</option>
                        <option value="fixed_installments">Cuotas Fijas (Amortizado)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frecuencia</label>
                        <select name="payment_frequency"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="weekly">Semanal</option>
                            <option value="biweekly">Quincenal</option>
                            <option value="monthly">Mensual</option>
                        </select>
                    </div>
                    <div id="installments_div" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Núm. Cuotas</label>
                        <input type="number" name="installments_count"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de inicio</label>
                    <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('modalPrestamo').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-sm font-semibold">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-semibold">Guardar
                        Préstamo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= SECCIÓN: HISTORIAL DE ABONOS Y PAGOS ================= -->
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mt-6">
        <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-4 pb-3 border-b dark:border-gray-700">
            Historial de Abonos Realizados
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead>
                    <tr
                        class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="py-2 px-3">Fecha</th>
                        <th class="py-2 px-3">Concepto / Préstamo</th>
                        <th class="py-2 px-3 text-right">Monto Abonado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                    @php
                        $allPayments = $client->debts->flatMap->payments->sortByDesc('created_at');
                    @endphp

                    @forelse($allPayments as $payment)
                        <tr>
                            <td class="py-2.5 px-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $payment->debt->concept ?? 'Concepto general' }}
                                </span>
                                <span class="block text-[11px] text-indigo-500">
                                    {{ $payment->debt->type == 'cash_loan' ? 'Préstamo en Efectivo' : 'Mercancía Fiada' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <span class="font-bold text-green-600 dark:text-green-400">
                                        +${{ number_format($payment->amount, 2) }}
                                    </span>

                                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este abono? El dinero se sumará nuevamente a la deuda.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 transition"
                                            title="Borrar abono">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400 text-sm">
                                Este cliente aún no registra abonos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts de Control -->
    <script>
        function toggleInstallments() {
            var modal = document.getElementById('loan_modal').value;
            var div = document.getElementById('installments_div');
            if (modal === 'fixed_installments') {
                div.style.display = 'block';
            } else {
                div.style.display = 'none';
            }
        }

        function disableSubmit(form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Guardando...';
            }
        }
    </script>
</x-app-layout>