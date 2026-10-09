<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditItem extends Model
{
    use HasFactory;

    protected $table = 'credit_items';

    protected $fillable = [
        'debt_id',
        'description',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class, 'debt_id');
    }
}