<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class MillerAuctionOrder extends Model
{
    //

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "miller_auction_order";

    protected $primaryKey = 'id';

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

    public function miller(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Miller::class);
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MillerAuctionOrderItem::class, 'order_id', 'id');
    }

    public function getQuantityAttribute()
    {
        $rawQty = DB::select(DB::raw("
            SELECT sum(item.quantity) as quantity
            FROM miller_auction_order_item item
            WHERE item.order_id = '$this->id'
        "));
        return $rawQty[0]->quantity;
    }

    public function deliveries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AuctionOrderDelivery::class, 'order_id', 'id');
    }

    public function getDeliveredQuantityAttribute()
    {
        $rawQty = DB::select(DB::raw("
            SELECT sum(item.quantity) as quantity
            FROM auction_order_delivery_item item
            JOIN auction_order_delivery delivery ON delivery.id = item.delivery_id
            WHERE delivery.order_id = '$this->id'
        "));
        return $rawQty[0]->quantity;
    }

    public function getUndeliveredQuantityAttribute()
    {
        return $this->quantity - $this->deliveredQuantity;
    }

}
