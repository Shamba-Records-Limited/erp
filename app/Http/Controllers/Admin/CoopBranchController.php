<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CoopBranch;
use App\Cooperative;
use App\County;
use App\Events\AuditTrailEvent;
use App\Product;
use App\SubCounty;
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
    // Get basic branch data
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

    // Add this calculation for branches registered this month
    $branchesThisMonth = DB::select(DB::raw("
        SELECT COUNT(*) as count 
        FROM coop_branches 
        WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
        AND YEAR(created_at) = YEAR(CURRENT_DATE())
        AND deleted_at IS NULL
    "))[0]->count;

    // Your existing code continues...
    $branchesPerCooperative = DB::select(DB::raw("
        SELECT 
            c.id,
            c.name as cooperative_name,
            COUNT(b.id) as branch_count
        FROM cooperatives c
        LEFT JOIN coop_branches b ON c.id = b.cooperative_id
        WHERE b.deleted_at IS NULL
        GROUP BY c.id, c.name
        ORDER BY branch_count DESC
    "));

    $branchesPerCounty = DB::select(DB::raw("
        SELECT 
            county.name as county_name,
            COUNT(b.id) as branch_count
        FROM counties county
        LEFT JOIN coop_branches b ON county.id = b.county_id
        WHERE b.deleted_at IS NULL
        GROUP BY county.id, county.name
        ORDER BY branch_count DESC
        LIMIT 5
    "));

    $performanceMetrics = [
        'total_branches' => count($branches),
        'total_cooperatives' => Cooperative::count(),
        'total_counties' => County::count(),
        'avg_branches_per_coop' => count($branches) / (Cooperative::count() ?: 1),
        'most_active_county' => $branchesPerCounty[0]->county_name ?? 'N/A',
        'branches_this_month' => $branchesThisMonth
    ];

    $cooperatives = Cooperative::all();
    $products = Product::all();
    $counties = County::all();
    $sub_counties = SubCounty::all();

    return view('pages.admin.branch.index', compact(
        'branches', 
        'cooperatives', 
        'products', 
        'counties', 
        'sub_counties',
        'branchesPerCooperative',
        'branchesPerCounty',
        'performanceMetrics',
        'branchesThisMonth'  // Add this line
    ));
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
            'main_product_id' => 'required|exists:products,id',
            "county_id" => "required|exists:counties,id",
            "sub_county_id" => "required|exists:sub_counties,id",
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
            $branch->product_id = $request->main_product_id;
            $branch->county_id = $request->county_id;
            $branch->sub_county_id = $request->sub_county_id;
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