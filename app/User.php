<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Webpatser\Uuid\Uuid;
use Laravel\Passport\HasApiTokens;


//ALTER TABLE `lara_erp`.`oauth_auth_codes`
//CHANGE COLUMN `user_id` `user_id` CHAR(36) NOT NULL ;


//ALTER TABLE `lara_erp`.`oauth_clients`
//CHANGE COLUMN `user_id` `user_id` CHAR(36) NULL DEFAULT NULL ;


//ALTER TABLE `lara_erp`.`oauth_auth_codes`
//CHANGE COLUMN `user_id` `user_id` CHAR(36) NOT NULL ;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    const STATUS_DEACTIVATED = 4;
    const STATUS_ACTIVE = 1;
    const STATUS_SUSPENDED_WITH_PAY = 2;
    const STATUS_SUSPENSION_WITHOUT_PAY = 3;

    public $incrementing = false;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'username', 'country_id'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $primaryKey = "id";

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

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);

    }

    public function audit_trails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AuditTrail::class);
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, "farmers_products", "farmer_id", "product_id");
    }

    public function farmer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Farmer::class);
    }

    public function vet_items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, "vets_vets_items", "vet_id", "vets_item_id");
    }

    public function vet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Vet::class);
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CoopEmployee::class, 'user_id');
    }

    public function cooperative_roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CooperativeInternalRole::class, 'users_cooperative_internal_roles',
            'user_id', 'role_id');
    }

    public static function setup_default_admin()
    {
        try {
            DB::beginTransaction();
            $coop_id = Cooperative::where('default_coop', 1)->first()->id;
            $role = Role::select('id', 'name')->where('name', '=', 'cooperative admin')->first();
            $role_name = $role->name;
            $count_admins = DB::select("SELECT count(*) as count FROM users u 
                                JOIN model_has_roles mr ON mr.model_id = u.id 
                                JOIN roles r ON r.id = mr.role_id 
                                WHERE r.name = '$role_name' AND u.cooperative_id = '$coop_id'")[0]->count;

            echo 'Found ' . $count_admins . ' Admins';
            if ($count_admins > 0) {
                echo 'Admin is already set';
            } else {
                $user = new User();
                $user->first_name = 'John';
                $user->other_names = 'Doe';
                $user->cooperative_id = $coop_id;
                $user->email = 'john@erp.com';
                $user->username = 'john_admin';
                $user->password = bcrypt(env('DEFAULT_PASSWORD'));
                $user->save();
                $user->refresh()->assignRole($role_name);
                CooperativeFinancialPeriod::default_cooperative_financial_periods($coop_id);
                DB::commit();
                echo 'User Created successfully';
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            echo 'Error: ' . $th->getMessage();

        }
    }

}
