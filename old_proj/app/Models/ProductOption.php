<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    /** @use HasFactory<\Database\Factories\ProductOptionFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'type',
    ];

    // Relations
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // HasMany

    public function values() {
        return $this->hasMany(ProductOptionValue::class, 'product_option_id');
    }


}
