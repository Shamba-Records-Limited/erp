<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ProductionHistory extends Model
{

    const EXPIRY_STATUS_NOT_EXPIRED = 1;
    const EXPIRY_STATUS_EXPIRED = 2;
    public $incrementing = false;

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

    public function production(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Production::class, 'production_id', 'id');
    }

    public function registered_by(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function raw_materials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RawMaterial::class, 'raw_material_id', 'id');
    }

    public static function raw_material_used($cooperativeId, $productionHistoryId)
    {
        return ProductionMaterial::where('cooperative_id', $cooperativeId)
            ->where('production_history_id', $productionHistoryId)
            ->with('rawMaterial')
            ->get();
    }

    public static function production_histories($id, $request, $limit)
    {
        $production_history = ProductionHistory::where('production_id', $id);

        if ($request) {
            if ($request->production_lot) {
                $production_history = $production_history->where('production_lot', $request->production_lot);
            }

            if ($request->expiry_date) {
                $dates = split_dates($request->expiry_date);
                $from = $dates['from'];
                $to = $dates['to'];
                $production_history = $production_history->whereBetween('expiry_date', [$from, $to]);
            }

            if ($request->expiry_status) {
                $production_history = $production_history->where('expiry_status', $request->expiry_status);
            }
        }

        if ($limit) {
            return $production_history->latest()->limit($limit)->get();
        } else {
            return $production_history->latest()->get();
        }
    }

    public static function production_history_by_store($storeId, $request, $limit)
    {
        $production_history = ProductionHistory::join('productions', 'production_histories.production_id', '=', 'productions.id' )
        ->where('productions.manufacturing_store_id', $storeId);

        if ($request) {
            if ($request->production_lot) {
                $production_history = $production_history->where('production_histories.production_lot', $request->production_lot);
            }

            if ($request->expiry_date) {
                $dates = split_dates($request->expiry_date);
                $from = $dates['from'];
                $to = $dates['to'];
                $production_history = $production_history->whereBetween('production_histories.expiry_date', [$from, $to]);
            }

            if ($request->expiry_status) {
                $production_history = $production_history->where('production_histories.expiry_status', $request->expiry_status);
            }
        }

        if ($limit) {
            return $production_history
                ->orderBy('productions.created_at', 'DESC')
                ->limit($limit)
                ->get();
        } else {
            return $production_history
                ->orderBy('productions.created_at')
                ->get();
        }
    }

    public static function expired_products($coopId, $request, $limit)
    {
        $production_history = ProductionHistory::select('production_histories.*')->join('productions', 'production_histories.production_id', '=', 'productions.id')
            ->where('production_histories.cooperative_id', $coopId)
            ->where('production_histories.expiry_status', ProductionHistory::EXPIRY_STATUS_EXPIRED);

        if ($request) {
            if ($request->production_lot) {
                $production_history = $production_history
                    ->where('production_histories.production_lot', $request->production_lot);
            }

            if ($request->expiry_date) {
                $dates = split_dates($request->expiry_date);
                $from = $dates['from'];
                $to = $dates['to'];
                $production_history = $production_history
                    ->whereBetween('production_histories.expiry_date', [$from, $to]);
            }

            if ($request->final_product) {
                $production_history = $production_history
                    ->where('productions.final_product_id', $request->final_product);
            }
        }

        if ($limit) {
            return $production_history->orderBy('production_histories.expiry_date')->limit($limit)->get();
        } else {
            return $production_history->orderBy('production_histories.expiry_date')->get();
        }
    }
}
