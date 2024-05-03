<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\LoanInstallment;
use App\Loan;
use App\LoanLimit;
use App\WalletTransaction;
use App\Wallet;
use App\Events\AuditTrailEvent;
use Log;
use DB;

class LoanAutoRepay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            //get due installments
            $installments = LoanInstallment::where('status',0)->whereDate('date','<=',now())->get();
            
            if(count($installments)) {
                foreach ($installments as $key => $installment) {
                    $loan_id = $installment->loan_id;
                    $farmer_id = $installment->loan->farmer_id;
                    $user_id = $installment->loan->farmer->user_id;
                    $installment_id = $installment->id;
                    //get wallet
                    $wallet =  Wallet::where('farmer_id', $farmer_id)->first();
                    $wallet_id = $wallet->id;
                    //auto deduct
                    if($wallet->available_balance > 0) {
                        if($wallet->available_balance > $installment->amount){
                            $amount = $installment->amount;
                        }
                        else if($wallet->available_balance <= $installment->amount){
                            $amount = $wallet->available_balance;
                        }
                        $transaction = new WalletTransaction();
                        $transaction->wallet_id = $wallet_id;
                        $transaction->type = 'Loan Repayment';
                        $transaction->amount = $amount;
                        $transaction->reference ='LOAN#'.$loan_id;
                        $transaction->source = 'wallet';
                        $transaction->initiator_id = $user_id;
                        $transaction->description = 'Loan repaid from wallet';
                        $transaction->save();
                        //update wallet
                        $wallet = Wallet::find($wallet_id);
                        $wallet->available_balance -= $amount;
                        $wallet->save();

                        //update limit
                        $loan_limit = LoanLimit::where('farmer_id', $farmer_id)->first();
                        $limit = LoanLimit::find($loan_limit->id);
                        $limit->limit += $amount;
                        $limit->save();
                        //update status
                        $update = LoanInstallment::find($installment_id);
                        if($amount == $installment->amount) {
                            $update->status = 1;
                        }
                        else {
                            $update->amount -= $amount;
                        }
                        $update->save();
                        //loan 
                        $loan = Loan::find($loan_id);
                        $loan->balance -= $amount;
                        $loan->save();
                    } else {
                        //log pending not repaid
                    }
                }
            }
        DB::commit();
        return 'success';
        } catch (\Throwable $th) {
            DB::rollback();
            Log::info($th);
        }
    }
}
