<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $collections = DB::select(DB::raw("
            SELECT usr.username, p.name as product_name, quantity, c.*, pc.unit,
                   co.name as cooperative_name
            FROM collections c
            JOIN farmers f ON f.id = c.farmer_id
            JOIN users usr ON usr.id = f.user_id
            JOIN products p ON p.id = c.product_id
            JOIN product_categories pc ON pc.id = p.category_id
            JOIN cooperatives co ON co.id = c.cooperative_id
            ORDER BY c.created_at DESC;
        "));


        return view('pages.admin.collections.index', compact('collections'));
    }
    public function viewBranch($branchId) // New method to view branch details
{
    $branch = DB::table('coop_branches')->find($branchId);
    $farmers = DB::select(DB::raw("
        SELECT f.id, u.username
        FROM farmers f
        JOIN users u ON f.user_id = u.id
        JOIN farmer_cooperative fc ON fc.farmer_id = f.id
        WHERE fc.cooperative_id = :coop_id
    "), ["coop_id" => $branch->cooperative_id]); // Fetch farmers for the branch

    $totalFarmers = count($farmers); // Count of farmers

    return view('pages.admin.branch.view', compact('branch', 'farmers', 'totalFarmers'));
}
}