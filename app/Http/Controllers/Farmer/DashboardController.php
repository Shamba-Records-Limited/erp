<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
      

        $user = Auth::user();
        $farmer_id = $user->farmer->id;

        // total collection weight
        $totalCollectionWeight = DB::table('collections')
        ->where('farmer_id', $farmer_id)
        ->sum('quantity');
    

           // This month's weight % 
            $thisMonthWeight = DB::table('collections')
            ->where('farmer_id', $farmer_id)
            ->whereMonth('date_collected', date('m')) 
            ->whereYear('date_collected', date('Y'))
            ->sum('quantity');

            // Last month's weight %
            $lastMonthWeight = DB::table('collections')
            ->where('farmer_id', $farmer_id)
            ->whereMonth('date_collected', date('m', strtotime('first day of last month')))
            ->whereYear('date_collected', date('Y', strtotime('first day of last month')))
            ->sum('quantity');

            // Calculate percentage change
            $percentageWeight = 0;
            if ($lastMonthWeight > 0) {
            $percentageChange = (($thisMonthWeight - $lastMonthWeight) / $lastMonthWeight) * 100;
            }

            // Optionally: You can also check for negative percentage changes (e.g., if the last month weight is zero).


        // collection count
        $collectionCount = DB::select(DB::raw("
        SELECT 
            COUNT(1) AS count
        FROM collections
        WHERE farmer_id = :farmer_id
    "), ["farmer_id" => $farmer_id])[0]->count;


                // This month's collection count
            $thisMonthCollectionCount = DB::table('collections')
            ->where('farmer_id', $farmer_id)
            ->whereMonth('date_collected', date('m')) 
            ->whereYear('date_collected', date('Y'))
            ->count();
            // Last month's collection count
            $lastMonthCollectionCount = DB::table('collections')
            ->where('farmer_id', $farmer_id)
            ->whereMonth('date_collected', date('m', strtotime('first day of last month')))
            ->whereYear('date_collected', date('Y', strtotime('first day of last month')))
            ->count();
            // Calculate percentage change
            $percentageCollectionCountChange = 0;
            if ($lastMonthCollectionCount > 0) {
            $percentageCollectionCountChange = (($thisMonthCollectionCount - $lastMonthCollectionCount) / $lastMonthCollectionCount) * 100;
            }

        //total paid
        $totalAmountPaid = DB::table('transactions')
                   ->where('recipient_id', $farmer_id)
                  ->sum('amount');
    //total Paid %
                // This month's amount paid
            $thisMonthAmountPaid = DB::table('transactions')
            ->where('recipient_id', $farmer_id)
            ->whereMonth('created_at', date('m')) 
            ->whereYear('created_at', date('Y'))
            ->sum('amount');

            // Last month's amount paid
            $lastMonthAmountPaid = DB::table('transactions')
            ->where('recipient_id', $farmer_id)
            ->whereMonth('created_at', date('m', strtotime('first day of last month')))
            ->whereYear('created_at', date('Y', strtotime('first day of last month')))
            ->sum('amount');

            // Calculate percentage change
            $percentageAmountPaidChange = 0;
            if ($lastMonthAmountPaid > 0) {
            $percentageAmountPaidChange = (($thisMonthAmountPaid - $lastMonthAmountPaid) / $lastMonthAmountPaid) * 100;
            }

            // Optionally: You can check for negative percentage changes (e.g., if the last month amount paid is zero).


        
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
        $collectionsByCooperative = [];
        $grade_distribution_data=[];
 

        if ($suggested_chart_mode == "daily") {
            // Query for daily collections
            $collections =$this->getCollectionsByDateRange($from_date, $to_date,$farmer_id);
            $collectionsByCooperative=$this->getCoopCollectionsByDateRange($from_date, $to_date,$farmer_id);
            $grade_distribution_data =$this->getgradeDistriubutionByDateRange($from_date, $to_date,$farmer_id);

            $prevCollections = !empty($from_date_prev) && !empty($to_date_prev) 
                ? $this->getCollectionsByDateRange($from_date_prev, $to_date_prev, $farmer_id ) 
                : collect();
        } elseif ($suggested_chart_mode == "monthly") {
            // Query for monthly collections
            $collections = $this->getCollectionsByMonthRange($from_date, $to_date,$farmer_id);
            $collectionsByCooperative=$this->getCoopCollectionsByMonthRange($from_date, $to_date,$farmer_id);
            $grade_distribution_data =$this->getgradeDistriubutionByMonth($from_date, $to_date,$farmer_id);

            $prevCollections = !empty($from_date_prev) && !empty($to_date_prev) 
                ? $this->getCollectionsByMonthRange($from_date_prev, $to_date_prev) 
                : collect();
        }
   
        // cooperatives count
        $cooperativesCount = DB::select(DB::raw("SELECT COUNT(*) AS count FROM cooperatives"))[0]->count;

        $collection_percent = $collections_comparison[0]->percentage_change ?? 0;

        $data = [
            "total_collection_weight" => $totalCollectionWeight,
            "collection_count" => $collectionCount,
            "collections" => $collections,
            "collections_by_cooperative" => $collectionsByCooperative,
            "cooperatives_count" => $cooperativesCount,
            "grade_distribution" => $grade_distribution_data,
            "collection_percent"=> $collection_percent, 
             "total_amount_paid" => $totalAmountPaid,
            "percentageWeight"=> $percentageWeight,
            "percentageAmountPaidChange"=>$percentageAmountPaidChange,
            "percentageCollectionCountChange"=>$percentageCollectionCountChange 
        ];

      
        return view('pages.farmer.dashboard', compact("data", "date_range", "from_date", "to_date"));
    }


         /**
         * Get collections grouped by date within a date range.
         */
       public function getCollectionsByDateRange($from_date, $to_date,$farmer_id)
        {
            return DB::table('collections')
                ->join('farmers', 'farmers.id', '=', 'collections.farmer_id')
                ->selectRaw('DATE(collections.date_collected) as x, IFNULL(SUM(collections.quantity), 0) as y')
                  ->where('collections.farmer_id', $farmer_id) // Filter by farmer_id
                ->whereBetween('collections.date_collected', [$from_date, $to_date])
                ->groupBy('x')
                ->get();
        }
        /**
         * Get collections grouped by month within a date range.
         */
       public function getCollectionsByMonthRange($from_date, $to_date,$farmer_id)
        {
            return DB::table('collections')
                ->join('farmers', 'farmers.id', '=', 'collections.farmer_id')
                ->selectRaw('DATE_FORMAT(collections.date_collected, "%Y-%b") as x, IFNULL(SUM(collections.quantity), 0) as y')
                ->where('collections.farmer_id', $farmer_id) // Filter by farmer_id
                ->whereBetween('collections.date_collected', [$from_date, $to_date])
                ->groupBy('x')
                ->get();
        }
     //Collection bY Coop
        public function getCoopCollectionsByDateRange($from_date, $to_date,$farmer_id)
        {
            return DB::table('collections')
            ->join('cooperatives', 'collections.cooperative_id', '=', 'cooperatives.id')
            ->select(
                'cooperatives.name as cooperative_name', 
                DB::raw('SUM(collections.quantity) as total_quantity')
            )
            ->where('collections.farmer_id', $farmer_id) // Filter by farmer_id
            ->whereBetween('collections.date_collected', [$from_date, $to_date])
            ->groupBy('cooperatives.name')
            ->orderBy('total_quantity', 'DESC')
            ->get();
        }
        public function getCoopCollectionsByMonthRange($from_date, $to_date,$farmer_id)
        {
            return DB::table('collections')
            ->join('cooperatives', 'collections.cooperative_id', '=', 'cooperatives.id')
            ->select(
                'cooperatives.name as cooperative_name',
                DB::raw('SUM(collections.quantity) as total_quantity'),
                DB::raw('MONTH(collections.date_collected) as collection_month'), // Get the month
                DB::raw('YEAR(collections.date_collected) as collection_year')  // Get the year for monthly data
            )
            ->where('collections.farmer_id', $farmer_id) // Filter by farmer_id
            ->whereBetween('collections.date_collected', [$from_date, $to_date])
            ->groupBy('cooperatives.name', DB::raw('MONTH(collections.date_collected)'), DB::raw('YEAR(collections.date_collected)'))  // Group by month and cooperative
            ->orderBy('collection_year', 'ASC')
            ->orderBy('collection_month', 'ASC')  // Order by year and month
            ->get();
        }

        public function getgradeDistriubutionByDateRange($from_date, $to_date, $farmer_id)
         {
            $grade_distribution = DB::select(DB::raw("
                SELECT 
                    SUM(lgd.quantity) AS quantity, 
                    pg.name AS name
                FROM 
                    lot_grade_distributions lgd
                JOIN 
                    product_grades pg ON pg.id = lgd.product_grade_id
                JOIN 
                    lots l ON l.lot_number = lgd.lot_number
                JOIN 
                    collections col ON col.lot_number = l.lot_number
                JOIN 
                    farmers frm ON frm.id = col.farmer_id
                WHERE 
                    frm.id = :farmer_id
                    AND col.date_collected BETWEEN :from_date AND :to_date
                GROUP BY 
                    lgd.product_grade_id
                ORDER BY 
                    quantity DESC
            "), ['farmer_id' => $farmer_id, 'from_date' => $from_date, 'to_date' => $to_date]);

            // Transform the data for the chart
            $grade_distribution_data = array_map(function ($item) {
                return [
                    'name' => $item->name, // Grade name
                    'quantity' => (int) $item->quantity // Quantity
                ];
            }, $grade_distribution);

            // Return the transformed data
            return $grade_distribution_data;
        }


        public function getgradeDistriubutionByMonth($from_date, $to_date, $farmer_id)
{
    $grade_distribution = DB::select(DB::raw("
        SELECT 
            SUM(lgd.quantity) AS quantity, 
            pg.name AS name,
            MONTH(col.date_collected) AS collection_month,  -- Get the month
            YEAR(col.date_collected) AS collection_year   -- Get the year
        FROM 
            lot_grade_distributions lgd
        JOIN 
            product_grades pg ON pg.id = lgd.product_grade_id
        JOIN 
            lots l ON l.lot_number = lgd.lot_number
        JOIN 
            collections col ON col.lot_number = l.lot_number
        JOIN 
            farmers frm ON frm.id = col.farmer_id
        WHERE 
            frm.id = :farmer_id
            AND col.date_collected BETWEEN :from_date AND :to_date
        GROUP BY 
            lgd.product_grade_id, collection_year, collection_month
        ORDER BY 
            collection_year ASC, collection_month ASC, quantity DESC
    "), ['farmer_id' => $farmer_id, 'from_date' => $from_date, 'to_date' => $to_date]);

    // Transform the data for the chart
    $grade_distribution_data = array_map(function ($item) {
        return [
            'name' => $item->name, // Grade name
            'quantity' => (int) $item->quantity, // Quantity
            'month_year' => $item->collection_year . '-' . str_pad($item->collection_month, 2, '0', STR_PAD_LEFT), // Combine year and month
        ];
    }, $grade_distribution);

    return $grade_distribution_data;
 }
 
}
