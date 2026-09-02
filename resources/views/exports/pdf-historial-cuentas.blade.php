<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial General de Cuentas y Préstamos</title>
    <style>
        body {
            font-family: sans-serif;
            color: #1f2937;
            font-size: 8pt;
            margin: 10px;
        }

        h2 {
            text-align: center;
            color: #4f46e5;
            margin-bottom: 3px;
            font-size: 12pt;
        }

        .subtitulo {
            text-align: center;
            font-size: 7.5pt;
            color: #6b7280;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 3px 4px;
            text-align: left;
        }

        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-align: center;
            font-size: 7.5pt;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-emerald {
            color: #059669;
        }
    </style>
</head>

<body>
    <h2>Historial General de Cuentas y Préstamos</h2>
    <div class="subtitulo">Generado el: {{ date('d/m/Y H:i') }}</div>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Concepto</th>
                <th>Monto Inicial</th>
                <th>Abonado</th>
                <th>Saldo Actual</th>
                <th>F. Apertura</th>
                <th>F. Liquidación</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @php 
                                $sumaMonto = 0;
                $sumaAbonado = 0;
                $sumaSaldo = 0; 
            @endphp

            @forelse($loans ?? [] as $debt)
                @php
                    $isLiquidated = $debt->current_capital <= 0;
                    $montoAbonadoDebt = $debt->payments ? $debt->payments->sum('capital_covered') : 0;
                    $estatusTexto = $isLiquidated ? 'Liquidado' : 'Pendiente';

                    $sumaMonto += $debt->total_amount ?? 0;
                    $sumaAbonado += $montoAbonadoDebt;
                    $sumaSaldo += $debt->current_capital ?? 0;
                @endphp
                <tr>
                    <td>{{ $debt->client->name ?? 'Cliente Desconocido' }}</td>
                    <td>{{ $debt->client->address ?? 'Sin dirección' }}</td>
                    <td>{{ $debt->concept ?? 'Cuenta #' . $debt->id }}</td>
                    <td class="text-right">${{ number_format($debt->total_amount ?? 0, 2) }}</td>
                    <td class="text-right text-emerald">${{ number_format($montoAbonadoDebt, 2) }}</td>
                    <td class="text-right font-bold">${{ number_format($debt->current_capital ?? 0, 2) }}</td>
                    <td class="text-center">{{ $debt->created_at ? $debt->created_at->format('d/m/Y') : 'N/A' }}</td>
                    <td class="text-center">
                        @if($isLiquidated)
                            {{ optional($debt->payments->last())->created_at ? optional($debt->payments->last())->created_at->format('d/m/Y') : 'Liquidado' }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center font-bold">{{ strtoupper($estatusTexto) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; color: #6b7280;">
                        No se encontraron cuentas registradas en el sistema con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse

            <!-- Fila de Totales -->
            <tr style="background-color: #f9fafb; font-weight: bold;">
                <td colspan="3" class="text-right">TOTALES:</td>
                <td class="text-right">${{ number_format($sumaMonto, 2) }}</td>
                <td class="text-right text-emerald">${{ number_format($sumaAbonado, 2) }}</td>
                <td class="text-right">${{ number_format($sumaSaldo, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>