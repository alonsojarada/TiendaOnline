<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Cobranza</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 15px; }
        
        /* Cabecera del reporte */
        .header { margin-bottom: 20px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; color: #1e3a8a; font-size: 18px; }
        .header p { margin: 0; color: #666; font-size: 10px; }

        /* Tarjeta de Resumen */
        .summary-box { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; margin-bottom: 15px; border-radius: 4px; width: 100%; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { text-align: center; font-size: 11px; color: #475569; border-right: 1px solid #cbd5e1; }
        .summary-table td:last-child { border-right: none; }
        .summary-table .amount { font-size: 14px; font-weight: bold; color: #0f172a; margin-top: 3px; display: block; }

        /* Tabla principal de datos */
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.main-table th { background-color: #f1f5f9; color: #334155; font-weight: bold; text-align: left; padding: 8px 6px; border: 1px solid #cbd5e1; font-size: 10px; text-transform: uppercase; }
        table.main-table td { border: 1px solid #e2e8f0; padding: 7px 6px; vertical-align: middle; }
        
        /* Filas alternadas para mejor lectura */
        table.main-table tr:nth-child(even) { background-color: #f8fafc; }

        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Badges de estado discretos para PDF */
        .badge { padding: 3px 6px; font-size: 9px; font-weight: bold; text-align: center; border-radius: 3px; display: inline-block; }
        .badge-atrasado { background-color: #fee2e2; color: #991b1b; }
        .badge-proximo { background-color: #fef3c7; color: #92400e; }
        .badge-aldia { background-color: #dcfce7; color: #166534; }

        /* Pie de página */
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 9px; text-align: center; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Panel General de Cobranza</h2>
        <p>Fecha de emisión: {{ date('d/m/Y H:i') }} | Reporte operativo de saldos pendientes</p>
    </div>

    <!-- Tarjeta de Resumen Global -->
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>Por Cobrar Total <span class="amount">${{ number_format($totalGlobalPendiente, 2) }}</span></td>
                <td>Préstamos <span class="amount">${{ number_format($allDebts->where('is_loan', true)->sum('saldo_pendiente'), 2) }}</span></td>
                <td>Mercancía Fiada <span class="amount">${{ number_format($allDebts->where('is_loan', false)->sum('saldo_pendiente'), 2) }}</span></td>
                <td>Créditos Activos <span class="amount">{{ $allDebts->count() }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Tabla de Detalles -->
    <table class="main-table">
        <thead>
            <tr>
                <th>Cliente / Alias</th>
                <th>Dirección</th>
                <th>Concepto / Tipo</th>
                <th>Ult. Abono / Vencido</th>
                <th class="text-right">Saldo Total</th>
                <th style="text-align: center;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allDebts as $d)
            <tr>
                <td>
                    <span class="font-bold">{{ $d->client->name ?? 'General' }}</span>
                    @if($d->client && $d->client->alias)
                        <br><span style="color: #64748b; font-size: 10px;">"{{ $d->client->alias }}"</span>
                    @endif
                </td>
                <td>{{ $d->client->address ?? 'Sin dirección' }}</td>
                <td>
                    {{ $d->concept }}
                    <br><span style="color: #64748b; font-size: 9px;">{{ $d->tipo }}</span>
                </td>
                <td>{{ $d->is_loan ? $d->texto_proximo_abono : $d->fecha_ref }}</td>
                <td class="text-right font-bold">${{ number_format($d->saldo_pendiente, 2) }}</td>
                <td style="text-align: center;">
                    @if($d->is_atrasado)
                        <span class="badge badge-atrasado">ATRASADO</span>
                    @elseif($d->is_proximo)
                        <span class="badge badge-proximo">PRÓXIMO</span>
                    @else
                        <span class="badge badge-aldia">AL DÍA</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado desde el Sistema Operativo de la Tienda — Página 1
    </div>

</body>
</html>