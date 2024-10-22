<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class VehicleType extends Model
{
    protected $primarykey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'name', 'cooperative_id'
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
    
}
