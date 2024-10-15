<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exceptions\FailedToCompleteFarmerPaymentException;
use App\Exports\FarmerCollectionExport;
use App\Exports\FarmerPaymentExport;
use App\Farmer;
use App\Http\Traits\WalletTrait;
use App\IncomeAndExpense;
use App\Loan;
use App\Wallet;
use App\WalletTransaction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Throwable;

class WalletController extends Controller
{

    use WalletTrait;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $cooperative = Auth::user()->cooperative->id;
        $user_ids = get_id_of_user_with_role('farmer', $cooperative);
        $farmer_ids = get_farmers($user_ids, $cooperative)->pluck('id')->toArray();
        $date = Carbon::createFromDate(date('Y'), date('m'), date('d'));
        $endOfYear = $date->copy()->endOfYear();
        $startOfYear = $date->copy()->startOfYear();
        $wallet = Wallet::whereIn('farmer_id', $farmer_ids)->get();
        $wallet_ids = Wallet::whereIn('farmer_id', $farmer_ids)->pluck('id');
        $trxs = WalletTransaction::whereIn('wallet_id', $wallet_ids)->whereBetween('created_at', [$startOfYear, $endOfYear])->count();
        $expense_income = IncomeAndExpense::select('income', 'expense')->where('cooperative_id', $cooperative);
        $income = $expense_income->sum('income');
        $expense = $expense_income->sum('expense');

        $loans = DB::select("
        SELECT SUM(l.balance) as balance
        FROM loans l LEFT JOIN farmers f ON f.id = l.farmer_id LEFT JOIN users u ON f.user_id = u.id 
        WHERE (l.amount > 0 AND l.status = '1') AND u.cooperative_id = '$cooperative'")[0];


        if ($loans->balance == null) {
            $loans = 0;
        } else {
            $loans = $loans->balance;
        }
        $profit_margin = $income - $expense;

        $data = (object)[
            "transactions" => $trxs,
            "wallet" => $wallet,
            "loans" => $loans,
            "profit_margin" => $profit_margin
        ];

        return view('pages.cooperative.wallet.dashboard', compact('data'));
    }

    public function payment_configurations()
    {
        dd('Loading...');
    }

    public function initiate_payments()
    {
        dd('loading....');
    }


    public function pay_farmer(Request $request)
    {

        $this->validate($request, [
            'farmer_id' => 'required|string',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        try {
            DB::beginTransaction();

            $this->pay_farmer_util($request, 'Single Payment Transaction', 'SPAY');
            DB::commit();
            toastr()->success('Payment Completed');
            return redirect()->back();

        } catch (Exception|FailedToCompleteFarmerPaymentException|Throwable $ex) {
            $user = Auth::user();
            DB::rollBack();
            Log::error($ex);
            $audit_trail_data = ['user_id' => $user->id,
                'activity' => 'Failed to complete bulk payments ['.$ex->getMessage().']',
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->error('Oops! Failed to initiate transaction');
            return redirect()->back()->withInput($request->all());
        }

    }




    public function get_loaned_farmers()
    {
        $cooperative = Auth::user()->cooperative->id;
        $loaned_farmers = DB::select(
            "SELECT l.id, l.amount, l.balance, u.first_name, u.other_names, l.due_date, f.phone_no, s.type
                    FROM loans l LEFT JOIN farmers f ON f.id = l.farmer_id LEFT JOIN users u ON f.user_id = u.id 
                    LEFT JOIN loan_settings s ON s.id = l.loan_setting_id
                    WHERE (l.amount > 0 AND l.status = '1') AND u.cooperative_id = '$cooperative' "
        );

        return view('pages.cooperative.wallet.loaned_farmers', compact('loaned_farmers'));
    }

    public function get_farmer_pending_payments()
    {
        $cooperative = Auth::user()->cooperative->id;
        $pending_payments = DB::select("
            SELECT u.id as id, w.current_balance, u.first_name, u.other_names, f.phone_no FROM wallets w 
            LEFT JOIN farmers f ON f.id = w.farmer_id LEFT JOIN  users u ON f.user_id  = u.id
            WHERE u.cooperative_id = '$cooperative';
        ");

        return view('pages.cooperative.wallet.pending_payments', compact('pending_payments'));
    }

    public function show_payment_histories($farmer_id)
    {

        $from = request()->from;
        $to = request()->to;

        $query = WalletTransaction::select('wallet_transactions.*')
            ->join('wallets', 'wallets.id', '=', 'wallet_transactions.wallet_id');
        if ($from) {
            Cache::put('farmer_payments_from', $from, now()->addMinutes(5));
            $from = Carbon::parse($from)->format('Y-m-d');
            $query = $query->whereDate('wallet_transactions.updated_at', '>=', $from);
        }

        if ($to) {
            Cache::put('farmer_payments_to', $to, now()->addMinutes(5));
            $to = Carbon::parse($to)->format('Y-m-d');
            $query = $query->whereDate('wallet_transactions.updated_at', '<=', $to);
        }

        $payments = $query->where('wallets.farmer_id', $farmer_id)
            ->whereIn('wallet_transactions.type', ['payment'])->get();

        return view('pages.farmer.payments', compact('payments', 'farmer_id'));
    }

    public function download_payment_histories($type, $farmer_id)
    {
        $farmer = Farmer::findOrFail($farmer_id);
        $payments = WalletTransaction::get_trx($farmer->id);
        $file_name = 'payment_history_'
            . $farmer->user->first_name . '_'
            . $farmer->user->other_names . '_'
            . date('d_m_Y');
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new FarmerPaymentExport($payments), $file_name);
        }else{

            $names = ucwords(strtolower($farmer->user->first_name.' '.$farmer->user->other_names));
            $data = [
                'title' => "{$names} Payment History",
                'pdf_view' => 'farmer_payment_history',
                'records' => $payments,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }

}
