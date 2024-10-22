<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class LotGradeDistribution extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "lot_grade_distributions";

    protected $primaryKey = 'id';

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

    public function product_grade()
    {
        return $this->belongsTo(ProductGrade::class, "product_grade_id", "id");
    }
}
