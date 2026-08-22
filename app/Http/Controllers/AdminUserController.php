<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;

class AdminUserController extends Controller
{
    // Listar todos los usuarios
    public function index()
    {
        // Validación de seguridad unificada
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403, 'Acceso denegado. Solo el Administrador puede gestionar usuarios.');
        }

        $usuarios = User::with('company')->get(); 

        return view('admin.users.usuarios.index', compact('usuarios'));
    }

    // Mostrar el formulario de creación
    public function create()
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403, 'Acceso denegado.');
        }

        $companies = Company::all(); 
        return view('admin.users.create', compact('companies'));
    }

    // Guardar el usuario
    public function store(Request $request)
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_id' => ['required', 'exists:companies,id'],
            'role' => ['required', 'string', 'in:admin,seller,cashier'],
            'status' => ['required', 'string', 'in:active,inactive'], // <-- Validación de estatus añadida
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $request->company_id,
            'role' => $request->role,
            'status' => $request->status, // <-- Guardar el estatus
        ]);

        return redirect()->route('usuarios.index')->with('status', '¡Usuario creado y asignado con éxito!');
    }

    // Mostrar formulario de edición
    public function edit(User $user)
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403, 'Acceso denegado.');
        }

        $companies = Company::all();
        return view('admin.users.edit', compact('user', 'companies'));
    }

    // Actualizar los datos del usuario
    public function update(Request $request, User $user)
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'company_id' => ['required', 'exists:companies,id'],
            'role' => ['required', 'string', 'in:admin,seller,cashier,user'],
            'status' => ['required', 'string', 'in:active,inactive'], // <-- Validación de estatus añadida
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Contraseña opcional al editar
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->company_id = $request->company_id;
        $user->role = $request->role;
        $user->status = $request->status; // <-- Actualizar el estatus

        // Solo actualiza la contraseña si el usuario escribió una nueva
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('usuarios.index')->with('status', '¡Usuario actualizado con éxito!');
    }
}