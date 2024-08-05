<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Account;
use App\Http\Controllers\Controller;
use App\LotGroup;
use App\LotGroupItem;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $transactions = DB::select(DB::raw("
            SELECT t.*, c.name AS dest
            FROM transactions t
            JOIN millers m ON t.sender_id = :miller_id OR t.recipient_id = :miller_id1
            JOIN cooperatives c ON c.id = t.sender_id OR c.id = t.recipient_id
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL
        "), ["miller_id" => $miller_id, "miller_id1" => $miller_id]);

        return view("pages.miller-admin.transactions.index", compact('transactions'));
    }

    public function view_add()
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
        $lots = DB::select(DB::raw("
            SELECT l.lot_number FROM lots l WHERE l.cooperative_id = :id
        "), ["id" => $id]);

        $lotOptions = "<option value=''>--SELECT LOT--</option>
        ";
        foreach ($lots as $lot) {
            $lotOptions .= "<option value='$lot->lot_number'>$lot->lot_number</option>
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

    public function add(Request $request)
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
                $miller_acc->acc_number = "A".str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
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
                $cooperative_acc->acc_number = "A".str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
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

            
            if(count($request->lot_ids) == 1){
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
                foreach($request->lot_ids as $lot_id) {
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
            return redirect()->route('miller-admin.transactions.show')->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
}
