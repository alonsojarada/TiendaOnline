<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Debt;
use App\Models\Client;
use App\Models\Payment;
use App\Models\LoanInstallment;
use Carbon\Carbon;

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
            'payment_frequency' => 'nullable|in:weekly,biweekly,monthly',
            'installments_count' => 'nullable|integer|min:1',
            'loan_date' => 'nullable|date', // Cambiado a nullable por si es mercancía fiada
        ]);

        $capitalInicial = $request->total_amount;
        $interesPorcentaje = $request->interest_rate ?? 0;

        // Si es mercancía fiada, el total es netamente el costo ingresado sin intereses automáticos de préstamos
        $totalConInteres = $request->type === 'store_credit'
            ? $capitalInicial
            : $capitalInicial * (1 + ($interesPorcentaje / 100));

        $debt = Debt::create([
            'client_id' => $request->client_id,
            'type' => $request->type,
            'concept' => $request->concept,
            'total_amount' => $totalConInteres,
            'interest_rate' => $interesPorcentaje,
            'loan_modal' => $request->loan_modal,
            'payment_frequency' => $request->payment_frequency,
            'installments_count' => $request->installments_count,
            'status' => 'pending',
        ]);

        if ($request->type === 'cash_loan' && $request->loan_modal === 'fixed_installments' && $request->installments_count > 0) {
            $numCuotas = $request->installments_count;
            $montoPorCuota = round($totalConInteres / $numCuotas, 2);

            $fechaVencimiento = Carbon::parse($request->loan_date);

            for ($i = 1; $i <= $numCuotas; $i++) {
                if ($request->payment_frequency === 'weekly') {
                    $fechaVencimiento = $fechaVencimiento->addWeek();
                } elseif ($request->payment_frequency === 'biweekly') {
                    $fechaVencimiento = $fechaVencimiento->addDays(15);
                } else {
                    $fechaVencimiento = $fechaVencimiento->addMonth();
                }

                LoanInstallment::create([
                    'debt_id' => $debt->id,
                    'installment_number' => $i,
                    'amount_due' => $montoPorCuota,
                    'due_date' => $fechaVencimiento->toDateString(),
                    'status' => 'pending',
                ]);
            }
        }

        $mensaje = $request->type === 'store_credit' ? 'Mercancía fiada registrada correctamente.' : 'Préstamo registrado y cuotas calculadas correctamente.';

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

        // 2. Crear el registro en la tabla payments guardando el installment_id y los campos requeridos
        Payment::create([
            'debt_id' => $debtId,
            'installment_id' => $installmentId,
            'amount' => $installment->amount_due,
            'capital_covered' => $installment->amount_due,
            'interest_covered' => 0,
            'payment_date' => now(),
        ]);

        // 3. Verificar si el préstamo se ha completado en su totalidad
        $totalPagado = Payment::where('debt_id', $debtId)->sum('amount');
        if ($totalPagado >= $debt->total_amount) {
            $debt->status = 'paid';
            $debt->save();
        }

        return redirect()->back()->with('success', "Cuota #{$installment->installment_number} pagada correctamente.");
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
        $clientId = $debt->client_id;

        $payment->delete();

        if ($debt->status == 'paid' && $debt->remaining_balance > 0) {
            $debt->status = 'pending';
            $debt->save();
        }

        return redirect()->route('clients.show', $clientId)
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

        // 1. Recorrer y actualizar las cuotas pendientes
        foreach ($loan->installments as $installment) {
            if ($installment->status !== 'paid') {
                $installment->update(['status' => 'paid']);

                // Registrar el pago correspondiente para mantener la integridad en la tabla payments
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

        // 2. Actualizar los campos clave del préstamo para reflejar la liquidación total
        $loan->update([
            'status' => 'paid',
            // Si tu base de datos guarda el total abonado en una columna de la tabla debts, la igualamos al total:
            'total_abonado' => $loan->total_amount,
            'saldo_restante' => 0,
        ]);

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
}