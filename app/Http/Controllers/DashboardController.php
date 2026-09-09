<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Método privado que centraliza la consulta y procesamiento de deudas.
     */
    private function obtenerDatosCobranza()
    {
        $hoy = Carbon::now()->startOfDay();

        return Debt::with(['client', 'payments', 'installments'])
            ->get()
            ->map(function ($credit) use ($hoy) {

                // --- CÁLCULO PARA PRÉSTAMOS ---
                $cuotasVencidasCount = 0;
                $diasProximoAbono = null;
                $textoProximoAbono = '';

                if ($credit->type === 'cash_loan') {
                    $cuotasVencidasCount = $credit->installments->filter(function ($installment) use ($hoy) {
                        return $installment->status === 'pending' && Carbon::parse($installment->due_date)->isPast();
                    })->count();

                    $siguienteCuota = $credit->installments
                        ->where('status', 'pending')
                        ->sortBy(fn($inst) => Carbon::parse($inst->due_date)->timestamp)
                        ->first();

                    if ($siguienteCuota) {
                        $fechaVenc = Carbon::parse($siguienteCuota->due_date)->startOfDay();
                        $diasProximoAbono = $hoy->diffInDays($fechaVenc, false);

                        if ($diasProximoAbono > 0) {
                            $textoProximoAbono = "Faltan {$diasProximoAbono} días";
                        } elseif ($diasProximoAbono === 0) {
                            $textoProximoAbono = "Vence hoy";
                        } else {
                            $textoProximoAbono = "Vencido hace " . abs($diasProximoAbono) . " días";
                        }
                    } else {
                        $textoProximoAbono = "Sin cuotas pendientes";
                    }
                }

                // --- CÁLCULO PARA MERCANCÍA FIADA ---
                $ultimoPago = $credit->payments()->latest('payment_date')->first();
                $fechaRef = $ultimoPago ? Carbon::parse($ultimoPago->payment_date) : Carbon::parse($credit->created_at);
                $diasSinAbonar = $fechaRef->startOfDay()->diffInDays($hoy);

                $pagado = $credit->payments->sum('amount');
                $pendiente = $credit->total_amount - $pagado;

                // --- REGLAS DE ESTADO ---
                $isAtrasado = ($credit->type === 'cash_loan' && $cuotasVencidasCount > 0) || ($credit->type !== 'cash_loan' && $diasSinAbonar > 7);
                $isProximo = !$isAtrasado && (
                    ($credit->type === 'cash_loan' && $diasProximoAbono !== null && $diasProximoAbono <= 2 && $diasProximoAbono >= 0) ||
                    ($diasSinAbonar > 4)
                );
                $isAlDia = !$isAtrasado && !$isProximo;

                $tipoTexto = 'Mercancía Fiada';
                if ($credit->type === 'cash_loan') {
                    $tipoTexto = 'Préstamo en Efectivo';
                } elseif ($credit->type) {
                    $tipoTexto = ucfirst(str_replace('_', ' ', $credit->type));
                }

                return (object) [
                    'client_id' => $credit->client_id,
                    'client' => $credit->client,
                    'concept' => '#' . $credit->id . ' - ' . ($credit->concept ?? 'Cuenta'),
                    'tipo' => $tipoTexto,
                    'is_loan' => $credit->type === 'cash_loan',
                    'fecha_ref' => $fechaRef->format('d/m/Y'),
                    'dias_sin_abonar' => $diasSinAbonar,
                    'cuotas_vencidas' => $cuotasVencidasCount,
                    'texto_proximo_abono' => $textoProximoAbono,
                    'dias_proximo_abono' => $diasProximoAbono,
                    'is_atrasado' => $isAtrasado,
                    'is_proximo' => $isProximo,
                    'is_al_dia' => $isAlDia,
                    'saldo_pendiente' => $pendiente,
                    'amount_due' => $credit->amount_due ?? 0,
                    'is_liquidado' => $pendiente <= 0
                ];
            })
            ->filter(fn($item) => !$item->is_liquidado)
            ->sort(function ($a, $b) {
                if ($a->is_atrasado !== $b->is_atrasado) {
                    return $a->is_atrasado ? -1 : 1;
                }
                if ($a->is_atrasado && $b->is_atrasado) {
                    $diasA = $a->is_loan ? abs(min(0, $a->dias_proximo_abono)) : $a->dias_sin_abonar;
                    $diasB = $b->is_loan ? abs(min(0, $b->dias_proximo_abono)) : $b->dias_sin_abonar;
                    return $diasB <=> $diasA;
                }
                $valA = $a->is_loan ? $a->dias_proximo_abono : -$a->dias_sin_abonar;
                $valB = $b->is_loan ? $b->dias_proximo_abono : -$b->dias_sin_abonar;
                return $valA <=> $valB;
            })
            ->values();
    }

    public function index()
    {
        $allDebts = $this->obtenerDatosCobranza();

        $totalGlobalPendiente = $allDebts->sum('saldo_pendiente');
        $totalPrestamos = $allDebts->where('is_loan', true)->sum('saldo_pendiente');
        $totalMercancia = $allDebts->where('is_loan', false)->sum('saldo_pendiente');
        $montoAbonosRetrasados = $allDebts->where('is_atrasado', true)->sum('amount_due');
        $clientesConRetrasoCount = $allDebts->where('is_atrasado', true)->unique('client_id')->count();

        return view('dashboard', [
            'deudasGlobales' => $allDebts,
            'totalGlobalPendiente' => $totalGlobalPendiente,
            'totalPrestamos' => $totalPrestamos,
            'totalMercancia' => $totalMercancia,
            'montoAbonosRetrasados' => $montoAbonosRetrasados,
            'clientesConRetrasoCount' => $clientesConRetrasoCount
        ]);
    }

    /**
     * Exportar a Excel (CSV optimizado para compatibilidad con móviles y PC)
     */
    public function exportExcel()
    {
        $allDebts = $this->obtenerDatosCobranza();
        $totalGlobalPendiente = $allDebts->sum('saldo_pendiente');
        $filename = "panel_cobranza_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($allDebts, $totalGlobalPendiente) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 para tildes y eñes en Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Encabezados profesionales
            fputcsv($file, ['REPORTE GENERAL DE COBRANZA - ' . date('d/m/Y')]);
            fputcsv($file, []); // Línea en blanco
            fputcsv($file, ['Cliente', 'Alias', 'Dirección', 'Concepto', 'Tipo', 'Estado / Vencimiento', 'Saldo Pendiente']);

            foreach ($allDebts as $d) {
                $estadoTexto = $d->is_atrasado ? 'ATRASADO' : ($d->is_proximo ? 'PRÓXIMO' : 'AL DÍA');

                fputcsv($file, [
                    $d->client->name ?? 'Cliente General',
                    $d->client->alias ?? '',
                    $d->client->address ?? 'Sin dirección',
                    $d->concept,
                    $d->tipo,
                    $d->is_loan ? $d->texto_proximo_abono : $d->fecha_ref,
                    number_format($d->saldo_pendiente, 2, '.', '')
                ]);
            }

            // Fila de Total Global al final
            fputcsv($file, []);
            fputcsv($file, ['TOTAL GLOBAL PENDIENTE', '', '', '', '', '', number_format($totalGlobalPendiente, 2, '.', '')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar a PDF (Abre en pestaña nueva para visualizar, imprimir o compartir)
     */
    public function exportPDF()
    {
        $allDebts = $this->obtenerDatosCobranza();
        $totalGlobalPendiente = $allDebts->sum('saldo_pendiente');

        // Carga la vista del PDF
        $pdf = Pdf::loadView('exports.cobranza-pdf', compact('allDebts', 'totalGlobalPendiente'));

        // Opciones de papel
        $pdf->setPaper('a4', 'portrait');

        // CAMBIA stream() por download() PARA FORZAR LA DESCARGA DIRECTA
        return $pdf->download('reporte_cobranza_' . date('Y-m-d') . '.pdf');
    }
}