<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\DefaultedLoanExport;
use App\Exports\InterestExport;
use App\Exports\LoanedFarmersExport;
use App\Exports\LoanInstallmentExport;
use App\Exports\LoanRepaymentExport;
use App\Exports\SavingFarmersExport;
use App\Exports\SavingInstallmentExport;
use App\GroupLoan;
use App\GroupLoanConfig;
use App\GroupLoanRepayment;
use App\GroupLoanSummary;
use App\GroupLoanType;
use App\Http\Traits\FinancialProducts;
use App\LimitRateConfig;
use App\Loan;
use App\LoanApplicationDetail;
use App\LoanInstallment;
use App\SavingAccount;
use App\SavingType;
use App\Farmer;
use App\SavingInstallment;
use App\User;
use App\Wallet;
use App\WalletTransaction;
use Carbon\Carbon;
use App\LoanSetting;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Mixins\DownloadCollection;
use Mockery\Exception;
use PDF;

class FinancialProductController extends Controller
{
    use FinancialProducts;


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function loans(Request $request)
    {
        //farmer, status
        $cooperative = Auth::user()->cooperative->id;

        $isLimitConfigurationSet = LimitRateConfig::where('cooperative_id', $cooperative)
            ->first();
        if ($isLimitConfigurationSet == null) {
            toastr()->error('Please do the configuration first');
            return redirect()->route('cooperative.limit-rate.config');
        }

        //get farmers with their loan limits
        $farmers = Farmer::with(['wallet'])->whereHas('user', function ($query) use ($cooperative) {
            $query->where('cooperative_id', $cooperative);
        })->get();
        $loan_configs = LoanSetting::where('cooperative_id', $cooperative)->get();


        $query = "select l.id, l.status as status, u.first_name, u.other_names, lt.type as type, l.amount as amount, l.balance
                        as balance, l.due_date
                        from loans l join loan_settings lt on l.loan_setting_id = lt.id
                        join farmers f on l.farmer_id = f.id join users u on f.user_id = u.id where u.cooperative_id = '$cooperative' ";

        if ($request->farmer) {
            $query .= " and f.id = '$request->farmer' ";
        }

        if ($request->status) {
            $query .= " and l.status = '$request->status' ";
        }

        $query .= "order by l.id desc";

        $loans = DB::select($query);
        return view('pages.cooperative.financial-products.loans', compact('loans', 'loan_configs', 'farmers'));
    }

    public function farmer_limit($farmer_id, $has_farm_tools)
    {
        $farm_tools = $has_farm_tools > 0;
        return json_encode($this->calculateLimit($farmer_id, Auth::user()->cooperative_id, $farm_tools));
    }

    public function commercial_loan_details($loanId)
    {
        $loanDetails = LoanApplicationDetail::where('loan_id', $loanId)->first();
        if ($loanDetails == null) {
            toastr()->error('Loan Details Does not exist');
            return redirect()->back();
        }
        return view('pages.cooperative.financial-products.commercial_loan_details', compact('loanDetails'));
    }

    public function update_commercial_loan_status($loanId, $newStatus)
    {
        $user = Auth::user();
        $loan = Loan::findOrFail($loanId);
        $loan->status = $newStatus;
        $loan->save();
        toastr()->success('Loan updated Successfully');
        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Moved loan ' . $loanId . ' to status ' . $newStatus, 'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Saving account updated');
        return redirect()->back();
    }

    public function savings()
    {
        return $this->get_savings('cooperative.financial-products.savings', Auth::user());
    }

    public function loan_installments($loan_id)
    {
        return $this->get_loan_installments($loan_id, 'cooperative.financial-products.installments');
    }


    public function admin_create_saving_account(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "amount" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "type" => 'required',
            'farmer' => 'required'
        ]);

        $farmer = Farmer::findOrFail($request->farmer);
        $auth_user = Auth::user();
        Log::info("Creating/adding saving account by admin");
        return $this->saving_account($request, $auth_user, $farmer);
    }


    public function admin_initiate_withdraw_from_saving_account(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "saving_type" => 'required',
            "farmer" => 'required'
        ]);

        $auth_user = Auth::user();
        $farmer = Farmer::findOrFail($request->farmer);
        $farmer_id = $farmer->id;

        try {
            DB::beginTransaction();

            $matured_savings = SavingAccount::where('farmer_id', $farmer_id)
                ->whereDate('maturity_date', '<=', date('Y-m-d'))
                ->where('saving_type_id', $request->saving_type)
                ->first();

            $amount = $matured_savings->amount + ($matured_savings->amount * ($matured_savings->interest / 100));

            if (update_wallet($request, true, $amount, $farmer_id)) {
                $matured_savings->status = SavingAccount::STATUS_WITHDRAWN;
                $matured_savings->save();
                DB::commit();
                $audit_trail_data = ['user_id' => $auth_user->id, 'activity' => 'Saving account updated with '
                    . $amount . ' amount', 'cooperative_id' => $auth_user->cooperative->id];
                event(new AuditTrailEvent($audit_trail_data));
                toastr()->success('Saving account updated');
                return redirect()->back();
            } else {
                DB::rollBack();
                Log::error("================== Failed to create a saving transaction ==================");
                toastr()->error('OOps! Operation failed');
                return redirect()->back()->withInput();
            }
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error(json_encode($ex->getTrace()));
            $audit_trail_data = ['user_id' => $auth_user->id, 'activity' => 'Failed to withdraw from saving account  ' .
                $request->amount . ' amount', 'cooperative_id' => $auth_user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->error('OOps! Operation failed');
            return redirect()->back()->withInput();
        } catch (\Throwable $e) {

            DB::rollBack();
            Log::error($e);
            toastr()->error('OOps! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function saving_installments($saving_account_id)
    {
        return $this->get_saving_installments('cooperative.financial-products.saving-installments', $saving_account_id);
    }

    public function farmer_matured_saving_type($farmerId): \Illuminate\Support\Collection
    {
        return SavingAccount::select('saving_types.id as id', 'saving_types.type as type')
            ->join('saving_types', 'saving_types.id', '=', 'saving_accounts.saving_type_id')
            ->where('saving_accounts.farmer_id', $farmerId)
            ->whereDate('saving_accounts.maturity_date', '<=', date('Y-m-d'))
            ->get();
    }

    public function dashboard(Request $request)
    {
        if ($request->dates) {
            $dates = split_dates($request->dates);
            $from = $dates['from'];
            $to = $dates['to'];

        } else {
            $from = Carbon::now()->startOfYear()->format('Y-m-d');
            $to = Carbon::now()->endOfYear()->format('Y-m-d');
        }

        $approvedLoanStatus = Loan::STATUS_APPROVED;
        $user = Auth::user();

        $amountInLoans = DB::select(
            "SELECT SUM(l.balance) AS amount FROM loans l JOIN loan_settings ls on l.loan_setting_id = ls.id
                              WHERE ls.cooperative_id = '$user->cooperative_id' AND l.due_date >= NOW()
                                AND l.status = '$approvedLoanStatus' AND (l.created_at BETWEEN '$from'AND '$to');"
        )[0]->amount;

        $defaultedLoans = DB::select(
            "SELECT COUNT(*) AS defaults FROM  loans l JOIN loan_settings ls on l.loan_setting_id = ls.id
                            WHERE ls.cooperative_id = '$user->cooperative_id' AND l.due_date <= NOW() AND
                                  l.status = '$approvedLoanStatus' AND l.balance > 0 AND (l.created_at BETWEEN '$from'AND '$to');"
        )[0]->defaults;

        $active = SavingAccount::STATUS_ACTIVE;
        $amountInSavings = DB::select("
            SELECT SUM(sa.amount) AS amount FROM saving_accounts sa JOIN saving_types st on sa.saving_type_id = st.id
                                            WHERE st.cooperative_id = '$user->cooperative_id'
                                          AND sa.status = '$active' AND (sa.created_at BETWEEN '$from'AND '$to'); ;
        ")[0]->amount;

        $repayment = DB::select(
            "SELECT coalesce(SUM(li.repaid_amount),0) as repayment FROM loan_installments li WHERE li.loan_id IN (
                    SELECT l.id FROM loans l JOIN loan_settings ls ON
                        ls.id = l.loan_setting_id WHERE ls.cooperative_id = '$user->cooperative_id'
                ) AND li.repaid_amount > 0 AND (li.created_at BETWEEN '$from'AND '$to');"
        )[0]->repayment;

        $data = (object)[
            "defaulted_loans" => $defaultedLoans,
            "amount_in_repayments" => $repayment ?? 0,
            "amount_in_loans" => $amountInLoans ?? 0,
            "amount_in_savings" => $amountInSavings ?? 0,
        ];
        return view('pages.cooperative.minidashboards.financial-products', compact('data'));
    }


    public function financial_dashboard_starts(Request $request): array
    {
        $user = Auth::user();
        $loan_types = DB::select("
        SELECT coalesce(SUM(l.amount),0) AS total_amount, ls.type
        FROM loan_settings ls LEFT JOIN loans l ON l.loan_setting_id = ls.id WHERE ls.cooperative_id = '$user->cooperative_id' GROUP BY ls.id
        ORDER BY ls.id;
        ");

        $repayments = DB::select(
            "SELECT coalesce(SUM(li.repaid_amount),0) as repayment, li.loan_id FROM loan_installments li WHERE li.loan_id IN (
                    SELECT l.id FROM loans l JOIN loan_settings ls ON
                        ls.id = l.loan_setting_id WHERE ls.cooperative_id = '$user->cooperative_id'
                ) AND li.repaid_amount > 0 GROUP BY li.loan_id"
        );

        $loans = DB::select("
        SELECT l.balance, l.status, l.due_date FROM loans l LEFT JOIN loan_settings ls  ON ls.id = l.loan_setting_id WHERE
        ls.cooperative_id = '$user->cooperative_id' ORDER BY due_date
        ");

        $savings = DB::select("
            SELECT  coalesce(SUM(s.amount), 0) as amount, st.type FROM saving_accounts s
            JOIN saving_types st ON s.saving_type_id = st.id WHERE st.cooperative_id = '$user->cooperative_id'
            GROUP BY st.id;
        ");

        $repaymentsType = [];
        foreach ($repayments as $r) {
            $loan = Loan::findOrFail($r->loan_id);
            if (array_key_exists($loan->loan_setting->type, $repaymentsType)) {
                $repaymentsType[$loan->loan_setting->type] += $r->repayment;
            } else {
                $repaymentsType[$loan->loan_setting->type] = $r->repayment;
            }
        }

        $bought_off = 0;
        $rejected = 0;
        $approved = 0;
        $repaid = 0;
        $partial_payment = 0;
        $due = 0;

        $today = Carbon::now();
        foreach ($loans as $l) {
            switch (true) {
                case Carbon::parse($l->due_date)->lt($today):
                    $due += $l->balance;
                    break;
                case $l->status == Loan::STATUS_REJECTED:
                    $rejected += $l->balance;
                    break;
                case $l->status == Loan::STATUS_APPROVED:
                    $approved += $l->balance;
                    break;
                case $l->status == Loan::STATUS_REPAID:
                    $repaid += $l->balance;
                    break;
                case $l->status == Loan::STATUS_PARTIAL_REPAYMENT:
                    $partial_payment += $l->balance;
                    break;
                default:
                    $bought_off += $l->balance;
                    break;
            }
        }


        $loans_by_type = [];
        foreach ($loan_types as $lt) {
            if (array_key_exists($lt->type, $repaymentsType)) {
                $loans_by_type[] = ["type" => $lt->type, "repayment" => $repaymentsType[$lt->type], 'loan' => $lt->total_amount];
            } else {
                $loans_by_type[] = ["type" => $lt->type, "repayment" => 0, 'loan' => $lt->total_amount];
            }
        }

        $loan_grouped_by_status = [
            "Repaid" => $repaid,
            "Rejected" => $rejected,
            "Due" => $due,
            "Approved" => $approved,
            "Partial_Payment" => $partial_payment,
            "Bought_off" => $bought_off
        ];

        return [
            "loans_by_type" => $loans_by_type,
            "loan_grouped_by_status" => $loan_grouped_by_status,
            'savings' => $savings
        ];
    }

    public function export_loaned_farmers($type)
    {
        $cooperative = Auth::user()->cooperative;
        $query = "select l.id, l.status as status, u.first_name, u.other_names, lt.type as type, l.amount as amount, l.balance
                        as balance, l.due_date
                        from loans l join loan_settings lt on l.loan_setting_id = lt.id
                        join farmers f on l.farmer_id = f.id join users u on f.user_id = u.id
                        where u.cooperative_id = '$cooperative->id'";
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'loaned_farmers' . '.' . $type;
            return Excel::download(new LoanedFarmersExport($query), $file_name);
        } else {
            $loans = DB::select($query);
            $data = [
                'title' => 'Loaned Applications',
                'pdf_view' => 'loaned-farmers',
                'records' => $loans,
                'filename' => strtolower($cooperative->name . '_loaned_farmers'),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }


    public function export_farmer_savings($type)
    {
        $cooperative = Auth::user()->cooperative;
        $query = "SELECT sa.id AS id, sa.status AS status, u.first_name, u.other_names, sa.amount, sa.date_started, sa.maturity_date,
                            st.type AS saving_type, sa.interest AS interest_rate
                            FROM saving_accounts sa JOIN saving_types st ON sa.saving_type_id = st.id 
                            JOIN farmers f ON sa.farmer_id = f.id
                            JOIN users u ON f.user_id = u.id WHERE amount > 0 AND u.cooperative_id = '$cooperative->id'
                            ORDER BY sa.status ASC LIMIT 100";
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'farmer_savings' . '.' . $type;
            return Excel::download(new SavingFarmersExport($query), $file_name);
        } else {
            $savings = DB::select($query);
            $data = [
                'title' => 'Farmer Savings',
                'pdf_view' => 'farmer-saving',
                'records' => $savings,
                'filename' => strtolower($cooperative->name . '_farmer_savings'),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }


    public function export_saving_installments($saving_id, $type)
    {
        $savingAccount = SavingAccount::findOrFail($saving_id);
        $saving_type = $savingAccount->saving_type->type;
        $user = $savingAccount->farmer->user;
        $farmer = ucwords(strtolower($user->first_name . ' ' . $user->other_names));
        $query = " SELECT wt.amount, wt.created_at AS date, wt.reference  FROM saving_accounts sa 
            INNER JOIN saving_installments si ON sa.id = si.saving_id
            INNER JOIN wallet_transactions wt ON si.wallet_transaction_id = wt.id
            WHERE sa.id = '$savingAccount->id'";
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower($farmer) . '_saving_installments' . '.' . $type;
            return Excel::download(new SavingInstallmentExport($query, $farmer, $saving_id), $file_name);
        } else {

            $savings = DB::select($query);
            $data = [
                'title' => $farmer . ' Saving Installment. Saving ID: ' . $saving_id . ' Type: ' . $saving_type,
                'pdf_view' => 'farmer-saving-installment',
                'records' => $savings,
                'filename' => strtolower($farmer . '_saving_installment'),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    public function export_loan_installments($loan_id, $type)
    {
        $loan = Loan::findOrFail($loan_id);
        $farmer = ucwords(strtolower($loan->farmer->user->first_name . ' ' . $loan->farmer->user->other_names));
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower($farmer) . '_loan_' . $loan_id . '_installments' . '.' . $type;
            return Excel::download(new LoanInstallmentExport($farmer, $loan_id), $file_name);
        } else {


            $data = [
                'title' => $farmer . ' Loan Installment. Loan Id: ' . $loan_id . ' Type: ' . $loan->loan_setting->type,
                'pdf_view' => 'farmer-loan-installment',
                'records' => LoanInstallment::where('loan_id', $loan_id)->get(),
                'filename' => strtolower($farmer . '_loan_' . $loan_id . '_installment'),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    public function loan_defaulters()
    {
        $defaulters = DB::select(loan_defaulters_query());
        return view('pages.cooperative.financial-products.loan-defaulters', compact('defaulters'));
    }

    public function export_loan_defaulters($type)
    {
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'loan_defaulters.' . $type;
            return Excel::download(new DefaultedLoanExport(loan_defaulters_query()), $file_name);
        } else {

            $defaulters = DB::select(loan_defaulters_query());
            $data = [
                'title' => 'Loan Defaulters',
                'pdf_view' => 'loan-defaulters',
                'records' => $defaulters,
                'filename' => 'loan_defaulters',
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function loan_repayments()
    {
        $loan_repayments = DB::select(loan_repayments_query());
        return view('pages.cooperative.financial-products.loan-repayments', compact('loan_repayments'));
    }

    public function export_loan_repayments($type)
    {
        if ($type != env('PDF_FORMAT')) {
            $file_name = 'loan_default.' . $type;
            return Excel::download(new LoanRepaymentExport(loan_repayments_query()), $file_name);
        } else {

            $defaulters = DB::select(loan_repayments_query());
            $data = [
                'title' => 'Loan Repayments',
                'pdf_view' => 'loan-repayments',
                'records' => $defaulters,
                'filename' => 'loan_repayments',
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function repay_loan($InstallmentId, Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            Log::info("Farmer repaying loan via admin portal");
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
                    if (!preg_match("/^[0-9]{12}$/", $request->phone)) {
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
            Log::error("-----------------------------------------------------\n{$ex->getMessage()}");
            Log::error($ex);
            toastr()->error('Oops Operation Fail');
            return redirect()->back();
        }
    }

    public function interest()
    {
        $cooperative = Auth::user()->cooperative_id;
        $loans = Loan::interests($cooperative);

        return view('pages.cooperative.financial-products.interest', compact('loans'));
    }

    public function export_interest($type)
    {
        $cooperative = Auth::user()->cooperative;
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('Loan_Interest_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new InterestExport($cooperative->id), $file_name);
        } else {
            $data = [
                'title' => 'Loan Interest',
                'pdf_view' => 'interest',
                'records' => Loan::interests($cooperative->id),
                'filename' => strtolower('Loan_Interest_' . date('d_m_Y')),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    // --- group loans

    public function groupLoanTypes()
    {
        $user = Auth::user();
        $groupLoanTypes = GroupLoanType::where('cooperative_id', $user->cooperative_id)->get();
        return view('pages.cooperative.financial-products.group-loan-types', compact('groupLoanTypes'));
    }

    /**
     * @throws ValidationException
     */
    public function addGroupLoanType(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $user = Auth::user();
        $groupLoanType = new GroupLoanType();
        $groupLoanType->cooperative_id = $user->cooperative_id;
        $groupLoanType->name = $request->name;
        $groupLoanType->created_by = $user->id;
        $groupLoanType->save();

        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Created a group loan type',
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Group loan type created');
        return redirect()->back();
    }

    public function groupLoans()
    {
        $cooperative = Auth::user()->cooperative->id;

        //get farmers with their loan limits
        $farmers = Farmer::whereHas('user', function ($query) use ($cooperative) {
            $query->where('cooperative_id', $cooperative);
        })->get();
        $loan_types = GroupLoanType::where('cooperative_id', $cooperative)->get();
        $loans = GroupLoanSummary::where('cooperative_id', $cooperative)->orderBy('id', 'desc')->limit(100)->get();
        return view('pages.cooperative.financial-products.group-loans', compact('loans', 'loan_types', 'farmers'));
    }

    public function requestGroupLoan(Request $request)
    {

        $request->validate([
            'type' => 'required',
            'amount' => 'required',
        ]);
        $user = Auth::user();
        $cooperative = $user->cooperative->id;

        $all_farmers = [];
        if ($request->farmers) {
            $all_farmers = $request->farmers;
        } else {
            $all_farmers = Farmer::whereHas('user', function ($query) use ($cooperative) {
                $query->where('cooperative_id', $cooperative);
            })->pluck('id')->toArray();
        }
        $config = GroupLoanConfig::where('cooperative_id', $cooperative)->first();

        if ($config == null) {
            toastr()->error("No configurations for group loan found");
            return redirect()->back();
        }

        $farmers = $this->allowedToGetGroupLoan($all_farmers, $config);
        $amount = ceil($request->amount / count($farmers));
        try {
            DB::beginTransaction();
            $group_loan_summery = new GroupLoanSummary();
            $group_loan_summery->total_amount = $request->amount;
            $group_loan_summery->number_of_farmers = count($farmers);
            $group_loan_summery->group_loan_type_id = $request->type;
            $group_loan_summery->created_by = $user->id;
            $group_loan_summery->cooperative_id = $cooperative;
            $group_loan_summery->save();
            $group_loan_summery_id = $group_loan_summery->refresh()->id;
            Log::info("Created Group loan Summery id $group_loan_summery_id");
            foreach ($farmers as $farmer) {
                $group_loan = new GroupLoan();
                $group_loan->amount = $amount;
                $group_loan->balance = $amount;
                $group_loan->farmer_id = $farmer;
                $group_loan->group_loan_summary_id = $group_loan_summery_id;
                $group_loan->save();
                $loan_id = $group_loan->refresh()->id;
                Log::info("Created Group loan id $loan_id for farmer id $farmer");

                $loanFarmer = Farmer::findOrFail($farmer);
                $farmer_names = ucwords(strtolower($loanFarmer->user->first_name . ' ' . $loanFarmer->user->other_names));
                if ($loanFarmer->wallet == null) {
                    $wallet = default_wallet($farmer, 0);
                    $wallet_id = $wallet->id;
                } else {
                    $wallet_id = $loanFarmer->wallet->id;
                }

                //save transaction
                $transaction = new WalletTransaction();
                $transaction->wallet_id = $wallet_id;
                $transaction->type = 'Group Loan Deposit';
                $transaction->amount = $amount;
                $transaction->reference = 'Group LOAN#' . $loan_id;
                $transaction->source = 'Group Loan';
                $transaction->initiator_id = $user->id;
                $transaction->description = 'Group Loan request deposited to wallet';
                $transaction->save();
                //update wallet
                $wallet = Wallet::find($wallet_id);
                $wallet->available_balance += $amount;
                $wallet->save();

                $acc_description = $farmer_names . ' Group Loan Request';

                $trx = create_account_transaction('Loan Awarded', $amount, $acc_description);
                if (!$trx) {
                    Log::error("Failed to record accounting transactions");
                    DB::rollback();
                    toastr()->error('Failed to create group loan: Failed to save wallet transaction');
                    throw new \Exception("Failed to save wallet transaction");
                }
            }
            $activity = 'Admin creating a group loan id ' . $group_loan_summery_id;
            $audit_trail_data = ['user_id' => $user->id, 'activity' => $activity, 'cooperative_id' => $cooperative];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Group Loan Created Successfully');
            return redirect()->back();


        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            toastr()->error('Oops Failed to create a group loan');
            DB::rollback();
            return redirect()->back();

        }

    }

    public function group_loan_details($group_loan_summery_id)
    {
        $group_loan_details = GroupLoan::where('group_loan_summary_id', $group_loan_summery_id)->get();
        $group_loan_summery = GroupLoanSummary::findOrFail($group_loan_summery_id);
        return view('pages.cooperative.financial-products.group-loan-details', compact('group_loan_details', 'group_loan_summery'));
    }

    public function groupLoanRepayment(Request $request, $group_loan_id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'source' => 'required',
            'amount' => 'required',
            'phone' => 'nullable|sometimes|regex:/^[0-9]{12}$/'
        ]);
        if ($request->source == LoanInstallment::WALLET_REPAYMENT_OPTION) {
            return $this->repay_group_loan($group_loan_id, $request->amount, $request->source);
        } else {
            toastr()->success('Check your phone to complete the transaction');
            $user = Auth::user();
            $this->repay_group_loan_by_mpesa($group_loan_id, $request->amount, $request->source, $user, $request->phone);
            return redirect()->back();
        }
    }

    public function group_loan_repayment_history($group_loan_id)
    {
        $group_loan = GroupLoan::findOrFail($group_loan_id);
        $repayment_histories = GroupLoanRepayment::where('group_loan_id', $group_loan_id)->get();
        return view('pages.cooperative.financial-products.group-loan-repayments', compact('group_loan', 'repayment_histories'));
    }

    public function group_loan_setting()
    {
        $configs = GroupLoanConfig::where('cooperative_id', Auth::user()->cooperative_id)->get();
        return view('pages.cooperative.financial-products.group-loan-setting', compact('configs'));
    }

    /**
     * @throws ValidationException
     */
    public function add_group_loan_config(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "number_of_loans_allowed" => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $alreadyConfigured = GroupLoanConfig::where('cooperative_id', $user->cooperative_id)->count() > 0;

        if ($alreadyConfigured) {
            toastr()->warning('Configuration is already set');
            return redirect()->back();
        }

        $groupLoanConfig = new GroupLoanConfig();
        $groupLoanConfig->cooperative_id = $user->cooperative_id;
        $groupLoanConfig->number_of_loans_allowed = $request->number_of_loans_allowed;
        $groupLoanConfig->save();

        $audit_trail_data = ['user_id' => $user->id, 'activity' => ' Created a group loan config', 'cooperative_id' => $user->cooperative_id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Config Created successfully');
        return redirect()->back();
    }

    /**
     * @throws ValidationException
     */
    public function edit_group_loan_setting(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "number_of_loans_allowed" => 'required|integer|min:1'
        ]);
        $user = Auth::user();
        $groupLoanConfig = GroupLoanConfig::findOrFail($id);
        $groupLoanConfig->number_of_loans_allowed = $request->number_of_loans_allowed;
        $groupLoanConfig->save();

        $audit_trail_data = ['user_id' => $user->id, 'activity' => ' Created a group loan config', 'cooperative_id' => $user->cooperative_id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Config Created successfully');
        return redirect()->back();
    }

    private function allowedToGetGroupLoan(array $farmers, GroupLoanConfig $config): array
    {
        $allowed_to_apply = [];
        $exceeded_limit = [];
        foreach ($farmers as $farmer) {
            $is_allowed = GroupLoan::where('farmer_id', $farmer)->where('status', '!=', GroupLoan::STATUS_PAID)->count() < $config->number_of_loans_allowed;
            if ($is_allowed) {
                $allowed_to_apply[] = $farmer;
            } else {
                $exceeded_limit[] = $farmer;
            }
        }
        if (count($exceeded_limit) > 0) {
            Log::info('These farmers exceeded the number of loans in group loan limit: ' . print_r($exceeded_limit, true));
        }

        return $allowed_to_apply;
    }

    public function group_loan_repayments()
    {
        $repayments = GroupLoanRepayment::where('cooperative_id', Auth::user()->cooperative_id)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
        return view('pages.cooperative.financial-products.group-loan-repayment-report', compact('repayments'));
    }

    public function limit_config()
    {
        $config = LimitRateConfig::where('cooperative_id', Auth::user()->cooperative_id)->first();
        return view('pages.cooperative.financial-products.limit_rate_config', compact('config'));
    }

    public function set_limit_config(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'rate' => 'required|min:1|max:100|regex:/^\d+(\.\d{1,2})?$/',
            'needs_approval' => 'required|in:0,1',
            'limit_for_approval' => 'required_if:needs_approval,==,1'
        ], [
            'limit_for_approval.required_if' => 'The limit for approval field is required when Needs Approval is Yes.'
        ]);

        $user = Auth::user();
        $cooperative = $user->cooperative_id;
        $config = LimitRateConfig::where('cooperative_id', $cooperative)->first();

        if ($config) {
            $config->rate = $request->rate;
            $config->needs_approval = $request->needs_approval;
            $config->limit_for_approval = $request->needs_approval == 1 ? $request->limit_for_approval : null;
        } else {
            $config = new LimitRateConfig();
            $config->rate = $request->rate;
            $config->needs_approval = $request->needs_approval;
            $config->limit_for_approval = $request->limit_for_approval;
            $config->cooperative_id = $cooperative;
        }
        $config->save();
        $audit_trail_data = ['user_id' => $user->id, 'activity' => ' Created a cooperative commercial loan config', 'cooperative_id' => $user->cooperative_id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Config Created successfully');
        return redirect()->back();
    }
}
