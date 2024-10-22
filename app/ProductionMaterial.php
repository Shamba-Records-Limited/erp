<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionMaterial extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        'raw_material_id',
        'production_id',
        'cooperative_id',
        'cost',
        'quantity',
        'unit_id'
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
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id','id');
    }
    public function productionHistory()
    {
        return $this->belongsTo(ProductionHistory::class, 'production_history_id','id');
    }
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id','id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id','id');
    }
}
