<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class CountyGovtOfficial extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $table = 'county_govt_officials';

    // todo: fill this
    protected $fillable = [
        'country_id',
        'county_id',
        'sub_county_id',
        'gender',
        'id_no',
        'phone_no',
        'employee_no',
        'user_id',
        'status',
        'ministry',
        'designation',
        'cooperative_id'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

}