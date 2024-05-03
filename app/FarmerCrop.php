<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class FarmerCrop extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = [
        'farmer_id', 'crop_id', 'stage_id', 'last_date', 'next_stage_id', 'total_cost', 'cooperative_id', 'start_date', 'type', 'livestock_id'
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

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }

    public function crop(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Crop::class, 'crop_id', 'id');
    }

    public function stage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CropCalendarStage::class, 'stage_id', 'id');
    }

    public function next_stage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CropCalendarStage::class, 'next_stage_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

    public function livestock(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cow::class, 'livestock_id', 'id');
    }


    /**
     * @param User $user
     * @return FarmerCrop[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function farmerCrops(User $user, $type)
    {
        if($type == 0) {
            $crops = FarmerCrop::where('cooperative_id', $user->cooperative_id);
        } else {
            $crops = FarmerCrop::where('cooperative_id', $user->cooperative_id)->where('type', $type);
        }

        
        if ($user->hasRole('farmer')) {
            $crops = FarmerCrop::where('cooperative_id', $user->cooperative_id)
                ->where('farmer_id', $user->farmer->id);
        }
        return $crops->get();
    }

    public static function farmYieldCrops($cooperative_id, $complete_status){
        return FarmerCrop::where('cooperative_id', $cooperative_id)
            ->where('status', $complete_status)
            ->get();
    }
}
