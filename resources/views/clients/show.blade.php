<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $client->name }} <span
                    class="text-sm text-indigo-500 font-normal">({{ $client->alias ? '"' . $client->alias . '"' : 'Sin alias' }})</span>
            </h2>
            <a href="{{ route('clients.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Volver al catálogo
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

            <!-- Datos Generales del Cliente -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6 text-gray-700 dark:text-gray-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><strong>Teléfono:</strong> {{ $client->phone ?? 'No registrado' }}</div>
                    <div><strong>Dirección:</strong> {{ $client->address ?? 'No registrada' }}</div>
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

                    @forelse($storeCredits as $credit)
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg mb-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $credit->concept }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Costo original:
                                        ${{ number_format($credit->total_amount, 2) }}</p>
                                </div>
                                <span class="text-sm font-bold text-red-600 dark:text-red-400">
                                    Debe: ${{ number_format($credit->total_amount - $credit->payments->sum('amount'), 2) }}
                                </span>
                            </div>

                            <!-- Formulario de Abono Rápido -->
                            <form action="{{ route('payments.store', $credit->id) }}" method="POST" class="mt-3 flex gap-2">
                                @csrf
                                <input type="number" step="0.01" name="amount" placeholder="$ Monto a abonar" required
                                    class="w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="submit"
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-md uppercase tracking-wider">
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
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b dark:border-gray-700">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Préstamos en Efectivo</h3>
                        <button type="button"
                            onclick="document.getElementById('modalPrestamo').classList.remove('hidden')"
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs font-semibold uppercase tracking-wider transition">
                            + Nuevo Préstamo
                        </button>
                    </div>

                    @forelse($cashLoans as $loan)
                        @php
                            $totalPagado = $loan->payments->sum('amount');
                            $montoTotalConInteres = $loan->total_amount;
                            $porcentajePagado = $montoTotalConInteres > 0 ? min(100, ($totalPagado / $montoTotalConInteres) * 100) : 0;
                            
                            $valorCuota = $loan->installments->first()->amount_due ?? 0;
                            $totalCuotas = $loan->installments->count();
                            $cuotasPagadas = $loan->installments->where('status', 'paid')->count();
                            
                            $frecuenciaTexto = 'Libre';
                            if ($loan->payment_frequency == 'weekly') $frecuenciaTexto = 'Semanal';
                            if ($loan->payment_frequency == 'biweekly') $frecuenciaTexto = 'Quincenal';
                            if ($loan->payment_frequency == 'monthly') $frecuenciaTexto = 'Mensual';

                            $capitalPrestado = $loan->capital_amount ?? ($montoTotalConInteres / (1 + ($loan->interest_rate / 100))); 
                            $valorInteresTotal = $montoTotalConInteres - $capitalPrestado;
                            $saldoTotalRestante = max(0, $montoTotalConInteres - $totalPagado);
                        @endphp

                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-5 mb-5 border border-gray-100 dark:border-gray-700">

                            <!-- Cabecera de la tarjeta con Botón de Información -->
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/50 rounded-xl text-emerald-600">
                                        💵
                                    </div>
                                    <div>
                                        <span class="text-2xl font-black text-gray-900 dark:text-white">
                                            ${{ number_format($montoTotalConInteres, 2) }}
                                        </span>
                                        <span class="block text-xs text-gray-400 font-medium">{{ $loan->concept }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="document.getElementById('modalResumen-{{ $loan->id }}').classList.remove('hidden')"
                                        class="px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-700 dark:text-gray-300 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Ver detalles completos">
                                        ℹ️ <span class="hidden sm:inline">Detalles</span>
                                    </button>

                                    <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-mono font-bold tracking-wider">
                                        #{{ $loan->id }}
                                    </span>
                                </div>
                            </div>

                            <!-- Barra de Progreso -->
                            <div class="mb-2">
                                <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-500"
                                        style="width: {{ $porcentajePagado }}%;"></div>
                                </div>
                            </div>

                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-4 font-medium">
                                <span>Pagado: ${{ number_format($totalPagado, 2) }}</span>
                                <span>De ${{ number_format($montoTotalConInteres, 2) }}</span>
                            </div>

                            <!-- Métricas Grid -->
                            <div class="grid grid-cols-3 gap-2 py-3 border-t border-b border-gray-100 dark:border-gray-700 text-center mb-4">
                                <div>
                                    <span class="block text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Valor cuota</span>
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200">${{ number_format($valorCuota, 2) }}</span>
                                </div>
                                <div class="border-x border-gray-100 dark:border-gray-700">
                                    <span class="block text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Cuotas</span>
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $cuotasPagadas }} / {{ $totalCuotas > 0 ? $totalCuotas : 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="block text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Interés</span>
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-200">% {{ number_format($loan->interest_rate, 1) }}</span>
                                </div>
                            </div>

                            <!-- Frecuencia y Fecha de Inicio -->
                            <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 mb-4">
                                <div>
                                    <span class="block text-[10px] uppercase text-gray-400 font-semibold">Frecuencia de pagos</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">📅 {{ $frecuenciaTexto }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] uppercase text-gray-400 font-semibold">Inicio</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200 text-sm">
                                        {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Calendario de Cuotas (Desplegable) -->
                            @if($loan->loan_modal == 'fixed_installments' && $loan->installments && $loan->installments->count() > 0)
                                <div class="mt-3">
                                    <button type="button" onclick="toggleDetails('cuotas-{{ $loan->id }}')"
                                        class="w-full flex justify-between items-center px-3 py-2 bg-gray-50 dark:bg-gray-900/40 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 transition border border-gray-100 dark:border-gray-700">
                                        <span>📅 Ver calendario de cuotas</span>
                                        <span id="arrow-cuotas-{{ $loan->id }}">▼</span>
                                    </button>

                                    <div id="cuotas-{{ $loan->id }}"
                                        class="hidden mt-2 space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                        @foreach($loan->installments as $installment)
                                            <div
                                                class="flex justify-between items-center py-1.5 px-2.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 text-xs">
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    Cuota #{{ $installment->installment_number }}
                                                    <span class="text-[10px] text-gray-400">({{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/y') }})</span>
                                                </span>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-gray-900 dark:text-white">${{ number_format($installment->amount_due, 2) }}</span>
                                                    @if($installment->status == 'paid')
                                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 rounded-md text-[10px] font-bold">PAGADA</span>
                                                    @else
                                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 rounded-md text-[10px] font-bold">PENDIENTE</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Formulario de Abono Rápido y Botón Eliminar -->
                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center gap-2">
                                <form action="{{ route('payments.store', $loan->id) }}" method="POST"
                                    class="flex gap-2 w-full">
                                    @csrf
                                    <input type="number" step="0.01" name="amount" placeholder="$ Monto a abonar" required
                                        class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                    <button type="submit"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl uppercase tracking-wider transition shadow-sm">
                                        Abonar
                                    </button>
                                </form>

                                <form action="{{ route('debts.destroy', $loan->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este préstamo por completo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2.5 text-gray-400 hover:text-red-500 rounded-xl bg-gray-50 dark:bg-gray-700 transition"
                                        title="Eliminar Préstamo">
                                        🗑️
                                    </button>
                                </form>
                            </div>

                        </div>

                        <!-- ================= MODAL ESTILO RESUMEN (TIPO IMAGEN) ================= -->
                        <div id="modalResumen-{{ $loan->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200">
                                
                                <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700 mb-4">
                                    <h3 class="text-base font-black tracking-wide text-gray-900 dark:text-white uppercase flex items-center gap-2">
                                        📊 Resumen del Crédito
                                    </h3>
                                    <button type="button" onclick="document.getElementById('modalResumen-{{ $loan->id }}').classList.add('hidden')"
                                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 hover:text-gray-700 dark:hover:text-white font-bold">✕</button>
                                </div>

                                <div class="space-y-2.5 text-xs font-medium">
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">ID del crédito</span>
                                        <span class="font-bold font-mono text-gray-900 dark:text-white">{{ $loan->id }}-VJB</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Fecha del crédito</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Fecha próxima cuota</span>
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            @php
                                                $nextInstallment = $loan->installments->where('status', 'pending')->first();
                                            @endphp
                                            {{ $nextInstallment ? \Carbon\Carbon::parse($nextInstallment->due_date)->format('d M Y') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Cuotas vencidas</span>
                                        <span class="font-bold text-gray-900 dark:text-white">0</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Interés</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($loan->interest_rate, 1) }} %</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Valor total intereses</span>
                                        <span class="font-bold text-gray-900 dark:text-white">${{ number_format($valorInteresTotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Cuotas pagadas</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $cuotasPagadas }} / {{ $totalCuotas > 0 ? $totalCuotas : 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Frecuencia de Pago</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $frecuenciaTexto }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Valor cuota</span>
                                        <span class="font-bold text-gray-900 dark:text-white">${{ number_format($valorCuota, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Total prestado (Capital)</span>
                                        <span class="font-bold text-gray-900 dark:text-white">${{ number_format($capitalPrestado, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Prestado + intereses</span>
                                        <span class="font-bold text-gray-900 dark:text-white">${{ number_format($montoTotalConInteres, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-gray-50 dark:border-gray-700/50">
                                        <span class="text-gray-400">Total abonado</span>
                                        <span class="font-bold text-green-600 dark:text-green-400">${{ number_format($totalPagado, 2) }}</span>
                                    </div>

                                    <div class="mt-4 pt-3 border-t-2 border-gray-100 dark:border-gray-700 space-y-2">
                                        <div class="flex justify-between py-0.5 text-xs">
                                            <span class="text-gray-500 font-semibold">Deuda a Capital</span>
                                            <span class="font-bold text-gray-900 dark:text-white">${{ number_format(max(0, $capitalPrestado - $totalPagado), 2) }}</span>
                                        </div>
                                        <div class="flex justify-between py-0.5 text-xs">
                                            <span class="text-gray-500 font-semibold">Saldo Total Restante</span>
                                            <span class="font-black text-sm text-emerald-600 dark:text-emerald-400">${{ number_format($saldoTotalRestante, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="button" onclick="document.getElementById('modalResumen-{{ $loan->id }}').classList.add('hidden')"
                                        class="w-full py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl text-xs font-bold uppercase tracking-wider transition">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>

                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No tiene préstamos activos.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- ================= MODAL: FIAR MERCANCÍA ================= -->
    <div id="modalFiado"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl">
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Fiar Ropa o Mercancía</h3>
                <button type="button" onclick="document.getElementById('modalFiado').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form action="{{ route('debts.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="type" value="store_credit">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Concepto / Descripción del
                        artículo</label>
                    <input type="text" name="concept" placeholder="Ej. Pantalón de mezclilla talla 32" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Costo Total ($)</label>
                    <input type="number" step="0.01" name="total_amount" required
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
    <div id="modalPrestamo"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl">
            <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Otorgar Préstamo en Efectivo</h3>
                <button type="button" onclick="document.getElementById('modalPrestamo').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form action="{{ route('debts.store') }}" method="POST" class="mt-4">
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de inicio del
                        préstamo</label>
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

    <!-- Script para ocultar/mostrar cuotas -->
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

        function toggleDetails(id) {
            const element = document.getElementById(id);
            const arrow = document.getElementById('arrow-' + id);

            element.classList.toggle('hidden');
            arrow.innerText = element.classList.contains('hidden') ? '▼' : '▲';
        }
    </script>
</x-app-layout>