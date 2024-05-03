<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Cow extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    const APPROVAL_STATUS_APPROVED = 1;
    const APPROVAL_STATUS_REJECTED = 2;
    const APPROVAL_STATUS_PENDING = 3;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name','tag_name','breed_id','cooperative_id','farmer_id', 'approval_status'
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

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function stages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CropCalendarStage::class, 'livestock_id','id');
    }

    public static function persist($request, $cooperative_id, $farmerId, $approval_status, $cow)
    {
        $cow->name = $request->name;
        $cow->tag_name = $request->tag_name;
        $cow->breed_id = $request->breed_id;
        $cow->farmer_id = $farmerId;
        $cow->animal_type = $request->animal_type;
        $cow->cooperative_id = $cooperative_id;
        $cow->approval_status = $approval_status;
        $cow->save();
        return true;
    }
}
