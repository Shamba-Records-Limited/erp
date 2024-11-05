<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class MillerBranch extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "miller_branches";
    protected $primaryKey = 'id';

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

    // Define the relationship to the Miller model
    public function miller()
    {
        return $this->belongsTo(Miller::class, 'miller_id');
    }

    // Define the relationship to the County model
    public function county()
    {
        return $this->belongsTo(County::class, 'county_id');
    }

    // Define the relationship to the SubCounty model
    public function subCounty()
    {
        return $this->belongsTo(SubCounty::class, 'sub_county_id');
    }
}
