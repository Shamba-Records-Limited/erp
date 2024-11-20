<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use App\CoopBranch;
use App\CoopBranchDepartment;
use App\CoopEmployee;
use App\EmployeeBankDetail;
use App\EmployeeEmploymentType;
use App\EmployeePosition;
use App\EmployeeSalary;
use App\EmploymentType;
use App\Events\AuditTrailEvent;
use App\Http\Controllers\Controller;
use App\Http\Traits\Employee;
use App\JobPosition;
use App\User;
use DB;
use Hash;
use Log;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    use Employee;

    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index()
    {
        $users = DB::select(DB::raw("
                SELECT u.id, u.username, u.first_name, u.other_names, u.email, u.profile_picture, c.name as coop_name
                FROM users u
                JOIN cooperatives c ON u.cooperative_id = c.id
                ORDER BY u.created_at DESC;
            "));

        $cooperatives = DB::select(DB::raw("
                SELECT c.id, c.name FROM cooperatives c;
            "));

        return view('pages.admin.users.index', compact('users', 'cooperatives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cooperative_id' => 'required',
            'username' => 'required|unique:users,username',
            'first_name' => 'required',
            'other_names' => 'required',
            'email' => 'required|email|unique:users,email',
            'profile_picture' => "sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);

        try {
            $user = new User();
            $user->first_name = ucwords(strtolower($request->first_name));
            $user->other_names = ucwords(strtolower($request->other_names));
            $user->cooperative_id = $request->cooperative_id;
            $user->email = $request->email;
            $user->username = $request->username;

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filePath = $file->store('uploads/profile_pictures', 'public'); // Save in 'storage/app/public'
                $user->profile_picture = $filePath; // Save file path to the database
            }

            $password = generate_password();
            $user->password = Hash::make($password);

            $user->save();

            toastr()->success('User created successfully');
            return redirect()->route('admin.users.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('User could not be created');
            return redirect()->back()->withInput();
        }
    }

    public function detail($id)
    {
        $results =  DB::select(DB::raw("
                SELECT
                    u.id,
                    u.username,
                    u.first_name,
                    u.other_names,
                    u.email,
                    c.name as coop_name,
                    official.id as official_id,
                    official_county.name as official_county,
                    official.gender as official_gender,
                    official.id_no as official_id_no,
                    official.phone_no as official_phone_no,
                    official.employee_no as official_employee_no,
                    official.country_code as official_country_name,
                    employee.id as employee_id,
                    employee.country_code as employee_country_name,
                    employee.county_of_residence as employee_county,
                    employee.area_of_residence as employee_residence_area,
                    employee.marital_status as employee_marital_status,
                    employee.dob as employee_dob,
                    employee.gender as employee_gender,
                    employee.id_no as employee_id_no,
                    employee.phone_no as employee_phone_no,
                    employee.employee_no as employee_employee_no,
                    employee.kra as employee_kra,
                    employee.nhif_no as employee_nhif,
                    employee.nssf_no as employee_nssf,
                    employee_department.name as employee_department_name
                FROM users u
                JOIN cooperatives c ON u.cooperative_id = c.id

                LEFT JOIN county_govt_officials official ON official.user_id = u.id
                LEFT JOIN counties official_county ON official_county.id = official.county_id

                LEFT JOIN coop_employees employee ON employee.user_id = u.id
                LEFT JOIN coop_branch_departments employee_department ON employee_department.id = employee.department_id

                WHERE u.id = :id
            "), ["id" => $id]);

        $user = null;
        if (count($results) > 0) {
            $user = $results[0];
        }

        return view('pages.admin.users.detail', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required',
            'first_name' => 'required',
            'other_names' => 'required',
            'email' => 'required|email',
            'profile_picture' => 'sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072',
        ]);

        try {
            $user = User::findOrFail($id);

            // Update user details
            $user->username = $request->username;
            $user->first_name = $request->first_name;
            $user->other_names = $request->other_names;
            $user->email = $request->email;

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Log the file upload
                Log::info('Profile picture uploaded for user: ' . $user->id);

                // Delete old profile picture if it exists
                if ($user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture))) {
                    unlink(public_path('storage/' . $user->profile_picture));
                    Log::info('Old profile picture deleted: ' . $user->profile_picture);
                }

                // Save the new profile picture
                $file = $request->file('profile_picture');
                $filePath = $file->store('uploads/profile_pictures', 'public');
                $user->profile_picture = $filePath;

                Log::info('New profile picture stored at: ' . $filePath);
            }

            $user->save();

            Log::info('User updated successfully: ' . $user->id);

            return redirect()->route('admin.users.show')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update user']);
        }
    }


    public function viewMakeEmployee($id)
    {
        $results =  DB::select(DB::raw("
                SELECT u.id, u.username, u.first_name, u.other_names, u.email, c.id as coop_id, c.name as coop_name
                FROM users u
                JOIN cooperatives c ON u.cooperative_id = c.id
                WHERE u.id = :id
            "), ["id" => $id]);

        $user = null;
        if (count($results) > 0) {
            $user = $results[0];
        }

        $countries = get_countries();
        $banks = Bank::where('cooperative_id', $user->coop_id)->get();
        $coop_branches = CoopBranch::where('cooperative_id', $user->coop_id)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->latest()->get();
        $positions = JobPosition::where('cooperative_id', $user->coop_id)->orderBy('position')->get();
        $types = EmploymentType::where('cooperative_id', $user->coop_id)->latest()->get();

        return view('pages.admin.users.make-employee', compact('user', 'countries', 'banks', 'departments', 'types', 'positions'));
    }

    public function makeEmployee(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'country' => 'required',
            'county' => 'required|string',
            'area_of_residence' => 'required|string',
            'id_no' => 'required|unique:' . CoopEmployee::class . ',id_no',
            'phone_no' => 'required|regex:/^[0-9]{12}$/|unique:' . CoopEmployee::class . ',phone_no',
            'employee_number' => 'required|string',
            'kra' => 'string|unique:' . CoopEmployee::class . ',kra',
            'nhif' => 'string|unique:' . CoopEmployee::class . ',nhif_no',
            'nssf' => 'string|unique:' . CoopEmployee::class . ',nssf_no',
            'first_name' => 'required|string',
            'other_names' => 'required|string',
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
            //generate password
            $password = generate_password();

            $user = User::find($request->user_id);

            //employee...
            $employee = new CoopEmployee();
            $new_employee = $this->persist($request, $user->id, $employee);
            Log::debug("Saved employee: $new_employee");
            //assign role to user
            $role = Role::select('id', 'name')->where('name', '=', 'employee')->first();
            $user->assignRole($role->name);
            //bank details
            $account_details = new EmployeeBankDetail();
            $account_details->account_name = $request->bank_account_name;
            $account_details->account_number = $request->bank_account;
            $account_details->bank_branch_id = $request->bank_branch_id;
            $account_details->employee_id = $new_employee;
            $account_details->bank_id = $request->bank_id;
            $account_details->save();
            Log::debug("Saved employee bank details: " . $account_details->refresh()->id);
            //employment type
            $employment_type = new EmployeeEmploymentType();
            $employment_type->employment_type_id = $request->employment_type;
            $employment_type->employee_id = $new_employee;
            $employment_type->save();
            Log::debug("Saved employee employment type: " . $employment_type->refresh()->id);
            //position
            $employment_position = new EmployeePosition();
            $employment_position->position_id = $request->position;
            $employment_position->employee_id = $new_employee;
            $employment_position->save();
            Log::debug("Saved employee position: " . $employment_position->refresh()->id);

            $employeeSalary = new EmployeeSalary();
            $employeeSalary->employee_id = $new_employee;
            $employeeSalary->job_group = $request->job_group;
            $employeeSalary->amount = $request->basic_salary;
            $employeeSalary->save();
            Log::debug("Saved employee salary: " . $employeeSalary->refresh()->id);
            //audit trail log
            $role_created_audit = ['user_id' => $user->id, 'activity' => 'Assigned ' . $role->name .
                ' to  ' . $user->username, 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($role_created_audit));

            //send email and new audit trail
            $data = [
                "name" => ucwords(strtolower($request->first_name)) . ' ' . ucwords(strtolower($request->other_names)),
                "email" => $request->email, "password" => $password
            ];
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => 'Created ' . $user->username . 'account',
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            // event(new NewUserRegisteredEvent($data));

            DB::commit();
            toastr()->success('Employee Created Successfully');
            return redirect()->route('admin.users.show');
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("----------------------------------------");
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('Employee could not be created');
            return redirect()->back()->withInput();
        }

        return redirect()->route('admin.users.detail', $request->user_id);
    }

    public function viewMakeCountyGovtAcc($id)
    {
        $results =  DB::select(DB::raw("
                SELECT u.id, u.username, u.first_name, u.other_names, u.email, c.id as coop_id, c.name as coop_name
                FROM users u
                JOIN cooperatives c ON u.cooperative_id = c.id
                WHERE u.id = :id
            "), ["id" => $id]);

        $user = null;
        if (count($results) > 0) {
            $user = $results[0];
        }

        $countries = get_countries();
        $banks = Bank::where('cooperative_id', $user->coop_id)->get();
        $coop_branches = CoopBranch::where('cooperative_id', $user->coop_id)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->latest()->get();
        $positions = JobPosition::where('cooperative_id', $user->coop_id)->orderBy('position')->get();
        $types = EmploymentType::where('cooperative_id', $user->coop_id)->latest()->get();

        return view('pages.admin.users.make-county-govt-official', compact('user', 'countries', 'banks', 'departments', 'types', 'positions'));
    }

    public function makeCountyGovtAcc(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'country' => 'required',
            'county' => 'required|string',
            'id_no' => 'required|unique:' . CoopEmployee::class . ',id_no',
            'phone_no' => 'required|regex:/^[0-9]{12}$/|unique:' . CoopEmployee::class . ',phone_no',
            'employee_number' => 'required|string',
            'first_name' => 'required|string',
            'other_names' => 'required|string',
            'gender' => 'required',
            'profile_picture' => "sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);
    }

    public function edit($id)
    {
        // $branch = CoopBranch::find($id);
        $users = DB::select(DB::raw("
                    SELECT u.*, c.name as coop_name FROM users u
                    JOIN cooperatives c ON u.cooperative_id = c.id
                    WHERE u.id = :id;
                "), ["id" => $id]);

        $user = null;
        if (count($users) > 0) {
            $user = $users[0];
        }

        return view('pages.admin.users.edit', compact('user', 'id'));
    }
}
