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
        .info-box td { padding: 6px 10px; vertical-align: top; width: 50%; }
        
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
        .text-indigo { color: #4f46e5; }
        .text-green { color: #059669; }
        .text-amber { color: #d97706; }
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

    @php
        // ==========================================
        // SEPARACIÓN DE LÓGICA SEGÚN `loan_modal`
        // ==========================================
        $modalidad = $loan->loan_modal ?? 'fixed_installments';
        $esCuotaFija = ($modalidad === 'fixed_installments');

        if ($esCuotaFija) {
            // --- MODALIDAD 1: CUOTAS FIJAS ---
            $prestadoMasIntereses = $loan->installments->sum('amount_due');
            if ($prestadoMasIntereses <= 0) {
                $prestadoMasIntereses = $loan->total_amount ?? 0;
            }

            $tasaPorcentaje = $loan->interest_rate ?? 0;
            $capitalTotal = $prestadoMasIntereses / (1 + ($tasaPorcentaje / 100));
            $interesTotal = $prestadoMasIntereses - $capitalTotal;

            $totalAbonado = $loan->installments->where('status', 'paid')->sum('amount_due');
            $saldoRestante = max(0, $prestadoMasIntereses - $totalAbonado);

            $totalCuotas = $loan->installments->count();
            $cuotasPagadasCount = $loan->installments->where('status', 'paid')->count();

            $esLiquidado = ($loan->status == 'paid') || ($saldoRestante <= 0 && $totalCuotas > 0 && $loan->installments->where('status', '!=', 'paid')->count() == 0);
            $ultimoPago = $loan->payments()->latest('payment_date')->first();
            $fechaLiquidacion = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date)->format('d/m/Y H:i') : now()->format('d/m/Y');

        } else {
            // --- MODALIDAD 2: OTRA MODALIDAD (Interés Fijo / Pagos Libres) ---
            $capitalTotal = $loan->total_amount ?? $loan->amount ?? 0;
            $tasaPorcentaje = $loan->interest_rate ?? 0;

            $interesTotal = $capitalTotal * ($tasaPorcentaje / 100);

            // Suma de los abonos de interés registrados en la tabla payments
            $totalAbonadoInteres = $loan->payments->sum('interest_covered');

            // Suma de los abonos a capital registrados en la tabla payments
            $totalAbonadoCapital = $loan->payments->sum('capital_covered');

            // Prestado + intereses = Capital prestado + Abonos de interés acumulados
            $prestadoMasIntereses = $capitalTotal + $totalAbonadoInteres;

            // Total abonado = Únicamente la suma de abonos a capital
            $totalAbonado = $totalAbonadoCapital;

            // Saldo restante = Capital prestado - Abonos a capital
            $saldoRestante = max(0, $capitalTotal - $totalAbonado);

            $totalCuotas = $loan->installments->count();
            $cuotasPagadasCount = $loan->installments->where('status', 'paid')->count();

            $esLiquidado = ($loan->status == 'paid') || ($saldoRestante <= 0);
            $ultimoPago = $loan->payments()->latest('payment_date')->first();
            $fechaLiquidacion = $ultimoPago ? \Carbon\Carbon::parse($ultimoPago->payment_date)->format('d/m/Y H:i') : now()->format('d/m/Y');
        }

        // Alias de respaldo
        $tasaFijaPorcentaje = $tasaPorcentaje;
        $interesFijoTotal = $interesTotal;
        $capitalFijo = $capitalTotal;
        $prestadoMasInteresesFijo = $prestadoMasIntereses;
        $totalAbonadoFijo = $totalAbonado;
        $saldoRestanteFijo = $saldoRestante;

        // Nombre base del tipo de crédito
        $nombreTipoCredito = $esCuotaFija ? 'Cuotas Fijas' : 'Interés Fijo';

        // Traducción o mapeo de la frecuencia (frequency / payment_frequency)
        $frecuenciaRaw = $loan->frequency ?? $loan->payment_frequency ?? '';
        $frecuenciaTexto = '';
        switch (strtolower($frecuenciaRaw)) {
            case 'weekly':
            case 'semanal':
                $frecuenciaTexto = 'Semanal';
                break;
            case 'biweekly':
            case 'quincenal':
                $frecuenciaTexto = 'Quincenal';
                break;
            case 'monthly':
            case 'mensual':
                $frecuenciaTexto = 'Mensual';
                break;
            default:
                $frecuenciaTexto = ucfirst($frecuenciaRaw);
                break;
        }

        // Construir el texto final combinado (Ej. "Interés Fijo - Semanal" o solo "Cuotas Fijas")
        $tipoCreditoTexto = $nombreTipoCredito;
        if (!empty($frecuenciaTexto)) {
            $tipoCreditoTexto .= ' - ' . $frecuenciaTexto;
        }
    @endphp

    <div class="header">
        <h2>Estado de Cuenta - Préstamo</h2>
        <p>Crédito #{{ $loan->id }}-VJB | Concepto: {{ $loan->concept }}</p>
    </div>

    <!-- Panel Superior sin fondo gris -->
    <table class="info-box">
        <tr>
            <td>
                <strong>Tipo de crédito:</strong> <span class="text-indigo">{{ $tipoCreditoTexto }}</span><br>
                <strong>Fecha del Crédito:</strong> {{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : $loan->created_at->format('d/m/Y') }}<br>
                <strong>Interés Aplicado:</strong> {{ number_format($tasaPorcentaje, 1) }}%<br>
                <strong>Total Intereses:</strong> ${{ number_format($interesTotal, 2) }}<br>
                <strong>Total prestado (Capital):</strong> ${{ number_format($capitalTotal, 2) }}
            </td>
            <td class="text-right">
                <strong>Cuotas pagadas:</strong> {{ $cuotasPagadasCount }} / {{ $totalCuotas }}<br>
                <strong>Prestado + intereses:</strong> ${{ number_format($prestadoMasIntereses, 2) }}<br>
                <strong>Total Abonado:</strong> <span class="text-green">${{ number_format($totalAbonado, 2) }}</span><br>
                <strong>Saldo Restante:</strong> <span class="text-amber font-bold">${{ number_format($saldoRestante, 2) }}</span>
            </td>
        </tr>
    </table>

    <!-- 1. PRIMERO: Historial de Movimientos / Abonos -->
    <h4>Historial de Movimientos (Abonos)</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Concepto / Detalle</th>
                <th class="text-right">Interés</th>
                <th class="text-right">Abono (Capital)</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : $loan->created_at->format('d/m/Y') }}</td>
                <td>Crédito Inicial ({{ $loan->concept }})</td>
                <td class="text-right">-</td>
                <td class="text-right">-</td>
                <td class="text-right font-bold">${{ number_format($capitalTotal, 2) }}</td>
            </tr>

            @php
                $saldoTablaAcumulado = $capitalTotal;
                $tieneMovimientos = false;
            @endphp

            @if($esCuotaFija)
                @foreach($loan->installments as $installment)
                    @if($installment->status == 'paid')
                        @php
                            $tieneMovimientos = true;
                            $pagoCuota = $loan->payments->where('installment_id', $installment->id)->first();
                            
                            $montoInteres = $pagoCuota->interest_covered ?? $installment->interest_amount ?? 0;
                            $montoAbonado = $pagoCuota ? $pagoCuota->amount : $installment->amount_due;
                            $montoCapital = $pagoCuota ? ($pagoCuota->capital_covered ?? max(0, $montoAbonado - $montoInteres)) : max(0, $montoAbonado - $montoInteres);
                            
                            $saldoTablaAcumulado -= $montoCapital;
                            $fechaFila = $pagoCuota ? \Carbon\Carbon::parse($pagoCuota->payment_date ?? $pagoCuota->updated_at)->format('d/m/Y') : ($installment->updated_at ? \Carbon\Carbon::parse($installment->updated_at)->format('d/m/Y') : 'N/A');
                        @endphp
                        <tr>
                            <td>{{ $fechaFila }}</td>
                            <td>Abono Cuota #{{ $installment->installment_number }}</td>
                            <td class="text-right text-indigo">${{ number_format($montoInteres, 2) }}</td>
                            <td class="text-right font-bold text-green">${{ number_format($montoCapital, 2) }}</td>
                            <td class="text-right font-bold">${{ number_format(max(0, $saldoTablaAcumulado), 2) }}</td>
                        </tr>
                    @endif
                @endforeach
            @else
                @php
                    $contadorInteres = 0;
                @endphp
                @foreach($loan->payments as $payment)
                    @php
                        $tieneMovimientos = true;
                        $montoInteres = $payment->interest_covered ?? 0;
                        $montoCapital = $payment->capital_covered ?? 0;
                        
                        // Determinar los conceptos separados
                        $detallesFila = [];
                        if ($montoInteres > 0) {
                            $contadorInteres++;
                            $detallesFila[] = "Interés #" . $contadorInteres;
                        }
                        if ($montoCapital > 0) {
                            $detallesFila[] = "Abono a Capital";
                        }
                        if (empty($detallesFila)) {
                            $detallesFila[] = $payment->concept ?? 'Abono';
                        }
                        
                        $textoConcepto = implode(' / ', $detallesFila);
                        if ($payment->notes) {
                            $textoConcepto .= '<br><small style="color: #666;">"' . $payment->notes . '"</small>';
                        }

                        $saldoTablaAcumulado -= $montoCapital;
                        $fechaFila = $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : $payment->created_at->format('d/m/Y');
                    @endphp
                    <tr>
                        <td>{{ $fechaFila }}</td>
                        <td>{!! $textoConcepto !!}</td>
                        <td class="text-right text-indigo">{{ $montoInteres > 0 ? '$' . number_format($montoInteres, 2) : '-' }}</td>
                        <td class="text-right font-bold text-green">{{ $montoCapital > 0 ? '$' . number_format($montoCapital, 2) : '-' }}</td>
                        <td class="text-right font-bold">${{ number_format(max(0, $saldoTablaAcumulado), 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold">Saldo Pendiente Actual:</td>
                <td class="text-right font-bold text-amber" style="font-size: 13px;">
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