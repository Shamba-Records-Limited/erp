<?php

namespace App\Http\Controllers\Admin;

use App\County;
use App\Http\Controllers\Controller;
use App\MillerBranch;
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
                    c.name as country_name,
                    county.name as county_name,
                    sub_county.name as sub_county_name
                FROM miller_branches mb
                JOIN millers m ON m.id = mb.miller_id
                JOIN countries c ON m.country_id = c.id
                LEFT JOIN counties county ON county.id = mb.county_id
                LEFT JOIN sub_counties sub_county ON sub_county.id = mb.sub_county_id;
            "));

        $millers = DB::select(DB::raw("
            SELECT id, name FROM millers;
        "));


        $countries = get_countries();
        $counties = County::all();
        $sub_counties = SubCounty::all();


        return view('pages.admin.miller-branches.index', compact('miller_branches', 'countries', 'counties', 'sub_counties', 'millers'));
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
}
