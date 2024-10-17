<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\LoanSetting;
use App\SavingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function loan_configs()
    {
        $loan_configurations = LoanSetting::where('cooperative_id', Auth::user()->cooperative->id)
            ->orderBy("created_at","desc")->get();
        return view('pages.cooperative.configs.loans-configs', compact('loan_configurations'));
    }

    public function loan_add(Request $request)
    {
        $this->validate($request, [
           "type" => "required|string",
            "interest" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "penalty" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "period" => "required|integer|min:1",
            "installments" => "required",
        ]);
        $loan_settings= new LoanSetting();
        $this->save_loan_settings($request, $loan_settings,false);
        $data = ['user_id' => Auth::user()->id, 'activity' => 'Added a loan configuration of type '.$request->type,'cooperative_id'=> Auth::user()->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Loan Configuration added');
        return redirect()->route('cooperative.loan_configs');
    }

    private function save_loan_settings($request, $loan_settings, $isUpdating)
    {
            $loan_settings->type = $request->type;
            $loan_settings->interest = $request->interest;
            $loan_settings->penalty = $request->penalty;
            $loan_settings->period = $request->period;
            $loan_settings->installments = $request->installments;
            $loan_settings->cooperative_id = Auth::user()->cooperative->id;
            if($isUpdating)
            {
                $loan_settings->updated_at = date('Y-m-d H:i:s');
            }
            $loan_settings->save();
    }

    public function savings_types()
    {
        $saving_configurations = SavingType::where('cooperative_id', Auth::user()->cooperative->id)
            ->orderBy("created_at","desc")->get();
        return view('pages.cooperative.configs.savings-configs', compact('saving_configurations'));
    }

    public function saving_type_add(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "type" => "required|string",
            "interest" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "period" => "required|integer|min:1",
        ]);
        $saving_settings= new SavingType();
        $this->save_savings_settings($request, $saving_settings,false);
        $data = ['user_id' => Auth::user()->id, 'activity' => 'Added a saving configuration of type '.$request->type,'cooperative_id'=> Auth::user()->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Saving Configuration added');
        return redirect()->route('cooperative.saving_types');
    }

    public function edit_loan_saving_types(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "type" => "required|string",
            "interest" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "penalty" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "period" => "required|integer|min:1",
            "installments" => "required",
        ]);
        $loan_settings= LoanSetting::findOrFail($id);
        $this->save_loan_settings($request, $loan_settings,true);
        $data = ['user_id' => Auth::user()->id, 'activity' => 'Edit a loan configuration of type '.$request->type,'cooperative_id'=> Auth::user()->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Loan Configuration Updated');
        return redirect()->route('cooperative.loan_configs');
    }

    public function edit_saving_types(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "type" => "required|string",
            "interest" => "required|regex:/^\d+(\.\d{1,2})?$/",
            "period" => "required|integer|min:1",
        ]);

        $saving_settings= SavingType::findOrFail($id);
        $this->save_savings_settings($request, $saving_settings,true);
        $data = ['user_id' => Auth::user()->id, 'activity' => 'Updated a saving configuration of type '.$request->type,'cooperative_id'=> Auth::user()->cooperative->id];
        event(new AuditTrailEvent($data));
        toastr()->success('Saving Configuration Updated');
        return redirect()->route('cooperative.saving_types');
    }

    private function save_savings_settings($request, $saving_settings, $isUpdating)
    {
        $saving_settings->type = $request->type;
        $saving_settings->interest = $request->interest;
        $saving_settings->period = $request->period;
        $saving_settings->cooperative_id = Auth::user()->cooperative->id;
        if($isUpdating)
        {
            $saving_settings->updated_at = date('Y-m-d H:i:s');
        }
        $saving_settings->save();
    }
}
