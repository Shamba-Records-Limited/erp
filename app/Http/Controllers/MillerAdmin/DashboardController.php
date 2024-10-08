<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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


        $data = [
            "coffee_in_marketplace" => $coffee_in_marketplace,
            "milled_series" => $milled_series,
            "pre_milled_series" => $pre_milled_series,
            "grade_distribution" => $grade_distribution,
            "income_series" => $income_series,
            "expenses_series" => $expenses_series,
            "inventory_series" => $inventory_series,
            "sales_series" => $sales_series,
        ];

        return view('pages.miller-admin.dashboard', compact("data", "date_range", "from_date", "to_date"));
    }
}
