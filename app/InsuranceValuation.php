<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class InsuranceValuation extends Model
{
    protected $fillable = ['type', 'amount', 'description', 'cooperative_id'];

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string)Uuid::generate(4);
        });
    }

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }
}
