<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Payroll extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = "string";

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

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CoopEmployee::class, 'employee_id', 'id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public static function payroll_details($request, $cooperativeId, $limit)
    {

        $query = "
            SELECT CONCAT(u.first_name,' ', u.other_names) as name, d.name as department,ce.id as employee_id,p.id as id,
                   p.basic_pay, p.net_pay,p.taxable_income,p.paye, p.period_month, p.period_year
            from payrolls p
                JOIN coop_employees ce ON ce.id = p.employee_id
                JOIN coop_branch_departments d ON ce.department_id = d.id
                JOIN employee_employment_types et ON ce.id = et.employee_id
                JOIN users u ON ce.user_id = u.id
            WHERE p.cooperative_id = '$cooperativeId'";


        if ($request) {
            if ($request->month) {
                $query .= " AND p.period_month = '$request->month'";
            }

            if ($request->year) {
                $query .= " AND p.period_year = '$request->year'";
            }

            if (isset($request->employees)) {
                $employees = formatArrayForSQL($request->employees);
                $query .= " AND ce.id in $employees";
            }

            if ($request->employment_type && $request->employment_type != 'all') {
                $query .= " AND et.employment_type_id =  '$request->employment_type'";
            }

            if ($request->department) {
                $query .= " AND ce.department_id =  '$request->department'";
            }
        }

        if ($limit) {
            return DB::select($query . " ORDER BY p.period_year DESC, p.period_month DESC LIMIT $limit");
        } else {
            return DB::select($query . " ORDER BY p.period_year DESC, p.period_month DESC");
        }

    }

    public static function department_payrolls($request, $cooperativeId, $limit): array
    {

        $query = "
            SELECT d.name, SUM(p.paye) AS paye,SUM(p.net_pay) AS net_pay ,SUM(p.basic_pay) as basic_pay,
                   SUM(p.taxable_income) as taxable_income, p.period_year, p.period_month, d.name AS department
            FROM payrolls p
                  JOIN coop_employees ce ON ce.id = p.employee_id
                  JOIN coop_branch_departments d ON ce.department_id = d.id
                  JOIN employee_employment_types et ON ce.id = et.employee_id
             WHERE p.cooperative_id = '$cooperativeId'";

        if ($request) {
            if (property_exists($request, 'month') && $request->month) {
                $query .= " AND p.period_month = '$request->month'";
            }

            if (property_exists($request, 'year') &&  $request->year) {
                $query .= " AND p.period_year = '$request->year'";
            }

            if ($request->department) {
                $query .= " AND ce.department_id =  '$request->department'";
            }
        }

        if ($limit) {
            return DB::select($query . " GROUP BY ce.department_id, p.period_month,
             p.period_year ORDER BY p.period_year DESC, p.period_month DESC LIMIT $limit");
        } else {
            return DB::select($query . "  GROUP BY ce.department_id, p.period_month,
             p.period_year ORDER BY p.period_year DESC, p.period_month DESC");
        }

    }
}
