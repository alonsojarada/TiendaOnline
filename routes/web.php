<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ClientController;
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
    Route::get('/usuarios', [App\Http\Controllers\AdminUserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [App\Http\Controllers\AdminUserController::class, 'create'])->name('usuarios.crear');
    Route::post('/usuarios/guardar', [App\Http\Controllers\AdminUserController::class, 'store'])->name('usuarios.guardar');

    // NUEVAS RUTAS: Crear Categorías
    Route::get('/categorias', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categorias.crear');
    Route::post('/categorias/guardar', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categorias.guardar');
    Route::get('/categorias/{category}/editar', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categorias.update');

    // Ver el estado de cuenta y créditos de un cliente en específico
    Route::get('/client/{id}/accounts', [DebtController::class, 'showClientAccount'])->name('clients.accounts');
    // Guardar un nuevo préstamo o mercancía fiada
    Route::post('/debts/store', [DebtController::class, 'store'])->name('debts.store');
    // Registrar un abono a una deuda o préstamo
    Route::post('/debts/{id}/payment', [DebtController::class, 'storePayment'])->name('payments.store');

    // Listar y buscar clientes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    // Guardar un cliente nuevo (desde un formulario POST)
    Route::post('/clients/store', [ClientController::class, 'store'])->name('clients.store');
    // Ver el perfil detallado del cliente (reemplaza la ruta anterior de accounts que hicimos)
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');

    // Rutas de Deudas (Préstamos / Fiados)
    Route::post('/debts/store', [DebtController::class, 'store'])->name('debts.store');
    Route::delete('/debts/{id}', [DebtController::class, 'destroy'])->name('debts.destroy'); // Eliminar préstamo/fiado

    // Rutas de Abonos
    Route::post('/debts/{id}/payment', [DebtController::class, 'storePayment'])->name('payments.store');
    Route::delete('/payments/{id}', [DebtController::class, 'destroyPayment'])->name('payments.destroy'); // Eliminar abono
});
require __DIR__ . '/auth.php';
