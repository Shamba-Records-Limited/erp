<?php

namespace App\Http\Controllers;

use App\AccountingConfiguration;
use App\AccountingLedger;
use App\AccountingRule;
use App\AccountingTransaction;
use App\CooperativeFinancialPeriod;
use App\Events\AuditTrailEvent;
use App\Exports\AccountingChartsOfAccountExport;
use App\Exports\AccountingReportsExport;
use App\Exports\AccountingTransactionsExport;
use App\Farmer;
use App\Http\Traits\Accounting;
use App\ParentLedger;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AccountingController extends Controller
{
    use Accounting;

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $fy = CooperativeFinancialPeriod::where('cooperative_id', Auth::user()->cooperative_id)
            ->orderBy('end_period', 'desc')
            ->get();
        return view('pages.cooperative.accounting.index', compact('fy'));
    }

    public function report_type($fy_id)
    {
        $coopid = Auth::user()->cooperative_id;
        $fy = CooperativeFinancialPeriod::find($fy_id);

        if ($fy) {

            $ledgers = AccountingLedger::whereNull('deleted_at')
                ->where(function ($query) use($coopid) {
                    return $query->where('cooperative_id', $coopid)->orWhereNull('cooperative_id');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $farmers = Farmer::select(['farmers.id', DB::raw('CONCAT(users.first_name, " ", users.other_names) AS name') ])
                ->join('users', 'users.id', '=', 'farmers.user_id')
                ->where('users.cooperative_id', $coopid)
                ->get();

            return view('pages.cooperative.accounting.accounting_report', compact('fy', 'ledgers', 'farmers'));

        } else {
            toastr()->warning('Financial Period Not Found');
            return redirect()->back();
        }
    }


    public function details()
    {
        $ledgers = AccountingLedger::whereNull('deleted_at')
            ->where(
                function ($query) {
                    $query->where('cooperative_id', Auth::user()->cooperative_id)
                        ->orWhereNull('cooperative_id');
                }
            )->orderBy('parent_ledger_id')->orderBy('ledger_code')->get();

        $parent_ledgers = ParentLedger::all();

        return view('pages.cooperative.accounting.acc_details', compact('ledgers', 'parent_ledgers'));
    }

    public function add_ledger_account(Request $request)
    {
        $this->validate($request, [
            'account_type' => 'required',
            'name' => 'required',
            'parent_ledger' => 'required',
            'classification' => 'nullable',
            'ledger_code' => 'required|integer|min:10000|max:59999'
        ]);

        $cooperative_id = Auth::user()->cooperative_id;

        $check_if_ledger_has_been_created = AccountingLedger::where('name', $request->name)
            ->whereNull('deleted_at')
            ->where(
                function ($query) {
                    $query->where('cooperative_id', Auth::user()->cooperative_id)
                        ->orWhereNull('cooperative_id');
                }
            )->count() > 0;
        if ($check_if_ledger_has_been_created) {
            return redirect()->back()->withInput()->withErrors(['name' => 'You already created a ledger with this name']);
        }
        //check code
        $check_if_ledger_code_has_been_created = AccountingLedger::where('cooperative_id', $cooperative_id)
            ->where('ledger_code', $request->ledger_code)->count() > 0;

        if ($check_if_ledger_code_has_been_created) {
            return redirect()->back()->withInput()
                ->withErrors(['ledger_code' => 'You already created a ledger with code: ' . $request->ledger_code]);
        }
        try {
            DB::beginTransaction();
            $ledger_account = new AccountingLedger();
            $ledger_account->name = $request->name;
            $ledger_account->type = $request->account_type;
            $ledger_account->parent_ledger_id = $request->parent_ledger;
            $ledger_account->ledger_code = $request->ledger_code;;
            $ledger_account->description = $request->description;;
            $ledger_account->cooperative_id = $cooperative_id;
            $ledger_account->classification = $request->classification;
            $ledger_account->save();
            DB::commit();
            $audit_trail_data = [
                'user_id' => Auth::user()->id, 'activity' => 'Created a ledger account ' . $request->name,
                'cooperative_id' => Auth::user()->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Ledger account created successfully');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            toastr()->error('Oops! Failed to create a ledger account');
            return redirect()->back();
        }
    }


    public function get_the_next_ledger_code($parent_ledger_id)
    {

        $ledger = AccountingLedger::where(function ($query) {
            $query->where('cooperative_id', Auth::user()->cooperative_id)
                ->orWhereNull('cooperative_id');
        })->where('parent_ledger_id', $parent_ledger_id)
            ->orderBy('ledger_code', 'desc')->first();
        if ($ledger) {
            return $ledger->ledger_code + 1;
        }
        return ParentLedger::find($parent_ledger_id)->parent_ledger_code + 1;
    }

    public function close_financial_period($financial_period_id)
    {
        //calculate the balances for the last financial period
        return $this->closeAndCreateNewPeriod($financial_period_id);
    }


    public function delete_ledger_account($ledger_id): \Illuminate\Http\RedirectResponse
    {
        $ledger_account = AccountingLedger::find($ledger_id);
        try {
            DB::beginTransaction();
            if ($ledger_account) {
                $ledger_account->deleted_at = Carbon::now();
                $ledger_account->save();
            }
            DB::commit();
            $audit_trail_data = [
                'user_id' => Auth::user()->id, 'activity' => 'Delete Ledger ' . $ledger_account->name,
                'cooperative_id' => Auth::user()->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Ledger Account Deleted');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            toastr()->error('Oops! Request could not be processed at the moment');
            return redirect()->back();
        }
    }


    public function edit_ledger_account(Request $request, $ledger_id)
    {
        $this->validate($request, [
            "name_edit" => 'required'
        ]);

        $check_if_ledger_has_been_created = AccountingLedger::where('name', $request->name_edit)
            ->where(
                function ($query) {
                    $query->where('cooperative_id', Auth::user()->cooperative_id)
                        ->orWhereNull('cooperative_id');
                }
            )->count() > 1;
        if ($check_if_ledger_has_been_created) {
            return redirect()->back()->withInput()->withErrors(['name' => 'The name is being used by another ledger']);
        }

        try {
            DB::beginTransaction();
            $ledger_account = AccountingLedger::find($ledger_id);
            $ledger_account->name = $request->name_edit;
            $ledger_account->save();
            DB::commit();
            $audit_trail_data = [
                'user_id' => Auth::user()->id, 'activity' => 'Edited a ledger account ' . $ledger_account->name,
                'cooperative_id' => Auth::user()->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Ledger account updated successfully');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            toastr()->error('Oops! Failed to updated a ledger account');
            return redirect()->back();
        }
    }


    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function create_transaction(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "credit_ledger_account" => 'required_if:approach,2',
            "debit_ledger_account" => 'required_if:approach,2',
            "acc_rule" => 'required_if:approach,1',
            "amount" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "description" => 'required',
            "ref_no" => 'sometimes|nullable|string',
            "approach" => 'required|string'
        ]);

        $default_ref_no = strtoupper(date('M') . date('dyhis') . date_default_timezone_get() . date('D'));
        $ref_no = $request->ref_no !== null ? $request->ref_no : $default_ref_no;

        if ($request->approach == "1") {
            $rule = AccountingRule::find($request->acc_rule);
            $credit_ledger = AccountingLedger::find($rule->credit_ledger->id);
            $debit_ledger = AccountingLedger::find($rule->debit_ledger->id);
        } else {
            if ($request->credit_ledger_account == $request->debit_ledger_account) {
                return redirect()->back()->withInput()->withErrors([
                    'credit_ledger_account' => 'Credit and Debit Ledger account can not be the same',
                    'debit_ledger_account' => 'Credit and Debit Ledger account can not be the same'
                ]);
            }
            $credit_ledger = AccountingLedger::find($request->credit_ledger_account);
            $debit_ledger = AccountingLedger::find($request->debit_ledger_account);
        }

        $this->save_transaction(new AccountingTransaction(), $request->amount, $credit_ledger, true, $ref_no, $request->description);
        $this->save_transaction(new AccountingTransaction(), $request->amount, $debit_ledger, false, $ref_no, $request->description);

        return redirect()->back()->withInput();
    }

    private function save_transaction($accounting_transaction, $amount, $accounting_ledger, $is_credit, $ref_no, $description)
    {
        try {

            if (strtolower($accounting_ledger->parent_ledger->name) == 'assets') {
                if ($is_credit) {
                    $accounting_transaction->credit = $amount;
                } else {
                    $accounting_transaction->debit = $amount;
                }
            } else {
                if (!$is_credit) {
                    $accounting_transaction->debit = $amount;
                } else {
                    $accounting_transaction->credit = $amount;
                }
            }
            $accounting_transaction->particulars = $description;
            $accounting_transaction->accounting_ledger_id = $accounting_ledger->id;
            $accounting_transaction->cooperative_id = Auth::user()->cooperative_id;
            $accounting_transaction->date = Carbon::now();
            $accounting_transaction->ref_no = $ref_no;
            $accounting_transaction->save();

            $ledger = $accounting_ledger->name;

            $data = [
                "date" => date('Y-m-d'),
                "income" => $is_credit ? $amount : null,
                "expense" => !$is_credit ? $amount : null,
                "particulars" => $ledger . ' ' . $description,
                "user_id" => Auth::user()->id,
                "cooperative_id" => Auth::user()->cooperative_id,
            ];
            has_recorded_income_expense($data);
            DB::commit();
            $audit_trail_data = [
                'user_id' => Auth::user()->id, 'activity' => 'Created a new transactions',
                'cooperative_id' => Auth::user()->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Transaction completed Successfully!');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            toastr()->error('Oops! Failed to create the transaction');
        }
    }

    public function get_accounting_transaction()
    {
        $user = Auth::user();
        $rules = AccountingRule::whereNull('deleted_at')
            ->where('cooperative_id', $user->cooperative_id)->get();

        $transactions = AccountingTransaction::accountingTransactions($user->cooperative_id, 100);
        $ledgers = AccountingLedger::whereNull('accounting_ledgers.deleted_at')
            ->where(
                function ($query) use ($user) {
                    $query->where('accounting_ledgers.cooperative_id', $user->cooperative_id)
                        ->orWhereNull('accounting_ledgers.cooperative_id');
                }
            )->orderBy('accounting_ledgers.created_at', 'desc')->get();
        return view('pages.cooperative.accounting.transactions', compact('transactions', 'ledgers', 'rules'));
    }

    public function export_accounting_transactions_jornal_entries($type)
    {
        $cooperative = Auth::user()->cooperative;
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('accounting_transactions_journal_entries_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new AccountingTransactionsExport($cooperative->id), $file_name);
        } else {
            $data = [
                'title' => 'Accounting Transactions Journal Entries',
                'pdf_view' => 'accountingtranx_entries',
                'records' => AccountingTransaction::accountingTransactions($cooperative->id),
                'filename' => strtolower('accounting_transactions_journal_entries_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_accounting_details($type)
    {
        $cooperative = Auth::user()->cooperative->id;

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('accounting_details_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new AccountingChartsOfAccountExport($cooperative), $file_name);
        } else {
            $data = [
                'title' => 'Accounting Details',
                'pdf_view' => 'accounting_details',
                'records' => AccountingLedger::whereNull('deleted_at')
                    ->where(
                        function ($query) use ($cooperative) {
                            $query->where('cooperative_id', $cooperative)
                                ->orWhereNull('cooperative_id');
                        }
                    )->orderBy('parent_ledger_id')->orderBy('ledger_code')->get(),
                'filename' => strtolower('accounting_details_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function export_accounting_reports($type)
    {
        $cooperative = Auth::user()->cooperative->id;

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('accounting_reports_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new AccountingReportsExport($cooperative), $file_name);
        } else {
            $data = [
                'title' => 'Accounting Reports',
                'pdf_view' => 'accounting_reports',
                'records' => CooperativeFinancialPeriod::where('cooperative_id', $cooperative)
                    ->orderBy('end_period', 'desc')
                    ->get(),
                'filename' => strtolower('accounting_reports_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }
}
