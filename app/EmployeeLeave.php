<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeave extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";

    protected $fillable = [
        'start_date',
        'end_date',
        'reason',
        'remarks',
        'file',
        'status',//0-pending,1-granted,2-rejected,3-complete
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
