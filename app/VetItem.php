<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class VetItem extends Model
{

    protected $fillable = [
        'name','unit_id','cooperative_id','quantity','bp','sp'
    ];


    protected  $primaryKey = "id";

    protected $table = "vet_items";

    protected  $keyType = "string";

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


    public function unit()
    {
        return $this->belongsTo(Unit::class);

    }

    public function vets()
    {
        return $this->belongsToMany(User::class,"vets_vets_items","vet_id","vets_item_id");
    }

    public function vet_bookings(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(VetBooking::class,"bookings_vet_items","vet_item_id","vet_booking_id");
    }

    public static function vet_items($cooperativeId){
        return VetItem::select('id', 'name', 'quantity')
            ->where('cooperative_id', $cooperativeId)
            ->get();
    }
}
