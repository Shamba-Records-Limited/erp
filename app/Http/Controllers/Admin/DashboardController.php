<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Farmer;

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
                WHEN age BETWEEN 18 AND 25 THEN '18-25'
                WHEN age BETWEEN 26 AND 35 THEN '26-35'
                WHEN age BETWEEN 36 AND 45 THEN '36-45'
                WHEN age BETWEEN 46 AND 55 THEN '46-55'
                WHEN age BETWEEN 56 AND 65 THEN '56-65'
                ELSE '66+' 
            END AS age_group,
            COUNT(*) AS quantity
        FROM farmers
        GROUP BY age_group
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
                            CASE WHEN :coop = 'all' THEN 1 ELSE c.coop_id = :coop1 END
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
        }

        $data = [
            "total_collection_weight" => $totalCollectionWeight,
            "farmer_count" => $farmerCount,
            "collection_count" => $collectionCount,
            "collections" => $collections,
            "collections_by_cooperative" => $collectionsByCooperative,
            // "cooperatives_count" => $cooperativesCount,
            // "grade_distribution" => $grade_distribution,
            "male_collections" => $maleCollections,
            "female_collections" => $femaleCollections,
            "age_distribution" => $age_distribution, // Added age distribution
        ];
        
        //Farmer age distribution
        $ageDistribution = Farmer::selectRaw("
        CASE 
            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 45 THEN '36-45'
            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 46 AND 55 THEN '46-55'
            WHEN TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 56 AND 65 THEN '56-65'
            ELSE '66+'
	END as age_group, COUNT(*) as quantity
    ")
    ->groupBy('age_group')
    ->orderByRaw("FIELD(age_group, '18-25', '26-35', '36-45', '46-55', '56-65', '66+')")
    ->get()->toArray();
    $age_distribution=json_encode($ageDistribution);

        // Generate gender distribution data
        $genderDistribution = Farmer::select('gender', DB::raw('COUNT(*) as quantity'))
        ->groupBy('gender')
        ->orderBy('gender') // Optional: Order genders alphabetically
        ->get()->toArray();
        $gender_distribution=json_encode($genderDistribution);

        return view('pages.admin.dashboard', compact("data", "date_range", "from_date", "to_date", "gender_distribution", "age_distribution"));
    }
}
