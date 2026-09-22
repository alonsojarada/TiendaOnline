<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class Client extends Model
{
    //
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'name',
        'alias',
        'phone',
        'address',
        'notes',
        'status',
        'company_id',
        'user_id', // <-- Agregado para capturar el ID del usuario que crea el cliente
    ];

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function tandas()
    {
        return $this->belongsToMany(Tanda::class, 'tanda_participantes', 'cliente_id', 'tanda_id')
                    ->withPivot(['numero_asignado', 'estado'])
                    ->withTimestamps();
    }

}
