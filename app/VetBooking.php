<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class VetBooking extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = [
        'event_start','event_end','event_name','cooperative_id','farmer_id','vet_id','reported_case_id',
        'booking_type','service_id','status'
    ];


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


    public function vet()
    {
        return $this->belongsTo(User::class);
    }

    public function farmer()
    {
        return $this->belongsTo(User::class);
    }

    public function service(){
        return $this->belongsTo(VetService::class, 'service_id', 'id');
    }

    public function vet_items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(VetItem::class,"bookings_vet_items","vet_booking_id","vet_item_id");
    }

    public static function bookings(User $user){

        $query = VetBooking::where('cooperative_id', $user->cooperative_id);
         if($user->hasRole('farmer')){
             $query = $query->where('farmer_id', $user->id);
         }
        return $query->latest()->limit(100)->get();
    }
}
