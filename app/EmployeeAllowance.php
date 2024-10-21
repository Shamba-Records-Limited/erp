<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAllowance extends Model
{

    const TYPE_BENEFIT = 'benefit';
    const TYPE_DEDUCTION = 'deduction';
    const TYPE_INSURANCE = 'insurance';
    const TYPE_MORTGAGE = 'mortgage';
    use SoftDeletes;
    protected  $keyType = "string";

    public $incrementing = false;
    protected $fillable = [
        'amount',
        'type',//benefit or deduction
        'title',
        'description',
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
}
