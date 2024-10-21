<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class FarmUnit extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = ['name'];


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

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    public static function farmUnits($cooperativeId){
        return FarmUnit::where('cooperative_id', $cooperativeId)->get();
    }
}
