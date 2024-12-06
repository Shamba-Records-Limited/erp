<?php

namespace App;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmerAuctionOrderItem extends Model
{
    protected $table = 'farmer_auction_order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'selling_price',
    ];

    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // UUID is a string

    // Automatically generate a UUID for the `id` field
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Define relationship with FarmerAuctionOrder
    public function order()
    {
        return $this->belongsTo(FarmerAuctionOrder::class, 'order_id');
    }




}
