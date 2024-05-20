<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Http\Controllers\Controller;
use App\Product;
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
        $qualityStandards = [];

        $products = DB::select(DB::raw("
            SELECT p.* FROM products p
            WHERE (SELECT 1 FROM product_pricing pp WHERE pp.product_id = p.id)=1;
        "));

        return view('pages.cooperative-admin.collections.index', compact('products', 'farmers', 'qualityStandards'));
    }
}