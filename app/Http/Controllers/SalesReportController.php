<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoicePayment;
use App\Sale;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    //view
    public function index()
    {
        //get manufactured products
        $coop = Auth::user()->cooperative->id;
        $quote = Sale::SALE_TYPE_QUOTATION;
        $sale = Sale::SALE_TYPE_SALE;
        $invoices = Sale::with('invoices')->where('cooperative_id', $coop)->where('type', 'sale')->latest()->get()->take(5);
        $quotations = Sale::with('invoices')->where('cooperative_id', $coop)->where('type', 'quotation')->latest()->get()->take(5);
        $payments = \DB::select("select sum(amount) as payments from invoice_payments ip
                                        join invoices i on ip.invoice_id = i.id
                                        join sales s on i.sale_id = s.id where s.cooperative_id = '$coop' AND s.type = '$sale' ")[0]->payments;

        $pending_payments =\DB::select("
        select sum(balance) as pending_payments from sales where cooperative_id = '$coop' AND type = '$sale'
        ")[0]->pending_payments;

        $quotes  =\DB::select("
        select sum(balance) as quotations from sales where cooperative_id = '$coop' AND type = '$quote'
        ")[0]->quotations;

        $returned_goods = \DB::select(

            "select sum(amount) as returns_value from returned_items where cooperative_id = '$coop';"
        )[0]->returns_value;

        return view('pages.cooperative.sales.pos.reports', compact('invoices', 'quotations', 'payments','pending_payments', 'quotes', 'returned_goods'));
    }
}
