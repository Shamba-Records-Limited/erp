<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\County;
use App\Farmer;
use App\FarmerCooperative;
use App\Http\Controllers\Controller;
use App\Imports\CooperativeAdmin\FarmerImport;
use App\SubCounty;
use App\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Maatwebsite\Excel\Facades\Excel;

class FarmersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function farmer_mini_dashboard(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative;
        $coop_id = $coop->id;

        // gender distribution
        $gender_distribution = DB::select(DB::raw("select
            count(case when gender='M' then 1 end) as male,
            count(case when gender='F' then 1 end) as female,
            count(case when gender='X' then 1 end) as other
            from farmers f
            JOIN farmer_cooperative fc ON fc.farmer_id = f.id AND fc.cooperative_id = :coop_id
        "), ["coop_id" => $coop_id])[0];

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
                            CASE WHEN :gender = 'all' THEN 1 ELSE f.gender = :gender1 END
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
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($dailyQuery), [
                    "from_date" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
                ]);
            }

            $gender = "M";
            $maleCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
            ]);

            $gender = "F";
            $femaleCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
            ]);

            $gender = "X";
            $otherGenderCollections = DB::select(DB::raw($dailyQuery), [
                "from_date" => $from_date,
                "to_date" => $to_date,
                "gender" => $gender,
                "gender1" => $gender,
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
                            CASE WHEN :gender = 'all' THEN 1 ELSE f.gender = :gender1 END
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
            ]);
            if ($from_date_prev != "") {
                $prevCollections = DB::select(DB::raw($monthlyQuery), [
                    "from_date" => $from_date_prev,
                    "from_date1" => $from_date_prev,
                    "to_date" => $to_date_prev,
                    "gender" => $gender,
                    "gender1" => $gender,
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

        // age distribution
        $maleAges = [];
        $femaleAges = [];
        $otherAges = [];

        $ageQuery = "
            WITH RECURSIVE AgeGroups AS (
                -- Anchor member: starting group
                SELECT
                    1 AS start_age,
                    10 AS end_age
                UNION ALL
                -- Recursive member: generate groups in increments of 10
                SELECT
                    start_age + 10,
                    end_age + 10
                FROM
                    AgeGroups
                WHERE
                    start_age + 10 <= 100  -- Adjust this value based on your max age range
            ),
            FarmerAges AS (
                SELECT
                    id,
                    FLOOR(DATEDIFF(CURDATE(), f.dob) / 365) AS age
                FROM
                    farmers f
                WHERE CASE WHEN :gender = 'all' THEN 1 ELSE f.gender = :gender1 END
            )
            SELECT
                CONCAT(ag.start_age, '-', ag.end_age) AS x,
                COUNT(fa.id) AS y
            FROM
                AgeGroups ag
            LEFT JOIN FarmerAges fa ON fa.age BETWEEN ag.start_age AND ag.end_age
            GROUP BY
                ag.start_age, ag.end_age
            ORDER BY
                ag.start_age;
        ";

        $gender = "M";
        $maleAges = DB::select(DB::raw($ageQuery), [
            "gender" => $gender,
            "gender1" => $gender,
        ]);

        $gender = "F";
        $femaleAges = DB::select(DB::raw($ageQuery), [
            "gender" => $gender,
            "gender1" => $gender,
        ]);



        $data = [
            'gender' => $gender_distribution,
            "male_collections" => $maleCollections,
            "female_collections" => $femaleCollections,
            "other_gender_collections" => $otherGenderCollections,
            "male_ages" => $maleAges,
            "female_ages" => $femaleAges,
            "other_ages" => $otherAges,
        ];
        return view('pages.cooperative-admin.farmers.mini-dashboard', compact('data'));
    }

    public function index()
    {
        $user = Auth::user();
        $coop_id = $user->cooperative->id;

        $routes = [];
        $banks = [];
        $products = [];
        $locations = [];
        $farmers = [];
        // $routes = Route::select('name', 'id')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $banks = Bank::select('name', 'id')->where('cooperative_id', $coop)->latest()->get();
        // $products = Product::select('id', 'name')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $farmers = $this->farmers($request, $coop, 10);
        // $locations = Location::select('id', 'name')
        //     ->where('cooperative_id', $coop)
        //     ->orderBy('name')
        //     ->get();

        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    f.gender,
                    u.username,
                    u.first_name,
                    u.other_names,
                    county.name as county_name,
                    sub_county.name as sub_county_name,
                    (SELECT SUM(c.quantity) FROM collections c WHERE c.farmer_id=f.id) AS total_collection_quantity
                FROM farmers f
                JOIN users u ON f.user_id = u.id
                LEFT JOIN counties county ON county.id = f.county_id
                LEFT JOIN sub_counties sub_county ON sub_county.id = f.sub_county_id
                JOIN farmer_cooperative fc ON fc.farmer_id = f.id AND fc.cooperative_id = :coop_id;
            "),["coop_id" => $coop_id]);


        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.cooperative-admin.farmers.index', compact('farmers', 'counties', 'sub_counties'));

        // return view(
        //     'pages.cooperative-admin.farmers.index',
        //     compact('routes', 'products', 'farmers', 'banks', 'locations')
        // );
    }

    public function view_add_new(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative->id;

        $routes = [];
        $banks = [];
        $products = [];
        $locations = [];
        $farmers = [];
        // $routes = Route::select('name', 'id')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $banks = Bank::select('name', 'id')->where('cooperative_id', $coop)->latest()->get();
        // $products = Product::select('id', 'name')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $farmers = $this->farmers($request, $coop, 10);
        // $locations = Location::select('id', 'name')
        //     ->where('cooperative_id', $coop)
        //     ->orderBy('name')
        //     ->get();

        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    u.username
                FROM farmers f
                JOIN users u ON f.user_id = u.id
                JOIN farmer_cooperative fc ON fc.farmer_id = f.id;
            "));


        $counties = County::all();
        $sub_counties = SubCounty::all();


        return view("pages.cooperative-admin.farmers.add-new", compact('farmers', 'counties', 'sub_counties'));
    }

    public function view_add_existing(Request $request)
    {
        $search = $request->query("search", "");

        $farmers = [];
        $searchDone = false;
        if (!empty($search) && is_numeric($search)) {
            $search_int = intval($search);
            $farmers = DB::select(DB::raw("
                SELECT f.*, u.username FROM farmers f
                JOIN users u ON u.id = f.user_id
                WHERE f.id_no = :search_int OR f.member_no = :search
            "), ["search_int" => $search_int, "search" => $search]);
            $searchDone = true;
        }

        return view("pages.cooperative-admin.farmers.add-existing", compact("farmers", "search", "searchDone"));
    }

    public function add_existing(Request $request)
    {
        $request->validate([
            "farmer_id" => "required|exists:farmers,id"
        ]);

        $user = Auth::user();
        $coop = $user->cooperative;

        try {
            DB::beginTransaction();
            // check duplicate
            $isDuplicate = FarmerCooperative::where("farmer_id", $request->farmer_id)->where("cooperative_id", $coop->id)->exists();
            if ($isDuplicate) {
                toastr()->warning("This record will create a duplicate");
                return redirect()->back();
            }

            $farmerCooperative = new FarmerCooperative();
            $farmerCooperative->farmer_id = $request->farmer_id;
            $farmerCooperative->cooperative_id = $coop->id;
            $farmerCooperative->save();

            DB::commit();

            toastr()->success('Farmer Added Successfully');
            return redirect()->route('cooperative-admin.farmers.show');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_code' => 'required',
            'county_id' => 'required|string',
            'sub_county_id' => 'required|string',
            'member_no' => 'required|unique:farmers,member_no',
            'id_no' => 'required|unique:farmers,id_no',
            'phone_no' => 'required|regex:/^[0-9]{12}$/|unique:farmers,phone_no',
            'first_name' => 'required|string',
            'other_names' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'dob' => 'required',
            'gender' => 'required',
            'profile_picture' => "sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);

        try {
            DB::beginTransaction();

            $authUser = Auth::user();
            $authUserId = $authUser->id;
            $coop_id = $authUser->cooperative_id;

            // create user
            $user = new User();
            $user->first_name = ucwords(strtolower($request->first_name));
            $user->other_names = ucwords(strtolower($request->other_names));
            $user->cooperative_id = $request->cooperative_id;
            $user->email = $request->email;
            $user->username = $request->username;
            $password = generate_password();
            $user->password = Hash::make($password);
            save_user_image($user, $request);
            $user->save();

            // farmer
            $farmer = new Farmer();
            $farmer->user_id = $user->id;
            $farmer->country_code = $request->country_code;
            $farmer->county_id = $request->county_id;
            $farmer->sub_county_id = $request->sub_county_id;
            $farmer->member_no = $request->member_no;
            $farmer->id_no = $request->id_no;
            $farmer->member_no = $request->id_no;
            $farmer->gender = $request->gender[0];
            $farmer->phone_no = $request->phone_no;
            $farmer->dob = $request->dob;
            $farmer->save();

            // farmer cooperative
            $farmerCoop = new FarmerCooperative();
            $farmerCoop->farmer_id = $farmer->id;
            $farmerCoop->cooperative_id = $coop_id;
            $farmerCoop->save();

            $data = [
                "name" => ucwords(strtolower($request->first_name)) . ' ' . ucwords(strtolower($request->other_names)),
                "email" => $request->email, "password" => $password
            ];
            // $audit_trail_data = [
            //     'user_id' => $user->id,
            //     'activity' => 'Created ' . $user->username . 'account',
            //     'cooperative_id' => $user->cooperative->id
            // ];
            // event(new AuditTrailEvent($audit_trail_data));

            toastr()->success('Farmer Created Successfully');
            DB::commit();
            return redirect()->route('cooperative-admin.farmers.show');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("----------------------------------------");
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('Farmer could not be created');
            return redirect()->back()->withInput();
        }

        return redirect()->back()->withInput();
    }
    public function detail(Request $request, $id)
    {
        $farmers = DB::select(DB::raw("
            SELECT
                f.*,
                u.*,
                county.name as county_name,
                sub_county.name as sub_county_name,
                (SELECT SUM(c.quantity) FROM collections c WHERE c.farmer_id=f.id) AS total_collection_quantity
            FROM farmers f
            JOIN users u ON f.user_id = u.id
            LEFT JOIN counties county ON county.id = f.county_id
            LEFT JOIN sub_counties sub_county ON sub_county.id = f.sub_county_id
            WHERE f.id = :id;
        "), ["id" => $id]);

        $farmer = null;
        if (count($farmers) > 0) {
            $farmer = $farmers[0];
        }

        $tab = $request->query('tab', 'collections');

        $farmerCollections = DB::select(DB::raw("
            SELECT
                c.*, p.name
            FROM collections c
            JOIN products p ON c.product_id = p.id
            WHERE c.farmer_id = :farmer_id;
        "), ["farmer_id" => $id]);

        return view('pages.cooperative-admin.farmers.detail', compact('farmer', 'tab', 'farmerCollections'));
    }

    function import_bulk(Request $request)
    {
        $this->validate($request, [
            'farmers' => 'required'
        ]);

        try {
            if ($request->hasFile('farmers')) {
                Excel::import(new FarmerImport, $request->farmers);
            }
            toastr()->success("Farmers created successfully");
            return redirect()->back();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $uploadErrors = [];
            foreach ($failures as $failure) {
                $uploadErrors[] = [
                    'Row #' . $failure->row() . ' ' . $failure->attribute() . ': ' . $failure->errors()[0] . ' (' . $failure->values()[$failure->attribute()] . ')'
                ];
            }
            toastr()->error('Upload was  not successful');
            return redirect()->back()->with(['uploadErrors' => $uploadErrors]);
        }
    }
}
