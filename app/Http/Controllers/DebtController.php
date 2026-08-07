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
    public function showClientAccount($clientId)
    {
        $client = Client::with(['debts.payments', 'debts.installments'])->findOrFail($clientId);

        $storeCredits = $client->debts->where('type', 'store_credit');
        $cashLoans = $client->debts->where('type', 'cash_loan');

        return view('clients.account', compact('client', 'storeCredits', 'cashLoans'));
    }

    // Guardar un nuevo crédito de tienda (mercancía) o préstamo en efectivo
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
            'loan_date' => 'required|date', // Validamos que se envíe la fecha de inicio
        ]);

        $capitalInicial = $request->total_amount;
        $interesPorcentaje = $request->interest_rate ?? 0;

        // Calcular el monto total acumulado con el interés incluido
        $totalConInteres = $capitalInicial * (1 + ($interesPorcentaje / 100));

        // Crear el registro principal
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

        // Si es de Cuotas Fijas, generar el calendario a partir de la fecha elegida
        if ($request->loan_modal === 'fixed_installments' && $request->installments_count > 0) {
            $numCuotas = $request->installments_count;
            $montoPorCuota = round($totalConInteres / $numCuotas, 2);
            
            // Tomamos la fecha enviada en el formulario en lugar de now()
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

        return redirect()->route('clients.show', $request->client_id)
            ->with('success', 'Préstamo registrado y cuotas calculadas correctamente.');
    }

    // Registrar un abono de forma limpia
    public function storePayment(Request $request, $debtId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $debt = Debt::findOrFail($debtId);
        $amountPaid = $request->amount;

        $interestCovered = 0;
        $capitalCovered = 0;

        if ($debt->type == 'cash_loan' && $debt->loan_modal == 'interest_only') {
            // Usamos el saldo pendiente real para calcular intereses si aplica
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

        // Creamos el registro del abono
        Payment::create([
            'debt_id' => $debt->id,
            'amount' => $amountPaid,
            'interest_covered' => $interestCovered,
            'capital_covered' => $capitalCovered,
            'payment_date' => now(),
            'notes' => $request->notes
        ]);

        // Verificamos de manera dinámica si ya quedó pagado por completo
        if ($debt->remaining_balance <= 0) {
            $debt->status = 'paid';
            $debt->save();
        }

        return redirect()->back()->with('success', 'Abono registrado correctamente.');
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

        // Como el saldo se calcula dinámicamente con los pagos existentes, 
        // con solo borrar el pago el saldo se recalcula bien automáticamente.
        $payment->delete();

        // Si la deuda estaba pagada pero al borrar el abono vuelve a tener saldo, la pasamos a pendiente
        if ($debt->status == 'paid' && $debt->remaining_balance > 0) {
            $debt->status = 'pending';
            $debt->save();
        }

        return redirect()->route('clients.show', $clientId)
            ->with('success', 'Abono eliminado y saldo recalculado correctamente.');
    }
}