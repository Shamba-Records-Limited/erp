<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Loan;
use App\Wallet;
use App\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //get most recent transactions
        $date = Carbon::createFromDate(date('Y'), date('m'), date('d'));
        $endOfYear   = $date->copy()->endOfYear();
        $startOfYear = $date->copy()->startOfYear();
        $wallet = Wallet::where('farmer_id', Auth::user()->farmer->id)->first();
        if($wallet)
        {
            $trxs = WalletTransaction::where('wallet_id', $wallet->id)->whereBetween('created_at', [$startOfYear, $endOfYear])->count();
        }else{
            $trxs = 0;
        }


        $data = (object)[
            "transactions" =>$trxs,
            "wallet" => $wallet
        ];
        return view('pages.as-farmer.wallet.dashboard', compact('data'));
    }


    public function transactions()
    {

        $wallet_transactions = Wallet::select('wallets.farmer_id', 'trx.id as transaction_id', 'trx.reference as reference','trx.description as description',
            'trx.phone as phone','trx.initiator_id as initiator_id', 'trx.type as type', 'trx.amount as amount', 'trx.source as source', 'trx.created_at as date')
            ->join('wallet_transactions as trx', 'trx.wallet_id', 'wallets.id')
            ->where('wallets.farmer_id', Auth::user()->farmer->id)
            ->orderBy('trx.created_at', 'desc')->get();
        return view('pages.as-farmer.wallet.transactions', compact('wallet_transactions'));
    }

    public function bar_chart_data()
    {

        $wallet = Wallet::where('farmer_id', Auth::user()->farmer->id)->first();
        $endOfYear   = Carbon::now()->endOfYear()->format('Y-m-d');
        $startOfYear = Carbon::now()->startOfYear()->format('Y-m-d');
        if($wallet)
        {

            $barchartAndDonut = DB::select("SELECT count(*) as transactions , SUM(amount)  as income,
                        DATE_FORMAT(created_at, '%b') as month FROM wallet_transactions WHERE wallet_id = '$wallet->id'
                        AND type <> 'savings' AND created_at BETWEEN '$startOfYear' AND '$endOfYear' GROUP BY month  ORDER BY  month");

        }else{
            $barchartAndDonut = null;
        }
        $farmer = Auth::user()->farmer->id;
        $wallet = Wallet::where('farmer_id', $farmer)->first();
        $loan = Loan::where('farmer_id', $farmer)->first();

        $loansVsIncomeData = ["data" => [$loan ? $loan->amount : 0,$wallet ? $wallet->available_balance : 0]];

        $data = ["barchartAndDonut" => $barchartAndDonut, "loansVsIncome"=>$loansVsIncomeData];
        return json_encode($data);

    }


}
