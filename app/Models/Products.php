<?php

namespace App\Models;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;


class Products extends Model implements HasMedia
{

    use InteractsWithMedia;


    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'stock',
    ];

    public function regiterMediaCollections(): void
    {
        $this->addMediaCollection('product_images');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

}
