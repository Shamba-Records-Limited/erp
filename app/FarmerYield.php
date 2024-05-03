<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class FarmerYield extends Model
{

    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    const FREQUENCY_TYPE_DAILY = 2;
    const FREQUENCY_TYPE_TOTAL = 1;


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
    public function livestock_breed(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Breed::class, 'livestock_breed_id', 'id');
    }

    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function expected_yields(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FarmerExpectedYield::class, 'expected_yields_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

    /**
     * @param User $user
     * @return array
     */
    public static function yields(User $user): array
    {
        $from = Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        $queryCrops = FarmerYield::where('cooperative_id', $user->cooperative_id)->whereBetween('created_at', [$from, $to]);
        $queryLivestock = FarmerYield::where('cooperative_id', $user->cooperative_id)->whereBetween('created_at', [$from, $to]);
        if($user->hasRole('farmer')){
            $queryLivestock = $queryLivestock->where('farmer_id', $user->farmer->id);
            $queryCrops = $queryCrops->where('farmer_id', $user->farmer->id);
            dd('Not a farmer');
        }
        $livestock = $queryLivestock->where('type', 'livestock')->get();
        $crops = $queryCrops->where('type', '!=','livestock')->get();
        return ['livestock' => $livestock, "crops" =>$crops];
    }

}
