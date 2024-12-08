<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request; // Correct namespace for the Request class
use App\Product;
use App\ProductCategory;
use Illuminate\Support\Facades\Auth;

class MarketProductsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function index()
    {
        $product = Product::all();
        $products = $product->toArray();
        $categories = ProductCategory::all();
        //dd($products);
        // Paginate the products (8 per page)
        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($products, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new LengthAwarePaginator($currentItems, count($products), $perPage);
        $paginatedProducts->setPath(route('miller-admin.marketplace-products'));
        // Pass the paginated products to the view
        return view('pages.miller-admin.market-auction.products', compact('paginatedProducts','categories'));
    }

    public function add_product(Request $request) {

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
            'category_id' =>'required',
        ]);
    
        $imagePath = $request->file('image')->store('images/product_images', 'public');
    
        $product = Product::create([
            'name' => $request->name,
            'image' => $imagePath,
            'quantity' => $request->quantity,
            'sale_price' => $request->price,
            'miller_id'=> $miller_id,
            'category_id' => $request->category_id,
        ]);

        // dd($product);     /.oduct->save();

        //dd($request->all());

        toastr()->success('Product saved successfully!');
        return redirect()->route("miller-admin.marketplace-products");
    }
    

}
