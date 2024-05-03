<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeBankDetail extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;

    protected $fillable = [
        'bank_id',
        'account_name',
        'account_number',
        'bank_branch_id',
        'employee_id',
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
    public function bankBranch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id','id');
    }
    public function employee()
    {
        return $this->belongsTo(BankBranch::class, 'employee_id','id');
    }

    public function bank(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
