<?php

namespace App\Imports;

use App\Bank;
use App\BankBranch;
use App\CoopBranchDepartment;
use App\CoopEmployee;
use App\Country;
use App\EmploymentType;
use App\Exceptions\UnableToCreateEmployeeException;
use App\Http\Traits\Employee;
use App\JobPosition;
use App\Rules\BankBranchRule;
use App\Rules\BirthYearRule;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


HeadingRowFormatter::extend('custom', function ($value, $key) {
    return str_replace(" ", "_", $value);
});

class EmployeeImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Employee;

    /**
     * @param Collection $rows
     * @throws UnableToCreateEmployeeException
     * @throws \Throwable
     */
    public function collection(Collection $rows)
    {
        \DB::beginTransaction();
        $user = \Auth::user();
        foreach ($rows as $row) {
            $country_id = Country::where('name', $row['country'])->first()->id;
            $bank_branch_id = BankBranch::where('name', $row['bank_branch'])
                ->where('cooperative_id', $user->cooperative_id)
                ->first()->id;
            $employment_type_id = EmploymentType::where('type', $row['employment_type'])
                ->where('cooperative_id', $user->cooperative_id)->first()->id;
            $job_position_id = JobPosition::where('position', $row['position'])
                ->where('cooperative_id', $user->cooperative_id)
                ->first()->id;
            $department = $row['department'];
            $department_id = \DB::select("SELECT cd.id FROM coop_branch_departments  cd
             JOIN erp.coop_branches cb ON cd.branch_id = cb.id
             WHERE cd.name ='$department' AND cb.cooperative_id = '$user->cooperative_id' LIMIT 1")[0]->id;

            $bank_id = Bank::where('cooperative_id', $user->cooperative_id)
                ->where('name', $row['bank'])
                ->first()->id;
            $r = (object)[
                'country' => $country_id,
                'county' => $row['county'],
                'first_name' => $row['first_name'],
                'other_names' => $row['other_names'],
                'user_name' => $row['user_name'],
                'email' => $row['email'],
                'area_of_residence' => $row['area_of_residence'],
                'id_no' => $row['id_no'],
                'dob' => $row['dob'],
                'gender' => $row['gender'],
                'marital_status' => $row['marital_status'],
                'phone_no' => $row['phone_number'],
                'bank_account' => $row['bank_acc_no'],
                'bank_account_name' => $row['bank_account_name'],
                'bank' => $row['bank'],
                'bank_id' => $bank_id,
                'bank_branch_id' => $bank_branch_id,
                'kra' => $row['kra'],
                'nssf' => $row['nssf'],
                'nhif' => $row['nhif'],
                'department' => $department_id,
                'employment_type' => $employment_type_id,
                'employee_number' => $row['employee_no'] == null ? $this->generateEmpNumber(5) : $row['employee_no'],
                'position' => $job_position_id,
                'job_group' => $row['job_group'],
                'basic_salary' => $row['basic_salary'],
            ];

            if(!$this->createEmployee($r)){
                \DB::rollBack();
                throw new UnableToCreateEmployeeException("Unable to create employee: ".$row['first_name']);
            }
        }
        \DB::commit();
    }

    public function generateEmpNumber($n): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return strtoupper($randomString);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $user = \Auth::user();
        return [
            'country' => Rule::in(Country::select('name')->pluck('name')->toArray()),
            'county' => ['required'],
            'id_no' => ['required', 'unique:' . CoopEmployee::class . ',id_no'],
            'phone_number' => ['required', 'regex:/^[0-9]{10}$/', 'unique:' . CoopEmployee::class . ',phone_no'],
            'first_name' => ['required'],
            'other_names' => ['required'],
            'user_name' => ['required', 'unique:' . User::class . ',username'],
            'email' => ['required', 'unique:' . User::class . ',email'],
            'area_of_residence' => ['required'],
            'dob' => ['required', 'date', 'date_format:Y-m-d', new BirthYearRule()],
            'gender' => Rule::in(config('enums.employee_configs')['gender']),
            'marital_status' => Rule::in(config('enums.employee_configs')['marital_status']),
            'bank_acc_no' => ['required'],
            'bank' => Rule::in(Bank::select('name')->where('cooperative_id', $user->cooperative_id)->pluck('name')->toArray()),
            'bank_branch' => ['exists:' . BankBranch::class . ',name', new BankBranchRule($user)],
            'kra' => ['sometimes', 'nullable'],
            'nssf' => ['sometimes', 'nullable'],
            'nhif' => ['sometimes', 'nullable'],
            'department' => Rule::in(CoopBranchDepartment::select('coop_branch_departments.name')
                ->join('coop_branches', 'coop_branches.id', '=', 'coop_branch_departments.branch_id')
                ->where('coop_branches.cooperative_id', $user->cooperative_id)
                ->pluck('coop_branch_departments.name')->toArray()),
            'employment_type' => Rule::in(EmploymentType::select('type')->where('cooperative_id', $user->cooperative_id)->pluck('type')->toArray()),
            'position' => Rule::in(JobPosition::select('position')->where('cooperative_id', $user->cooperative_id)->pluck('position')->toArray()),
            'job_group' => ['required'],
            'basic_salary' => ['sometimes', 'nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'bank_account_name' => ['required'],
        ];
    }

}
