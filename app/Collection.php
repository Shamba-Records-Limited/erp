<?php

namespace App;

use Cache;
use Carbon\Carbon;
use DB;
use Exception;
use FurqanSiddiqui\ECDSA\Curves\Secp256k1_RPC;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Webpatser\Uuid\Uuid;



class Collection extends Model
{

    const SUBMISSION_STATUS_PENDING = 1;
    const SUBMISSION_STATUS_APPROVED = 2;
    const SUBMISSION_STATUS_REJECTED = 3;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'farmer_id',
        'product_id',
        'quantity',
        'batch_no',
        'status',
        'date_collected',
        'agent_id',
        'cooperative_id',
        'collection_number',
        'comments',
        'collection_quality_standard_id',
        'unit_price',
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

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }
 
    public function collection_quality_standard()
    {
        return $this->belongsTo(CollectionQualityStandard::class, 'collection_quality_standard_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * @param array $request
     * @param string $coop
     * @return $this
     */

    public function saveCollection(array $request)
    {
        $this->farmer_id = $request['farmerId'];
        $this->product_id = $request['productId'];
        $this->quantity = $request['availableQuantity'];
        $this->available_quantity = $request['availableQuantity'];
        $this->batch_no = strtoupper($request['batchNo']);
        $this->status = 1;
        $this->date_collected = Carbon::now()->format('Y-m-d');
        $this->agent_id = $request['agentId'];
        $this->cooperative_id = $request['cooperative'];
        $this->collection_quality_standard_id = $request['quality'];
        $this->collection_number = 'CR' . Carbon::now()->format('yymdhs');
        $this->comments = $request['comments'];
        $this->submission_status = $request['submission_status'];
        $this->collection_time = $request['collection_time'];
        $this->unit_price = $request['unit_price'];
        $this->save();
        
        return $this->refresh();
    }

    public static function get_collections($cooperative, $request, $limit)
    {
        $query = "SELECT SUM(c.quantity) AS quantity, SUM(c.available_quantity) AS available_quantity,
       SUM(p.buying_price*c.available_quantity) AS available_quantity_value,
       p.name,p.id as id, p.buying_price,p.threshold, SUM(p.buying_price*c.quantity) AS total_value, u.name as unit
       FROM collections c
        JOIN products p ON c.product_id = p.id
        JOIN units u ON p.unit_id = u.id
               WHERE c.cooperative_id = '$cooperative'";

        if ($request) {
            if ($request->product) {
                $query .= " AND p.id = '$request->product'";
            }
        }

        if ($limit) {
            return DB::select($query . " GROUP BY p.id LIMIT $limit");
        } else {
            return DB::select($query . " GROUP BY p.id");
        }
    }


    public static function get_collections_by_product($request, $productId, $limit)
    {
        $collections = Collection::where('product_id', $productId);

        if ($request) {
            if ($request->batch_no) {
                $collections = $collections->where('batch_no', $request->batch_no);
            }

            if ($request->farmer) {
                $collections = $collections->where('farmer_id', $request->farmer);
            }

            if ($request->quality) {
                $collections = $collections->where('collection_quality_standard_id', $request->quality);
            }

            if ($request->date) {
                $dates = split_dates($request->date);
                $from = $dates['from'];
                $to = $dates['to'];
                $collections = $collections->whereBetween('date_collected', [$from, $to]);
            }

            if ($request->agent) {
                $collections = $collections->where('agent_id', $request->agent);
            }
        }

        if ($limit) {
            return $collections->limit($limit)->orderBy('date_collected', 'DESC')->get();
        } else {
            return $collections->orderBy('date_collected', 'DESC')->get();
        }
    }


    public static function submitted_collections($coop, $request, $limit)
    {
        $collections = Collection::where('cooperative_id', $coop)
            ->whereIn('submission_status',
                [Collection::SUBMISSION_STATUS_PENDING, Collection::SUBMISSION_STATUS_REJECTED]);

        if ($request) {
            if ($request->batch_no) {
                $collections = $collections->where('batch_no', $request->batch_no);
            }

            if ($request->farmer) {
                $collections = $collections->where('farmer_id', $request->farmer);
            }

            if ($request->quality) {
                $collections = $collections->where('collection_quality_standard_id', $request->quality);
            }

            if ($request->date) {
                $dates = split_dates($request->date);
                $from = $dates['from'];
                $to = $dates['to'];
                $collections = $collections->whereBetween('date_collected', [$from, $to]);
            }

            if ($request->status) {
                $collections = $collections->where('submission_status', $request->status);
            }

            if ($request->product) {
                $collections = $collections->where('product_id', $request->product);
            }
        }

        if ($limit) {
            return $collections->limit($limit)->orderBy('date_collected', 'DESC')->get();
        } else {
            return $collections->orderBy('date_collected', 'DESC')->get();
        }
    }

    public static function pending_payments($coop, $request)
    {
        $approved = Collection::SUBMISSION_STATUS_APPROVED;
        $pending_payments = "
        SELECT CONCAT(u.first_name, ' ', u.other_names) AS name, u.id as id, f.id as farmer_id,f.member_no,
               w.current_balance as pending_payments, SUM(p.buying_price * c.quantity) AS collection_worth,
               f.bank_account,f.phone_no, bb.name as branch, b.name as bank
        FROM wallets w
            JOIN erp.farmers f ON w.farmer_id = f.id
            JOIN users  u ON u.id = f.user_id
            JOIN collections c ON c.farmer_id = f.id
            LEFT JOIN products p ON c.product_id = p.id
            JOIN bank_branches bb on f.bank_branch_id = bb.id
            JOIN banks b on bb.bank_id = b.id
        WHERE u.cooperative_id = '$coop' AND c.submission_status = '$approved'";

        try {
            if ($request) {

                if ((property_exists($request, 'date') && $request->date != null) || $request->date) {
                    $dates = split_dates($request->date);
                    $from = $dates['from'];
                    $to = $dates['to'];
                    $pending_payments .= " AND (c.date_collected BETWEEN '$from' AND '$to')";
                }

                if ((property_exists($request, 'batch_no') && $request->batch_no != null) || $request->batch_no) {
                    $pending_payments .= " AND c.batch_no = '$request->batch_no'";
                }

                if ((property_exists($request, 'product') && $request->product != null) || $request->product) {
                    $pending_payments .= " AND c.product_id = '$request->product'";
                }

            }
            $pending_payments .= " GROUP BY  c.farmer_id, w.id  ORDER BY pending_payments DESC, name";


            return DB::select($pending_payments);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $pending_payments .= " GROUP BY  c.farmer_id, w.id  ORDER BY pending_payments DESC, name";
            return DB::select($pending_payments);
        }
    }

    public static function farmer_collections($cooperative, $farmer_id)
    {
        $from = Cache::get('farmer_collection_from');
        $to = Cache::get('farmer_collection_to');
        $query = Collection::where("cooperative_id", $cooperative);
        if ($from) {
            $from = Carbon::parse($from)->format('Y-m-d');
            $query = $query->whereDate('date_collected', '>=', $from);
        }

        if ($to) {
            $to = Carbon::parse($to)->format('Y-m-d');
            $query = $query->whereDate('date_collected', '<=', $to);
        }

        Cache::forget('farmer_collection_to');
        Cache::forget('farmer_collection_from');
        return $query->where('farmer_id', $farmer_id)->get();
    }
}
