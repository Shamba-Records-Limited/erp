<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class FinalProductRawMaterial extends Model
{
    //
    protected  $keyType = "string";
    public $incrementing = false;

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

    public function milled_inventory()
    {
        return $this->belongsTo(MilledInventory::class, "milled_inventory_id", "id");
    }
}
