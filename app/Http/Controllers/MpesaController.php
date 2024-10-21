<?php

namespace App\Http\Controllers;

use App\GroupLoan;
use App\GroupLoanRepayment;
use App\Http\Traits\Payment;
use App\InvoicePayment;
use App\LNMTransaction;
use App\Loan;
use App\LoanInstallment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Farmer;
use App\Wallet;
use App\WalletTransaction;
use Log;

class MpesaController extends Controller
{
    const SUCCESS_RESULT_CODE = "0";
    const MPESA_AMOUNT_INDEX = 0;
    const MPESA_RECEIPT_INDEX = 1;
    const MPESA_TRANSACTION_DATE_INDEX = 2;
    const MPESA_PHONE_NUMBER_INDEX = 3;

    use Payment;

    //mpesa result
    public function getC2BResults(Request $request)
    {
        try {
            Log::info("Get B2C Results", $request->all());
            DB::beginTransaction();
            $amount = $request->TransAmount;
            $mpesaRef = $request->TransID;
            $transactionDate = now();//$request->Body['stkCallback']['CallbackMetadata']['Item'][3]['Value'];
            $phoneNumber = $request->MSISDN;
            $account = $request->BillRefNumber;
            //get farmer
            $farmer = Farmer::where('phone_no', 'LIKE', '%' . substr($phoneNumber, -9))
                ->with(['user', 'wallet'])
                ->first();

            if ($farmer) {
                $farmer_id = $farmer->id;
                $user_id = $farmer->user->id;
                $wallet_id = $farmer->wallet->id;
                //save transaction
                $transaction = new WalletTransaction();
                $transaction->wallet_id = $wallet_id;
                $transaction->type = 'Deposit';
                $transaction->amount = $amount;
                $transaction->reference = $mpesaRef;
                $transaction->source = 'MPESA';
                $transaction->initiator_id = $user_id;
                $transaction->description = json_encode($request);
                $transaction->phone = $phoneNumber;
                $transaction->save();
                //update wallet
                $wallet = Wallet::find($wallet_id);
                $wallet->available_balance += $amount;
                $wallet->current_balance += 0;
                $wallet->save();
            }
            DB::commit();
            return 'success';
        } catch (\Throwable $th) {
            Log::error("getC2BResults: ", $th->getMessage());
            Log::debug($th->getTraceAsString());
            DB::rollback();
            return response()->json('Error', 500);
        }

    }

    //validation
    public function getC2BValidation(Request $request)
    {
        Log::info("Validation: ", $request->all());
    }

    public function b2cInit(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        $request->validate([
            'amount' => 'required|numeric|max:' . $user->farmer->wallet->available_balance
        ],
            [
                'amount.max' => 'You have only ' . $user->farmer->wallet->available_balance . ' in your a/c. Withdrawal must not be more than that'
            ]);
        $this->b2c($request->phone ?? $user->farmer->phone_no, $request->amount);
        toastr()->success('Payment initiated successfully');
        return back();
    }

    //b2c initiate
    public function b2c($phone, $amount)
    {
        $mpesa = new B2CController();
        $user = Auth::user();
        $b2cTransaction = $mpesa->b2c($amount, $phone, $Remarks ?? 'None', $user);

        $return_object = json_decode($b2cTransaction, true);
        Log::info("B2C response ", $return_object);
        $array = explode(",", $return_object);
        //get the conversation and the originatorid for the transaction
        $conversationid_string = explode(":", ltrim($array[0], '{'));

        $originatorconversationid_string = explode(":", $array[1]);
        $c_rtrim = rtrim($conversationid_string[1], '"');
        $o_rtrim = rtrim($originatorconversationid_string[1], '"');
        $checksum_array['conversation_id'] = str_replace(' ', '', str_replace('"', '', $c_rtrim));
        $checksum_array['originator_id'] = str_replace(' ', '', str_replace('"', '', $o_rtrim));

        //save the response fo use in callback
        //save transaction
        //get farmer
        $phoneNumber = $phone;
        $farmer = Farmer::where('phone_no', 'LIKE', '%' . substr($phoneNumber, -9))->with(['user', 'wallet'])->first();
        if ($farmer) {
            $farmer_id = $farmer->id;
            $user_id = $farmer->user->id;
            $wallet_id = $farmer->wallet->id;

            $transaction = new WalletTransaction();
            $transaction->wallet_id = $wallet_id;
            $transaction->type = 'payment';
            $transaction->amount = $amount;
            $transaction->reference = 'mpesaRef';
            $transaction->source = 'MPESA';
            $transaction->initiator_id = $user_id;
            $transaction->description = 'Awaiting mpesa response';
            $transaction->phone = $phoneNumber;
            $transaction->org_conv_id = $checksum_array['originator_id'];
            $transaction->conv_id = $checksum_array['conversation_id'];
            $transaction->status = 2;
            $transaction->save();
        } else {
            Log::info($checksum_array);
        }
        return $checksum_array;
    }

    //stk push
    public function lnmStkPush(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|max:' . Auth::user()->farmer->wallet->available_balance
        ]);
        // ============GET PAYBILL=============//
        $user = Auth::user();
        //get configs
        try {
            $curl_response = $this->stk_push($user, $request->phone, $request->amount, "Deposit");
            //if succesfull
            if (substr($curl_response->ResponseDescription ?? $curl_response->errorMessage, 0, 7) == 'Success') {
                //save to db
                toastr()->success('Deposit initiated. Complete the MPESA request in your phone');
                return back();
            } else {
                toastr()->error('Deposit failed initiated');
                return back();
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Log::debug($th->getTraceAsString());
            toastr()->error('Deposit failed initiated');
            return back();
        }
    }

    public function stkpush_results(Request $request)
    {
        try {
            DB::beginTransaction();
            Log::debug("stkpush_results: ");
            Log::debug($request);
            //receive data to verify
            $resultCode = $request->Body['stkCallback']['ResultCode'];
            $resultDesc = $request->Body['stkCallback']['ResultDesc'];
            $merchantRequestID = $request->Body['stkCallback']['MerchantRequestID'];
            $checkoutRequestID = $request->Body['stkCallback']['CheckoutRequestID'];
            $lnmTrx = LNMTransaction::where('merchant_request_id', $merchantRequestID)
                ->where('checkout_request_id', $checkoutRequestID)
                ->where('status', LNMTransaction::STATUS_INITIATED)
                ->first();
            if ($resultCode == self::SUCCESS_RESULT_CODE) {
                $receipt = $request->Body['stkCallback']['CallbackMetadata']['Item'][self::MPESA_RECEIPT_INDEX]['Value'];
                $amount = $request->Body['stkCallback']['CallbackMetadata']['Item'][self::MPESA_AMOUNT_INDEX]['Value'];
                if (count($request->Body['stkCallback']['CallbackMetadata']['Item']) == 5) {
                    $paid_phone = $request->Body['stkCallback']['CallbackMetadata']['Item'][self::MPESA_PHONE_NUMBER_INDEX + 1]['Value'];
                    $transaction_date = $request->Body['stkCallback']['CallbackMetadata']['Item'][self::MPESA_TRANSACTION_DATE_INDEX + 1]['Value'];
                } else {
                    $paid_phone = $request->Body['stkCallback']['CallbackMetadata']['Item'][self::MPESA_PHONE_NUMBER_INDEX]['Value'];
                    $transaction_date = $request->Body['stkCallback']['CallbackMetadata']['Item'][self::MPESA_TRANSACTION_DATE_INDEX]['Value'];
                }

                Log::info("success full payment");
                $formattedDateTransactionDate = Carbon::createFromFormat('YmdHis', $transaction_date)->format('Y-m-d H:i:s');
                $lnmTrx->result_code = $resultCode;
                $lnmTrx->result_description = $resultDesc;
                $lnmTrx->receipt = $receipt;
                $lnmTrx->transaction_date = $formattedDateTransactionDate;
                $lnmTrx->phone_number = $paid_phone;
                $lnmTrx->status = LNMTransaction::STATUS_SUCCESS;
                $lnmTrx->amount = $amount;
                $lnmTrx->save();
                $model = $lnmTrx->model_name;
                if ($model == GroupLoanRepayment::class) {
                    $this->updateGroupLoan($amount, $merchantRequestID, $checkoutRequestID);
                }

                if ($model == LoanInstallment::class) {
                    $this->updateCommercialLoan($amount, $merchantRequestID, $checkoutRequestID);
                }

                if($model == InvoicePayment::class){

                    $invoicePayment = InvoicePayment::where('merchant_request_id', $merchantRequestID)
                        ->where('checkout_request_id', $checkoutRequestID)->first();
                    if($invoicePayment){
                        $invoicePayment->transaction_number = $receipt;
                        $invoicePayment->save();
                        complete_sale_payment($invoicePayment->id);
                    }
                }
            } else {
                Log::info("Payment Failed: [{$resultDesc}]");
                $lnmTrx->status = LNMTransaction::STATUS_FAILED;
                $lnmTrx->result_code = $resultCode;
                $lnmTrx->result_description = $resultDesc;
                $lnmTrx->save();
                $model = $lnmTrx->model_name;
                if ($model == GroupLoanRepayment::class) {
                    $groupLoanRepayment = GroupLoanRepayment::where('merchant_request_id', $merchantRequestID)
                        ->where('checkout_request_id', $checkoutRequestID)
                        ->where('status', GroupLoanRepayment::STATUS_INITIATED)
                        ->first();
                    $groupLoanRepayment->status = GroupLoanRepayment::STATUS_FAILED;
                    $groupLoanRepayment->save();
                }

                if ($model == LoanInstallment::class) {
                    $loanInstallment = LoanInstallment::where('merchant_request_id', $merchantRequestID)
                        ->where('checkout_request_id', $checkoutRequestID)
                        ->where('status', LoanInstallment::STATUS_PENDING)
                        ->first();
                    $loanInstallment->status = LoanInstallment::STATUS_FAILED;
                    $loanInstallment->save();
                }

                if($model == InvoicePayment::class ){
                    $invoicePayment = InvoicePayment::where('merchant_request_id', $merchantRequestID)
                        ->where('checkout_request_id', $checkoutRequestID)->first();
                    $invoicePayment->status = InvoicePayment::PAYMENT_STATUS_FAILED;
                    $invoicePayment->updated_at = Carbon::now();
                    $invoicePayment->save();
                }
            }

            DB::commit();

        } catch (\Throwable $t) {
            Log::error("Operation Failed");
            Log::error($t);
            DB::rollBack();
        }

    }


    private function updateGroupLoan($amount, $merchantRequestId, $checkoutRequestId)
    {
        $groupLoanRepayment = GroupLoanRepayment::where('merchant_request_id', $merchantRequestId)
            ->where('checkout_request_id', $checkoutRequestId)
            ->where('status', GroupLoanRepayment::STATUS_INITIATED)
            ->first();

        $group_loan = $groupLoanRepayment->group_loan;
        $remaining_balance = $group_loan->balance - $amount;
        $group_loan->status = $remaining_balance == 0 ? GroupLoan::STATUS_PAID : GroupLoan::STATUS_PARTIALLY_PAID;
        $group_loan->balance = $remaining_balance;
        $group_loan->save();
        $groupLoanRepayment->status = GroupLoanRepayment::STATUS_COMPLETED;
        $groupLoanRepayment->save();
        
        create_account_transaction('Loan Repaid', $amount, 'Repay group loan via MPESA', $group_loan->farmer->user->cooperative_id);
    }

    private function updateCommercialLoan($amount, $merchantRequestId, $checkoutRequestId)
    {
        $loanInstallment = LoanInstallment::where('merchant_request_id', $merchantRequestId)
            ->where('checkout_request_id', $checkoutRequestId)
            ->where('status', LoanInstallment::STATUS_PENDING)
            ->first();
        $loan = $loanInstallment->loan;
        $loan->balance -= $amount;
        if ($loan->balance <= 0) {
            Log::info("Fully Paid Loan #$loan->id with MPESA");
            $loan->status = Loan::STATUS_REPAID;
        } else {
            Log::info("Partially Paid Loan #$loan->id with MPESA");
            $loan->status = Loan::STATUS_PARTIAL_REPAYMENT;
        }
        $loan->save();
        $loanInstallment->status = LoanInstallment::STATUS_PAID;
        $loanInstallment->repaid_amount += $amount;
        $loanInstallment->save();
        
        create_account_transaction('Loan Repaid', $amount, 'Repay loan via MPESA', $loan->farmer->user->cooperative_id);
    }

    private function updateSales($merchantRequestId, $checkoutRequestId){


    }
}
