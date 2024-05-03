<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class EmployeeEmploymentType extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'employment_type_id',
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
    public function employeeType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id','id');
    }
}
