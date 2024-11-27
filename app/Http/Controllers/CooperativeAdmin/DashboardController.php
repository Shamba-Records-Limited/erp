<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade as PDF;

class DashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative;
        $coop_id = $coop->id;

        // farmer count
        $farmerCount = DB::select(DB::raw("
            SELECT 
                COUNT(1) AS count
            FROM farmer_cooperative
            WHERE cooperative_id = :coop_id
        "), ["coop_id" => $coop_id])[0]->count;

        // collection count
        $collectionCount = DB::select(DB::raw("
            SELECT 
                COUNT(1) AS count
            FROM collections
            WHERE cooperative_id = :coop_id
        "), ["coop_id" => $coop_id])[0]->count;

        // total collection weight
        $totalCollectionWeight = DB::select(DB::raw("
            SELECT 
                SUM(quantity) AS quantity
            FROM collections
            WHERE cooperative_id = :coop_id
        "), ["coop_id" => $coop_id])[0]->quantity;

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
                        SELECT IFNULL(SUM(collections.quantity), 0)
                        FROM collections
                        JOIN farmers f ON f.id = collections.farmer_id
                        WHERE collections.date_collected = date_series.date AND
                            CASE WHEN :gender = 'all' THEN 1 ELSE f.gender = :gender1 END AND
                            collections.cooperative_id = :coop_id
                    ) AS y
                FROM date_series
                GROUP BY date_series.date;
            ";

            $gender = "all";
            $collections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop_id" => $coop_id,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($dailyQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
                    "coop_id" => $coop_id,
                ]);
            }

            $gender = "M";
            $maleCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop_id" => $coop_id,
            ]);

            $gender = "F";
            $femaleCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop_id" => $coop_id,
            ]);

            $gender = "X";
            $otherGenderCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop_id" => $coop_id,
            ]);
        } else if ($suggested_chart_mode == "monthly") {
            $monthlyQuery = "
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
                            c.cooperative_id = :coop_id
                    ) AS y
                FROM date_series
                GROUP BY date_series.month_year;
            ";
            $gender = "all";
            $collections = DB::select(DB::raw($monthlyQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop_id" => $coop_id,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($monthlyQuery), [
                    "from_date" => $from_date_prev,
                    "from_date1" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
                    "coop_id" => $coop_id,
                ]);
            }

            $gender = 'M';
            $maleCollections = DB::select(DB::raw($monthlyQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop_id" => $coop_id,
            ]);

            $gender = 'F';
            $femaleCollections = DB::select(DB::raw($monthlyQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                "coop_id" => $coop_id,
            ]);
        }

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

        // collections by wet mills
        $collections_by_wet_mills = DB::select(DB::raw("
            SELECT SUM(quantity) AS quantity, branch.name AS name
            FROM collections c
            JOIN coop_branches branch ON branch.id = c.coop_branch_id
            WHERE c.cooperative_id = :coop_id
            GROUP BY branch.id
            ORDER BY quantity DESC
        "), ["coop_id" => $coop_id]);


        // gender distribution
        $gender_distribution = DB::select(DB::raw("select
            count(case when gender='M' then 1 end) as male,
            count(case when gender='F' then 1 end) as female,
            count(case when gender='X' then 1 end) as other
            from farmers f
            JOIN farmer_cooperative fc ON fc.farmer_id = f.id AND fc.cooperative_id = :coop_id
            "), ["coop_id" => $coop_id])[0];
        // $collectionsCount = DB::select(DB::raw("
        //     SELECT 
        // "));

        //collections yesterday backwords and today compared//
        $collections_compare = DB::select(
            DB::raw("
                SELECT 
                    SUM(CASE 
                        WHEN created_at >= CURDATE() AND created_at < CURDATE() + INTERVAL 1 DAY THEN quantity 
                        ELSE 0 
                    END) AS today_quantity,
                    SUM(CASE 
                        WHEN created_at >= CURDATE() - INTERVAL 1 DAY AND created_at < CURDATE() THEN quantity 
                        ELSE 0 
                    END) AS yesterday_quantity
                FROM 
                    collections
                WHERE 
                    created_at >= CURDATE() - INTERVAL 1 DAY
                    AND cooperative_id = :cooperative_id
            "),
            ['cooperative_id' => 'ac1da4bd-532e-4016-8a0b-8d6c234549ef']
        );
        
        $todayQuantity = $collections_compare[0]->today_quantity ?? 0;
        $yesterdayQuantity = $collections_compare[0]->yesterday_quantity ?? 0;
        //percentage of taday agains yesterday
        if($yesterdayQuantity==0){
            $percent_daily=100;
        }else{
            $today_yesterdays=(($todayQuantity-$yesterdayQuantity)/$yesterdayQuantity)*100;
             $percent_daily=round($today_yesterdays,2);
        }
      
       //Farmer Count Last month compared to this month
       $farmers_count = DB::select(
        DB::raw("
            SELECT 
                COUNT(CASE 
                    WHEN MONTH(f.created_at) = MONTH(CURDATE()) AND YEAR(f.created_at) = YEAR(CURDATE()) THEN 1 
                    ELSE NULL 
                END) AS this_month_count,
                COUNT(CASE 
                    WHEN MONTH(f.created_at) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(f.created_at) = YEAR(CURDATE() - INTERVAL 1 MONTH) THEN 1 
                    ELSE NULL 
                END) AS last_month_count
            FROM 
                farmers f
            JOIN 
                farmer_cooperative fc ON fc.farmer_id = f.id 
                AND fc.cooperative_id = :coop_id
        "),
        ["coop_id" => $coop_id]
        )[0];
         $thisMonthFarmers = $farmers_count->this_month_count ?? 0;
         $lastMonthFarmers = $farmers_count->last_month_count ?? 0;   
         if($lastMonthFarmers==0){
            $farmer_percent=100;
         }else{
            $this_month_last_months=(($thisMonthFarmers-$lastMonthFarmers)/$lastMonthFarmers)*100;
            $farmer_percent=round($this_month_last_months,2);
         }

         
     //collections count last week against this week
     $collection_counts = DB::select(
        DB::raw("
            SELECT 
                COUNT(CASE 
                    WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 
                    ELSE NULL 
                END) AS this_week_count,
                COUNT(CASE 
                    WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1) THEN 1 
                    ELSE NULL 
                END) AS last_week_count
            FROM 
                collections
            WHERE 
                cooperative_id = :coop_id
        "),
        ['coop_id' => $coop_id]
    )[0];
    $thisWeekCount = $collection_counts->this_week_count ?? 0;
    $lastWeekCount = $collection_counts->last_week_count ?? 0;
     if( $lastWeekCount==0){
        $percent_weekly=100;
     }else{
        $this_week_last_week=(($thisWeekCount-$lastWeekCount)/$lastWeekCount)*100;
        $percent_weekly=round($this_week_last_week,2);
     }
     


    $data = [
        "farmer_count" => $farmerCount,
        "collection_count" => $collectionCount,
        "total_collection_weight" => $totalCollectionWeight,
        "collections_by_wet_mills" => $collections_by_wet_mills,
        "grade_distribution" => $grade_distribution,
        "gender" => $gender_distribution,
        "collections" => $collections,
        "male_collections" => $maleCollections,
        "female_collections" => $femaleCollections,
        "other_gender_collections" => $otherGenderCollections,
        "prev_collections" => $prevCollections,
         "percent_farmer" =>  $farmer_percent,
         "percent_daily"=>$percent_daily,
         "percent_weekly"=>$percent_weekly
    ];

        return view('pages.cooperative-admin.dashboard', compact("data", "date_range", "from_date", "to_date"));
    }

    public function export_dashboard(Request $request)
    {
        $dateRange = $request->input("dateRange");
        $fromDate = $request->input("fromDate");
        $toDate = $request->input("toDate");

        $farmerCount = $request->input("farmerCount");
        $collectionCount = $request->input("collectionCount");
        $collectionTotalWeight = $request->input("collectionTotalWeight");

        $genderChartImg = $request->input("genderChartImg");
        $collectionsBarChartImg = $request->input("collectionsBarChartImg");
        $gradeDistributionChartImg = $request->input("gradeDistributionChartImg");



        $pdf = PDF::loadView('pages.cooperative-admin.export_dash', compact(
            'dateRange',
            'fromDate',
            'toDate',
            'farmerCount',
            'collectionCount',
            'collectionTotalWeight',
            'genderChartImg',
            'collectionsBarChartImg',
            'gradeDistributionChartImg'
        ));

        return $pdf->download('chart.pdf');
    }
}
