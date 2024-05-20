<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\CoopBranch;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(){
        // todo: load farmers
        $farmers = [];
        // todo: load quality standards
        $grading = DB::select(DB::raw("
            SELECT g.id, g.name FROM product_grades g;
        "));

        $products = DB::select(DB::raw("
            SELECT p.* FROM products p;
        "));

        $coop = Auth::user()->cooperative;
        // todo: if user is assigned to branch use the default product for branch
        $default_product_id = null;
        $default_product_ids = DB::select(DB::raw("
            SELECT c.main_product_id FROM cooperatives c
            WHERE c.id = :id
        "),["id"=>$coop->id]);
        if (count($default_product_ids) > 0) {
            $default_product_id = $default_product_ids[0]->main_product_id;
        }

        $coopBranches = DB::select(DB::raw("
            SELECT b.id, b.name FROM coop_branches b
            WHERE b.cooperative_id = :id
        "),["id" => $coop->id]);

        return view('pages.cooperative-admin.collections.index', compact('products', 'farmers', 'grading', 'default_product_id', 'coopBranches'));
    }
}