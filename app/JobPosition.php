<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosition extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";

    protected $fillable = [
        'position',
        'role',
        'code',
        'description',
        'cooperative_id',
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
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }
    public function employeePosition()
    {
        return $this->hasMany(EmployeePosition::class, 'position_id');
    }
}
