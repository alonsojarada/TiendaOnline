<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Debt;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller
{
    //
    public function historialCuentas(Request $request)
    {
        $clients = Client::orderBy('name')->get();

        $clientId = $request->input('client_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $selectedClient = null;

        // Si hay un cliente seleccionado, filtramos por él. Si no, traemos todas las deudas.
        $query = Debt::with(['client', 'payments']);

        if ($clientId) {
            $selectedClient = Client::find($clientId);
            $query->where('client_id', $clientId);
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        return view('reports.historial-cuentas', compact('clients', 'selectedClient', 'loans', 'fechaInicio', 'fechaFin'));
    }

    public function descargarPdfHistorial(Request $request)
    {
        $clientId = $request->input('client_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = Debt::with(['client', 'payments']);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('exports.pdf-historial-cuentas', compact('loans'));

        return $pdf->download('historial_cuentas_' . date('Y-m-d') . '.pdf');
    }
}
