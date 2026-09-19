<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TandaParticipante extends Model
{
    protected $table = 'tanda_participantes';
    protected $guarded = [];

    public function tanda(): BelongsTo
    {
        return $this->belongsTo(Tanda::class, 'tanda_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(TandaCuota::class, 'tanda_participante_id');
    }
}