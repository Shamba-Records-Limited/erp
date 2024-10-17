<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class FarmerExpectedYield extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = ['quantity', 'crop_id', 'livestock_id','volume_indicator','farm_unit_id', 'cooperative_id'];


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

    public function livestock_breed(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        {
            return $this->belongsTo(Breed::class, 'livestock_breed_id', 'id');
        }
    }

    public function crop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Crop::class, 'crop_id', 'id');
    }

    public function farm_unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FarmUnit::class, 'farm_unit_id', 'id');
    }
    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    public static function expectedYields($cooperative_id){
        return FarmerExpectedYield::where('cooperative_id', $cooperative_id)->get();
    }

}
