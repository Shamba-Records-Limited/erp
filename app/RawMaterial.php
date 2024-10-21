<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        'name',
        'estimated_cost',
        'unit_id',
        'cooperative_id'
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

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id','id');
    }

    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
