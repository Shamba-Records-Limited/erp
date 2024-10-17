<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Vet extends Model
{
    protected $fillable = [
        'phone_no', 'id_no', 'gender', 'profile_image', 'user_id'
    ];

    protected $primaryKey = "id";

    protected $table = "vets";

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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function vets($cooperativeId): \Illuminate\Support\Collection
    {
        return Vet::select("users.first_name", 'users.other_names', 'users.id')
            ->join('users', 'users.id', '=', 'vets.user_id')
            ->where('users.cooperative_id', $cooperativeId)
            ->get();
    }

}
