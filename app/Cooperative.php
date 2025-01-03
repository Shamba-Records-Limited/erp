<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class Cooperative extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "cooperatives";

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'abbreviation',
        'country_id',
        'location',
        'address',
        'email',
        'contact_details',
        'logo',
        'currency',
        'owner_id',
        'deactivated_at'
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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function agents()
    {
        // Assuming 'cooperative_id' is the foreign key in the users table
        // and 'id' is the primary key in the cooperatives table
        return $this->hasMany(User::class, 'cooperative_id', 'id');
    }

    public function getAdminAttribute()
    {
        $admin = null;

        $users = DB::select(DB::raw("
            select u.id FROM users u
            JOIN model_has_roles ur ON ur.model_id = u.id
            JOIN roles r ON r.id = ur.role_id and r.name = 'cooperative admin'
            WHERE u.cooperative_id = :coop_id
        "), ["coop_id" => $this->id]);

        if(count($users) > 0){
            $admin = User::find($users[0]->id);
        }
        
        return $admin;
    }
}
