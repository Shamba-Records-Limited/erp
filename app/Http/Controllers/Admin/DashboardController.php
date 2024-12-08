<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $date_range = $request->query("date_range", "week");
        $from_date = $request->query("from_date", "");
        $to_date = $request->query("to_date", "");

        // total collection weight
        $totalCollectionWeight = DB::select(DB::raw("
            SELECT 
                SUM(quantity) AS quantity
            FROM collections
        "))[0]->quantity;

        // farmer count
        $farmerCount = DB::select(DB::raw("
            SELECT 
                COUNT(1) AS count
            FROM farmer_cooperative
        "))[0]->count;

        // collection count
        $collectionCount = DB::select(DB::raw("
            SELECT 
                COUNT(1) AS count
            FROM collections
        "))[0]->count;

        // Age Distribution Logic
        $age_distribution = DB::select(DB::raw("
        SELECT 
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, f.dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                WHEN TIMESTAMPDIFF(YEAR, f.dob, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
                WHEN TIMESTAMPDIFF(YEAR, f.dob, CURDATE()) BETWEEN 36 AND 45 THEN '36-45'
                WHEN TIMESTAMPDIFF(YEAR, f.dob, CURDATE()) BETWEEN 46 AND 55 THEN '46-55'
                WHEN TIMESTAMPDIFF(YEAR, f.dob, CURDATE()) BETWEEN 56 AND 65 THEN '56-65'
                ELSE '66+' 
            END AS age_group,
            COUNT(*) AS quantity
        FROM farmers f
        GROUP BY age_group
        ORDER BY 
            CASE 
                WHEN age_group = '18-25' THEN 1
                WHEN age_group = '26-35' THEN 2
                WHEN age_group = '36-45' THEN 3
                WHEN age_group = '46-55' THEN 4
                WHEN age_group = '56-65' THEN 5
                WHEN age_group = '66+' THEN 6
            END
    "));

        // collection over time
        $date_range = $request->query("date_range", "week");
        $from_date = $request->query("from_date", "");
        $to_date = $request->query("to_date", "");
        $from_date_prev = "";
        $to_date_prev = "";
        $prevCollections = [];

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


        $collections = [];
        $maleCollections = [];
        $femaleCollections = [];
        $otherGenderCollections = [];
        $collectionsByCooperative = [];
        $myQuery = "";
        if ($suggested_chart_mode == "daily") {
            $myQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT :from_date AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 DAY)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 DAY) <= :to_date
                )
                SELECT date_series.date AS x,
                    (
                        SELECT IFNULL(SUM(c.quantity), 0)
                        FROM collections c
                        JOIN farmers f ON f.id = c.farmer_id
                        WHERE c.date_collected = date_series.date AND
                            CASE WHEN :gender = 'all' THEN 1 ELSE f.gender = :gender1 END AND
                            CASE WHEN :coop = 'all' THEN 1 ELSE c.cooperative_id = :coop1 END
                    ) AS y
                FROM date_series
                GROUP BY date_series.date;
            ";

            $gender = "all";
            $coop = "all";
            $collections = DB::select(DB::raw($myQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop,
                "coop1" => $coop,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($myQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
                    "coop" => $coop,
                    "coop1" => $coop,
                ]);
            }

            $gender = "M";
            $maleCollections = DB::select(DB::raw($myQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop,
                "coop1" => $coop,
            ]);

            $gender = "F";
            $femaleCollections = DB::select(DB::raw($myQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop,
                "coop1" => $coop,
            ]);

            $gender = "X";
            $otherGenderCollections = DB::select(DB::raw($myQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop,
                "coop1" => $coop,
            ]);
        } else if ($suggested_chart_mode == "monthly") {
            $myQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT DATE_FORMAT(:from_date, '%Y-%b') AS month_year, :from_date1 AS date
                    UNION ALL
                    SELECT DATE_FORMAT(DATE_ADD(date, INTERVAL 1 MONTH), '%Y-%b'), DATE_ADD(date, INTERVAL 1 MONTH)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 MONTH) <= DATE_ADD(:to_date, INTERVAL 1 MONTH)
                )
                SELECT date_series.month_year AS x, 
                    (
                        SELECT IFNULL(SUM(c.quantity), 0)
                        FROM collections c
                        JOIN farmers f ON f.id = c.farmer_id
                        WHERE DATE_FORMAT(c.date_collected, '%Y-%b') = date_series.month_year AND
                            CASE WHEN :gender = 'all' THEN 1 ELSE f.gender = :gender1 END AND
                            CASE WHEN :coop = 'all' THEN 1 ELSE c.cooperative_id = :coop1 END
                    ) AS y
                FROM date_series
                GROUP BY date_series.month_year;
            ";
            $gender = "all";
            $coop = "all";
            $collections = DB::select(DB::raw($myQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop,
                "coop1" => $coop,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($myQuery), [
                    "from_date" => $from_date_prev,
                    "from_date1" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
                    "coop" => $coop,
                    "coop1" => $coop,
                ]);
            }

            $gender = 'M';
            $maleCollections = DB::select(DB::raw($myQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop,
                "coop1" => $coop,
            ]);

            $gender = 'F';
            $femaleCollections = DB::select(DB::raw($myQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop,
                "coop1" => $coop,
            ]);
        }

        // collections by cooperative
        $coops = DB::select(DB::raw("SELECT id, name FROM cooperatives"));
        $gender = "all";
        foreach ($coops as $coop) {
            $coop_id = $coop->id;

            $params = [
                "from_date" => $from_date,
                // "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop" => $coop_id,
                "coop1" => $coop_id
            ];

            if ($suggested_chart_mode == "monthly") {
                $params["from_date1"] = $from_date;
            }

            $collectionsByCooperative[$coop->name] = DB::select(DB::raw($myQuery), $params);
        }
      
        // cooperatives count
        $cooperativesCount = DB::select(DB::raw("SELECT COUNT(*) AS count FROM cooperatives"))[0]->count;

        // coffee grade distribution
        $grade_distribution = DB::select(DB::raw("
            SELECT SUM(quantity) AS quantity, pg.name AS name
            FROM lot_grade_distributions lgd
            JOIN product_grades pg ON pg.id = lgd.product_grade_id
            JOIN lots l ON l.lot_number = lgd.lot_number 
            WHERE l.cooperative_id = :coop_id
            GROUP BY lgd.product_grade_id
            ORDER BY quantity DESC
        "), ["coop_id" => $coop_id]);

        $genderDistribution = DB::table('farmers')
        ->select('gender', DB::raw('COUNT(*) as count'))
        ->groupBy('gender')
        ->get();
      // dd($genderDistribution);
        // Separate data into male and female
        $male_collections_pie = $genderDistribution->where('gender', 'M')->pluck('count');
        $female_collections_pie = $genderDistribution->where('gender', 'F')->pluck('count');
        //Raw material quantity
        $raw_material_count = DB::select(DB::raw("
            SELECT 
                COUNT(*) AS total_count,
                SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN quantity ELSE 0 END) AS this_month_quantity,
                SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN quantity ELSE 0 END) AS last_month_quantity,
                -- Calculate percentage change
                CASE 
                    WHEN SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN quantity ELSE 0 END) = 0 
                    THEN NULL -- Avoid division by zero
                    ELSE (SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN quantity ELSE 0 END) 
                        - SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN quantity ELSE 0 END)) 
                        / SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN quantity ELSE 0 END) * 100 
                END AS percentage_change
            FROM raw_material_inventories
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)
        "));

        $raw_percent = $raw_material_count[0]->percentage_change ?? 0;

        $raw_materials_qnty = DB::select(DB::raw("
            SELECT 
                SUM(quantity) AS total_quantity
            FROM raw_material_inventories
            WHERE created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
            AND created_at < CURDATE()
        "));

        $raw_materialsqnty = $raw_materials_qnty[0]->total_quantity ?? 0;   
      



         //Farmers Cahnge
         $farmer_count_comparison = DB::select(DB::raw("
         SELECT
             -- Farmer count for this week
             COUNT(CASE WHEN WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) AS this_week_count,
             
             -- Farmer count for last week
             COUNT(CASE WHEN WEEK(created_at) = WEEK(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) AS last_week_count,
             
             -- Calculate the percentage change
             CASE 
                 WHEN COUNT(CASE WHEN WEEK(created_at) = WEEK(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) = 0 
                 THEN NULL -- Avoid division by zero
                 ELSE (COUNT(CASE WHEN WEEK(created_at) = WEEK(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) 
                       - COUNT(CASE WHEN WEEK(created_at) = WEEK(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END)) 
                       / COUNT(CASE WHEN WEEK(created_at) = WEEK(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) * 100 
             END AS percentage_change
         FROM farmers
         WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 2 WEEK)
     "));
         $farmer_percent = $farmer_count_comparison[0]->percentage_change ?? 0;

         //collection count
         $collections_comparison = DB::select(DB::raw("
            SELECT
                -- Collection count for today
                COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) AS today_count,
                -- Collection count for yesterday
                COUNT(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END) AS yesterday_count,
                -- Calculate the percentage change
                CASE 
                    WHEN COUNT(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END) = 0 
                    THEN NULL -- Avoid division by zero
                    ELSE (COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) 
                        - COUNT(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END)) 
                        / COUNT(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END) * 100 
                END AS percentage_change
            FROM collections
            WHERE created_at >= CURDATE() - INTERVAL 1 DAY
        "));
        $collection_percent = $collections_comparison[0]->percentage_change ?? 0;

        //cooperatives count
        $cooperative_comparison = DB::select(DB::raw("
            SELECT
                -- Count cooperatives for this month
                COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN 1 END) AS this_month_count,
                -- Count cooperatives for last month
                COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END) AS last_month_count,
                -- Calculate the percentage change
                CASE
                    WHEN COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END) = 0
                    THEN NULL -- Avoid division by zero
                    ELSE (COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN 1 END)
                        - COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END))
                        / COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END) * 100
                END AS percentage_change
            FROM cooperatives
            WHERE created_at >= CURDATE() - INTERVAL 2 MONTH
        "));
        $coop_percent = $cooperative_comparison[0]->percentage_change ?? 0;

        $miller_comparison = DB::select(DB::raw("
                SELECT
                    -- Count millers for this month
                    COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN 1 END) AS this_month_count,
                    -- Count millers for last month
                    COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END) AS last_month_count,
                    -- Calculate the percentage change
                    CASE
                        WHEN COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END) = 0
                        THEN NULL -- Avoid division by zero
                        ELSE (COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN 1 END)
                            - COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END))
                            / COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN 1 END) * 100
                    END AS percentage_change
                FROM millers
                WHERE created_at >= CURDATE() - INTERVAL 2 MONTH
            "));

            $millers_percent = $miller_comparison[0]->percentage_change ?? 0;

            $millers_count = DB::select(DB::raw("
                SELECT 
                    COUNT(*) AS total_millers
                FROM millers
                WHERE created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
                AND created_at < CURDATE()
            "));
            $total_millers=$millers_count[0]->total_millers ?? 0;

            $product_comparison = DB::select(DB::raw("
                SELECT 
                    -- Sum of quantities for this month
                    SUM(CASE 
                        WHEN YEAR(created_at) = YEAR(CURDATE()) 
                        AND MONTH(created_at) = MONTH(CURDATE()) THEN quantity 
                        ELSE 0 END) AS this_month_quantity,
                    -- Sum of quantities for last month
                    SUM(CASE 
                        WHEN YEAR(created_at) = YEAR(CURDATE()) 
                        AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN quantity 
                        ELSE 0 END) AS last_month_quantity,
                    -- Calculate percentage change
                    CASE
                        WHEN SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN quantity ELSE 0 END) = 0
                        THEN NULL -- Avoid division by zero
                        ELSE 
                            (SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) THEN quantity ELSE 0 END) 
                            - SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN quantity ELSE 0 END)) 
                            / SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN quantity ELSE 0 END) * 100
                    END AS percentage_change
                FROM final_products
                WHERE created_at >= CURDATE() - INTERVAL 2 MONTH
            "));
            $percent_product = $product_comparison[0]->percentage_change;

            $final_products_quantity = DB::select(DB::raw("
                SELECT 
                    SUM(quantity) AS total_quantity
                FROM final_products
                WHERE created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
                AND created_at < CURDATE()
            "));
            $final_products_qnty =$final_products_quantity[0]->total_quantity ?? 0;

            $final_products_count = DB::select(DB::raw("
                SELECT 
                    SUM(count) AS total_count
                FROM final_products
                WHERE created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
                AND created_at < CURDATE()
            "));
            $final_prod_count = $final_products_count[0]->total_count ?? 0;
        
       //product count

       $product_count_comparison = DB::select(DB::raw("
                SELECT 
                    -- Sum of 'count' for this month
                    SUM(CASE 
                        WHEN YEAR(created_at) = YEAR(CURDATE()) 
                        AND MONTH(created_at) = MONTH(CURDATE()) THEN count 
                        ELSE 0 END) AS this_month_count,
                    -- Sum of 'count' for last month
                    SUM(CASE 
                        WHEN YEAR(created_at) = YEAR(CURDATE()) 
                        AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN count 
                        ELSE 0 END) AS last_month_count,
                    -- Calculate percentage change
                    CASE
                        WHEN SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN count ELSE 0 END) = 0
                        THEN NULL -- Avoid division by zero
                        ELSE 
                            (SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) THEN count ELSE 0 END) 
                            - SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN count ELSE 0 END)) 
                            / SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                                    AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN count ELSE 0 END) * 100
                    END AS percentage_change

                FROM final_products
            "));
        $finalproductcount_percent = $product_count_comparison[0]->percentage_change ?? 0;

              //total sales
      $sales_comparison = DB::select(DB::raw("
      SELECT 
          -- Total sales for this month
          SUM(CASE 
              WHEN YEAR(created_at) = YEAR(CURDATE()) 
              AND MONTH(created_at) = MONTH(CURDATE()) THEN (paid_amount - balance) 
              ELSE 0 END) AS this_month_sales,
          -- Total sales for last month
          SUM(CASE 
              WHEN YEAR(created_at) = YEAR(CURDATE()) 
              AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN (paid_amount - balance) 
              ELSE 0 END) AS last_month_sales,
          -- Calculate percentage change
          CASE
              WHEN SUM(CASE 
                       WHEN YEAR(created_at) = YEAR(CURDATE()) 
                       AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN (paid_amount - balance) 
                       ELSE 0 END) = 0
              THEN NULL -- Avoid division by zero
              ELSE 
                  (SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                            AND MONTH(created_at) = MONTH(CURDATE()) THEN (paid_amount - balance) 
                            ELSE 0 END) 
                  - SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                             AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN (paid_amount - balance) 
                             ELSE 0 END)) 
                  / SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) 
                             AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN (paid_amount - balance) 
                             ELSE 0 END) * 100
          END AS percentage_change
      FROM sales
  "));

     $sales_percent = $sales_comparison[0]->percentage_change ?? 0;

     //total sales
     $total_sales = DB::select(DB::raw("
    SELECT 
        SUM(paid_amount - balance) AS total_sales_since_last_month
    FROM sales
    WHERE created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
    AND created_at < CURDATE()
     "));
     $sales_since_last_month = $total_sales[0]->total_sales_since_last_month ?? 0;

     $pre_milled_inventories = DB::select(DB::raw("
    SELECT
        -- Sum for this month
        SUM(CASE 
            WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) THEN quantity 
            ELSE 0 
        END) AS this_month_quantity,

        -- Sum for last month
        SUM(CASE 
            WHEN YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) - 1 THEN quantity 
            ELSE 0 
        END) AS last_month_quantity,

        -- Total since the beginning of last month to date
        SUM(CASE 
            WHEN created_at >= DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH) AND created_at < CURDATE() THEN quantity
            ELSE 0 
        END) AS total_since_last_month
    FROM pre_milled_inventories
"));

        $this_month_quantity = $pre_milled_inventories[0]->this_month_quantity ?? 0;
        $last_month_quantity = $pre_milled_inventories[0]->last_month_quantity ?? 0;
        // Calculate percentage change
        $percentage_change = $last_month_quantity > 0 
            ? (($this_month_quantity - $last_month_quantity) / $last_month_quantity) * 100 
            : 0;
        $total_Premiiled_since_last_month = $pre_milled_inventories[0]->total_since_last_month ?? 0;
        $premilled_percent  = round($percentage_change, 2);

        $data = [
            "total_collection_weight" => $totalCollectionWeight,
            "farmer_count" => $farmerCount,
            "collection_count" => $collectionCount,
            "collections" => $collections,
            "collections_by_cooperative" => $collectionsByCooperative,
            "cooperatives_count" => $cooperativesCount,
            "grade_distribution" => $grade_distribution,
            "male_collections" => $maleCollections,
            "female_collections" => $femaleCollections,
            "age_distribution" => $age_distribution, // Added age distribution
            "genderDistribution" =>  $genderDistribution,
            "raw_percent"=>$raw_percent,
            "raw_materials_qnty"=> $raw_materialsqnty ,
            "farmer_percent" =>$farmer_percent,
            "collection_percent"=> $collection_percent,
            "coop_percent"=> $coop_percent ,
            "millers_percent"=> $millers_percent,
            "millers_count" => $total_millers,
            "finalproductcount_percent"=> $finalproductcount_percent,
            "finalproductqnty_percent"=>$percent_product,
            "final_products_quantity"=>$final_products_qnty,
            "final_products_count"=>$final_prod_count,
            "sales_percent" =>  $sales_percent,
             "sales_since_last_month"=>$sales_since_last_month,
             "premilled_percent" => $premilled_percent,
             "total_Premilled_since_last_month" => $total_Premiiled_since_last_month,
             "male_collections_pie" => $male_collections_pie ,
             "female_collections_pie" => $female_collections_pie,
        ];

      
        return view('pages.admin.dashboard', compact("data", "date_range", "from_date", "to_date","age_distribution"));
    }

}
