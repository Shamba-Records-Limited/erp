<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webpatser\Uuid\Uuid;


class TransportProvider extends Model
{
    protected $primarykey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'cooperative_id', 'name', 'phone_number', 'location',
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

    public function vehicles(): HasMany
    {
        return $this->hasMany(TransportProviderVehicle::class);
    }
}
