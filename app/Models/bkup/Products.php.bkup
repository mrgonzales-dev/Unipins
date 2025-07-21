<?php

namespace App\Models;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model implements HasMedia
{

    use InteractsWithMedia;
    use HasFactory;

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
