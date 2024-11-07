<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CoopBranch;
use App\Events\AuditTrailEvent;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class CoopBranchController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    //return branch view
    public function index()
    {
        $coop = Auth::user()->cooperative->id;
        $branches = CoopBranch::where('cooperative_id',$coop)->latest()->get();
        return view('pages.cooperative.hr.branch.index', compact('branches'));
    }
    public function edit($id)
    {
        $coop = Auth::user()->cooperative->id;
        $branch = CoopBranch::find($id);
        return view('pages.cooperative.hr.branch.edit', compact('branch','id'));
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'location' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $branch = new CoopBranch();
            $branch->name = $request->name;
            $branch->location = $request->location;
            $branch->code = $request->code;
            $branch->cooperative_id = $coop;
            $branch->save();
            
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Created branch '. $request->name.
                ' to  '.$coop_name, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Branch Created Successfully');
            return redirect()->route('hr.branches.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Branch failed to create');
            return redirect()->route('hr.branches.show');
        }
    }
    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string',
            'location' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $branch = CoopBranch::findOrFail($request->id);
            $branch->name = $request->name;
            $branch->location = $request->location;
            $branch->code = $request->code;
            if($branch->isDirty()){
                $branch->save();
            
                //audit trail log
                $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Updated branch '. $request->name.
                ' to  '.$coop_name, 'cooperative_id'=> $coop];
                event(new AuditTrailEvent($activity_log));
                DB::commit();
                toastr()->success('Branch updated Successfully');
                return redirect()->route('hr.branches.show');
            }
            else{
                toastr()->success('Branch details are unchanged');
                return redirect()->route('hr.branches.show');
            }
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Branch failed to update');
            return redirect()->route('hr.branches.show');
        }
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $coop = Auth::user()->cooperative->id;
            $coop_name = Auth::user()->cooperative->name;
            $branch = CoopBranch::findOrFail($id);
            $branch->delete();
            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Deleted branch '. $branch['name'].
            ' belonging to '.$coop_name, 'cooperative_id'=> $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Branch deleted Successfully');
            return redirect()->route('hr.branches.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Branch failed to delete');
            return redirect()->route('hr.branches.show');
        }
    }
    // CoopBranchController.php

  public function collections(Request $request, $id)
{
    $coop = Auth::user()->cooperative->id;

    // Fetch collections for the specified branch with additional fields
    $collections = DB::select(DB::raw("
        SELECT c.*, branch.name AS branch_name
        FROM collections c
        JOIN coop_branches branch ON branch.id = c.coop_branch_id
        WHERE c.cooperative_id = :coop_id AND c.coop_branch_id = :branch_id
    "), ['coop_id' => $coop, 'branch_id' => $id]);

    // Convert the array to a collection
    $collections = collect($collections);

    return view('pages.cooperative-admin.branches.collections', compact('collections'));
}
}
