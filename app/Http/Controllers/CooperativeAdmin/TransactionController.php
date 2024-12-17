<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Account;
use App\CollectionGroup;
use App\CollectionGroupItem;
use App\Http\Controllers\Controller;
use App\LotGroup;
use App\LotGroupItem;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    /**
     * @return View|Factory
     */
    public function index(): View
    {
        $user = Auth::user();
        $coop_id = $user->cooperative->id;

        $transactions = DB::select(DB::raw("
            SELECT t.*,
            (
                CASE WHEN t.subject_type = 'LOT'
                    THEN (SELECT l.lot_number FROM lots l WHERE l.lot_number = t.subject_id)
                WHEN t.subject_type = 'LOT_GROUP'
                    THEN (SELECT g.group_number FROM lot_groups g WHERE g.id= t.subject_id)
                WHEN t.subject_type = 'COLLECTION'
                    THEN (SELECT c.collection_number FROM collections c WHERE c.id = t.subject_id)
                WHEN t.subject_type = 'COLLECTION_GROUP'
                    THEN (SELECT cg.group_number FROM collection_groups cg WHERE cg.id = t.subject_id)
                END
            ) AS subject,
            (
                CASE WHEN t.sender_id = :coop_id
                    THEN 'Me'
                WHEN t.sender_type = 'MILLER' THEN
                    CONCAT(t.sender_type, ' - ',(SELECT m.name FROM millers m WHERE m.id = t.sender_id))
                ELSE 'OTHER'
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :coop_id1
                    THEN 'Me'
                WHEN t.recipient_type = 'MILLER' THEN
                    CONCAT(t.recipient_type, ' - ',(SELECT m.name FROM millers m WHERE m.id = t.recipient_id))
                WHEN t.recipient_type = 'FARMER' THEN
                    CONCAT(t.recipient_type, ' - ',(SELECT u.username FROM farmers f JOIN users u ON u.id = f.user_id WHERE f.id = t.recipient_id))
                ELSE 'Other'
                END
            ) AS recipient

            FROM transactions t
            -- WHERE HAS NO PARENT
            WHERE t.parent_id IS NULL AND
                (t.sender_id = :coop_id2 OR t.recipient_id = :coop_id3)
            ORDER BY t.created_at DESC;
        "), ["coop_id" => $coop_id, "coop_id1" => $coop_id, "coop_id2" => $coop_id, "coop_id3" => $coop_id]);

      

        return view("pages.cooperative-admin.transactions.index", compact('transactions'));
    }
    /**
     * @return View|Factory
     */
    public function view_add(): View
    {
        // add a filter for cooperatives with deliveries
        $farmers = DB::select(DB::raw("
            SELECT f.id, u.username FROM farmers f
            JOIN users u ON f.user_id = u.id
        "));

        return view("pages.cooperative-admin.transactions.add", compact("farmers"));
    }
    /**
     * @param mixed $id
     * @return Response
     */
    public function view_add_collection_selector($id): Response
    {
        // todo: add id filter
        $collections = DB::select(DB::raw("
            SELECT c.id, c.collection_number FROM collections c WHERE c.farmer_id = :id
        "), ["id" => $id]);

        $collectionOptions = "<option value=''>--SELECT COLLECTION--</option>
        ";
        foreach ($collections as $collection) {
            $collectionOptions .= "<option value='$collection->id'>$collection->collection_number</option>
            ";
        }

        return response($collectionOptions, 200)->header('Content-Type', 'text/html');
    }
   

    public function add_new(Request $request)
    {
        $request->validate([
            "farmer_id" => "required|exists:farmers,id",
            "collection_ids" => "required",
            "amount" => "required|numeric",
            "description" => "required"
        ]);
    
        DB::beginTransaction();

        $user = Auth::user();
        $coop_id = $user->cooperative->id;
        try {
            $transaction = new Transaction();
            $transaction->created_by = $user->id;
            // get or create miller account
            $cooperative_acc = Account::where("owner_type", "COOPERATIVE")->where("owner_id", $coop_id)->first();
            if (is_null($cooperative_acc)) {
                $accCount = Account::count();
                $cooperative_acc = new Account();
                $cooperative_acc->acc_number = "A".str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
                $cooperative_acc->owner_type = "COOPERATIVE";
                $cooperative_acc->owner_id = $coop_id;
                $cooperative_acc->credit_or_debit = "DEBIT";
                $cooperative_acc->save();
            }

            $transaction->sender_type = 'COOPERATIVE';
            $transaction->sender_id = $coop_id;
            $transaction->sender_acc_id = $cooperative_acc->id;
            // get or create farmer account
            $farmer_acc = Account::where("owner_type", "FARMER")->where("owner_id", $request->farmer_id)->first();
            if (is_null($farmer_acc)) {
                $accCount = Account::count();
                $farmer_acc = new Account();
                $farmer_acc->acc_number = "A".str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
                $farmer_acc->owner_type = "FARMER";
                $farmer_acc->owner_id = $request->farmer_id;
                $farmer_acc->credit_or_debit = "CREDIT";
                $farmer_acc->save();
            }
            $transaction->recipient_type = 'FARMER';
            $transaction->recipient_id = $request->farmer_id;
            $transaction->recipient_acc_id = $farmer_acc->id;
             //  dd($transaction);
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
            $transaction->type = 'FARMER_PAYMENT';
            $transaction->status = 'PENDING';

            if(count($request->collection_ids) == 1){
                $transaction->subject_type = 'COLLECTION';
                $transaction->subject_id = $request->collection_ids[0];
            } else {
                $groupCount = CollectionGroup::count();
                $groupNumber = "CG";
                $groupNumber .= str_pad($groupCount + 1, 3, '0', STR_PAD_LEFT);
                //dd($groupNumber);
                # todo: add bulk
                $collectionGroup = new CollectionGroup();
                $collectionGroup->group_number = $groupNumber;
                $collectionGroup->save();
                # save corresponding collection group items
                foreach($request->collection_ids as $collection_id) {
                    $collectionGroupItem = new CollectionGroupItem();
                    $collectionGroupItem->collection_group_id = $collectionGroup->id;
                    $collectionGroupItem->collection_id = $collection_id;
                    $collectionGroupItem->save();
                }
                $transaction->subject_type = 'COLLECTION_GROUP';
                $transaction->subject_id = $collectionGroup->id;
            }
            $transaction->save();
            DB::commit();
            toastr()->success('Transaction Created Successfully');
            return redirect()->route('cooperative-admin.transactions.show')->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
    /**
     * @param mixed $id
     * @return View|Factory
     */
    public function transaction_detail($id){
       $transaction = Transaction::find($id);

            $lots = $transaction->lots;

         
//           dd($lots);

        return view("pages.cooperative-admin.transactions.detail", compact('transaction', 'lots'));
    }
    /**
     * @param mixed $id
     * @return RedirectResponse
     */
    public function complete($id): RedirectResponse{
        $transaction = Transaction::find($id);

        DB::beginTransaction();
        try {
            perform_transaction($transaction);
            DB::commit();
            toastr()->success('Transaction Completed Successfully');
            return redirect()->route('cooperative-admin.transactions.show')->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }


    }
}
