<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class FarmerCropProgressTracker extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'farmer_crop_id', 'stage_id', 'last_date', 'next_stage_id', 'cost','status','start_date'
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

    public function farmer_crop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FarmerCrop::class,'farmer_crop_id','id');
    }

    public function stage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CropCalendarStage::class, 'stage_id', 'id');
    }

    public function next_stage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CropCalendarStage::class, 'next_stage_id', 'id');
    }

    public function costBreakDowns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CropCalendarStageCostBreakdown::class, 'tracker_id', 'id');
    }
}
