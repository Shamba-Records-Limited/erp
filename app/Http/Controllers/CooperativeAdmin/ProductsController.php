<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Http\Controllers\Controller;
use App\ProductPricing;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class ProductsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $products = DB::select(DB::raw("
            SELECT p.id, p.name, pc.name as category_name FROM products p
            LEFT JOIN product_categories pc ON p.category_id = pc.id;
        "));
        return view('pages.cooperative-admin.products.index', compact("products"));
    }

    public function detail($id)
    {
        $user = Auth::user();
        $coop_id = $user->cooperative->id;

        $products = DB::select(DB::raw("
            SELECT p.id, p.name, pc.name as category_name FROM products p
            LEFT JOIN product_categories pc ON p.category_id = pc.id
            WHERE p.id = :id;
        "),["id"=> $id]);

        $units = Unit::all();

        $product = [];
        if (count($products) > 0) {
            $product = $products[0];
        }

        $pricings = DB::select(DB::raw("
            SELECT pp.*, u.abbreviation as unit_abbr
            FROM product_pricing pp
            JOIN units u ON pp.unit_id = u.id
            WHERE pp.product_id = :product_id AND pp.cooperative_id = :coop_id
            ORDER BY min;
        "), ["product_id" => $id, "coop_id" => $coop_id]);

        return view('pages.cooperative-admin.products.detail', compact("product", "units", "pricings"));
    }

    public function store_product_pricing(Request $request)
    {
        $request->validate([
            "product_id"=>"required|exists:products,id",
            "unit_id"=>"required|exists:units,id",
            "min"=>"required",
            "max"=>"",
            "buying_price"=>"required",
            "selling_price"=>"required",
        ]);

        try {
            $user = Auth::user();

            $product = new ProductPricing();
            $product->cooperative_id = $user->cooperative->id;
            $product->product_id = $request->product_id;
            $product->unit_id = $request->unit_id;
            $product->min = $request->min;
            $product->max = $request->max;
            $product->buying_price = $request->buying_price;
            $product->selling_price = $request->selling_price;
            $product->created_by_id = $user->id;
            $product->save();

            toastr()->success('Product Pricing Created Successfully');
            return redirect()->route('cooperative-admin.products.detail', $request->product_id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Oops! Operation failed');
            return redirect()->back()->withInput();
        }
    }
}
