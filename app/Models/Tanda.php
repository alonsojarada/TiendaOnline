<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanda extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'nombre',
        'monto_cuota',
        'frecuencia',
        'total_participantes',
        'incluye_turno_cero',
        'ciclo_actual',
        'estado'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function participantes(): HasMany
    {
        return $this->hasMany(TandaParticipante::class);
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(TandaCuota::class);
    }
}
