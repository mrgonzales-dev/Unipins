<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'stock',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

}
