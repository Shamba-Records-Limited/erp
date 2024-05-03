<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class RawMaterialSupplyPayment extends Model
{
    public $incrementing = false;

    protected $primaryKey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'amount',
        'balance',
        'supply_history_id',
        'cooperative_id',
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
    public function supply_history(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RawMaterialSupplyHistory::class, 'supply_history_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

}
