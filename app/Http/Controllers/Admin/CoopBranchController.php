<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CoopBranch;
use App\Cooperative;
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
        $branches = DB::select(DB::raw("
                SELECT 
                    b.*,
                    c.name as coop_name,
                    county.name as county_name,
                    sub_county.name as sub_county_name
                FROM coop_branches b
                JOIN cooperatives c ON b.cooperative_id = c.id
                LEFT JOIN counties county ON county.id = b.county_id
                LEFT JOIN sub_counties sub_county ON sub_county.id = b.sub_county_id
                WHERE b.deleted_at IS NULL
                ORDER BY b.created_at DESC;
            "));

        $cooperatives = Cooperative::all();

        return view('pages.admin.branch.index', compact('branches', 'cooperatives'));
    }

    public function edit($id)
    {
        $coop = Auth::user()->cooperative->id;
        // $branch = CoopBranch::find($id);
        $branches = DB::select(DB::raw("
                SELECT b.*, c.name as coop_name FROM coop_branches b
                JOIN cooperatives c ON b.cooperative_id = c.id
                WHERE b.deleted_at IS NULL
                ORDER BY b.created_at DESC;
            "));

        $branch = null;
        if (count($branches) > 0) {
            $branch = $branches[0];
        }

        return view('pages.admin.branch.edit', compact('branch', 'id'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'cooperative_id' => 'required|exists:cooperatives,id',
            'name' => 'required|string',
            'location' => 'required|string',
            'main_product_id' => 'required|exists:products,id'
        ]);
        try {
            DB::beginTransaction();
            //new branch
            $coop_id = $request->cooperative_id;
            $coop = Cooperative::find($coop_id);

            $coop_name = $coop->name;
            $branch = new CoopBranch();
            $branch->name = $request->name;
            $branch->location = $request->location;
            $branch->code = $request->code;
            $branch->cooperative_id = $coop_id;
            $branch->main_product_id = $request->main_product_id;
            $branch->save();

            //audit trail log
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Created branch ' . $request->name .
                ' to  ' . $coop_name, 'cooperative_id' => $coop_id];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Branch Created Successfully');
            return redirect()->route('branches.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Branch failed to create');
            return redirect()->route('branches.show');
        }
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'location' => 'required|string',
            'main_product_id' => 'required|exists:products,id'
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
            $branch->main_product_id = $request->main_product_id;
            if ($branch->isDirty()) {
                $branch->save();

                //audit trail log
                $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Updated branch ' . $request->name .
                    ' to  ' . $coop_name, 'cooperative_id' => $coop];
                event(new AuditTrailEvent($activity_log));
                DB::commit();
                toastr()->success('Branch updated Successfully');
                return redirect()->route('branches.show');
            } else {
                toastr()->success('Branch details are unchanged');
                return redirect()->route('branches.show');
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Branch failed to update');
            return redirect()->route('branches.show');
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
            $activity_log = ['user_id' => Auth::user()->id, 'activity' => 'Deleted branch ' . $branch['name'] .
                ' belonging to ' . $coop_name, 'cooperative_id' => $coop];
            event(new AuditTrailEvent($activity_log));
            DB::commit();
            toastr()->success('Branch deleted Successfully');
            return redirect()->route('branches.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Branch failed to delete');
            return redirect()->route('branches.show');
        }
    }
}
