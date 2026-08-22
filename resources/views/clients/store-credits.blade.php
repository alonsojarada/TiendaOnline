<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Botón Volver (Se oculta al imprimir) -->
            <div class="mb-4 flex justify-end print:hidden">
                <a href="{{ route('clients.show', $credit->client_id) }}"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                    ← Volver al perfil del cliente
                </a>
            </div>

            <!-- Título Principal -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    Detalle del Crédito: #{{ $credit->id }}-{{ strtoupper(substr($credit->client->name ?? 'X', 0, 3)) }}
                </h2>
            </div>

            <!-- Tarjeta Contenedora Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 relative print:shadow-none print:p-0 print:bg-white">

                <!-- Cabecera para Impresión (Solo visible al imprimir) -->
                <div class="hidden print:block mb-6 border-b pb-4 text-center">
                    <h1 class="text-xl font-bold text-gray-900">Ropa y Novedades Nena</h1>
                    <p class="text-xs text-gray-600">Estado de Cuenta de Crédito / Fiado</p>
                    <p class="text-xs text-gray-500 mt-1">Fecha de emisión: {{ date('d/m/Y') }}</p>
                </div>

                <!-- Cabecera de la tarjeta: Cliente, Concepto, Botón Eliminar y Botón Exportar PDF -->
                <div class="flex justify-between items-start border-b pb-4 mb-6 dark:border-gray-700">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Cliente</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            {{ $credit->client->name ?? 'Sin cliente' }}
                        </h3>
                    </div>
                    <div class="text-right flex items-center gap-3">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Concepto</span>
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $credit->concept }}</span>
                        </div>

                        <!-- Botón Exportar PDF -->
                        <a href="{{ route('credits.export-pdf', $credit->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md text-xs font-semibold transition print:hidden shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Exportar PDF
                        </a>

                        <!-- Botón Eliminar Crédito -->
                        <form action="{{ route('debts.destroy', $credit->id) }}" method="POST"
                            onsubmit="return confirm('¿Estás seguro de eliminar este crédito?');" class="print:hidden">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-md text-xs font-semibold border border-red-200 transition">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                @php
                    $totalAbonado = $credit->payments->sum('amount');
                    $saldoRestante = $credit->total_amount - $totalAbonado;
                    $estaLiquidado = $saldoRestante <= 0;
                @endphp

                <!-- Grid de Información General -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-8 text-sm mb-6 text-gray-600 dark:text-gray-300">
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700/50 pb-2">
                        <span class="text-gray-400">Fecha del crédito</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($credit->created_at)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700/50 pb-2">
                        <span class="text-gray-400">Total Artículos / Fiado</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">${{ number_format($credit->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700/50 pb-2">
                        <span class="text-gray-400">Total abonado</span>
                        <span class="font-semibold text-green-600 dark:text-green-400">${{ number_format($totalAbonado, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700/50 pb-2">
                        <span class="text-gray-400">Saldo Restante</span>
                        <span class="font-bold text-amber-600 dark:text-amber-400">${{ number_format($saldoRestante, 2) }}</span>
                    </div>
                </div>

                <!-- Estado de Liquidación y Botón de Abonar -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8 print:hidden">
                    @if($estaLiquidado)
                        <div class="p-4 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-3 w-full">
                            <span class="bg-green-600 text-white p-1.5 rounded-full text-xs">✓</span>
                            <div>
                                <h4 class="text-sm font-bold text-green-800 dark:text-green-300">CRÉDITO LIQUIDADO</h4>
                                <p class="text-xs text-green-600 dark:text-green-400">Este crédito ha sido pagado en su totalidad.</p>
                            </div>
                        </div>
                    @else
                        <div></div>
                    @endif

                    @unless($estaLiquidado)
                        <button type="button" onclick="document.getElementById('paymentModal').classList.remove('hidden')"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold uppercase tracking-widest rounded-xl shadow-sm transition shrink-0">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Registrar Abono
                        </button>
                    @endunless
                </div>

                <!-- SECCIÓN INFERIOR EN DOS COLUMNAS -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

                    <!-- COLUMNA 1: Historial de Abonos -->
                    <div class="print:hidden">
                        <!-- Título Centrado -->
                        <div class="mb-4 text-center">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200">Historial de Abonos</h3>
                        </div>

                        <div class="space-y-3">
                            @forelse($credit->payments as $index => $payment)
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Abono #{{ $index + 1 }}</span>
                                        <div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block">({{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }})</span>
                                            @if($payment->notes)
                                                <span class="text-[11px] text-gray-500 italic">"{{ $payment->notes }}"</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                            ${{ number_format($payment->amount, 2) }}
                                        </span>

                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar este abono?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded text-[10px] font-bold uppercase transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 bg-gray-50 dark:bg-gray-700/20 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Aún no se han registrado abonos.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- COLUMNA 2: Estado de Cuenta (Diseño plano, sin fondos ni bordes pesados) -->
                    <div class="lg:col-span-1 print:col-span-2">
                        <!-- Título Centrado -->
                        <div class="mb-4 text-center">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200">Estado de Cuenta (Tabla)</h3>
                        </div>

                        <!-- Tabla Estilo Minimalista / Texto Plano -->
                        <div class="overflow-hidden text-xs">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-gray-400 dark:text-gray-500 border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-2 px-1 font-semibold">Fecha</th>
                                        <th class="py-2 px-1 font-semibold">Concepto / Detalle</th>
                                        <th class="py-2 px-1 font-semibold text-right">Abono</th>
                                        <th class="py-2 px-1 font-semibold text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">

                                    <!-- Fila Inicial / Apertura -->
                                    <tr class="font-medium">
                                        <td class="py-2.5 px-1">{{ \Carbon\Carbon::parse($credit->created_at)->format('d/m/Y') }}</td>
                                        <td class="py-2.5 px-1">Crédito Inicial ({{ $credit->concept }})</td>
                                        <td class="py-2.5 px-1 text-right text-gray-400">-</td>
                                        <td class="py-2.5 px-1 text-right font-semibold text-gray-900 dark:text-gray-100">
                                            ${{ number_format($credit->total_amount, 2) }}
                                        </td>
                                    </tr>

                                    @php
                                        $saldoContable = $credit->total_amount;
                                    @endphp

                                    <!-- Filas de Abonos -->
                                    @forelse($credit->payments->sortBy('created_at') as $index => $payment)
                                        @php
                                            $saldoContable = max(0, $saldoContable - $payment->amount);
                                        @endphp
                                        <tr>
                                            <td class="py-2.5 px-1">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                            <td class="py-2.5 px-1">
                                                Abono #{{ $index + 1 }}
                                                @if($payment->notes)
                                                    <span class="block text-[10px] text-gray-400 italic">"{{ $payment->notes }}"</span>
                                                @endif
                                            </td>
                                            <td class="py-2.5 px-1 text-right text-green-600 dark:text-green-400 font-medium">
                                                ${{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="py-2.5 px-1 text-right font-medium text-gray-800 dark:text-gray-200">
                                                ${{ number_format($saldoContable, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 px-1 text-center text-gray-400 italic">
                                                Sin movimientos registrados.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                                <tfoot>
                                    <tr class="border-t border-gray-200 dark:border-gray-700 font-bold text-gray-800 dark:text-gray-200">
                                        <td colspan="3" class="py-3 px-1 text-right">Saldo Pendiente Actual:</td>
                                        <td class="py-3 px-1 text-right text-amber-600 dark:text-amber-400 text-sm">
                                            ${{ number_format($saldoRestante, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- MODAL: Registrar Abono -->
    <div id="paymentModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 print:hidden">
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Registrar Nuevo Abono</h3>
                <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    ✕
                </button>
            </div>

            <form action="{{ route('payments.store', $credit->id) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="credit_id" value="{{ $credit->id }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">Monto del Abono ($)</label>
                    <input type="number" step="0.01" max="{{ $saldoRestante }}" name="amount" required
                        placeholder="Máx: ${{ $saldoRestante }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">Fecha</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">Notas / Observación (Opcional)</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                        placeholder="Ej. Abonó en efectivo..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-semibold uppercase tracking-wider transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold uppercase tracking-wider transition shadow-sm">
                        Guardar Abono
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>