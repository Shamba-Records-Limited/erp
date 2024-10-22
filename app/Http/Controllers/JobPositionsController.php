<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobPosition;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class JobPositionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    //return branch view
    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $positions = JobPosition::where('cooperative_id',$coop)->with('employeePosition')->latest()->get();
        return view('pages.cooperative.hr.job-position.index', compact('positions'));
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'position' => 'required|string',
            'role' => 'required|string',
            'code' => 'required|string',
            'description' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $position = new JobPosition();
            $position->position = $request->position;
            $position->role = $request->role;
            $position->code = $request->code;
            $position->description = $request->description;
            $position->cooperative_id = $coop;
            $position->save();
            
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Created Job Position '. $request->position.
                ' for  '.$coop_name, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Job Position Created Successfully');
            return redirect()->route('hr.job-positions.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Job position failed to create');
            return redirect()->route('hr.job-positions.show');
        }
    }
    public function update(Request $request)
    {
        $this->validate($request,[
            'position' => 'required|string',
            'role' => 'required|string',
            'code' => 'required|string',
            'description' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $position = JobPosition::find($request->id);
            $position->position = $request->position;
            $position->role = $request->role;
            $position->code = $request->code;
            $position->description = $request->description;
            $position->cooperative_id = $coop;
            
            if($employment_type->isDirty()){
                $employment_type->save();
            
                //audit trail log
                $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Updated job  position '. $request->type.
                ' for  '.$coop_name, 'cooperative_id'=> $coop];
                event(new AuditTrailEvent($activity_log));
                DB::commit();
                toastr()->success('Job position updated Successfully');
                return redirect()->route('hr.job-positions.show');
            }
            else{
                toastr()->success('Job position details are unchanged');
                return redirect()->route('hr.job-positions.show');
            }
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('employment-types failed to update');
            return redirect()->route('hr.job-positions.show');
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $position = JobPosition::findOrFail($id);
            $type->delete();
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Deleted job position '. $position['position'].
            ' belonging to '.$coop_name, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Job position deleted Successfully');
            return redirect()->route('hr.job-positions.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Job position failed to delete');
            return redirect()->route('hr.job-positions.show');
        }
    }
}
