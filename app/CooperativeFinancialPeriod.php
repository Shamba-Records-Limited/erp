<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class CooperativeFinancialPeriod extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = "cooperative_financial_periods";


    protected $primaryKey = 'id';

    public function getRouteKeyName(): string
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

    public static function default_cooperative_financial_periods($cooperative_id){
        $monthly_financial_period = new CooperativeFinancialPeriod();
        $monthly_financial_period->cooperative_id = $cooperative_id;
        $monthly_financial_period->start_period = Carbon::now()->format('Y-m-d');
        $monthly_financial_period->end_period = Carbon::now()->addMonth()->format('Y-m-d');
        $monthly_financial_period->type = 'monthly';
        $monthly_financial_period->balance_bf = 0;
        $monthly_financial_period->balance_cf = 0;
        $monthly_financial_period->active = 1;
        $monthly_financial_period->save();

        $quarterly_financial_period = new CooperativeFinancialPeriod();
        $quarterly_financial_period->cooperative_id = $cooperative_id;
        $quarterly_financial_period->start_period = Carbon::now()->format('Y-m-d');
        $quarterly_financial_period->end_period = Carbon::now()->addMonths(3)->format('Y-m-d');
        $quarterly_financial_period->type = 'quarterly';
        $quarterly_financial_period->balance_bf = 0;
        $quarterly_financial_period->balance_cf = 0;
        $quarterly_financial_period->active = 1;
        $quarterly_financial_period->save();

        $annually_financial_period = new CooperativeFinancialPeriod();
        $annually_financial_period->cooperative_id = $cooperative_id;
        $annually_financial_period->start_period = Carbon::now()->format('Y-m-d');
        $annually_financial_period->end_period = Carbon::now()->addYear()->format('Y-m-d');
        $annually_financial_period->type = 'annually';
        $annually_financial_period->balance_bf = 0;
        $annually_financial_period->balance_cf = 0;
        $annually_financial_period->active = 1;
        $annually_financial_period->save();
    }
}
