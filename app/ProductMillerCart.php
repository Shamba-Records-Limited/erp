<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductMillerCart extends Model
{
    
    public $incrementing = false; // UUID primary key
    protected $keyType = 'string'; // Primary key is a string

    protected $fillable = [
        'id',
        'miller_id',
        'farmer_id',
        'user_id',
    ];
    
    protected static function boot(){
    parent::boot();
    static::creating(function ($model) {
        $model->id = (string) \Illuminate\Support\Str::uuid();
    });
}

    // Define relationships
    public function miller()
    {
        return $this->belongsTo(Miller::class, 'miller_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(ProductAuctionCartItem::class, 'cart_id');
    }

}
