<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    /** @use HasFactory<\Database\Factories\ProductOptionValueFactory> */
    use HasFactory;

    protected $fillable = [
        'option_id',
        'value',
        'price_adjustment',
    ];

    //Relations
    public function option() {
        return $this->belongsTo(ProductOption::class);
    }


}
