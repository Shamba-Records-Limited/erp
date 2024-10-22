<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class EmployeeAppraisal extends Model
{
    use SoftDeletes;

    const APPRAISAL_TYPE_PROMOTION = 1;
    const APPRAISAL_TYPE_SALARY_RAISE = 2;
    const APPRAISAL_TYPE_SALARY_CUT = 3;
    const APPRAISAL_TYPE_DEMOTION = 4;
    const APPRAISAL_TYPE_PIP = 5;

    protected $keyType = "string";
    public $incrementing = false;
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

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);

    }

    public function actionedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'actioned_by_id', 'id');

    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CoopEmployee::class, 'employee_id', 'id');

    }

    public function oldPosition()
    {
        return $this->belongsTo(EmployeePosition::class, 'old_position_id', 'position_id');
    }
    public function newPosition()
    {
        return $this->belongsTo(EmployeePosition::class, 'new_position_id', 'position_id');
    }

    public function oldDepartment()
    {
        return $this->belongsTo(CoopBranchDepartment::class, 'old_department_id', 'id');
    }

    public function newDepartment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CoopBranchDepartment::class, 'new_department_id', 'id');
    }

    public function oldEmploymentType()
    {
        return $this->belongsTo(EmployeeEmploymentType::class, 'old_employment_type_id', 'employment_type_id');
    }
     public function newEmploymentType()
     {
        return $this->belongsTo(EmployeeEmploymentType::class, 'new_employment_type_id', 'employment_type_id');
    }

}
