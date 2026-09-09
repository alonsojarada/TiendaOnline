<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-left">
                Flujo de Ventas y Cobranza
            </h2>
        </div>
    </x-slot>

    <div class="space-y-4">

        @php
            $totalVendidoAnual = $reporte->sum('total_vendido');
            $totalCobradoAnual = $reporte->sum('total_cobrado');
            $balanceAnual = $totalCobradoAnual - $totalVendidoAnual;
            // Evitar división entre cero para el porcentaje anual
            $eficienciaAnual = $totalVendidoAnual > 0 ? ($totalCobradoAnual / $totalVendidoAnual) * 100 : ($totalCobradoAnual > 0 ? 100 : 0);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">

            <!-- 1. Total Vendido -->
            <div
                class="bg-white dark:bg-gray-800 px-3.5 py-2.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total
                        Vendido / Prestado</p>
                    <p class="text-lg font-black text-indigo-600 dark:text-indigo-400 mt-1 leading-none">
                        ${{ number_format($totalVendidoAnual, 2) }}
                    </p>
                </div>
                <div
                    class="p-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-sm shrink-0">
                    🛍️
                </div>
            </div>

            <!-- 2. Total Cobrado -->
            <div
                class="bg-white dark:bg-gray-800 px-3.5 py-2.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total
                        Cobrado (Real)</p>
                    <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 mt-1 leading-none">
                        ${{ number_format($totalCobradoAnual, 2) }}
                    </p>
                </div>
                <div
                    class="p-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg text-sm shrink-0">
                    💵
                </div>
            </div>

            <!-- 3. Balance Neto -->
            <div
                class="bg-white dark:bg-gray-800 px-3.5 py-2.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Balance
                        Neto Anual</p>
                    <p
                        class="text-lg font-black {{ $balanceAnual >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400' }} mt-1 leading-none">
                        {{ $balanceAnual < 0 ? '-' : '' }}${{ number_format(abs($balanceAnual), 2) }}
                    </p>
                </div>
                <div
                    class="p-2 {{ $balanceAnual >= 0 ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' }} rounded-lg text-sm shrink-0">
                    ⚖️
                </div>
            </div>

            <!-- 4. % Eficiencia de Cobranza -->
            <div
                class="bg-white dark:bg-gray-800 px-3.5 py-2.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <p class="text-xs font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Eficiencia de Cobranza</p>
                    <p class="text-lg font-black text-purple-600 dark:text-purple-400 mt-1 leading-none">
                        {{ number_format($eficienciaAnual, 1) }}%
                    </p>
                </div>
                <div
                    class="p-2 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg text-sm shrink-0">
                    🎯
                </div>
            </div>

            <!-- 5. Tarjeta de Estado y Filtro de Año (Ocupa 2 columnas en pantallas medianas si es necesario para equilibrar) -->
            <div
                class="bg-white dark:bg-gray-800 px-3.5 py-2.5 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 flex items-center justify-between sm:col-span-2 lg:col-span-1 xl:col-span-1">
                <div class="flex items-center">
                    @if($eficienciaAnual >= 80)
                        <span
                            class="text-xs font-bold bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">✅
                            Saludable</span>
                    @elseif($eficienciaAnual >= 50)
                        <span
                            class="text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full border border-blue-200 dark:border-blue-800">ℹ️
                            Estable</span>
                    @else
                        <span
                            class="text-xs font-bold bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 px-2.5 py-1 rounded-full border border-amber-200 dark:border-amber-800">⚠️
                            Atención a Cartera</span>
                    @endif
                </div>

                <form method="GET" action="{{ route('reports.reporte-mensual') }}" class="flex items-center gap-2">
                    <label class="text-xs font-bold text-gray-500 dark:text-gray-400">Año:</label>
                    <select name="year" onchange="this.form.submit()"
                        class="w-20 text-sm font-bold rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white shadow-xs focus:ring-indigo-500 focus:border-indigo-500 py-1.5 px-2.5">
                        @foreach($aniosDisponibles as $anio)
                            <option value="{{ $anio }}" {{ $year == $anio ? 'selected' : '' }}>{{ $anio }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

        </div>

        <!-- 3. Gráfica de Comparación (Altura Reducida) -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Comportamiento Anual</h4>
                <span
                    class="text-[10px] font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-md">Flujo
                    de Caja</span>
            </div>
            <div class="relative h-52 w-full">
                <canvas id="graficaFlujoCaja"></canvas>
            </div>
        </div>

        <!-- 4. Tabla Detallada con Scroll Interno Vertical -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
            <div
                class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-2">
                <h4 class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Flujo de Ventas y Cobranza
                    Mensual</h4>

                <!-- Botones de Acción / Exportar -->
                <div class="flex items-center gap-2">
                    <button onclick="window.print()"
                        class="text-[11px] font-bold bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 transition flex items-center gap-1.5 cursor-pointer">
                        <span>🖨️</span> Imprimir / PDF
                    </button>
                    <button onclick="exportarTablaExcel()"
                        class="text-[11px] font-bold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/40 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-800 transition flex items-center gap-1.5 cursor-pointer">
                        <span>📊</span> Exportar CSV
                    </button>
                </div>
            </div>

            <!-- Contenedor con scroll interno estricto (sin scroll horizontal general) -->
            <div class="max-h-72 overflow-y-auto overflow-x-hidden w-full">
                <table id="tablaReporteFlujo" class="w-full text-left border-collapse table-auto">
                    <thead class="sticky top-0 z-10 shadow-xs">
                        <tr
                            class="bg-gray-50 dark:bg-gray-700 text-[11px] font-bold text-gray-500 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2.5 px-4">Mes</th>
                            <th class="py-2.5 px-4 text-right">Total Vendido / Prestado</th>
                            <th class="py-2.5 px-4 text-right">Dinero Cobrado (Real)</th>
                            <th class="py-2.5 px-4 text-right">Diferencia / Balance</th>
                            <th class="py-2.5 px-4 text-right">Eficiencia</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($reporte as $fila)
                            @php
                                $diferencia = $fila['total_cobrado'] - $fila['total_vendido'];
                                $eficienciaMes = $fila['total_vendido'] > 0 ? ($fila['total_cobrado'] / $fila['total_vendido']) * 100 : ($fila['total_cobrado'] > 0 ? 100 : 0);
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                                <td class="py-3 px-4 font-bold text-gray-900 dark:text-white capitalize whitespace-nowrap">
                                    {{ $fila['mes_nombre'] }}
                                </td>
                                <td
                                    class="py-3 px-4 text-right font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    ${{ number_format($fila['total_vendido'], 2) }}
                                </td>
                                <td
                                    class="py-3 px-4 text-right font-black text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                    ${{ number_format($fila['total_cobrado'], 2) }}
                                </td>
                                <td
                                    class="py-3 px-4 text-right font-bold {{ $diferencia >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400' }} whitespace-nowrap">
                                    {{ $diferencia < 0 ? '-' : '' }}${{ number_format(abs($diferencia), 2) }}
                                </td>
                                <td
                                    class="py-3 px-4 text-right font-black text-purple-600 dark:text-purple-400 whitespace-nowrap">
                                    {{ number_format($eficienciaMes, 1) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-400">
                                    <div class="text-2xl mb-1">📭</div>
                                    <p class="font-medium">No hay registros financieros para este año.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script de Chart.js y Funciones de Utilidad -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const canvas = document.getElementById('graficaFlujoCaja');
            if (!canvas) return;

            const datosReporte = @json($reporte->values());

            const labels = datosReporte.map(item => item.mes_nombre);
            const ventasData = datosReporte.map(item => item.total_vendido);
            const cobrosData = datosReporte.map(item => item.total_cobrado);

            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Vendido / Prestado',
                            data: ventasData,
                            backgroundColor: 'rgba(99, 102, 241, 0.85)',
                            borderWidth: 0,
                            borderRadius: 4,
                            barPercentage: 0.5,
                            categoryPercentage: 0.6
                        },
                        {
                            label: 'Cobrado (Dinero Real)',
                            data: cobrosData,
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderWidth: 0,
                            borderRadius: 4,
                            barPercentage: 0.5,
                            categoryPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                borderRadius: 3,
                                font: {
                                    family: 'Figtree, sans-serif',
                                    size: 10,
                                    weight: 'bold'
                                },
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 10 },
                            padding: 8,
                            cornerRadius: 6,
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)'
                            },
                            ticks: {
                                font: { size: 9 },
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280',
                                callback: function (value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10, weight: 'bold' },
                                color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#4b5563'
                            }
                        }
                    }
                }
            });
        });

        // Función sencilla para exportar la tabla a formato CSV (Excel)
        function exportarTablaExcel() {
            let tabla = document.getElementById("tablaReporteFlujo");
            let csv = [];
            let filas = tabla.querySelectorAll("tr");

            for (let i = 0; i < filas.length; i++) {
                let fila = [], celdas = filas[i].querySelectorAll("th, td");
                for (let j = 0; j < celdas.length; j++) {
                    let texto = celdas[j].innerText.replace(/[\n\r]+/g, "").trim();
                    fila.push('"' + texto + '"');
                }
                csv.push(fila.join(","));
            }

            let blob = new Blob(["\ufeff" + csv.join("\n")], { type: 'text/csv;charset=utf-8;' });
            let enlace = document.createElement("a");
            enlace.href = URL.createObjectURL(blob);
            enlace.download = "reporte_flujo_ventas_cobranza_{{ $year }}.csv";
            enlace.style.display = "none";
            document.body.appendChild(enlace);
            enlace.click();
            document.body.removeChild(enlace);
        }
    </script>
    <style>
        @media print {

            /* Ocultar navegación, botones de exportar y elementos innecesarios al imprimir */
            header,
            nav,
            aside,
            footer,
            button,
            form select {
                display: none !important;
            }

            /* Asegurar que el contenedor de la gráfica mantenga dimensiones fijas para impresión */
            .relative.h-52 {
                height: 220px !important;
                width: 100% !important;
                page-break-inside: avoid;
            }

            canvas {
                max-width: 100% !important;
                height: auto !important;
            }

            /* Evitar saltos de página incómodos en las tarjetas y la tabla */
            .grid {
                display: grid !important;
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                gap: 8px !important;
                page-break-inside: avoid;
            }

            div,
            table {
                page-break-inside: avoid;
            }

            body {
                background-color: white !important;
                color: black !important;
            }
        }
    </style>
</x-app-layout>