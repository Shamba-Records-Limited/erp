<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Inventory extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "inventories";

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function Order()
    {
        return $this->belongsTo(MillerAuctionOrder::class, 'order_id', 'id');
    }
}
