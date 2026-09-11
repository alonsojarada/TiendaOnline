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
        Schema::create('tanda_cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanda_id')->constrained('tandas')->onDelete('cascade');
            $table->foreignId('tanda_participante_id')->constrained('tanda_participantes')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('ciclo');
            $table->decimal('monto_esperado', 10, 2);
            $table->decimal('monto_pagado', 10, 2)->default(0.00);
            $table->enum('estado', ['pendiente', 'parcial', 'pagado', 'atrasado'])->default('pendiente');
            $table->date('fecha_limite');
            $table->dateTime('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanda_cuotas');
    }
};
