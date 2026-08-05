<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['store_credit', 'cash_loan']);

            $table->string('concept'); // Descripción (Ej: "Pantalón de mezclilla" o "Préstamo personal")

            $table->decimal('total_amount', 10, 2); // Capital inicial o costo total de la mercancía

            // Modalidad (Solo para préstamos en efectivo): 
            // 'fixed_installments' (cuotas fijas) o 'interest_only' (solo interés con abono a capital)
            $table->enum('loan_modal', ['fixed_installments', 'interest_only'])->nullable();

            $table->decimal('interest_rate', 5, 2)->default(0); // Porcentaje de interés (Ej: 10.00)

            // Frecuencia: 'weekly', 'biweekly', 'monthly'
            $table->enum('payment_frequency', ['weekly', 'biweekly', 'monthly'])->nullable();

            $table->integer('installments_count')->nullable(); // Número total de cuotas (para cuotas fijas)

            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
