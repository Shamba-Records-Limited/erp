<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
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

        $duration_days=(strtotime($to_date) - strtotime($from_date)) / (24 * 60 * 60);
       // dd($from_date, $to_date,$date_range);

        $suggested_chart_mode = "daily";
        // to 60 days
        if ($duration_days < 60) {
            $suggested_chart_mode = "daily";
        } elseif ($duration_days < 120) {
            $suggested_chart_mode = "weekly";
        } elseif ($duration_days < 365) { // 1 year or less
            $suggested_chart_mode = "monthly";
        } else { // More than 1 year
            $suggested_chart_mode = "yearly";
        }

        $milled_series = [];
        $pre_milled_series = [];
        $income_series = [];
        $expenses_series = [];
        $sales_series=[];
        if ($suggested_chart_mode == "daily") {
            $dailyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT :from_date AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 DAY)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 DAY) <= :to_date
                )
                SELECT date_series.date AS x,
                    (
                        SELECT {}
                        FROM {}
                        WHERE {}
                    ) AS y
                FROM date_series
                GROUP BY date_series.date;
            ";

            $milledQuery = erpStrFormat($dailyQuery, [
                "IFNULL(SUM(inv.milled_quantity), 0)",
                "milled_inventories inv",
                "inv.created_at = date_series.date AND inv.miller_id = :miller_id"
            ]);

            $milled_series = DB::select(DB::raw($milledQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $preMilledQuery = erpStrFormat($dailyQuery, [
                "IFNULL(SUM(inv.quantity), 0)",
                "pre_milled_inventories inv",
                "inv.created_at = date_series.date AND inv.miller_id = :miller_id"
            ]);

            $pre_milled_series = DB::select(DB::raw($preMilledQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $incomeQuery = erpStrFormat($dailyQuery, [
                "IFNULL(SUM(t.amount), 0)",
                "transactions t",
                "CAST(t.completed_at AS DATE) = date_series.date AND t.recipient_id = :miller_id"
            ]);

            $income_series = DB::select(DB::raw($incomeQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $expensesQuery = erpStrFormat($dailyQuery, [
                "IFNULL(SUM(t.amount), 0)",
                "transactions t",
                "CAST(t.completed_at AS DATE) = date_series.date AND t.sender_id = :miller_id"
            ]);

            $expenses_series = DB::select(DB::raw($expensesQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $salesQuery = erpStrFormat($dailyQuery, [
                "IFNULL(SUM(inv_item.quantity), 0)",
                "new_invoice_items inv_item",
                "inv_item.created_at = date_series.date AND (SELECT inv.miller_id FROM new_invoices inv WHERE inv_item.new_invoice_id = inv.id) = :miller_id"
            ]);

            $sales_series = DB::select(DB::raw($salesQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);


            $ordersQuery = erpStrFormat($dailyQuery, [
                "IFNULL(count(odr.id), 0)",
                "miller_auction_order odr",
                "odr.created_at = date_series.date AND odr.miller_id = :miller_id"
            ]);
            

            $orders_series = DB::select(DB::raw($ordersQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);


        }

        if ($suggested_chart_mode == "monthly") {
            $monthlyQuery = "
                         WITH RECURSIVE date_series AS (
                    SELECT :from_date AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 MONTH)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 MONTH) <= :to_date
                )
                SELECT DATE_FORMAT(date_series.date, '%Y-%m') AS x, -- Month
                    (
                        SELECT IFNULL(SUM(inv.milled_quantity), 0) -- Aggregate function
                        FROM milled_inventories inv -- Table name
                        WHERE DATE_FORMAT(inv.created_at, '%Y-%m') = DATE_FORMAT(date_series.date, '%Y-%m') -- Date match
                        AND inv.miller_id = :miller_id
                    ) AS y
                FROM date_series
                GROUP BY date_series.date, x;
             ";
          
        
            $milledQuery = erpStrFormat($monthlyQuery, [
                "IFNULL(SUM(inv.milled_quantity), 0)",
                "milled_inventories inv",
                "MONTH(inv.created_at) = MONTH(date_series.date) AND YEAR(inv.created_at) = YEAR(date_series.date) AND inv.miller_id = :miller_id"
            ]);
        
            $milled_series = DB::select(DB::raw($milledQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);
        
            // Repeat for other queries: preMilledQuery, incomeQuery, expensesQuery, salesQuery
            $preMilledQuery = erpStrFormat($monthlyQuery, [
                "IFNULL(SUM(inv.quantity), 0)",
                "pre_milled_inventories inv",
                 "MONTH(inv.created_at) = MONTH(date_series.date) AND YEAR(inv.created_at) = YEAR(date_series.date) AND inv.miller_id = :miller_id"
            ]);

            $pre_milled_series = DB::select(DB::raw($preMilledQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $incomeQuery = erpStrFormat($monthlyQuery, [
                "IFNULL(SUM(t.amount), 0)",
                "transactions t",
                "MONTH(inv.t.completed_at as DATE) = MONTH(date_series.date) AND YEAR(t.completed_at) = YEAR(date_series.date) AND t.recipient_id = :miller_id"
            ]);

            $income_series = DB::select(DB::raw($incomeQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $expensesQuery = erpStrFormat($monthlyQuery, [
                "IFNULL(SUM(t.amount), 0)",
                "transactions t",
                "MONTH(t.completed_at as DATE) = MONTH(date_series.date) AND YEAR(t.completed_at) = YEAR(date_series.date) AND t.sender_id = :miller_id"

            ]);

            $expenses_series = DB::select(DB::raw($expensesQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $salesQuery = erpStrFormat($monthlyQuery, [
                "IFNULL(SUM(inv_item.quantity), 0)",
                "new_invoice_items inv_item",
                "MONTH(inv_item.created_at) = MONTH(date_series.date) AND YEAR(date_series.date) = YEAR(inv_item.created_at)  AND (SELECT inv.miller_id FROM new_invoices inv WHERE inv_item.new_invoice_id = inv.id) = :miller_id"
            ]);

            $sales_series = DB::select(DB::raw($salesQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);


            $ordersQuery = erpStrFormat($monthlyQuery, [
                "IFNULL(count(odr.id), 0)",
                "miller_auction_order odr",
                "MONTH(odr.created_at) = MONTH(date_series.date) AND YEAR(date_series.date) = YEAR(odr.created_at) AND odr.miller_id = :miller_id"
            ]);
            

            $orders_series = DB::select(DB::raw($ordersQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

        }

        if ($suggested_chart_mode == "yearly") {
            $yearlyQuery = "
                            WITH RECURSIVE date_series AS (
                                SELECT :from_date AS date
                                UNION ALL
                                SELECT DATE_ADD(date, INTERVAL 1 YEAR)
                                FROM date_series
                                WHERE DATE_ADD(date, INTERVAL 1 YEAR) <= :to_date
                            )
                            SELECT 
                                YEAR(date_series.date) AS x, -- Year
                                (
                                    SELECT IFNULL(SUM(inv.milled_quantity), 0) -- Aggregate function
                                    FROM milled_inventories inv
                                    WHERE YEAR(inv.created_at) = x -- Use the grouped year directly
                                    AND inv.miller_id = :miller_id
                                ) AS y
                            FROM date_series
                            GROUP BY x; -- Group by year
                ";
        
            $milledQuery = erpStrFormat($yearlyQuery, [
                "IFNULL(SUM(inv.milled_quantity), 0)",
                "milled_inventories inv",
                "YEAR(inv.created_at) = YEAR(date_series.date) AND inv.miller_id = :miller_id"
            ]);
        
            $milled_series = DB::select(DB::raw($milledQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);
        
            // Repeat for other queries: preMilledQuery, incomeQuery, expensesQuery, salesQuery
            $preMilledQuery = erpStrFormat($yearlyQuery, [
                "IFNULL(SUM(inv.quantity), 0)",
                "pre_milled_inventories inv",
                "YEAR(inv.created_at) = YEAR(date_series.date) AND inv.miller_id = :miller_id"
            ]);

            $pre_milled_series = DB::select(DB::raw($preMilledQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $incomeQuery = erpStrFormat($yearlyQuery, [
                "IFNULL(SUM(t.amount), 0)",
                "transactions t",
                "YEAR(t.completed_at) = YEAR(date_series.date) AND t.recipient_id = :miller_id"
            ]);

            $income_series = DB::select(DB::raw($incomeQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $expensesQuery = erpStrFormat($yearlyQuery, [
                "IFNULL(SUM(t.amount), 0)",
                "transactions t",
                "YEAR(t.completed_at) = YEAR(date_series.date) AND t.sender_id = :miller_id"

            ]);

            $expenses_series = DB::select(DB::raw($expensesQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $salesQuery = erpStrFormat($yearlyQuery, [
                "IFNULL(SUM(inv_item.quantity), 0)",
                "new_invoice_items inv_item",
                "YEAR(inv_item.created_at) = YEAR(date_series.date)  AND (SELECT inv.miller_id FROM new_invoices inv WHERE inv_item.new_invoice_id = inv.id) = :miller_id"
            ]);

            $sales_series = DB::select(DB::raw($salesQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);

            $ordersQuery = erpStrFormat($yearlyQuery, [
                "IFNULL(count(odr.id), 0)",
                "miller_auction_order odr",
                "YEAR(odr.created_at) = (date_series.date) AND YEAR(date_series.date) = YEAR(odr.created_at) AND odr.miller_id = :miller_id"
            ]);
            

            $orders_series = DB::select(DB::raw($ordersQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "miller_id" => $miller_id
            ]);
        }
        
        //dd($suggested_chart_mode);
      

        $coffee_in_marketplace = DB::select(DB::raw("
            SELECT sum(l.available_quantity) AS total FROM lots l
            where
                (SELECT count(1) FROM miller_auction_order_item item
                    WHERE item.lot_number = l.lot_number
                ) = 0
        "))[0]->total;

        // coffee grade distribution
        $grade_distribution = DB::select(DB::raw("
            SELECT SUM(quantity) AS quantity, pg.name AS name
            FROM milled_inventory_grades mig
            JOIN product_grades pg ON pg.id = mig.product_grade_id
            JOIN milled_inventories m ON m.id = mig.milled_inventory_id 
            WHERE m.miller_id = :miller_id
            GROUP BY mig.product_grade_id
            ORDER BY quantity DESC
        "), ["miller_id" => $miller_id]);

        // todo: continue here

        // combination of milled and pre-milled
        $inventory_series = [];
        for ($i = 0; $i < count($milled_series); $i++) {
            $x = $pre_milled_series[$i]->x;

            $y = $pre_milled_series[$i]->y + $milled_series[$i]->y;

            $inventory_series[] = [
                "x" => $x,
                "y" => $y
            ];
        }
            //remaining coffe
     $totalRemainingLot = DB::select(DB::raw("
            SELECT SUM(remaining_quantity) AS total_remaining_quantity
            FROM (
                SELECT 
                    (l.available_quantity - COALESCE(s.sold_quantity, 0)) AS remaining_quantity
                FROM lots l
                LEFT JOIN (
                    SELECT 
                        lot_number, 
                        SUM(quantity) AS sold_quantity
                    FROM miller_auction_order_item
                    GROUP BY lot_number
                ) s ON s.lot_number = l.lot_number
            ) subquery;
        "));
        
        $totalRemainingQuantity=$totalRemainingLot[0]->total_remaining_quantity ?? 0 ;
        
        $totalPercentageChange = DB::select(DB::raw("
        SELECT 
            -- Calculate total quantity for this month and last month
            CASE 
                WHEN last_month.total_qty = 0 THEN NULL
                ELSE ((current_month.total_qty - last_month.total_qty) / last_month.total_qty) * 100
            END AS total_percentage_change
        FROM (
            -- Subquery for current month's total quantity
            SELECT 
                SUM(item.quantity) AS total_qty
            FROM miller_auction_cart_item item
            WHERE MONTH(item.created_at) = MONTH(CURRENT_DATE)
            AND YEAR(item.created_at) = YEAR(CURRENT_DATE)
        ) current_month,
        (
            -- Subquery for last month's total quantity
            SELECT 
                SUM(item.quantity) AS total_qty
            FROM miller_auction_cart_item item
            WHERE MONTH(item.created_at) = MONTH(CURRENT_DATE) - 1
            AND YEAR(item.created_at) = YEAR(CURRENT_DATE)
        ) last_month
    "), []);
             $totalPercentageLot=$totalPercentageChange[0]->total_percentage_change;
            // Handle the case where there were no lots last month
            $percentageChangeLot = $totalPercentageLot ?? 0;

        //2.current orders
        // Get the start and end timestamps for today and yesterday
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();

            $yesterdayStart = Carbon::yesterday()->startOfDay();
            $yesterdayEnd = Carbon::yesterday()->endOfDay();     
        $orderCounts = DB::table('miller_auction_order as mac')
             ->selectRaw("
                 SUM(CASE WHEN mac.created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as today_count,
                 SUM(CASE WHEN mac.created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as yesterday_count
             ", [
                 $todayStart, $todayEnd,  // Today
                 $yesterdayStart, $yesterdayEnd  // Yesterday
             ])
             ->where('mac.miller_id', '=', $miller_id)
             ->first();
            // Check if the query returned results
            if ($orderCounts) {
                // Extract counts, defaulting to 0 if not available
                $today_count = $orderCounts->today_count ?? 0;
                $yesterday_count = $orderCounts->yesterday_count ?? 0;

                // Calculate the percentage change
                if ($yesterday_count > 0) {
                    $order_percent = (($today_count - $yesterday_count) / $yesterday_count) * 100;
                } else {
                    $order_percent = $today_count > 0 ? 100 : 0; // If no orders yesterday, consider 100% increase if today has orders
                }
            } else {
                // Default values if no data is returned
                $today_count = 0;
                $yesterday_count = 0;
                $order_percent = 0;
            }
             $total_count=$today_count+$yesterday_count;
            // Initialize count

        $data = [
            "coffee_in_marketplace" => $coffee_in_marketplace,
            "milled_series" => $milled_series,
            "pre_milled_series" => $pre_milled_series,
            "grade_distribution" => $grade_distribution,
            "income_series" => $income_series,
            "expenses_series" => $expenses_series,
            "inventory_series" => $inventory_series,
            "sales_series" => $sales_series,
            "total_remaining_quantity"=>$totalRemainingQuantity,
            "count_order"=>$total_count,
            "order_percent" => $order_percent,
            "orders_series"=> $orders_series ,
            "percentageRemaining"=>round($percentageChangeLot,2),
        ];
        
      

        return view('pages.miller-admin.dashboard', compact("data", "date_range", "from_date", "to_date"));
    }
}
