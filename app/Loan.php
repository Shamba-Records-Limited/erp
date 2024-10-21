<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Loan extends Model
{
    const STATUS_REJECTED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REPAID= 2;
    const STATUS_PARTIAL_REPAYMENT = 3;
    const STATUS_BOUGHT_OFF = 4;
    const STATUS_PENDING = 5;

    const FARM_TOOLS_MACHINERY = 2;
    const FARM_TOOLS_VEHICLE = 2;

    const OPTION_REPAYMENT_MODE_ONE_OFF = 1;
    const OPTION_REPAYMENT_MODE_MONTHLY_DEDUCTIONS = 2;

    protected $fillable = [
        'amount',
        'balance',
        'status',//0 - rejected, 1 - approved, 2 - repaid, 4 - bought off
        'farmer_id',
        'due_date',
        'mode_of_payment',//repayment mode  1 - one off auto, 2 - monthly deductions
        'interest',
        'purpose',
        'loan_setting_id',
        'bought_off_at'
    ];

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id','id');
    }

    public function loan_setting(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LoanSetting::class,'loan_setting_id','id');
    }

    public function loanInstallments() {
        return $this->hasMany(LoanInstallment::class,'loan_id', 'id' );
    }
    public function loanRepayment() {
        return $this->hasMany(LoanRepayment::class);
    }

    public function bought_off_loan(){
        return $this->belongsTo(Loan::class, 'bought_off_loan_id', 'id');
    }

    public static function interests($cooperative) {
        return DB::select("select l.id, u.first_name, u.other_names, lt.type as type, lt.interest, lt.penalty, l.amount as amount, l.due_date
        from loans l join loan_settings lt on l.loan_setting_id = lt.id
        join farmers f on l.farmer_id = f.id join users u on f.user_id = u.id
        where u.cooperative_id = '$cooperative'");
    }

}
