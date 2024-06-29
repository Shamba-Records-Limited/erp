<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankBranch;
use App\Events\AuditTrailEvent;
use App\Events\NewUserRegisteredEvent;
use App\Exports\FarmersExport;
use App\Farmer;
use App\Imports\FarmerImport;
use App\Location;
use App\Product;
use App\Route;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class FarmerController extends Controller
{

    use \App\Http\Traits\Farmer;

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative->id;
        $routes = Route::select('name', 'id')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        $banks = Bank::select('name', 'id')->where('cooperative_id', $coop)->latest()->get();
        $products = Product::select('id', 'name')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        $farmers = $this->farmers($request, $coop, 10);
        $countries = get_countries();
        $locations = Location::select('id', 'name')
            ->where('cooperative_id', $coop)
            ->orderBy('name')
            ->get();
        return view('pages.farmer.index',
            compact('routes', 'products', 'farmers', 'countries', 'banks', 'locations'));
    }

    private function farmers($request, $coop, $limit)
    {

        $farmers = Farmer::select("farmers.*")->join('users', 'users.id', '=', 'farmers.user_id')
            ->join('bank_branches', 'farmers.bank_branch_id', '=', 'bank_branches.id')
            ->join('banks', 'banks.id', '=', 'bank_branches.bank_id')
            ->where('users.cooperative_id', $coop);
        if ($request) {
            if ($request->country) {
                $farmers = $farmers->where('farmers.country_id', $request->country);
            }

            if ($request->route) {
                $farmers = $farmers->where('farmers.route_id', $request->route);
            }

            if ($request->location) {
                $farmers = $farmers->where('farmers.location_id', $request->location);
            }

            if ($request->customer_type) {
                $farmers = $farmers->where('farmers.customer_type', $request->customer_type);
            }

            if (property_exists($request, 'gender') || $request->gender) {
                $farmers = $farmers->where('farmers.gender', $request->gender);
            }

            if ($request->bank) {
                $farmers = $farmers->where('banks.id', $request->bank);
            }

            if ($request->name) {
                $farmers = $farmers
                    ->where('users.first_name', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('users.other_names', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->dob) {
                $dates = split_dates($request->dob);
                $from = $dates['from'];
                $to = $dates['to'];
                $farmers = $farmers->whereBetween('farmers.dob', [$from, $to]);
            }

            if ($request->member_no) {
                $farmers = $farmers->where('member_no', $request->member_no);
            }
        }

        if ($limit) {
            return $farmers->limit($limit)
                ->orderBy('users.created_at', 'desc')->get();
        } else {
            return $farmers->orderBy('users.created_at', 'desc')->get();
        }
    }

    public function store(Request $req)
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
            'member_no' => 'required|string|unique:farmers,member_no',
            'bank_branch_id' => 'required|string',
            'customer_type' => 'required|string',
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'u_name' => 'required|unique:users,username',
            'products' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'farm_size' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'profile_picture' => "sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);


        try {
            $auth_user = Auth::user();
            DB::beginTransaction();
            //generate password
            $password = generate_password();

            //new user and farmer objecr
            $user = new User();
            $farmer = new Farmer();
            //save user and farmer
            $user = $this->saveUser($req, $user, $password, $auth_user->cooperative_id);
            $new_user = $user->refresh();
            $this->saveFarmer($req, $new_user, $farmer, $req->member_no);
            //assign role to user
            $role = Role::select('id', 'name')->where('name', '=', 'farmer')->first();
            $new_user->assignRole($role->name);

            //audit trail log
            $role_created_audit = ['user_id' => $auth_user->id, 'activity' => 'Assigned ' . $role->name .
                ' to  ' . $new_user->username, 'cooperative_id' => $auth_user->cooperative->id];
            event(new AuditTrailEvent($role_created_audit));

            //send email and new audit trail
            $data = ["name" => ucwords(strtolower($req->f_name)) . ' ' . ucwords(strtolower($req->o_names)),
                "email" => $req->user_email, "password" => $password];
            $audit_trail_data = ['user_id' => $auth_user->id, 'activity' => 'Created ' . $new_user->username . 'account', 'cooperative_id' => $auth_user->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            event(new NewUserRegisteredEvent($data));
            DB::commit();
            toastr()->success('Farmer Created Successfully');
            return redirect()->route('cooperative.farmers.show');

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }

    }


    public function get_bank_branches($bank_id)
    {
        return BankBranch::getByBankId($bank_id);
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export_farmers($type, Request $request)
    {
        $cooperative = Auth::user()->cooperative;

        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $farmers = $this->farmers($request, $cooperative->id, null);
        $file_name = 'farmers_' . date('d_m_Y') . '.' . $type;
        if ($type != env('PDF_FORMAT')) {
            return Excel::download(new FarmersExport($farmers), $file_name);
        } else {
            $data = [
                'title' => 'Farmers',
                'pdf_view' => 'farmers',
                'records' => $farmers,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }

    }


    public function edit_farmer($farmerId)
    {
        $farmer = Farmer::findOrFail($farmerId);
        $coop = $farmer->user->cooperative_id;
        $routes = Route::select('name', 'id')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        $banks = Bank::select('name', 'id')->where('cooperative_id', $coop)->latest()->get();
        $products = Product::select('id', 'name')->where('cooperative_id', $coop)->orderBy('name', 'asc')->get();
        $countries = get_countries();
        return view('pages.farmer.edit', compact('farmer', 'countries', 'routes', 'banks', 'products'));
    }

    public function update_farmer_profile(Request $request, $farmerId)
    {
        $this->validate($request, [
            'country_id' => 'required|string',
            'county' => 'required|string',
            'location' => 'required',
            'id_no' => 'required|string',
            'phone_no' => 'required|regex:/^[0-9]{10}$/',
            'route_id' => 'required|string',
            'bank_account' => 'required|string',
            'member_no' => 'required|string',
            'bank_branch_id' => 'required|string',
            'customer_type' => 'required|string',
            'kra' => 'required|string',
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email',
            'u_name' => 'required',
            'products' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'farm_size' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'profile_picture' => "sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($farmerId);
            $this->update_profile($user, $request);
            $auth_user = Auth::user();

            $audit_trail_data = ['user_id' => $auth_user->id,
                'activity' => 'Updated  ' . $user->username . 'Profile',
                'cooperative_id' => $auth_user->cooperative_id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success("Profile updated successfully");
            return redirect()->back();

        } catch (Exception $ex) {
            Log::error("Error occured: " . $ex->getMessage());
            DB::rollBack();
            toastr()->error("Oops! Error occurred");
            return redirect()->back();
        }
    }


    public function importFarmers(Request $request)
    {

        $this->validate($request, [
            'farmers' => 'required'
        ]);


        try {
            DB::beginTransaction();
            if ($request->hasFile('farmers')) {
                Excel::import(new FarmerImport, $request->farmers);
            }
            toastr()->success("Farmers added successfully");
            DB::commit();
            return redirect()->back();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $uploadErrors = [];
            foreach ($failures as $failure) {
                $uploadErrors[] = [
                    'Row #' . $failure->row() . ' ' . $failure->attribute() . ': ' . $failure->errors()[0] . ' (' . $failure->values()[$failure->attribute()] . ')'
                ];
            }

            toastr()->error('Upload was not successful');
            return redirect()->back()->with(['uploadErrors' => $uploadErrors]);
        }
    }
}
