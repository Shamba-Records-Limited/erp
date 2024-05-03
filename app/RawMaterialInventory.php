<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class RawMaterialInventory extends Model
{
    public $incrementing = false;
    protected $primaryKey = "id";
    protected $keyType = "string";

    protected $fillable = [
        'raw_material_id',
        'quantity',
        'value',
    ];

    public function getRouteKeyName(): string
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
    public function raw_material(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id', 'id');
    }
}
