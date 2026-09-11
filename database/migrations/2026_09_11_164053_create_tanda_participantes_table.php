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
       Schema::create('tanda_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanda_id')->constrained('tandas')->onDelete('cascade');            
            $table->foreignId('cliente_id')->nullable()->constrained('clients')->onDelete('set null');            
            $table->integer('turno');
            $table->integer('ciclo_entrega');
            $table->boolean('es_organizador')->default(false);
            $table->enum('estado', ['activo', 'completado', 'retirado'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanda_participantes');
    }
};
