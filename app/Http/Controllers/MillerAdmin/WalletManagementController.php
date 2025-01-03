<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Account;
use App\Cooperative;
use App\Exports\GeneratableExport;
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

        return view('pages.common.wallet-management.deposits.add', compact('account'));
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


        return view('pages.common.wallet-management.withdrawals.add', compact('account'));
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



        return view("pages.common.wallet-management.dashboard", compact("data", "date_range", "from_date", "to_date"));
    }

    public function account_receivables()
    {
        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.account-receivables.index", compact('acc_type'));
    }

    public function account_receivables_table(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "DEBIT")->first();

        $condition = "t.parent_id IS NULL AND 
                        t.status = 'PENDING' AND
                        t.recipient_id = :miller_id";

        // pagination
        $limit = (int)$request->query("limit", "10");
        $page = (int)$request->query("page", "1");
        $offset = ($page - 1) * $limit;


        // search
        $outerCondition = "";
        $search = $request->query("search");
        if ($search) {
            $outerCondition .= " AND (
                            subquery.transaction_number LIKE '%$search%' OR
                            subquery.subject LIKE '%$search%' OR
                            subquery.sender LIKE '%$search%' OR
                            subquery.recipient LIKE '%$search%' OR
                            subquery.formatted_amount LIKE '%$search%' OR
                            subquery.created_at LIKE '%$search%'
                            )";
        }

        // filter
        $outerCondition = "";
        $rawFilter = $request->query("filter", "");
        $outerCondition .= outerConditionFromFilter($rawFilter);

        $subjectColumn = getTransactionSubjectSubqueryColumn();
        $senderColumn = getTransactionSenderSubqueryColumn();
        $recipientColumn = getTransactionRecipientSubqueryColumn();

        $receivables = DB::select(DB::raw("
            SELECT * FROM (SELECT t.*, FORMAT(t.amount, 2) as formatted_amount,
            $subjectColumn AS subject,
            $senderColumn AS sender,
            $recipientColumn AS recipient

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
             WHERE TRUE $outerCondition
             LIMIT :limit OFFSET :offset
        "), ["miller_id" => $miller_id, "limit" => $limit, "offset" => $offset]);

        $summationQuery = DB::select(DB::raw("
            SELECT SUM(amount) AS total, COUNT(id) AS total_count FROM (SELECT t.*, FORMAT(t.amount, 2) as formatted_amount,
            $subjectColumn AS subject,
            $senderColumn AS sender,
            $recipientColumn AS recipient

            FROM transactions t
            WHERE $condition
            ) AS subquery
             WHERE TRUE $outerCondition
            "), ["miller_id" => $miller_id]);

        $receivablesTotal = $summationQuery[0]->total;
        $totalItems = $summationQuery[0]->total_count;

        $lastPage = ceil($totalItems / $limit);

        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.account-receivables.table", compact('receivables', 'receivablesTotal', 'totalItems', 'page', 'lastPage', 'acc_type'));
    }

    public function export_account_receivables(request $request, $type)
    {
        $user = auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }



        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "DEBIT")->first();

        $condition = "t.parent_id IS NULL AND 
                        t.status = 'PENDING' AND
                        t.recipient_id = :miller_id";

        // search
        $outercondition = "";
        $search = $request->query("search");
        if ($search) {
            $outercondition .= " and (
                            subquery.transaction_number like '%$search%' or
                            subquery.subject like '%$search%' or
                            subquery.sender like '%$search%' or
                            subquery.recipient like '%$search%' or
                            subquery.formatted_amount like '%$search%' or
                            subquery.created_at like '%$search%'
                            )";
        }

        // filter
        $rawfilter = $request->query("filter", "");
        $outercondition .= outerconditionfromfilter($rawfilter);

        $payables = db::select(db::raw("
            select * from (select t.*, format(t.amount, 2) as formatted_amount,
            (
                case when t.type = 'operational_expense'
                    then 'operational_expense'
                when t.subject_type = 'lot'
                    then (select l.lot_number from lots l where l.lot_number = t.subject_id)
                when t.subject_type = 'lot_group'
                    then (select g.group_number from lot_groups g where g.id= t.subject_id)
                else t.type
                end
            ) as subject,
            (
                case when t.sender_type = 'cooperative'
                    then (select c.name from cooperatives c where c.id = t.sender_id)
                when t.sender_type = 'miller'
                    then (select m.name from millers m where m.id = t.sender_id)
                when t.sender_type = 'cash'
                    then 'cash'
                end
            ) as sender,
            (
                case when t.recipient_type = 'cash'
                    then 'cash'
                when t.recipient_type = 'cooperative'
                    then (select c.name from cooperatives c where c.id = t.recipient_id)
                when t.recipient_type = 'miller'
                    then (select m.name from millers m where m.id = t.recipient_id)
                when t.recipient_type = 'farmer'
                    then (select concat(u.first_name,' ',u.other_names) from farmers f join users u on f.user_id = u.id where f.id = t.recipient_id)
                end
            ) as recipient
            from transactions t
            -- where has no parent
            where $condition) as subquery
            where true $outercondition
        "), ["miller_id" => $miller_id]);

        $paymentstotal = db::select(db::raw("select sum(amount) as total from (select t.*, format(t.amount, 2) as formatted_amount,
            (
                case when t.type = 'operational_expense'
                    then 'operational_expense'
                when t.subject_type = 'lot'
                    then (select l.lot_number from lots l where l.lot_number = t.subject_id)
                when t.subject_type = 'lot_group'
                    then (select g.group_number from lot_groups g where g.id= t.subject_id)
                else t.type
                end
            ) as subject,
            (
                case when t.sender_type = 'cooperative'
                    then (select c.name from cooperatives c where c.id = t.sender_id)
                when t.sender_type = 'miller'
                    then (select m.name from millers m where m.id = t.sender_id)
                when t.sender_type = 'cash'
                    then 'cash'
                end
            ) as sender,
            (
                case when t.recipient_type = 'cash'
                    then 'cash'
                when t.recipient_type = 'cooperative'
                    then (select c.name from cooperatives c where c.id = t.recipient_id)
                when t.recipient_type = 'miller'
                    then (select m.name from millers m where m.id = t.recipient_id)
                when t.recipient_type = 'farmer'
                    then (select concat(u.first_name,' ',u.other_names) from farmers f join users u on f.user_id = u.id where f.id = t.recipient_id)
                end
            ) as recipient
            from transactions t
            -- where has no parent
            where $condition) as subquery
            where true $outercondition
        "), ["miller_id" => $miller_id])[0]->total;

        $columns = [
            ["name" => "transaction number", "key" => "transaction_number"],
            ["name" => "subject", "key" => "subject"],
            ["name" => "sender", "key" => "sender"],
            ["name" => "recipient", "key" => "recipient"],
            ["name" => "amount", "key" => "formatted_amount"],
            ["name" => "timestamp", "key" => "created_at"],
        ];

        if ($type != env('pdf_format')) {
            $file_name = strtolower('account_payables_' . date('d_m_y')) . '.' . $type;
            return excel::download(new GeneratableExport($columns, $payables), $file_name);
        } else {
            // convert to arrays of arrays from arrays of objects
            $payables = array_map(function ($payable) {
                return (array) $payable;
            }, $payables);

            $data = [
                'title' => 'account payables',
                'pdf_view' => 'account_payables',
                'records' => $payables,
                'summation' => number_format($paymentstotal),
                'filename' => strtolower('account_payables_' . date('d_m_y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }

    public function account_payables()
    {
        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.account-payables.index", compact('acc_type'));
    }

    public function account_payables_table(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "DEBIT")->first();

        $condition = "t.parent_id IS NULL AND
                        t.status='PENDING' AND 
                        t.sender_id = :miller_id";

        // pagination
        $limit = (int)$request->query("limit", "10");
        $page = (int)$request->query("page", "1");
        $offset = ($page - 1) * $limit;


        // search
        $outerCondition = "";
        $search = $request->query("search");
        if ($search) {
            $outerCondition .= " AND (
                            subquery.transaction_number LIKE '%$search%' OR
                            subquery.subject LIKE '%$search%' OR
                            subquery.sender LIKE '%$search%' OR
                            subquery.recipient LIKE '%$search%' OR
                            subquery.formatted_amount LIKE '%$search%' OR
                            subquery.created_at LIKE '%$search%'
                            )";
        }

        $rawFilter = $request->query("filter", "");
        $outerCondition .= outerConditionFromFilter($rawFilter);

        $subjectColumn = getTransactionSubjectSubqueryColumn();
        $senderColumn = getTransactionSenderSubqueryColumn();
        $recipientColumn = getTransactionRecipientSubqueryColumn();

        $payables = DB::select(DB::raw("
            SELECT * FROM (SELECT t.*, FORMAT(t.amount, 2) as formatted_amount,
            $subjectColumn AS subject,
            $senderColumn AS sender,
            $recipientColumn AS recipient

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition) AS subquery
            WHERE TRUE $outerCondition
            LIMIT :limit OFFSET :offset
        "), ["miller_id" => $miller_id, "limit" => $limit, "offset" => $offset]);

        $summationQuery = DB::select(DB::raw("
            SELECT SUM(amount) AS total, COUNT(id) AS total_count FROM (SELECT t.*, FORMAT(t.amount, 2) as formatted_amount,
            $subjectColumn AS subject,
            $senderColumn AS sender,
            $recipientColumn AS recipient
            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition) AS subquery
            WHERE TRUE $outerCondition
            "), ["miller_id" => $miller_id]);

        $payablesTotal = $summationQuery[0]->total;
        $totalItems = $summationQuery[0]->total_count;

        $lastPage = ceil($totalItems / $limit);


        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.account-payables.table", compact('payables', 'payablesTotal', 'totalItems', 'page', 'lastPage', 'acc_type'));
    }

    public function export_account_payables(Request $request, $type)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $condition = "t.parent_id IS NULL AND
                        t.status='PENDING' AND  
                        t.sender_id = :miller_id";

        // search
        $outerCondition = "";
        $search = $request->query("search");
        if ($search) {
            $outerCondition .= " AND (
                            subquery.transaction_number LIKE '%$search%' OR
                            subquery.subject LIKE '%$search%' OR
                            subquery.sender LIKE '%$search%' OR
                            subquery.recipient LIKE '%$search%' OR
                            subquery.formatted_amount LIKE '%$search%' OR
                            subquery.created_at LIKE '%$search%'
                            )";
        }

        // filter
        $rawFilter = $request->query("filter", "");
        $outerCondition .= outerConditionFromFilter($rawFilter);

        $payables = DB::select(DB::raw("
            SELECT * FROM (SELECT t.*, FORMAT(t.amount, 2) as formatted_amount,
            (
                CASE WHEN t.type = 'OPERATIONAL_EXPENSE'
                    THEN 'OPERATIONAL_EXPENSE'
                WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                ELSE t.type
                END
            ) AS subject,
            (
                CASE WHEN t.sender_type = 'COOPERATIVE'
                    THEN (select c.name from cooperatives c where c.id = t.sender_id)
                WHEN t.sender_type = 'MILLER'
                    THEN (select m.name from millers m where m.id = t.sender_id)
                WHEN t.sender_type = 'CASH'
                    THEN 'CASH'
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_type = 'CASH'
                    THEN 'CASH'
                WHEN t.recipient_type = 'COOPERATIVE'
                    THEN (select c.name from cooperatives c where c.id = t.recipient_id)
                WHEN t.recipient_type = 'MILLER'
                    THEN (select m.name from millers m where m.id = t.recipient_id)
                WHEN t.recipient_type = 'FARMER'
                    THEN (select CONCAT(u.first_name,' ',u.other_names) from farmers f join users u ON f.user_id = u.id where f.id = t.recipient_id)
                END
            ) AS recipient
            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition) AS subquery
            WHERE TRUE $outerCondition
        "), ["miller_id" => $miller_id]);

        $paymentsTotal = DB::select(DB::raw("SELECT SUM(amount) AS total FROM (SELECT t.*, FORMAT(t.amount, 2) as formatted_amount,
            (
                CASE WHEN t.type = 'OPERATIONAL_EXPENSE'
                    THEN 'OPERATIONAL_EXPENSE'
                WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                ELSE t.type
                END
            ) AS subject,
            (
                CASE WHEN t.sender_type = 'COOPERATIVE'
                    THEN (select c.name from cooperatives c where c.id = t.sender_id)
                WHEN t.sender_type = 'MILLER'
                    THEN (select m.name from millers m where m.id = t.sender_id)
                WHEN t.sender_type = 'CASH'
                    THEN 'CASH'
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_type = 'CASH'
                    THEN 'CASH'
                WHEN t.recipient_type = 'COOPERATIVE'
                    THEN (select c.name from cooperatives c where c.id = t.recipient_id)
                WHEN t.recipient_type = 'MILLER'
                    THEN (select m.name from millers m where m.id = t.recipient_id)
                WHEN t.recipient_type = 'FARMER'
                    THEN (select CONCAT(u.first_name,' ',u.other_names) from farmers f join users u ON f.user_id = u.id where f.id = t.recipient_id)
                END
            ) AS recipient
            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition) AS subquery
            WHERE TRUE $outerCondition
        "), ["miller_id" => $miller_id])[0]->total;

        $columns = [
            ["name" => "Transaction Number", "key" => "transaction_number"],
            ["name" => "Subject", "key" => "subject"],
            ["name" => "Sender", "key" => "sender"],
            ["name" => "Recipient", "key" => "recipient"],
            ["name" => "Amount", "key" => "formatted_amount"],
            ["name" => "Timestamp", "key" => "created_at"],
        ];

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('account_payables_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new GeneratableExport($columns, $payables), $file_name);
        } else {
            // convert to arrays of arrays from arrays of objects
            $payables = array_map(function ($payable) {
                return (array) $payable;
            }, $payables);

            $data = [
                'title' => 'Account Payables',
                'pdf_view' => 'account_payables',
                'records' => $payables,
                'summation' => number_format($paymentsTotal),
                'filename' => strtolower('account_payables_' . date('d_m_Y')),
                'orientation' => 'letter',
            ];
            return download_pdf($columns, $data);
        }
    }

    public function income()
    {
        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.income_partials.income", compact('acc_type'));
    }

    public function income_table(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $account = Account::where("owner_type", "MILLER")->where("owner_id", $miller_id)->where("credit_or_debit", "DEBIT")->first();

        $condition = "t.parent_id IS NULL AND
                t.status = 'COMPLETE' AND
                t.recipient_id = :miller_id AND
                t.recipient_type != 'CASH' AND
                t.sender_type != 'CASH'";

        // pagination
        $limit = (int)$request->query("limit", "10");
        $page = (int)$request->query("page", "1");
        $offset = ($page - 1) * $limit;


        // search
        $outerCondition = "";
        $search = $request->query("search");
        if ($search) {
            $outerCondition .= " AND (
                            subquery.transaction_number LIKE '%$search%' OR
                            subquery.subject LIKE '%$search%' OR
                            subquery.sender LIKE '%$search%' OR
                            subquery.recipient LIKE '%$search%' OR
                            subquery.formatted_amount LIKE '%$search%' OR
                            subquery.created_at LIKE '%$search%'
                            )";
        }

        $rawFilter = $request->query("filter", "");
        $outerCondition .= outerConditionFromFilter($rawFilter);

        $subjectColumn = getTransactionSubjectSubqueryColumn();
        $senderColumn = getTransactionSenderSubqueryColumn();
        $recipientColumn = getTransactionRecipientSubqueryColumn();

        $income = DB::select(DB::raw("
            SELECT * FROM (SELECT t.*, CONCAT('KSH. ', FORMAT(t.amount, 2)) as formatted_amount,
            $subjectColumn AS subject,
            $senderColumn AS sender,
            $recipientColumn AS recipient

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
             WHERE TRUE $outerCondition
             LIMIT :limit OFFSET :offset
        "), ["miller_id" => $miller_id, "limit" => $limit, "offset" => $offset]);

        $summationQuery = DB::select(DB::raw("
            SELECT SUM(amount) AS total, COUNT(id) AS total_count FROM (
                SELECT t.*, 
                CONCAT('KSH. ', FORMAT(t.amount, 2)) as formatted_amount,
                $subjectColumn AS subject,
                $senderColumn AS sender,
                $recipientColumn AS recipient

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
             WHERE TRUE $outerCondition
        "), ["miller_id" => $miller_id]);

        $incomeTotal = $summationQuery[0]->total;
        $totalItems = $summationQuery[0]->total_count;

        $lastPage = ceil($totalItems / $limit);

        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.income_partials.table", compact('income', 'incomeTotal', 'totalItems', 'page', 'lastPage', 'acc_type'));
    }

    public function export_income(Request $request, $type)
    {
       return general_export_transaction($request, "income","miller-admin", $type); 
    }

    public function expenses(Request $request)
    {
        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.expenses_partials.expenses", compact('acc_type'));
    }

    public function expenses_table(Request $request)
    {
        \Debugbar::disable();

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $condition = "t.parent_id IS NULL AND
                t.status = 'COMPLETE' AND
                t.sender_id = :miller_id AND
                t.recipient_type != 'CASH' AND
                t.sender_type != 'CASH'";

        // pagination
        $limit = (int)$request->query("limit", "10");
        $page = (int)$request->query("page", "1");
        $offset = ($page - 1) * $limit;


        // search
        $outerCondition = "";
        $search = $request->query("search");
        if ($search) {
            $outerCondition .= " AND (
                            subquery.transaction_number LIKE '%$search%' OR
                            subquery.subject LIKE '%$search%' OR
                            subquery.sender LIKE '%$search%' OR
                            subquery.recipient LIKE '%$search%' OR
                            subquery.formatted_amount LIKE '%$search%' OR
                            subquery.created_at LIKE '%$search%'
                            )";
        }

        // filter
        $rawFilter = $request->query("filter", "");
        $outerCondition .= outerConditionFromFilter($rawFilter);

        $subjectColumn = getTransactionSubjectSubqueryColumn();
        $senderColumn = getTransactionSenderSubqueryColumn();
        $recipientColumn = getTransactionRecipientSubqueryColumn();

        $expenses = DB::select(DB::raw("
            SELECT * FROM (SELECT 
                t.*, 
                CONCAT('KSH. ', FORMAT(t.amount, 2)) as formatted_amount,
                $subjectColumn AS subject,
                $senderColumn AS sender,
                $recipientColumn AS recipient

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
            WHERE TRUE $outerCondition
            LIMIT :limit OFFSET :offset
        "), ["miller_id" => $miller_id, "limit" => $limit, "offset" => $offset]);

        $summationQuery = DB::select(DB::raw("
            SELECT SUM(amount) AS total, COUNT(id) AS total_count FROM (SELECT
                t.*,
                CONCAT('KSH. ', FORMAT(t.amount, 2)) as formatted_amount,
                $subjectColumn AS subject,
                $senderColumn AS sender,
                $recipientColumn AS recipient

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
            WHERE TRUE $outerCondition
        "), ["miller_id" => $miller_id]);

        $expensesTotal = $summationQuery[0]->total;
        $totalItems = $summationQuery[0]->total_count;

        $lastPage = ceil($totalItems / $limit);

        $acc_type = "miller-admin";

        // $expensesTotal = DB::select(DB::raw("SELECT FORMAT(sum(t.amount), 2) AS total FROM transactions t WHERE $condition"), ["coop_id" => $coop_id])[0]->total;


        return view("pages.common.wallet-management.expenses_partials.table", compact('expenses', 'expensesTotal', "totalItems", "page", "lastPage", 'acc_type'));
    }

    public function export_expenses(Request $request, $type)
    {
        $columns = ["transaction_number", "subject", "sender", "recipient", "amount", "status", "created_at"];
        return general_export_transaction($request, "expense", "miller-admin", $type, ["columns" => $columns]);
    }


    public function list_deposits()
    {
        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.deposits.index", compact('acc_type'));
    }

    public function deposits_table(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $condition = "t.parent_id IS NULL AND
                        t.status = 'COMPLETE' AND
                        t.type = 'DEPOSIT' AND
                        t.sender_id = :miller_id";

        // pagination
        $limit = (int)$request->query("limit", "10");
        $page = (int)$request->query("page", "1");
        $offset = ($page - 1) * $limit;


        // search
        $outerCondition = "";
        $search = $request->query("search");
        if ($search) {
            $outerCondition .= " AND (
                            subquery.transaction_number LIKE '%$search%' OR
                            subquery.amount LIKE '%$search%' OR
                            subquery.amount_source LIKE '%$search%' OR
                            subquery.status LIKE '%$search%' OR
                            subquery.created_at LIKE '%$search%'
                            )";
        }

        // filter
        $rawFilter = $request->query("filter", "");
        $outerCondition .= outerConditionFromFilter($rawFilter);

        // dd($outerCondition);

        $deposits = DB::select(DB::raw("
            SELECT * FROM (SELECT 
                t.id,
                t.transaction_number,
                t.amount,
                FORMAT(t.amount, 2) AS formatted_amount,
                t.amount_source,
                t.status,
                t.created_at

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
             WHERE TRUE $outerCondition
             LIMIT :limit OFFSET :offset
        "), ["miller_id" => $miller_id, "limit" => $limit, "offset" => $offset]);

        $summationQuery = DB::select(DB::raw("
            SELECT SUM(amount) AS total, COUNT(id) AS total_count FROM (SELECT
                t.id,
                t.transaction_number,
                t.amount,
                FORMAT(t.amount, 2) AS formatted_amount,
                t.amount_source,
                t.status,
                t.created_at
            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ) AS subquery
             WHERE TRUE $outerCondition
        "), ["miller_id" => $miller_id]);

        $depositsTotal = $summationQuery[0]->total;
        $totalItems = $summationQuery[0]->total_count;

        $lastPage = ceil($totalItems / $limit);


        $acc_type = "miller-admin";


        return view("pages.common.wallet-management.deposits.table", compact('deposits', 'depositsTotal', 'totalItems', 'page', 'lastPage', 'acc_type'));
    }

    public function export_deposits(Request $request, $type)
    {
        $columns = ["transaction_number", "amount", "amount_source", "status", "created_at"];
        return general_export_transaction($request, "deposit", "miller-admin", $type, ["columns" => $columns]);
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

        return view("pages.common.wallet-management.withdrawals.index", compact('withdrawals', 'account'));
    }

    public function withdrawals_table(Request $request)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $condition = "t.parent_id IS NULL AND
                        t.status = 'COMPLETE' AND
                        t.type = 'WITHDRAWAL' AND
                        t.sender_id = :miller_id";

        // pagination
        $limit = (int)$request->query("limit", "10");
        $page = (int)$request->query("page", "1");
        $offset = ($page - 1) * $limit;


        // search
        $outerCondition = "";
        $search = $request->query("search");
        if ($search) {
            $outerCondition .= " AND (
                            subquery.transaction_number LIKE '%$search%' OR
                            subquery.amount LIKE '%$search%' OR
                            subquery.purpose LIKE '%$search%' OR
                            subquery.status LIKE '%$search%' OR
                            subquery.created_at LIKE '%$search%'
                            )";
        }

        // filter
        $rawFilter = $request->query("filter", "");
        $outerCondition .= outerConditionFromFilter($rawFilter);

        // dd($outerCondition);


        $withdrawals = DB::select(DB::raw("
            SELECT * FROM (SELECT t.id, t.transaction_number, t.amount, FORMAT(t.amount, 2) AS formatted_amount, t.purpose, t.status, t.created_at
            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
             WHERE TRUE $outerCondition
             LIMIT :limit OFFSET :offset
        "), ["miller_id" => $miller_id, "limit" => $limit, "offset" => $offset]);

        $summationQuery = DB::select(DB::raw("
            SELECT SUM(amount) AS total, COUNT(id) AS total_count FROM (SELECT t.id, t.transaction_number, t.amount, FORMAT(t.amount, 2) AS formatted_amount, t.purpose, t.status, t.created_at
            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE $condition
            ORDER BY t.created_at DESC
            ) AS subquery
             WHERE TRUE $outerCondition
        "), ["miller_id" => $miller_id]);

        $withdrawalsTotal = $summationQuery[0]->total;
        $totalItems = $summationQuery[0]->total_count;

        $lastPage = ceil($totalItems / $limit);

        $acc_type = "miller-admin";

        return view("pages.common.wallet-management.withdrawals.table", compact('withdrawals', 'withdrawalsTotal', 'totalItems', 'page', 'lastPage', 'acc_type'));
    }

    public function export_withdrawals(Request $request, $type)
    {
        $columns = ["transaction_number", "amount", "purpose", "status", "created_at"];
        return general_export_transaction($request, "withdrawal", "miller-admin", $type, ["columns" => $columns]);
    }

    public function complete_transaction($id, Request $request)
    {
        $transaction = Transaction::find($id);

        $to = $request->query("to", "miller-admin.transactions.show");


        DB::beginTransaction();
        try {
            perform_transaction($transaction);
            DB::commit();
            toastr()->success('Transaction Completed Successfully');
            return redirect()->route($to)->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
}
