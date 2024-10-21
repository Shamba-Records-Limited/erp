<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePosition extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'position_id',
        'employee_id'
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
    public function employee()
    {
        return $this->belongsTo(CoopEmployee::class, 'employee_id','id');
    }
    public function position()
    {
        return $this->belongsTo(JobPosition::class, 'position_id','id');
    }
}
