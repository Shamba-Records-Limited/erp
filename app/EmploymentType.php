<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentType extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";

    protected $fillable = [
        'type',
        'cooperative_id'
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
    //rlshps
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id','id');
    }

    public function typeEmployees()
    {
        return $this->hasMany(EmployeeEmploymentType::class);
    }
}
