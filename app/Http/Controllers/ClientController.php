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

    // 3. Mostrar el perfil detallado de un cliente (y sus cuentas)
    public function show($id)
    {
        $client = Client::with(['debts.payments', 'debts.installments'])->findOrFail($id);
        
        // Separamos sus deudas por tipo para mostrarlas ordenadas
        $storeCredits = $client->debts->where('type', 'store_credit');
        $cashLoans = $client->debts->where('type', 'cash_loan');

        return view('clients.show', compact('client', 'storeCredits', 'cashLoans'));
    }
}
