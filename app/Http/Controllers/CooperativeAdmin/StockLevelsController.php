<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Http\Controllers\Controller;
use App\Product;
use App\Collection;
use App\Lot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockLevelsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    // Show stock levels for all products
    public function index()
    {
        $coop_id = Auth::user()->cooperative->id;

        $stockLevels = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(collections.quantity) AS total_collected'),
                'product_categories.unit'  // Add unit from product_categories
            )
            ->leftJoin('collections', 'products.id', '=', 'collections.product_id')
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id') // Join for unit
            ->where('collections.cooperative_id', $coop_id)
            ->groupBy('products.id', 'product_categories.unit')

            ->get();

        return view('pages.cooperative-admin.stock-levels.index', compact('stockLevels'));
    }

    // Show detailed stock levels for a specific product
    public function show($productId)
    {
        $coop_id = Auth::user()->cooperative->id;

        // Get product stock details
        $productStock = DB::table('collections')
            ->select(
                'collections.lot_number',
                'collections.quantity',
                'collections.date_collected',
                'product_categories.unit' 
            )
            ->join('products', 'collections.product_id', '=', 'products.id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->where('collections.product_id', $productId)
            ->where('collections.cooperative_id', $coop_id)
            ->get();

        return view('pages.cooperative-admin.stock-levels.show', compact('productStock'));
    }

    // Update stock levels
    public function updateStockLevels()
    {
        // Implement stock level update logic here as needed
    }
}
