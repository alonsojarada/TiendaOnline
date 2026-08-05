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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debt_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 10, 2);            // Cantidad total que entregó el cliente
            $table->decimal('interest_covered', 10, 2);  // Cuánto se fue a interés (0 si es mercancía fiada)
            $table->decimal('capital_covered', 10, 2);   // Cuánto se fue a reducir la deuda principal

            $table->date('payment_date');                // Fecha del abono
            $table->string('notes')->nullable();         // Opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
