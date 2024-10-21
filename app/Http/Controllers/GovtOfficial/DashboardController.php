<?php

namespace App\Http\Controllers\GovtOfficial;

use App\Cooperative;
use App\Http\Controllers\Controller;
use App\Miller;
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

        $cooperative_id = $request->query("cooperative_id", "all");

        // farmer count
        $farmerCount = DB::select(DB::raw("
            SELECT 
                COUNT(1) AS count
            FROM farmer_cooperative
        "))[0]->count;

        // total collection weight
        $totalCollectionWeight = DB::select(DB::raw("
            SELECT 
                SUM(quantity) AS quantity
            FROM collections
            WHERE CASE WHEN :coop_id = 'all' THEN 1 ELSE collections.cooperative_id = :coop_id1 END
        "), ["coop_id" => $cooperative_id, "coop_id1" => $cooperative_id])[0]->quantity;

        $cooperativeCount = Cooperative::count();

        $millerCount = Miller::count();


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
                            CASE WHEN :coop_id = '' THEN 1 ELSE collections.cooperative_id = :coop_id1 END
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
                "coop_id" => $cooperative_id,
                "coop_id1" => $cooperative_id,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($dailyQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
                    'coop_id' => $cooperative_id,
                    'coop_id1' => $cooperative_id,
                ]);
            }

            $gender = "M";
            $maleCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                'coop_id' => $cooperative_id,
                'coop_id1' => $cooperative_id,
            ]);

            $gender = "F";
            $femaleCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                'coop_id' => $cooperative_id,
                'coop_id1' => $cooperative_id,
            ]);

            $gender = "X";
            $otherGenderCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
                'coop_id' => $cooperative_id,
                'coop_id1' => $cooperative_id,
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
                            CASE WHEN :coop_id = '' THEN 1 ELSE collections.cooperative_id = :coop_id2 END
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
                'coop_id' => $cooperative_id,
                'coop_id1' => $cooperative_id,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($monthlyQuery), [
                    "from_date" => $from_date_prev,
                    "from_date1" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
                    'coop_id' => $cooperative_id,
                    'coop_id1' => $cooperative_id,
                ]);
            }

            $gender = 'M';
            $maleCollections = DB::select(DB::raw($monthlyQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
            ]);

            $gender = 'F';
            $femaleCollections = DB::select(DB::raw($monthlyQuery), [
                "from_date" => $from_date,
                "from_date1" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
            ]);
        }

        $gender_distribution = DB::select(DB::raw("select
            count(case when gender='M' then 1 end) as male,
            count(case when gender='F' then 1 end) as female,
            count(case when gender='X' then 1 end) as other
            from farmers f
            JOIN farmer_cooperative fc ON fc.farmer_id = f.id
            WHERE CASE WHEN :coop_id = 'all' THEN 1 ELSE fc.cooperative_id = :coop_id1 END
        "), ["coop_id" => $cooperative_id, "coop_id1" => $cooperative_id])[0];

        // coffee grade distribution
        $grade_distribution = DB::select(DB::raw("
            SELECT SUM(quantity) AS quantity, pg.name AS name
            FROM lot_grade_distributions lgd
            JOIN product_grades pg ON pg.id = lgd.product_grade_id
            JOIN lots l ON l.lot_number = lgd.lot_number 
            WHERE CASE WHEN :coop_id = 'all' THEN 1 ELSE l.cooperative_id = :coop_id1 END
            GROUP BY lgd.product_grade_id
            ORDER BY quantity DESC
        "), ["coop_id" => $cooperative_id, "coop_id1" => $cooperative_id]);

        $data = [
            "farmer_count" => $farmerCount,
            "cooperative_count" => $cooperativeCount,
            "miller_count" => $millerCount,
            "total_collection_weight" => $totalCollectionWeight,
            "collections" => $collections,
            "male_collections" => $maleCollections,
            "female_collections" => $femaleCollections,
            "other_gender_collections" => $otherGenderCollections,
            "prev_collections" => $prevCollections,
            "gender" => $gender_distribution,
            "grade_distribution" => $grade_distribution,
        ];

        $selectableCooperatives = DB::select(DB::raw("
            SELECT c.id, c.name FROM cooperatives c;
        "));


        return view('pages.govt-official.dashboard', compact("data", "date_range", "from_date", "to_date", "selectableCooperatives", "cooperative_id"));
    }
}
