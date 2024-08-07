<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class LotGroup extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "lot_groups";

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

    public function lotGroupItems(){
        return $this->hasMany(LotGroupItem::class, 'lot_group_id', 'id');
    }

    public function getLotsAttribute(){
        $items = $this->lotGroupItems;
        $lots = [];
        foreach($items as $item) {
            $lots[] = Lot::where('lot_number',$item->lot_number)->first();
        }
        return $lots;
    }
}
