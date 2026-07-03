<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserController extends Controller
{

    // Trae y muestra la lista de todos los usuarios
    public function index()
    {
        // Validación de seguridad (opcional, igual que en create)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes autorización para ver esta página.');
        }

        // Traemos todos los usuarios de la base de datos
        $usuarios = User::all(); 

        // Retornamos la vista (por ejemplo: admin.users.index) pasando los datos
        return view('admin.users.usuarios.index', compact('usuarios'));
    }


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
