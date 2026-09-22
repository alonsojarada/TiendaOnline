<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'debt_id', 
        'installment_id', // <--- Agrégalo aquí
        'user_id', // <--- Agrégalo aquí
        'amount', 
        'interest_covered', 
        'capital_covered', 
        'payment_date', 
        'notes'
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}