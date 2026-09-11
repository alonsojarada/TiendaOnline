<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tandas', function (Blueprint $table) {
            $table->id();
            // Apuntando correctamente a la tabla 'companies'
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre', 150);
            $table->decimal('monto_cuota', 10, 2);
            $table->enum('frecuencia', ['semanal', 'quincenal', 'mensual']);
            $table->integer('total_participantes');
            $table->boolean('incluye_turno_cero')->default(true);
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
