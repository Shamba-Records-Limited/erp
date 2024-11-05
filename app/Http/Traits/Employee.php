<?php

namespace App\Http\Traits;

use App\CoopEmployee;
use App\EmployeeBankDetail;
use App\EmployeeDisciplinary;
use App\EmployeeEmploymentType;
use App\EmployeePosition;
use App\EmployeeSalary;
use App\Events\AuditTrailEvent;
use App\Events\NewUserRegisteredEvent;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use Hash;
use Illuminate\Support\Facades\Auth;
use Log;
use Spatie\Permission\Models\Role;

trait Employee
{
    public function createEmployee($request): bool
    {
        try {
            DB::beginTransaction();
            //generate password
            $password = generate_password();

            //new user and farmer object
            $user = new User();
            $new_user = $this->persist_user($request, $user, $password, false);
            Log::debug("Saved a new User: $new_user->id");
            //employee...
            $employee = new CoopEmployee();
            $new_employee = $this->persist($request, $new_user->id, $employee);
            Log::debug("Saved employee: $new_employee");
            //assign role to user
            $role = Role::select('id', 'name')->where('name', '=', 'employee')->first();
            $new_user->assignRole($role->name);
            //bank details
            // $account_details = new EmployeeBankDetail();
            // $account_details->account_name = $request->bank_account_name;
            // $account_details->account_number = $request->bank_account;
            // $account_details->bank_branch_id = $request->bank_branch_id;
            // $account_details->employee_id = $new_employee;
            // $account_details->bank_id = $request->bank_id;
            // $account_details->save();
            // Log::debug("Saved employee bank details: " . $account_details->refresh()->id);
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
                ' to  ' . $new_user->username, 'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($role_created_audit));

            //send email and new audit trail
            $data = ["name" => ucwords(strtolower($request->first_name)) . ' ' . ucwords(strtolower($request->other_names)),
                "email" => $request->email, "password" => $password];
            $audit_trail_data = ['user_id' => $user->id,
                'activity' => 'Created ' . $new_user->username . 'account',
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            event(new NewUserRegisteredEvent($data));

            DB::commit();
            toastr()->success('Employee Created Successfully');
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("----------------------------------------");
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('Employee could not be created');
            return false;
        }
    }


    private function persist_user($request, User $user, $password, $is_editing): User
    {
        Log::info("Saving Employee user profile");
        $user->first_name = ucwords(strtolower($request->first_name));
        $user->other_names = ucwords(strtolower($request->other_names));
        $user->cooperative_id = Auth::user()->cooperative->id;
        $user->email = $request->email;
        $user->username = $request->user_name;
        save_user_image($user, $request);
        if (!$is_editing) {
            $user->password = Hash::make($password);
        }
        $user->save();
        return $user->refresh();
    }

    private function persist($req, $new_user, CoopEmployee $employee, $is_edit=false): string
    {
        $employee->country_code = $req->country;
        $employee->county_of_residence = $req->county;
        $employee->area_of_residence = $req->area_of_residence;
        $employee->marital_status = $req->marital_status;
        $employee->dob = $req->dob;
        $employee->gender = $req->gender;
        $employee->id_no = $req->id_no;
        $employee->phone_no = $req->phone_no;
        $employee->employee_no = $req->employee_number;
        $employee->kra = $req->kra;
        $employee->nhif_no = $req->nhif;
        $employee->nssf_no = $req->nssf;
        $employee->department_id = $is_edit ?  $employee->department_id  :$req->department;
        $employee->user_id = $new_user;
        $employee->save();
        return $employee->refresh()->id;
    }


    /**
     * Activate employees who were suspended
     * @return void
     * @throws \Throwable
     */
    private function activate_suspended_users()
    {
        $cases = EmployeeDisciplinary::whereNotNull('end_date')
        ->whereDate('end_date', '<=', Carbon::now())->get();

        try {
            $number = count($cases);
            DB::beginTransaction();
            Log::info("Updating status of {$number} cases!");
            foreach ($cases as $case) {
                $case->status = EmployeeDisciplinary::STATUS_INACTIVE;
                $case->save();
                $case->employee->status = CoopEmployee::STATUS_ACTIVE;
                $case->employee->save();
                $case->employee->user->status = User::STATUS_ACTIVE;
                $case->employee->user->save();
            }
            DB::commit();
            Log::info("{$number} cases updated!");
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }

    }

}
