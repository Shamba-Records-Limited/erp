<?php

namespace App\Http\Controllers\Admin;

use App\County;
use App\Http\Controllers\Controller;
use App\SubCounty;
use DB;

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


        $countries = get_countries();
        $counties = County::all();
        $sub_counties = SubCounty::all();

        return view('pages.admin.miller-branches.index', compact('miller_branches', 'countries', 'counties', 'sub_counties'));
    }
}
