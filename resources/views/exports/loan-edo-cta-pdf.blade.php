<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - Préstamo #{{ $loan->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #4f46e5; }
        .info-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-box td { padding: 5px; vertical-align: top; }
        
        /* Estilos generales para la tabla de Abonos */
        table.table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        table.table th, table.table td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        table.table th { background-color: #f3f4f6; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        
        /* Estilo de texto plano (Calendario de Cuotas) */
        table.plain-table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; font-family: courier, monospace; font-size: 10px; }
        table.plain-table th { text-align: left; border-bottom: 1px solid #111; padding: 3px 4px; font-weight: bold; text-transform: uppercase; color: #111; background-color: transparent; }
        table.plain-table td { padding: 3px 4px; border-bottom: 1px dotted #ccc; vertical-align: top; color: #111; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        h4 { margin-bottom: 5px; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; }
        
        /* Pie de página de descarga */
        .download-date {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
            border-top: 1px dashed #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Estado de Cuenta - Préstamo</h2>
        <p>Crédito #{{ $loan->id }}-VJB | Concepto: {{ $loan->concept }}</p>
    </div>

    <table class="info-box">
        <tr>
            <td>
                <strong>Cliente:</strong> {{ $loan->client->name ?? 'N/A' }}<br>
                <strong>Fecha del Crédito:</strong> {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : $loan->created_at->format('d/m/Y') }}<br>
                <strong>Interés Aplicado:</strong> {{ number_format($loan->interest_rate ?? 0, 1) }}%
            </td>
            <td class="text-right">
                @php
                    $totalPagado = $loan->payments->sum('amount');
                    $montoTotalConInteres = $loan->total_amount;
                    $saldoRestante = max(0, $montoTotalConInteres - $totalPagado);
                @endphp
                <strong>Total con Intereses:</strong> ${{ number_format($montoTotalConInteres, 2) }}<br>
                <strong>Total Abonado:</strong> ${{ number_format($totalPagado, 2) }}<br>
                <strong>Saldo Restante:</strong> ${{ number_format($saldoRestante, 2) }}
            </td>
        </tr>
    </table>

    <!-- 1. PRIMERO: Historial de Movimientos / Abonos con Saldo al pie -->
    <h4>Historial de Movimientos (Abonos)</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Concepto / Detalle</th>
                <th class="text-right">Abono</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : $loan->created_at->format('d/m/Y') }}</td>
                <td>Crédito Inicial ({{ $loan->concept }})</td>
                <td class="text-right">-</td>
                <td class="text-right font-bold">${{ number_format($montoTotalConInteres, 2) }}</td>
            </tr>

            @php
                $saldoTablaAcumulado = $montoTotalConInteres;
                $tieneMovimientos = false;
            @endphp

            @foreach($loan->installments as $installment)
                @if($installment->status == 'paid')
                    @php
                        $tieneMovimientos = true;
                        $pagoCuota = $loan->payments->where('installment_id', $installment->id)->first();
                        $montoAbonado = $pagoCuota ? $pagoCuota->amount : $installment->amount_due;
                        $saldoTablaAcumulado -= $montoAbonado;
                        $fechaFila = $pagoCuota ? \Carbon\Carbon::parse($pagoCuota->updated_at)->format('d/m/Y') : ($installment->updated_at ? \Carbon\Carbon::parse($installment->updated_at)->format('d/m/Y') : 'N/A');
                    @endphp
                    <tr>
                        <td>{{ $fechaFila }}</td>
                        <td>Abono Cuota #{{ $installment->installment_number }}</td>
                        <td class="text-right font-bold" style="color: #059669;">${{ number_format($montoAbonado, 2) }}</td>
                        <td class="text-right font-bold">${{ number_format(max(0, $saldoTablaAcumulado), 2) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right font-bold">Saldo Pendiente Actual:</td>
                <td class="text-right font-bold" style="color: #d97706; font-size: 13px;">
                    ${{ number_format($saldoRestante, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    @if(!$tieneMovimientos)
        <p class="text-center" style="color: #666; font-style: italic; margin-top: -10px; margin-bottom: 20px;">Sin abonos registrados todavía.</p>
    @endif


    <!-- 2. DESPUÉS: Calendario de Cuotas (Texto Plano) -->
    <h4>Calendario de Cuotas</h4>
    <table class="plain-table">
        <thead>
            <tr>
                <th class="text-center"># CUOTA</th>
                <th>VENCIMIENTO</th>
                <th class="text-right">MONTO</th>
                <th class="text-center">ESTATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loan->installments as $installment)
                @php
                    $esPagada = $installment->status == 'paid';
                    $esVencida = !$esPagada && $installment->due_date && \Carbon\Carbon::parse($installment->due_date)->startOfDay()->isPast();
                    
                    if ($esPagada) {
                        $estadoTexto = "PAGADA";
                    } elseif ($esVencida) {
                        $estadoTexto = "VENCIDA";
                    } else {
                        $estadoTexto = "PENDIENTE";
                    }
                @endphp
                <tr>
                    <td class="text-center font-bold">#{{ $installment->installment_number }}</td>
                    <td>{{ $installment->due_date ? \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') : 'N/A' }}</td>
                    <td class="text-right font-bold">${{ number_format($installment->amount_due, 2) }}</td>
                    <td class="text-center font-bold">[{{ $estadoTexto }}]</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="font-style: italic; color: #555;">[ No hay cuotas registradas ]</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Fecha y hora de impresión actual -->
    <div class="download-date">
        Fecha de impresión: {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>