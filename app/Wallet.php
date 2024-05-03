<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Wallet extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
    protected $fillable = [
        'available_balance',
        'current_balance',
        'farmer_id',
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
    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id','id');
    }

    public static function getWalletByFarmerId(string $farmerId){
       return  Wallet::select('id','current_balance','available_balance')
            ->where('farmer_id', $farmerId)
            ->first();
    }

    public static function averageCashFlow(int $period, string $walletId){
        $start = Carbon::now();
        $lastDate = Carbon::now()->subMonths($period);

        $transactions = WalletTransaction::select('amount')
            ->where('wallet_id', $walletId)
            ->whereBetween('created_at', [$start, $lastDate])
            ->sum('amount');
        return ceil($transactions/$period);
    }
}
