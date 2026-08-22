<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta #{{ $credit->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }
        .info-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-box td {
            padding: 6px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 10px;
        }
        .value {
            font-weight: bold;
            color: #1f2937;
        }
        table.accounting {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.accounting th, table.accounting td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        table.accounting th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-green {
            color: #16a34a;
        }
        .text-indigo {
            color: #4f46e5;
        }
        .text-amber {
            color: #d97706;
        }
        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">Estado de Cuenta / Crédito: #{{ $credit->id }}-{{ strtoupper(substr($credit->client->name ?? 'X', 0, 3)) }}</div>
        <div>Fecha de emisión: {{ date('d/m/Y') }}</div>
    </div>

    @php
        $totalAbonado = $credit->payments->sum('amount');
        $saldoRestante = $credit->total_amount - $totalAbonado;
    @endphp

    <!-- Detalles superiores -->
    <table class="info-box">
        <tr>
            <td>
                <span class="label">Cliente:</span><br>
                <span class="value">{{ $credit->client->name ?? 'Sin cliente' }}</span>
            </td>
            <td>
                <span class="label">Concepto:</span><br>
                <span class="value">{{ $credit->concept }}</span>
            </td>
            <td>
                <span class="label">Fecha del Crédito:</span><br>
                <span class="value">{{ \Carbon\Carbon::parse($credit->created_at)->format('d/m/Y') }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Total Fiado / Artículos:</span><br>
                <span class="value">${{ number_format($credit->total_amount, 2) }}</span>
            </td>
            <td>
                <span class="label">Total Abonado:</span><br>
                <span class="value text-green">${{ number_format($totalAbonado, 2) }}</span>
            </td>
            <td>
                <span class="label">Saldo Restante:</span><br>
                <span class="value text-amber">${{ number_format($saldoRestante, 2) }}</span>
            </td>
        </tr>
    </table>

    <h3 style="margin-top: 30px; margin-bottom: 10px; font-size: 14px; color: #111827;">Detalle de Movimientos</h3>

    <!-- Tabla Contable (Sin fondos en las filas) -->
    <table class="accounting">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Concepto / Detalle</th>
                <th class="text-right">Abono</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Fila Inicial -->
            <tr>
                <td>{{ \Carbon\Carbon::parse($credit->created_at)->format('d/m/Y') }}</td>
                <td>Crédito Inicial ({{ $credit->concept }})</td>
                <td class="text-right">-</td>
                <td class="text-right value">${{ number_format($credit->total_amount, 2) }}</td>
            </tr>

            @php
                $saldoContable = $credit->total_amount;
            @endphp

            @foreach($credit->payments->sortBy('created_at') as $index => $payment)
                @php
                    $saldoContable = max(0, $saldoContable - $payment->amount);
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                    <td>
                        Abono #{{ $index + 1 }}
                        @if($payment->notes)
                            <br><small style="color: #6b7280;">"{{ $payment->notes }}"</small>
                        @endif
                    </td>
                    <td class="text-right text-green font-bold">${{ number_format($payment->amount, 2) }}</td>
                    <td class="text-right text-indigo font-bold">${{ number_format($saldoContable, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="3" class="text-right">Saldo Pendiente Actual:</td>
                <td class="text-right text-amber">${{ number_format($saldoRestante, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>