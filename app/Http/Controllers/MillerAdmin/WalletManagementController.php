<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Account;
use App\Cooperative;
use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Lot;
use App\LotGroup;
use App\LotGroupItem;
use App\Transaction;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletManagementController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function list_transactions()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $transactions = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.type = 'DEPOSIT' THEN 'DEPOSIT'
                WHEN t.type = 'WITHDRAWAL' THEN 'WITHDRAWAL'
                WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.sender_id = :miller_id2 OR t.recipient_id = :miller_id3
            LEFT JOIN cooperatives c ON c.id = t.sender_id OR c.id = t.recipient_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id, "miller_id3" => $miller_id]);

        return view("pages.miller-admin.transactions.index", compact('transactions'));
    }

    public function export_many_transactions(Request $request, $type)
    {

        $export_status = $request->query("export_status", "all");
        $start_date = $request->query("start_date");
        $end_date = $request->query("end_date");

        $user = Auth::user();
        $miller_id = null;
        if ($user->miller_admin) {
            $miller_id = $user->miller_admin->miller_id;
        }

        $rawTransactions = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.sender_id = :miller_id2 OR t.recipient_id = :miller_id3
            JOIN cooperatives c ON c.id = t.sender_id OR c.id = t.recipient_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id, "miller_id3" => $miller_id]);

        $transactions = [];

        foreach ($rawTransactions as $transaction) {
            $status = 'Pending';
            if ($transaction->status == 'COMPLETE') {
                $status = 'Complete';
            }
            $transactions[] = [
                'transaction_number' => $transaction->transaction_number,
                'subject' => $transaction->subject,
                'sender' => $transaction->sender,
                'recipient' => $transaction->recipient,
                'amount' => $transaction->amount,
                'status' => $status,
            ];
        }

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('transactions_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new TransactionExport($transactions), $file_name);
        } else {
            $columns = [
                ['name' => 'Transaction Number', 'key' => "transaction_number"],
                ['name' => 'Subject', 'key' => "subject"],
                ['name' => 'Sender', 'key' => "sender"],
                ['name' => 'Recipient', 'key' => "recipient"],
                ['name' => 'Amount', 'key' => "amount"],
                ['name' => 'Status', 'key' => "status"],
            ];
            $data = [
                'title' => 'Transactions',
                'pdf_view' => 'transactions',
                'records' => $transactions,
                'filename' => strtolower('transactions_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }

    public function view_make_payment()
    {
        // add a filter for cooperatives with deliveries
        $cooperatives = DB::select(DB::raw("
            SELECT c.id, c.name FROM cooperatives c
        "));

        return view("pages.miller-admin.transactions.add", compact("cooperatives"));
    }

    public function view_add_lot_selector($id)
    {
        // todo: add id filter
        $lots = Lot::where("cooperative_id", $id)->get();

        $lotOptions = "<option value=''>--SELECT LOT--</option>
        ";
        foreach ($lots as $lot) {
            $lotOptions .= "<option value='$lot->lot_number'>$lot->lot_number - $lot->quantity KG</option>
            ";
        }

        $elem = "
            <label>Lot</label>
            <select class='form-control select2bs4' name='lot_ids' id='lot_ids' multiple>
                $lotOptions
            </select>
        ";

        return response($lotOptions, 200)->header('Content-Type', 'text/html');
    }

    public function add_transaction(Request $request)
    {
        $request->validate([
            "cooperative_id" => "required|exists:cooperatives,id",
            "lot_ids" => "required",
            "amount" => "required|numeric",
        ]);


        DB::beginTransaction();

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        try {
            $transaction = new Transaction();
            $transaction->created_by = $user->id;
            // get or create miller account
            $miller_acc = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->first();
            if (is_null($miller_acc)) {
                $accCount = Account::count();
                $miller_acc = new Account();
                $miller_acc->acc_number = "A" . str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
                $miller_acc->owner_type = "MILLER";
                $miller_acc->owner_id = $miller_id;

                $miller_acc->credit_or_debit = "CREDIT";
                $miller_acc->save();
            }

            $transaction->sender_type = 'MILLER';
            $transaction->sender_id = $miller_id;
            $transaction->sender_acc_id = $miller_acc->id;

            // get or create cooperative account
            $cooperative_acc = Account::where("owner_type", "COOPERATIVE")->where("owner_id", $request->cooperative_id)->first();
            if (is_null($cooperative_acc)) {
                $accCount = Account::count();
                $cooperative_acc = new Account();
                $cooperative_acc->acc_number = "A" . str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
                $cooperative_acc->owner_type = "COOPERATIVE";
                $cooperative_acc->owner_id = $request->cooperative_id;

                $cooperative_acc->credit_or_debit = "CREDIT";
                $cooperative_acc->save();
            }

            $transaction->recipient_type = 'COOPERATIVE';
            $transaction->recipient_id = $request->cooperative_id;
            $transaction->recipient_acc_id = $cooperative_acc->id;

            // get transaction number
            $now = Carbon::now();
            $transactionNumber = "T";
            $transactionNumber .= $now->format('Ymd');
            // count today's transactions
            $todaysTransactions = Transaction::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $transactionNumber .= str_pad($todaysTransactions + 1, 3, '0', STR_PAD_LEFT);

            $transaction->transaction_number = $transactionNumber;

            // amount source
            $transaction->amount_source = "SELF";
            $transaction->amount = $request->amount;
            $transaction->description = $request->description;
            $transaction->type = 'COOPERATIVE_PAYMENT';
            $transaction->status = 'PENDING';


            if (count($request->lot_ids) == 1) {
                $transaction->subject_type = 'LOT';
                $transaction->subject_id = $request->lot_ids[0];
            } else {
                $groupCount = LotGroup::count();
                $groupNumber = "LG";
                $groupNumber .= str_pad($groupCount + 1, 3, '0', STR_PAD_LEFT);
                # todo: add bulk
                $lotGroup = new LotGroup();
                $lotGroup->group_number = $groupNumber;
                $lotGroup->save();

                # save corresponding lot group items
                foreach ($request->lot_ids as $lot_id) {
                    $lotGroupItem = new LotGroupItem();
                    $lotGroupItem->lot_group_id = $lotGroup->id;
                    $lotGroupItem->lot_number = $lot_id;
                    $lotGroupItem->save();
                }

                $transaction->subject_type = 'LOT_GROUP';
                $transaction->subject_id = $lotGroup->id;
            }


            $transaction->save();

            DB::commit();
            toastr()->success('Transaction Created Successfully');
            return redirect()->route('miller-admin.wallet-management.account-payables')->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function transaction_detail($id)
    {
        $transaction = Transaction::find($id);

        $lots = $transaction->lots;

        return view("pages.miller-admin.transactions.detail", compact('transaction', 'lots'));
    }

    public function retrieve_lot_weights(Request $request)
    {
        $request->validate([
            "selectedLots" => "required"
        ]);

        $totalWeight = 0;
        $lotNumbers = $request->selectedLots;
        foreach ($lotNumbers as $lotNumber) {
            $lot = Lot::where("lot_number", $lotNumber)->firstOrFail();
            $totalWeight += $lot->quantity;
        }

        return response()->json(["lot_weights" => $totalWeight]);
    }

    public function view_deposit()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        // get or create miller account
        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->first();
        if (is_null($account)) {
            $accCount = Account::count();
            $account = new Account();
            $account->acc_number = "A" . str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
            $account->owner_type = "MILLER";
            $account->owner_id = $miller_id;

            $account->credit_or_debit = "CREDIT";
            $account->save();
        }

        return view('pages.miller-admin.wallet-management.deposits.add', compact('account'));
    }

    public function deposit(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "CREDIT")->first();
        // dd($account);

        $request->validate([
            "amount" => "required|numeric",
            "source_of_funds" => "required|string",
            "description" => "required|string",
            "deposit_slip" => "required|file",
        ]);

        DB::beginTransaction();
        try {
            // generate transaction number
            $now = Carbon::now();
            $transactionNumber = "T";
            $transactionNumber .= $now->format('Ymd');
            // count today's transactions
            $todaysTransactions = Transaction::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $transactionNumber .= str_pad($todaysTransactions + 1, 3, '0', STR_PAD_LEFT);

            $transaction = new Transaction();
            $transaction->transaction_number = $transactionNumber;
            $transaction->created_by = $user->id;
            $transaction->sender_type = 'CASH';
            $transaction->sender_id = $miller_id;
            $transaction->sender_acc_id = $account->id;

            $transaction->amount_source = 'SELF';

            $transaction->recipient_type = 'MILLER';
            $transaction->recipient_id = $miller_id;
            $transaction->recipient_acc_id = $account->id;

            $transaction->amount = $request->amount;
            $transaction->description = $request->description;
            $transaction->type = 'DEPOSIT';
            $transaction->status = 'PENDING';

            $transaction->subject_type = 'MILLER';
            $transaction->subject_id = $miller_id;

            $transaction->save();

            // save deposit slip
            $transaction->attachment = $request->deposit_slip ? store_image($request, "deposit_slip", $request->deposit_slip, 'images/deposit_slips', 200, 200) : null;
            $transaction->save();

            perform_transaction($transaction);

            DB::commit();
            toastr()->success('Deposit saved successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function view_withdraw()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        // get or create miller account
        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->first();
        if (is_null($account)) {
            $accCount = Account::count();
            $account = new Account();
            $account->acc_number = "A" . str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
            $account->owner_type = "MILLER";
            $account->owner_id = $miller_id;

            $account->credit_or_debit = "CREDIT";
            $account->save();
        }


        return view('pages.miller-admin.wallet-management.withdrawals.add', compact('account'));
    }

    public function withdraw(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "CREDIT")->first();

        $request->validate([
            "amount" => "required|numeric",
            "description" => "required|string",
            "purpose" => "required|string",
            "withdrawal_slip" => "sometimes|file|max:3072",
        ]);

        DB::beginTransaction();
        try {
            // create transaction number
            $now = Carbon::now();
            $transactionNumber = "T";
            $transactionNumber .= $now->format('Ymd');
            // count today's transactions
            $todaysTransactions = Transaction::where(DB::raw("DATE(created_at)"), $now->format('Y-m-d'))->count();
            $transactionNumber .= str_pad($todaysTransactions + 1, 3, '0', STR_PAD_LEFT);

            $transaction = new Transaction();
            $transaction->transaction_number = $transactionNumber;
            $transaction->created_by = $user->id;
            $transaction->sender_type = 'MILLER';
            $transaction->sender_id = $miller_id;
            $transaction->sender_acc_id = $account->id;

            $transaction->amount_source = 'SELF';

            $transaction->recipient_type = 'CASH';
            $transaction->recipient_id = $miller_id;
            $transaction->recipient_acc_id = $account->id;

            $transaction->amount = $request->amount;
            $transaction->description = $request->description;
            $transaction->type = 'WITHDRAWAL';
            $transaction->status = 'PENDING';

            $transaction->subject_type = 'MILLER';
            $transaction->subject_id = $miller_id;

            $transaction->purpose = $request->purpose;

            $transaction->save();

            // save withdrawal slip
            $transaction->attachment = $request->withdrawal_slip ? store_image($request, "withdrawal_slip", $request->withdrawal_slip, 'images/withdrawal_slips', 200, 200) : null;
            $transaction->save();

            perform_transaction($transaction);

            DB::commit();
            toastr()->success('Withdraw saved successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error($th->getMessage());
            return redirect()->back();
        }
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $date_range = $request->query("date_range", "week");
        $from_date = $request->query("from_date", "");
        $to_date = $request->query("to_date", "");

        $from_date_prev = "";
        $to_date_prev = "";

        if ($date_range == "custom") {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
        } else if ($date_range == "week") {
            $from_date = date("Y-m-d", strtotime("-7 days"));
            $to_date = date("Y-m-d");
            $prev_range = "Last Week";
            $from_date_prev = date("Y-m-d", strtotime("-14 days"));
            $to_date_prev = date("Y-m-d", strtotime("-7 days"));
        } else if ($date_range == "month") {
            $from_date = date("Y-m-d", strtotime("-30 days"));
            $to_date = date("Y-m-d");
            $prev_range = "Last Month";
            $from_date_prev = date("Y-m-d", strtotime("-60 days"));
            $to_date_prev = date("Y-m-d", strtotime("-30 days"));
        } else if ($date_range == "year") {
            $from_date = date("Y-m-d", strtotime("-365 days"));
            $to_date = date("Y-m-d");
            $prev_range = "Last Year";
            $from_date_prev = date("Y-m-d", strtotime("-730 days"));
            $to_date_prev = date("Y-m-d", strtotime("-365 days"));
        }

        $suggested_chart_mode = "daily";
        // to 60 days
        if (strtotime($to_date) - strtotime($from_date) < 60 * 24 * 60 * 60) {
            $suggested_chart_mode = "daily";
        }
        // to 4 months
        else if (strtotime($to_date) - strtotime($from_date) < 4 * 30 * 24 * 60 * 60) {
            $suggested_chart_mode = "weekly";
        }
        // to 3 years
        else if (strtotime($to_date) - strtotime($from_date) < 3 * 12 * 30 * 24 * 60 * 60) {
            $suggested_chart_mode = "monthly";
        } else {
            $suggested_chart_mode = "yearly";
        }

        $income = [];
        $expenses = [];
        if ($suggested_chart_mode == "daily") {
            $incomeDailyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT :from_date AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 DAY)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 DAY) <= :to_date
                )
                SELECT date_series.date AS x,
                    (
                        SELECT IFNULL(SUM(t.amount), 0)
                        FROM transactions t
                        WHERE CAST(t.completed_at AS DATE) = date_series.date AND
                            t.recipient_id = :miller_id
                    ) AS y
                FROM date_series
                GROUP BY date_series.date;";

            $income = DB::select(DB::raw($incomeDailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id,
            ]);

            $expensesDailyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT :from_date AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 DAY)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 DAY) <= :to_date
                )
                SELECT date_series.date AS x,
                    (
                        SELECT IFNULL(SUM(t.amount), 0)
                        FROM transactions t
                        WHERE CAST(t.completed_at AS DATE) = date_series.date AND
                            t.sender_id = :miller_id
                    ) AS y
                FROM date_series
                GROUP BY date_series.date;";

            $expenses = DB::select(DB::raw($expensesDailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id,
            ]);
        }



        $data = [
            "income" => $income,
            "expenses" => $expenses
        ];



        return view("pages.miller-admin.wallet-management.dashboard", compact("data", "date_range", "from_date", "to_date"));
    }

    public function account_receivables()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "DEBIT")->first();

        $receivables = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.recipient_id = :miller_id2
            JOIN cooperatives c ON c.id = t.sender_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL AND t.status = 'PENDING'
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id]);



        return view("pages.miller-admin.wallet-management.account-receivables", compact('receivables', 'account'));
    }

    public function account_payables()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "CREDIT")->first();

        $payables = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.sender_id = :miller_id2
            JOIN cooperatives c ON c.id = t.recipient_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL AND t.status = 'PENDING'
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id]);


        return view("pages.miller-admin.wallet-management.account-payables", compact('payables', 'account'));
    }

    public function payments_made()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "CREDIT")->first();

        $paymentsMade = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.sender_id = :miller_id2
            JOIN cooperatives c ON c.id = t.recipient_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL AND t.status = 'COMPLETE'
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id]);

        return view("pages.miller-admin.wallet-management.payments-made", compact('paymentsMade', 'account'));
    }

    public function payments_received()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "DEBIT")->first();

        $paymentsReceived = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.recipient_id = :miller_id2
            JOIN cooperatives c ON c.id = t.sender_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL AND t.status = 'PENDING'
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id]);


        return view("pages.miller-admin.wallet-management.payments-received", compact('paymentsReceived', 'account'));
    }

    public function list_deposits()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "CREDIT")->first();

        $deposits = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.type = 'DEPOSIT' THEN 'DEPOSIT'
                WHEN t.type = 'WITHDRAWAL' THEN 'WITHDRAWAL'
                WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.sender_id = :miller_id2 OR t.recipient_id = :miller_id3
            LEFT JOIN cooperatives c ON c.id = t.sender_id OR c.id = t.recipient_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL AND t.status = 'COMPLETE' AND t.type = 'DEPOSIT'
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id, "miller_id3" => $miller_id]);




        return view("pages.miller-admin.wallet-management.deposits.index", compact('deposits', 'account'));
    }

    public function list_withdrawals()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "CREDIT")->first();

        $withdrawals = DB::select(DB::raw("
            SELECT t.*, c.name AS dest,
            (
                CASE WHEN t.type = 'DEPOSIT' THEN 'DEPOSIT'
                WHEN t.type = 'WITHDRAWAL' THEN 'WITHDRAWAL'
                WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :miller_id
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :miller_id1
                    THEN 'Me'
                ELSE (SELECT cf.name FROM cooperatives cf WHERE cf.id = c.id)
                END
            ) AS recipient
            FROM transactions t
            JOIN millers m ON t.sender_id = :miller_id2 OR t.recipient_id = :miller_id3
            LEFT JOIN cooperatives c ON c.id = t.sender_id OR c.id = t.recipient_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL AND t.status = 'COMPLETE' AND t.type = 'WITHDRAWAL'
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id, "miller_id2" => $miller_id, "miller_id3" => $miller_id]);

        return view("pages.miller-admin.wallet-management.withdrawals.index", compact('withdrawals', 'account'));
    }
}
