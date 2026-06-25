<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Trae todas las categorías ordenadas por las más recientes
        $categories = Category::latest()->get();

        return view('admin.users.categories.index', compact('categories'));
    }

    // Muestra el formulario para crear una categoría
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.users.categories.create');
    }

    // Guarda la categoría en la base de datos
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Validamos que el nombre sea obligatorio y único
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        // Creamos la categoría generando el slug automático
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Convierte 'Tenis Deportivos' en 'tenis-deportivos'
            'description' => $request->description,
        ]);

        return redirect()->route('categorias.index')->with('status', '¡Categoría creada con éxito!');
    }

    public function edit(Category $category)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Retorna la vista pasando la categoría seleccionada
        return view('admin.users.categories.edit', compact('category'));
    }

    // Procesa la actualización en la base de datos
    public function update(Request $request, Category $category)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Validamos. El parámetro ignorar el ID ($category->id) evita que falle 
        // si el usuario guarda la categoría sin cambiar el nombre original.
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
        ]);

        // Actualizamos los datos (si usas slug automático como en tu método store, 
        // puedes recalcularlo aquí también antes de actualizar)
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            // 'slug' => Str::slug($request->name), // <-- Descomenta si usas slug automático
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada correctamente.');
    }
}