<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'client_id', 'type', 'concept', 'total_amount', 
        'loan_modal', 'interest_rate', 'payment_frequency', 
        'installments_count', 'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

    // Accessor para calcular el capital actual (restando los abonos a capital)
    public function getCurrentCapitalAttribute()
    {
        $capitalPaid = $this->payments()->sum('capital_covered');
        return max(0, $this->total_amount - $capitalPaid);
    }
}
