<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmerAuctionOrder extends Model
{
    protected $table = 'farmer_auction_orders';

    protected $fillable = [
        'id',
        'batch_number',
        'miller_id',
        'user_id',
        'published_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public $incrementing = false; // Since the ID is a UUID

    protected $keyType = 'string'; // ID is a string (UUID)
}
