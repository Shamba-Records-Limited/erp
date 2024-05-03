<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmploymentType;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class EmploymentTypeController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    //return branch view
    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $types = EmploymentType::where('cooperative_id',$coop)->with('typeEmployees')->latest()->get();
        return view('pages.cooperative.hr.job-type.index', compact('types'));
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'type' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $employment_type = new EmploymentType();
            $employment_type->type = $request->type;
            $employment_type->cooperative_id = $coop;
            $employment_type->save();
            
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Created Employment type '. $request->name.
                ' for  '.$coop_name, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Type Created Successfully');
            return redirect()->route('hr.employment-types.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Type failed to create');
            return redirect()->route('hr.employment-types.show');
        }
    }
    public function update(Request $request)
    {
        $this->validate($request,[
            'type' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $employment_type =EmploymentType::find($request->id);
            $employment_type->type = $request->type;
            
            if($employment_type->isDirty()){
                $employment_type->save();
            
                //audit trail log
                $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Updated employment type '. $request->type.
                ' for  '.$coop_name, 'cooperative_id'=> $coop];
                event(new AuditTrailEvent($activity_log));
                DB::commit();
                toastr()->success('Employment types updated Successfully');
                return redirect()->route('hr.employment-types.show');
            }
            else{
                toastr()->success('employment types details are unchanged');
                return redirect()->route('hr.employment-types.show');
            }
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('employment-types failed to update');
            return redirect()->route('hr.employment-types.show');
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $type = EmploymentType::findOrFail($id);
            $type->delete();
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Deleted employment type '. $type['type'].
            ' belonging to '.$coop_name, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Employment deleted Successfully');
            return redirect()->route('hr.employment-types.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Employment type failed to delete');
            return redirect()->route('hr.employment-types.show');
        }
    }
}
