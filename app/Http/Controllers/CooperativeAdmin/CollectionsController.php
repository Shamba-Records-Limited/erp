<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Collection;
use App\CoopBranch;
use App\Cooperative;
use App\Exports\CollectionExport;
use App\Http\Controllers\Controller;
use App\Imports\CooperativeAdmin\CollectionImport;
use App\Lot;
use App\Product;
use App\Unit;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Log;

class CollectionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $coop_id = Auth::user()->cooperative->id;
        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    u.username
                FROM farmers f
                JOIN users u ON f.user_id = u.id
                JOIN farmer_cooperative fc ON fc.farmer_id = f.id AND fc.cooperative_id = :coop_id;
            "), ["coop_id" => $coop_id]);

        $grading = DB::select(DB::raw("
            SELECT g.id, g.name FROM product_grades g;
        "));

        $products = DB::select(DB::raw("
            SELECT p.* FROM products p;
        "));

        $coop = Auth::user()->cooperative;
        // if user is assigned to branch use the default product for branch
        $default_product_id = null;
        $default_product_ids = DB::select(DB::raw("
            SELECT c.main_product_id FROM cooperatives c
            WHERE c.id = :id
        "), ["id" => $coop->id]);
        if (count($default_product_ids) > 0) {
            $default_product_id = $default_product_ids[0]->main_product_id;
        }

        $coopBranches = DB::select(DB::raw("
            SELECT b.id, b.name FROM coop_branches b
            WHERE b.cooperative_id = :id
        "), ["id" => $coop->id]);


        $units = Unit::all();

        $collections = DB::select(DB::raw("
            SELECT usr.username, p.name as product_name, quantity, c.*, pc.unit,
                f.id as farmer_id, usr.first_name, usr.other_names, f.member_no
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            WHERE c.cooperative_id = :id
            ORDER BY c.created_at DESC;
        "), ["id" => $coop->id]);

        return view('pages.cooperative-admin.collections.index', compact(
            'collections',
            'products',
            'farmers',
            'grading',
            'default_product_id',
            'coopBranches'
        ));
    }

    public function store(Request $request)
    {
        $units = [];
        foreach (config('enums.units') as $k => $u) {
            $units[] = $k;
        }


        $request->validate([
            "coop_branch_id" => "required",
            "farmer_id" => "required",
            "product_id" => "required",
            "quantity" => "required",
            "unit" => [
                "required",
                Rule::in($units),
            ],
            "collection_time" => "required",
        ]);

        $coop_id = Auth::user()->cooperative_id;
        $coop = Cooperative::find($coop_id);
        if (is_null($coop)) {
            toastr()->error("You are not assigned to any cooperative");
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            // creating lot
            $now = Carbon::now();
            $now_str = strtoupper($now->format('Ymd'));
            $date_str = $now->format('Y-m-d') . " 00:00:00";

            $dateAfter_str = $now->format('Y-m-d') . " 23:59:59";

            $lot_count = Lot::where('created_at', '>=', $date_str)
                ->where('created_at', '<', $dateAfter_str)
                ->count();

            $lot_ind = $lot_count + 1;

            $lot_number =  'LOT' . $now_str . $lot_ind;
            try {
                $lot = Lot::where('cooperative_id', $coop->id)
                    ->where('created_at', '<', $dateAfter_str)
                    ->where('created_at', '>=', $date_str)
                    ->firstOrFail();
            } catch (\Throwable $th) {
                $lot = new Lot();
                $lot->cooperative_id = $coop->id;
                $lot->lot_number = $lot_number;
                $lot->available_quantity = $request->quantity;
                $lot->save();
            }

            $lot->available_quantity += floatval($request->quantity);
            $lot->save();

            // creating collection
            $collection_count = Collection::where('cooperative_id', $coop->id)
                ->where('lot_number', $lot->lot_number)
                ->count();

            $collection_ind = $collection_count + 1;

            $collection_number = 'COL' . $now_str . $collection_ind;

            $collection = new Collection();
            $collection->lot_number = $lot->lot_number;
            $collection->collection_number = $collection_number;
            $collection->cooperative_id = $coop->id;
            $collection->coop_branch_id = $request->coop_branch_id;
            $collection->farmer_id = $request->farmer_id;
            $collection->product_id = $request->product_id;
            $collection->quantity = $request->quantity;
            $collection->collection_time = $request->collection_time;
            $collection->comments = $request->comments;
            $collection->date_collected = Carbon::now();
            $collection->save();


            DB::commit();
            toastr()->success('Collection Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }


        return redirect()->route('cooperative-admin.collections.show');
    }

    public function export_collection($type)
    {
        $cooperative = Auth::user()->cooperative->id;
        // if ($request->request_data == '[]') {
        //     $request = null;
        // } else {
        //     $request = json_decode($request->request_data);
        // }

        $collections = Collection::where("cooperative_id", $cooperative)->get();

        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('collections_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new CollectionExport($collections), $file_name);
        } else {
            $data = [
                'title' => 'Collections',
                'pdf_view' => 'collections',
                'records' => $collections,
                'filename' => strtolower('collections_' . date('d_m_Y')),
                'orientation' => 'portrait'
            ];
            return download_pdf($data);
        }
    }

    public function collections_mini_dashboard(Request $request)
    {
        // collection over time
        $date_range = $request->query("date_range", "week");
        $from_date = $request->query("from_date", "");
        $to_date = $request->query("from_date", "");
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
        if ($suggested_chart_mode == "daily") {
            $dailyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT :from_date AS date
                    UNION ALL
                    SELECT DATE_ADD(date, INTERVAL 1 DAY)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 DAY) <= :to_date
                )
                SELECT date_series.date AS x, IFNULL(SUM(collections.quantity), 0) AS y FROM date_series
                LEFT JOIN collections ON date_series.date = collections.date_collected
                GROUP BY date_series.date;
            ";
            $collections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($dailyQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                ]);
            }
        } else if ($suggested_chart_mode == "monthly") {
            $monthlyQuery = "
                WITH RECURSIVE date_series AS (
                    SELECT DATE_FORMAT(:from_date, '%Y-%b') AS month_year, :from_date AS date
                    UNION ALL
                    SELECT DATE_FORMAT(DATE_ADD(date, INTERVAL 1 MONTH), '%Y-%b'), DATE_ADD(date, INTERVAL 1 MONTH)
                    FROM date_series
                    WHERE DATE_ADD(date, INTERVAL 1 MONTH) <= :to_date
                )
                SELECT date_series.month_year AS x, IFNULL(SUM(c.quantity), 0) AS y FROM date_series
                LEFT JOIN collections c ON DATE_FORMAT(c.date_collected, '%Y-%b') = date_series.month_year
                GROUP BY date_series.month_year;
            ";
            $collections = DB::select(DB::raw($monthlyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($monthlyQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                ]);
            }
        }

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

    $data = [
        "collections" => $collections,
        "collectionTimeData" => $collectionTimeData,
    ];

    return view("pages.cooperative-admin.collections.mini-dashboard", compact(
        'data', 'date_range', 'from_date', 'to_date'
    ));
    }

    function import_bulk(Request $request)
    {
        $this->validate($request, [
            'collections' => 'required'
        ]);

        try {
            if ($request->hasFile('collections')) {
                Excel::import(new CollectionImport, $request->collections);
            }
            toastr()->success("Collections created successfully");
            return redirect()->back();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $uploadErrors = [];
            foreach ($failures as $failure) {
                $uploadErrors[] = ['Row #' . $failure->row() . ' ' . $failure->attribute() . ': ' . $failure->errors()[0] . ' (' . $failure->values()[$failure->attribute()] . ')'];
            }
            toastr()->error('Upload was  not successful');
            Session::flash('uploadErrors', $uploadErrors);
            return redirect()->back();
        }
    }
}