<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webpatser\Uuid\Uuid;

class Trip extends Model
{
    protected $primarykey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'cooperative_id', 'transport_type', 'transport_provider_id', 'vehicle_id', 'driver_name',
        'driver_phone_number', 'load_type', 'load_unit', 'trip_distance', 'trip_cost_per_km',
        'trip_cost_per_kg', 'trip_cost_total', 'status', 'status_date', 'status_comment', 'id',
    ];

    public const Scheduled  = 1;

    public const Inprogress = 2;
    
    public const Completed = 3;

    public const cancelled = 4;

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

    public function locations(): HasMany
    {
        return $this->hasMany(TripLocation::class);
    }

    public function weighbridgeEvents(): HasMany
    {
        return $this->hasMany(WeighBridgeEvent::class);
    }

    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

    public function transportProvider(): HasOne
    {
        return $this->hasOne(TransportProvider::class, 'id', 'transport_provider_id');
    }

    public function transporterVehicle(): HasOne
    {
        return $this->hasOne(TransportProviderVehicle::class, 'id', 'vehicle_id');
    }

    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'id', 'load_unit');
    }

    public function location(string $which): TripLocation
    {
        return TripLocation::where('trip_id', $this->id)->where('type', strtoupper($which))->first();
    }

    public function statusText()
    {
        $obj = new \ReflectionClass(__CLASS__);
        $status = \collect($obj->getConstants())
            ->filter(function($const) {
                return $const == $this->status;
            })
            ->keys()
            ->all();

        return $status[0];
    }
}
