<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoopBranchDepartment extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";

    protected $fillable = [
        'name',
        'code',
        'office_number',
        'branch_id',
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
    public function coopBranch()
    {
        return $this->belongsTo(CoopBranch::class, 'branch_id','id');
    }
    public function departmentEmployee()
    {
        return $this->hasMany(CoopEmployee::class, 'department_id');
    }
}
