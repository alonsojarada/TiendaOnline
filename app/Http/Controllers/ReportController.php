<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Debt;
use App\Models\Payment;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller
{
    //
    public function historialCuentas(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $clientId = $request->input('client_id');
        $activar = $request->has('activar');

        // Si no se han enviado fechas, por comodidad dejamos las del mes actual preparadas en los inputs
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->toDateString());

        $selectedClient = null;

        // Detectar si es la primera carga (cuando el usuario entra por primera vez y no ha presionado "Consultar")
        $isFirstLoad = !$request->hasAny(['activar', 'client_id', 'fecha_inicio', 'fecha_fin']);

        if ($isFirstLoad) {
            $loans = collect(); // Colección vacía para que no cargue nada al inicio
        } else {
            $query = Debt::with(['client', 'payments']);

            if ($clientId) {
                $selectedClient = Client::find($clientId);
                $query->where('client_id', $clientId);
            }

            // Si el checkbox "Activar" está marcado, filtramos por el rango de fechas
            if ($activar && $fechaInicio && $fechaFin) {
                $query->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
            }

            $loans = $query->orderBy('created_at', 'desc')->get();
        }

        return view('reports.historial-cuentas', compact('clients', 'selectedClient', 'loans', 'fechaInicio', 'fechaFin', 'activar', 'isFirstLoad'));
    }

    public function descargarPdfHistorial(Request $request)
    {
        $clientId = $request->input('client_id');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = Debt::with(['client', 'payments']);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('exports.pdf-historial-cuentas', compact('loans'));

        return $pdf->download('historial_cuentas_' . date('Y-m-d') . '.pdf');
    }

    public function ventasPorRango(Request $request)
    {
        // Si no se seleccionan fechas por defecto tomamos el mes actual
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->toDateString());
        $clientId = $request->input('client_id'); // <-- Capturamos el cliente seleccionado

        // Obtener la lista de todos los clientes para poblar el selector en la vista
        $clientes = Client::orderBy('name')->get();

        // 1. Obtener ventas de mercancía (store_credit) en el rango y opcionalmente filtradas por cliente
        $ventasMercancia = Debt::where('type', 'store_credit')
            ->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->when($clientId, function ($query) use ($clientId) {
                return $query->where('client_id', $clientId);
            })
            ->with(['client', 'user']) // <-- Cargamos la relación del usuario y cliente
            ->get()
            ->map(function ($item) {
                $item->type = 'store_credit'; // Aseguramos la propiedad type para la vista unificada
                return $item;
            });

        // 2. Obtener créditos en efectivo (cash_loan) en el rango y opcionalmente filtrados por cliente
        $prestamosEfectivo = Debt::where('type', 'cash_loan')
            ->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->when($clientId, function ($query) use ($clientId) {
                return $query->where('client_id', $clientId);
            })
            ->with(['client', 'user']) // <-- Cargamos la relación del usuario y cliente
            ->get()
            ->map(function ($item) {
                $item->type = 'cash_loan'; // Aseguramos la propiedad type para la vista unificada
                return $item;
            });

        // 3. Calcular totales
        $totalMercancia = $ventasMercancia->sum('total_amount');
        $totalPrestamos = $prestamosEfectivo->sum('total_amount');
        $granTotal = $totalMercancia + $totalPrestamos;

        // ==========================================
        // INTERCEPTAR PETICIÓN DE EXPORTACIÓN A EXCEL
        // ==========================================
        if ($request->has('export') && $request->input('export') === 'excel') {
            $fileName = 'reporte_general_salidas_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($ventasMercancia, $prestamosEfectivo) {
                $file = fopen('php://output', 'w');
                // Añadir BOM de UTF-8 para que Excel muestre correctamente los acentos y la eñe
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Cabeceras de columnas alineadas a tu vista (incluyendo "Registrado por")
                fputcsv($file, ['Fecha', 'Tipo', 'Cliente', 'Dirección', 'Concepto / Descripción', 'Estado', 'Registrado por', 'Monto']);

                // Unir y ordenar ambas colecciones cronológicamente por fecha de creación
                $todosMovimientos = $ventasMercancia->concat($prestamosEfectivo)->sortByDesc('created_at');

                // Rellenar filas con los datos reales
                foreach ($todosMovimientos as $debt) {
                    $client = optional($debt->client);
                    $clientName = trim($client->name . ' ' . ($client->alias ?? '')) ?: 'Cliente General';
                    $clientAddress = $client->address ?: 'S/D';
                    $tipoTexto = ($debt->type === 'store_credit') ? 'Mercancía' : 'Efectivo';
                    $concepto = '#' . $debt->id . ' - ' . ($debt->concept ?? ($debt->type === 'cash_loan' ? 'Préstamo en efectivo' : 'Venta de mercancía'));
                    $estado = ($debt->status === 'paid') ? 'Liquidado' : 'Pendiente';
                    $userName = optional($debt->user)->name ?? 'N/A';
                    $monto = $debt->total_amount ?? 0;

                    fputcsv($file, [
                        $debt->created_at ? $debt->created_at->format('d/m/Y H:i') : 'N/A',
                        $tipoTexto,
                        $clientName,
                        $clientAddress,
                        $concepto,
                        $estado,
                        $userName,
                        $monto
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('reports.ventas', compact(
            'ventasMercancia',
            'prestamosEfectivo',
            'totalMercancia',
            'totalPrestamos',
            'granTotal',
            'fechaInicio',
            'fechaFin',
            'clientes',       // <-- Pasamos los clientes a la vista
            'clientId'        // <-- Pasamos el cliente seleccionado para mantener el estado en el select
        ));
    }

    public function cobros(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->endOfMonth()->toDateString());
        $clientId = $request->input('client_id');

        // 1. Agregamos 'user' dentro del array de relaciones
        $pagosQuery = Payment::with(['debt.client', 'user'])
            ->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->when($clientId, function ($query, $clientId) {
                return $query->whereHas('debt', function ($q) use ($clientId) {
                    $q->where('client_id', $clientId);
                });
            })
            ->orderByDesc('created_at');

        $pagosDelPeriodo = $pagosQuery->get();

        // ==========================================
        // SI EL USUARIO HIZO CLIC EN EXPORTAR (CSV)
        // ==========================================
        if ($request->has('export') && $request->input('export') === 'excel') {
            $fileName = 'reporte_abonos_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($pagosDelPeriodo) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Cabeceras del CSV incluyendo "Registrado por"
                fputcsv($file, ['Fecha', 'Tipo de Crédito', 'Cliente', 'Concepto / Descripción', 'Dirección', 'Registrado por', 'Monto']);

                foreach ($pagosDelPeriodo as $payment) {
                    $client = optional($payment->debt)->client;
                    $clientName = $client ? trim($client->name . ' ' . $client->alias) : 'Cliente General';
                    $clientAddress = $client && $client->address ? $client->address : 'S/D';
                    $tipoTexto = (optional($payment->debt)->type === 'store_credit') ? 'Mercancía' : 'Crédito Efectivo';
                    $concepto = optional($payment->debt)->concept ?? 'Abonado a cuenta / Nota #' . $payment->debt_id;

                    // Obtenemos el nombre del usuario de forma segura
                    $userName = optional($payment->user)->name ?? 'N/A';

                    fputcsv($file, [
                        $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : 'N/A',
                        $tipoTexto,
                        $clientName,
                        $concepto,
                        $clientAddress,
                        $userName,
                        $payment->amount
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $totalCobrado = $pagosDelPeriodo->sum('amount');
        $clientes = Client::orderBy('name')->get();

        return view('reports.abonos', compact('pagosDelPeriodo', 'totalCobrado', 'fechaInicio', 'fechaFin', 'clientId', 'clientes'));
    }

    public function reporteMensual(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        // 1. Usamos el modelo Debt (asegúrate de importar tu modelo Debt arriba si es necesario)
        $ventas = Debt::whereYear('created_at', $year)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m');
            })
            ->map(function ($group) {
                return $group->sum('total_amount');
            });

        // 2. Usamos el modelo Payment (asegúrate de importar tu modelo Payment arriba si es necesario)
        $cobros = Payment::whereYear('created_at', $year)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m');
            })
            ->map(function ($group) {
                return $group->sum('amount');
            });

        // Unimos los meses que tengan registros
        $meses = $ventas->keys()->merge($cobros->keys())->unique()->sortDesc();

        $reporte = $meses->map(function ($mes) use ($ventas, $cobros) {
            $vendido = $ventas->get($mes, 0);
            $cobrado = $cobros->get($mes, 0);

            $nombreMes = Carbon::createFromFormat('Y-m', $mes)->locale('es')->isoFormat('MMMM YYYY');

            return [
                'mes_key' => $mes,
                'mes_nombre' => ucfirst($nombreMes),
                'total_vendido' => $vendido,
                'total_cobrado' => $cobrado,
            ];
        });

        // Años disponibles obtenidos de forma segura con Eloquent
        $aniosDebts = Debt::selectRaw('YEAR(created_at) as year')->distinct();
        $aniosDisponibles = Payment::selectRaw('YEAR(created_at) as year')
            ->union($aniosDebts)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($aniosDisponibles->isEmpty()) {
            $aniosDisponibles = collect([Carbon::now()->year]);
        }

        return view('reports.reporte-mensual', compact('reporte', 'year', 'aniosDisponibles'));
    }

   
}
