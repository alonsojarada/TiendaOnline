<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{

    protected $fillable = ['name', 'slug', 'description', 'price', 'stock', 'sku', 'is_active', 'category_id'];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
}
