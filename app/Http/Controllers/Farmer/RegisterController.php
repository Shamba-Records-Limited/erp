<?php

namespace App\Http\Controllers\Farmer;

use App\Bank;
use App\BankBranch;
use App\Cooperative;
use App\County;
use App\Farmer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FarmerController;
use App\Location;
use App\Product;
use App\Route;
use App\SubCounty;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    use RegistersUsers, \App\Http\Traits\Farmer;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        $countries = get_countries();
        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('auth.farmer_register', compact('countries', 'counties', 'sub_counties'));
    }

    public function bank_branches_by_bank($bankId)
    {
        return BankBranch::getByBankId($bankId);
    }

    public function register(Request $req)
    {
        $age = Carbon::parse($req->dob)->age;
        if ($age < 18) {
            toastr()->error('Farmer is below 18 years');
            return redirect()->back()->withInput();
        }
        $this->validate($req, [
            'country_code' => 'required|string',
            'county_id' => 'required|string|exists:counties,id',
            'sub_county_id' => 'required|string|exists:sub_counties,id',
            'id_no' => 'required|string',
            'phone_no' => 'required|regex:/^[0-9]{10}$/|unique:cooperatives,contact_details',
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'u_name' => 'required|unique:users,username',
            'dob' => 'required',
            'gender' => 'required',
            'password' => 'required_with:c_password|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
            'c_password' => 'required|same:password'
        ]);

        try {
            DB::beginTransaction();
            $member_no = generate_member_number();
            $cooperative = Cooperative::where('default_coop', 1)->first();
            $user = new User();
            $farmer = new Farmer();
            $user = $this->saveUser($req, $user, $req->password,$cooperative->id);
            $new_user = $user->refresh();
            $this->saveFarmer($req, $new_user, $farmer, $member_no);
            $role = Role::select('id', 'name')->where('name', '=', 'farmer')->first();
            $new_user->assignRole($role->name);
            $this->guard()->login($new_user);
            toastr()->success('Farmer Registered Successfully');
            DB::commit();
            return redirect("/dashboard");
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }

}
