<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Store extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'store_logo',
    ];



    // Get the user that owns the store
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

}
