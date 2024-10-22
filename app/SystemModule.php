<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SystemModule extends Model
{
    protected $fillable = ['name'];

    protected  $primaryKey = "id";

    protected  $keyType = "string";

    public $incrementing = false;

    public function getRouteKeyName(): string
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


    public function cooperative_roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CooperativeInternalRole::class, 'modules_cooperative_internal_roles',
            'module_id', 'role_id');
    }

    public function subModules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SystemSubmodule::class, 'module_id', 'id');
    }
}
