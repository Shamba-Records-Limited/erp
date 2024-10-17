<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class InventoryItem extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "inventory_items";

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

    public function Inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }
}
