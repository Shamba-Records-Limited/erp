<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class AuditTrail extends Model
{

    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'activity', 'company_id', 'cooperative_id'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public static function auditTrails($request, $cooperativeId, $isForExport=false)
    {
        $query = AuditTrail::where('cooperative_id', $cooperativeId);
        $from = null;
        $to = null;
        $employee = $request->employee;


        if ($request->dates) {
            $dates = split_dates($request->dates);
            $from = $dates['from'];
            $to = $dates['to'];
        }

        if ($employee) {
            $query = $query->where('user_id', $employee);
        }

        if ($from) {
            $query = $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query = $query->whereDate('created_at', '<=', $to);
        }

        if($isForExport){
            return $query->orderBy('created_at', 'desc')->get();
        }

        return $query->orderBy('created_at', 'desc')->limit(100)->get();


    }
}
