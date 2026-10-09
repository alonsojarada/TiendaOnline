<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class Debt extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'client_id',
        'user_id',
        'type',
        'concept',
        'total_amount',
        'loan_modal',
        'interest_rate',
        'payment_frequency',
        'installments_count',
        'status',
        'created_at',
        'loan_date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // <-- Agregamos esta relación faltante
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function installments()
    {
        return $this->hasMany(LoanInstallment::class);
    }

    // Relación con los artículos del crédito de mercancía (store_credit)
    public function items()
    {
        return $this->hasMany(CreditItem::class, 'debt_id');
    }
}