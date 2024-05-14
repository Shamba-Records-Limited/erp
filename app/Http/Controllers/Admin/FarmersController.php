<?php

namespace App\Http\Controllers\Admin;

use App\County;
use App\Events\AuditTrailEvent;
use App\Farmer;
use App\Http\Controllers\Controller;
use App\SubCounty;
use App\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;

class FarmersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $farmers = DB::select(DB::raw("
                SELECT
                    f.id,
                    c.name as country_name,
                    u.username
                FROM farmers f
                JOIN countries c ON f.country_id = c.id
                JOIN users u ON f.user_id = u.id;
            "));


        $countries = get_countries();
        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.admin.farmers.index', compact('farmers', 'countries', 'counties', 'sub_counties'));
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
            return redirect()->route('admin.farmers.show');
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
    }
}
