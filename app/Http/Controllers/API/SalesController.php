<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function pending_sales(): \Illuminate\Http\JsonResponse
    {
        $cooperative = Auth::user()->cooperative->id;
        $pending_sales = DB::select("
            SELECT s.id, concat(u.first_name,' ',u.other_names) as served_by, s.date, si.discount, i.invoice_number,
                   s.sale_batch_number, si.amount, c.currency,si.quantity FROM sales s 
            LEFT JOIN sale_items si on s.id = si.sales_id    
            LEFT JOIN invoices i on s.id = i.sale_id              
            JOIN users u on s.user_id = u.id
            JOIN cooperatives c on s.cooperative_id = c.id                                                                          
            WHERE s.deleted_at is null AND s.type = 'sale' AND (i.status = 1 OR i.status = 0) AND s.cooperative_id = '$cooperative';
        ");

        return response()->json([
            "success" => true,
            "message" => "Success",
            "data" => $pending_sales
        ]);
    }

    public function total_sales(): \Illuminate\Http\JsonResponse
    {
        $cooperative = Auth::user()->cooperative;
        $date = Carbon::now()->subYear();
        $total_sales = DB::select("
            SELECT sum(si.quantity * si.quantity) as total_sales  FROM sale_items si 
            LEFT JOIN sales s on s.id = si.sales_id    
            LEFT JOIN invoices i on s.id = i.sale_id              
            JOIN users u on s.user_id = u.id
            JOIN cooperatives c on s.cooperative_id = c.id                                                                          
            WHERE s.deleted_at is null AND s.type = 'sale'
            AND s.cooperative_id = '$cooperative->id' AND si.created_at >= '$date';
        ")[0]->total_sales;
        return response()->json([
            "success" => true,
            "message" => "Success",
            "data" => ["total_sales" => $total_sales, "currency" => $cooperative->currency]
        ]);

    }
}
