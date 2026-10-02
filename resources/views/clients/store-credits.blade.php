<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex flex-row items-center justify-between gap-2">
                <h2 class="font-semibold text-base sm:text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                    Detalle del Crédito: <span class="text-indigo-500">#{{ $credit->id }}-MERCANCIA</span>
                </h2>
                <div class="shrink-0">
                    <a href="{{ request('from') === 'historial' ? route('reports.historial-cuentas') : route('clients.show', $credit->client_id) }}"
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

            @php
                $totalAbonado = $credit->payments->sum('amount');
                $saldoRestante = max(0, $credit->total_amount - $totalAbonado);
                $estaLiquidado = $saldoRestante <= 0;
            @endphp

            <!-- Tarjeta Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-3 sm:p-5 md:p-6 border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200 relative print:shadow-none print:p-0 print:bg-white">

                <!-- Cabecera para Impresión -->
                <div class="hidden print:block mb-6 border-b pb-4 text-center">
                    <h1 class="text-xl font-bold text-gray-900">Ropa y Novedades Nena</h1>
                    <p class="text-xs text-gray-600">Estado de Cuenta de Crédito / Fiado</p>
                    <p class="text-xs text-gray-500 mt-1">Fecha de emisión: {{ date('d/m/Y') }}</p>
                </div>

                <!-- ENCABEZADO DE CLIENTE Y ACCIONES -->
                <div class="flex justify-between items-center pb-2.5 sm:pb-3 border-b dark:border-gray-700 gap-2 mb-2.5 sm:mb-3">
                    <div class="min-w-0 flex-1">
                        <span class="text-[9px] sm:text-xs text-gray-400 uppercase font-bold tracking-wider">Cliente</span>
                        <h3 class="text-xs sm:text-lg font-bold text-gray-900 dark:text-white truncate">
                            {{ $credit->client->name ?? 'Sin cliente' }}
                        </h3>
                    </div>

                    <div class="hidden sm:block text-right">
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Concepto</span>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $credit->concept }}</p>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0 print:hidden">
                        <a href="{{ route('credits.export-pdf', $credit->id) }}"
                            class="px-2 py-1 sm:px-3 sm:py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-md sm:rounded-xl text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-xs">
                            📄 <span>Exportar PDF</span>
                        </a>

                        @unless($estaLiquidado)
                            <form action="{{ route('debts.destroy', $credit->id) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de eliminar este crédito?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 sm:px-3 sm:py-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/50 dark:text-red-300 rounded-md sm:rounded-xl text-[10px] sm:text-xs font-bold transition flex items-center gap-1 shadow-xs">
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
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Fecha del crédito</span>
                            <span class="font-bold text-gray-900 dark:text-white leading-tight text-right">
                                {{ \Carbon\Carbon::parse($credit->created_at)->format('d M Y') }}
                            </span>
                        </div>

                        <div class="flex justify-between sm:flex-row sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Total Artículos / Fiado</span>
                            <span class="font-bold text-gray-900 dark:text-white leading-tight">
                                ${{ number_format($credit->total_amount, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1 sm:space-y-0.5">
                        <div class="flex justify-between sm:flex-row sm:justify-between sm:items-center py-0.5 border-b border-gray-200/40 dark:border-gray-700/40">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Total abonado</span>
                            <span class="font-bold text-green-600 dark:text-green-400 leading-tight">${{ number_format($totalAbonado, 2) }}</span>
                        </div>
                        <div class="flex justify-between sm:flex-row sm:justify-between sm:items-center py-0.5">
                            <span class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Saldo Restante</span>
                            <span class="font-black text-xs md:text-sm lg:text-lg text-amber-600 dark:text-amber-400 leading-tight">${{ number_format($saldoRestante, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($estaLiquidado)
                    <div class="mb-3 sm:mb-4 p-2.5 sm:p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 min-w-[24px] sm:min-w-[28px] rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                ✓
                            </div>
                            <div>
                                <h5 class="font-bold text-[11px] sm:text-xs text-emerald-800 dark:text-emerald-300 uppercase tracking-wide">Crédito Liquidado</h5>
                                <p class="text-[11px] sm:text-xs text-emerald-600 dark:text-emerald-400">Este crédito ha sido pagado en su totalidad y se encuentra en modo informativo.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- BOTONES DE ACCIÓN -->
                    <div class="mb-3 sm:mb-4 flex flex-col sm:flex-row sm:flex-wrap justify-end gap-2 print:hidden">
                        <button type="button" onclick="document.getElementById('paymentModal').classList.remove('hidden')"
                            class="w-full sm:w-auto justify-center px-3 py-2 sm:px-3.5 sm:py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition shadow-md flex items-center gap-2">
                            + Registrar Abono
                        </button>
                    </div>
                @endif

                <!-- CONTENEDOR INFERIOR: Historial y Tabla -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pt-3 sm:pt-4 border-t dark:border-gray-700">

                    <!-- Columna Izquierda: Historial de Abonos (Con Scroll Interno igual que la tabla) -->
                    <div class="space-y-2 print:hidden">
                        <h4 class="font-bold text-xs sm:text-sm lg:text-base text-gray-900 dark:text-white text-center">Historial de Abonos</h4>
                        
                        <div class="max-h-[380px] overflow-y-auto space-y-1.5 pr-1 border border-transparent">
                            @forelse($credit->payments as $index => $payment)
                                <div class="flex flex-row justify-between items-center py-2 px-3 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-700/40 text-xs transition gap-2">
                                    
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="font-bold text-gray-900 dark:text-white truncate">
                                            Abono #{{ $index + 1 }}
                                        </span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs hidden lg:inline shrink-0">
                                            ({{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }})
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                            ${{ number_format($payment->amount, 2) }}
                                        </span>

                                        @unless($estaLiquidado)
                                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar este abono?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-950/60 dark:text-red-300 rounded text-[10px] font-bold transition">
                                                    ELIMINAR
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 bg-gray-50/50 dark:bg-gray-800/20 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                                    <p class="text-xs text-gray-400 italic">Aún no se han registrado abonos.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Columna Derecha: Estado de Cuenta (Tabla) -->
                    <div class="space-y-2 mt-2 md:mt-0 lg:col-span-1 print:col-span-2">
                        <div class="text-center font-bold text-xs sm:text-sm lg:text-base text-gray-900 dark:text-white mb-1.5 sm:mb-2 tracking-wide">
                            Estado de Cuenta (Tabla)
                        </div>

                        <div class="w-full max-h-[380px] border border-gray-100 dark:border-gray-700 rounded-lg overflow-y-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-gray-50/80 dark:bg-gray-800/80 sticky top-0 z-10 backdrop-blur-sm">
                                    <tr class="text-gray-400 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-1.5 px-2 font-semibold text-xs uppercase tracking-wider">Fecha</th>
                                        <th class="py-1.5 px-2 font-semibold text-xs uppercase tracking-wider hidden xl:table-cell">Concepto / Detalle</th>
                                        <th class="py-1.5 px-2 font-semibold text-right text-xs uppercase tracking-wider">Abono</th>
                                        <th class="py-1.5 px-2 font-semibold text-right text-xs uppercase tracking-wider">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-gray-800 dark:text-gray-200">
                                    
                                    <!-- Fila Inicial -->
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                        <td class="py-1.5 px-2 text-xs">
                                            {{ \Carbon\Carbon::parse($credit->created_at)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-1.5 px-2 text-xs font-normal hidden xl:table-cell">Crédito Inicial ({{ $credit->concept }})</td>
                                        <td class="py-1.5 px-2 text-right text-gray-400 text-xs">-</td>
                                        <td class="py-1.5 px-2 text-right font-bold text-gray-900 dark:text-white text-xs">
                                            ${{ number_format($credit->total_amount, 2) }}
                                        </td>
                                    </tr>

                                    @php
                                        $saldoContable = $credit->total_amount;
                                    @endphp

                                    @forelse($credit->payments->sortBy('created_at') as $index => $payment)
                                        @php
                                            $saldoContable = max(0, $saldoContable - $payment->amount);
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="py-1.5 px-2 text-xs">
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="py-1.5 px-2 text-xs font-normal hidden xl:table-cell">
                                                Abono #{{ $index + 1 }}
                                                @if($payment->notes)
                                                    <span class="block text-[10px] text-gray-400 italic">"{{ $payment->notes }}"</span>
                                                @endif
                                            </td>
                                            <td class="py-1.5 px-2 text-right font-bold text-green-600 dark:text-green-400 text-xs">
                                                ${{ number_format($payment->amount, 2) }}
                                            </td>
                                            <td class="py-1.5 px-2 text-right font-bold text-gray-900 dark:text-white text-xs">
                                                ${{ number_format($saldoContable, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>

                            @if($credit->payments->isEmpty())
                                <div class="text-center py-5 text-gray-400 text-xs italic bg-gray-50/50 dark:bg-gray-800/20">
                                    Sin movimientos registrados.
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between sm:justify-end items-center gap-2 sm:gap-6 pt-2.5 mt-1 text-xs sm:text-sm font-bold bg-gray-50 dark:bg-gray-900/30 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700/50">
                            <span class="text-gray-900 dark:text-white text-xs font-bold uppercase tracking-wider">Saldo Pendiente Actual:</span>
                            <span class="text-amber-500 text-sm font-black">
                                ${{ number_format($saldoRestante, 2) }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- MODAL: Registrar Abono -->
    @unless($estaLiquidado)
        <div id="paymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden p-4 print:hidden">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 sm:p-6 w-full max-w-md shadow-xl border border-gray-100 dark:border-gray-700 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Registrar Nuevo Abono</h3>
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        ✕
                    </button>
                </div>
                
                <form action="{{ route('payments.store', $credit->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="credit_id" value="{{ $credit->id }}">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Monto del Abono ($)</label>
                        <input type="number" step="0.01" max="{{ $saldoRestante }}" name="amount" required 
                               placeholder="Máx: ${{ $saldoRestante }}"
                               class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500 shadow-xs transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Fecha</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required 
                               class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2.5 shadow-xs transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Notas / Observación (Opcional)</label>
                        <textarea name="notes" rows="2" placeholder="Ej. Abonó en efectivo..." 
                                  class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-sm px-3 py-2.5 shadow-xs transition"></textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" 
                                class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold rounded-xl transition cursor-pointer text-center">
                            Cancelar
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md transition cursor-pointer text-center">
                            Guardar Abono
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endunless

</x-app-layout>