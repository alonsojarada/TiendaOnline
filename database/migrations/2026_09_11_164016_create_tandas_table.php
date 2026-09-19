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
        Schema::create('tandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre', 150);
            $table->decimal('monto_cuota', 10, 2);

            // Frecuencia única de aportación/recaudación
            $table->enum('frecuencia', ['semanal', 'quincenal', 'mensual']);

            // Nueva variable clave: ¿Cada cuántas cuotas se entrega el fondo? (1, 2, 3, 4, etc.)
            $table->integer('cuotas_por_entrega')->default(1);

            $table->integer('total_participantes');
            $table->enum('modalidad', ['con_cero', 'sin_cero_cargo_gradual', 'sin_cero_sin_cargo'])->default('sin_cero_sin_cargo');
            $table->integer('ciclo_actual')->default(1);
            $table->enum('estado', ['activa', 'finalizada', 'cancelada'])->default('activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tandas');
    }
};
