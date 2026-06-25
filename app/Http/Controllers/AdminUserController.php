<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserController extends Controller
{
    //
    public function create()
    {
        // Validación de seguridad para asegurar que solo entre el admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes autorización para ver esta página.');
        }

        return view('admin.users.create');
    }

    // Guarda el nuevo usuario en la base de datos
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Validamos los datos recibidos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string'],
        ]);

        // Creamos el usuario
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('dashboard')->with('status', '¡Usuario creado con éxito!');
    }
}
