<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanda extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'nombre',
        'monto_cuota',
        'frecuencia',              // Asegúrate de que coincida con tu columna de frecuencia
        'cuotas_por_entrega',      // <--- Agrégala aquí para que Laravel permita guardarla
        'total_participantes',
        'modalidad',
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

    // Relación: Una tanda tiene muchos participantes (tabla pivote)
    public function participantes(): HasMany
    {
        return $this->hasMany(TandaParticipante::class, 'tanda_id');
    }

    // Relación directa: Clientes inscritos en esta tanda
    public function clientes(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'tanda_participantes', 'tanda_id', 'cliente_id')
            ->withPivot(['numero_asignado', 'estado'])
            ->withTimestamps();
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(TandaCuota::class);
    }
}