<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoopBranch;
use App\CoopBranchDepartment;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class CoopDepartmentController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    //return branch view
    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        //get branches
        $branch_query = CoopBranch::where('cooperative_id',$coop);
        $branch_ids = $branch_query->pluck('id');
        $branches = $branch_query->get();
        $departments = CoopBranchDepartment::whereIn('branch_id',$branch_ids)->with('departmentEmployee')->latest()->get();
        return view('pages.cooperative.hr.department.index', compact('departments','branches'));
    }
    public function edit($id)
    {
        $coop = Auth::user()->cooperative->id;
        //get branches
        $branch_query = CoopBranch::where('cooperative_id',$coop);
        $branch_ids = $branch_query->pluck('id');
        $branches = $branch_query->get();
        $department = CoopBranchDepartment::whereIn('branch_id',$branch_ids)->with('departmentEmployee','coopBranch')->find($id);
        return view('pages.cooperative.hr.department.edit', compact('department','branches','id'));
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'branch' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $department = new CoopBranchDepartment();
            $department->name = $request->name;
            $department->office_number = $request->office_number;
            $department->code = $request->code;
            $department->branch_id = $request->branch;
            $department->save();
            
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Created department '. $request->name.
                ' to  '.$request->branch, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Department Created Successfully');
            return redirect()->route('hr.departments.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Department failed to create');
            return redirect()->route('hr.departments.show');
        }
    }
    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'branch' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $department = CoopBranchDepartment::findOrFail($request->id);
            $department->name = $request->name;
            $department->office_number = $request->office_number;
            $department->code = $request->code;
            if($department->isDirty()){
                $department->save();
            
                //audit trail log
                $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Updated department '. $request->name.
                ' for  '.$coop_name, 'cooperative_id'=> $coop];
                event(new AuditTrailEvent($activity_log));
                DB::commit();
                toastr()->success('department updated Successfully');
                return redirect()->route('hr.departments.show');
            }
            else{
                toastr()->success('Department details are unchanged');
                return redirect()->route('hr.departments.show');
            }
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Department failed to update');
            return redirect()->route('hr.departments.show');
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $department = CoopBranchDepartment::findOrFail($id);
            $department->delete();
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Deleted department '. $department['name'].
            ' belonging to '.$coop_name, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Departments deleted Successfully');
            return redirect()->route('hr.departments.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Departments failed to delete');
            return redirect()->route('hr.departments.show');
        }
    }
}
