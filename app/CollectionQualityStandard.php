<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class CollectionQualityStandard extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'cooperative_id',
        'name',
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

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id','id');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'collection_quality_standard_id','id');
    }

    public static function getStandardQualities($coop){
       return  CollectionQualityStandard::select('id', 'name')->where('cooperative_id', $coop)->orderBy('name')->get();
    }
}
