<?php

namespace App\Http\Controllers\Farmer;

use App\Bank;
use App\BankBranch;
use App\Cooperative;
use App\Farmer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FarmerController;
use App\Location;
use App\Product;
use App\Route;
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
        $cooperative = Cooperative::where('default_coop', 1)->first();
        $locations = Location::select('name', 'id')->where('cooperative_id', $cooperative->id)->get();
        $routes = Route::select('id', 'name')->where('cooperative_id', $cooperative->id)->get();
        $banks = Bank::select('id', 'name')->where('cooperative_id', $cooperative->id)->get();
        $products = Product::select('id', 'name')->where('cooperative_id', $cooperative->id)->get();
        $coopId = $cooperative->id;
        return view('auth.farmer_register', compact('countries', 'products', 'locations', 'banks', 'routes', 'coopId'));
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
            'country_id' => 'required|string',
            'county' => 'required|string',
            'location' => 'required',
            'id_no' => 'required|string',
            'phone_no' => 'required|regex:/^[0-9]{10}$/|unique:cooperatives,contact_details',
            'route_id' => 'required|string',
            'bank_account' => 'required|string',
            'bank_branch_id' => 'required|string',
            'customer_type' => 'required|string',
            'kra' => 'required|string',
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'u_name' => 'required|unique:users,username',
            'products' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'farm_size' => 'required|regex:/^\d+(\.\d{1,2})?$/',
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
