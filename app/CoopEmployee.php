<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoopEmployee extends Model
{
    use SoftDeletes;
    const STATUS_DEACTIVATED = 4;
    const STATUS_ACTIVE = 1;
    const STATUS_SUSPENDED_WITH_PAY = 2;
    const STATUS_SUSPENSION_WITHOUT_PAY = 3;

    protected $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'country_code',
        'county_of_residence',
        'area_of_residence',
        'marital_status',
        'dob',
        'gender',
        'id_no',
        'phone_no',
        'employee_no',
        'kra',
        'nhif_no',
        'nssf_no',
        'department_id',
        'user_id',
        'status'
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

    //rlshps
    public function coopBranch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CoopBranch::class, 'branch_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'id');
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CoopBranchDepartment::class, 'department_id', 'id');
    }

    public function position(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EmployeePosition::class, 'employee_id');
    }

    public function bankDetails()
    {
        return $this->hasOne(EmployeeBankDetail::class, 'employee_id');
    }

    public function files()
    {
        return $this->hasMany(EmployeeFile::class, 'employee_id');
    }

    public function employmentType()
    {
        return $this->hasOne(EmployeeEmploymentType::class, 'employee_id');
    }

    public function employeeLeave()
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id');
    }

    public function employeeAllowance()
    {
        return $this->hasMany(EmployeeAllowance::class, 'employee_id');
    }

    public function employeeSalary()
    {
        return $this->hasOne(EmployeeSalary::class, 'employee_id');
    }


    public static function get_employees($coop, $department, $limit)
    {
        $notDeactivatedStatus = CoopEmployee::STATUS_DEACTIVATED;
        $employees = "
            SELECT emp.id,emp.employee_no, CONCAT(u.first_name, ' ', u.other_names) AS name, c.name AS country,
                   emp.id_no, emp.phone_no, et.type AS employment_type, jp.position, emp.status, c.iso_code AS iso,
                   d.name as department
            FROM coop_employees emp
                JOIN users u ON emp.user_id = u.id
                JOIN countries c ON emp.country_code= c.id
                JOIN employee_employment_types emp_type ON emp.id = emp_type.employee_id
                JOIN employment_types et ON emp_type.employment_type_id = et.id
                JOIN employee_positions ep ON emp.id = ep.employee_id
                JOIN job_positions jp ON ep.position_id = jp.id
                JOIN coop_branch_departments d ON emp.department_id = d.id
            WHERE u.cooperative_id = '$coop' AND emp.status <> '$notDeactivatedStatus'
        ";

        if ($department) {
            $employees .= " AND d.id = '$department' ";
        }

        if ($limit) {
            $employees .= " ORDER BY name LIMIT $limit";
        } else {
            $employees .= " ORDER BY name";
        }

        return DB::select($employees);
    }
}
