<?php

namespace App\Http\Controllers;

use App\AccountingLedger;
use App\Budget;
use App\BudgetAmount;
use App\Events\AuditTrailEvent;
use App\ParentLedger;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Throwable;

class BudgetController extends Controller
{
    //
    public function index(Request $request) 
    {
        $cooperativeId = Auth::user()->cooperative_id;

        $type = $request->query('type', 'monthly');
        $year = $request->query('year');
        $periods = $this->getPeriods($type, $year);

        $ledgers = [];
        $budgetAmounts = [];
        $parents = ParentLedger::whereIn('name', [ 'Revenue', 'Expenses' ])->get(['id', 'name']);
        $budget = Budget::where('type', $type)->where('year', $year)->where('cooperative_id', $cooperativeId)->first();

        // dd($budget);

        foreach ($parents as $parent) {

            $accountingLedgers = AccountingLedger::where('parent_ledger_id', $parent->id)
                ->where('cooperative_id', $cooperativeId)
                ->get(['id','name']);

            foreach ($accountingLedgers as $accountingLedger) {
                
                $ledgers[$parent->name][] = [
                    'id' => $accountingLedger->id,
                    'name' => $accountingLedger->name,
                ];
            }

            foreach ($periods as $period) {

                foreach ($accountingLedgers as $accountingLedger) {

                    $amount = $budget ? BudgetAmount::where('budget_id', $budget->id)->where('ledger_id', $accountingLedger->id)->where('period', $period)->first() : null;
                    $budgetAmounts[$accountingLedger->id][$period] = $amount ? $amount->amount : ' ';
                }
            }
        }

        return view('pages.cooperative.budget.index', compact('periods', 'ledgers', 'type', 'year', 'budgetAmounts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string|in:MONTHLY,QUARTERLY,YEARLY',
            'year' => 'required|numeric',
            'amount' => 'required|array'
        ]);

        try {

            $cooperativeId = Auth::user()->cooperative_id;

            DB::beginTransaction();               

            $budget = Budget::firstOrCreate([
                'type' => $request->input('type'),
                'year' => $request->input('year'),
                'cooperative_id' => $cooperativeId
            ]);

            foreach ($request->input('amount') as $period => $values) {

                foreach ($values as $ledgerId => $amount) {

                    if ($amount > 0) {
                        BudgetAmount::updateOrCreate([
                                'budget_id' => $budget->id,
                                'ledger_id' => $ledgerId,
                                'period' => $period,
                            ],[
                                'amount' => (float) $amount,
                            ]);
                    }
                }
            }

            DB::commit();

            $data = [ 'user_id' => Auth::user()->id, 'activity' => 'created '.$request->input('type').' Budget for '.$request->input('year'), 'cooperative_id' => $cooperativeId ];
            event(new AuditTrailEvent($data));
            toastr()->success('Budget updated successfully');

            return redirect()->route('cooperative.accounting.budget.index', [ 'type' => strtolower($request->input('type')), 'year' => $request->input('year') ]);

        } catch (Throwable $th) {
            DB::rollBack();
            Log::error($th);
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    private function getPeriods($type, $year = null)
    {
        if ($type == 'monthly') {
            return [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ];
        }
        else if ($type == 'quarterly') {
            return [
                'Q1', 'Q2', 'Q3', 'Q4'
            ];
        }
        else {
            return [
                $year ?? date('Y')
            ];
        }
    }
}
