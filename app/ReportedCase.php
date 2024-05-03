<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ReportedCase extends Model
{
    protected $primaryKey = "id";

    protected $keyType = "string";

    protected $fillable = [
        'farmer_id', 'disease_id', 'symptoms', 'symptoms', 'booked', 'cooperative_id', 'case_id'
    ];

    public function getRouteKeyName()
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

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id', 'id');
    }

    public function disease(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Disease::class, 'disease_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

    public static function cases(User $user){

        $query = ReportedCase::where('cooperative_id', $user->cooperative_id);
        if($user->hasRole('farmer')){
            $query = $query->where('farmer_id', $user->id);
        }
        return $query->latest()->get();
    }

}
