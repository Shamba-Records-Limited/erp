<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSalary extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;

    const REPORT_TYPE_P9 = 'p9';
    const REPORT_TYPE_P10 = 'p10';
    const REPORT_TYPE_NET_PAY = 'net pay';
    const REPORT_TYPE_GROSS_PAY = 'gross pay';
    const REPORT_TYPE_NHIF = 'nhif';
    const REPORT_TYPE_NSSF = 'nssf';
    const REPORT_TYPE_HOUSING_FUND = 'housing fund';
    const REPORT_TYPE_DEDUCTION = 'deductions';
    const REPORT_TYPE_ALLOWANCE = 'allowances';
    const DEDUCTION_TYPE_STATUTORY = 1;
    const DEDUCTION_TYPE_NON_STATUTORY = 2;

    protected $fillable = [
        'amount',
        'job_group',
        'has_benefits',
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
    public function employee()
    {
        return $this->belongsTo(CoopEmployee::class, 'employee_id','id');
    }
}
