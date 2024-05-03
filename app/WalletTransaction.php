<?php

namespace App;

use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class WalletTransaction extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'reference',
        'source',
        'initiator_id',
        'description',
        'phone',
        'org_conv_id',
        'conv_id',
        'status'
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

    public function wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wallet::class);

    }

    public function initiator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);

    }
    public function wallet_transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }


    public static function get_trx($farmer_id): \Illuminate\Support\Collection
    {
        $from = Cache::get('farmer_payments_from');
        $to = Cache::get('farmer_payments_to');
        $query = WalletTransaction::select('wallet_transactions.*')
            ->join('wallets', 'wallets.id', '=', 'wallet_transactions.wallet_id');
        if($from){
            Cache::put('farmer_payments_from', $from, now()->addMinutes(5));
            $from = Carbon::parse($from)->format('Y-m-d');
            $query = $query->whereDate('wallet_transactions.updated_at', '>=', $from);
        }

        if($to){
            Cache::put('farmer_payments_to', $to, now()->addMinutes(5));
            $to = Carbon::parse($to)->format('Y-m-d');
            $query = $query->whereDate('wallet_transactions.updated_at', '<=', $to);
        }

        return $query->where('wallets.farmer_id', $farmer_id)
            ->whereIn('wallet_transactions.type', ['payment'])->get();
    }
}
