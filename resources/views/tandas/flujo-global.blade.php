<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Financiero Global de Tandas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Tarjetas de Resumen Financiero -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase">Fondo Global Total</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">${{ number_format($fondoGlobalTotal, 2) }}</p>
                    <span class="text-xs text-gray-500">Suma total proyectada</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100 bg-emerald-50/20">
                    <p class="text-xs font-bold text-emerald-600 uppercase">Total Cobrado</p>
                    <p class="text-xl font-bold text-emerald-600 mt-1">${{ number_format($totalCobradoGlobal, 2) }}</p>
                    <span class="text-xs text-emerald-600/80">Ingresos reales en caja</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-blue-100 bg-blue-50/20">
                    <p class="text-xs font-bold text-blue-600 uppercase">Total Entregado</p>
                    <p class="text-xl font-bold text-blue-600 mt-1">${{ number_format($totalEntregadoGlobal, 2) }}</p>
                    <span class="text-xs text-blue-600/80">Pozos repartidos</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-indigo-100 bg-indigo-50/20">
                    <p class="text-xs font-bold text-indigo-600 uppercase">Balance / Utilidad</p>
                    <p class="text-xl font-bold text-indigo-600 mt-1">${{ number_format($balanceGlobal, 2) }}</p>
                    <span class="text-xs text-indigo-600/80">Flujo neto acumulado</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-rose-100 bg-rose-50/20">
                    <p class="text-xs font-bold text-rose-600 uppercase">Vencido Global</p>
                    <p class="text-xl font-bold text-rose-600 mt-1">${{ number_format($vencidoGlobal, 2) }}</p>
                    <span class="text-xs text-rose-600/80">Deuda retrasada total</span>
                </div>
            </div>

            <!-- Tabla de Desglose Global -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Desglose Detallado por Tanda
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr
                                class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3">Tanda</th>
                                <th class="px-4 py-3 text-right">Monto Entrega</th>
                                <th class="px-4 py-3 text-right">Cobrado</th>
                                <th class="px-4 py-3 text-right">Entregado</th>
                                <th class="px-4 py-3 text-right">Atrasado / Vencido</th>
                                <th class="px-4 py-3 text-center">Progreso de Cobranza</th>
                                <th class="px-4 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($reporteTandas as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $item['nombre'] }}
                                        <span
                                            class="block text-xs text-gray-400 capitalize font-normal">{{ $item['estado'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-700">
                                        ${{ number_format($item['monto_entrega'], 2) }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-emerald-600">
                                        ${{ number_format($item['total_cobrado'], 2) }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-blue-600">
                                        ${{ number_format($item['total_entregado'], 2) }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-rose-600">
                                        ${{ number_format($item['vencido'], 2) }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center space-x-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-indigo-600 h-2 rounded-full"
                                                    style="width: {{ $item['progreso'] }}%"></div>
                                            </div>
                                            <span
                                                class="text-xs font-semibold text-gray-600">{{ $item['progreso'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('tandas.show', $item['id']) }}"
                                            class="px-3 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition">
                                            Ver Tanda
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        No hay tandas registradas en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>