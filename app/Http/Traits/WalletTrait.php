<?php

namespace App\Http\Traits;

use App\BulkPayment;
use App\Events\AuditTrailEvent;
use App\Exceptions\FailedToCompleteFarmerPaymentException;
use App\Wallet;
use App\WalletTransaction;
use Illuminate\Support\Facades\Auth;

trait WalletTrait
{
    /**
     * @throws FailedToCompleteFarmerPaymentException
     */
    private function pay_farmer_util($request, $details, $prefix, $mode = BulkPayment::PAYMENT_MODE_INTERNAL_TRANSFER)
    {
        $farmer_wallet = Wallet::where('farmer_id', $request->farmer_id)->first();
        $farmer = \App\Farmer::findOrFail($request->farmer_id);
        $farmer_names = ucwords(strtolower($farmer->user->first_name . ' ' . $farmer->user->other_names));

        $user = Auth::user();

        if ($request->amount > $farmer_wallet->current_balance) {
            $audit_trail_data = ['user_id' => $user->id,
                'activity' =>
                    'Initiated payments for farmer id  '
                    . $request->farmer_id . ' of ' . $request->amount
                    . ' which is more than ' . $farmer_wallet->current_balance,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            throw new FailedToCompleteFarmerPaymentException('Payments are more than the farmer`s pending payments');
        }

        //update current balance
        if ($mode == BulkPayment::PAYMENT_MODE_INTERNAL_TRANSFER) {
            $farmer_wallet->available_balance += $request->amount;
        }

        $farmer_wallet->current_balance -= $request->amount;
        $farmer_wallet->save();

        $wallet_transaction = new WalletTransaction();
        $wallet_transaction->wallet_id = $farmer_wallet->id;
        $wallet_transaction->type = 'payment';
        $wallet_transaction->amount = $request->amount;
        $wallet_transaction->reference = $prefix . date('Ymdhis').strtoupper(substr(generate_password(), 2, 2));;
        $wallet_transaction->source = 'internal';
        $wallet_transaction->initiator_id = $user->id;
        $wallet_transaction->description = $details;
        $wallet_transaction->phone = null;
        $wallet_transaction->save();
        $acc_trx_amount = $request->amount;

        $data = [
            "date" => date('Y-m-d'),
            "income" => null,
            "expense" => $acc_trx_amount,
            "particulars" => "Pay Farmer",
            "user_id" => $user->id,
            "cooperative_id" => Auth::user()->cooperative_id,
        ];
        $record_expenditure = has_recorded_income_expense($data);

        //30,000
        //62,644,000.00
        //62,614,000.00
        //416,000.00

        $trx = create_account_transaction('Farmer Payments', $acc_trx_amount, "Pay farmer {$farmer_names} for collections made");

        if ($record_expenditure && $trx) {
            $audit_trail_data = ['user_id' => $user->id,
                'activity' => 'Paid farmer with id ' . $request->farmer_id . ' a total of ' . $request->amount,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
        } else {
            throw new FailedToCompleteFarmerPaymentException('Failed to update ledgers');

        }
    }
}
