<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAuctionCartItem extends Model
{


    public $incrementing = false; // UUID primary key
    protected $keyType = 'string'; // Primary key is a string

    protected $fillable = [
        'id',
        'cart_id',
        'product_id',
        'quantity',
    ];


    protected static function boot()
{
    parent::boot();
    static::creating(function ($model) {
        $model->id = (string) \Illuminate\Support\Str::uuid();
    });
}

    // Define relationships
    public function cart()
    {
        return $this->belongsTo(ProductMillerCart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
