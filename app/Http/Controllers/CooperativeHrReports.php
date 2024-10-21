<?php

namespace App\Http\Controllers;

use App\EmployeeSalary;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;

class CooperativeHrReports extends Controller
{

    const INSURANCE_RELIEF_RATE = 0.15;
    const INSURANCE_RELIEF_MAX_LIMIT = 5000;
    const FIXED_DEDUCTION = 20000;
    const THIRTY_PERCENT = 0.3;
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function reports(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative_id;
        $employees = User::where('cooperative_id', $coop)
            ->whereHas('employee')
            ->with('employee')
            ->orderBy('first_name')
            ->orderBy('other_names')
            ->get();
        $start_date = (int)Carbon::parse($user->cooperative->created_at)->format('Y');
        $years = get_years_from_start($start_date);
        return view('pages.cooperative.hr.reports.reports', compact('employees', 'years'));
    }

    public function download_reports(Request $request)
    {
        $this->validate($request, [
            'employee' => 'sometimes|nullable',
            'report_type' => 'required',
            'year' => 'required',
            'deduction_type' => 'required_if:report_type,==,' . EmployeeSalary::REPORT_TYPE_DEDUCTION
        ]);

        $require_employee = [EmployeeSalary::REPORT_TYPE_P9];

        if (in_array($request->report_type, $require_employee) && $request->employee == null) {
            toastr()->warning("Select Employee to generate this report");
            return redirect()->back();
        }

        if ($request->report_type == EmployeeSalary::REPORT_TYPE_P9) {
            return $this->p9Report($request);
        }

        if ($request->report_type == EmployeeSalary::REPORT_TYPE_P10) {
            return $this->p10Report($request);
        }

        if (in_array($request->report_type, [EmployeeSalary::REPORT_TYPE_NET_PAY, EmployeeSalary::REPORT_TYPE_GROSS_PAY])) {
            return $this->net_gross_pay($request);
        }
        if (in_array($request->report_type, [EmployeeSalary::REPORT_TYPE_NHIF, EmployeeSalary::REPORT_TYPE_NSSF, EmployeeSalary::REPORT_TYPE_HOUSING_FUND])) {
            return $this->deductionsReport($request);
        }

        if ($request->report_type == EmployeeSalary::REPORT_TYPE_DEDUCTION) {
            return $this->annualDeductionsReport($request);
        }
        if ($request->report_type == EmployeeSalary::REPORT_TYPE_ALLOWANCE) {
            return $this->annualAllowancesReport($request);
        }

        toastr()->warning('Report Not yet Ready');
        return redirect()->back()->withInput();
    }

    private function p9Report($request)
    {
        $employee = User::findOrFail($request->employee);
        $names = ucwords(strtolower($employee->first_name . ' ' . $employee->other_names));
        $pin = $employee->employee->kra;
        $empId = $employee->employee->id;
        $file_name = strtolower(str_replace(' ', '_', $employee->username)) . '_p9_form';

        $p9Data = [];

        foreach (config('enums.Months') as $k => $m) {
            $p9Data[] = $this->p9Monthly_data($k, $empId, $request->year);
        }

        $data = [
            "p9Data" => $p9Data,
            'year' => $request->year,
            'employee' => $names,
            'pin' => $pin,
        ];

        $data = [
            'title' => 'P9 Report',
            'pdf_view' => 'hr_reports.p9',
            'records' => $data,
            'filename' => $file_name,
            'orientation' => 'landscape'
        ];
        return download_pdf($data);
    }


    private function p9Monthly_data($month, $empId, $year)
    {
        $payslip_data = DB::select(
            "SELECT period_month AS month, period_year as year, gross_pay, net_pay, paye, paye_before_deduction,paye_deduction,
                    basic_pay, total_allowances, after_tax_deductions, before_tax_deductions from payrolls
                    where period_month = '$month'  and period_year = '$year' and employee_id = '$empId'
                    order by month, year limit 1"
        );


        if (!empty($payslip_data)) {
            $tax = $payslip_data[0]->paye_before_deduction;
            $reliefs = $this->calculate_reliefs($payslip_data[0]);
            $tax_relief = $reliefs['tax_relief'];

            $es = [
                "E1"=>$payslip_data[0]->gross_pay * self::THIRTY_PERCENT,
                "E2"=>$reliefs['pension'], //TODO: this should be only nssf and pension
                "E3" => self::FIXED_DEDUCTION
            ];
            asort($es); // get the asc order;
                $lowest_es = reset($es);
            $data = [
                "month" => config('enums.Months')[$month],
                "A" => $payslip_data[0]->gross_pay,
                "B" => 0,
                "C" => 0,
                "D" => $payslip_data[0]->gross_pay,
                "E1" => $es["E1"], // 30% of A
                "E2" => $es["E2"], // insurance contribution
                "E3" => $es["E3"], // Fixed
                "F" => 0,
                "G" => $lowest_es, // lowest of Es Added to F
                "H" => $payslip_data[0]->gross_pay - $lowest_es, // D - G
                "J" => $tax,
                "K" => abs($reliefs['total_insurance_relief']),// Insurance relief TODO: (nhif + any insurance) * 15 < 5000
                "L" => abs($tax_relief), // Tax relief
                "M" => $payslip_data[0]->paye,// Tax relief
            ];
        } else {
            $data = [
                "month" => config('enums.Months')[$month],
                "A" => 0,
                "B" => 0,
                "C" => 0,
                "D" => 0,
                "E1" => 0,
                "E2" => 0,
                "E3" => 0,
                "F" => 0,
                "G" => 0,
                "H" => 0,
                "J" => 0,
                "K" => 0,
                "L" => 0,
                "M" => 0
            ];
        }

        return $data;
    }


    private function employees($coopId)
    {
        return DB::select("SELECT ce.id AS id, CONCAT(u.first_name,' ',u.other_names) AS names,
                                        ce.employee_no FROM coop_employees ce
                                        JOIN users u ON ce.user_id = u.id
                                       WHERE u.cooperative_id = '$coopId' ORDER BY names, ce.employee_no");
    }

    private function calculate_reliefs($payrolData)
    {

        $afterTaxDeductions = $payrolData->after_tax_deductions != null ?
            unserialize($payrolData->after_tax_deductions) : [];
        $beforeTaxDeductions = $payrolData->before_tax_deductions != null ?
            unserialize($payrolData->before_tax_deductions) : [];

        $all_tax_relief = unserialize($payrolData->paye_deduction);

        $pension = 0;
        if (!empty($beforeTaxDeductions)) {
            foreach ($beforeTaxDeductions as $key => $d) {
                if (trim(strtolower($key)) == 'nssf' ||
                    trim(strtolower($key)) == 'pension') {
                    $pension += abs($d);
                }
            }
        }

        return [
            'tax_relief' => $all_tax_relief['Tax Relief'],
            'pension' => $pension,
            'total_insurance_relief' => $all_tax_relief['Insurance Relief'],
        ];

    }

    private function p10Report($request)
    {

        $year = $request->year;
        $coop = Auth::user();
        $coopId = $coop->cooperative_id;
        $payslip_data = DB::select(
            "SELECT SUM(gross_pay) as gross_pay, SUM(paye) as paye, ce.kra, CONCAT(u.first_name,' ',u.other_names) as name
                    from payrolls
                    JOIN coop_employees ce on ce.id = payrolls.employee_id
                    JOIN users u on ce.user_id = u.id
                    where  period_year = '$year' and u.cooperative_id = '$coopId' group by employee_id
                    order by gross_pay"
        );

        $file_name = date('Y') . '_10_form';

        $data = [
            "p10Data" => $payslip_data,
            'year' => $request->year,
            'employer' => ucwords(strtolower($coop->cooperative->name)),
        ];
        $data = [
            'title' => 'P10 Report',
            'pdf_view' => 'hr_reports.p10',
            'records' => $data,
            'filename' => $file_name,
            'orientation' => 'landscape'
        ];
        return download_pdf($data);
    }

    private function net_gross_pay($request)
    {
        $year = $request->year;
        $coop = Auth::user();
        $coopId = $coop->cooperative_id;
        $employees = $this->employees($coopId);

        $payData = [];
        if (count($employees) > 0) {

            foreach ($employees as $emp) {
                $payslip_data = DB::select("SELECT net_pay,gross_pay, period_month
                                                FROM payrolls where  period_year = '$year'
                                                                and employee_id = '$emp->id'
                                                              order by period_month");
                $employeeData = [
                    'report_type' => $request->report_type,
                    "name" => ucwords(strtolower($emp->names)),
                    "emp_no" => $emp->employee_no,
                    "data" => $payslip_data
                ];

                $payData[] = $employeeData;
            }

        } else {
            toastr()->warning("You have no employees");
            return redirect()->back();
        }

        $data = ['year' => $year,
            "payData" => $payData,
            'title' => strtoupper($request->report_type)];

        $allData = [
            'title' => "$year Net Pay Data",
            'pdf_view' => 'hr_reports.pay',
            'records' => $data,
            'filename' => $year . '_' . strtolower(str_replace(' ', '_', $request->report_type)),
            'orientation' => 'landscape'
        ];
        return download_pdf($allData);
    }


    public function deductionsReport($request)
    {
        $year = $request->year;
        $coop = Auth::user();
        $coopId = $coop->cooperative_id;
        $employees = $this->employees($coopId);
        $deductionData = [];
        if (count($employees) > 0) {

            foreach ($employees as $emp) {
                $payslip_data = DB::select("SELECT after_tax_deductions, period_month FROM
                                              payrolls where  period_year = '$year'
                                                         and employee_id = '$emp->id'
                                                       order by period_month");
                $employeeData = [
                    'report_type' => $request->report_type,
                    "name" => ucwords(strtolower($emp->names)),
                    "emp_no" => $emp->employee_no,
                    "data" => $this->unserialize_data_extract_value($payslip_data, $request->report_type)];

                $deductionData[] = $employeeData;
            }

        } else {
            toastr()->warning("You have no employees");
            return redirect()->back();
        }

        $data = ['year' => $year,
            "deductionData" => $deductionData,
            'title' => strtoupper($request->report_type)
        ];
        $allData = [
            'title' => "$year " . strtoupper($request->report_type),
            'pdf_view' => 'hr_reports.deductions',
            'records' => $data,
            'filename' => $year . '_' . strtolower(str_replace(' ', '_', $request->report_type)),
            'orientation' => 'landscape'
        ];
        return download_pdf($allData);
    }

    private function unserialize_data_extract_value($data, $key)
    {
        $formatted_data = [];
        foreach ($data as $d) {
            $serialized_data = array_change_key_case(unserialize($d->after_tax_deductions));
            if (key_exists($key, $serialized_data)) {

                $formatted_data[] = [$key => $serialized_data[$key], 'month' => $d->period_month];
            } else {
                $formatted_data[] = [$key => 0, 'month' => $d->period_month];
            }
        }

        return $formatted_data;
    }

    /**
     * Download report for all deductions statutory and non-statutory for all employees
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function annualDeductionsReport($request)
    {
        $year = $request->year;
        $coop = Auth::user();
        $coopId = $coop->cooperative_id;
        $employees = $this->employees($coopId);
        $deductionData = [];

        if (count($employees) > 0) {
            foreach ($employees as $emp) {
                $payslip_data = DB::select("SELECT after_tax_deductions, before_tax_deductions, period_month
                                                FROM payrolls where  period_year = '$year'
                                                                AND employee_id = '$emp->id'
                                                order by period_month");
                $employeeData = [
                    'report_type' => $request->report_type,
                    "name" => ucwords(strtolower($emp->names)),
                    "emp_no" => $emp->employee_no,
                    "data" => $this->unserialize_data_extract_deduction_annual_monthly_sum($payslip_data, $request->deduction_type)];

                $deductionData[] = $employeeData;
            }

        } else {
            toastr()->warning("You have no employees");
            return redirect()->back();
        }

        $data = ['year' => $year,
            "deductionData" => $deductionData,
            'title' => strtoupper(config('enums.deduction_types')[$request->deduction_type].' '.$request->report_type)
        ];

        $allData = [
            'title' => "$year " . strtoupper($request->report_type),
            'pdf_view' => 'hr_reports.deduction_statutory_non_statutory',
            'records' => $data,
            'filename' => $year . '_' . strtolower(str_replace(' ', '_', $request->report_type)),
            'orientation' => 'landscape'
        ];
        return download_pdf($allData);
    }

    /**
     * @param $data array after tax and before tax deductions data for an employee
     * [{after_tax:"serialized", before_tax:"serialized", period_month:"1"}]
     * @param $deduction_type string statutory or non statutory deductions
     * @return array array of monthly deductions [0=>230,1=>340]
     */
    private function unserialize_data_extract_deduction_annual_monthly_sum($data, $deduction_type): array
    {
        $deductions = [];

        //Loop through monthly data to check for deductions in every month.
        foreach (config('enums.Months') as $k => $v) {

            //check if the month is in $data array.. the data is oder by month in asc order jan -> dec.
            if (key_exists(--$k, $data)) {

                //unserialize data and sum all the deductions
                $total_statutory_deductions = 0;
                foreach (unserialize($data[$k]->after_tax_deductions) as $deduction) {
                    $total_statutory_deductions += $deduction;
                }

                $total_non_statutory_deductions = 0;
                foreach (unserialize($data[$k]->before_tax_deductions) as $deduction) {
                    $total_non_statutory_deductions += $deduction;
                }

                if ($deduction_type == EmployeeSalary::DEDUCTION_TYPE_STATUTORY) {
                    $deductions[] = [$k => $total_statutory_deductions];
                } elseif ($deduction_type == EmployeeSalary::DEDUCTION_TYPE_NON_STATUTORY) {
                    $deductions[] = [$k => $total_non_statutory_deductions];
                } else {
                    $deductions[] = [$k => ($total_non_statutory_deductions + $total_statutory_deductions)];
                }

            } else {
                // if month is not in $data object give it 0
                $deductions[] = [$k => 0];
            }
        }
        return $deductions;
    }


    private function annualAllowancesReport($request){
        $year = $request->year;
        $coop = Auth::user();
        $coopId = $coop->cooperative_id;
        $employees = $this->employees($coopId);

        $allowancesData = [];
        if (count($employees) > 0) {
            foreach ($employees as $emp) {
                $payslip_data = DB::select("SELECT total_allowances, period_month
                                                FROM payrolls where  period_year = '$year'
                                                                AND employee_id = '$emp->id'
                                                order by period_month");
                $employeeData = [
                    'report_type' => $request->report_type,
                    "name" => ucwords(strtolower($emp->names)),
                    "emp_no" => $emp->employee_no,
                    "data" => $this-> monthly_total_allowances($payslip_data)
                ];

                $allowancesData[] = $employeeData;
            }

        } else {
            toastr()->warning("You have no employees");
            return redirect()->back();
        }

        $data = ['year' => $year,
            "allowancesData" => $allowancesData,
            'title' => strtoupper('Total '.$request->report_type)
        ];

        $allData = [
            'title' => strtoupper($request->report_type),
            'pdf_view' => 'hr_reports.allowance',
            'records' => $data,
            'filename' => $year . '_' . strtolower(str_replace(' ', '_', $request->report_type)),
            'orientation' => 'landscape'
        ];
        return download_pdf($allData);
    }


    private function monthly_total_allowances($payslipData): array{
        $monthly_allowances = [];
        foreach (config('enums.Months') as $k => $v){
            if(key_exists(--$k,$payslipData )){
                $monthly_allowances[]=$payslipData[$k]->total_allowances;
            }else{
                $monthly_allowances[]=0;
            }
        }
        return $monthly_allowances;
    }

}
