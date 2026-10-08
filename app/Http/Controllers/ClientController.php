<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;


class ClientController extends Controller
{
    // 1. Mostrar la lista de clientes (con opción de búsqueda por nombre o alias)
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = Client::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('alias', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
        })
            ->orderBy('name', 'asc')
            ->get(); // Paginación de 10 en 10 para mayor orden

        return view('clients.index', compact('clients', 'search'));
    }

    // 2. Guardar un nuevo cliente desde el formulario
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        Client::create([
            'company_id' => auth()->user()->company_id, // Vincula automáticamente el cliente a la empresa del usuario activo
            'name' => $request->name,
            'alias' => $request->alias,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'status' => $request->status,
            'user_id' => auth()->user()->id, // Captura el ID del usuario que crea el cliente
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado exitosamente.');
    }

    // 3. Actualizar un cliente existente (Método PUT / PATCH)
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:50', // Validación de status
        ]);

        $client->update([
            'name' => $request->name,
            'alias' => $request->alias,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('clients.index', $client->id)->with('success', 'Cliente actualizado exitosamente.');
    }

    // 4. Ver detalles y cuentas del cliente
    public function show($id)
    {
        $client = Client::with('debts.payments', 'debts.installments')->findOrFail($id);

        $storeCredits = $client->debts->where('type', 'store_credit');
        $cashLoans = $client->debts->where('type', 'cash_loan');

        // Cálculos de Mercancía Fiada
        $totalMercanciaOriginal = (float) $storeCredits->sum('total_amount');
        $totalAbonosMercancia = 0.0;
        foreach ($storeCredits as $debt) {
            $totalAbonosMercancia += (float) $debt->payments->sum('amount');
        }
        $totalMercanciaRestante = max(0, $totalMercanciaOriginal - $totalAbonosMercancia);

        // Cálculos de Préstamos en Efectivo
        $totalPrestamosOriginal = (float) $cashLoans->sum('total_amount');
        $totalAbonosPrestamos = 0.0;
        foreach ($cashLoans as $debt) {
            $totalAbonosPrestamos += (float) $debt->payments->sum('amount');
        }
        $totalPrestamosRestante = max(0, $totalPrestamosOriginal - $totalAbonosPrestamos);

        // Totales Globales
        $totalGeneralOriginal = $totalMercanciaOriginal + $totalPrestamosOriginal;
        $totalAbonosGlobal = $totalAbonosMercancia + $totalAbonosPrestamos;
        $totalAdeudoGlobal = $totalMercanciaRestante + $totalPrestamosRestante;

        $loan = $client->debts->first();

        return view('clients.show', compact(
            'loan',
            'client',
            'storeCredits',
            'cashLoans',
            'totalMercanciaOriginal',
            'totalAbonosMercancia',
            'totalMercanciaRestante',
            'totalPrestamosOriginal',
            'totalAbonosPrestamos',
            'totalPrestamosRestante',
            'totalGeneralOriginal',
            'totalAbonosGlobal',
            'totalAdeudoGlobal'
        ));
    }

    // 5. Método para exportar a Excel (Descarga en formato CSV compatible con Excel)
    public function exportExcel()
    {
        $clients = Client::orderBy('name', 'asc')->get();

        $fileName = 'directorio_clientes_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($clients) {
            $file = fopen('php://output', 'w');
            // Añadir BOM para que reconozca los acentos correctamente en Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Encabezados de columnas
            fputcsv($file, ['NOMBRE', 'ALIAS', 'DIRECCIÓN', 'TELÉFONO', 'ESTADO']);

            // Filas de datos
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->name,
                    $client->alias,
                    $client->address,
                    $client->phone,
                    $client->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 6. Método para exportar a PDF (Vista preliminar / Descarga rápida)
    public function exportPdf()
    {
        $clients = Client::all();

        $pdf = Pdf::loadView('exports.clients-pdf', compact('clients'));

        return $pdf->download('directorio_clientes.pdf');
    }

    public function openAccounts()
    {
        $clients = Client::with(['debts.payments'])
            ->get()
            ->filter(function ($client) {
                $montoMercancia = $client->debts->where('type', 'store_credit')->sum(function ($debt) {
                    return $debt->total_amount - $debt->payments->sum('amount');
                });

                $montoPrestamo = $client->debts->where('type', 'cash_loan')->sum(function ($debt) {
                    return $debt->total_amount - $debt->payments->sum('amount');
                });

                $client->montoMercanciaRestante = $montoMercancia;
                $client->montoPrestamoRestante = $montoPrestamo;
                $client->adeudoGlobal = $montoMercancia + $montoPrestamo;

                return $client->adeudoGlobal > 0;
            });

        return view('clients.open-accounts', compact('clients'));
    }

    // Opcional: Métodos si deseas habilitar la exportación directa desde esta vista
    public function exportOpenAccountsExcel()
    {
        // Reutilizamos la misma lógica exacta que alimenta tu vista web
        $clients = Client::with(['debts.payments'])
            ->get()
            ->filter(function ($client) {
                $montoMercancia = $client->debts->where('type', 'store_credit')->sum(function ($debt) {
                    return $debt->total_amount - $debt->payments->sum('amount');
                });

                $montoPrestamo = $client->debts->where('type', 'cash_loan')->sum(function ($debt) {
                    return $debt->total_amount - $debt->payments->sum('amount');
                });

                $client->montoMercanciaRestante = $montoMercancia;
                $client->montoPrestamoRestante = $montoPrestamo;
                $client->adeudoGlobal = $montoMercancia + $montoPrestamo;

                return $client->adeudoGlobal > 0;
            });

        $filename = "reporte-cuentas-abiertas-" . date('Y-m-d') . ".xls";
        $fechaDescarga = now()->format('d/m/Y H:i');

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"></head>';
        $html .= '<body>';

        $html .= '<table style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt;">';
        $html .= '<tr><td colspan="4" style="font-weight: bold; font-size: 14pt; color: #1f2937; padding-bottom: 10px;">REPORTE DE CUENTAS ABIERTAS</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4b5563;">Total con cuentas activas:</td><td colspan="2">' . $clients->count() . '</td></tr>';
        $html .= '<tr><td colspan="4">&nbsp;</td></tr>';

        $html .= '<tr>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left; background-color: #f3f4f6;">Cliente</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right; background-color: #f3f4f6;">Mercancía</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right; background-color: #f3f4f6;">Préstamos</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right; background-color: #f3f4f6;">Adeudo Global</th>';
        $html .= '</tr>';

        if ($clients->isEmpty()) {
            $html .= '<tr><td colspan="4" style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">No hay clientes con cuentas abiertas.</td></tr>';
        } else {
            foreach ($clients as $client) {
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($client->name) . ($client->alias ? ' ("' . htmlspecialchars($client->alias) . '")' : '') . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right;">$' . number_format($client->montoMercanciaRestante, 2) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right;">$' . number_format($client->montoPrestamoRestante, 2) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right; font-weight: bold; color: #b91c1c;">$' . number_format($client->adeudoGlobal, 2) . '</td>';
                $html .= '</tr>';
            }

            // Fila de Totales para Excel
            $html .= '<tr>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left; font-weight: bold; background-color: #f9fafb;">TOTAL GENERAL</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right; font-weight: bold; background-color: #f9fafb;">$' . number_format($clients->sum('montoMercanciaRestante'), 2) . '</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right; font-weight: bold; background-color: #f9fafb;">$' . number_format($clients->sum('montoPrestamoRestante'), 2) . '</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right; font-weight: bold;font-weight: bold; background-color: #f9fafb; color: #b91c1c;">$' . number_format($clients->sum('adeudoGlobal'), 2) . '</td>';
            $html .= '</tr>';
        }

        $html .= '<tr><td colspan="4">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="4" style="font-style: italic; color: #6b7280; font-size: 10pt;">Fecha de Descarga: ' . $fechaDescarga . '</td></tr>';
        $html .= '</table>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportOpenAccountsPdf()
    {
        $clients = Client::with(['debts.payments'])
            ->get()
            ->filter(function ($client) {
                $montoMercancia = $client->debts->where('type', 'store_credit')->sum(function ($debt) {
                    return $debt->total_amount - $debt->payments->sum('amount');
                });

                $montoPrestamo = $client->debts->where('type', 'cash_loan')->sum(function ($debt) {
                    return $debt->total_amount - $debt->payments->sum('amount');
                });

                $client->montoMercanciaRestante = $montoMercancia;
                $client->montoPrestamoRestante = $montoPrestamo;
                $client->adeudoGlobal = $montoMercancia + $montoPrestamo;

                return $client->adeudoGlobal > 0;
            });

        $nombreArchivo = "reporte-cuentas-abiertas-" . date('Y-m-d') . ".pdf";
        $fechaDescarga = now()->format('d/m/Y H:i');

        $html = '<html><head><meta charset="UTF-8"><style>';
        $html .= '@page { margin: 20mm 15mm 20mm 15mm; }';
        $html .= 'body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }';
        $html .= 'h2 { color: #1f2937; margin-bottom: 10px; font-size: 14pt; }';
        $html .= '.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 10px; }';
        $html .= '.summary-table td { padding: 5px 8px; vertical-align: top; }';
        $html .= 'table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= 'table.data-table th, table.data-table td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }';
        $html .= 'table.data-table th { background-color: #f3f4f6; font-weight: bold; }';
        $html .= '.text-right { text-align: right; }';
        $html .= '.text-rose { color: #b91c1c; font-weight: bold; }';
        $html .= '.footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 8pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 5px; }';
        $html .= '</style></head><body>';

        $html .= '<div class="footer">Fecha de Descarga: ' . $fechaDescarga . '</div>';
        $html .= '<h2>REPORTE DE CUENTAS ABIERTAS</h2>';

        $html .= '<table class="summary-table">';
        $html .= '<tr><td><strong>Total con cuentas activas:</strong> ' . $clients->count() . '</td></tr>';
        $html .= '</table>';

        $html .= '<table class="data-table">';
        $html .= '<tr><th>Cliente</th><th class="text-right">Mercancía</th><th class="text-right">Préstamos</th><th class="text-right">Adeudo Global</th></tr>';

        if ($clients->isEmpty()) {
            $html .= '<tr><td colspan="4" style="text-align: center;">No hay clientes con cuentas abiertas.</td></tr>';
        } else {
            foreach ($clients as $client) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($client->name) . ($client->alias ? ' <span style="color: #6366f1;">("' . htmlspecialchars($client->alias) . '")</span>' : '') . '</td>';
                $html .= '<td class="text-right">$' . number_format($client->montoMercanciaRestante, 2) . '</td>';
                $html .= '<td class="text-right">$' . number_format($client->montoPrestamoRestante, 2) . '</td>';
                $html .= '<td class="text-right text-rose">$' . number_format($client->adeudoGlobal, 2) . '</td>';
                $html .= '</tr>';
            }

            // Fila de Totales para PDF
            $html .= '<tr style="font-weight: bold; background-color: #f3f4f6;">';
            $html .= '<td style="padding: 6px; border: 1px solid #d1d5db;">TOTAL GENERAL</td>';
            $html .= '<td class="text-right" style="padding: 6px; border: 1px solid #d1d5db;">$' . number_format($clients->sum('montoMercanciaRestante'), 2) . '</td>';
            $html .= '<td class="text-right" style="padding: 6px; border: 1px solid #d1d5db;">$' . number_format($clients->sum('montoPrestamoRestante'), 2) . '</td>';
            $html .= '<td class="text-right text-rose" style="padding: 6px; border: 1px solid #d1d5db;">$' . number_format($clients->sum('adeudoGlobal'), 2) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return $pdf->download($nombreArchivo);
    }
}