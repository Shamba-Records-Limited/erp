<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;

class Vehicle extends Model
{
    protected $primarykey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'cooperative_id', 'registration_number', 'vehicle_type_id', 'user_id', 
        'weight', 'status', 'status_date', 'status_comment',
    ];

    public const Active  = 1;

    public const Service = 2;
    
    public const Closed  = 3;

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

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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

        $statusText = '';
        switch($status[0]) {
            case 'Active': $statusText = 'Active'; 
            break;
            case 'Service': $statusText = 'Under Service'; 
            break;
            case 'Closed': $statusText = 'Account Closed'; 
            break;
        };

        return $statusText;
    }
}
