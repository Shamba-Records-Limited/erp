<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request; // Correct namespace for the Request class
use App\Product;

class MarketProductsController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function index()
    {
        // Hardcoded products data with relevant images from cdn.mafrservices.com
        $products = [
            ['name' => 'Nescafe Coffee', 'price' => 157.00, 'image' => 'https://images.pexels.com/photos/7507582/pexels-photo-7507582.jpeg'], // Coffee cup
            ['name' => 'Cadbury Cocoa', 'price' => 172.00, 'image' => 'https://cdn.mafrservices.com/sys-master-root/h1a/hce/9428046315550/25993_2.jpg'],

        ];
        $product = Product::all();
        $products = $product->toArray();
       // dd($products);
        // Paginate the products (8 per page)
        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($products, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new LengthAwarePaginator($currentItems, count($products), $perPage);
        $paginatedProducts->setPath(route('miller-admin.marketplace-products'));
        // Pass the paginated products to the view
        return view('pages.miller-admin.market-auction.products', compact('paginatedProducts'));
    }

    public function add_product(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:1',
        ]);
    
        $imagePath = $request->file('image')->store('images/product_images', 'public');
    
        $product = Product::create([
            'name' => $request->name,
            'image' => $imagePath,
           // 'quantity' => $request->quantity,
            'sale_price' => $request->price,
        ]);
        $product->save();

        //dd($request->all());

        toastr()->success('Product saved successfully!');
        return redirect()->route("miller-admin.marketplace-products");
    }
    

}
