<?php

namespace App\Http\Controllers;

use App\Cooperative;
use App\CooperativeFinancialPeriod;
use App\Events\AuditTrailEvent;
use App\Events\NewCompanyRegisteredEvent;
use App\Events\NewCooperativeRegisteredEvent;
use App\Events\NewUserRegisteredEvent;
use App\PayrollDeduction;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Webpatser\Uuid\Uuid;

class CooperativeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $countries = get_countries();
        $cooperatives = Cooperative::orderBy('default_coop', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.admin.cooperatives', compact('countries', 'cooperatives'));
    }


    public function add_company(Request $request)
    {


        $this->validate($request, [
            "cooperative_name" => "required|string",
            "abbr" => "sometimes|string",
            "country" => "required",
            "location" => "required|string",
            "address" => "required|string",
            "cooperative_email" => "required|email|unique:cooperatives,email",
            'cooperative_contact' => 'required|regex:/^[0-9]{10}$/|unique:cooperatives,contact_details',
            'cooperative_currency' => 'required|string|min:2',
            'f_name' => 'required|string',
            'o_names' => 'required|string',
            'user_email' => 'required|email|unique:users,email',
            'u_name' => 'required|unique:users,username',
        ]);

        try {

            DB::beginTransaction();

            $cooperative = new Cooperative();
            $cooperative->name = $request->cooperative_name;
            $cooperative->abbreviation = $request->abbr;
            $cooperative->country_id = $request->country;
            $cooperative->location = $request->location;
            $cooperative->address = $request->address;
            $cooperative->email = $request->cooperative_email;
            $cooperative->contact_details = $request->cooperative_contact;
            $cooperative->currency = $request->cooperative_currency;
            $cooperative->save();

            $cooperative_id = $cooperative->id;

            $this->create_financial_period($cooperative_id, 'annually');
            $this->create_financial_period($cooperative_id, 'quarterly');
            $this->create_financial_period($cooperative_id, 'monthly');

            $date = (new DateTime())->format('Y-m-d H:i:s');
            
            $inventoryId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Inventory', 'description' => 'Inventory', 
                    'ledger_code' => 10008,  'parent_ledger_id' => 1, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $customerDebtsId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Customer Debts (AR)', 'description' => 'Customer Debts (AR)', 
                    'ledger_code' => 10009,  'parent_ledger_id' => 1, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $purchasesId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Purchases (AP)', 'description' => 'Purchases (AP)', 
                    'ledger_code' => 20010,  'parent_ledger_id' => 2, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $cogsId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Cost of Goods Sold', 'description' => 'Cost of Goods Sold', 
                    'ledger_code' => 40001,  'parent_ledger_id' => 4, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $vetChargesId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Vet Charges', 'description' => 'Vet Charges', 
                    'ledger_code' => 10006,  'parent_ledger_id' => 1, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $vetInventoryId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Vet inventory', 'description' => 'Vet inventory', 
                    'ledger_code' => 10010,  'parent_ledger_id' => 1, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $loansReceivablesId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Loans Receivables', 'description' => 'Holds repaid member loans', 
                    'ledger_code' => 10011,  'parent_ledger_id' => 1, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $loanInterestId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Loan Interest', 'description' => 'Holds interest paid from member loans', 
                    'ledger_code' => 10007,  'parent_ledger_id' => 5, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $rawMaterialsId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Raw Materials', 'description' => 'Raw Materials', 
                    'ledger_code' => 10012,  'parent_ledger_id' => 1, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $subscriptionId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Insurance Subscriptions', 'description' => 'Holds insurance premiums paid', 
                    'ledger_code' => 20011,  'parent_ledger_id' => 2, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
                ]);
            $tripsId = DB::table('accounting_ledgers')->insertGetId([ 'cooperative_id' => $cooperative_id, 'name' => 'Trip Expenses', 'description' => 'Records trips taken', 
                'ledger_code' => 40002,  'parent_ledger_id' => 4, 'type' => 'current', 'created_at' => $date, 'updated_at' => $date
            ]);

            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 18:13:47', '8', '4', NULL, 'Member deposit into savings account', uuid(), 'Savings Deposit', '2024-02-03 18:13:47')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 04:46:57', $customerDebtsId, '9', NULL, 'Sales returns', uuid(), 'Sales Returns', '2024-02-03 04:46:57')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-01 13:33:57', '9', $customerDebtsId, NULL, 'Credit Sales', uuid(), 'Credit Sales', '2024-02-01 13:33:57')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 02:48:28', '4', $vetInventoryId, NULL, 'Purchase of vet items', uuid(), 'Purchase Vet Items', '2024-02-03 02:48:28')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-01 13:33:23', $customerDebtsId, '4', NULL, 'Payment For Credit Sales Via Bank', uuid(), 'Credit Sales Bank Payments', '2024-02-01 13:33:23')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-01 13:34:57', $customerDebtsId, '3', NULL, 'Payment For Credit Sales Via Cash', uuid(), 'Credit Sales Cash Payments', '2024-02-01 13:34:57')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-01 13:32:40', $inventoryId, $cogsId, NULL, 'Inventory Movement For Sales', uuid(), 'Inventory Sale', '2024-02-01 13:32:40')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-06 00:33:47', '4', '14', NULL, 'Payment of salaries to employees', uuid(), 'Salary Payments', '2024-02-06 00:33:47')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 04:48:13', $cogsId, $inventoryId, NULL, 'Returns of issued goods', uuid(), 'Inventory Returns', '2024-02-03 04:48:13')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-01 13:32:06', '9', '3', NULL, 'Cash Sales', uuid(), 'Cash Sales', '2024-02-01 13:32:06')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 17:09:52', $loanInterestId, '4', NULL, 'Loan interest paid', uuid(), 'Loan Interest Paid', '2024-02-03 17:09:52')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 17:07:59', '4', $loansReceivablesId, NULL, 'Member awarded a loan', uuid(), 'Loan Awarded', '2024-02-03 17:07:59')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-01 13:35:33', $purchasesId, $inventoryId, NULL, 'Purchase Farmer Produce On Credit', uuid(), 'Farmer Collections', '2024-02-01 13:35:33')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 17:08:41', $loansReceivablesId, '4', NULL, 'Loan repayment', uuid(), 'Loan Repaid', '2024-02-03 17:47:24')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 01:53:06', $vetChargesId, '4', NULL, 'Income from vet charges', uuid(), 'Vet Charges', '2024-02-03 01:53:06')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-03 05:23:13', '4', '11', NULL, 'Purchase of property', uuid(), 'Property Purchase', '2024-02-03 05:23:13')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-06 11:56:18', '4', '25', NULL, 'Payment of suppliers', uuid(), 'Supplier Payment', '2024-02-06 11:56:18')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-06 14:39:52', $subscriptionId, '4', NULL, 'Member pays insurance premium', uuid(), 'Insurance Premium Paid', '2024-02-06 14:39:52')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-06 00:49:08', '4', $purchasesId, NULL, 'Pay for credit purchases of farmer produce', uuid(), 'Farmer Payments', '2024-02-06 00:49:08')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-06 11:55:43', '25', $rawMaterialsId, NULL, 'Purchase of raw materials', uuid(), 'Purchase Raw Materials', '2024-02-06 11:55:43')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-06 11:55:43', $loansReceivablesId, $loanInterestId, NULL, 'Captures outstanding loan interest', uuid(), 'Loan Interest Outstanding', '2024-02-06 11:55:43')");
            DB::insert("insert into accounting_rules (cooperative_id, created_at, credit_ledger_id, debit_ledger_id, deleted_at, description, id, name, updated_at) values ('$cooperative_id', '2024-02-06 11:55:43', '4', $tripsId, NULL, 'Captures outstanding loan interest', uuid(), 'Records expenses from trips taken', '2024-02-06 11:55:43')");
                            
            $user = new User();
            $password = generate_password();
            $user->first_name = ucwords(strtolower($request->f_name));
            $user->other_names = ucwords(strtolower($request->o_names));
            $user->cooperative_id = $cooperative_id;
            $user->email = $request->user_email;
            $user->username = $request->u_name;
            $user->password = Hash::make($password);
            $user->save();

            //get roles
            $role = Role::select('id', 'name')->where('name', '=', 'cooperative admin')->first();
            $new_user = $user->refresh();
            $new_user->assignRole($role->name);

            $role_created_audit = ['user_id' => Auth::user()->id, 'activity' => 'Assigned ' . $role->name .
                ' to  ' . $new_user->username, 'cooperative_id' => Auth::user()->cooperative->id];

            event(new AuditTrailEvent($role_created_audit));

            $data = ["name" => ucwords(strtolower($request->f_name)) . ' ' . ucwords(strtolower($request->o_names)),
                "email" => $request->user_email, "password" => $password];
            $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Created ' . $cooperative->name .
                ' cooperative and user ' . $user->username . 'account', 'cooperative_id' => Auth::user()->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            event(new NewCooperativeRegisteredEvent($data));
            event(new NewUserRegisteredEvent($data));
            DB::commit();
            toastr()->success('Cooperative Created Successfully');
            return redirect()->route('cooperative')->withInput();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }


    }

    public static function create_financial_period($cooperative_id, $type): void
    {
        try {
            DB::beginTransaction();
            $start_date = Carbon::now()->format('Y-m-d');
            if ($type == 'annually') {
                $end_date = Carbon::parse($start_date)->addYear()->format('Y-m-d');
            }

            if ($type == "quarterly") {
                $end_date = Carbon::parse($start_date)->addMonths(4)->format('Y-m-d');
            }

            if ($type == "monthly") {
                $end_date = Carbon::parse($start_date)->addMonths(1)->format('Y-m-d');
            }

            $fy = new CooperativeFinancialPeriod();
            $fy->cooperative_id = $cooperative_id;
            $fy->start_period = $start_date;
            $fy->end_period = $end_date;
            $fy->type = $type;
            $fy->balance_bf = 0;
            $fy->save();
            DB::commit();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            redirect()->back()->withInput();
        }

    }

    public function payroll_config(){
        $countries = get_countries();
        $configs = PayrollDeduction::orderBy('min_amount')->get();
        return view('pages.admin.payroll-config', compact('countries', 'configs'));
    }

    public function add_payroll_config(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'min_amount' => 'sometimes|nullable|regex:/^\d+(\.\d{1,2})?$/',
            'max_amount' => 'sometimes|nullable|regex:/^\d+(\.\d{1,2})?$/',
            'amount' => 'sometimes|nullable|required_without:rate',
            'rate' => 'sometimes|nullable|required_without:amount|regex:/^\d+(\.\d{1,2})?$/',
            'deduction_stage' => 'required',
            'country' => 'required',
            'base_amount' => 'required',
        ]);

        //check if the code deduction is set in the country
        $name = strtolower($request->name);
        $exists = PayrollDeduction::where('name', $name)
            ->where('country_id', $request->country)
            ->where('min_amount', $request->min_amount)
            ->where('max_amount', $request->min_amount)
            ->count() > 0;

        if($exists){
            toastr()->error("$name deduction already set");
            return redirect()->back()->withInput()->withErrors(["name" => "Deduction already set"]);
        }
        $new_deduction = new PayrollDeduction();
        $new_deduction->name = strtoupper($name);
        $new_deduction->min_amount = $request->min_amount;
        $new_deduction->max_amount = $request->max_amount;
        $new_deduction->amount = $request->amount;
        $new_deduction->deduction_stage = $request->deduction_stage;
        $new_deduction->rate = $request->rate;
        $new_deduction->on_gross_pay = $request->base_amount;
        $new_deduction->country_id = $request->country;
        $new_deduction->save();
        $user = Auth::user();
        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Added a new deduction '.$name.' to country id'.$request->country,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Deduction was added');
        return redirect()->back();

    }

    public function edit_payroll_config($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'min_amount' => 'sometimes|nullable|regex:/^\d+(\.\d{1,2})?$/',
            'max_amount' => 'sometimes|nullable|regex:/^\d+(\.\d{1,2})?$/',
            'amount' => 'sometimes|nullable|required_without:rate',
            'rate' => 'sometimes|nullable|required_without:amount|regex:/^\d+(\.\d{1,2})?$/',
            'deduction_stage' => 'required',
            'country' => 'required',
            'base_amount' => 'required',
        ]);

        $new_deduction =  PayrollDeduction::findOrFail($id);
        $new_deduction->name = strtoupper($request->name);
        $new_deduction->min_amount = $request->min_amount;
        $new_deduction->max_amount = $request->max_amount;
        $new_deduction->amount = $request->amount;
        $new_deduction->deduction_stage = $request->deduction_stage;
        $new_deduction->rate = $request->rate;
        $new_deduction->country_id = $request->country;
        $new_deduction->on_gross_pay = $request->base_amount;
        $new_deduction->save();
        $user = Auth::user();
        $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Updated  deduction '.$request->name.' to country id'.$request->country,
            'cooperative_id' => $user->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        toastr()->success('Deduction was updated');
        return redirect()->back();
    }

    public function delete_payroll_config($id): \Illuminate\Http\RedirectResponse
    {
        $config = PayrollDeduction::findOrFail($id);
        $config->delete();
        Log::info("Delete payroll config Id $id");
        toastr()->success("Deleted successfully");
        return redirect()->back();
    }

}
