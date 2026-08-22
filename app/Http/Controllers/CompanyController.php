<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    // Listar empresas
    public function index()
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403, 'Acceso denegado.');
        }

        $companies = Company::withCount('users')->get();
        return view('admin.companies.index', compact('companies'));
    }

    // Formulario de creación
    public function create()
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403, 'Acceso denegado.');
        }

        return view('admin.companies.create');
    }

    // Guardar empresa
    public function store(Request $request)
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies'],
            'slug' => ['required', 'string', 'max:255', 'unique:companies'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Company::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('companies.index')->with('status', '¡Empresa creada con éxito!');
    }

    // Formulario de edición
    public function edit(Company $company)
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403);
        }

        return view('admin.companies.edit', compact('company'));
    }

    // Actualizar empresa
    public function update(Request $request, Company $company)
    {
        if (!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'master' && !is_null(auth()->user()->company_id))) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name,' . $company->id],
            'slug' => ['required', 'string', 'max:255', 'unique:companies,slug,' . $company->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $company->name = $request->name;
        $company->slug = $request->slug;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->save();

        return redirect()->route('companies.index')->with('status', '¡Empresa actualizada con éxito!');
    }
}