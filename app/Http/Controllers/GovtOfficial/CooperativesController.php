<?php

namespace App\Http\Controllers\GovtOfficial;

use App\Collection;
use App\Cooperative;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CooperativesController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $cooperatives = DB::select(DB::raw("
            SELECT c.*, u.first_name, u.other_names, (SELECT count(1) FROM farmers f JOIN farmer_cooperative fc ON fc.farmer_id = f.id AND fc.cooperative_id = c.id) AS num_of_farmers FROM cooperatives c
            JOIN users u ON u.id = (
                select u.id FROM users u
                JOIN model_has_roles ur ON ur.model_id = u.id
                JOIN roles r ON r.id = ur.role_id and r.name = 'cooperative admin'
                WHERE u.cooperative_id = c.id
                LIMIT 1
            )
        "));

        return view('pages.govt-official.cooperatives.index', compact('cooperatives'));
    }

    public function details(Request $request, $id)
    {
        $tab = $request->query("tab", "collections");
        $productId = $request->query("product_id", "");

        $cooperative = Cooperative::find($id);

        $totalCollectionQty = 0;
        $collections = [];
        $selectableProducts = [];
        $lots = [];
        $grades = [];

        if ($tab == 'collections') {
            $collections = DB::select(DB::raw("
                SELECT usr.username, p.name as product_name, quantity, c.*, pc.unit,
                    co.name as cooperative_name, usr.first_name, usr.other_names, f.member_no
                FROM collections c
                JOIN farmers f ON f.id = c.farmer_id
                JOIN users usr ON usr.id = f.user_id
                JOIN products p ON p.id = c.product_id
                JOIN product_categories pc ON pc.id = p.category_id
                JOIN cooperatives co ON co.id = c.cooperative_id
                WHERE c.cooperative_id = :coop_id AND 
                CASE WHEN :product_id != ''
                    THEN p.id = :product_id2
                    ELSE
                    true
                END
                ORDER BY c.created_at DESC;
            "), ["coop_id" => $id, "product_id" => $productId, "product_id2" => $productId]);

            $totalCollectionQty = DB::select(DB::raw("
                SELECT sum(c.quantity) AS qty FROM collections c
                WHERE c.cooperative_id = :coop_id
            "), ["coop_id" => $id])[0]->qty;

            $selectableProducts = Product::all();
        }
        else if ($tab == 'lots') {
            $lots = DB::select(DB::raw("
                SELECT l.* FROM lots l
                WHERE l.cooperative_id = :coop_id
                ORDER BY l.created_at DESC;
            "), ["coop_id" => $id]);
        }
        else {
            $grades = DB::select(DB::raw("
                SELECT pg.name, SUM(g.quantity) AS quantity FROM lot_grade_distributions g
                JOIN lots l ON l.lot_number = g.lot_number
                JOIN product_grades pg ON pg.id = g.product_grade_id
                GROUP BY g.product_grade_id;
            "));
        }


        return view('pages.govt-official.cooperatives.detail', compact('cooperative', 'tab', 'collections', 'lots', 'grades', 'totalCollectionQty', 'selectableProducts', "productId"));
    }
}
