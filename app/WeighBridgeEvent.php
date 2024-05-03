<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webpatser\Uuid\Uuid;

class WeighBridgeEvent extends Model
{
    protected $primarykey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'cooperative_id', 'trip_id', 'trip_location_id', 'weight', 'datetime'
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

    public function tripLocation(): BelongsTo
    {
        return $this->belongsTo(TripLocation::class);
    }
}
