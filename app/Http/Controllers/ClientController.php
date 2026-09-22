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
}