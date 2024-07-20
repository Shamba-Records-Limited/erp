<?php

namespace App\Http\Controllers\Common;

use App\Collection;
use App\Http\Controllers\Controller;
use App\NewInvoice;
use App\Quotation;
use App\Receipt;
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

    public function view_invoice($id) {
        $invoice = NewInvoice::find($id);

        return view('pages.common.view_invoice', compact('invoice'));
    }

    public function view_receipt($id) {
        $receipt = Receipt::find($id);

        // return view('pages.common.view_receipt', compact('receipt'));
    }
}