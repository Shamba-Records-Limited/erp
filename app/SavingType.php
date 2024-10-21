<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SavingType extends Model
{
    protected $fillable = [
        'type','period','interest','cooperative_id'
    ];


    protected  $primaryKey = "id";

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

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }
}
