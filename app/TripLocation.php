<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webpatser\Uuid\Uuid;

class TripLocation extends Model
{
    protected $primarykey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'cooperative_id', 'trip_id', 'type', 'location_id', 'datetime'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            $model->id = (string)Uuid::generate(4);
        });
    } 

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function weighBridgeEvent(): HasOne
    {
        return $this->hasOne(WeighBridgeEvent::class);
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }
}
