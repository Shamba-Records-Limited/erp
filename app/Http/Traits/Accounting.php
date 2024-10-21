<?php

namespace App\Http\Traits;

use App\AccountingTransaction;
use App\CooperativeFinancialPeriod;
use App\Events\AuditTrailEvent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

trait Accounting
{
    private function updateFinancialPeriods(): bool
    {
        try {
            $today = Carbon::now()->format('Y-m-d');
            $user = Auth::user();
            $financialPeriod = CooperativeFinancialPeriod::where('cooperative_id', $user->cooperative_id)
                ->where('active', true)
                ->whereDate('end_period', '<=', $today)
                ->get();
            foreach ($financialPeriod as $fp){
                $this->closeAndCreateNewPeriod($fp->id, true);
            }
            return true;
        }catch (Exception $ex){
            Log::error("Error: %s ".$ex->getMessage());
            return false;
        }
    }

    private function closeAndCreateNewPeriod($financialPeriodId, $isAutoUpdate = false)
    {
        $financial_period = CooperativeFinancialPeriod::find($financialPeriodId);
        $start_date = Carbon::parse($financial_period->start_period);
        $end_date = Carbon::parse($financial_period->end_period);
        $financial_period_type = $financial_period->type;
        $cooperative = Auth::user()->cooperative_id;
        //check is the financial year has actually ended
        if (Carbon::now()->isBefore($end_date)) {
            Log::warning(sprintf('The last day of the financial period %s is: %s ', $end_date->format('d M, Y'), $financialPeriodId));
            if (!$isAutoUpdate) {
                toastr()->error('The last day of the financial period is: ' . $end_date->format('d M, Y'));
                return redirect()->back();
            }

        } else {

            try {
                DB::beginTransaction();
                // Calculate balance cf
                $accounting_transactions = AccountingTransaction::where('cooperative_id', $cooperative)
                    ->whereBetween('date', [$start_date, $end_date]);
                $debits = $accounting_transactions->sum('debit');
                $credits = $accounting_transactions->sum('credit');

                $balance_cf = ($credits + $financial_period->balance_bf) - $debits;
                //change status to closed and save the balance cf
                $financial_period->active = false;
                $financial_period->balance_cf = $balance_cf;
                $financial_period->save();
                //create the next financial period
                $next_fy_start_date = Carbon::parse($end_date)->addDay()->format('Y-m-d');
                $next_fy_end_date = null;
                if ($financial_period_type == 'monthly') {
                    $next_fy_end_date = Carbon::parse($next_fy_start_date)->addMonth()->format('Y-m-d');
                }

                if ($financial_period_type == 'quarterly') {
                    $next_fy_end_date = Carbon::parse($next_fy_start_date)->addMonths(4)->format('Y-m-d');
                }
                if ($financial_period_type == 'annually') {
                    $next_fy_end_date = Carbon::parse($next_fy_start_date)->addYear()->format('Y-m-d');
                }
                //check if that period has been created
                $existing_same_period = CooperativeFinancialPeriod::where('cooperative_id', $cooperative)
                        ->whereDate('start_period', $next_fy_start_date)
                        ->whereDate('end_period', $next_fy_end_date)
                        ->count() > 0;
                if (!$existing_same_period) {
                    $next_financial_period = new CooperativeFinancialPeriod();
                    $next_financial_period->balance_bf = $balance_cf; // bf from previous period
                    $next_financial_period->cooperative_id = $cooperative;
                    $next_financial_period->start_period = $next_fy_start_date;
                    $next_financial_period->end_period = $next_fy_end_date;
                    $next_financial_period->type = $financial_period_type;
                    $next_financial_period->active = true;
                    $next_financial_period->save();
                }
                DB::commit();

                $activity = $isAutoUpdate ? 'Automatically ' : '';
                $audit_trail_data = [
                    'user_id' => Auth::user()->id, 'activity' => $activity . 'Closed financial period running from ' .
                        $start_date->format('d M, Y') . ' - ' . $end_date->format('d M, Y'),
                    'cooperative_id' => $cooperative
                ];
                event(new AuditTrailEvent($audit_trail_data));
                if (!$isAutoUpdate) {
                    toastr()->success('Financial period updated successfully');
                    return redirect()->back();
                }

            } catch (Throwable $ex) {
                DB::rollBack();
                Log::error($ex->getMessage());
                if (!$isAutoUpdate) {
                    toastr()->error('Oops! Request could not be processed at the moment');
                    return redirect()->back();
                }
            }
        }
    }
}
