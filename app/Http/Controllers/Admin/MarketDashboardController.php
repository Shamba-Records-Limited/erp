<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketDashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        try {
            $coop_id = $user->cooperative->id;
        } catch (\Throwable $th) {
            $coop_id=null;
        }
         
        $miller_id=null;
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

        $milled_series = [];
        $pre_milled_series = [];
        $income_series = [];
        $expenses_series = [];
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
        }


        $coffee_in_marketplace = DB::select(DB::raw("
            SELECT sum(l.available_quantity) AS total FROM lots l
            where
                (SELECT count(1) FROM miller_auction_order_item item
                    WHERE item.lot_number = l.lot_number
                ) = 0
        "))[0]->total;

        //Charts data

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
        $salesChart = DB::table('sales')
        ->select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM(paid_amount) as total_sales")
        )
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->get();

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

        //Available stock 
        $available_stock = DB::select(DB::raw("
            SELECT SUM(l.available_quantity) AS quantity
            FROM lots l
            LEFT JOIN (
                SELECT lot_number, SUM(quantity) AS total_quantity
                FROM miller_auction_order_item
                GROUP BY lot_number
            ) item_summary
            ON l.lot_number = item_summary.lot_number
            WHERE (item_summary.lot_number IS NULL OR item_summary.total_quantity < l.available_quantity)
            AND l.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        "));

        $stock_available = $available_stock[0]->quantity ?? 0; // Use null coalescing to avoid errors if the 
        
        // Calculate the sum of quantities for this week
            $thisWeekQuantity = DB::table('lots as l')
            ->leftJoinSub(
                DB::table('miller_auction_order_item')
                    ->select('lot_number', DB::raw('SUM(quantity) AS total_quantity'))
                    ->groupBy('lot_number'),
                'item_summary',
                'l.lot_number',
                '=',
                'item_summary.lot_number'
            )
            ->where(function ($query) {
                $query->whereNull('item_summary.lot_number')
                    ->orWhereRaw('item_summary.total_quantity < l.available_quantity');
            })
            ->whereBetween('l.created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('l.available_quantity');

            // Calculate the sum of quantities for last week
            $lastWeekQuantity = DB::table('lots as l')
            ->leftJoinSub(
                DB::table('miller_auction_order_item')
                    ->select('lot_number', DB::raw('SUM(quantity) AS total_quantity'))
                    ->groupBy('lot_number'),
                'item_summary',
                'l.lot_number',
                '=',
                'item_summary.lot_number'
            )
            ->where(function ($query) {
                $query->whereNull('item_summary.lot_number')
                    ->orWhereRaw('item_summary.total_quantity < l.available_quantity');
            })
            ->whereBetween('l.created_at', [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek()
            ])
            ->sum('l.available_quantity');

            // Calculate percentage change
            if ($lastWeekQuantity > 0) {
            $stock_percent= (($thisWeekQuantity - $lastWeekQuantity) / $lastWeekQuantity) * 100;
            } else {
            $stock_percent=100; // Or set to 100% if there's no stock last week
            }
            
         //toatal sales since ast month
         $sales = DB::select(DB::raw("
                SELECT SUM(paid_amount) AS total_sales
                FROM sales
                WHERE miller_id = :miller_id
                AND created_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01')
            "), [
                'miller_id' => $miller_id,
            ]);
            // Access the total_sales value
            $totalSales = $result[0]->total_sales ?? 0;

            // Total sales for this month
                $thisMonthResult = DB::select(DB::raw("
                SELECT SUM(paid_amount) AS total_sales
                FROM sales
                WHERE miller_id = :miller_id
                AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                "), [
                'miller_id' => $miller_id,
                ]);

                $thisMonthSales = $thisMonthResult[0]->total_sales ?? 0;

                // Total sales for last month
                $lastMonthResult = DB::select(DB::raw("
                SELECT SUM(paid_amount) AS total_sales
                FROM sales
                WHERE miller_id = :miller_id
                AND created_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01')
                AND created_at < DATE_FORMAT(CURDATE(), '%Y-%m-01')
                "), [
                'miller_id' => $miller_id,
                ]);

                $lastMonthSales = $lastMonthResult[0]->total_sales ?? 0;

                // Calculate percentage change
                if ($lastMonthSales > 0) {
                $sale_percent = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
                } else {
                    $sale_percent  = $thisMonthSales > 0 ? 100 : 0; // If no sales last month, set change to 100% or 0%
                }

            // Count total orders approved by the specified miller from last week to today
            $totalOrders = DB::select(DB::raw("
                SELECT COUNT(*) AS total_orders
                FROM auction_order_delivery
                WHERE approved_by = :miller_id
                AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
            "), [
                'miller_id' => $miller_id,
            ]);
            // Access the total_orders value
            $totalOrdersCount = $totalOrders[0]->total_orders ?? 0;


            // Get the total orders for this week
            $thisWeekTotal = DB::select(DB::raw("
            SELECT COUNT(*) AS total_orders
            FROM auction_order_delivery
            WHERE approved_by = :miller_id
            AND created_at >= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY
            AND created_at < CURDATE() + INTERVAL 1 DAY
            "), ['miller_id' => $miller_id]);

            // Get the total orders for last week
            $lastWeekTotal = DB::select(DB::raw("
            SELECT COUNT(*) AS total_orders
            FROM auction_order_delivery
            WHERE approved_by = :miller_id
            AND created_at >= CURDATE() - INTERVAL (WEEKDAY(CURDATE()) + 7) DAY
            AND created_at < CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY
            "), ['miller_id' => $miller_id]);

            // Access the total orders count for this week and last week
            $thisWeekOrders = $thisWeekTotal[0]->total_orders ?? 0;
            $lastWeekOrders = $lastWeekTotal[0]->total_orders ?? 0;

            // Calculate percentage change if last week orders exist
            $order_percent = 0;
            if ($lastWeekOrders > 0) {
                $order_percent  = (($thisWeekOrders - $lastWeekOrders) / $lastWeekOrders) * 100;
            }

            //Product Revenue graph data
            $prodrevdata = DB::table('farmer_auction_order_items as items')
            ->join('products as prd', 'items.product_id', '=', 'prd.id')
            ->select(
                'prd.name as product_name',
                DB::raw('SUM(items.quantity * items.selling_price) as revenue')
            )
            //->where('prd.miller_id', $miller_id) // Add the where clause for miller_id
            ->groupBy('prd.name')
            ->get();
            // Prepare data for the chart
            $prodlabels = $prodrevdata->pluck('product_name');
            $prodrevenues = $prodrevdata->pluck('revenue');

       // Fetch revenue grouped by product category for pie chart
                $prodcatdata = DB::table('farmer_auction_order_items as items')
                ->join('products as prd', 'items.product_id', '=', 'prd.id')
                ->join('product_categories as cat', 'prd.category_id', '=', 'cat.id')
                ->select(
                    'cat.name as category_name',
                    DB::raw('SUM(items.quantity * items.selling_price) as total_revenue')
                )
              //  ->where('prd.miller_id', $miller_id) // Add the where clause for miller_id
                ->groupBy('cat.name') // Ensure grouping is by category name
                ->get();
            // Extract labels (categories) and data (revenue) for the chart
            $prodcatlabels = $prodcatdata->pluck('category_name')->toArray();
            $prodcatrevenues = $prodcatdata->pluck('total_revenue')->toArray();
           // dd($prodcatdata,$prodcatlabels,$prodcatrevenues);


           //
           $salesData = DB::table('sales')
                        ->select(
                            DB::raw("CASE 
                                        WHEN sales.cooperative_id IS NULL THEN 'Miller'
                                        WHEN sales.miller_id IS NULL THEN 'Cooperative'
                                    END AS entity_type"),
                            DB::raw('SUM(sales.paid_amount) as total_paid_amount'),
                            DB::raw('SUM(sales.balance) as total_balance'),
                            DB::raw('SUM(sales.discount) as total_discount'),
                            DB::raw('COUNT(sales.id) as total_sales_count')
                        )
                        ->groupBy('entity_type')
                        ->get();


        $data = [
            "coffee_in_marketplace" => $coffee_in_marketplace,
            "milled_series" => $milled_series,
            "pre_milled_series" => $pre_milled_series,
            "grade_distribution" => $grade_distribution,
            "income_series" => $income_series,
            "expenses_series" => $expenses_series,
            "inventory_series" => $inventory_series,
            "sales_series" => $sales_series,
            'stock_availabe'=>$stock_available,
            'stock_percent'=> round($stock_percent,2),
            'totalsales'=> $totalSales,
            'sale_percent'=>$sale_percent,
            'totalOrdersCount'=> $totalOrdersCount,
            'order_percent'=>$order_percent,
            'salesChart' => $salesChart,
            'prodlabels'=> $prodlabels,
            'prodrevenues'=>$prodrevenues,
            'prodcatlabels'=>$prodcatlabels,
            'prodcatrevenues'=>$prodcatrevenues,
            'salesData'=>$salesData,


        ];

        return view('pages.admin.market-auction.dashboard',compact('data'));
    }
}
