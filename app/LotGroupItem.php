<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class LotGroupItem extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "lot_group_items";

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
}
