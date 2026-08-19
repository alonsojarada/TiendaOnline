<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Botón Volver -->
            <div class="mb-4 flex justify-end">
                <a href="{{ route('clients.show', $credit->client_id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
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
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 relative">
                
                <!-- Cabecera de la tarjeta: Cliente y Botón Eliminar -->
                <div class="flex justify-between items-start border-b pb-4 mb-6 dark:border-gray-700">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Cliente</span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $credit->client->name ?? 'Sin cliente' }}</h3>
                    </div>
                    <div class="text-right flex items-center gap-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block">Concepto</span>
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $credit->concept }}</span>
                        </div>
                        <!-- Botón Eliminar (Opcional si usas ruta destroy) -->
                        <form action="{{ route('debts.destroy', $credit->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este crédito?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded-md text-xs font-semibold border border-red-200 transition">
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

                <!-- Estado de Liquidación o Alerta -->
                @if($estaLiquidado)
                    <div class="mb-8 p-4 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="bg-green-600 text-white p-1.5 rounded-full text-xs">✓</span>
                            <div>
                                <h4 class="text-sm font-bold text-green-800 dark:text-green-300">CRÉDITO LIQUIDADO</h4>
                                <p class="text-xs text-green-600 dark:text-green-400">Este crédito ha sido pagado en su totalidad.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Sección del Historial de Abonos / Pagos -->
                <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4">Historial de Abonos</h3>

                <div class="space-y-3">
                    @forelse($credit->payments as $index => $payment)
                        <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Abono #{{ $index + 1 }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">(Fecha: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }})</span>
                                @if($payment->notes)
                                    <span class="text-xs text-gray-500 italic">"{{ $payment->notes }}"</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4">
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($payment->amount, 2) }}
                                </span>

                                <!-- Botón para eliminar abono individual (opcional) -->
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este abono?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-100 hover:bg-red-200 text-red-600 rounded text-[10px] font-bold uppercase transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 bg-gray-50 dark:bg-gray-700/20 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aún no se han registrado abonos para esta mercancía fiada.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>