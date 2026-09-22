<?php

namespace App\Http\Controllers;

use App\Models\Tanda;
use App\Models\TandaParticipante;
use App\Models\TandaCuota;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TandaController extends Controller
{

    // Mostrar lista de tandas de la empresa activa
    public function index()
    {
        $tandas = Tanda::where('company_id', auth()->user()->company_id)
            ->where('estado', 'activa') // <--- Agregamos este filtro
            ->with('participantes.cliente')
            ->get();

        return view('tandas.index', compact('tandas'));
    }

    // Mostrar el formulario de creación con los clientes de la empresa
    public function create()
    {
        $clientes = Client::where('company_id', auth()->user()->company_id)->get();

        return view('tandas.create', compact('clientes'));
    }

    // Procesar la creación de la tanda, turnos y cuotas calendarizadas
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'monto_cuota' => 'required|numeric|min:0',
            'frecuencia' => 'required|in:semanal,quincenal,mensual',
            'cuotas_por_entrega' => 'required|integer|min:1',
            'total_participantes' => 'required|integer|min:2',
            'modalidad' => 'required|in:con_cero,sin_cero_cargo_gradual,sin_cero_sin_cargo',
            'fecha_inicio' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $N = $request->total_participantes;
            $modalidad = $request->modalidad;
            $cuotasPorEntrega = (int) $request->cuotas_por_entrega;
            $montoFinalCuota = $request->monto_cuota;

            if ($modalidad === 'sin_cero_cargo_gradual') {
                $montoFinalCuota = $request->monto_cuota * (($N + 1) / $N);
            }

            // El total de ciclos y turnos base cambia únicamente si es 'con_cero'
            $baseCount = ($modalidad === 'con_cero') ? $N + 1 : $N;
            $totalCiclos = $baseCount * $cuotasPorEntrega;


            $tanda = Tanda::create([
                'company_id' => auth()->user()->company_id,
                'user_id' => auth()->user()->id,
                'nombre' => $request->nombre,
                'monto_cuota' => $montoFinalCuota,
                'frecuencia' => $request->frecuencia,
                'cuotas_por_entrega' => $cuotasPorEntrega, // <--- Aquí ya toma el valor correcto del formulario
                'total_participantes' => $N,
                'modalidad' => $modalidad,
                'ciclo_actual' => 1,
                'estado' => 'activa'
            ]);

            if ($modalidad === 'con_cero') {
                // El organizador (turno 0) recibe su entrega al finalizar el primer bloque de cuotas
                TandaParticipante::create([
                    'tanda_id' => $tanda->id,
                    'cliente_id' => null,
                    'turno' => 0,
                    'ciclo_entrega' => $cuotasPorEntrega,
                    'estado' => 'activo'
                ]);
            }

            // Creamos los participantes vacíos del 1 hasta N basados en el total de participantes
            for ($turnoActual = 1; $turnoActual <= $N; $turnoActual++) {
                // Si es con_cero, los participantes se desplazan un bloque; si no, se calculan normal
                if ($modalidad === 'con_cero') {
                    $cicloEntregaAsignado = ($turnoActual + 1) * $cuotasPorEntrega;
                } else {
                    $cicloEntregaAsignado = $turnoActual * $cuotasPorEntrega;
                }

                TandaParticipante::create([
                    'tanda_id' => $tanda->id,
                    'cliente_id' => null, // Se asignará posteriormente desde la vista de detalles
                    'turno' => $turnoActual,
                    'ciclo_entrega' => $cicloEntregaAsignado,
                    'estado' => 'activo'
                ]);
            }

            $fechaBase = Carbon::parse($request->fecha_inicio);

            // Función auxiliar reutilizable para calcular la fecha de cada ciclo
            $calcularFechaLimite = function ($frecuencia, $fechaBase, $ciclo) {
                return match ($frecuencia) {
                    'semanal' => (clone $fechaBase)->addWeeks($ciclo - 1),
                    'quincenal' => (function () use ($fechaBase, $ciclo) {
                            $f = clone $fechaBase;
                            $totalQuincenasAvanzar = $ciclo - 1;
                            for ($i = 0; $i < $totalQuincenasAvanzar; $i++) {
                                if ($f->day < 15) {
                                    $f->day(15);
                                } elseif ($f->day == 15) {
                                    $f->endOfMonth();
                                } else {
                                    $f->addMonth()->day(15);
                                }
                            }
                            return $f;
                        })(),
                    'mensual' => (clone $fechaBase)->addMonths($ciclo - 1),
                    default => (clone $fechaBase)->addWeeks($ciclo - 1),
                };
            };

            for ($ciclo = 1; $ciclo <= $totalCiclos; $ciclo++) {
                $fechaLimite = $calcularFechaLimite($request->frecuencia, $fechaBase, $ciclo);

                foreach ($tanda->participantes()->get() as $participante) {
                    // Si es el organizador (turno 0), creamos un registro de cuota informativa solo en su ciclo de entrega
                    if ($participante->turno === 0) {
                        if ($ciclo === $participante->ciclo_entrega) {
                            TandaCuota::create([
                                'tanda_id' => $tanda->id,
                                'tanda_participante_id' => $participante->id,
                                'ciclo' => $ciclo,
                                'fecha_limite' => $fechaLimite,
                                'monto_esperado' => 0.00,
                                'monto_pagado' => 0.00,
                                'estado' => 'pagado', // Se marca como pagado automáticamente ya que no tiene aportación
                                'user_id' => auth()->id()
                            ]);
                        }
                        continue;
                    }

                    // Para los demás participantes se generan sus cuotas normales de aportación
                    TandaCuota::create([
                        'tanda_id' => $tanda->id,
                        'tanda_participante_id' => $participante->id,
                        'ciclo' => $ciclo,
                        'fecha_limite' => $fechaLimite,
                        'monto_esperado' => $montoFinalCuota,
                        'monto_pagado' => 0.00,
                        'estado' => 'pendiente',
                        'user_id' => auth()->id()
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('tandas.index')->with('success', 'Tanda creada correctamente. Ya puedes asignar los clientes a cada turno.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error: ' . $e->getMessage()]);
        }
    }

    public function show(Request $request, Tanda $tanda)
    {
        // Asegurar que pertenezca a la empresa actual por seguridad
        if ($tanda->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        // Cargar relaciones necesarias
        $tanda->load(['participantes.cliente', 'participantes.cuotas']);

        // Obtener los clientes de la empresa actual
        $clientes = Client::where('company_id', auth()->user()->company_id)->orderBy('name', 'asc')->get();

        // Capturar el origen desde la URL (ej. ?origen=reporte)
        $origen = $request->query('origen');

        // Pasar la variable $origen a la vista
        return view('tandas.show', compact('tanda', 'clientes', 'origen'));
    }

    public function cuotasParticipante(Tanda $tanda, TandaParticipante $participante)
    {
        if ($participante->tanda_id !== $tanda->id) {
            abort(404);
        }

        // Si es el organizador (turno 0 / id_participante 0), filtramos sus hitos de entrega
        if ($participante->turno === 0) {
            $cuotas = TandaCuota::where('tanda_id', $tanda->id)
                ->where('tanda_participante_id', 0)
                ->orderBy('ciclo', 'asc')
                ->get();
        } else {
            $cuotas = $participante->cuotas()->orderBy('ciclo', 'asc')->get();
        }

        return view('tandas.participante_cuotas', compact('tanda', 'participante', 'cuotas'));
    }

    public function pagarCuota(Request $request, TandaCuota $cuota)
    {
        $request->validate([
            'monto_pagado' => 'required|numeric|min:0.01'
        ]);

        $nuevoMontoPagado = $cuota->monto_pagado + $request->monto_pagado;

        $estadoCuota = 'pendiente';
        if ($nuevoMontoPagado >= $cuota->monto_esperado) {
            $estadoCuota = 'pagado';
        } elseif ($nuevoMontoPagado > 0) {
            $estadoCuota = 'parcial';
        }

        $cuota->update([
            'monto_pagado' => $nuevoMontoPagado,
            'estado' => $estadoCuota,
            'fecha_pago' => now()
        ]);

        // VERIFICACIÓN AUTOMÁTICA: Si es un participante normal, comprobamos si ya pagó todas sus cuotas
        if ($cuota->tanda_participante_id > 0) {
            $participante = TandaParticipante::find($cuota->tanda_participante_id);
            if ($participante) {
                // Contamos cuotas pendientes o parciales de este participante
                $cuotasRestantes = TandaCuota::where('tanda_participante_id', $participante->id)
                    ->where('estado', '!=', 'pagado')
                    ->count();

                // Si ya no le quedan cuotas pendientes, se marca como completado automáticamente
                if ($cuotasRestantes === 0) {
                    $participante->update(['estado' => 'completado']);
                } else {
                    $participante->update(['estado' => 'activo']);
                }
            }
        }

        return back()->with('success', 'Pago registrado correctamente.');
    }

    public function marcarEntregado(Request $request, TandaParticipante $participante)
    {
        $request->validate([
            'fecha_entrega' => 'required|date'
        ]);

        $participante->update([
            'entregado' => true,
            'fecha_entrega' => $request->fecha_entrega
        ]);

        return back()->with('success', 'Se ha registrado la entrega de la tanda exitosamente.');
    }

    public function asignarCliente(Request $request, Tanda $tanda, TandaParticipante $participante)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clients,id'
        ]);

        $participante->update([
            'cliente_id' => $request->cliente_id
        ]);

        return back()->with('success', 'Cliente asignado correctamente al turno.');
    }

    public function quitarCliente(Tanda $tanda, TandaParticipante $participante)
    {
        $participante->update([
            'cliente_id' => null
        ]);

        return back()->with('success', 'Cliente removido del turno.');
    }

    public function destroy(Tanda $tanda)
    {
        // Opcional: Eliminar relaciones si no tienes cascada en la base de datos
        $tanda->cuotas()->delete();
        $tanda->participantes()->delete();

        // Eliminar la tanda
        $tanda->delete();

        return redirect()->route('tandas.index')->with('success', 'Tanda eliminada correctamente.');
    }

    public function eliminarPago($id)
    {
        $cuota = TandaCuota::findOrFail($id);

        // Cambiar estatus a pendiente y limpiar montos de pago
        $cuota->update([
            'estado' => 'pendiente',
            'monto_pagado' => 0,
        ]);

        return back()->with('success', 'El pago ha sido eliminado correctamente y la cuota ahora está pendiente.');
    }

    public function exportarExcelClienteCuotas($tandaId, $participanteId)
    {
        $tanda = Tanda::findOrFail($tandaId);
        $participante = TandaParticipante::with(['cuotas', 'cliente'])->findOrFail($participanteId);

        $nombreCliente = preg_replace('/[^A-Za-z0-9_\-]/', '_', ($participante->cliente->name ?? 'sin-cliente'));
        $nombreTanda = preg_replace('/[^A-Za-z0-9_\-]/', '_', $tanda->nombre);
        $nombreArchivo = "cuotas-turno-{$participante->turno}-{$nombreCliente}-{$nombreTanda}.xls";

        $cuotasPagadas = $participante->cuotas->where('estado', 'pagado')->count();
        $totalAbonado = $participante->cuotas->where('estado', 'pagado')->sum('monto_esperado');
        $totalPendiente = $participante->cuotas->where('estado', '!=', 'pagado')->sum('monto_esperado');

        $montoRetrasado = $participante->cuotas
            ->where('estado', '!=', 'pagado')
            ->where('fecha_limite', '<', now()->toDateString())
            ->sum('monto_esperado');

        $entregadoTexto = (isset($participante->entregado) && $participante->entregado) ? 'Sí' : 'No';
        $fechaDescarga = now()->format('d/m/Y H:i'); // Fecha y hora actual

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"></head>';
        $html .= '<body>';

        // Encabezado / Resumen
        $html .= '<table style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt;">';
        $html .= '<tr><td colspan="5" style="font-weight: bold; font-size: 14pt; color: #1f2937; padding-bottom: 10px; background-color: transparent;">REPORTE DE CUOTAS - ' . strtoupper($tanda->nombre) . '</td></tr>';
        $html .= '<tr><td style="font-weight: bold; color: #4b5563;">Participante / Turno:</td><td colspan="4">Turno ' . $participante->turno . ' - ' . ($participante->cliente->name ?? 'Sin asignar') . '</td></tr>';
        $html .= '<tr><td style="font-weight: bold; color: #4b5563;">Cuotas Pagadas:</td><td colspan="4">' . $cuotasPagadas . ' de ' . $participante->cuotas->count() . '</td></tr>';
        $html .= '<tr><td style="font-weight: bold; color: #4b5563;">Total Abonado:</td><td colspan="4">$' . number_format($totalAbonado, 2) . '</td></tr>';
        $html .= '<tr><td style="font-weight: bold; color: #4b5563;">Total Pendiente:</td><td colspan="4">$' . number_format($totalPendiente, 2) . '</td></tr>';
        $html .= '<tr><td style="font-weight: bold; color: #b91c1c;">Monto Retrasado:</td><td colspan="4" style="color: #b91c1c; font-weight: bold;">$' . number_format($montoRetrasado, 2) . '</td></tr>';
        $html .= '<tr><td style="font-weight: bold; color: #4b5563;">¿Entregado?:</td><td colspan="4">' . $entregadoTexto . '</td></tr>';
        $html .= '<tr><td colspan="5">&nbsp;</td></tr>';

        // Tabla de Cuotas
        $html .= '<tr style="background-color: #f3f4f6;">';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold;">Ciclo</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold;">Fecha Límite Pago</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold;">Monto</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold;">Estatus</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold;">Fecha de Pago</th>';
        $html .= '</tr>';

        foreach ($participante->cuotas as $cuota) {
            // Validación para determinar si está retrasado en la tabla
            $esRetrasado = ($cuota->estado !== 'pagado' && $cuota->fecha_limite < now()->toDateString());
            $estatusTexto = $esRetrasado ? 'Retrasado' : ucfirst($cuota->estado);
            $estatusEstilo = $esRetrasado ? 'color: #b91c1c; font-weight: bold;' : '';

            $html .= '<tr>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px;">Ciclo ' . $cuota->ciclo . '</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">' . $cuota->fecha_limite . '</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right;">$' . number_format($cuota->monto_esperado, 2) . '</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center; ' . $estatusEstilo . '">' . $estatusTexto . '</td>';
            $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">' . ($cuota->fecha_pago ?? '-') . '</td>';
            $html .= '</tr>';
        }

        // Espacio y Pie de Página (Fecha de Descarga al final)
        $html .= '<tr><td colspan="5">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="5" style="font-style: italic; color: #6b7280; font-size: 10pt;">Fecha de Descarga: ' . $fechaDescarga . '</td></tr>';

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$nombreArchivo}\"",
        ]);
    }

    public function exportarPdfClienteCuotas($tandaId, $participanteId)
    {
        $tanda = Tanda::findOrFail($tandaId);
        $participante = TandaParticipante::with(['cuotas', 'cliente'])->findOrFail($participanteId);

        $nombreCliente = preg_replace('/[^A-Za-z0-9_\-]/', '_', ($participante->cliente->name ?? 'sin-cliente'));
        $nombreTanda = preg_replace('/[^A-Za-z0-9_\-]/', '_', $tanda->nombre);
        $nombreArchivo = "cuotas-turno-{$participante->turno}-{$nombreCliente}-{$nombreTanda}.pdf";

        $cuotasPagadas = $participante->cuotas->where('estado', 'pagado')->count();
        $totalAbonado = $participante->cuotas->where('estado', 'pagado')->sum('monto_esperado');
        $totalPendiente = $participante->cuotas->where('estado', '!=', 'pagado')->sum('monto_esperado');

        $montoRetrasado = $participante->cuotas
            ->where('estado', '!=', 'pagado')
            ->where('fecha_limite', '<', now()->toDateString())
            ->sum('monto_esperado');

        $entregadoTexto = (isset($participante->entregado) && $participante->entregado) ? 'Sí' : 'No';
        $fechaDescarga = now()->format('d/m/Y H:i');

        $html = '<html><head><meta charset="UTF-8"><style>';
        $html .= '@page { margin: 20mm 15mm 20mm 15mm; }';
        $html .= 'body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }';
        $html .= 'h2 { color: #1f2937; margin-bottom: 10px; font-size: 14pt; }';
        $html .= '.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }';
        $html .= '.summary-table td { padding: 3px 0; vertical-align: top; }';
        $html .= '.text-retrasado { color: #b91c1c; font-weight: bold; }';
        $html .= 'table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= 'table.data-table th, table.data-table td { border: 1px solid #d1d5db; padding: 5px; text-align: left; }';
        $html .= 'table.data-table th { background-color: #f3f4f6; font-weight: bold; }';
        $html .= '.text-center { text-align: center; }';
        $html .= '.text-right { text-align: right; }';
        $html .= '.footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 8pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 5px; }';
        $html .= '</style></head><body>';

        $html .= '<div class="footer">Fecha de Descarga: ' . $fechaDescarga . '</div>';

        $html .= '<h2>REPORTE DE CUOTAS - ' . strtoupper($tanda->nombre) . '</h2>';

        // Resumen en 2 Columnas para PDF
        $html .= '<table class="summary-table">';
        $html .= '<tr>';
        $html .= '<td style="width: 50%; padding-right: 10px;">';
        $html .= '<strong>Participante / Turno:</strong> Turno ' . $participante->turno . ' - ' . ($participante->cliente->name ?? 'Sin asignar') . '<br>';
        $html .= '<strong>Cuotas Pagadas:</strong> ' . $cuotasPagadas . ' de ' . $participante->cuotas->count() . '<br>';
        $html .= '<strong>Total Abonado:</strong> $' . number_format($totalAbonado, 2);
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding-left: 10px;">';
        $html .= '<strong>Total Pendiente:</strong> $' . number_format($totalPendiente, 2) . '<br>';
        $html .= '<span class="text-retrasado">Monto Retrasado: $' . number_format($montoRetrasado, 2) . '</span><br>';
        $html .= '<strong>¿Entregado?:</strong> ' . $entregadoTexto;
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        // Tabla principal de cuotas
        $html .= '<table class="data-table">';
        $html .= '<tr><th>Ciclo</th><th>Fecha Límite Pago</th><th>Monto</th><th>Estatus</th><th>Fecha de Pago</th></tr>';

        foreach ($participante->cuotas as $cuota) {
            // Validación para estatus dinámico en PDF
            $esRetrasado = ($cuota->estado !== 'pagado' && $cuota->fecha_limite < now()->toDateString());
            $estatusTexto = $esRetrasado ? 'Retrasado' : ucfirst($cuota->estado);
            $estatusClase = $esRetrasado ? 'text-retrasado' : '';

            $html .= '<tr>';
            $html .= '<td>Ciclo ' . $cuota->ciclo . '</td>';
            $html .= '<td class="text-center">' . $cuota->fecha_limite . '</td>';
            $html .= '<td class="text-right">$' . number_format($cuota->monto_esperado, 2) . '</td>';
            $html .= '<td class="text-center ' . $estatusClase . '">' . $estatusTexto . '</td>';
            $html .= '<td class="text-center">' . ($cuota->fecha_pago ?? '-') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return $pdf->download($nombreArchivo);
    }

    public function anularEntrega($tandaId, $participanteId)
    {
        $participante = TandaParticipante::where('tanda_id', $tandaId)
            ->where('id', $participanteId)
            ->firstOrFail();

        $participante->update([
            'entregado' => false,
            'fecha_entrega' => null,
        ]);

        return redirect()->back()->with('success', 'La entrega ha sido anulada con éxito.');
    }

    public function cobranza()
    {
        $hoy = Carbon::today();
        $finSemana = Carbon::now()->endOfWeek();

        // Cuotas Vencidas agrupadas por participante (cliente + tanda)
        $cuotasVencidas = TandaCuota::with(['participante.tanda', 'participante.cliente'])
            ->where('estado', '!=', 'pagado')
            ->whereDate('fecha_limite', '<', $hoy)
            ->get()
            ->groupBy('tanda_participante_id')
            ->map(function ($cuotasDelParticipante) {
                $primeraCuota = $cuotasDelParticipante->sortBy('fecha_limite')->first();
                $primeraCuota->total_pendiente = $cuotasDelParticipante->sum(fn($c) => $c->monto_esperado - $c->monto_pagado);
                $primeraCuota->cantidad_atrasadas = $cuotasDelParticipante->count();
                $primeraCuota->fecha_mas_antigua = $cuotasDelParticipante->min('fecha_limite');
                return $primeraCuota;
            })
            ->sortBy('fecha_mas_antigua');

        // Cuotas que Vencen Esta Semana agrupadas por participante
        $cuotasEstaSemana = TandaCuota::with(['participante.tanda', 'participante.cliente'])
            ->where('estado', '!=', 'pagado')
            ->whereBetween('fecha_limite', [$hoy, $finSemana])
            ->get()
            ->groupBy('tanda_participante_id')
            ->map(function ($cuotasDelParticipante) {
                $primeraCuota = $cuotasDelParticipante->sortBy('fecha_limite')->first();
                $primeraCuota->total_pendiente = $cuotasDelParticipante->sum(fn($c) => $c->monto_esperado - $c->monto_pagado ?? $c->monto_esperado - $c->monto_pagado);
                $primeraCuota->cantidad_proximas = $cuotasDelParticipante->count();
                $primeraCuota->fecha_proxima = $primeraCuota->fecha_limite;
                return $primeraCuota;
            })
            ->sortBy('fecha_proxima');

        return view('tandas.cobranza', compact('cuotasVencidas', 'cuotasEstaSemana'));
    }

   public function procesarPagoLote(Request $request)
{
    $request->validate([
        'tanda_participante_id' => 'required|exists:tanda_participantes,id',
        'cantidad_cuotas' => 'required|integer|min:1',
    ]);

    $participanteId = $request->tanda_participante_id;
    $cantidadACobrar = $request->cantidad_cuotas;

    // Tomamos exactamente el número de cuotas pendientes ordenadas de la más antigua a la más nueva
    $cuotasAPagar = TandaCuota::whereHas('participante', function ($q) use ($participanteId) {
        $q->where('id', $participanteId);
    })
        ->where('estado', '!=', 'pagado')
        ->orderBy('fecha_limite', 'asc')
        ->limit($cantidadACobrar)
        ->get();

    foreach ($cuotasAPagar as $cuota) {
        // Se liquida la cuota completa de forma estricta
        $cuota->monto_pagado = $cuota->monto_esperado;
        $cuota->estado = 'pagado';
        
        // Actualizamos fecha de pago y el usuario que realiza el registro
        $cuota->fecha_pago = now();
        $cuota->user_id =  auth()->id();
        
        $cuota->save();
    }

    return redirect()->route('tandas.cobranza')->with('success', "Se han marcado exitosamente {$cuotasAPagar->count()} cuota(s) como pagadas.");
}

    public function reporteGlobal(Request $request)
    {
        $companyId = auth()->user()->company_id;

        // Verificamos si el usuario dio clic en "Ver todo" o si envió fechas
        $verTodo = $request->has('todo');
        $hasFiltered = $request->filled('fecha_inicio') && $request->filled('fecha_fin');

        // Si NO ha filtrado Y TAMPOCO dio en "Ver todo", inicializamos vacío
        if (!$hasFiltered && !$verTodo) {
            $tandas = collect();
            $totalRecaudadoGlobal = 0;
            $totalEntregadoGlobal = 0;
            $totalFondoGlobal = 0;
            $totalVencidoGlobal = 0;
            $balanceNeto = 0;

            return view('tandas.reporte-global', compact(
                'tandas',
                'totalFondoGlobal',
                'totalRecaudadoGlobal',
                'totalEntregadoGlobal',
                'balanceNeto',
                'totalVencidoGlobal'
            ));
        }

        // --- OBTENEMOS LAS TANDAS DE LA EMPRESA ---
        $query = Tanda::where('company_id', $companyId)
            ->with(['participantes.cliente', 'participantes.cuotas']);

        // Si filtró por fechas, aplicamos la condición sobre la primera cuota
        $tandas = $query->get();

        if ($hasFiltered) {
            $fechaInicioFiltro = $request->input('fecha_inicio');
            $fechaFinFiltro = $request->input('fecha_fin');

            $tandas = $tandas->filter(function ($tanda) use ($fechaInicioFiltro, $fechaFinFiltro) {
                $todasLasCuotas = $tanda->participantes->flatMap->cuotas->sortBy('fecha_limite');

                if ($todasLasCuotas->isEmpty()) {
                    return false;
                }

                $tandaInicio = Carbon::parse($todasLasCuotas->first()->fecha_limite)->format('Y-m-d');
                return $tandaInicio >= $fechaInicioFiltro && $tandaInicio <= $fechaFinFiltro;
            });
        }

        // Inicializamos acumuladores globales
        $totalRecaudadoGlobal = 0;
        $totalEntregadoGlobal = 0;
        $totalFondoGlobal = 0;
        $totalVencidoGlobal = 0;

        foreach ($tandas as $tanda) {
            $participantesNormales = $tanda->participantes->filter(function ($p) {
                return $p->turno !== 0;
            });
            $numIntegrantes = $participantesNormales->count();
            $cuotasPorCiclo = 4;
            $montoPozoCiclo = $cuotasPorCiclo * $tanda->monto_cuota * $numIntegrantes;

            $tandaEntregado = 0;

            foreach ($tanda->participantes as $p) {
                $totalFondoGlobal += $p->cuotas->sum('monto_esperado');
                $totalRecaudadoGlobal += $p->cuotas->where('estado', 'pagado')->sum('monto_pagado');

                if ($p->entregado) {
                    if ($p->turno === 0) {
                        $tandaEntregado += $p->cuotas->sum('monto_esperado');
                    } else {
                        $tandaEntregado += $montoPozoCiclo;
                    }
                }

                foreach ($p->cuotas as $c) {
                    if ($c->estado !== 'pagado' && Carbon::parse($c->fecha_limite)->isPast()) {
                        $totalVencidoGlobal += ($c->monto_esperado - $c->monto_pagado);
                    }
                }
            }

            $totalEntregadoGlobal += $tandaEntregado;
        }

        $balanceNeto = $totalRecaudadoGlobal - $totalEntregadoGlobal;

        return view('tandas.reporte-global', compact(
            'tandas',
            'totalFondoGlobal',
            'totalRecaudadoGlobal',
            'totalEntregadoGlobal',
            'balanceNeto',
            'totalVencidoGlobal'
        ));
    }

    public function reporteGlobalPdf(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $verTodo = $request->has('todo');
        $hasFiltered = $request->filled('fecha_inicio') && $request->filled('fecha_fin');

        if (!$hasFiltered && !$verTodo) {
            $tandas = collect();
        } else {
            $tandas = Tanda::where('company_id', $companyId)
                ->with(['participantes.cliente', 'participantes.cuotas'])
                ->get();

            if ($hasFiltered) {
                $fechaInicioFiltro = $request->input('fecha_inicio');
                $fechaFinFiltro = $request->input('fecha_fin');

                $tandas = $tandas->filter(function ($tanda) use ($fechaInicioFiltro, $fechaFinFiltro) {
                    $todasLasCuotas = $tanda->participantes->flatMap->cuotas->sortBy('fecha_limite');
                    if ($todasLasCuotas->isEmpty())
                        return false;
                    $tandaInicio = Carbon::parse($todasLasCuotas->first()->fecha_limite)->format('Y-m-d');
                    return $tandaInicio >= $fechaInicioFiltro && $tandaInicio <= $fechaFinFiltro;
                });
            }
        }

        // Calcular los totales globales (KPIs)
        $totalRecaudadoGlobal = 0;
        $totalEntregadoGlobal = 0;
        $totalFondoGlobal = 0;
        $totalVencidoGlobal = 0;

        foreach ($tandas as $tanda) {
            $participantesNormales = $tanda->participantes->filter(fn($p) => $p->turno !== 0);
            $numIntegrantes = $participantesNormales->count();
            $montoPozoCiclo = 4 * $tanda->monto_cuota * $numIntegrantes;

            $tandaEntregado = 0;
            foreach ($tanda->participantes as $p) {
                $totalFondoGlobal += $p->cuotas->sum('monto_esperado');
                $totalRecaudadoGlobal += $p->cuotas->where('estado', 'pagado')->sum('monto_pagado');

                if ($p->entregado) {
                    if ($p->turno === 0) {
                        $tandaEntregado += $p->cuotas->sum('monto_esperado');
                    } else {
                        $tandaEntregado += $montoPozoCiclo;
                    }
                }

                foreach ($p->cuotas as $c) {
                    if ($c->estado !== 'pagado' && Carbon::parse($c->fecha_limite)->isPast()) {
                        $totalVencidoGlobal += ($c->monto_esperado - $c->monto_pagado);
                    }
                }
            }
            $totalEntregadoGlobal += $tandaEntregado;
        }

        $balanceNeto = $totalRecaudadoGlobal - $totalEntregadoGlobal;
        $nombreArchivo = "reporte-global-tandas-" . date('Y-m-d') . ".pdf";
        $fechaDescarga = now()->format('d/m/Y H:i');

        // Construcción del HTML con la misma lógica del PDF que compartiste
        $html = '<html><head><meta charset="UTF-8"><style>';
        $html .= '@page { margin: 20mm 15mm 20mm 15mm; }';
        $html .= 'body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }';
        $html .= 'h2 { color: #1f2937; margin-bottom: 10px; font-size: 14pt; }';
        $html .= '.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 10px; }';
        $html .= '.summary-table td { padding: 5px 8px; vertical-align: top; }';
        $html .= '.text-retrasado { color: #b91c1c; font-weight: bold; }';
        $html .= '.text-negativo { color: #b91c1c; font-weight: bold; }';
        $html .= 'table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= 'table.data-table th, table.data-table td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }';
        $html .= 'table.data-table th { background-color: #f3f4f6; font-weight: bold; }';
        $html .= '.text-center { text-align: center; }';
        $html .= '.text-right { text-align: right; }';
        $html .= '.footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 8pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 5px; }';
        $html .= '</style></head><body>';

        $html .= '<div class="footer">Fecha de Descarga: ' . $fechaDescarga . '</div>';

        $html .= '<h2>REPORTE GLOBAL DE TANDAS</h2>';

        // Resumen / Tarjetas de Totales Arriba en 2 Columnas
        $html .= '<table class="summary-table">';
        $html .= '<tr>';
        $html .= '<td style="width: 50%;">';
        $html .= '<strong>Fondo Global Total:</strong> $' . number_format($totalFondoGlobal, 2) . '<br>';
        $html .= '<strong>Total Cobrado:</strong> $' . number_format($totalRecaudadoGlobal, 2) . '<br>';
        $html .= '<strong>Total Entregado:</strong> $' . number_format($totalEntregadoGlobal, 2);
        $html .= '</td>';
        $html .= '<td style="width: 50%;">';
        $balanceClase = $balanceNeto < 0 ? 'text-negativo' : '';
        $html .= '<span class="' . $balanceClase . '"><strong>Balance / Utilidad:</strong> $' . number_format($balanceNeto, 2) . '</span><br>';
        $html .= '<span class="text-retrasado">Vencido Global: $' . number_format($totalVencidoGlobal, 2) . '</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        // Tabla Principal del Reporte Global
        $html .= '<table class="data-table">';
        $html .= '<tr><th>Nombre de la Tanda</th><th class="text-center">Estado</th><th class="text-center">Participantes</th><th class="text-right">Fondo Total</th><th class="text-right">Cobrado</th><th class="text-right">Entregado</th><th class="text-right">Utilidad</th></tr>';

        if ($tandas->isEmpty()) {
            $html .= '<tr><td colspan="7" class="text-center">No hay tandas registradas en el periodo seleccionado.</td></tr>';
        } else {
            foreach ($tandas as $tanda) {
                $numIntegrantes = $tanda->participantes->filter(fn($p) => $p->turno !== 0)->count();
                $fondoTanda = $tanda->participantes->sum(fn($p) => $p->cuotas->sum('monto_esperado'));
                $cobradoTanda = $tanda->participantes->sum(fn($p) => $p->cuotas->where('estado', 'pagado')->sum('monto_pagado'));

                $montoPozoCiclo = 4 * $tanda->monto_cuota * $numIntegrantes;
                $entregadoTanda = 0;
                foreach ($tanda->participantes as $p) {
                    if ($p->entregado) {
                        $entregadoTanda += ($p->turno === 0) ? $p->cuotas->sum('monto_esperado') : $montoPozoCiclo;
                    }
                }
                $utilidadTanda = $cobradoTanda - $entregadoTanda;
                $utilidadClase = $utilidadTanda < 0 ? 'text-negativo' : '';

                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($tanda->nombre) . '</td>';
                $html .= '<td class="text-center">' . ucfirst($tanda->estado ?? 'Activa') . '</td>';
                $html .= '<td class="text-center">' . $numIntegrantes . '</td>';
                $html .= '<td class="text-right">$' . number_format($fondoTanda, 2) . '</td>';
                $html .= '<td class="text-right">$' . number_format($cobradoTanda, 2) . '</td>';
                $html .= '<td class="text-right">$' . number_format($entregadoTanda, 2) . '</td>';
                $html .= '<td class="text-right ' . $utilidadClase . '">$' . number_format($utilidadTanda, 2) . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</table></body></html>';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return $pdf->download($nombreArchivo);
    }


    public function reporteGlobalExcel(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $verTodo = $request->has('todo');
        $hasFiltered = $request->filled('fecha_inicio') && $request->filled('fecha_fin');

        if (!$hasFiltered && !$verTodo) {
            $tandas = collect();
        } else {
            $tandas = Tanda::where('company_id', $companyId)
                ->with(['participantes.cliente', 'participantes.cuotas'])
                ->get();

            if ($hasFiltered) {
                $fechaInicioFiltro = $request->input('fecha_inicio');
                $fechaFinFiltro = $request->input('fecha_fin');

                $tandas = $tandas->filter(function ($tanda) use ($fechaInicioFiltro, $fechaFinFiltro) {
                    $todasLasCuotas = $tanda->participantes->flatMap->cuotas->sortBy('fecha_limite');
                    if ($todasLasCuotas->isEmpty())
                        return false;
                    $tandaInicio = Carbon::parse($todasLasCuotas->first()->fecha_limite)->format('Y-m-d');
                    return $tandaInicio >= $fechaInicioFiltro && $tandaInicio <= $fechaFinFiltro;
                });
            }
        }

        // Calcular los totales globales (KPIs)
        $totalRecaudadoGlobal = 0;
        $totalEntregadoGlobal = 0;
        $totalFondoGlobal = 0;
        $totalVencidoGlobal = 0;

        foreach ($tandas as $tanda) {
            $participantesNormales = $tanda->participantes->filter(fn($p) => $p->turno !== 0);
            $numIntegrantes = $participantesNormales->count();
            $montoPozoCiclo = 4 * $tanda->monto_cuota * $numIntegrantes;

            $tandaEntregado = 0;
            foreach ($tanda->participantes as $p) {
                $totalFondoGlobal += $p->cuotas->sum('monto_esperado');
                $totalRecaudadoGlobal += $p->cuotas->where('estado', 'pagado')->sum('monto_pagado');

                if ($p->entregado) {
                    if ($p->turno === 0) {
                        $tandaEntregado += $p->cuotas->sum('monto_esperado');
                    } else {
                        $tandaEntregado += $montoPozoCiclo;
                    }
                }

                foreach ($p->cuotas as $c) {
                    if ($c->estado !== 'pagado' && Carbon::parse($c->fecha_limite)->isPast()) {
                        $totalVencidoGlobal += ($c->monto_esperado - $c->monto_pagado);
                    }
                }
            }
            $totalEntregadoGlobal += $tandaEntregado;
        }

        $balanceNeto = $totalRecaudadoGlobal - $totalEntregadoGlobal;
        $filename = "reporte-global-tandas-" . date('Y-m-d') . ".xls";
        $fechaDescarga = now()->format('d/m/Y H:i');

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"></head>';
        $html .= '<body>';

        // Estructura principal en tabla HTML para Excel
        $html .= '<table style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt;">';

        // Título del Reporte
        $html .= '<tr><td colspan="7" style="font-weight: bold; font-size: 14pt; color: #1f2937; padding-bottom: 10px; background-color: transparent;">REPORTE GLOBAL DE TANDAS</td></tr>';

        // Sección de Totales / KPIs Arriba
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4b5563;">Fondo Global Total:</td><td colspan="5">$' . number_format($totalFondoGlobal, 2) . '</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4b5563;">Total Cobrado:</td><td colspan="5">$' . number_format($totalRecaudadoGlobal, 2) . '</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4b5563;">Total Entregado:</td><td colspan="5">$' . number_format($totalEntregadoGlobal, 2) . '</td></tr>';

        $balanceEstilo = $balanceNeto < 0 ? 'color: #b91c1c; font-weight: bold;' : '';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4b5563;">Balance / Utilidad:</td><td colspan="5" style="' . $balanceEstilo . '">$' . number_format($balanceNeto, 2) . '</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #b91c1c;">Vencido Global:</td><td colspan="5" style="color: #b91c1c; font-weight: bold;">$' . number_format($totalVencidoGlobal, 2) . '</td></tr>';
        $html .= '<tr><td colspan="7">&nbsp;</td></tr>';

        // Cabecera de la Tabla Principal
        $html .= '<tr style="background-color: #f3f4f6;">';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left;">Nombre de la Tanda</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: center;">Estado</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: center;">Participantes</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right;">Fondo Total</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right;">Cobrado</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right;">Entregado</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right;">Utilidad</th>';
        $html .= '</tr>';

        // Filas de Datos
        if ($tandas->isEmpty()) {
            $html .= '<tr><td colspan="7" style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">No hay tandas registradas en el periodo seleccionado.</td></tr>';
        } else {
            foreach ($tandas as $tanda) {
                $numIntegrantes = $tanda->participantes->filter(fn($p) => $p->turno !== 0)->count();
                $fondoTanda = $tanda->participantes->sum(fn($p) => $p->cuotas->sum('monto_esperado'));
                $cobradoTanda = $tanda->participantes->sum(fn($p) => $p->cuotas->where('estado', 'pagado')->sum('monto_pagado'));

                $montoPozoCiclo = 4 * $tanda->monto_cuota * $numIntegrantes;
                $entregadoTanda = 0;
                foreach ($tanda->participantes as $p) {
                    if ($p->entregado) {
                        $entregadoTanda += ($p->turno === 0) ? $p->cuotas->sum('monto_esperado') : $montoPozoCiclo;
                    }
                }
                $utilidadTanda = $cobradoTanda - $entregadoTanda;
                $utilidadEstilo = $utilidadTanda < 0 ? 'color: #b91c1c; font-weight: bold;' : '';

                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($tanda->nombre) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">' . ucfirst($tanda->estado ?? 'Activa') . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">' . $numIntegrantes . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right;">$' . number_format($fondoTanda, 2) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right;">$' . number_format($cobradoTanda, 2) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right;">$' . number_format($entregadoTanda, 2) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right; ' . $utilidadEstilo . '">$' . number_format($utilidadTanda, 2) . '</td>';
                $html .= '</tr>';
            }
        }

        // Pie de Página con Fecha de Descarga
        $html .= '<tr><td colspan="7">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="7" style="font-style: italic; color: #6b7280; font-size: 10pt;">Fecha de Descarga: ' . $fechaDescarga . '</td></tr>';

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function reporteAtrasosGlobal(Request $request)
    {
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $filtro = $request->query('filtro'); // Recogemos el parámetro de control

        $clientesConRetraso = collect();

        // Permitimos la carga si hay fechas o si el filtro es exactamente 'todos'
        if (!empty($fechaInicio) || !empty($fechaFin) || $filtro === 'todos') {
            $tandas = Tanda::with('participantes.cliente', 'participantes.cuotas')->get();

            foreach ($tandas as $tanda) {
                foreach ($tanda->participantes as $participante) {
                    $cliente = $participante->cliente;
                    if (!$cliente)
                        continue;

                    $cuotasAtrasadas = $participante->cuotas->filter(function ($c) use ($fechaInicio, $fechaFin) {
                        if ($c->estado === 'pagado' || !Carbon::parse($c->fecha_limite)->isPast()) {
                            return false;
                        }

                        // Si se seleccionaron fechas, filtramos por el rango
                        if (!empty($fechaInicio) || !empty($fechaFin)) {
                            $fechaCuota = Carbon::parse($c->fecha_limite)->startOfDay();

                            if ($fechaInicio && $fechaCuota->lt(Carbon::parse($fechaInicio)->startOfDay())) {
                                return false;
                            }
                            if ($fechaFin && $fechaCuota->gt(Carbon::parse($fechaFin)->endOfDay())) {
                                return false;
                            }
                        }

                        return true;
                    });

                    if ($cuotasAtrasadas->count() > 0) {
                        $montoRetrasado = $cuotasAtrasadas->sum(function ($c) {
                            return $c->monto_esperado - $c->monto_pagado;
                        });

                        $origenCliente = $cliente->address ?? $cliente->origen ?? 'N/D';

                        $clientesConRetraso->push([
                            'tanda_id' => $tanda->id,
                            'tanda_nombre' => $tanda->nombre,
                            'cliente_nombre' => $cliente->nombre ?? $cliente->name ?? 'Sin nombre',
                            'telefono' => $cliente->telefono ?? 'N/A',
                            'origen' => $origenCliente,
                            'turno' => $participante->turno,
                            'cuotas_atrasadas' => $cuotasAtrasadas->count(),
                            'monto_retrasado' => $montoRetrasado,
                        ]);
                    }
                }
            }

            $clientesConRetraso = $clientesConRetraso->sortByDesc('monto_retrasado');
        }

        return view('tandas.reporte-atrasos', compact('clientesConRetraso', 'fechaInicio', 'fechaFin'));
    }


    public function exportarAtrasosExcel(Request $request)
    {
        $companyId = auth()->user()->company_id ?? null;

        $verTodo = $request->query('filtro') === 'todos' || $request->has('todo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $hasFiltered = !empty($fechaInicio) || !empty($fechaFin);

        $clientesConRetraso = collect();

        // Validamos si debe procesar datos o retornar vacío
        if ($hasFiltered || $verTodo) {
            $query = Tanda::query();

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $tandas = $query->with(['participantes.cliente', 'participantes.cuotas'])->get();

            foreach ($tandas as $tanda) {
                foreach ($tanda->participantes as $participante) {
                    $cliente = $participante->cliente;
                    if (!$cliente)
                        continue;

                    $cuotasAtrasadas = $participante->cuotas->filter(function ($c) use ($fechaInicio, $fechaFin, $hasFiltered) {
                        // Debe estar vencida
                        if ($c->estado === 'pagado' || !Carbon::parse($c->fecha_limite)->isPast()) {
                            return false;
                        }

                        // Filtro opcional por rango de fechas
                        if ($hasFiltered) {
                            $fechaCuota = Carbon::parse($c->fecha_limite)->startOfDay();

                            if ($fechaInicio && $fechaCuota->lt(Carbon::parse($fechaInicio)->startOfDay())) {
                                return false;
                            }
                            if ($fechaFin && $fechaCuota->gt(Carbon::parse($fechaFin)->endOfDay())) {
                                return false;
                            }
                        }

                        return true;
                    });

                    if ($cuotasAtrasadas->count() > 0) {
                        $montoRetrasado = $cuotasAtrasadas->sum(function ($c) {
                            return $c->monto_esperado - $c->monto_pagado;
                        });

                        $origenCliente = $cliente->address ?? $cliente->origen ?? 'N/D';

                        $clientesConRetraso->push([
                            'tanda_id' => $tanda->id,
                            'tanda_nombre' => $tanda->nombre,
                            'cliente_nombre' => $cliente->nombre ?? $cliente->name ?? 'Sin nombre',
                            'telefono' => $cliente->telefono ?? 'N/A',
                            'origen' => $origenCliente,
                            'turno' => $participante->turno,
                            'cuotas_atrasadas' => $cuotasAtrasadas->count(),
                            'monto_retrasado' => $montoRetrasado,
                        ]);
                    }
                }
            }

            $clientesConRetraso = $clientesConRetraso->sortByDesc('monto_retrasado');
        }

        // Totales para la cabecera (KPIs)
        $totalClientesAfectados = $clientesConRetraso->count();
        $totalCuotasVencidas = $clientesConRetraso->sum('cuotas_atrasadas');
        $totalMontoVencido = $clientesConRetraso->sum('monto_retrasado');

        $filename = "reporte-atrasos-tandas-" . date('Y-m-d') . ".xls";
        $fechaDescarga = now()->format('d/m/Y H:i');

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"></head>';
        $html .= '<body>';

        // Estructura principal en tabla HTML para Excel
        $html .= '<table style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt;">';

        // Título del Reporte
        $html .= '<tr><td colspan="6" style="font-weight: bold; font-size: 14pt; color: #1f2937; padding-bottom: 10px; background-color: transparent;">REPORTE DE CLIENTES CON ATRASOS</td></tr>';

        // Sección de Totales / KPIs Arriba
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4b5563;">Clientes con Atraso:</td><td colspan="4">' . $totalClientesAfectados . '</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #d97706;">Cuotas Atrasadas:</td><td colspan="4" style="color: #d97706; font-weight: bold;">' . $totalCuotasVencidas . '</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #b91c1c;">Monto Total Vencido:</td><td colspan="4" style="color: #b91c1c; font-weight: bold;">$' . number_format($totalMontoVencido, 2) . '</td></tr>';
        $html .= '<tr><td colspan="6">&nbsp;</td></tr>';

        // Cabecera de la Tabla Principal
        $html .= '<tr style="background-color: #f3f4f6;">';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left;">Cliente</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left;">Origen</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left;">Tanda</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: center;">Turno</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: center;">Cuotas Vencidas</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right;">Monto Vencido</th>';
        $html .= '</tr>';

        // Filas de Datos
        if ($clientesConRetraso->isEmpty()) {
            $html .= '<tr><td colspan="6" style="border: 1px solid #d1d5db; padding: 6px; text-align: center; color: #6b7280;">No hay atrasos registrados para los criterios seleccionados.</td></tr>';
        } else {
            foreach ($clientesConRetraso as $item) {
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($item['cliente_nombre']) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($item['origen']) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($item['tanda_nombre']) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">Turno ' . $item['turno'] . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center; color: #b91c1c; font-weight: bold;">' . $item['cuotas_atrasadas'] . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right; color: #b91c1c; font-weight: bold;">$' . number_format($item['monto_retrasado'], 2) . '</td>';
                $html .= '</tr>';
            }
        }

        // Pie de Página con Fecha de Descarga
        $html .= '<tr><td colspan="6">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="6" style="font-style: italic; color: #6b7280; font-size: 10pt;">Fecha de Descarga: ' . $fechaDescarga . '</td></tr>';

        $html .= '</table>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function reporteAtrasosPdf(Request $request)
    {
        $companyId = auth()->user()->company_id ?? null;

        $verTodo = $request->query('filtro') === 'todos' || $request->has('todo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $hasFiltered = !empty($fechaInicio) || !empty($fechaFin);

        $clientesConRetraso = collect();

        // Validamos si debe procesar datos o retornar vacío
        if ($hasFiltered || $verTodo) {
            $query = Tanda::query();

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $tandas = $query->with(['participantes.cliente', 'participantes.cuotas'])->get();

            foreach ($tandas as $tanda) {
                foreach ($tanda->participantes as $participante) {
                    $cliente = $participante->cliente;
                    if (!$cliente)
                        continue;

                    $cuotasAtrasadas = $participante->cuotas->filter(function ($c) use ($fechaInicio, $fechaFin, $hasFiltered) {
                        // Debe estar vencida
                        if ($c->estado === 'pagado' || !Carbon::parse($c->fecha_limite)->isPast()) {
                            return false;
                        }

                        // Filtro opcional por rango de fechas
                        if ($hasFiltered) {
                            $fechaCuota = Carbon::parse($c->fecha_limite)->startOfDay();

                            if ($fechaInicio && $fechaCuota->lt(Carbon::parse($fechaInicio)->startOfDay())) {
                                return false;
                            }
                            if ($fechaFin && $fechaCuota->gt(Carbon::parse($fechaFin)->endOfDay())) {
                                return false;
                            }
                        }

                        return true;
                    });

                    if ($cuotasAtrasadas->count() > 0) {
                        $montoRetrasado = $cuotasAtrasadas->sum(function ($c) {
                            return $c->monto_esperado - $c->monto_pagado;
                        });

                        $origenCliente = $cliente->address ?? $cliente->origen ?? 'N/D';

                        $clientesConRetraso->push([
                            'tanda_id' => $tanda->id,
                            'tanda_nombre' => $tanda->nombre,
                            'cliente_nombre' => $cliente->nombre ?? $cliente->name ?? 'Sin nombre',
                            'telefono' => $cliente->telefono ?? 'N/A',
                            'origen' => $origenCliente,
                            'turno' => $participante->turno,
                            'cuotas_atrasadas' => $cuotasAtrasadas->count(),
                            'monto_retrasado' => $montoRetrasado,
                        ]);
                    }
                }
            }

            $clientesConRetraso = $clientesConRetraso->sortByDesc('monto_retrasado');
        }

        // Totales para la cabecera (KPIs)
        $totalClientesAfectados = $clientesConRetraso->count();
        $totalCuotasVencidas = $clientesConRetraso->sum('cuotas_atrasadas');
        $totalMontoVencido = $clientesConRetraso->sum('monto_retrasado');

        $nombreArchivo = "reporte-atrasos-tandas-" . date('Y-m-d') . ".pdf";
        $fechaDescarga = now()->format('d/m/Y H:i');

        // Construcción del HTML con el diseño profesional de PDF
        $html = '<html><head><meta charset="UTF-8"><style>';
        $html .= '@page { margin: 20mm 15mm 20mm 15mm; }';
        $html .= 'body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }';
        $html .= 'h2 { color: #1f2937; margin-bottom: 10px; font-size: 14pt; }';
        $html .= '.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 10px; }';
        $html .= '.summary-table td { padding: 5px 8px; vertical-align: top; }';
        $html .= '.text-retrasado { color: #b91c1c; font-weight: bold; }';
        $html .= '.text-alerta { color: #d97706; font-weight: bold; }';
        $html .= 'table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= 'table.data-table th, table.data-table td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }';
        $html .= 'table.data-table th { background-color: #f3f4f6; font-weight: bold; }';
        $html .= '.text-center { text-align: center; }';
        $html .= '.text-right { text-align: right; }';
        $html .= '.footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 8pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 5px; }';
        $html .= '</style></head><body>';

        $html .= '<div class="footer">Fecha de Descarga: ' . $fechaDescarga . '</div>';

        $html .= '<h2>REPORTE DE CLIENTES CON ATRASOS</h2>';

        // Resumen / Tarjetas de Totales Arriba en 2 Columnas
        $html .= '<table class="summary-table">';
        $html .= '<tr>';
        $html .= '<td style="width: 50%;">';
        $html .= '<strong>Clientes con Atraso:</strong> ' . $totalClientesAfectados . '<br>';
        $html .= '<span class="text-alerta">Cuotas Atrasadas: ' . $totalCuotasVencidas . '</span>';
        $html .= '</td>';
        $html .= '<td style="width: 50%;">';
        $html .= '<span class="text-retrasado">Monto Total Vencido: $' . number_format($totalMontoVencido, 2) . '</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        // Tabla Principal del Reporte de Atrasos
        $html .= '<table class="data-table">';
        $html .= '<tr><th>Cliente</th><th>Origen</th><th>Tanda</th><th class="text-center">Turno</th><th class="text-center">Cuotas Vencidas</th><th class="text-right">Monto Vencido</th></tr>';

        if ($clientesConRetraso->isEmpty()) {
            $html .= '<tr><td colspan="6" class="text-center" style="color: #6b7280;">No hay atrasos registrados para los criterios seleccionados.</td></tr>';
        } else {
            foreach ($clientesConRetraso as $item) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($item['cliente_nombre']) . '<br><span style="font-size: 8pt; color: #6b7280;">Tel: ' . htmlspecialchars($item['telefono']) . '</span></td>';
                $html .= '<td>' . htmlspecialchars($item['origen']) . '</td>';
                $html .= '<td>' . htmlspecialchars($item['tanda_nombre']) . '</td>';
                $html .= '<td class="text-center">Turno ' . $item['turno'] . '</td>';
                $html .= '<td class="text-center text-retrasado">' . $item['cuotas_atrasadas'] . '</td>';
                $html .= '<td class="text-right text-retrasado">$' . number_format($item['monto_retrasado'], 2) . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</table></body></html>';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return $pdf->download($nombreArchivo);
    }

    public function reporteEntregadosGlobal(Request $request)
    {
        $companyId = auth()->user()->company_id ?? null;

        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $filtro = $request->query('filtro');

        $busquedaRealizada = !empty($fechaInicio) || !empty($fechaFin) || $filtro === 'todos';

        $clientesEntregados = collect();

        if ($busquedaRealizada) {
            $query = Tanda::query();

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $tandas = $query->with(['participantes.cliente', 'participantes.cuotas'])->get();

            foreach ($tandas as $tanda) {
                // Participantes que ya recibieron / entregado
                $participantesEntregados = $tanda->participantes->filter(function ($p) {
                    return $p->entregado == true; // o tu condición de entrega
                });

                foreach ($participantesEntregados as $p) {
                    $cliente = $p->cliente;
                    if (!$cliente)
                        continue;

                    // Cálculo o obtención de la fecha en que recibió (puedes ajustar según tu columna de fecha de entrega o fecha de la cuota correspondiente al turno)
                    $fechaEntrega = $p->fecha_entrega ?? $p->updated_at;

                    if (!empty($fechaInicio) || !empty($fechaFin)) {
                        if ($fechaEntrega) {
                            $fechaItem = Carbon::parse($fechaEntrega)->startOfDay();
                            if ($fechaInicio && $fechaItem->lt(Carbon::parse($fechaInicio)->startOfDay())) {
                                continue;
                            }
                            if ($fechaFin && $fechaItem->gt(Carbon::parse($fechaFin)->endOfDay())) {
                                continue;
                            }
                        }
                    }

                    $participantesNormales = $tanda->participantes->filter(fn($part) => $part->turno !== 0);
                    $numIntegrantes = $participantesNormales->count();

                    $montoEntregado = 0;
                    if ($p->turno === 0) {
                        $montoEntregado = $p->cuotas->sum('monto_esperado');
                    } else {
                        // Multiplicamos el monto de la cuota por las cuotas_por_entrega y por los integrantes
                        $montoEntregado = $tanda->cuotas_por_entrega * $tanda->monto_cuota * $numIntegrantes;
                    }

                    $origenCliente = $cliente->address ?? $cliente->origen ?? 'N/D';

                    $clientesEntregados->push([
                        'tanda_id' => $tanda->id,
                        'tanda_nombre' => $tanda->nombre,
                        'cliente_nombre' => $cliente->nombre ?? $cliente->name ?? 'Sin nombre',
                        'telefono' => $cliente->telefono ?? 'N/A',
                        'origen' => $origenCliente,
                        'turno' => $p->turno,
                        'fecha_entrega' => $fechaEntrega ? Carbon::parse($fechaEntrega)->format('Y-m-d') : 'N/D',
                        'monto_entregado' => $montoEntregado,
                    ]);
                }
            }

            $clientesEntregados = $clientesEntregados->sortByDesc('fecha_entrega');
        }

        return view('tandas.reporte-entregados', compact('clientesEntregados', 'fechaInicio', 'fechaFin'));
    }

    public function exportarEntregadosExcel(Request $request)
    {
        $companyId = auth()->user()->company_id ?? null;

        $verTodo = $request->query('filtro') === 'todos' || $request->has('todo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $hasFiltered = !empty($fechaInicio) || !empty($fechaFin);

        $clientesEntregados = collect();

        if ($hasFiltered || $verTodo) {
            $query = Tanda::query();

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $tandas = $query->with(['participantes.cliente', 'participantes.cuotas'])->get();

            foreach ($tandas as $tanda) {
                $participantesEntregados = $tanda->participantes->filter(function ($p) {
                    return $p->entregado == true;
                });

                foreach ($participantesEntregados as $p) {
                    $cliente = $p->cliente;
                    if (!$cliente)
                        continue;

                    $fechaEntrega = $p->fecha_entrega ?? $p->updated_at;

                    if ($hasFiltered) {
                        if ($fechaEntrega) {
                            $fechaItem = Carbon::parse($fechaEntrega)->startOfDay();
                            if ($fechaInicio && $fechaItem->lt(Carbon::parse($fechaInicio)->startOfDay())) {
                                continue;
                            }
                            if ($fechaFin && $fechaItem->gt(Carbon::parse($fechaFin)->endOfDay())) {
                                continue;
                            }
                        }
                    }

                    $participantesNormales = $tanda->participantes->filter(fn($part) => $part->turno !== 0);
                    $numIntegrantes = $participantesNormales->count();

                    $montoEntregado = 0;
                    if ($p->turno === 0) {
                        $montoEntregado = $p->cuotas->sum('monto_esperado');
                    } else {
                        $montoEntregado = ($tanda->cuotas_por_entrega ?? 1) * $tanda->monto_cuota * $numIntegrantes;
                    }

                    $origenCliente = $cliente->address ?? $cliente->origen ?? 'N/D';

                    $clientesEntregados->push([
                        'tanda_id' => $tanda->id,
                        'tanda_nombre' => $tanda->nombre,
                        'cliente_nombre' => $cliente->nombre ?? $cliente->name ?? 'Sin nombre',
                        'telefono' => $cliente->telefono ?? 'N/A',
                        'origen' => $origenCliente,
                        'turno' => $p->turno,
                        'fecha_entrega' => $fechaEntrega ? Carbon::parse($fechaEntrega)->format('Y-m-d') : 'N/D',
                        'monto_entregado' => $montoEntregado,
                    ]);
                }
            }

            $clientesEntregados = $clientesEntregados->sortByDesc('fecha_entrega');
        }

        // Totales para los KPIs
        $totalBeneficiarios = $clientesEntregados->count();
        $totalEntregas = $clientesEntregados->count(); // o sum('total_tandas') si agrupas
        $totalMontoEntregado = $clientesEntregados->sum('monto_entregado');

        $filename = "reporte-tandas-entregadas-" . date('Y-m-d') . ".xls";
        $fechaDescarga = now()->format('d/m/Y H:i');

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"></head>';
        $html .= '<body>';

        $html .= '<table style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11pt;">';
        $html .= '<tr><td colspan="6" style="font-weight: bold; font-size: 14pt; color: #1f2937; padding-bottom: 10px;">REPORTE DE CLIENTES QUE YA RECIBIERON TANDA</td></tr>';

        // KPIs
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4b5563;">Clientes Entregados:</td><td colspan="4">' . $totalBeneficiarios . '</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #047857;">Tandas Entregadas:</td><td colspan="4" style="color: #047857; font-weight: bold;">' . $totalEntregas . '</td></tr>';
        $html .= '<tr><td colspan="2" style="font-weight: bold; color: #4338ca;">Monto Total Entregado:</td><td colspan="4" style="color: #4338ca; font-weight: bold;">$' . number_format($totalMontoEntregado, 2) . '</td></tr>';
        $html .= '<tr><td colspan="6">&nbsp;</td></tr>';

        // Cabecera Tabla
        $html .= '<tr style="background-color: #f3f4f6;">';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left;">Cliente</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left;">Origen</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: left;">Tanda</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: center;">Turno / Sorteo</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: center;">Fecha de Entrega</th>';
        $html .= '<th style="border: 1px solid #d1d5db; padding: 6px; font-weight: bold; text-align: right;">Monto Entregado</th>';
        $html .= '</tr>';

        if ($clientesEntregados->isEmpty()) {
            $html .= '<tr><td colspan="6" style="border: 1px solid #d1d5db; padding: 6px; text-align: center; color: #6b7280;">No hay entregas registradas para los criterios seleccionados.</td></tr>';
        } else {
            foreach ($clientesEntregados as $item) {
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($item['cliente_nombre']) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($item['origen']) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: left;">' . htmlspecialchars($item['tanda_nombre']) . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">Turno ' . $item['turno'] . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: center;">' . ($item['fecha_entrega'] !== 'N/D' ? Carbon::parse($item['fecha_entrega'])->format('d/m/Y') : 'N/D') . '</td>';
                $html .= '<td style="border: 1px solid #d1d5db; padding: 6px; text-align: right; color: #4338ca; font-weight: bold;">$' . number_format($item['monto_entregado'], 2) . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '<tr><td colspan="6">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="6" style="font-style: italic; color: #6b7280; font-size: 10pt;">Fecha de Descarga: ' . $fechaDescarga . '</td></tr>';
        $html .= '</table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportarEntregadosPdf(Request $request)
    {
        $companyId = auth()->user()->company_id ?? null;

        $verTodo = $request->query('filtro') === 'todos' || $request->has('todo');
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');
        $hasFiltered = !empty($fechaInicio) || !empty($fechaFin);

        $clientesEntregados = collect();

        if ($hasFiltered || $verTodo) {
            $query = Tanda::query();

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $tandas = $query->with(['participantes.cliente', 'participantes.cuotas'])->get();

            foreach ($tandas as $tanda) {
                $participantesEntregados = $tanda->participantes->filter(function ($p) {
                    return $p->entregado == true;
                });

                foreach ($participantesEntregados as $p) {
                    $cliente = $p->cliente;
                    if (!$cliente)
                        continue;

                    $fechaEntrega = $p->fecha_entrega ?? $p->updated_at;

                    if ($hasFiltered) {
                        if ($fechaEntrega) {
                            $fechaItem = Carbon::parse($fechaEntrega)->startOfDay();
                            if ($fechaInicio && $fechaItem->lt(Carbon::parse($fechaInicio)->startOfDay())) {
                                continue;
                            }
                            if ($fechaFin && $fechaItem->gt(Carbon::parse($fechaFin)->endOfDay())) {
                                continue;
                            }
                        }
                    }

                    $participantesNormales = $tanda->participantes->filter(fn($part) => $part->turno !== 0);
                    $numIntegrantes = $participantesNormales->count();

                    $montoEntregado = 0;
                    if ($p->turno === 0) {
                        $montoEntregado = $p->cuotas->sum('monto_esperado');
                    } else {
                        $montoEntregado = ($tanda->cuotas_por_entrega ?? 1) * $tanda->monto_cuota * $numIntegrantes;
                    }

                    $origenCliente = $cliente->address ?? $cliente->origen ?? 'N/D';

                    $clientesEntregados->push([
                        'tanda_id' => $tanda->id,
                        'tanda_nombre' => $tanda->nombre,
                        'cliente_nombre' => $cliente->nombre ?? $cliente->name ?? 'Sin nombre',
                        'telefono' => $cliente->telefono ?? 'N/A',
                        'origen' => $origenCliente,
                        'turno' => $p->turno,
                        'fecha_entrega' => $fechaEntrega ? Carbon::parse($fechaEntrega)->format('Y-m-d') : 'N/D',
                        'monto_entregado' => $montoEntregado,
                    ]);
                }
            }

            $clientesEntregados = $clientesEntregados->sortByDesc('fecha_entrega');
        }

        $totalBeneficiarios = $clientesEntregados->count();
        $totalEntregas = $clientesEntregados->count();
        $totalMontoEntregado = $clientesEntregados->sum('monto_entregado');

        $nombreArchivo = "reporte-tandas-entregadas-" . date('Y-m-d') . ".pdf";
        $fechaDescarga = now()->format('d/m/Y H:i');

        $html = '<html><head><meta charset="UTF-8"><style>';
        $html .= '@page { margin: 20mm 15mm 20mm 15mm; }';
        $html .= 'body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; }';
        $html .= 'h2 { color: #1f2937; margin-bottom: 10px; font-size: 14pt; }';
        $html .= '.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 10px; }';
        $html .= '.summary-table td { padding: 5px 8px; vertical-align: top; }';
        $html .= '.text-entregado { color: #4338ca; font-weight: bold; }';
        $html .= '.text-exito { color: #047857; font-weight: bold; }';
        $html .= 'table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= 'table.data-table th, table.data-table td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }';
        $html .= 'table.data-table th { background-color: #f3f4f6; font-weight: bold; }';
        $html .= '.text-center { text-align: center; }';
        $html .= '.text-right { text-align: right; }';
        $html .= '.footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 8pt; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 5px; }';
        $html .= '</style></head><body>';

        $html .= '<div class="footer">Fecha de Descarga: ' . $fechaDescarga . '</div>';

        $html .= '<h2>REPORTE DE CLIENTES QUE YA RECIBIERON TANDA</h2>';

        // Resumen / Tarjetas de Totales Arriba
        $html .= '<table class="summary-table">';
        $html .= '<tr>';
        $html .= '<td style="width: 50%;">';
        $html .= '<strong>Clientes Entregados:</strong> ' . $totalBeneficiarios . '<br>';
        $html .= '<span class="text-exito">Tandas Entregadas: ' . $totalEntregas . '</span>';
        $html .= '</td>';
        $html .= '<td style="width: 50%;">';
        $html .= '<span class="text-entregado">Monto Total Entregado: $' . number_format($totalMontoEntregado, 2) . '</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        // Tabla Principal
        $html .= '<table class="data-table">';
        $html .= '<tr><th>Cliente</th><th>Origen</th><th>Tanda</th><th class="text-center">Turno / Sorteo</th><th class="text-center">Fecha de Entrega</th><th class="text-right">Monto Entregado</th></tr>';

        if ($clientesEntregados->isEmpty()) {
            $html .= '<tr><td colspan="6" class="text-center" style="color: #6b7280;">No hay entregas registradas para los criterios seleccionados.</td></tr>';
        } else {
            foreach ($clientesEntregados as $item) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($item['cliente_nombre']) . '<br><span style="font-size: 8pt; color: #6b7280;">Tel: ' . htmlspecialchars($item['telefono']) . '</span></td>';
                $html .= '<td>' . htmlspecialchars($item['origen']) . '</td>';
                $html .= '<td>' . htmlspecialchars($item['tanda_nombre']) . '</td>';
                $html .= '<td class="text-center">Turno ' . $item['turno'] . '</td>';
                $html .= '<td class="text-center">' . ($item['fecha_entrega'] !== 'N/D' ? Carbon::parse($item['fecha_entrega'])->format('d/m/Y') : 'N/D') . '</td>';
                $html .= '<td class="text-right text-entregado">$' . number_format($item['monto_entregado'], 2) . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</table></body></html>';

        $pdf = Pdf::loadHTML($html)->setPaper('letter', 'portrait');

        return $pdf->download($nombreArchivo);
    }

    public function reporteCobrosTandas(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', Carbon::today()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::today()->format('Y-m-d'));
        $clientId = $request->input('client_id');

        // Usamos 'participante.client' y 'usuario' según tu modelo TandaCuota
        $query = TandaCuota::with(['tanda', 'participante.cliente', 'usuario'])
            ->where('estado', 'pagado')
            ->whereBetween('fecha_pago', [
                Carbon::parse($fechaInicio)->startOfDay(),
                Carbon::parse($fechaFin)->endOfDay()
            ]);

        // Filtramos usando la relación 'participante' y la columna 'cliente_id'
        if ($clientId) {
            $query->whereHas('participante', function ($q) use ($clientId) {
                $q->where('cliente_id', $clientId);
            });
        }

        $abonos = $query->orderBy('fecha_pago', 'desc')->get();

        $totalCobrado = $abonos->sum('monto_pagado');
        $clientes = Client::orderBy('name')->get();

        return view('tandas.tandas-abonos', compact(
            'abonos',
            'totalCobrado', // Asegúrate de que no tenga el signo $ aquí
            'fechaInicio',
            'fechaFin',
            'clientId',
            'clientes'
        ));
    }
}