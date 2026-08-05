<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
        'phone',
        'address',
        'notes',
    ];

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

}
