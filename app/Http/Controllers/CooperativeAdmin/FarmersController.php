<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\County;
use App\Farmer;
use App\FarmerCooperative;
use App\Http\Controllers\Controller;
use App\SubCounty;
use App\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class FarmersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $coop = $user->cooperative->id;

        $routes = [];
        $banks = [];
        $products = [];
        $countries = [];
        $locations = [];
        $farmers = [];
        // $routes = Route::select('name', 'id')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $banks = Bank::select('name', 'id')->where('cooperative_id', $coop)->latest()->get();
        // $products = Product::select('id', 'name')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $farmers = $this->farmers($request, $coop, 10);
        // $countries = get_countries();
        // $locations = Location::select('id', 'name')
        //     ->where('cooperative_id', $coop)
        //     ->orderBy('name')
        //     ->get();

        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    c.name as country_name,
                    u.username
                FROM farmers f
                JOIN countries c ON f.country_id = c.id
                JOIN users u ON f.user_id = u.id
                JOIN farmer_cooperative fc ON fc.farmer_id = f.id;
            "));


        $countries = get_countries();
        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.cooperative-admin.farmers.index', compact('farmers', 'countries', 'counties', 'sub_counties'));

        // return view(
        //     'pages.cooperative-admin.farmers.index',
        //     compact('routes', 'products', 'farmers', 'countries', 'banks', 'locations')
        // );
    }

    public function view_add_new(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative->id;

        $routes = [];
        $banks = [];
        $products = [];
        $countries = [];
        $locations = [];
        $farmers = [];
        // $routes = Route::select('name', 'id')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $banks = Bank::select('name', 'id')->where('cooperative_id', $coop)->latest()->get();
        // $products = Product::select('id', 'name')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        // $farmers = $this->farmers($request, $coop, 10);
        // $countries = get_countries();
        // $locations = Location::select('id', 'name')
        //     ->where('cooperative_id', $coop)
        //     ->orderBy('name')
        //     ->get();

        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    c.name as country_name,
                    u.username
                FROM farmers f
                JOIN countries c ON f.country_id = c.id
                JOIN users u ON f.user_id = u.id
                JOIN farmer_cooperative fc ON fc.farmer_id = f.id;
            "));


        $countries = get_countries();
        $counties = County::all();
        $sub_counties = SubCounty::all();


        return view("pages.cooperative-admin.farmers.add-new", compact('farmers', 'countries', 'counties', 'sub_counties'));
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
            "),["search_int"=> $search_int, "search" => $search]);
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
            'country_id' => 'required',
            'county_id' => 'required|string',
            'sub_county_id' => 'required|string',
            'id_no' => 'required|unique:farmers,id_no',
            'phone_no' => 'required|regex:/^[0-9]{12}$/|unique:farmers,phone_no',
            'kra' => 'string|unique:farmers,kra',
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
            $farmer->country_id = $request->country_id;
            $farmer->county_id = $request->county_id;
            $farmer->sub_county_id = $request->sub_county_id;
            $farmer->id_no = $request->id_no;
            $farmer->member_no = $request->id_no;
            $farmer->gender = $request->gender[0];
            $farmer->phone_no = $request->phone_no;
            $farmer->kra = $request->kra;
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
                c.name as country_name,
                u.*,
                county.name as county_name,
                sub_county.name as sub_county_name
            FROM farmers f
            JOIN countries c ON f.country_id = c.id
            JOIN users u ON f.user_id = u.id
            LEFT JOIN counties county ON county.id = f.county_id
            LEFT JOIN sub_counties sub_county ON sub_county.id = f.sub_county_id
            WHERE f.id = :id;
        "),["id" => $id]);

        $farmer = null;
        if (count($farmers) > 0) {
            $farmer = $farmers[0];
        }

        $tab = $request->query('tab', 'collections');

        $farmerCollections = [];

        return view('pages.cooperative-admin.farmers.detail', compact('farmer', 'tab', 'farmerCollections'));
    }
}
