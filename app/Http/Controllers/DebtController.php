<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\Client;
use App\Models\Payment;
use App\Models\LoanInstallment;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DebtController extends Controller
{
    // Vista principal de la cuenta del cliente (donde aparece el teléfono, dirección y totales globales)
    public function showClientAccount($clientId)
    {
        $client = Client::with(['debts.payments', 'debts.installments'])->findOrFail($clientId);

        // 1. Ropa y mercancía fiada: Ordenada de forma que los de mayor antigüedad (más días transcurridos) queden arriba
        $storeCredits = $client->debts
            ->where('type', 'store_credit')
            ->sortBy(function ($credit) {
                $ultimoPago = $credit->payments->sortByDesc('payment_date')->first();
                $fechaReferencia = $ultimoPago ? Carbon::parse($ultimoPago->payment_date) : Carbon::parse($credit->created_at);
                return $fechaReferencia->timestamp; // Retorna el timestamp para un orden cronológico exacto
            })
            ->values(); // Reorganiza los índices de la colección

        // 2. Préstamos en efectivo: Primero los que tengan cuotas pendientes vencidas
        $cashLoans = $client->debts
            ->where('type', 'cash_loan')
            ->sortByDesc(function ($loan) {
                $tieneVencidas = $loan->installments->contains(function ($installment) {
                    return $installment->status === 'pending' && Carbon::parse($installment->due_date)->isPast();
                });
                return $tieneVencidas ? 1 : 0;
            })
            ->values();

        return view('clients.show', compact(
            'client',
            'storeCredits',
            'cashLoans'
        ));
    }


    // Guardar un nuevo crédito de tienda (mercancía) o préstamo en efectivo
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:store_credit,cash_loan',
            'concept' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'loan_modal' => 'nullable|in:interest_only,fixed_installments',
            'payment_frequency' => 'nullable|in:weekly,biweekly,monthly', // Frecuencia opcional o requerida según lógica de negocio
            'installments_count' => 'required_if:loan_modal,fixed_installments|nullable|integer|min:1',
            'loan_date' => 'required_if:type,cash_loan|nullable|date',
            'created_at' => 'required|date',
        ]);

        $capitalInicial = $request->total_amount;
        $interesPorcentaje = $request->interest_rate ?? 0;

        // Si es mercancía fiada o préstamo de "Solo Interés", la deuda principal guarda netamente el capital puro
        $totalDeuda = ($request->type === 'store_credit' || $request->loan_modal === 'interest_only')
            ? $capitalInicial
            : $capitalInicial * (1 + ($interesPorcentaje / 100));

        $debt = Debt::create([
            'company_id' => auth()->user()->company_id,
            'client_id' => $request->client_id,
            'type' => $request->type,
            'concept' => $request->concept,
            'total_amount' => $totalDeuda,
            'interest_rate' => $interesPorcentaje,
            'loan_modal' => $request->loan_modal,
            'payment_frequency' => $request->payment_frequency,
            'installments_count' => $request->installments_count,
            'status' => 'pending',
            'loan_date' => $request->type === 'cash_loan' ? $request->loan_date : null,
            'created_at' => $request->type === 'cash_loan' ? $request->loan_date : $request->created_at,
        ]);

        // Función auxiliar para sumar periodos limpiamente
        $avanzarFecha = function ($fecha, $frecuencia) {
            if ($frecuencia === 'weekly') {
                return $fecha->addWeek();
            } elseif ($frecuencia === 'biweekly') {
                return $fecha->addDays(15);
            } else {
                return $fecha->addMonth();
            }
        };

        // 1. Si es modalidad "Solo Interés"
        if ($request->type === 'cash_loan' && $request->loan_modal === 'interest_only') {
            $montoInteresPeriodo = round($capitalInicial * ($interesPorcentaje / 100), 2);
            $fechaVencimiento = Carbon::parse($request->loan_date);

            $fechaVencimiento = $avanzarFecha($fechaVencimiento, $request->payment_frequency);

            LoanInstallment::create([
                'debt_id' => $debt->id,
                'installment_number' => 1,
                'amount_due' => $montoInteresPeriodo,
                'due_date' => $fechaVencimiento->toDateString(),
                'status' => 'pending',
            ]);
        }

        // 2. Si es cuotas fijas
        if ($request->type === 'cash_loan' && $request->loan_modal === 'fixed_installments' && $request->installments_count > 0) {
            $numCuotas = $request->installments_count;
            $montoPorCuota = round($totalDeuda / $numCuotas, 2);
            $fechaVencimiento = Carbon::parse($request->loan_date);

            for ($i = 1; $i <= $numCuotas; $i++) {
                $fechaVencimiento = $avanzarFecha($fechaVencimiento, $request->payment_frequency);

                LoanInstallment::create([
                    'debt_id' => $debt->id,
                    'installment_number' => $i,
                    'amount_due' => $montoPorCuota,
                    'due_date' => $fechaVencimiento->toDateString(),
                    'status' => 'pending',
                ]);
            }
        }

        $mensaje = match ($request->type) {
            'store_credit' => 'Mercancía fiada registrada correctamente.',
            default => $request->loan_modal === 'interest_only'
                ? 'Préstamo de solo interés registrado y primera cuota de interés generada.'
                : 'Préstamo registrado y cuotas calculadas correctamente.'
        };

        return redirect()->route('clients.show', $request->client_id)
            ->with('success', $mensaje);
    }

    

    // Registrar un abono general (vía input manual)
    public function storePayment(Request $request, $debtId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'installment_id' => 'nullable|exists:loan_installments,id',
        ]);

        $debt = Debt::findOrFail($debtId);
        $amountPaid = $request->amount;

        $interestCovered = 0;
        $capitalCovered = 0;

        if ($debt->type == 'cash_loan' && $debt->loan_modal == 'interest_only') {
            $currentBalance = $debt->remaining_balance;
            $interestDue = $currentBalance * ($debt->interest_rate / 100);

            if ($amountPaid >= $interestDue) {
                $interestCovered = $interestDue;
                $capitalCovered = $amountPaid - $interestDue;
            } else {
                $interestCovered = $amountPaid;
                $capitalCovered = 0;
            }
        } else {
            $interestCovered = 0;
            $capitalCovered = $amountPaid;
        }

        Payment::create([
            'debt_id' => $debt->id,
            'installment_id' => $request->installment_id,
            'amount' => $amountPaid,
            'interest_covered' => $interestCovered,
            'capital_covered' => $capitalCovered,
            'payment_date' => now(),
            'notes' => $request->notes
        ]);

        if ($request->filled('installment_id')) {
            $installment = LoanInstallment::find($request->installment_id);
            if ($installment) {
                $installment->update(['status' => 'paid']);
            }
        }

        if ($debt->remaining_balance <= 0) {
            $debt->status = 'paid';
            $debt->save();
        }

        return redirect()->back()->with('success', 'Abono registrado correctamente.');
    }

    // Pagar una cuota individual mediante su botón dedicado
    public function payInstallment($debtId, $installmentId)
    {
        $debt = Debt::findOrFail($debtId);
        $installment = LoanInstallment::where('id', $installmentId)->where('debt_id', $debtId)->findOrFail($installmentId);

        if ($installment->status == 'paid') {
            return redirect()->back()->with('error', 'Esta cuota ya se encuentra pagada.');
        }

        // 1. Cambiar estado de la cuota a pagada
        $installment->update(['status' => 'paid']);

        // Identificar si es un préstamo de solo interés
        $isInterestOnly = ($debt->loan_modal === 'interest_only');

        // Definir qué cubre este pago específico
        $capitalCovered = $isInterestOnly ? 0 : $installment->amount_due;
        $interestCovered = $isInterestOnly ? $installment->amount_due : 0;

        // 2. Crear el registro en la tabla payments distinguiendo capital e interés
        Payment::create([
            'debt_id' => $debtId,
            'installment_id' => $installmentId,
            'amount' => $installment->amount_due,
            'capital_covered' => $capitalCovered,
            'interest_covered' => $interestCovered,
            'payment_date' => now(),
        ]);

        // 3. Si es modalidad "Solo Interés", calculamos el capital real restando los abonos directos
        if ($isInterestOnly) {
            // Sumar todos los abonos a capital directos (donde installment_id es nulo)
            $totalAbonosCapital = Payment::where('debt_id', $debt->id)
                ->whereNull('installment_id')
                ->sum('capital_covered');

            // Capital actual real sobre el que se calculan los intereses
            $capitalActual = max(0, $debt->total_amount - $totalAbonosCapital);

            // El interés se calcula usando el capital real actualizado
            $nuevoMontoInteres = round($capitalActual * ($debt->interest_rate / 100), 2);
            $siguienteNumero = $installment->installment_number + 1;

            $fechaVencimiento = Carbon::parse($installment->due_date);
            if ($debt->payment_frequency === 'weekly') {
                $fechaVencimiento->addWeek();
            } elseif ($debt->payment_frequency === 'biweekly') {
                $fechaVencimiento->addDays(15);
            } else {
                $fechaVencimiento->addMonth();
            }

            LoanInstallment::create([
                'debt_id' => $debt->id,
                'installment_number' => $siguienteNumero,
                'amount_due' => $nuevoMontoInteres,
                'due_date' => $fechaVencimiento->toDateString(),
                'status' => 'pending',
            ]);
        }

        // 4. Verificar si el préstamo se ha completado
        if ($isInterestOnly) {
            // En "Solo Interés", la deuda se marca como pagada si el capital real llega a 0 por los abonos
            $totalAbonosCapital = Payment::where('debt_id', $debt->id)
                ->whereNull('installment_id')
                ->sum('capital_covered');

            $capitalActual = max(0, $debt->total_amount - $totalAbonosCapital);

            if ($capitalActual <= 0) {
                $debt->status = 'paid';
                $debt->save();
            }
        } else {
            // Para cuotas fijas mantenemos la validación original de pagos totales
            $totalPagado = Payment::where('debt_id', $debtId)->sum('amount');
            if ($totalPagado >= $debt->total_amount) {
                $debt->status = 'paid';
                $debt->save();
            }
        }

        return redirect()->back()->with('success', "Cuota #{$installment->installment_number} pagada correctamente.");
    }


    public function storeCapitalPayment(Request $request, $debtId)
    {
        $request->validate([
            'capital_covered' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $debt = Debt::findOrFail($debtId);
        $montoAbono = $request->input('capital_covered');

        // Registrar el abono directo a capital sin alterar la deuda principal original
        Payment::create([
            'debt_id' => $debtId,
            'installment_id' => null, // No va asociado a una cuota
            'amount' => $montoAbono,
            'capital_covered' => $montoAbono,
            'interest_covered' => 0,
            'payment_date' => $request->input('payment_date', now()),
            'notes' => $request->input('notes', 'Abono directo a capital'),
        ]);

        return redirect()->back()->with('success', 'Abono a capital registrado correctamente.');
    }

    // Eliminar un préstamo o mercancía fiada por completo
    public function destroy($id)
    {
        $debt = Debt::findOrFail($id);
        $clientId = $debt->client_id;
        $debt->delete();

        return redirect()->route('clients.show', $clientId)
            ->with('success', 'El registro ha sido eliminado correctamente.');
    }

    // Eliminar un abono de forma totalmente segura
    public function destroyPayment($id)
    {
        $payment = Payment::with('debt')->findOrFail($id);
        $debt = $payment->debt;

        $payment->delete();

        if ($debt->status == 'paid' && $debt->remaining_balance > 0) {
            $debt->status = 'pending';
            $debt->save();
        }

        // Cambiado de clients.show a redirect()->back() para que no te mueva de pantalla
        return redirect()->back()
            ->with('success', 'Abono eliminado y saldo recalculado correctamente.');
    }

    // Mostrar detalles de un préstamo específico
    public function show($id)
    {
        $loan = Debt::with(['payments', 'installments', 'client'])->findOrFail($id);


        return view('clients.details', compact('loan'));
    }

    // Eliminar el pago de una cuota específica y recalcular saldos
    public function destroyInstallmentPayment($debtId, $installmentId)
    {
        $debt = Debt::findOrFail($debtId);
        $installment = LoanInstallment::where('id', $installmentId)->where('debt_id', $debtId)->findOrFail($installmentId);

        // 1. Buscar y eliminar el abono exacto vinculado a esta cuota
        $payment = Payment::where('debt_id', $debtId)
            ->where('installment_id', $installmentId)
            ->first();

        if ($payment) {
            $payment->delete();
        } else {
            // Fallback por si acaso algún pago antiguo no tenía el ID vinculado
            $fallbackPayment = Payment::where('debt_id', $debtId)
                ->where('amount', $installment->amount_due)
                ->latest()
                ->first();
            if ($fallbackPayment) {
                $fallbackPayment->delete();
            }
        }

        // 2. Regresar la cuota a estado pendiente
        $installment->update(['status' => 'pending']);

        // 3. Actualizar el estatus global del préstamo si estaba marcado como pagado
        if ($debt->status == 'paid') {
            $debt->status = 'pending';
            $debt->save();
        }

        return redirect()->back()->with('success', "Se ha eliminado el pago de la Cuota #{$installment->installment_number}, se retiró del historial y se recalculó el saldo.");
    }

    public function liquidar($id)
    {
        $loan = Debt::with('installments')->findOrFail($id);
        $isInterestOnly = ($loan->loan_modal === 'interest_only');

        if ($isInterestOnly) {
            // 1. Pagar y registrar las cuotas de interés pendientes que existan
            foreach ($loan->installments as $installment) {
                if ($installment->status !== 'paid') {
                    $installment->update(['status' => 'paid']);

                    Payment::create([
                        'debt_id' => $loan->id,
                        'installment_id' => $installment->id,
                        'amount' => $installment->amount_due,
                        'capital_covered' => 0,
                        'interest_covered' => $installment->amount_due,
                        'payment_date' => now(),
                        'notes' => 'Pago de interés al liquidar préstamo',
                    ]);
                }
            }

            // 2. Calcular el capital real pendiente (Monto inicial menos abonos a capital previos)
            $totalAbonosCapitalPrevios = Payment::where('debt_id', $loan->id)
                ->whereNull('installment_id')
                ->sum('capital_covered');

            $capitalRestante = max(0, $loan->total_amount - $totalAbonosCapitalPrevios);

            // 3. Si aún queda capital por cubrir, generamos un abono directo por el total restante
            if ($capitalRestante > 0) {
                Payment::create([
                    'debt_id' => $loan->id,
                    'installment_id' => null, // Abono directo a capital
                    'amount' => $capitalRestante,
                    'capital_covered' => $capitalRestante,
                    'interest_covered' => 0,
                    'payment_date' => now(),
                    'notes' => 'Liquidación total de capital pendiente',
                ]);
            }

            // 4. Marcar el préstamo como pagado
            $loan->update([
                'status' => 'paid',
            ]);

        } else {
            // Lógica original para préstamos de cuotas fijas (fixed_installments)
            foreach ($loan->installments as $installment) {
                if ($installment->status !== 'paid') {
                    $installment->update(['status' => 'paid']);

                    Payment::create([
                        'debt_id' => $loan->id,
                        'installment_id' => $installment->id,
                        'amount' => $installment->amount_due,
                        'capital_covered' => $installment->amount_due,
                        'interest_covered' => 0,
                        'payment_date' => now(),
                    ]);
                }
            }

            $loan->update([
                'status' => 'paid',
                'total_abonado' => $loan->total_amount,
                'saldo_restante' => 0,
            ]);
        }

        return redirect()->back()->with('success', '¡Préstamo liquidado por completo y saldos actualizados!');
    }

    public function showStoreDetails($id)
    {
        // Carga el crédito de tienda con sus pagos
        $credit = Debt::with(['payments', 'client'])
            ->where('type', 'store_credit')
            ->findOrFail($id);

        return view('clients.store-credits', compact('credit'));
    }

    public function exportPdf($id)
    {
        $credit = Debt::with(['client', 'payments'])->findOrFail($id);

        // Puedes crear una vista específica limpia solo para el PDF o usar la misma con una variable de control
        $pdf = Pdf::loadView('exports.edo-cta-pdf', compact('credit'));

        // download() fuerza la descarga directa del archivo sin abrir la ventana de impresión
        return $pdf->download("estado-de-cuenta-{$credit->id}.pdf");
    }

    public function exportLoanPdf($id)
    {
        $loan = Debt::with(['client', 'installments', 'payments'])->findOrFail($id);

        // Genera el PDF utilizando una vista diseñada para préstamos
        $pdf = Pdf::loadView('exports.loan-edo-cta-pdf', compact('loan'));

        return $pdf->download("estado-de-cuenta-prestamo-{$loan->id}.pdf");
    }
}