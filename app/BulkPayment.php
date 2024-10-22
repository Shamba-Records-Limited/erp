<?php

namespace App;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Log;
use Webpatser\Uuid\Uuid;

class BulkPayment extends Model
{

    const PAYMENT_MODE_INTERNAL_TRANSFER = 1;
    const PAYMENT_MODE_OFFLINE = 2;

    const PAYMENT_MODE_STATUS_COMPLETED = 1;
    const PAYMENT_MODE_STATUS_PENDING = 2;
    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = [
        'batch', 'total_amount', 'cooperative_id', 'created_by_id', 'mode', 'status'
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

    public static function bulk_payment_batches($cooperative, $request, $limit)
    {
        $bulkPayment = "SELECT bp.id, CONCAT(u.first_name,' ', u.other_names) AS names,bp.batch,
                        bp.mode,bp.total_amount, bp.created_at as date, bp.updated_at as date_updated,
                        bp.status
                        FROM bulk_payments bp
                        JOIN users u ON bp.created_by_id = u.id";

        try {

            if ($request) {
                if ((property_exists($request, 'dates') && $request->dates != null) || $request->dates) {


                    $dates = split_dates($request->dates);
                    $from = $dates['from'];
                    $to = $dates['to'];
                    $bulkPayment .= " WHERE ( bp.created_at BETWEEN '$from' AND '$to')";
                }

//            if (property_exists($request, 'employee') && $request->employee) {
//                $bulkPayment .= " AND bp.created_by_id = '$request->employee'";
//            }

                if ((property_exists($request, 'batch') && $request->batch != null) || $request->batch) {
                    $bulkPayment .= " AND bp.batch = '$request->batch'";
                }

                if ((property_exists($request, 'mode') && $request->mode != null) || $request->mode) {
                    $bulkPayment .= " AND bp.mode = '$request->mode'";
                }
            }
            if ($limit) {
                return DB::select($bulkPayment . " ORDER BY bp.created_at DESC LIMIT $limit");
            } else {
                return DB::select($bulkPayment . " ORDER BY bp.created_at DESC");
            }
        } catch (Exception $x) {
            Log::error($x->getMessage());
            if ($limit) {
                return DB::select($bulkPayment . " ORDER BY bp.created_at DESC LIMIT $limit");
            } else {
                return DB::select($bulkPayment . " ORDER BY bp.created_at DESC");
            }
        }

    }

    public static function bulk_payments_farmers($cooperative, $batch){
        $query = "SELECT u.id as user_id, wt.id, wt.amount, wt.reference, bp.mode, CONCAT(u.first_name, ' ', u.other_names) AS name,
                f.bank_account,f.phone_no,f.member_no, bb.name AS branch, b.name AS bank
            FROM wallet_transactions wt
            JOIN bulk_payments bp ON wt.reference LIKE '$batch%' AND bp.batch = '$batch'
            JOIN wallets w ON wt.wallet_id = w.id
            JOIN erp.farmers f ON w.farmer_id = f.id
            JOIN users  u ON u.id = f.user_id
            JOIN bank_branches bb ON f.bank_branch_id = bb.id
            JOIN banks b ON bb.bank_id = b.id
            WHERE bp.cooperative_id = '$cooperative'";
        return DB::select($query);
    }
}
