<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Disease extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'cooperative_id', 'disease_category_id'
    ];


    public function getRouteKeyName()
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

    public function disease_category()
    {
        return $this->belongsTo(DiseaseCategory::class);
    }

    public static function diseases($cooperative)
    {
        return Disease::where('cooperative_id', $cooperative)->latest()->get();
    }
}
