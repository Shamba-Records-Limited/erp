<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmerManagementMiniDashboardController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $cooperative = Auth::user()->cooperative_id;
        $farmer_routes = $this->farmer_routes_count($cooperative);
        $collections_per_route = $this->collections_per_route($cooperative);
        return view('pages.cooperative.minidashboards.farmer-management', compact('farmer_routes','collections_per_route'));
    }

    public function stats()
    {
        $cooperative = Auth::user()->cooperative_id;
        return json_encode([
            "farmer_routes" => $this->farmer_routes_count($cooperative),
            "collections_per_route" => $this->collections_per_route($cooperative)
        ]);
    }

    private function farmer_routes_count($cooperative): array
    {
        return DB::select("SELECT count(f.id) AS farmers, r.name AS route FROM
        routes r JOIN farmers f ON f.route_id = r.id   WHERE r.cooperative_id='$cooperative' GROUP BY f.route_id ORDER BY route");

    }

    private function collections_per_route($cooperative): array
    {
        return DB::select("SELECT sum(c.quantity) AS collections, r.name  AS route FROM collections c 
         JOIN farmers f ON c.farmer_id = f.id JOIN routes r ON f.route_id = r.id WHERE c.cooperative_id='$cooperative'
         GROUP BY f.route_id ORDER BY route ;");

    }
}
