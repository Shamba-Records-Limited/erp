<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Miller;
use App\MillerBranch;
use App\County;
use App\SubCounty;
use DB;
use Illuminate\Http\Request;
use Log;

class MillerBranchesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $miller_branches = DB::select(DB::raw("
            SELECT
                m.name as miller_name,
                mb.*,
                county.name as county_name,
                sub_county.name as sub_county_name
            FROM miller_branches mb
            JOIN millers m ON m.id = mb.miller_id
            LEFT JOIN counties county ON county.id = mb.county_id
            LEFT JOIN sub_counties sub_county ON sub_county.id = mb.sub_county_id;
        "));

        // Calculate unique counties covered by miller branches
        $uniqueCounties = DB::table('miller_branches')
                            ->distinct('county_id')
                            ->count('county_id');

        // Calculate branches registered this month
        $branchesThisMonth = DB::table('miller_branches')
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        $branchesPerMiller = DB::select(DB::raw("
            SELECT
                mb.miller_id,
                m.name as miller_name,
                COUNT(mb.id) as branch_count
            FROM millers m
            LEFT JOIN miller_branches mb on mb.miller_id = m.id
            GROUP BY mb.miller_id, m.name
            ORDER BY branch_count DESC
            LIMIT 5  
        "));

        $branchesPerCounty = DB::select(DB::raw("
            SELECT 
                county.name as county_name,
                COUNT(mb.id) as branch_count
            FROM counties county
            LEFT JOIN miller_branches mb ON county.id = mb.county_id
            GROUP BY county.id, county.name
            ORDER BY branch_count DESC
            LIMIT 5
        "));

        $performanceMetrics = [
            'total_branches' => count($miller_branches),
            'total_millers' => Miller::count(),
            'total_counties' => County::count(),
            'avg_branches_per_miller' => count($miller_branches) / (Miller::count() ?: 1),
            'most_active_county' => $branchesPerCounty[0]->county_name ?? 'N/A',
            'branches_this_month' => $branchesThisMonth
        ];

        $millers = DB::select(DB::raw("
            SELECT id, name FROM millers;
        "));

        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.admin.miller-branches.index', compact(
            'miller_branches',
            'millers',
            'counties',
            'sub_counties',
            'branchesPerMiller',
            'branchesPerCounty',
            'performanceMetrics',
            'branchesThisMonth',
            'uniqueCounties'
        ));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'miller_id' => 'required|exists:millers,id',
            'name' => 'required|string',
            'code' => 'required|string',
            "county_id" => "required|exists:counties,id",
            "sub_county_id" => "required|exists:sub_counties,id",
            'location' => 'required|string',
            "address" => "required|string",
        ]);
        try {
            DB::beginTransaction();
            
            $branch = new MillerBranch();
            $branch->miller_id = $request->miller_id;
            $branch->name = $request->name;
            $branch->code = $request->code;
            $branch->county_id = $request->county_id;
            $branch->sub_county_id = $request->sub_county_id;
            $branch->location = $request->location;
            $branch->address = $request->address;
            $branch->save();

            DB::commit();
            toastr()->success('Miller branch Created Successfully');
            return redirect()->route('admin.miller-branches.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Miller Branch failed to create');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        // Retrieve the branch with the associated Miller
        $branch = MillerBranch::with('miller')->findOrFail($id);

        // Get all millers, counties, and sub-counties
        $millers = Miller::all();
        $counties = County::all();
        $sub_counties = SubCounty::where('county_id', $branch->county_id)->get();

        // Pass the branch, millers, counties, and sub_counties to the view
        return view('pages.admin.miller-branches.edit', compact('branch', 'millers', 'counties', 'sub_counties'));
    }

    public function view($id)
    {
        // Fetch the branch details along with related Miller, County, and Sub-County information
        $branch = MillerBranch::with(['miller', 'county', 'subCounty'])->findOrFail($id);

        // Define any additional data or formatting if needed
        $details = [
            'Branch Name' => $branch->name,
            'Miller' => $branch->miller->name ?? 'N/A',
            'Location' => $branch->location,
            'Address' => $branch->address,
            'County' => $branch->county->name ?? 'N/A',
            'Sub-County' => $branch->subCounty->name ?? 'N/A',
            'Code' => $branch->code,
            'Created At' => $branch->created_at->format('d M Y'),
        ];

        return view('pages.admin.miller-branches.view', compact('branch', 'details'));
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $branch = MillerBranch::findOrFail($id);
            $branch->delete();

            DB::commit();
            toastr()->success('Miller branch deleted successfully');
            return redirect()->route('admin.miller-branches.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Failed to delete miller branch');
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'code' => 'required|string',
            'location' => 'required|string',
            'address' => 'required|string',
            'county_id' => 'required|exists:counties,id',
            'sub_county_id' => 'required|exists:sub_counties,id',
        ]);

        try {
            $branch = MillerBranch::findOrFail($id);
            $branch->update($request->all());

            toastr()->success('Miller branch updated successfully');
            return redirect()->route('admin.miller-branches.show');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Failed to update miller branch');
            return redirect()->back()->withInput();
        }
    }
}
