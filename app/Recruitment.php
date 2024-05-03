<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Recruitment extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";

    protected $fillable = [
        'role',
        'description',
        'desired_skills',
        'qualifications',
        'employment_type',
        'salary_range',
        'location',
        'file',
        'status',//0-ongoing,1-closed,2-expired,
        'end_date',
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
}
