<?php

namespace App\Http\Controllers\Farmer;

use App\Collection;
use App\CollectionGroup;
use App\Http\Controllers\Controller;
use App\Lot;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $farmer_id = $user->farmer->id;

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
                CASE WHEN t.sender_id = :farmer_id
                    THEN 'Me'
                WHEN t.sender_type = 'MILLER' THEN
                    CONCAT(t.sender_type, ' - ',(SELECT m.name FROM millers m WHERE m.id = t.sender_id))
                ELSE 'OTHER'
                END
            ) AS sender,
            (
                CASE WHEN t.recipient_id = :farmer_id1
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
                (t.sender_id = :farmer_id2 OR t.recipient_id = :farmer_id3)
        "), ["farmer_id" => $farmer_id, "farmer_id1" => $farmer_id, "farmer_id2" => $farmer_id, "farmer_id3" => $farmer_id]);

        return view("pages.farmer.transactions.index", compact('transactions'));
    }

    public function detail($id) {
        $transaction = Transaction::find($id);

        $subjectItems = [];
        if($transaction->subject_type == 'LOT') {
            $lot = Lot::find($transaction->subject_id);
            $subjectItems[] = [
                "identification" => $lot->lot_number,
                "quantity" => $lot->quantity." KG",
                "sub_total" => round($lot->quantity * $transaction->pricing, 2),
            ];
        } else if ($transaction->subject_type == 'COLLECTION') {
            $collection = Collection::find($transaction->subject_id);
            $subjectItems[] = [
                "identification" => $collection->collection_number,
                "quantity" => $collection->quantity." KG",
                "sub_total" => round($collection->quantity * $transaction->pricing, 2),
            ];
        } else if ($transaction->subject_type == 'COLLECTION_GROUP') {
            $collectionGroup = CollectionGroup::find($transaction->subject_id);
            $collections = $collectionGroup->collections;
            foreach($collections as $collection) {
                $subjectItems[] = [
                    "identification" => $collection->collection_number,
                    "quantity" => $collection->quantity." KG",
                    "sub_total" => round($collection->quantity * $transaction->pricing, 2),
                ];               
            }
        }

        return view("pages.farmer.transactions.detail", compact('transaction', 'subjectItems'));
    }
}
