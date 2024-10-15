<?php

namespace App\Http\Controllers;

use App\AdvanceDeduction;
use App\AdvanceDeductionTransaction;
use App\CoopBranchDepartment;
use App\CoopEmployee;
use App\EmployeeDisciplinary;
use App\EmploymentType;
use App\Exports\AdvanceDeductionTransactionExport;
use App\Exports\DepartmentPayrollSummaryExport;
use App\Exports\PayrollSummaryExport;
use App\Http\Traits\PayrollTrait;
use App\Payroll;
use Illuminate\Http\Request;
use App\EmployeeSalary;
use App\EmployeePayroll;
use App\EmployeeAllowance;
use App\PayrollExtra;
use App\Events\AuditTrailEvent;
use Carbon\Carbon;
use Auth;
use DB;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;

class EmployeePayrollController extends Controller
{

    use PayrollTrait;

    public function __construct()
    {
        return $this->middleware('auth');
    }

    //
    public function salary($employeeId)
    {
        $user = Auth::user();
        $employee = CoopEmployee::withTrashed()->findOrFail($employeeId);
        $salary = EmployeeSalary::where('employee_id', $employeeId)->withTrashed()->firstOrFail();
        $allAllowances = EmployeeAllowance::where('employee_id', $employeeId)->get();
        $payrolls = Payroll::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        $payslipData = $this->generatePayslipData($employeeId, $user, false);

        if (empty($payslipData)) {
            $net = 0;
            $gross = 0;
        } else {
            $net = $payslipData["net_pay"];
            $gross = $payslipData["gross_pay"];
        }


        return view('pages.cooperative.hr.employee.salary',
            compact('employeeId', 'gross', 'net', 'salary', 'allAllowances',
                'payrolls', 'employee'));
    }

    //set employee salary
    public function updateHasBenefits(Request $request)
    {
        $this->validate($request, [
            'employeeId' => 'required',
            'has_benefits' => 'required',
        ]);
        try {
            DB::beginTransaction();
            //code...
            EmployeeSalary::updateOrCreate([
                'employee_id' => $request->employeeId
            ], [
                'has_benefits' => $request->has_benefits,
            ]);

            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Set employee salary for ' . $request->employeeId, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Operation Failed!');
            return redirect()->back();
        }
    }

    //set employee salary
    public function setAllowance(Request $request)
    {
        try {
            DB::beginTransaction();
            $salary = EmployeeSalary::select('amount', 'has_benefits')
                ->where('employee_id', $request->employee_id)
                ->first();

            if ($salary->has_benefits == null) {
                toastr()->error("Please set if the employee has benefits or not before setting Salary");
                return redirect()->back();
            }

            if (strtolower($salary->has_benefits) == 'no') {
                toastr()->error("Employee Is not allowed to have benefits");
                return redirect()->back();
            }
            $basic = $salary->amount;
            $allowance = new EmployeeAllowance();
            $amount = $request->amount;
            //chec if has %
            if (substr($amount, -1) == '%') {
                $allowance->percentage = substr($amount, 0, strlen($amount) - 1);
                $amount = ($allowance->percentage / 100) * $basic;
            }
            $allowance->amount = $amount;

            $allowance->type = $request->type;
            $allowance->title = $request->title;
            $allowance->description = $request->description;
            $allowance->employee_id = $request->employee_id;
            $allowance->save();
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Allowance set for employee' . $request->employee_id, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Employee Allowance Set Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th);
            DB::rollback();
            toastr()->error('Employee allowance could not be set');
            return redirect()->back();
        }
    }

    //edit allowance
    public function updateAllowance(Request $request)
    {
        try {
            DB::beginTransaction();
            //get basic pay
            $basic = EmployeeSalary::where('employee_id', $request->employee_id)->pluck('amount');
            Log::info($basic);
            Log::info($request->employee_id);
            $basic = $basic[0];
            $allowance = EmployeeAllowance::find($request->id);
            $amount = $request->amount;
            //chec if has %
            if (substr($amount, -1) == '%') {
                $allowance->percentage = substr($amount, 0, strlen($amount) - 1);
                $amount = ($allowance->percentage / 100) * $basic;
            }
            $allowance->amount = $amount;

            $allowance->type = $request->type;
            $allowance->title = $request->title;
            $allowance->description = $request->description;
            $allowance->save();
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Allowance ' . $allowance['id'] . ' updated for employee' . $request->employee_id, 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Employee Allowance Set Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th);
            DB::rollback();
            toastr()->error('Employee allowance could not be set');
            return redirect()->back();
        }
    }

    //delete allowance
    public function deleteAllowance($id)
    {
        try {
            DB::beginTransaction();
            //
            $allowance = EmployeeAllowance::find($id);
            $allowance->delete();

            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Allowance ' . $allowance['id'] . ' deleted for employee', 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            DB::commit();
            toastr()->success('Employee Allowance Deleted Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            //throw $th;
            Log::info($th);
            DB::rollback();
            toastr()->error('Employee allowance could not be deleted set');
            return redirect()->back();
        }
    }

    //payroll
    public function payroll(Request $request)
    {
        $coopObject = Auth::user()->cooperative;
        $coop = $coopObject->id;
        $termination_status = CoopEmployee::STATUS_DEACTIVATED;
        $employees = DB::select(
            "select CONCAT(u.first_name, ' ',u.other_names ) as name, ce.id as id
                    from coop_employees ce
                        join users u on u.id = ce.user_id
                    where u.cooperative_id = '$coop' AND ce.status <> '$termination_status'
"
        );

        $employment_types = EmploymentType::select('id', 'type')->where('cooperative_id', $coop)->latest()->get();

        $start_year = (int)Carbon::parse($coopObject->created_at)->format('Y');
        $years = get_years_from_start($start_year);
        $departments = CoopBranchDepartment::select('coop_branch_departments.name', 'coop_branch_departments.id')
            ->join('coop_branches', 'coop_branch_departments.branch_id', '=', 'coop_branches.id')
            ->where('coop_branches.cooperative_id', $coop)
            ->orderBy('coop_branch_departments.name')
            ->get();

        $payrolls = Payroll::payroll_details($request, $coop, 100);
        return view('pages.cooperative.hr.payroll.index', compact('payrolls', 'employees', 'years', 'employment_types', 'departments'));
    }

    //payroll
    public function payrollDetails($payroll_id)
    {
        $payroll = Payroll::findOrFail($payroll_id);
        $basic_pay = $payroll->basic_pay;
        $gross_pay = $payroll->gross_pay;
        $empAllowances = $payroll->allowances != null ? unserialize($payroll->allowances) : [];
        $beforePAYEDeductions = $payroll->before_tax_deductions != null ? unserialize($payroll->before_tax_deductions) : [];
        $afterPAYEDeductions = $payroll->after_tax_deductions != null ? unserialize($payroll->after_tax_deductions) : [];
        $advanceDeductions = $payroll->advance_deductions != null ? unserialize($payroll->advance_deductions) : [];
        $net_pay = $payroll->net_pay;
        $paye = $payroll->paye;
        $taxable_income = $payroll->taxable_income;
        $totalAllowances = $payroll->total_allowances;
        $payeDeductions = $payroll->paye_deduction != null ? unserialize($payroll->paye_deduction) : [];
        $paye_before_deduction = $payroll->paye_before_deduction;
        $names = ucwords(strtolower($payroll->employee->user->first_name . ' ' . $payroll->employee->user->other_names));

        $period = Carbon::parse($payroll->created_at)->format('l, d F Y H:i:s');
        return view('pages.cooperative.hr.payroll.details',
            compact('period', 'payroll', 'basic_pay', 'gross_pay', 'net_pay',
                'empAllowances', 'afterPAYEDeductions', 'beforePAYEDeductions', 'names',
                'totalAllowances', 'paye', 'taxable_income', 'advanceDeductions', 'payeDeductions',
                'paye_before_deduction'));
    }

    //generate payroll
    public function generatePayroll(Request $request)
    {
        $this->validate($request, [
            'month' => 'required',
            'year' => 'required',
            'employees' => 'required',
            'employment_type' => 'required'
        ]);

        $current_month = (int)Carbon::now()->format('m');
        $current_year = (int)Carbon::now()->format('Y');
        $year = (int)$request->year;
        $month = (int)$request->month;

        $past = true;

        if ($year == Carbon::now()->year && $month == Carbon::now()->month) {
            $past = false;
        }

        if ($year == $current_year && $month > $current_month) {
            return redirect()->back()->withInput()
                ->withErrors([
                    'month' => 'The selected month is in the future, the current month is '
                        . config('enums.Months')[$current_month],
                ]);
        }

        try {
            $user = Auth::user();
            if ($request->employment_type == 'all') {
                $employees = CoopEmployee::select('coop_employees.id')
                    ->join('users', 'coop_employees.user_id', '=', 'users.id');
            } else {
                $employees = CoopEmployee::select('coop_employees.id')
                    ->join('users', 'coop_employees.user_id', '=', 'users.id')
                    ->join('employee_employment_types', 'coop_employees.id', '=', 'employee_employment_types.employee_id')
                    ->where('employee_employment_types.employment_type_id', $request->employment_type);
            }


            if ($request->employees && $request->employees[0] != null) {
                $employees = $employees
                    ->whereIn('coop_employees.id', $request->employees)
                    ->where('users.cooperative_id', $user->cooperative_id)
                    ->get();
            } else {
                $employees = $employees
                    ->where('users.cooperative_id', $user->cooperative_id)
                    ->get();
            }


            if (count($employees) == 0) {
                toastr()->warning("There are no employees in that category");
                return redirect()->back()->withInput();
            }

            DB::beginTransaction();
            if ($this->generate_payroll($employees, $year, $month, $user, $past)) {
                DB::commit();
            } else {
                DB::rollBack();
            }
            return redirect()->back();

        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' ' . __METHOD__ . ' ' . __LINE__);
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('Employee payroll could not be set');
            return redirect()->back();
        }
    }

    //print payslip before generation
    public function payslip($payrollId)
    {

        $title = 'Payslip ';
        $period = Carbon::now()->format('D, d M Y  H:i:s');;
        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('letter', 'portrait');

        $payroll = Payroll::findOrFail($payrollId);
        $basic_pay = $payroll->basic_pay;
        $gross_pay = $payroll->gross_pay;
        $empAllowances = $payroll->allowances != null ? unserialize($payroll->allowances) : [];
        $beforePAYEDeductions = $payroll->before_tax_deductions != null ? unserialize($payroll->before_tax_deductions) : [];
        $afterPAYEDeductions = $payroll->after_tax_deductions != null ? unserialize($payroll->after_tax_deductions) : [];
        $advanceDeductions = $payroll->advance_deductions != null ? unserialize($payroll->advance_deductions) : [];
        $net_pay = $payroll->net_pay;
        $taxable_income = $payroll->taxable_income;
        $paye = $payroll->paye;
        $totalAllowances = $payroll->total_allowances;
        $payeDeductions = $payroll->paye_deduction != null ? unserialize($payroll->paye_deduction) : [];
        $paye_before_deduction = $payroll->paye_before_deduction;
        $names = ucwords(strtolower($payroll->employee->user->first_name
            . ' ' . $payroll->employee->user->other_names));

        if ($payroll->employee->bankDetails->bank) {
            $bank = $payroll->employee->bankDetails->bank->name;
        } else {
            $bank = '-';
        }

        $employeeDetails = [
            'names' => $names,
            'period' => config('enums.Months')[$payroll->period_month].' '.$payroll->period_year,
            'kra' => $payroll->employee->kra,
            'phone' => $payroll->employee->phone_no,
            'emp_no' => $payroll->employee->employee_no,
            'nhif' => $payroll->employee->nhif_no,
            'nssf' => $payroll->employee->nssf_no,
            'bank' => $bank . ', ' . $payroll->employee->bankDetails->bankBranch->name,
            'account' => $payroll->employee->bankDetails->account_number
        ];

        $pdf->loadView('pages.cooperative.pdf_views.payslip',
            compact('title', 'period', 'basic_pay', 'gross_pay', 'net_pay',
                'empAllowances', 'afterPAYEDeductions', 'beforePAYEDeductions', 'employeeDetails',
                'totalAllowances', 'taxable_income', 'paye', 'advanceDeductions', 'paye_before_deduction','payeDeductions'));
        $file_name = 'Employee Payslip ' . now();
        return $pdf->stream($file_name);
    }


    //edit
    public function editBenefit($id)
    {
        $allowance = EmployeeAllowance::find($id);
        return view('pages.cooperative.hr.employee.benefit', compact('id', 'allowance'));
    }

    public function download_payroll_summary($type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $cooperative = Auth::user()->cooperative->id;
        $data = Payroll::payroll_details($request, $cooperative, null);
        $file_name = strtolower('payroll_summary_' . date('d_m_Y'));
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new PayrollSummaryExport($data), $file_name);
        } else {
            $data = [
                'title' => 'Payrolls Summary',
                'pdf_view' => 'payroll_summary',
                'records' => $data,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }

    public function departmentPayroll(Request $request)
    {

        $coopObject = Auth::user()->cooperative;
        $coop = $coopObject->id;

        $start_year = (int)Carbon::parse($coopObject->created_at)->format('Y');
        $years = get_years_from_start($start_year);
        $departments = CoopBranchDepartment::select('coop_branch_departments.name', 'coop_branch_departments.id')
            ->join('coop_branches', 'coop_branch_departments.branch_id', '=', 'coop_branches.id')
            ->where('coop_branches.cooperative_id', $coop)
            ->orderBy('coop_branch_departments.name')
            ->get();
        $employment_types = EmploymentType::select('id', 'type')->where('cooperative_id', $coop)->latest()->get();
        $payrolls = Payroll::department_payrolls($request, $coop, 100);

        return view('pages.cooperative.hr.payroll.department', compact('payrolls', 'years', 'employment_types', 'departments'));
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function download_department_payroll_summary($type, Request $request)
    {
        if ($request->request_data == '[]') {
            $request = null;
        } else {
            $request = json_decode($request->request_data);
        }

        $cooperative = Auth::user()->cooperative->id;
        $data = Payroll::department_payrolls($request, $cooperative, 100);
        $file_name = strtolower('department_payroll_summary_' . date('d_m_Y'));
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new DepartmentPayrollSummaryExport($data), $file_name);
        } else {
            $data = [
                'title' => 'Departments Payroll Summary',
                'pdf_view' => 'department_payroll_summary',
                'records' => $data,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }

    public function addAdvanceDeductions(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentMonthName = config('enums.Months')[$currentMonth];
        $this->validate($request, [
            'deduction_type' => 'required',
            'deduction_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'start_month' => "required|integer|between:$currentMonth, 12",
            'principal_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'balance' => 'sometimes|nullable|regex:/^\d+(\.\d{1,2})?$/',
            'employee' => 'required',
        ], [
            'start_month.between' =>  " Starting month should be between {$currentMonthName} and December for this year"
        ]);

        try {
            DB::beginTransaction();
            Log::info("Adding a new advance deductions");
            $employee = CoopEmployee::findOrFail($request->employee);
            $basic_salary = $employee->employeeSalary->amount;

            if($employee->status != CoopEmployee::STATUS_ACTIVE){
                toastr()->error("Employee is not currently Active");
                $current_status = config('enums.employment_status')[$employee->status];
                Log::warning("Employee is not currently Active, current status {$current_status}");
                return redirect()->back()->withInput();
            }

            $similar_active_deduction = AdvanceDeduction::where('employee_id', $request->employee)
                ->where('status', AdvanceDeduction::STATUS_ACTIVE)
                ->where('type', $request->deduction_type)
                ->first();

            $deduction_type = config('enums.advance_deduction_types')[$request->deduction_type];

            if ($similar_active_deduction) {
                Log::info("Employee {$employee->id} has another {$deduction_type} deduction");
                toastr()->error("Employee has an existing active {$deduction_type} deduction");
                return redirect()->back()->withInput();
            }

            $active_monthly_deductions_amount = AdvanceDeduction::where('employee_id', $request->employee)
                ->where('status', AdvanceDeduction::STATUS_ACTIVE)
                ->sum('monthly_deductions');


            if ($active_monthly_deductions_amount) {
                Log::info("There are other deductions, check if the new addition will make the basic salary less than 50% of basic pay");
                $remaining_salary = $basic_salary - $active_monthly_deductions_amount - $request->deduction_amount;
                if ($remaining_salary < ($basic_salary / 2)) {
                    $message = "Remaining Basic Salary {$remaining_salary} is less than 50% of Gross {$basic_salary}";
                    Log::info($message);
                    toastr()->error($message);
                    return redirect()->back()->withInput();
                }
            }else{
                Log::info("No Active advance monthly deductions for employee id {$employee->id}");
            }

            $user = Auth::user();

            $advanceDeduction = new AdvanceDeduction();
            $advanceDeduction->type = $request->deduction_type;
            $advanceDeduction->start_month = $request->start_month;
            $advanceDeduction->start_year = Carbon::now()->year;
            $advanceDeduction->principal_amount = $request->principal_amount;
            $advanceDeduction->monthly_deductions = $request->deduction_amount;
            $advanceDeduction->balance = $request->balance??$request->principal_amount;
            $advanceDeduction->status = AdvanceDeduction::STATUS_ACTIVE;
            $advanceDeduction->deduction_period =  ceil($request->principal_amount/$request->deduction_amount);
            $advanceDeduction->employee_id = $employee->id;
            $advanceDeduction->created_by = $user->id;
            $advanceDeduction->cooperative_id = $user->cooperative_id;
            $advanceDeduction->save();

            $audit_trail_data = [
                'user_id' => $user->id,
                'activity' => 'Adding an advance deduction to Employee'.$employee->employee_no,
                'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));

            DB::commit();
            toastr()->success("Operation Completed Successfully!");
            return redirect()->back();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error("Error while adding advance deductions: {$ex->getMessage()}");
            toastr()->error("Oops Request Failed");
            return redirect()->back();
        }

    }

    public function advance_deduction_transactions($advance_deduction_id){
        $deduction_transactions = AdvanceDeductionTransaction::advance_deduction_trx($advance_deduction_id);
        $deduction = AdvanceDeduction::findOrFail($advance_deduction_id);
        return view('pages.cooperative.hr.payroll.advance_deductions_trx',
            compact('deduction_transactions', 'advance_deduction_id', 'deduction'));
    }


    public function download_advance_deduction_transactions($type, $advance_deduction_id)
    {
        $data = AdvanceDeductionTransaction::advance_deduction_trx($advance_deduction_id);
        $file_name = strtolower('advance_deduction_transactions_' . date('d_m_Y'));
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new AdvanceDeductionTransactionExport($data), $file_name);
        } else {
            $deduction = AdvanceDeduction::findOrFail($advance_deduction_id);
            $data = [
                'title' => ucwords(strtolower($deduction->employee->user->first_name.' '.$deduction->employee->user->other_names.' '.config('enums.advance_deduction_types')[$deduction->type].' Deductions')),
                'pdf_view' => 'advance_deduction_transactions',
                'records' => $data,
                'filename' => $file_name,
                'orientation' => 'landscape'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
