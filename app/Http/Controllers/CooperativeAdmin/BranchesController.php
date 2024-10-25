<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\CoopBranch;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class BranchesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function detail(Request $request, $id)
    {
        $coop = Auth::user()->cooperative;
        $coop_id = $coop->id;

        $tab = $request->query('tab', 'manager');

        $employees = DB::select(DB::raw("
            SELECT e.id, e.user_id, u.username, u.first_name, u.other_names FROM coop_employees e
            JOIN users u ON u.id = e.user_id
            WHERE u.cooperative_id = :coop_id
        "), ['coop_id' => $coop_id]);

        $branches = DB::select(DB::raw("
            SELECT b.* FROM coop_branches b
            WHERE b.id = :id
        "), ["id" => $id]);

        $branch = null;
        if (count($branches) > 0) {
            $branch = $branches[0];
        }

        return view("pages.cooperative-admin.branches.detail", compact("tab", "employees", "branch"));
    }

    public function set_manager(Request $request, $id)
    {
        $request->validate([
            "manager_id" => "required|exists:users,id"
        ]);

        $incomingManager = User::find($request->manager_id);
        $branch_id = $id;

        $rawCurrentManagers = DB::select(DB::raw("
            SELECT u.id FROM coop_branches b
            JOIN users u ON u.id = b.manager_id
            WHERE b.id = :id
        "), ["id" => $branch_id]);
        $currentManager = null;
        if (count($rawCurrentManagers) > 0) {
            $currentManagerId = $rawCurrentManagers[0]->id;
            $currentManager = User::find($currentManagerId);
        }

        if (!is_null($currentManager) && $incomingManager->id == $currentManager->id) {
            // unchanged
            return redirect()->route("cooperative-admin.branches.detail", $branch_id);
        }

        try {
            DB::beginTransaction();
            // remove role - coop branch admin
            if ($currentManager != null) {
                // current manager branches count
                $rawMyBranchesCount = DB::select(DB::raw("
                    SELECT count(1) AS count FROM coop_branches b
                    WHERE b.manager_id = :id
                "), ["id" => $currentManager->id]);

                $myBranchesCount = $rawMyBranchesCount[0]->count;

                if ($myBranchesCount <= 1) {
                    $currentManager->removeRole("coop branch admin");
                    $currentManager->save();
                }
            }

            // add role to incoming manager
            $incomingManager->assignRole("coop branch admin");
            $incomingManager->save();

            $branch = CoopBranch::find($branch_id);
            $branch->manager_id = $incomingManager->id;
            $branch->save();

            DB::commit();
            toastr()->success('Manager Updated Successfully');
            DB::commit();
            return redirect()->route("cooperative-admin.branches.detail", $branch_id);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            Log::error($th->getTraceAsString());
            DB::rollback();
            toastr()->error('Branch manager could not be set');
            return redirect()->back()->withInput();
        }
    }

    public function branches_mini_dashboard(Request $request)
{
    $user = Auth::user();
    $coop = $user->cooperative;
    $coop_id = $coop->id;

    // Fetch collections by branches
    $collections_by_wet_mills = DB::select(DB::raw("
        SELECT SUM(quantity) AS quantity, branch.name AS name, branch.code AS code
        FROM collections c
        JOIN coop_branches branch ON branch.id = c.coop_branch_id
        WHERE c.cooperative_id = :coop_id
        GROUP BY branch.id
        ORDER BY quantity DESC
    "), ["coop_id" => $coop_id]);

    // Calculate average collection over time
    $average_collection_over_time = DB::select(DB::raw("
        SELECT DATE(c.created_at) AS date, AVG(c.quantity) AS average_quantity
        FROM collections c
        WHERE c.cooperative_id = :coop_id
        GROUP BY DATE(c.created_at)
        ORDER BY DATE(c.created_at) ASC
    "), ["coop_id" => $coop_id]);

    $data = [
        "collections_by_wet_mills" => $collections_by_wet_mills,
        "average_collection_over_time" => $average_collection_over_time, // Add this line
    ];

    // Fetch branches for the view
    $branches = CoopBranch::where("cooperative_id", $coop_id)->get();

    // Add performance status to each branch
    foreach ($branches as $branch) {
        $branch->performance_status = 'Inactive'; // Default to Inactive

        foreach ($collections_by_wet_mills as $collection) {
            if ($branch->name === $collection->name) {
                if ($collection->quantity > 1000) {
                    $branch->performance_status = 'High Performing';
                } elseif ($collection->quantity >= 100) {
                    $branch->performance_status = 'Active';
                } else {
                    $branch->performance_status = 'Inactive';
                }
                break;
            }
        }
    }

    return view('pages.cooperative-admin.branches.mini-dashboard', compact("data", "branches"));
}



}