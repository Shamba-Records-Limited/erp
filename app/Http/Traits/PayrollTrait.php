<?php

namespace App\Http\Traits;

use App\AdvanceDeduction;
use App\AdvanceDeductionTransaction;
use App\CoopEmployee;
use App\EmployeeAllowance;
use App\EmployeeDisciplinary;
use App\EmployeeSalary;
use App\Events\AuditTrailEvent;
use App\Payroll;
use App\PayrollDeduction;
use App\PayrollStatus;
use App\User;
use Auth;
use Carbon\Carbon;
use Log;

trait PayrollTrait
{

    private $rate_base_value = 100;
    private $insurance_relief_max_limit = 5000;
    private $insurance_relief_rate = 0.15;
    private function getSalary(CoopEmployee $employee, $past)
    {

        if($past){
            Log::info("It is in the past, return the current salary");
            return EmployeeSalary::where('employee_id', $employee->id)->first();
        }

        $employee_disciplinary = EmployeeDisciplinary::suspended_without_pay_employees($employee->id);
        if ($employee_disciplinary) {
            Log::info("Employee $employee->employee_no has a disciplinary case active");

            $days_to_pay = $this->getDaysToPay($employee_disciplinary);
            $number_of_days_in_the_month = Carbon::now()->daysInMonth;

            $salary = EmployeeSalary::where('employee_id', $employee->id)->first();

            $new_salary = ($salary->amount/$number_of_days_in_the_month)*$days_to_pay;
            Log::info("new Salary $new_salary from $salary->amount");
            return (object)[
                "amount" => $new_salary,
                "has_benefits" => $salary->has_benefits
            ];
        } else {
            Log::info("Employee $employee->employee_no does not have any disciplinary issue to deduct pay");
            return EmployeeSalary::where('employee_id', $employee->id)->first();
        }
    }


    private function getDaysToPay(EmployeeDisciplinary $employee_disciplinary){

        $effective_month = Carbon::parse($employee_disciplinary->effective_date)->month;
        $effective_day = Carbon::parse($employee_disciplinary->effective_date)->day;

        $end_month = Carbon::parse($employee_disciplinary->end_date)->month;
        $end_day = Carbon::parse($employee_disciplinary->end_date)->day;

        $current_month = Carbon::now()->month;
        $days_in_the_month = Carbon::now()->daysInMonth;

        // same effective month and different end month
        if ($effective_month == $current_month &&  $current_month != $end_month) {
            Log::info("we are in the same month, return number of days after the effective day");
            // we are in the same month, return number of days after the effective day
            return $days_in_the_month - $effective_day;
        } elseif ($current_month == $end_month && $effective_month != $current_month) {
            // last month, return the days after the suspension ended
            Log::info("last month, return the days after the suspension ended");
            return $days_in_the_month - $end_day;
        } elseif($current_month == $end_month && $effective_month == $current_month) {
            // same effective month, same end_month, return difference between start and end
            // subtracted from number of days in the month
            return $days_in_the_month - ($end_day - $effective_day) ;
            Log::info("same effective month, same end_month, return difference between start and end");
        }else{
            Log::info("Suspended the whole month");
            return 0;
        }

    }

    private function generate_payroll($employees, $year, $month, User $user, $past): bool
    {

        $total_gross = 0;
        $monthName = config('enums.Months')[$month];

        if ($employees) {
            Log::info("To generate payrolls for: " . count($employees) . " employees");
            foreach ($employees as $emp) {
                $payrollGenerated = Payroll::where('employee_id', $emp->id)
                        ->where('period_year', $year)
                        ->where('period_month', $month)
                        ->count() > 0;

                if (!$payrollGenerated) {
                    $payslipData = $this->generatePayslipData($emp->id, $user, $past);
                    if (!empty($payslipData)) {
                        Log::info("Generating payroll for employeeId: " . $emp->id);
                        $gross_pay = $payslipData['gross_pay'];
                        $total_gross += $gross_pay;

                        $payroll = new Payroll();
                        $payroll->employee_id = $emp->id;
                        $payroll->gross_pay = $gross_pay;
                        $payroll->net_pay = $payslipData['net_pay'];
                        $payroll->basic_pay = $payslipData['basic_pay'];;
                        $payroll->total_allowances = $payslipData['total_allowances'];
                        $payroll->allowances = serialize($payslipData['allowances']);
                        $payroll->before_tax_deductions = serialize($payslipData['before_paye_deductions']);
                        $payroll->after_tax_deductions = serialize($payslipData['after_paye_deductions']);
                        $payroll->taxable_income = $payslipData['taxable_income'];
                        $payroll->paye = $payslipData['paye'];
                        $payroll->paye_before_deduction = $payslipData['paye_before_deduction'];
                        $payroll->paye_deduction = serialize($payslipData['paye_deduction']);
                        $payroll->created_by = $user->id;
                        $payroll->cooperative_id = $user->cooperative_id;
                        $payroll->period_month = $month;
                        $payroll->period_year = $year;
                        $payroll->advance_deductions = serialize($payslipData['advance_deductions']);
                        $payroll->save();

                        $payrollStatus = new PayrollStatus();
                        $payrollStatus->payroll_id = $payroll->refresh()->id;
                        $payrollStatus->created_by = $user->id;
                        $payrollStatus->cooperative_id = $user->cooperative_id;
                        $payrollStatus->status = PayrollStatus::STATUS_PENDING;
                        $payrollStatus->save();

                        // update advance deductions
                        $this->advance_deductions_transaction($payslipData['advance_deductions'], $payroll->refresh()->id);

                        Log::info("Payroll employeeId: " . $emp->id . " created");
                    } else {
                        Log::info("No payslip data for employee id: " . $emp->id);
                    }
                } else {
                    Log::debug("Payroll for $emp->id is already generated for this period. Skipping...");
                }
            }

            if ($total_gross > 0) {
                $trx = create_account_transaction(
                    'Salary Payments',
                    $total_gross,
                    "Salary payments for {$year} {$monthName}");
                if ($trx) {
                    $audit_trail_data = [
                        'user_id' => Auth::user()->id,
                        'activity' => 'Payroll generated for cooperative ' . $user->cooperative->id,
                        'cooperative_id' => $user->cooperative->id];
                    event(new AuditTrailEvent($audit_trail_data));
                    toastr()->success('Payroll generated successfully for selected employees');
                    return true;
                } else {
                    Log::error("Employee payroll failed to generate due to accounting issues");
                    toastr()->error('Employee payroll failed to generate. Contact admin for support on Accounting ledgers');
                    return false;
                }
            } else {
                toastr()->warning('Total Gross is 0, no payments to be done.');

                return false;
            }
        }

        Log::info("No employees found");
        toastr()->warning('No employees found');
        return false;
    }


    private function advance_deductions_transaction($advance_deductions, $payrollId){
        Log::info("Deductions for Payroll Id {$payrollId} ", $advance_deductions);

        foreach ($advance_deductions as $d_transaction){
            $advanceDeductionType = $d_transaction['type'];
            $advanceDeductionId = $d_transaction['id'];
            $advanceDeductionAmount = $d_transaction['amount'];
            Log::info("Deduction type {$advanceDeductionType}");



            $advance_deduction_object = AdvanceDeduction::findOrFail($advanceDeductionId);
            $current_balance = $advance_deduction_object->balance;
            $balance_after_deduction = $current_balance - $advanceDeductionAmount;

            if ($balance_after_deduction <= 0) {
                Log::info("Balance after deduction is {$balance_after_deduction}, hence setting balance to 0");
                $advance_deduction_object->balance -= $current_balance; //setting balance to 0;
                $amount_to_deduct = $current_balance;
                $advance_deduction_object->status = AdvanceDeduction::STATUS_CLOSED;
            } else {
                Log::info("Balance after deduction is {$balance_after_deduction}, hence updating balance with amount to deduct {$balance_after_deduction}");
                $advance_deduction_object->balance = $balance_after_deduction;
                $amount_to_deduct = $advanceDeductionAmount;
            }

            $advance_deduction_object->save();

            $advanced_deduction_transaction = new AdvanceDeductionTransaction();
            $advanced_deduction_transaction->advance_deduction_id = $advanceDeductionId;
            $advanced_deduction_transaction->amount = $amount_to_deduct;
            $advanced_deduction_transaction->payroll_id = $payrollId;
            $advanced_deduction_transaction->balance = $advance_deduction_object->balance;
            $advanced_deduction_transaction->cooperative_id = $advance_deduction_object->cooperative_id;
            $advanced_deduction_transaction->save();
        }

        Log::info("Advance deductions completed");

    }
    private function generatePayslipData(string $employeeId, User $user, $past = false): array
    {
        Log::info("Generate Payslip  for $employeeId");
        $totalAllowances = 0;
        $beforePAYEDeductions = [];
        $afterPAYEDeductions = [];
        $empAllowances = [];
        $payeDeduction = [];
        $employee = CoopEmployee::withTrashed()->findOrFail($employeeId);
        $salary = $this->getSalary($employee,$past);
        if ($salary) {
            Log::info("Salary:{$salary->amount}");
            $benefitAllowances = EmployeeAllowance::where('employee_id', $employeeId)
                ->where('type', EmployeeAllowance::TYPE_BENEFIT)
                ->get();
            $names = ucwords(strtolower($employee->user->first_name . ' ' . $employee->user->other_names));
            $grossPayWithDeductionsMinusAllowances = $salary->amount;
            $basic_pay = $salary->amount;

            if (strtolower($salary->has_benefits) == 'yes') {
                foreach ($benefitAllowances as $allowance) {
                    if ($allowance->percentage) {
                        $value = ($grossPayWithDeductionsMinusAllowances * ($allowance->percentage / $this->rate_base_value));
                        $grossPayWithDeductionsMinusAllowances += $value;
                    } else {
                        $value = $allowance->amount;
                        $grossPayWithDeductionsMinusAllowances += $value;
                    }
                    $totalAllowances += $value;
                    $empAllowances[$allowance->title] = $value;
                }
            }

            $grossPayWithAllowances = $grossPayWithDeductionsMinusAllowances;
            if (strtolower($salary->has_benefits) == 'yes') {
                Log::info("Employee has benefits and deductions");
                $beforeTaxDeductions = PayrollDeduction::where("country_id", $user->cooperative->country_id)
                    ->where(function ($query) use ($grossPayWithDeductionsMinusAllowances) {
                        $query->where(function ($query2) use ($grossPayWithDeductionsMinusAllowances) {
                            $query2->where('min_amount', '<=', $grossPayWithDeductionsMinusAllowances)
                                ->where('max_amount', '>=', $grossPayWithDeductionsMinusAllowances);
                        })
                            ->orWhere(function ($query3) {
                                $query3->whereNull('min_amount')->whereNull('max_amount');
                            });
                    })->where('deduction_stage', PayrollDeduction::BEFORE_PAYE_DEDUCTION)->get();

                $nssf = get_nssf($grossPayWithDeductionsMinusAllowances);
                $grossPayWithDeductionsMinusAllowances-=$nssf;
                foreach ($beforeTaxDeductions as $deduction) {
                    if ($deduction->rate) {
                        $value = ($grossPayWithDeductionsMinusAllowances * ($deduction->rate / $this->rate_base_value));
                        $grossPayWithDeductionsMinusAllowances -= $value;

                    } else {
                        $value = $deduction->amount;
                        $grossPayWithDeductionsMinusAllowances -= $value;
                    }

                    $beforePAYEDeductions[$deduction->name] = $value;
                }
                $beforePAYEDeductions['NSSF'] = $nssf;
                $taxableCharge = $grossPayWithDeductionsMinusAllowances;
            } else {
                Log::info("Employee does not have benefits o deductions");
                $taxableCharge = 0;
            }

            if (strtolower($salary->has_benefits) == 'yes') {
                //after tax deductions
                $afterPAYE = PayrollDeduction::where("country_id",
                    $user->cooperative->country_id)
                    ->where(function ($query) use ($grossPayWithAllowances) {
                    $query->where(function ($query2) use ($grossPayWithAllowances) {
                        $query2->where('min_amount', '<=', $grossPayWithAllowances)
                            ->where('max_amount', '>=', $grossPayWithAllowances);
                    })
                        ->orWhere(function ($query3) {
                            $query3->whereNull('min_amount')->whereNull('max_amount');
                        });
                })->where('deduction_stage', PayrollDeduction::AFTER_PAYE_PAYE_DEDUCTION)->get();

                $paye = get_paye($taxableCharge);
                Log::debug("\n------------------------------PAYE------------------------------");
                Log::debug("Taxable Charge: {$taxableCharge} PAYE {$paye}\n");

                Log::debug("-----------AFTER PAYE DEDUCTION---------------");
                foreach ($afterPAYE as $deduction) {
                    Log::debug($deduction);
                    $base_amount = $deduction->on_gross_pay == PayrollDeduction::DEDUCTION_ON_GROSS_PAY_YES ? $grossPayWithAllowances : $grossPayWithDeductionsMinusAllowances;
                    if ($deduction->rate) {
                        $value = ($base_amount * ($deduction->rate / $this->rate_base_value));
                    } else {
                        $value = $deduction->amount;
                    }

                    //Extract tax relief
                    if(strtolower($deduction->name) == 'relief'){
                        $payeDeduction["Tax Relief"] = $value;
                    }else{
                        $grossPayWithDeductionsMinusAllowances -= $value;
                        $afterPAYEDeductions[$deduction->name] = $value;
                    }

                }
                Log::debug("-------------------------------------");
                $insurance_relief = $this->calculateInsuranceRelief($afterPAYEDeductions);
                $payeDeduction["Insurance Relief"] = $insurance_relief;
                Log::debug("Insurance Relief {$insurance_relief}");

                    //other deductions
                $OtherDeductions = EmployeeAllowance::where('employee_id', $employeeId)
                    ->where('type', EmployeeAllowance::TYPE_DEDUCTION)
                    ->get();
                foreach ($OtherDeductions as $deduction) {
                    if ($deduction->percentage) {
                        $value = ($grossPayWithDeductionsMinusAllowances * ($deduction->percentage / $this->rate_base_value));
                        $grossPayWithDeductionsMinusAllowances -= $value;
                    } else {
                        $value = $deduction->amount;
                        $grossPayWithDeductionsMinusAllowances -= $value;
                    }
                    $afterPAYEDeductions[$deduction->title] = $value;
                }
                Log::debug("After PAYE + Other deductions: ",$afterPAYEDeductions);
            } else {
                $paye = 0;
            }

            // minus paye with paye_deductions (- so we need to add)
            $paye_before_deductions = $paye;
            foreach ($payeDeduction as $k=>$d){
                $paye +=$d;
            }

            $net_pay = $grossPayWithDeductionsMinusAllowances - $paye;

            $advance_deductions = AdvanceDeduction::where('employee_id', $employeeId)
                ->where('status', AdvanceDeduction::STATUS_ACTIVE)
                ->get();

            $advanceDeductions = [];
            foreach ($advance_deductions as $deduction){
                $net_pay -= $deduction->monthly_deductions;
                $deduction_object= [
                    "amount" => $deduction->monthly_deductions,
                    "id" => $deduction->id,
                    "type" => config('enums.advance_deduction_types')[$deduction->type],
                ];
                $advanceDeductions[]=$deduction_object;
            }

            Log::info("Advance Deductions: ", $advanceDeductions);

            $summary =  [
                "net_pay" => $net_pay,
                "gross_pay" => $grossPayWithAllowances,
                "basic_pay" => $basic_pay,
                'names' => $names,
                'total_allowances' => $totalAllowances,
                'allowances' => $empAllowances,
                'before_paye_deductions' => $beforePAYEDeductions,
                "after_paye_deductions" => $afterPAYEDeductions,
                'taxable_income' => $taxableCharge,
                'paye' => $paye,
                'paye_deduction' => $payeDeduction,
                'paye_before_deduction' => $paye_before_deductions,
                'advance_deductions' => $advanceDeductions
            ];

            Log::debug("Payroll Summary ", $summary);
            return $summary;
        } else {
            return [];
        }


    }


    private function calculateInsuranceRelief($deduction){
        $total_insurance = 0;
        if (!empty($deduction)) {
            foreach ($deduction as $key => $d) {
                if (trim(strtolower($key)) == 'insurance' ||
                    trim(strtolower($key)) == 'nhif') {
                    Log::debug("Calculate Relief of :{$key}: => {$d}");
                    $total_insurance += abs($d);
                }
            }
        }

        $relief = $this->insurance_relief_rate * $total_insurance;
        return ($relief > $this->insurance_relief_max_limit ? $this->insurance_relief_max_limit : $relief)*-1;
    }

    private function extractTaxrelive($deduction){

    }
}
