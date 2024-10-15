<?php

namespace App\Http\Controllers;

use App\AdvanceDeduction;
use App\Bank;
use App\EmployeeAppraisal;
use App\EmployeeDisciplinary;
use App\EmployeeSalary;
use App\Http\Traits\Employee;
use App\Imports\EmployeeImport;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\CoopEmployee;
use App\CoopBranchDepartment;
use App\BankBranch;
use App\CoopBranch;
use App\EmploymentType;
use App\EmployeeFile;
use App\JobPosition;
use App\User;
use App\EmployeeBankDetail;
use App\EmployeeEmploymentType;
use App\EmployeePosition;
use App\EmployeeLeave;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use App\Charts\EmployeeChart;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Log;

class EmployeeController extends Controller
{
    use Employee;

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {

        $coop = Auth::user()->cooperative->id;
        $employees = CoopEmployee::get_employees($coop, null, 100);
        $banks = Bank::where('cooperative_id', $coop)->get();
        $coop_branches = CoopBranch::where('cooperative_id', $coop)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->latest()->get();
        $positions = JobPosition::where('cooperative_id', $coop)->orderBy('position')->get();
        $types = EmploymentType::where('cooperative_id', $coop)->latest()->get();
        $countries = get_countries();
        return view('pages.cooperative.hr.employee.index', compact('employees', 'countries', 'departments', 'positions', 'types', 'banks'));
    }

    //downloads
    public function export_employees($type, Request $request)
    {
        $cooperative = Auth::user()->cooperative;
        $employees = CoopEmployee::get_employees($cooperative->id, $request->department, null);

        if ($type != env('PDF_FORMAT')) {
            $file_name = 'employees_' . date('d') . '_' . date('m') . '_' . date('Y') . '.' . $type;
            return Excel::download(new EmployeesExport($employees), $file_name);
        } else {

            $data = [
                'title' => 'Employees',
                'pdf_view' => 'employees',
                'records' => $employees,
                'filename' => strtolower($cooperative->name . '_employees'),
                'orientation' => 'landscape'
            ];

            return deprecated_download_pdf($data);
        }
    }

    public function files()
    {
        $coop = Auth::user()->cooperative->id;
        $employees = User::where('cooperative_id', $coop)->whereHas('employee')->with('employee.files')->latest()->get();

        return view('pages.cooperative.hr.employee.files', compact('employees'));
    }

    public function store(Request $req)
    {
        $req->validate([
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
        if ($this->createEmployee($req)) {
            return redirect()->route('hr.employees.show');
        }
        return redirect()->back()->withInput();
    }

    public function bulkImportEmployees(Request $request)
    {
        $this->validate($request, [
            'employees' => 'required'
        ]);

        try {
            if ($request->hasFile('employees')) {
                Excel::import(new EmployeeImport, $request->employees);
            }
            toastr()->success("Employees created successfully");
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

    public function update(Request $req)
    {
        $req->validate([
            'country' => 'required',
            'county' => 'required|string',
            'area_of_residence' => 'required|string',
            'id_no' => 'required||unique:' . CoopEmployee::class . ',id_no,' . $req->id,
            'phone_no' => 'required|regex:/^[0-9]{12}$/|unique:' . CoopEmployee::class . ',phone_no,' . $req->id,
            'employee_number' => 'required|string',
            'kra' => 'string|unique:' . CoopEmployee::class . ',kra,' . $req->id,
            'nhif' => 'string|unique:' . CoopEmployee::class . ',nhif_no,' . $req->id,
            'nssf' => 'string|unique:' . CoopEmployee::class . ',nssf_no,' . $req->id,
            'first_name' => 'required|string',
            'other_names' => 'required|string',
            'email' => 'required|email|unique:' . User::class . ',email,' . $req->user_id,
            'user_name' => 'required|unique:' . User::class . ',username,' . $req->user_id,
            'marital_status' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'bank_id' => 'required',
            'bank_branch_id' => 'required',
            'profile_picture' => "sometimes|nullable|image|mimes:jpeg,jpg,png,gif|max:3072",
        ]);
        try {
            DB::beginTransaction();
            //generate password
            $password = generate_password();

            //new user and farmer objecr
            $user = User::find($req->user_id);
            $new_user = $this->persist_user($req, $user, $password, true);
            Log::debug("Update: Saved a new User: $new_user->id");

            //employee...
            $employee = CoopEmployee::find($req->id);
            $this->persist($req, $new_user->id, $employee, true);
            Log::debug("Update: Saved employee: $employee");

            //bank details
            $account_details = EmployeeBankDetail::find($req->bank_detail_id);
            $account_details->account_name = $req->bank_account_name;
            $account_details->account_number = $req->bank_account;
            $account_details->bank_branch_id = $req->bank_branch_id;
            $account_details->bank_id = $req->bank_id;
            $account_details->save();
            Log::debug("Update: Saved employee bank details: " . $account_details->refresh()->id);

            $auth_user = Auth::user();
            //send email and new audit trail
            $audit_trail_data = ['user_id' => $auth_user->id,
                'activity' => 'Updated ' . $new_user->username . 'account',
                'cooperative_id' => $auth_user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('Employee updated Successfully');
            return back();
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            Log::error($th);
            DB::rollback();
            toastr()->error('Employee could not be updated');
            return back()->withInput();
        }
    }

    //show employee details
    public function show($id)
    {
        $coop = Auth::user()->cooperative->id;
        $employee = CoopEmployee::with(['coopBranch', 'user', 'country', 'department', 'position', 'bankDetails', 'files', 'employmentType'])->withTrashed()->findOrFail($id);
        $coop_branches = CoopBranch::where('cooperative_id', $coop)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->latest()->get();
        $positions = JobPosition::where('cooperative_id', $coop)->orderBy('position')->get();
        $employment_types = EmploymentType::where('cooperative_id', $coop)->latest()->get();
        $salary = EmployeeSalary::where('employee_id', $id)->first();
        $appraisalHistories = EmployeeAppraisal::where('employee_id', $id)->latest()->get();
        $disciplinaries = EmployeeDisciplinary::where('employee_id', $id)->latest()->get();
        $advance_deductions = AdvanceDeduction::where('employee_id',$id)->orderBy('start_year', 'DESC')->orderBy('start_month')->get();
        return view('pages.cooperative.hr.employee.details',
            compact('salary', 'employee', 'departments', 'positions',
                'employment_types', 'appraisalHistories', 'disciplinaries', 'advance_deductions'));
    }

    //edit employee details
    public function edit($id)
    {
        $coop = Auth::user()->cooperative->id;
        $banks = Bank::where('cooperative_id', $coop)->latest()->get();
        $coop_branches = CoopBranch::where('cooperative_id', $coop)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->latest()->get();
        $positions = JobPosition::where('cooperative_id', $coop)->latest()->get();
        $types = EmploymentType::where('cooperative_id', $coop)->latest()->get();
        $countries = get_countries();
        $employee = CoopEmployee::with(['coopBranch', 'user', 'country', 'department', 'position', 'bankDetails', 'files', 'employmentType'])->findOrFail($id);
        return view('pages.cooperative.hr.employee.edit', compact('employee', 'countries', 'departments', 'positions', 'types', 'banks'));
    }

    //employee of deprtment
    public function deptEmployees($id)
    {
        $coop = Auth::user()->cooperative->id;
        $employees = CoopEmployee::get_employees($coop, $id, null);
        $banks = Bank::where('cooperative_id', $coop)->latest()->get();
        $bank_branches = BankBranch::where('cooperative_id', $coop)->latest()->get();
        $coop_branches = CoopBranch::where('cooperative_id', $coop)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->latest()->get();
        $positions = JobPosition::where('cooperative_id', $coop)->latest()->get();
        $types = EmploymentType::where('cooperative_id', $coop)->latest()->get();
        $countries = get_countries();
        $departmentId = $id;
        return view('pages.cooperative.hr.employee.index', compact('employees', 'countries', 'departments', 'positions', 'types', 'banks', 'bank_branches', 'departmentId'));
    }

    /////REPORTS/////
    public function hrReports()
    {
        $coop = Auth::user()->cooperative->id;

        # number of departments
        $coop_branches = CoopBranch::where('cooperative_id', $coop)->pluck('id');
        $departments = CoopBranchDepartment::whereIn('branch_id', $coop_branches)->count();
        # number of employees
        $total_employees = User::where('cooperative_id', $coop)->whereHas('employee')->with('employee')->count();
        # employees on leave
        $employees_on_leaves = EmployeeLeave::whereHas('employee', function ($query) use ($coop) {
            $query->whereHas('user', function ($query2) use ($coop) {
                $query2->where('cooperative_id', $coop);
            });
        })->where('status', 1)->count();
        //employed last 1 month
        $new_employees = User::where('cooperative_id', $coop)->whereDate('created_at', '>=', now()->subMonth(1))->whereHas('employee')->with('employee')->count();
        # recruitments ongoing
        $male = User::where('cooperative_id', $coop)->whereDate('created_at', '>=', now()->subMonth(1))->whereHas('employee', function ($query) {
            $query->where('gender', 'Male');
        })->with('employee')->count();
        $female = User::where('cooperative_id', $coop)->whereDate('created_at', '>=', now()->subMonth(1))->whereHas('employee', function ($query) {
            $query->where('gender', 'Female');
        })->with('employee')->count();

        //time sheet
        $employees = User::where('cooperative_id', $coop)->whereHas('employee')->with(['employee.employeeLeave'])->get();
        //pie
        $borderColors = ["#f2f2f2", "#ccc"];
        $fillColors = ["#2bb930", "#0cf",];
        $gender_chart = new EmployeeChart();
        $gender_chart->minimalist(false);
        $gender_chart->labels(['Male', 'Female']);
        $gender_chart->dataset('Male/Female Employees', 'doughnut', [$male, $female])
            ->color($borderColors)
            ->backgroundcolor($fillColors);

        $data = [
            'departments' => $departments,
            'all_employees' => $total_employees,
            'employees_on_leave' => $employees_on_leaves,
            'new_employees' => $new_employees,
            'employees' => $employees
        ];
        return view('pages.cooperative.hr.dashboard', compact('data', 'gender_chart'));
    }

    //upload files
    public function uploadEmployeeFile(Request $request)
    {
        $request->validate([
            'file_name' => 'required',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx|max:2048',
            'employee' => 'required'
        ]);
        try {
            DB::beginTransaction();

            $user = Auth::user();
            //upload file
            $file_link = '';
            if ($request->file('file')) {
                foreach ($request->file('file') as $file) {
                    $employee_file = new EmployeeFile();
                    $employee_file->file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $employee_file->employee_id = $request->employee;

                    //upload file
                    $files = $file;
                    $destinationPath = 'files/cooperative/employee-files/'; // upload path
                    $file = "leave_" . date('YmdHis') . "." . $files->guessExtension();
                    $files->move($destinationPath, $file);

                    $file_link = '/' . $destinationPath . $file;
                    $employee_file->file_link = $file_link;
                    $employee_file->save();
                    DB::commit();
                }
            }

            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => 'Uploaded file for employee #' . $request->employee,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success('File uploaded Successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            toastr()->error('File could not be uploaded');
            return back();
        }
    }

    public function employee_disciplinary($employeeId, Request $request)
    {

        $this->validate($request, [
            'effective_date' => 'required|after:yesterday',
            'end_date' => 'sometimes|nullable|after:today',
            'disciplinary_type' => 'required',
            'reason' => 'required',
        ]);

        //validates

        if($request->end_date){
            if(Carbon::parse($request->end_date)->isBefore(Carbon::parse($request->effective_date))) {
                toastr()->error('End date should come after effective date');
                return redirect()->back();
            }
        }


        try {
            DB::beginTransaction();
            $actionedBy = Auth::user();
            $employee = CoopEmployee::findOrFail($employeeId);

            $existing_disciplinary = EmployeeDisciplinary::where('employee_id', $employeeId)
                ->where('status', EmployeeDisciplinary::STATUS_ACTIVE)
                ->first();

            if ($existing_disciplinary) {
                Log::info("Found Disciplinary Case Active, closing it first.");
                $existing_disciplinary->updated_at = Carbon::now();
                $existing_disciplinary->status = EmployeeDisciplinary::STATUS_INACTIVE;
                $existing_disciplinary->save();
            }

            $employee->status = $request->disciplinary_type;
            $employee->deleted_at = $request->disciplinary_type == CoopEmployee::STATUS_DEACTIVATED ?
                Carbon::now() : null;
            $employee->updated_at = Carbon::now();
            $employee->save();

            $user = User::findOrFail($employee->user_id);
            $user->status = $request->disciplinary_type;
            $user->updated_at = Carbon::now();
            $user->save();

            //record disciplinary
            if ($request->end_date) {
                $days = Carbon::parse($request->end_date)->diffInDays(Carbon::parse($request->effective_date));
            } else {
                $days = null;
            }

            $employeeDisciplinary = new EmployeeDisciplinary();
            $employeeDisciplinary->employee_id = $employee->id;
            $employeeDisciplinary->effective_date = Carbon::parse($request->effective_date)->format('Y-m-d');
            $employeeDisciplinary->days = $days;
            $employeeDisciplinary->end_date = $days ? Carbon::parse($request->end_date)->format('Y-m-d') : null;
            $employeeDisciplinary->disciplinary_type = $request->disciplinary_type;
            $employeeDisciplinary->status = EmployeeDisciplinary::STATUS_ACTIVE;
            $employeeDisciplinary->cooperative_id = $actionedBy->cooperative_id;
            $employeeDisciplinary->actioned_by = $actionedBy->id;
            $employeeDisciplinary->reason = $request->reason;
            $employeeDisciplinary->with_pay =
                $request->disciplinary_type == EmployeeDisciplinary::DISCIPLINARY_TYPE_SUSPENSION_WITH_PAY ?
                    EmployeeDisciplinary::WITH_PAY : EmployeeDisciplinary::WITHOUT_PAY;

            $employeeDisciplinary->save();
            $audit_trail_data = [
                'user_id' => $actionedBy->id,
                'activity' => 'Suspended employee no: ' . $employee->employee_no,
                'cooperative_id' => $actionedBy->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success("Disciplinary actioned recorded");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation Failed');
            return redirect()->back();
        }
    }

    public function employee_appraisal(Request $request, $employeeId)
    {
        $this->validate($request, [
            'appraisal_type' => 'required',
            'position' => 'required',
            'job_group' => 'required', //level or job group
            'department' => 'required',
            'salary' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'employment_type' => 'required',
            'comments' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $appraisal = new EmployeeAppraisal();
            $employee = CoopEmployee::findOrFail($employeeId);
            $appraisal->employee_id = $employee->id;
            $appraisal->appraisal_type = $request->appraisal_type;
            $appraisal->effective_date = Carbon::now()->format('Y-m-d');
            $appraisal->old_position_id = $employee->position->position_id;
            $appraisal->new_position_id = $request->position;
            $employee->position->position_id = $request->position;
            $employee->position->save();


            if ($employee->employeeSalary) {
                $appraisal->old_job_group = $employee->employeeSalary->job_group;
                $appraisal->old_salary = $employee->employeeSalary->amount;
                $employee->employeeSalary->amount = $request->salary;
                $employee->employeeSalary->job_group = $request->job_group;
                $employee->employeeSalary->save();
            } else {

                $employeeSalo = new EmployeeSalary();
                $employeeSalo->amount = $request->salary;
                $employeeSalo->has_benefits = 'yes';
                $employeeSalo->employee_id = $employee->id;
                $employeeSalo->job_group = $request->job_group;
                $employeeSalo->save();

                $appraisal->old_job_group = 'Was not Set';
                $appraisal->old_salary = 0;
            }

            $appraisal->new_job_group = $request->job_group;
            $appraisal->new_salary = $request->salary;


            $appraisal->old_department_id = $employee->department_id;
            $appraisal->new_department_id = $request->department;
            $employee->department_id = $request->department;
            $employee->save();

            $appraisal->old_employment_type_id = $employee->employmentType->employment_type_id;
            $appraisal->new_employment_type_id = $request->employment_type;
            $employee->employmentType->employment_type_id = $request->employment_type;
            $employee->employmentType->save();

            $appraisal->comments = $request->comments;

            $user = Auth::user();
            $appraisal->actioned_by_id = $user->id;
            $appraisal->cooperative_id = $user->cooperative_id;
            $appraisal->save();

            DB::commit();

            $message = 'Appraisal of type: '
                . config('enums.appraisal_types')[$request->appraisal_type]
                . ' for employee number: ' . $employee->employee_no;
            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => $message,
                'cooperative_id' => $user->cooperative->id
            ];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success($message);
            return redirect()->back();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            toastr()->error("Oops! Operation failed");
            return redirect()->back();
        }
    }


}
