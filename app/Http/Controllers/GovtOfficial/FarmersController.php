<?php

namespace App\Http\Controllers\GovtOfficial;

use App\Collection;
use App\Farmer;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class FarmersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $coopId = $request->query("coop_id", '');

        $farmersQuery = "
            SELECT
                f.id,
                coop.name as coop_name,
                f.gender,
                u.username,
                u.first_name,
                u.other_names,
                county.name as county_name,
                sub_county.name as sub_county_name
            FROM farmers f
            JOIN users u ON f.user_id = u.id
            LEFT JOIN counties county ON county.id = f.county_id
            LEFT JOIN sub_counties sub_county ON sub_county.id = f.sub_county_id
            JOIN farmer_cooperative fc ON fc.farmer_id = f.id
            JOIN cooperatives coop ON coop.id = fc.cooperative_id
        ";

        $farmers = [];
        if ($coopId != '') {
            $farmersQuery += " WHERE fc.cooperative_id = :coop_id";
            $farmers = DB::select(DB::raw($farmersQuery), ["coop_id" => $coopId]);
        } else {
            $farmers = DB::select(DB::raw($farmersQuery));
        }

        return view('pages.govt-official.farmers.index', compact('farmers'));
    }

    public function details($id)
    {
        $farmer = Farmer::find($id);

        $collections = DB::select(DB::raw("
            SELECT usr.username, p.name as product_name, quantity, c.*, pc.unit,
                co.name as cooperative_name, usr.first_name, usr.other_names, f.member_no
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            JOIN cooperatives co ON co.id = c.cooperative_id
            WHERE f.id = :farmer_id
            ORDER BY c.created_at DESC;
        "), ["farmer_id" => $id]);

        $collectionsTotal = DB::select(DB::raw("
            SELECT Sum(c.quantity) AS total FROM collections c
            WHERE c.farmer_id = :farmer_id
        "), ["farmer_id" => $id])[0]->total;

        return view('pages.govt-official.farmers.detail', compact('farmer', 'collections', 'collectionsTotal'));
    }
}
