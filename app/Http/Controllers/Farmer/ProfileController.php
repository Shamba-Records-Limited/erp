<?php

namespace App\Http\Controllers\Farmer;

use App\Collection;
use App\Cow;
use App\Exports\FarmerLoanExport;
use App\Exports\FarmerSavingExport;
use App\Exports\PurchaseExport;
use App\Farmer;
use App\GroupLoan;
use App\Http\Controllers\Controller;
use App\Loan;
use App\Sale;
use App\SaleItem;
use App\SavingAccount;
use App\User;
use App\VetBooking;
use App\Wallet;
use App\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use Maatwebsite\Excel\Facades\Excel;

class ProfileController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    private $loan_status = [Loan::STATUS_APPROVED, Loan::STATUS_PARTIAL_REPAYMENT];
    private $group_loan_statuses = [GroupLoan::STATUS_DISBURSED, GroupLoan::STATUS_PARTIALLY_PAID];
    private $active_saving = SavingAccount::STATUS_ACTIVE;

    public function show($user_id)
    {
        $date = Carbon::createFromDate(date('Y'), date('m'), date('d'));
        $endOfYear = $date->copy()->endOfYear();
        $startOfYear = $date->copy()->startOfYear();

        $farmer = User::find($user_id);
        $products_they_supply = DB::table('farmers_products')->select('product_id')->where('farmer_id', $user_id)->count();
        $total_livestock = Cow::where('farmer_id', $farmer->farmer->id)->count();
        $lastDayofMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();
       // Check if the user has an associated cooperative before querying
if (Auth::user()->cooperative) {
    $bookings = VetBooking::where('farmer_id', $user_id)
        ->where('cooperative_id', Auth::user()->cooperative->id)
        ->whereDate('event_end', '<=', $lastDayofMonth)
        ->whereBetween('created_at', [$startOfYear, $endOfYear])
        ->latest()
        ->limit(3)
        ->get();
} else {
    // Handle the case when the cooperative is null, for example, set $bookings to an empty collection
    $bookings = collect();
}

        $all_collections = Collection::where('farmer_id', $farmer->farmer->id)->whereDate('date_collected', '<=', $lastDayofMonth);
        $collection_quantity = implode(',', $all_collections->pluck('quantity')->toArray());
        $collections = $all_collections->orderBy('date_collected', 'desc')->limit(2)->get();
        $wallet = Wallet::where('farmer_id', $farmer->farmer->id)->first();

        //savings
        $farmer_id = $farmer->farmer->id;
        $total_savings = SavingAccount::where('farmer_id', $farmer_id)->where('status', $this->active_saving)->sum('amount');
        $total_loans = Loan::whereIn('status', $this->loan_status)->where('farmer_id', $farmer_id)->where('balance', '>', 0)->sum('balance');
        $group_loans = GroupLoan::whereIn('status', $this->group_loan_statuses)->where('farmer_id', $farmer_id)->sum('balance');
        $total_loans += $group_loans;

        $purchases = DB::select("
             SELECT SUM( (si.amount*si.quantity) - s.discount) as amount FROM sale_items si
             JOIN sales s ON si.sales_id = s.id WHERE s.farmer_id = '$farmer_id'
             AND s.deleted_at is NULL
        ")[0]->amount;


        return view('pages.farmer.profile', compact('farmer', 'products_they_supply',
            'total_livestock', 'bookings', 'collection_quantity', 'collections', 'wallet', 'total_loans', 'total_savings', 'purchases'));
    }

    public function purchases($farmer_id)
    {
        $purchases = Sale::purchases_query($farmer_id);
        $farmer = Farmer::findOrFail($farmer_id);

        return view('pages.farmer.purchases', compact('purchases', 'farmer'));
    }


    public function savings($farmer_id)
    {

        $farmer = Farmer::findOrFail($farmer_id);
        $savings = DB::select("SELECT sa.id, sa.amount, sa.maturity_date, st.type AS type
                                FROM saving_accounts sa JOIN saving_types st ON sa.saving_type_id = st.id
                                WHERE sa.farmer_id = '$farmer_id' AND sa.status = '$this->active_saving'");

        return view('pages.farmer.savings', compact('farmer', 'savings'));
    }

    public function loans($farmer_id)
    {
        $farmer = Farmer::findOrFail($farmer_id);
        $loan_approved = Loan::STATUS_APPROVED;
        $partial_payment = Loan::STATUS_PARTIAL_REPAYMENT;
        $loans = DB::select("SELECT l.id, l.balance, ls.type, l.due_date FROM loans l
                                    JOIN loan_settings ls ON l.loan_setting_id = ls.id
                                    WHERE l.status in ('$loan_approved', '$partial_payment') AND l.balance > 0 AND  l.farmer_id = '$farmer_id'");

        $group_loans = GroupLoan::whereIn('status', $this->group_loan_statuses)
            ->where('farmer_id', $farmer_id)
            ->get();
        return view('pages.farmer.loans', compact('farmer', 'loans', 'group_loans'));
    }

    public function export_purchases($farmer_id, $type): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $farmer = Farmer::findOrFail($farmer_id);
        $purchases = Sale::purchases_query($farmer_id);
        $file_name = strtolower($farmer->user->first_name . '_' . $farmer->user->other_names) . '_purchases' . '.' . $type;
        return Excel::download(new PurchaseExport($purchases), $file_name);
    }

    public function export_savings($farmer_id, $type): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $farmer = Farmer::find($farmer_id);
        $file_name = strtolower($farmer->user->first_name . '_' . $farmer->user->other_names) . '_savings' . '.' . $type;
        return Excel::download(new FarmerSavingExport($farmer->id), $file_name);
    }

    public function export_loans($farmer_id, $type): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $farmer = Farmer::find($farmer_id);
        $file_name = strtolower($farmer->user->first_name . '_' . $farmer->user->other_names) . '_loans' . '.' . $type;
        return Excel::download(new FarmerLoanExport($farmer->id), $file_name);
    }

    public function print_payment_reciept($id)
    {
        $user = Auth::user();

        $wallet_transaction = WalletTransaction::findOrFail($id);

        $client = new Party([
            'name' => $user->cooperative->name,
            'phone' => $user->cooperative->contact_details,
            'email' => $user->cooperative->email,
            'custom_fields' => [
                'address' => $user->cooperative->address,
                'Generated By' => ucwords(strtolower($user->first_name . ' ' . $user->other_names)),
            ],
        ]);


        $customer = new Buyer([
            'name' => ucwords(strtolower(
                $wallet_transaction->wallet->farmer->user->first_name . ' ' .
                $wallet_transaction->wallet->farmer->user->other_names)),
            'custom_fields' => [
                'email' => $wallet_transaction->wallet->farmer->user->email,
                'address' => $wallet_transaction->wallet->farmer->route->name,
            ],
        ]);

        $date = Carbon::parse($wallet_transaction->created_at)->format('Y-m-d, l');
        $items = [(new InvoiceItem())
            ->title($date)
            ->subTotalPrice($wallet_transaction->amount)
            ->discount(0)];
        $invoice_name = "Payment Receipt";
        $currency = $user->cooperative->currency ?? 'KES';
        $invoice = Invoice::make()
            ->name($invoice_name)
            ->status(__(''))
            ->seller($client)
            ->serialNumberFormat($wallet_transaction->reference)
            ->buyer($customer)
            ->currencySymbol($currency)
            ->currencyThousandsSeparator(',')
            ->addItems($items)
            ->logo(public_path($user->cooperative->logo ?? 'assets/images/favicon.png'))
            ->notes('')
            ->template('farmer_payment_receipt');

        return $invoice->stream();

    }
}
