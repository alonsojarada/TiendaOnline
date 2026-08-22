<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'phone', 'address'])]
class Company extends Model
{
    use HasFactory;

    // Relación: Una empresa tiene muchos usuarios
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}