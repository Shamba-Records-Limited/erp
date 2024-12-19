<?php

namespace App\Http\Controllers\Common;

use App\Account;
use App\Collection;
use App\Http\Controllers\Controller;
use App\NewInvoice;
use App\Quotation;
use App\Receipt;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class CommonController extends Controller
{
    public function collection_unit($collection_id)
    {
        try {
            $units = DB::select(DB::raw("
                SELECT pc.unit FROM collections c
                JOIN products p ON p.id = c.product_id
                JOIN product_categories pc ON pc.id = p.product_category_id
                WHERE c.id = :collection_id
            "), ['collection_id' => $collection_id]);
            $unit = "";
            if (count($units) > 0) {
                $unit = $units[0]->unit;
            }

            dd($unit);

            return response($unit);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response();
        }
    }

    public function product_unit($product_id)
    {
        try {
            $units = DB::select(DB::raw("
                SELECT pc.unit FROM products p
                JOIN product_categories pc ON pc.id = p.category_id
                WHERE p.id = :product_id
            "), ['product_id' => $product_id]);
            $unit = "";
            if (count($units) > 0) {
                $unit = $units[0]->unit;
            }

            return response($unit);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response();
        }
    }

    public function view_quotation($id)
    {
        $quotation = Quotation::find($id);

        return view('pages.common.view_quotation', compact('quotation'));
    }

    public function print_quotation($id)
    {
        $quotation = Quotation::find($id);

        return view('pages.common.print_quotation', compact('quotation'));
    }

    public function view_invoice($id) {
        $invoice = NewInvoice::find($id);

        return view('pages.common.view_invoice', compact('invoice'));
    }

    public function print_invoice($id) {
        $invoice = NewInvoice::find($id);

        return view('pages.common.print_invoice', compact('invoice'));
    }

    public function view_receipt($id) {
        $receipt = Receipt::find($id);

        // return view('pages.common.view_receipt', compact('receipt'));
    }

    public function wallet_details() {
        $user = Auth::user();

        $data = [
            "has_wallet" => false
        ];

        // if cooperative admin
        if($user->hasRole('cooperative admin')){
            $coop_id = $user->cooperative_id;
            $cooperative_acc = Account::where("owner_type", "COOPERATIVE")->where("owner_id", $coop_id)->first();
            if (is_null($cooperative_acc)) {
                $accCount = Account::count();
                $cooperative_acc = new Account();
                $cooperative_acc->acc_number = "A".str_pad($accCount + 1, 5, '0', STR_PAD_LEFT);
                $cooperative_acc->owner_type = "COOPERATIVE";
                $cooperative_acc->owner_id = $coop_id;

                $cooperative_acc->credit_or_debit = "CREDIT";
                $cooperative_acc->save();
            }


            $data["has_wallet"] = True;
            $data["wallets"] = [["acc_number" => $cooperative_acc->acc_number, "balance" => $cooperative_acc->balance]];
        }
        // if miller admin
        else if ($user->hasRole('miller admin')){
            // get or create miller account
            $miller_id = $user->miller_admin->miller_id;
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


            $data["has_wallet"] = True;
            $data["wallets"] = [["acc_number" => $miller_acc->acc_number, "balance" => $miller_acc->balance]];
        }


        return response()->json($data);
    }

    public function print_transaction_receipt($id){
        $transaction = Transaction::find($id);
        //$lots = $transaction->lots;

        $lots = DB::select(DB::raw("
            SELECT l.*,l.available_quantity as quantity
            FROM collections c
            JOIN lots l ON c.lot_number = l.lot_number
            WHERE c.id = :subject_id
        "), [
            'subject_id' => $transaction->subject_id,
        ]);
       
       //dd($transaction,$lots);
        return view('pages.common.transaction_receipt', compact('transaction', 'lots'));
    }


    public function print_receipt($id) {
        $receipt = Receipt::find($id);

        return view('pages.common.print_receipt', compact('receipt'));
    }

}