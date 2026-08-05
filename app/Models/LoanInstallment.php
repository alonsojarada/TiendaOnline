<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanInstallment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'debt_id', 'installment_number', 'due_date', 'amount_due', 'status'
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
