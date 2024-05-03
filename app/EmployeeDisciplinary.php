<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class EmployeeDisciplinary extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const DISCIPLINARY_TYPE_SUSPENSION_WITH_PAY = 2;
    const DISCIPLINARY_TYPE_SUSPENSION_WITHOUT_PAY = 3;
    const DISCIPLINARY_TYPE_TERMINATION = 4;

    const CASES_TO_SKIP_PAYROLL_DATA = [
        EmployeeDisciplinary::DISCIPLINARY_TYPE_TERMINATION,
        EmployeeDisciplinary::DISCIPLINARY_TYPE_SUSPENSION_WITHOUT_PAY];

    const WITH_PAY = 1;
    const WITHOUT_PAY = 0;

    public $incrementing = false;


    public function getRouteKeyName(): string
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

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);

    }

    public function actionedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'actioned_by', 'id');

    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CoopEmployee::class, 'employee_id', 'id');

    }

    public static function suspended_without_pay_employees($employeeId)
    {
        $casesToSkipPayrollDays = EmployeeDisciplinary::CASES_TO_SKIP_PAYROLL_DATA;
        return EmployeeDisciplinary::where('employee_id', $employeeId)
            ->where('status', EmployeeDisciplinary::STATUS_ACTIVE)
            ->whereIn('disciplinary_type', $casesToSkipPayrollDays)
            ->whereDate('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->first();
    }
}
