<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class RecruitmentApplication extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";

    protected $fillable = [
        'surname',
        'othernames',
        'phone',
        'email',
        'area_of_residence',
        'qualification',
        'top_skills',
        'resume',
        'cover_letter',
        'status',//0-ongoing,1-interviews,1-closed,
        'recruitment_id'
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
    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id','id');
    }
}
