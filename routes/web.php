<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CompanyController; 
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// 1. MÓDULO EXCLUSIVO (Admin Global / Sin Empresa)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Rutas de Usuarios
    Route::get('/usuarios', [AdminUserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [AdminUserController::class, 'create'])->name('usuarios.crear');
    Route::post('/usuarios/guardar', [AdminUserController::class, 'store'])->name('usuarios.guardar');
    Route::get('/usuarios/{user}/editar', [AdminUserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [AdminUserController::class, 'update'])->name('usuarios.update');

    // Rutas de Empresas (Solo accesibles para el Admin Global)
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
});

// ==========================================
// 2. SISTEMA OPERATIVO DE LA TIENDA
// (Protegido para que el Admin Global sin empresa no pueda entrar)
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\CheckOperationalAccess::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Categorías
    Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [CategoryController::class, 'create'])->name('categorias.crear');
    Route::post('/categorias/guardar', [CategoryController::class, 'store'])->name('categorias.guardar');
    Route::get('/categorias/{category}/editar', [CategoryController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categorias.update');

    // Rutas para Productos
    Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [ProductController::class, 'create'])->name('productos.crear');
    Route::post('/productos/guardar', [ProductController::class, 'store'])->name('productos.guardar');

    // Listar y gestionar clientes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients/store', [ClientController::class, 'store'])->name('clients.store');

    // IMPORTANTE: Las rutas de exportación van ANTES de '/clients/{id}'
    Route::get('/clients/export/excel', [ClientController::class, 'exportExcel'])->name('clients.export.excel');
    Route::get('/clients/export/pdf', [ClientController::class, 'exportPdf'])->name('clients.export.pdf');

    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/client/{id}/accounts', [DebtController::class, 'showClientAccount'])->name('clients.accounts');

    // Rutas de Deudas (Préstamos / Fiados) y Abonos
    Route::post('/debts/store', [DebtController::class, 'store'])->name('debts.store');
    Route::delete('/debts/{id}', [DebtController::class, 'destroy'])->name('debts.destroy');
    Route::post('/debts/{id}/payment', [DebtController::class, 'storePayment'])->name('payments.store');
    Route::delete('/payments/{id}', [DebtController::class, 'destroyPayment'])->name('payments.destroy');

    Route::get('/credits/{id}/export-pdf', [DebtController::class, 'exportPdf'])->name('credits.export-pdf');
    Route::get('/debts/{id}', [DebtController::class, 'show'])->name('debts.details');
    Route::post('/debts/{debt}/installments/{installment}/pay', [DebtController::class, 'payInstallment'])->name('installments.pay');
    Route::delete('/debts/{debt}/installments/{installment}/payment', [DebtController::class, 'destroyInstallmentPayment'])->name('installments.destroyPayment');

    Route::post('/debts/{id}/liquidar', [DebtController::class, 'liquidar'])->name('debts.liquidar');

    Route::get('/detalles-fiado/{id}', [DebtController::class, 'show'])->name('loan-details');
    Route::get('/mercancia-fiada/detalle/{id}', [DebtController::class, 'showStoreDetails'])->name('store-details');

    Route::get('/dashboard/exportar-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/exportar-pdf', [DashboardController::class, 'exportPDF'])->name('dashboard.export.pdf');

});

require __DIR__ . '/auth.php';