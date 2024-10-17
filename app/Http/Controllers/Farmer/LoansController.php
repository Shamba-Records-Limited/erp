<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Http\Traits\FinancialProducts;
use App\LoanLimit;
use App\LoanSetting;
use App\LoanInstallment;
use App\Loan;
use App\WalletTransaction;
use App\Wallet;
use App\Events\AuditTrailEvent;
use Log;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoansController extends Controller
{
    use FinancialProducts;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user  = Auth::user();
        $farmer_id = $user->farmer->id;
        $cooperative_id = $user->cooperative->id;

        $loan_limit = LoanLimit::where('farmer_id', $farmer_id)->first();
        $loans = Loan::where('farmer_id', $farmer_id)->latest()->get();
        $loan_totals = Loan::where('farmer_id', $farmer_id)->sum('amount');
        $loan_balances = Loan::where('farmer_id', $farmer_id)->sum('balance');
        $loan_configs = LoanSetting::where('cooperative_id', $cooperative_id)->get();

        return view('pages.as-farmer.wallet.loans.loans', compact('loan_limit', 'loans', 'loan_configs', 'loan_totals', 'loan_balances'));
    }

    //loans dashboard
    public function dashboard()
    {
        //get most recent transactions
        $date = Carbon::createFromDate(date('Y'), date('m'), date('d'));
        $endOfYear = $date->copy()->endOfYear();
        $startOfYear = $date->copy()->startOfYear();
        $cooperative_id = Auth::user()->cooperative->id;

        $wallet = Wallet::whereHas('farmer', function ($farmer) use ($cooperative_id) {
            $farmer->whereHas('user', function ($user) use ($cooperative_id) {
                $user->where('cooperative_id', $cooperative_id);
            });
        })->first();
        if ($wallet) {
            $trxs = WalletTransaction::where('wallet_id', $wallet->id)->whereBetween('created_at', [$startOfYear, $endOfYear])->count();
        } else {
            $trxs = 0;
        }


        $data = (object)[
            "transactions" => $trxs,
            "wallet" => $wallet
        ];
        return view('pages.as-farmer.wallet.loans.dashboard', compact('data'));
    }

    //loan details
    public function details($loan_id)
    {
        $farmer_id = Auth::user()->farmer->id;
        $loan_limit = LoanLimit::where('farmer_id', $farmer_id)->first();
        $loans = Loan::where('id', $loan_id)->with(['loan_setting', 'loanInstallment', 'loanRepayment'])->latest()->first();
        return view('pages.as-farmer.wallet.loans.details', compact('loans', 'loan_limit'));
    }

    //pay installments vire
    public function payInstallmentView($id)
    {
        $installment = LoanInstallment::where('id', $id)->with(['loan'])->latest()->first();
        return view('pages.cooperative.financial-products.pay-installment', compact('installment'));
    }

    public function payInstallment(Request $request, $loan_id)
    {

        $user_id = Auth::user()->id;
        $loans = Loan::where('id', $loan_id)->with(['loan_setting', 'loanInstallment', 'loanRepayment'])->latest()->first();
        $farmer_id = $loans->farmer_id;
        $amount = $request->amount;
        //get wallet
        $wallet = Wallet::where('farmer_id', $farmer_id)->first();
        $wallet_id = $wallet->id;

        $installment_id = $request->id;

        $transaction = new WalletTransaction();
        $transaction->wallet_id = $wallet_id;
        $transaction->type = 'Loan Repayment';
        $transaction->amount = $amount;
        $transaction->reference = 'LOAN#' . $loan_id;
        $transaction->source = 'wallet';
        $transaction->initiator_id = $user_id;
        $transaction->description = 'Loan repaid from payment';
        $transaction->save();
        //update wallet
        $wallet = Wallet::find($wallet_id);
        $wallet->available_balance -= $amount;
        $wallet->save();
        //

        //update limit
        $loan_limit = LoanLimit::where('farmer_id', $farmer_id)->first();
        $limit = LoanLimit::find($loan_limit->id);
        $limit->limit += $amount;
        $limit->save();
        //update status
        $update = LoanInstallment::find($installment_id);
        if ($amount == $update->amount) {
            $update->status = 1;
        } else {
            $update->amount -= $amount;
        }
        $update->save();
        //loan 
        $loan = Loan::find($loan_id);
        $loan->balance -= $amount;
        $loan->save();
        return back();
    }

    //request loan
    public function requestLoan(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',
            'mode_of_repayment' => 'required',
            'purpose' => 'required',
            'supporting_document' => 'sometimes|mimes:jpeg,jpg,png|max:3072',
            'farm_tools' => 'required'
        ], [
            'supporting_document.max' => 'Document size should be less than 3MB',
            'supporting_document.mimes' => 'Only images of jpeg, jpg and png allowed',

        ]);

        try {
            $auth_user = Auth::user();
            if (!$auth_user->hasRole('farmer') && $request->farmer_id == null) {
                toastr()->error('Request Failed: Please select a farmer');
                return redirect()->back()->withInput();
            }
            $wallet_id = $request->wallet_id ?? $auth_user->farmer->wallet->id;
            $farmer_id = $request->farmer_id ?? $auth_user->farmer->id;
            Log::info("Applying loan for farmerId {$farmer_id}");
            return $this->applyLoan($request, $farmer_id, $wallet_id, $auth_user);
        } catch (\Throwable $th) {
            Log::info($th);
            DB::rollback();
            toastr()->error('Failed to request loan: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function loan_installments($loan_id)
    {
        return $this->get_loan_installments($loan_id, 'as-farmer.wallet.loans.installments');
    }

    public function repay_loan($InstallmentId, Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            Log::info("Farmer repaying loan");
            if ($request->source) {
                $loanInstallment = LoanInstallment::findOrFail($InstallmentId);
                $date = Carbon::parse($loanInstallment->date)->format('Y-m-d');
                $loan = $loanInstallment->loan;
                $hasPendingInstallment = LoanInstallment::select('id')
                        ->where('loan_id', $loan->id)
                        ->whereDate('date', '<', $date)
                        ->whereIn('status', [LoanInstallment::STATUS_PARTIALLY_PAID, LoanInstallment::STATUS_PENDING])
                        ->count() > 0;

                if ($hasPendingInstallment) {
                    toastr()->error('Please repay previous installments first');
                    return redirect()->back();
                }

                if ($request->source == LoanInstallment::WALLET_REPAYMENT_OPTION) {
                    return $this->repayLoan($loanInstallment);
                } else {

                    if ($request->amount != $loanInstallment->amount) {
                        toastr()->error('Amount is not same as the installment amount');
                        return redirect()->back();
                    }
                    if (!preg_grep("/^[0-9]{12}$/", $request->phone)) {
                        toastr()->error('Phone number is invalid');
                        return redirect()->back();
                    }
                    toastr()->success('Check your phone to complete the transaction');
                    $this->repay_loan_via_mpesa($loanInstallment, $request->phone);
                    return redirect()->back();
                }
            }

            toastr()->error('Please select payment source');
            return redirect()->back();
        } catch (\Exception $ex) {
            toastr()->error('Oops Operation Fail');
            return redirect()->back();
        }
    }
}
