<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "lots";

    protected $primaryKey = 'lot_number';

    public function getRouteKeyName()
    {
        return 'lot_number';
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, "lot_number", "lot_number");
    }

    public function getUnitAttribute()
    {
        $first_collection = $this->collections->first();
        return $first_collection->unit;
    }

    public function getQuantityAttribute()
    {
        $qty = 0;
        $collections = $this->collections;
        foreach($collections as $collection){
            $qty += $collection->quantity;
        }

        return $qty;
    }

}
