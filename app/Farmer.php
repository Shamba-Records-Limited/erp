<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Farmer extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = "farmers";


    protected $primaryKey = 'id';

    protected $fillable = [
        'country_id',
        'county',
        'location_id',
        'id_no',
        'phone_no',
        'route_id',
        'bank_account',
        'member_no',
        'bank_branch_id',
        'customer_type',
        'kra',
        'user_id'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function bank_branch()
    {
        return $this->belongsTo(BankBranch::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
    public function limit()
    {
        return $this->hasOne(LoanLimit::class, 'farmer_id');
    }

    public function livestock(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cow::class);
    }

    public static function get_farmers($coop, $request, $limit){
        $query = "select u.id as user_id,
                       concat(u.first_name,' ', u.other_names ) as name,
                       f.member_no, f.phone_no, f.id_no, f.customer_type, r.name as route
                    from farmers f
                    join users u on f.user_id = u.id
                    join erp.routes r on f.route_id = r.id
                    where u.cooperative_id = '$coop'";

        if($request){
            if($request->filter_route){
                $query .= " and r.id = '$request->filter_route'";
            }

            if($request->filter_member_no){
                $query .= " and f.member_no = '$request->filter_member_no'";
            }

            if($request->filter_customer_type){
                $query .= " and f.customer_type = '$request->filter_customer_type'";
            }

            if($request->filter_id_no){
                $query .= " and f.id_no = '$request->filter_id_no'";
            }
        }

        if($limit){
            $query .= " order by u.created_at desc, name limit $limit";
        }else{
            $query .= " order by u.created_at desc, name ";
        }
        return DB::select($query);
    }
}
