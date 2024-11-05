<?php

namespace App\Http\Controllers\Admin;

use App\CoopEmployee;
use App\Http\Controllers\Controller;
use App\CoopBranchDepartment;
use App\CoopBranch;
use App\User;
use Illuminate\Support\Facades\Auth;

use DB;
use Log;
use Request;

class EmployeesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
                $coop = Auth::user()->cooperative->id;

        $employees = CoopEmployee::get_employees($coop, null, 100);
        // $banks = Bank::where('cooperative_id', $coop)->get();
        $coop_branches = CoopBranch::where('cooperative_id', $coop)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->latest()->get();
        // $positions = JobPosition::where('cooperative_id', $coop)->orderBy('position')->get();
        // $types = EmploymentType::where('cooperative_id', $coop)->latest()->get();
        $countries = get_countries();


       $employees = DB::select(DB::raw("
            SELECT u.*, emp.*
            FROM coop_employees emp
            JOIN users u ON emp.user_id = u.id
        ORDER BY emp.created_at DESC;
    "));

        // return view('pages.cooperative.hr.employee.index', compact('employees', 'countries', 'departments', 'positions', 'types', 'banks'));
        return view('pages.admin.employees.index', compact("countries", "employees"));
    }

    public function store(Request $req)
    {
        $req->validate([
            'cooperative_id' => 'required',
            'country' => 'required',
            'county' => 'required|string',
            'area_of_residence' => 'required|string',
            'id_no' => 'required|unique:' . CoopEmployee::class . ',id_no',
            'phone_no' => 'required|regex:/^[0-9]{12}$/|unique:' . CoopEmployee::class . ',phone_no',
            'employee_number' => 'required|string',
            'employment_type' => 'required|string',
            'kra' => 'string|unique:' . CoopEmployee::class . ',kra',
            'nhif' => 'string|unique:' . CoopEmployee::class . ',nhif_no',
            'nssf' => 'string|unique:' . CoopEmployee::class . ',nssf_no',
            'first_name' => 'required|string',
            'other_names' => 'required|string',
            'email' => 'required|email|unique:' . User::class . ',email',
            'user_name' => 'required|unique:' . User::class . ',username',
            'marital_status' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'basic_salary' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'job_group' => 'required',
            'bank_account_name' => 'required',
            'profile_picture' => "sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);

        try {
            DB::beginTransaction();

            // save user

            // save employee

            DB::commit();
            toastr()->success('Employee Created Successfully');
        } catch (\Throwable $th) {
            Log::error("----------------------------------------");
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('Employee could not be created');
            return redirect()->back()->withInput();
        }
    }

}