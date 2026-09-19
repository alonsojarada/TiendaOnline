<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TandaCuota extends Model
{
    protected $table = 'tanda_cuotas';

    protected $fillable = [
        'tanda_id',
        'tanda_participante_id',
        'ciclo',
        'fecha_limite',
        'monto_esperado',
        'monto_pagado',
        'fecha_pago',
        'estado',
        'user_id',
    ];

    public function tanda(): BelongsTo
    {
        return $this->belongsTo(Tanda::class, 'tanda_id');
    }

    public function participante(): BelongsTo
    {
        return $this->belongsTo(TandaParticipante::class, 'tanda_participante_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}