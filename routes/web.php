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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TandaController;

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
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');

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
    Route::get('/debts/{loan}/pdf', [DebtController::class, 'exportLoanPdf'])->name('debts.pdf'); // Para préstamos en efectivo

    Route::get('/debts/{id}', [DebtController::class, 'show'])->name('debts.details');
    Route::post('/debts/{debt}/installments/{installment}/pay', [DebtController::class, 'payInstallment'])->name('installments.pay');
    Route::delete('/debts/{debt}/installments/{installment}/payment', [DebtController::class, 'destroyInstallmentPayment'])->name('installments.destroyPayment');

    Route::post('/debts/{id}/liquidar', [DebtController::class, 'liquidar'])->name('debts.liquidar');
    Route::post('/debts/{debt}/capital-payment', [DebtController::class, 'storeCapitalPayment'])->name('debts.store.capital');

    Route::get('/detalles-fiado/{id}', [DebtController::class, 'show'])->name('loan-details');
    Route::get('/mercancia-fiada/detalle/{id}', [DebtController::class, 'showStoreDetails'])->name('store-details');

    Route::get('/dashboard/exportar-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/exportar-pdf', [DashboardController::class, 'exportPDF'])->name('dashboard.export.pdf');

    Route::get('/historial-cuentas', [ReportController::class, 'historialCuentas'])->name('reports.historial-cuentas');
    Route::get('/reports/historial-cuentas/pdf-download', [ReportController::class, 'descargarPdfHistorial'])->name('reports.historial.pdf-download');

    Route::get('/reports/ventas', [ReportController::class, 'ventasPorRango'])->name('reports.ventas');
    Route::get('reports/ventas/excel', [ReportController::class, 'ventasExcel'])->name('reports.ventas.excel');

    Route::get('/reports/abonos', [ReportController::class, 'cobros'])->name('reports.abonos');

    Route::get('/reports/mensual', [ReportController::class, 'reporteMensual'])->name('reports.reporte-mensual');


    Route::get('/tandas', [TandaController::class, 'index'])->name('tandas.index');
    Route::get('/tandas/create', [TandaController::class, 'create'])->name('tandas.create');
    Route::get('/tandas/cobranza', [TandaController::class, 'cobranza'])->name('tandas.cobranza');
    Route::post('/tandas', [TandaController::class, 'store'])->name('tandas.store');
    Route::post('/tandas/pagar-lote', [TandaController::class, 'procesarPagoLote'])->name('tandas.pagar.lote');

    Route::get('/tandas/reporte-global', [TandaController::class, 'reporteGlobal'])->name('tandas.reporte.global');
    Route::get('/tandas/reporte-global/excel', [TandaController::class, 'reporteGlobalExcel'])->name('tandas.reporte.global.excel');
    Route::get('/tandas/reporte-global/pdf', [TandaController::class, 'reporteGlobalPdf'])->name('tandas.reporte.global.pdf');
    Route::get('/tandas/reporte-atrasos', [TandaController::class, 'reporteAtrasosGlobal'])
        ->name('tandas.reporte.atrasos');
    Route::get('/tandas/reporte-atrasos/excel', [TandaController::class, 'exportarAtrasosExcel'])->name('tandas.reporte.atrasos.excel');
    Route::get('/tandas/reporte-atrasos/pdf', [TandaController::class, 'reporteAtrasosPdf'])->name('tandas.reporte.atrasos.pdf');

    Route::get('/tandas/reporte-entregados', [TandaController::class, 'reporteEntregadosGlobal'])->name('tandas.reporte.entregados');

    // Rutas de Exportación (Excel y PDF) para Entregados
    Route::get('/tandas/reporte-entregados/excel', [TandaController::class, 'exportarEntregadosExcel'])->name('tandas.reporte.entregados.excel');
    Route::get('/tandas/reporte-entregados/pdf', [TandaController::class, 'exportarEntregadosPdf'])->name('tandas.reporte.entregados.pdf');


    Route::get('/tandas/{tanda}', [TandaController::class, 'show'])->name('tandas.show');

    Route::get('/tandas/{tanda}/participante/{participante}/cuotas', [TandaController::class, 'cuotasParticipante'])->name('tandas.participante.cuotas');

    Route::post('/tandas/cuotas/{cuota}/pagar', [TandaController::class, 'pagarCuota'])
        ->name('tandas.cuotas.pagar');

    Route::post('/tandas/participantes/{participante}/entregar', [TandaController::class, 'marcarEntregado'])
        ->name('tandas.participantes.entregar');

    Route::patch('/tandas/{tanda}/participantes/{participante}/asignar', [TandaController::class, 'asignarCliente'])->name('tandas.participantes.asignar');
    Route::patch('/tandas/{tanda}/participantes/{participante}/quitar', [TandaController::class, 'quitarCliente'])->name('tandas.participantes.quitar-cliente');
    Route::delete('/tandas/{tanda}', [TandaController::class, 'destroy'])->name('tandas.destroy');
    Route::delete('/tandas/cuotas/{cuota}/eliminar-pago', [TandaController::class, 'eliminarPago'])->name('tandas.cuotas.eliminar');


    Route::get('/tandas/{tanda}/participante/{participante}/exportar-excel', [TandaController::class, 'exportarExcelClienteCuotas'])->name('tandas.participante.excel');
    Route::get('/tandas/{tanda}/participante/{participante}/exportar-pdf', [TandaController::class, 'exportarPdfClienteCuotas'])->name('tandas.participante.pdf');
    Route::patch('/tandas/{tanda}/participantes/{participante}/anular-entrega', [TandaController::class, 'anularEntrega'])->name('tandas.participantes.anular-entrega');



});

require __DIR__ . '/auth.php';