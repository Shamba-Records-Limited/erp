<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class AdvanceDeductionTransaction extends Model
{

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

    public function payroll(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'id');
    }

    public function advanceDeduction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdvanceDeduction::class, 'advance_deduction_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

    public static function advance_deduction_trx($advance_deduction_id){
        return  DB::select(
            "
            SELECT adt.amount,adt.balance, p.period_year AS year, p.period_month AS month FROM advance_deduction_transactions adt
            JOIN payrolls p ON adt.payroll_id = p.id
            WHERE adt.advance_deduction_id = '$advance_deduction_id'
            ORDER BY adt.created_at
            ");
    }
}
