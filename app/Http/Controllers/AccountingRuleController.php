<?php

namespace App\Http\Controllers;

use App\AccountingLedger;
use App\AccountingRule;
use App\Events\AuditTrailEvent;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AccountingRuleController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $cooperative = Auth::user()->cooperative_id;
        $ledgers = AccountingLedger::whereNull('deleted_at')
            ->where(function ($query) use ($cooperative) {
                $query->where('cooperative_id', $cooperative)->orWhereNull('cooperative_id');
            }
            )->get();
        $rules = AccountingRule::where('cooperative_id', $cooperative)->whereNull('deleted_at')->get();
        return view('pages.cooperative.accounting.acc_rules', compact('ledgers', 'rules'));
    }

    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'debit_ledger' => 'required',
            'credit_ledger' => 'required',
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();
            $rule = new AccountingRule();
            $rule->name = $request->name;
            $rule->debit_ledger_id = $request->debit_ledger;
            $rule->credit_ledger_id = $request->credit_ledger;
            $rule->description = $request->description;
            $rule->cooperative_id = $user->cooperative_id;
            $rule->save();
            DB::commit();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Created an Accounting rule ' . $request->name,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Accounting rule created successfully');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }
    }


    /**
     * @throws \Throwable
     * @throws ValidationException
     */
    public function edit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_name' => 'required',
            'edit_debit_ledger' => 'required',
            'edit_credit_ledger' => 'required',
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();
            $rule = AccountingRule::find($id);
            $message = 'Updated an Accounting rule ' . $rule->name;
            $rule->name = $request->edit_name;
            $rule->debit_ledger_id = $request->edit_debit_ledger;
            $rule->credit_ledger_id = $request->edit_credit_ledger;
            $rule->description = $request->edit_description;
            $rule->save();
            DB::commit();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => $message,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->success('Accounting rule updated successfully');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }
    }

    public function delete($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $user = Auth::user();
            $rule = AccountingRule::find($id);
            $message = 'deleted an Accounting rule ' . $rule->name;
            $rule->delete();
            DB::beginTransaction();
            DB::commit();
            $audit_trail_data = ['user_id' => $user->id, 'activity' => $message,
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($audit_trail_data));
            toastr()->warning('Accounting deleted!');
            return redirect()->back();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }
    }
}
