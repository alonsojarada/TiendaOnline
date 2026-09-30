<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center pl-12 sm:pl-0 gap-2">
            <h2 class="font-semibold text-sm sm:text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                Edo. Cta.: {{ $client->name }} <span
                    class="text-[10px] sm:text-sm text-indigo-500 font-normal inline">({{ $client->address ? '"' . $client->address . '"' : '' }})</span>
            </h2>
            <a href="{{ route('dashboard') }}"
                class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 text-[11px] sm:text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-xl shadow-xs transition-all duration-200 group">
                <span class="transform group-hover:-translate-x-0.5 transition-transform duration-200">&larr;</span>
                <span>Volver</span>
            </a>
        </div>
    </x-slot>

    <div class="py-3 sm:py-4">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-3 font-medium text-xs sm:text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 p-2.5 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ================= SECCIÓN SUPERIOR ================= -->
            <div class="bg-white dark:bg-gray-800 p-3 sm:p-4 rounded-xl shadow-xs border border-gray-100 dark:border-gray-700 mb-4 space-y-3">
                
                <!-- DATOS DE CONTACTO -->
                <div class="flex flex-wrap justify-between items-center text-[11px] sm:text-xs text-gray-500 pb-2 border-b border-gray-100 dark:border-gray-700/60">
                    <div>📞 <strong class="text-gray-800 dark:text-gray-200">{{ $client->phone ?? 'N/A' }}</strong></div>
                    <div class="truncate max-w-[220px]">📍 <strong class="text-gray-800 dark:text-gray-200">{{ $client->address ?? 'N/A' }}</strong></div>
                </div>

                <!-- 
                  EN MÓVIL: Se organiza en un grid de 2 columnas para las tarjetas principales, 
                  y el resto abarca el ancho completo formando exactamente 2 filas compactas.
                  EN MD+: Se convierte en un flex row (todo en una sola línea horizontal).
                -->
                <div class="flex flex-col md:flex-row items-stretch gap-2.5">
                    
                    <!-- Contenedor superior en móvil (Mercancía y Préstamos lado a lado) -->
                    <div class="grid grid-cols-2 md:flex md:flex-1 gap-2.5 contents-mobile">
                        <!-- Tarjeta Mercancía -->
                        <div class="flex-1 bg-gray-50 dark:bg-gray-900/40 p-2 sm:p-3 rounded-xl border border-gray-100 dark:border-gray-700/60">
                            <span class="text-[9px] sm:text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block mb-0.5">Mercancía</span>
                            <div class="text-xs sm:text-base md:text-lg font-black text-gray-900 dark:text-white">${{ number_format($totalMercanciaRestante, 2) }}</div>
                            <div class="text-[9px] sm:text-[10px] text-gray-500">Abonado: ${{ number_format($totalAbonosMercancia, 2) }}</div>
                        </div>

                        <!-- Tarjeta Préstamos -->
                        <div class="flex-1 bg-gray-50 dark:bg-gray-900/40 p-2 sm:p-3 rounded-xl border border-gray-100 dark:border-gray-700/60">
                            <span class="text-[9px] sm:text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block mb-0.5">Préstamos</span>
                            <div class="text-xs sm:text-base md:text-lg font-black text-gray-900 dark:text-white">${{ number_format($totalPrestamosRestante, 2) }}</div>
                            <div class="text-[9px] sm:text-[10px] text-gray-500">Abonado: ${{ number_format($totalAbonosPrestamos, 2) }}</div>
                        </div>
                    </div>

                    <!-- Adeudo Global y Botones (Fila 2 en móvil, parte de la misma fila en md+) -->
                    <div class="flex flex-col sm:flex-row md:flex-row items-stretch gap-2.5 flex-[1.7]">
                        <!-- Adeudo Global -->
                        <div class="flex-1 bg-rose-50/60 dark:bg-rose-950/25 p-2 sm:p-3 rounded-xl border border-rose-100 dark:border-rose-900/30 flex flex-row justify-between items-center min-w-0">
                            <div>
                                <span class="text-[9px] sm:text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-wider block">Adeudo Global</span>
                                <span class="text-[9px] text-rose-500 font-bold hidden sm:inline">Total pendiente</span>
                            </div>
                            <div class="text-xs sm:text-base md:text-xl font-black text-rose-700 dark:text-rose-300 truncate">${{ number_format($totalAdeudoGlobal, 2) }}</div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex flex-row gap-2 shrink-0">
                            <button type="button" onclick="document.getElementById('modalFiado').classList.remove('hidden')"
                                class="flex-1 sm:flex-initial px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[11px] sm:text-xs font-extrabold shadow-xs transition text-center flex items-center justify-center gap-1">
                                🛍️ <span>+ Fiar</span>
                            </button>
                            <button type="button" onclick="document.getElementById('modalPrestamo').classList.remove('hidden')"
                                class="flex-1 sm:flex-initial px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[11px] sm:text-xs font-extrabold shadow-xs transition text-center flex items-center justify-center gap-1">
                                💵 <span>+ Préstamo</span>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
            <!-- ================= FIN SECCIÓN SUPERIOR ================= -->

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

                <!-- ================= COLUMNA 1: MERCANCÍA FIADA ================= -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-3 sm:p-5">
                    <div class="flex justify-between items-center mb-3 pb-2 border-b dark:border-gray-700">
                        <h3 class="font-bold text-sm sm:text-lg text-gray-900 dark:text-gray-100">Ropa y Mercancía Fiada</h3>
                        <button type="button" onclick="document.getElementById('modalFiado').classList.remove('hidden')"
                            class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-[11px] sm:text-xs font-semibold uppercase tracking-wider transition">
                            + FIAR
                        </button>
                    </div>

                    @php
                        $creditosOrdenados = $storeCredits->sortByDesc(function ($credit) {
                            $ultimoPago = $credit->payments()->latest('payment_date')->first();
                            $fechaRef = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date) : \Carbon\Carbon::parse($credit->created_at);
                            return $fechaRef->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                        })->filter(function ($credit) {
                            $totalAbonado = $credit->payments->sum('amount');
                            return ($credit->total_amount - $totalAbonado) > 0;
                        });
                    @endphp

                    @forelse($creditosOrdenados as $credit)
                        @php
                            $ultimoPago = $credit->payments()->latest('payment_date')->first();
                            $fechaReferencia = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date) : \Carbon\Carbon::parse($credit->created_at);
                            $diasTranscurridos = $fechaReferencia->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                            $textoFecha = $ultimoPago ? 'Abono: ' . $fechaReferencia->format('d/m/y') : 'Fiado: ' . $fechaReferencia->format('d/m/y');

                            $totalAbonado = $credit->payments->sum('amount');
                            $saldoPendiente = $credit->total_amount - $totalAbonado;
                            $alertaInactivo = ($diasTranscurridos >= 7);
                        @endphp

                        <div
                            class="p-2.5 sm:p-3 rounded-xl mb-2.5 border transition {{ $alertaInactivo ? 'border-red-300 dark:border-red-800/80 bg-red-50/30 dark:bg-red-950/10' : 'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-700' }}">

                            <div class="flex justify-between items-start gap-2 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-xs sm:text-sm truncate">#{{ $credit->id }} - {{ $credit->concept }}</h4>
                                    <p
                                        class="text-[11px] sm:text-xs text-gray-700 dark:text-gray-300 font-medium flex items-center gap-1 flex-wrap">
                                        <span>{{ $textoFecha }}</span>
                                        <span>•</span>
                                        <span>${{ number_format($credit->total_amount, 2) }}</span>
                                        @if($alertaInactivo)
                                            <span class="font-bold text-red-600 dark:text-red-400 text-[10px] sm:text-xs">
                                                ({{ $diasTranscurridos }}d sin abonar)
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <a href="{{ route('store-details', ['id' => $credit->id, 'from' => 'cliente']) }}"
                                    class="px-2 py-1 bg-gray-100 hover:bg-emerald-50 dark:bg-gray-700 dark:hover:bg-emerald-950 text-gray-600 dark:text-gray-300 hover:text-emerald-600 rounded-lg text-[11px] sm:text-xs font-semibold flex items-center gap-0.5 transition shrink-0"
                                    title="Ir a página completa">
                                    Detalle ➔
                                </a>
                            </div>

                            <div
                                class="flex justify-between items-center mt-2 pt-2 border-t border-gray-200/60 dark:border-gray-700/60">
                                <div class="flex items-center gap-3 sm:gap-6">
                                    <div>
                                        <span class="text-[9px] sm:text-[10px] uppercase text-green-600 font-bold block">Abonado</span>
                                        <span class="text-xs sm:text-sm font-bold text-green-600 dark:text-green-400">
                                            ${{ number_format($totalAbonado, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-[9px] sm:text-[10px] uppercase text-amber-500 font-bold block">Debe</span>
                                        <span class="text-xs sm:text-sm font-bold text-amber-600 dark:text-amber-400">
                                            ${{ number_format($saldoPendiente, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <button type="button"
                                    onclick="abrirModalAbono('{{ route('payments.store', $credit->id) }}', '{{ $saldoPendiente }}')"
                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-[11px] sm:text-xs font-semibold rounded-md uppercase tracking-wider transition shadow-sm">
                                    Abonar
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm text-center py-4">No tiene mercancía fiada pendiente.</p>
                    @endforelse
                </div>


                <!-- ================= COLUMNA 2: PRÉSTAMOS EN EFECTIVO ================= -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-3 sm:p-5">
                    <div class="flex justify-between items-center mb-3 pb-2 border-b dark:border-gray-700">
                        <h3 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100 flex items-center gap-1.5">
                            📈 Préstamos <span
                                class="text-[11px] sm:text-xs bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 px-1.5 py-0.5 rounded-full">({{ $cashLoans->count() }})</span>
                        </h3>
                        <button type="button"
                            onclick="document.getElementById('modalPrestamo').classList.remove('hidden')"
                            class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-[11px] sm:text-xs font-semibold uppercase tracking-wider transition">
                            + NUEVO
                        </button>
                    </div>

                    @php
                        $prestamosOrdenados = $cashLoans->filter(function ($loan) {
                            $totalPagado = $loan->payments->sum('amount');
                            $montoTotalConInteres = $loan->total_amount;
                            return ($montoTotalConInteres - $totalPagado) > 0;
                        })->sort(function ($a, $b) {
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

                        <div
                            class="bg-white dark:bg-gray-800 shadow-sm hover:shadow-md rounded-xl p-2.5 sm:p-3 mb-2.5 border {{ $cuotasVencidas > 0 ? 'border-red-300 dark:border-red-800/80 bg-red-50/30 dark:bg-red-950/10' : 'border-gray-100 dark:border-gray-700' }} transition relative">

                            <div class="flex justify-between items-center mb-1.5">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span
                                        class="px-1.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-[11px] font-mono font-bold shrink-0">
                                        #{{ $loan->id }}
                                    </span>
                                    <div class="min-w-0">
                                        <span class="text-base sm:text-lg font-black text-gray-900 dark:text-white">
                                            ${{ number_format($montoTotalConInteres, 2) }}
                                        </span>
                                        <span class="text-[11px] text-gray-400 font-medium ml-0.5 truncate">({{ $loan->concept }})</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 shrink-0">
                                    <button type="button"
                                        onclick="document.getElementById('modal-detalle-{{ $loan->id }}').classList.remove('hidden')"
                                        class="px-1.5 py-1 bg-gray-100 hover:bg-emerald-50 dark:bg-gray-700 dark:hover:bg-emerald-950 text-gray-600 dark:text-gray-300 hover:text-emerald-600 rounded-lg text-[11px] sm:text-xs font-semibold flex items-center gap-0.5 transition"
                                        title="Vista Rápida">
                                        👁️ <span class="hidden sm:inline">Info.</span>
                                    </button>

                                    <a href="{{ route('debts.details', ['id' => $loan->id, 'from' => 'cliente']) }}"
                                        class="px-1.5 py-1 bg-gray-100 hover:bg-emerald-50 dark:bg-gray-700 dark:hover:bg-emerald-950 text-gray-600 dark:text-gray-300 hover:text-emerald-600 rounded-lg text-[11px] sm:text-xs font-semibold flex items-center gap-0.5 transition"
                                        title="Ir a página completa">
                                        Detalle ➔
                                    </a>
                                </div>
                            </div>

                            <div class="mb-1.5">
                                <div class="w-full bg-gray-50 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-500"
                                        style="width: {{ $porcentajePagado }}%;"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-[11px] sm:text-xs px-0.5 mb-1.5">
                                <span class="text-gray-500 dark:text-gray-400">
                                    Pagado: <span class="font-medium text-green-600">${{ number_format($totalPagado, 2) }}</span>
                                </span>
                                <div>
                                    @php $restante = $montoTotalConInteres - $totalPagado; @endphp
                                    <span class="font-bold text-amber-600 dark:text-amber-400 text-sm sm:text-base tracking-tight">
                                        Restante: ${{ number_format($restante, 2) }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-4 gap-0.5 pt-1.5 border-t border-gray-100 dark:border-gray-700 text-center items-center text-[11px] sm:text-xs">
                                <div>
                                    <span class="block text-gray-400 text-[9px] uppercase font-bold tracking-tight">Cuota</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">${{ number_format($valorCuota, 2) }}</span>
                                </div>
                                <div class="border-l border-gray-100 dark:border-gray-700">
                                    <span class="block text-gray-400 text-[9px] uppercase font-bold tracking-tight">Avance</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $cuotasPagadas }}/{{ $totalCuotas > 0 ? $totalCuotas : 'N/A' }}</span>
                                </div>
                                <div class="border-l border-gray-100 dark:border-gray-700">
                                    <span class="block text-gray-400 text-[9px] uppercase font-bold tracking-tight">Frec.</span>
                                    <span class="font-semibold text-gray-700 dark:text-gray-200 truncate block">{{ $frecuenciaTexto }}</span>
                                </div>
                                <div class="border-l border-gray-100 dark:border-gray-700 flex justify-center">
                                    @if($cuotasVencidas > 0)
                                        <span class="px-1.5 py-0.5 bg-red-600 text-white dark:bg-red-700 rounded font-black text-[10px] shadow-xs flex items-center gap-0.5">
                                            🚨 {{ $cuotasVencidas }}V
                                        </span>
                                    @else
                                        <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 rounded font-bold text-[10px]">
                                            Al corriente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- MODAL DE VISTA RÁPIDA -->
                        <div id="modal-detalle-{{ $loan->id }}" role="dialog" aria-modal="true"
                            class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100 dark:border-gray-700 animate-in fade-in zoom-in duration-200">
                                <div class="flex justify-between items-center px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b dark:border-gray-700">
                                    <h3 class="font-bold text-gray-900 dark:text-gray-100 text-base flex items-center gap-2">
                                        📋 Detalle del Crédito <span class="text-emerald-600 font-mono">#{{ $loan->id }}</span>
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
                                        <span class="font-semibold text-gray-800 dark:text-gray-200 font-mono">#{{ $loan->id }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Fecha del crédito</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $fechaCredito }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Fecha próxima cuota</span>
                                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $fechaProxima }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Vencimiento del crédito</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $fechaVencimiento }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Cuotas vencidas</span>
                                        <span class="font-bold {{ $cuotasVencidas > 0 ? 'text-red-600' : 'text-gray-800 dark:text-gray-200' }}">{{ $cuotasVencidas }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Interés</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ number_format($interesPorcentaje, 1) }}%</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Valor total intereses</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($valorTotalIntereses, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Cuotas pagadas</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $cuotasPagadas }} / {{ $totalCuotas }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Frecuencia de Pago</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $frecuenciaTexto }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Valor cuota</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($valorCuota, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Total prestado</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($capitalPrestado, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Prestado + intereses</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($montoTotalConInteres, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b dark:border-gray-700/50">
                                        <span class="text-gray-500">Total abonado</span>
                                        <span class="font-semibold text-emerald-600">${{ number_format($totalPagado, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 pt-2 font-bold text-base bg-gray-50 dark:bg-gray-900/30 px-3 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300">Saldo Total Restante</span>
                                        <span class="text-amber-600">${{ number_format($montoTotalConInteres - $totalPagado, 2) }}</span>
                                    </div>
                                </div>

                                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 border-t dark:border-gray-700 flex justify-end">
                                    <button type="button"
                                        onclick="document.getElementById('modal-detalle-{{ $loan->id }}').classList.add('hidden')"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl text-xs font-semibold transition">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm text-center py-4">No tiene créditos activos.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL: FIAR MERCANCÍA -->
    <div id="modalFiado" role="dialog" aria-modal="true"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-5 sm:p-6 shadow-xl">
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">Fiar Ropa o Mercancía</h3>
                <button type="button" onclick="document.getElementById('modalFiado').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form action="{{ route('debts.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="type" value="store_credit">

                <div class="mb-3">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Concepto / Descripción</label>
                    <input type="text" name="concept" placeholder="Ej. Pantalón de mezclilla talla 32" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                </div>

                <div class="mb-3">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Costo Total ($)</label>
                    <input type="number" step="0.01" name="total_amount" placeholder="0.00" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                </div>
                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Fecha de Movimiento</label>
                    <input type="date" name="created_at" value="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-hidden text-gray-800 dark:text-gray-200">
                </div>

                <div class="flex justify-end space-x-2 sm:space-x-3 mt-5">
                    <button type="button" onclick="document.getElementById('modalFiado').classList.add('hidden')"
                        class="px-3 sm:px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs sm:text-sm font-semibold">Cancelar</button>
                    <button type="submit"
                        class="px-3 sm:px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-xs sm:text-sm font-semibold">Guardar Fiado</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: NUEVO PRÉSTAMO EN EFECTIVO -->
    <div id="modalPrestamo" role="dialog" aria-modal="true"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-5 sm:p-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">Otorgar Préstamo en Efectivo</h3>
                <button type="button" onclick="document.getElementById('modalPrestamo').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form action="{{ route('debts.store') }}" method="POST" class="mt-4" onsubmit="disableSubmit(this)">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="type" value="cash_loan">
                <input type="hidden" name="created_at" value="{{ date('Y-m-d H:i:s') }}">

                <div class="mb-3">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Concepto / Motivo</label>
                    <input type="text" name="concept" placeholder="Ej. Préstamo personal" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Capital ($)</label>
                        <input type="number" step="0.01" name="total_amount" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Interés (%)</label>
                        <input type="number" step="0.01" name="interest_rate" placeholder="Ej. 10" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Modalidad de Cobro</label>
                    <select name="loan_modal" id="loan_modal" onchange="toggleInstallments()"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                        <option value="interest_only">Solo Interés (Abonos libres)</option>
                        <option value="fixed_installments">Cuotas Fijas (Amortizado)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Frecuencia</label>
                        <select name="payment_frequency"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                            <option value="weekly">Semanal</option>
                            <option value="biweekly">Quincenal</option>
                            <option value="monthly">Mensual</option>
                        </select>
                    </div>
                    <div id="installments_div" style="display: none;">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Núm. Cuotas</label>
                        <input type="number" name="installments_count"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de inicio</label>
                    <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-sm">
                </div>

                <div class="flex justify-end space-x-2 sm:space-x-3 mt-5">
                    <button type="button" onclick="document.getElementById('modalPrestamo').classList.add('hidden')"
                        class="px-3 sm:px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs sm:text-sm font-semibold">Cancelar</button>
                    <button type="submit"
                        class="px-3 sm:px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs sm:text-sm font-semibold">Guardar Préstamo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FLOTANTE DE ABONO -->
    <div id="paymentModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl max-w-md w-full p-5 sm:p-6 shadow-2xl relative">
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">Registrar Nuevo Abono</h3>
                <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    ✕
                </button>
            </div>

            <form id="formAbonoModal" method="POST" class="mt-3 space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">Monto del Abono ($)</label>
                    <input type="number" step="0.01" id="inputMaxAmount" name="amount" required
                        class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs sm:text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">Fecha</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                        class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs sm:text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">Notas / Observación (Opcional)</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs sm:text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                        placeholder="Ej. Abonó en efectivo..."></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100 dark:border-gray-800 mt-4">
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                        class="px-3.5 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-[11px] sm:text-xs font-semibold uppercase tracking-wider transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-[11px] sm:text-xs font-semibold uppercase tracking-wider transition shadow-sm">
                        Guardar Abono
                    </button>
                </div>
            </form>
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

        function abrirModalAbono(urlAccion, saldoRestante) {
            const form = document.getElementById('formAbonoModal');
            form.action = urlAccion;

            const inputAmount = document.getElementById('inputMaxAmount');
            inputAmount.max = saldoRestante;
            inputAmount.value = '';
            inputAmount.placeholder = `Máx: $${saldoRestante}`;

            document.getElementById('paymentModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>