<?php

namespace App\Http\Controllers\Admin;

use App\County;
use App\Http\Controllers\Controller;
use App\Miller;
use App\MillerAdmin;
use App\MillerBranch;
use App\SubCounty;
use App\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;
use Spatie\Permission\Models\Role;

class MillersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $millers = DB::select(DB::raw("
                SELECT
                    m.*,
                    c.name as country_name
                FROM millers m
                JOIN countries c ON m.country_id = c.id;
            "));


        $countries = get_countries();
        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.admin.millers.index', compact('millers', 'countries', 'counties', 'sub_counties'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "miller_name" => "required|string",
            "abbreviation" => "required|string",
            "country_id" => "required",
            "county_id" => "required|exists:counties,id",
            "sub_county_id" => "required|exists:sub_counties,id",
            "location" => "required|string",
            "address" => "required|string",
            "miller_email" => "required|email|unique:millers,email",
            'phone_no' => 'required|regex:/^[0-9]{10,15}$/|unique:millers,phone_no',
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'u_name' => 'required|unique:users,username',
        ]);

        try {
            DB::beginTransaction();

            // miller
            $miller = new Miller();
            $miller->name = $request->miller_name;
            $miller->abbreviation = $request->abbreviation;
            $miller->country_id = $request->country_id;
            $miller->email = $request->miller_email;
            $miller->address = $request->address;
            $miller->phone_no = $request->phone_no;
            $miller->save();

            // miller branch
            $branch = new MillerBranch();
            $branch->name = "Main";
            $branch->code = "001";
            $branch->miller_id = $miller->id;
            $branch->county_id = $request->county_id;
            $branch->sub_county_id = $request->sub_county_id;
            $branch->location = $request->location;
            $branch->address = $request->address;
            $branch->save();

            // user
            $userPassword = generate_password();
            $hashedPassword = Hash::make($userPassword);
            
            $user = new User();
            $user->username = $request->u_name;
            $user->first_name = $request->f_name;
            $user->other_names = $request->o_names;
            $user->email = $request->user_email;
            $user->password = $hashedPassword;
            $user->save();

            // miller admin
            $millerAdmin = new MillerAdmin();
            $millerAdmin->miller_id = $miller->id;
            $millerAdmin->user_id = $user->id;
            $millerAdmin->save();


            //get roles
            $role = Role::select('id', 'name')->where('name', '=', 'miller admin')->first();
            $new_user = $user->refresh();
            $new_user->assignRole($role->name);


            DB::commit();
            toastr()->success('Miller Created Successfully');
            return redirect()->route('admin.millers.show')->withInput();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
}
