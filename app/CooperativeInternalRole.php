<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class CooperativeInternalRole extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;
    protected $table = 'cooperative_internal_roles';
    protected $primaryKey = 'id';

    protected $fillable = ['role'];

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

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_cooperative_internal_roles',
            'role_id', 'user_id');
    }

    public function modules(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'modules_cooperative_internal_roles',
            'role_id', 'module_id');
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InternalRolePermission::class, 'internal_role_id', 'id');
    }
}
