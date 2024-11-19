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
use App\Events\NewUserRegisteredEvent;

class MillersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Fetch millers with county and sub-county information based on the "Main" branch
        $millers = DB::select(DB::raw("
            SELECT
                m.id,
                m.name,
                m.abbreviation,
                m.email,
                m.phone_no,
                county.name as county_name,
                sub_county.name as sub_county_name
            FROM millers m
            LEFT JOIN miller_branches mb ON m.id = mb.miller_id
            LEFT JOIN counties county ON county.id = mb.county_id
            LEFT JOIN sub_counties sub_county ON sub_county.id = mb.sub_county_id
            WHERE mb.name = 'Main'
        "));

        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.admin.millers.index', compact('millers', 'counties', 'sub_counties'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "miller_name" => "required|string",
            "abbreviation" => "required|string",
            "country_code" => "required",
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
            'miller_logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Store the logo file
        $logoPath = $request->file('miller_logo')->store('logos', 'public');

        try {
            DB::beginTransaction();

            // Miller
            $miller = new Miller();
            $miller->name = $request->miller_name;
            $miller->abbreviation = $request->abbreviation;
            $miller->country_code = $request->country_code;
            $miller->email = $request->miller_email;
            $miller->address = $request->address;
            $miller->phone_no = $request->phone_no;
            $miller->logo = $logoPath;

            $miller->save();

            // Miller Branch
            $branch = new MillerBranch();
            $branch->name = "Main";
            $branch->code = "001";
            $branch->miller_id = $miller->id;
            $branch->county_id = $request->county_id;
            $branch->sub_county_id = $request->sub_county_id;
            $branch->location = $request->location;
            $branch->address = $request->address;
            $branch->save();

            // User
            $userPassword = generate_password();
            $hashedPassword = Hash::make($userPassword);

            $user = new User();
            $user->username = $request->u_name;
            $user->first_name = $request->f_name;
            $user->other_names = $request->o_names;
            $user->email = $request->user_email;
            $user->password = $hashedPassword;
            $user->save();

            // Miller Admin
            $millerAdmin = new MillerAdmin();
            $millerAdmin->miller_id = $miller->id;
            $millerAdmin->user_id = $user->id;
            $millerAdmin->save();

            // Assign Role to User
            $role = Role::select('id', 'name')->where('name', '=', 'miller admin')->first();
            $new_user = $user->refresh();
            $new_user->assignRole($role->name);

            $data = [
                "name" => ucwords(strtolower($request->f_name)),
                "email" => $request->user_email, "password" => $userPassword,
            ];
            
            event(new NewUserRegisteredEvent($data));
            DB::commit();
            toastr()->success('Miller Created Successfully');
            return redirect()->route('admin.millers.show')->withInput();

        } catch (\Throwable $th) {
            dd($th);
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

    public function showDetail($id)
    {
        // Fetch the Miller by its ID, along with related data if necessary
        $miller = Miller::with(['user', 'branches'])->findOrFail($id);

        // Return the view for the miller detail page
        return view('pages.admin.millers.detail', compact('miller'));
    }
}
