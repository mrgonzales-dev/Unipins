<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory , InteractsWithMedia;

    use  InteractsWithMedia;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];


    // Media
    public function registerMediaCollections(): void  {
        $this->addMediaCollection('product_images');
    }

    // Relations
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // HasMany
    public function options() {
        return $this->hasMany(ProductOption::class);
    }



}
