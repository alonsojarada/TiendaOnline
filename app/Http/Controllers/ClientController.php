<?php

namespace App\Models;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    //
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
            ->paginate(10); // Paginación de 10 en 10 para mayor orden

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
        ]);

        Client::create([
            'name' => $request->name,
            'alias' => $request->alias,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado exitosamente.');
    }

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

        // Nota: Como estamos en ClientController, pasamos $client principal 
        // y si la vista necesita un objeto $loan por compatibilidad, mandamos el primero o null
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
}
