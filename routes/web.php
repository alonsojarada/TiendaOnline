<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Usuarios Admin
    Route::get('/usuarios/crear', [App\Http\Controllers\AdminUserController::class, 'create'])->name('usuarios.crear');
    Route::post('/usuarios/guardar', [App\Http\Controllers\AdminUserController::class, 'store'])->name('usuarios.guardar');

    // NUEVAS RUTAS: Crear Categorías
    Route::get('/categorias', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categorias.crear');
    Route::post('/categorias/guardar', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categorias.guardar');
    Route::get('/categorias/{category}/editar', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categorias.update');
});

require __DIR__ . '/auth.php';
