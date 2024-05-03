<?php

namespace App\Http\Controllers;

use App\Product;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductManagementDashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function get_stats(Request $request)
    {
        $cooperative = Auth::user()->cooperative_id;
        return json_encode(
            [
                "product_supply_trend" => $this->highest_collected_product($cooperative, 15, $request),
                "products" => $this->get_product_collection($cooperative, $request)
            ]
        );
    }

    public function index(Request $request)
    {

        $cooperative = Auth::user()->cooperative_id;
        $units = Unit::where('cooperative_id', $cooperative)->count();;
        $products = Product::select('id')->where('cooperative_id', $cooperative);

        if($request->products){
            $products = $products->whereIn('id', $request->products);
        }
        $products = $products->count();
        $suppliers = $this->get_total_suppliers($cooperative, $request);
        $most_supplied = $this->get_most_supplied_products($cooperative, $request);

        $highest_collected = $this->highest_collected_product($cooperative,1, $request);
        if(empty($highest_collected)){
            $highest_collected  = null;
        }else{
            $highest_collected =  $highest_collected[0];
        }
        $data = (object)[
            "most_supplied" => count($most_supplied) > 0 ? $most_supplied[0] : null,
            "units" => $units,
            "products" => $products,
            "suppliers" => $suppliers,
            'highest_supply' => $highest_collected
        ];

        $products = Product::select('name', 'id')->where('cooperative_id', $cooperative)->orderBy('name')->get();
        return view('pages.cooperative.minidashboards.product-management', compact('data', 'products'));
    }


    private function get_total_suppliers($cooperative, $request): int
    {
        $query = "select count(distinct fp.farmer_id) as farmers_count
                    from farmers_products fp
                    join farmers f on fp.farmer_id = f.user_id
                    join users u on f.user_id = u.id
                    where u.cooperative_id ='$cooperative'";

        if($request->products){
            $products = formatArrayForSQL($request->products);
            $query .= " and fp.product_id in $products";
        }

//        if($request->date) {
//            $dates = split_dates($request->date);
//            $from = $dates['from'];
//            $to = $dates['to'];
//            $query .= " and u.created_at between '$from' and '$to'";
//        }

        $data = DB::select($query);

        if(empty($data)){
            return 0;
        }else{
            return $data[0]->farmers_count;
        }
    }

    private function get_most_supplied_products($cooperative, $request): array
    {
        $query = "
        SELECT count(p.id) AS total, p.name FROM farmers_products fp  LEFT JOIN products p
                    ON p.id = fp.product_id WHERE fp.farmer_id IN (
                SELECT id FROM users WHERE cooperative_id = '$cooperative'
            )
        ";

        if($request->products){
            $products = formatArrayForSQL($request->products);
            $query .= " and p.id in $products";
        }

//        if($request->date) {
//            $dates = split_dates($request->date);
//            $from = $dates['from'];
//            $to = $dates['to'];
//            $query .= " and p.created_at between '$from' and '$to'";
//        }


        return  DB::select(
            $query." GROUP BY p.id ORDER BY total DESC limit 1 "
        );

    }

    private function get_product_collection($cooperative, $request)
    {

        $query = "
         SELECT SUM(c.quantity) as quantity, SUM(c.available_quantity) as available,
            p.name as product from collections c join products p on c.product_id = p.id
            WHERE c.cooperative_id = '$cooperative'";
        if($request->products){
            $products = formatArrayForSQL($request->products);
            $query .= " AND p.id in $products";
        }

        if($request->date){
            $dates = split_dates($request->date);
            $start = $dates['from'];
            $end = $dates['to'];
        }else{
            $end = Carbon::now()->format('Y-m-d');
            $start = Carbon::now()->subMonths(12)->format('Y-m-d');
        }
      return  DB::select($query. " AND c.date_collected BETWEEN '$start' AND '$end'
            GROUP BY product ORDER BY product LIMIT 15 ");
    }


    private function highest_collected_product($cooperative, $limit, $request=null){

        $query = "  select COUNT(c.product_id) as total, p.name from collections c
        join products p on c.product_id = p.id
        where c.cooperative_id = '$cooperative'";
        if($request->products){
            $products = formatArrayForSQL($request->products);
            $query .= " and p.id in $products";
        }

        if($request->date){
            $dates = split_dates($request->date);
            $from = $dates['from'];
            $to = $dates['to'];
            $query .= " AND (c.date_collected between '$from' AND '$to')";
        }
        return  DB::select($query." GROUP BY p.name ORDER BY total DESC LIMIT $limit ");
    }
}
