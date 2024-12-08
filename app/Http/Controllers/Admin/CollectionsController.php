<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Collection;
use Excel;
use Hash;
use App\Exports\CollectionByAdmin;

class CollectionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $collections = DB::select(DB::raw("
            SELECT usr.username,usr.first_name,usr.other_names, p.name as product_name, p.quantity, c.*, pc.unit,
                   co.name as cooperative_name
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            JOIN cooperatives co ON co.id = c.cooperative_id
            ORDER BY c.created_at DESC;
        "));

       
        return view('pages.admin.collections.index', compact('collections'));
    }
    public function viewBranch($branchId) // New method to view branch details
{
    $branch = DB::table('coop_branches')->find($branchId);
    $farmers = DB::select(DB::raw("
        SELECT f.id, u.username
        FROM farmers f
        JOIN users u ON f.user_id = u.id
        JOIN farmer_cooperative fc ON fc.farmer_id = f.id
        
    "), ["coop_id" => $branch->cooperative_id]); // Fetch farmers for the branch

    $totalFarmers = count($farmers); // Count of farmers

    return view('pages.admin.branch.view', compact('branch', 'farmers', 'totalFarmers'));
}

public function collections_mini_dashboard(Request $request)
{
       // $coop_id = Auth::user()->cooperative->id;
        $date_range = $request->query("date_range", "week");
        $from_date = $request->query("from_date", "");
        $to_date = $request->query("to_date", "");
        $from_date_prev = "";
        $to_date_prev = "";
        $prevCollections = [];

        switch ($date_range) {
            case "custom":
                $from_date = $request->query("from_date", now()->subDays(7)->format('Y-m-d'));
                $to_date = $request->query("to_date", now()->format('Y-m-d'));
                break;
            case "week":
                $from_date = now()->subDays(7)->format('Y-m-d');
                $to_date = now()->format('Y-m-d');
                $from_date_prev = now()->subDays(14)->format('Y-m-d');
                $to_date_prev = now()->subDays(7)->format('Y-m-d');
                $prev_range = "Last Week";
                break;
            case "month":
                $from_date = now()->subDays(30)->format('Y-m-d');
                $to_date = now()->format('Y-m-d');
                $from_date_prev = now()->subDays(60)->format('Y-m-d');
                $to_date_prev = now()->subDays(30)->format('Y-m-d');
                $prev_range = "Last Month";
                break;
            case "year":
                $from_date = now()->subYear()->format('Y-m-d');
                $to_date = now()->format('Y-m-d');
                $from_date_prev = now()->subYears(2)->format('Y-m-d');
                $to_date_prev = now()->subYear()->format('Y-m-d');
                $prev_range = "Last Year";
                break;
            default:
                throw new \InvalidArgumentException("Invalid date range: $date_range");
        }


        $suggested_chart_mode = "daily";

        // Determine suggested chart mode based on $date_range and duration
        if ($date_range == "week") {
            $suggested_chart_mode = "weekly";
        } elseif ($date_range == "month") {
            $suggested_chart_mode = "monthly";
        } elseif ($date_range == "year") {
            $suggested_chart_mode = "yearly"; 
        } else {
            // Custom or other ranges, fallback to duration-based logic
            $range_duration = strtotime($to_date) - strtotime($from_date);
            if ($range_duration < 60 * 24 * 60 * 60) {
                $suggested_chart_mode = "daily";
            } elseif ($range_duration < 4 * 30 * 24 * 60 * 60) {
                $suggested_chart_mode = "weekly";
            } elseif ($range_duration < 3 * 12 * 30 * 24 * 60 * 60) {
                $suggested_chart_mode = "monthly";
            } else {
                $suggested_chart_mode = "yearly";
            }
        }

        // dd($suggested_chart_mode, $from_date, $to_date);
        $from_date_formatted = date('Y-m-01', strtotime($from_date));
        $to_date_formatted = date('Y-m-d', strtotime($to_date));
        // Fetch collections based on chart mode
        $collections = [];
        if ($suggested_chart_mode == "daily") {
            $dailyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT '$from_date_formatted' AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 DAY)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 DAY) <= '$to_date_formatted'
                )
                SELECT date_series.date AS x, IFNULL(SUM(c.quantity), 0) AS y
                FROM date_series
                LEFT JOIN collections c ON date_series.date = c.date_collected
                
                GROUP BY date_series.date;
            ";
            $collections = DB::select($dailyQuery, [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($from_date_prev) {
                $prevCollections = DB::select($dailyQuery, [
                    'from_date' => $from_date_prev,
                    'to_date' => $to_date_prev,
                ]);
            }
        } 
        if ($suggested_chart_mode == "weekly") {
            $weeklyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT DATE_FORMAT('$from_date_formatted', '%Y-%u') AS week_year, '$from_date_formatted' AS start_date
                    UNION ALL
                    SELECT DATE_FORMAT(DATE_ADD(start_date, INTERVAL 7 DAY), '%Y-%u'), DATE_ADD(start_date, INTERVAL 7 DAY)
                    FROM date_series
                    WHERE DATE_ADD(start_date, INTERVAL 7 DAY) <= '$to_date_formatted'
                )
                SELECT date_series.week_year AS x, IFNULL(SUM(c.quantity), 0) AS y
                FROM date_series
                LEFT JOIN collections c 
                ON DATE_FORMAT(c.date_collected, '%Y-%u') = date_series.week_year
                
                GROUP BY date_series.week_year, date_series.start_date
                ORDER BY date_series.start_date;               
            ";

           $collections = DB::select(DB::raw($weeklyQuery), [
                'from_date' => $from_date,
                'to_date' => $to_date,
                //'coop_id' => $coop_id,
            ]);

            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($weeklyQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                ]);
            }
        }
        elseif ($suggested_chart_mode == "monthly") {
            $monthlyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT DATE_FORMAT('$from_date_formatted', '%Y-%b') AS month_year,  '$from_date_formatted'  AS date
                    UNION ALL
                    SELECT DATE_FORMAT(DATE_ADD(date, INTERVAL 1 MONTH), '%Y-%b'), DATE_ADD(date, INTERVAL 1 MONTH)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 MONTH) <= '$to_date_formatted'
                )
                SELECT date_series.month_year AS x, IFNULL(SUM(c.quantity), 0) AS y
                FROM date_series
                LEFT JOIN collections c ON DATE_FORMAT(c.date_collected, '%Y-%b') = date_series.month_year
                
                GROUP BY date_series.month_year;
            ";
            $collections = DB::select($monthlyQuery, [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($from_date_prev) {
                $prevCollections = DB::select($monthlyQuery, [
                    'from_date' => $from_date_prev,
                    'to_date' => $to_date_prev,
                ]);
            }
        } 
        elseif ($suggested_chart_mode == "yearly") {
            $yearlyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT YEAR('$from_date_formatted') AS year, '$from_date_formatted' AS date
                    UNION ALL
                    SELECT YEAR(DATE_ADD(date, INTERVAL 1 YEAR)), DATE_ADD(date, INTERVAL 1 YEAR)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 YEAR) <= '$to_date_formatted'
                )
                SELECT date_series.year AS x, IFNULL(SUM(c.quantity), 0) AS y
                FROM date_series
                LEFT JOIN collections c ON YEAR(c.date_collected) = date_series.year
                
                GROUP BY date_series.year;
            ";
            
            $collections = DB::select(DB::raw($yearlyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
            ]);
            
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($yearlyQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                ]);
            }
        }
// Calculate KPIs
$totalCollections = count($collections);
$totalQuantityCollected = array_sum(array_column($collections, 'y')); // Use 'y' from the query result
$averageCollectionPerLot = $totalCollections > 0 ? $totalQuantityCollected / $totalCollections : 0;
    // Update this mapping to match your database values
$collectionTimeLabels = [
    1 => 'Morning',
    2 => 'Afternoon',
    3 => 'Evening',
];
// Retrieve and format `collection_time` data with labels
$collectionTimeData = DB::table('collections')
    ->select('collection_time', DB::raw('count(*) as count'))
    ->groupBy('collection_time')
    ->get()
    ->mapWithKeys(function ($item) use ($collectionTimeLabels) {
        return [$collectionTimeLabels[$item->collection_time] ?? $item->collection_time => $item->count];
    })
    ->toArray();

// Grading status data for stacked bar chart
    $gradingStatusData = DB::select(DB::raw("
        SELECT 
            l.lot_number,
            (SELECT SUM(c.quantity) FROM collections c WHERE c.lot_number = l.lot_number) as total_quantity,
            (SELECT SUM(d.quantity) FROM lot_grade_distributions d WHERE d.lot_number = l.lot_number) as graded_quantity
        FROM lots l
        
    "));

    // Format grading status data for the stacked bar chart
    $formattedGradingStatusData = [];
    foreach ($gradingStatusData as $lot) {
        $totalQuantity = $lot->total_quantity ?? 0;
        $gradedQuantity = $lot->graded_quantity ?? 0;
        $ungradedQuantity = max($totalQuantity - $gradedQuantity, 0);

        $formattedGradingStatusData[] = [
            'lot_number' => $lot->lot_number,
            'graded' => $gradedQuantity,
            'ungraded' => $ungradedQuantity,
            'remaining' => $ungradedQuantity,
        ];
    }

    // Prepare final data to send to view
    $data = [
        "collections" => $collections,
        "totalCollections" => $totalCollections,
        "totalQuantityCollected" => $totalQuantityCollected,
        "averageCollectionPerLot" => $averageCollectionPerLot,
                "collectionTimeData" => $collectionTimeData, // Include collectionTimeData here

        "gradingStatusData" => $formattedGradingStatusData, // Pass grading data to view
    ];

    return view("pages.admin.collections.mini-dashboard", compact(
        'data', 'date_range', 'from_date', 'to_date'
    ));
}

public function export_collections($type)
    {
        $user = Auth::user();

        $image=$user->profile_picture;
        
        $collections = collect(DB::select(DB::raw("
        SELECT usr.username,usr.first_name,usr.other_names, p.name as product_name, p.quantity, c.*, pc.unit,
               co.name as cooperative_name
        FROM collections c
        JOIN farmers f ON f.id = c.farmer_id
        JOIN users usr ON usr.id = f.user_id
        JOIN products p ON p.id = c.product_id
        JOIN product_categories pc ON pc.id = p.category_id
        JOIN cooperatives co ON co.id = c.cooperative_id
        ORDER BY c.created_at DESC;
    ")))->map(function ($item) {
        return (array) $item; // Cast each stdClass object to array
    })->toArray();
             
      //dd($collections);

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('detailed_collections_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new CollectionByAdmin($collections), $file_name);
        } else {
            $columns = [
                ['name' => 'Cooperative', 'key' => "cooperative_name"],
                ['name' => 'Collection No', 'key' => "collection_number"],
                ['name' => 'Lot No', 'key' => "lot_number"],
                ['name' => 'Farmer', 'key' => "first_name"],
                ['name' => 'Product', 'key' => "product_name"],
                ['name' => 'Qty', 'key' => "quantity"],
                ['name' => 'Unit', 'key' => "unit"],
                //['name' => 'Collection Time', 'key' => "collection_time"],
            ];
            $imagePath = public_path('storage/' . $image); // Absolute path to image
            $data = [
                'title' => 'Detailed Collections',
                'pdf_view' => 'warehouses',
                'records' => $collections,
                'filename' => strtolower('detailed_collections_' . date('d_m_Y')),
                'orientation' => 'letter',
                'image'=>$imagePath,
            ];
            return download_pdf($columns, $data);
        }
    }
	


}