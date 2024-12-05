<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Collection;
use App\Cooperative;
use App\Lot;
use App\ProductMillerCart;
use App\ProductAuctionCartItem;
use App\MillerAuctionOrder;
use App\MillerAuctionOrderItem;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Product;
use Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Farmer;

class MarketplaceController extends Controller
{
    
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function products()
    {
        $user = Auth::user();
        try {
            $user_id = $user->id;
        } catch (\Throwable $th) {
            $user_id = null;
        }

        $product = Product::all();
        $products = $product->toArray();

      // Count items in cart
       $items_in_cart_count = DB::select(DB::raw("
         SELECT count(1) AS count
         FROM product_auction_cart_items item
         inner join product_miller_carts cart on item.cart_id=cart.id
         WHERE cart.user_id = :user_id
     "), ["user_id" => $user->id])[0]->count;
     
        // Paginate the products (8 per page)
        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($products, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new LengthAwarePaginator($currentItems, count($products), $perPage);
        $paginatedProducts->setPath(route('farmer.marketplace.products'));
        // Pass the paginated products to the view
        return view('pages.farmer.marketplace.products', compact('paginatedProducts','items_in_cart_count'));
    }

    public function add_product_to_cart(Request $request, $miller_id, $product_id)
    {
        $quantity = $request->query('quantity');
        if (!$quantity || $quantity < 1) {
            return redirect()->back()->with('error', 'Invalid quantity.');
        } 
        $miller_id=$miller_id;
        $product_id=$product_id;
        $quantity=$request->quantity;
        
        DB::beginTransaction();
        $user = Auth::user();
        try {
            $user_id = $user->id;
        } catch (\Throwable $th) {
            $user_id = null;
        }

        $farmer = Farmer::where('user_id', $user_id)->first();
        // retrieve product
    
        try {
            $product = Product::where("id", $product_id)
                ->where("miller_id", $miller_id)
                ->firstOrFail();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Product does not exist');
            return redirect()->back();
        }

        // update or create cart
        $cart = null;
        try {
            $cart = ProductMillerCart::where("miller_id", $miller_id)
                ->where("farmer_id", $farmer->id)
                ->where("user_id", $user->id)
                ->first();
            if ($cart == null) {
                throw new \Throwable("cart does not exist");
            }
        } catch (\Throwable $th) {
            try {
                $cart = new ProductMillerCart();
                $cart->miller_id = $miller_id;
                $cart->farmer_id = $farmer->id;
                $cart->user_id = $user->id;
                $cart->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to initialize cart :'.$th->getMessage());
                return redirect()->back();
            }
        }
           
        try {
            $cartItem = ProductAuctionCartItem::where("cart_id", $cart->id)
                ->where("product_id", $product_id)
                ->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $cartItem = new ProductAuctionCartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = $product_id;
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }       

        DB::commit();
        toastr()->success('Item added to cart successfully');
        return redirect()->route('farmer.marketplace.products');
        //return redirect()->back();
    }


    public function clear_cart()
    {
        DB::beginTransaction();
        $user = Auth::user();
        try {
            $user_id = $user->id;
        } catch (\Throwable $th) {
            $user_id = null;
        }

        $farmer = Farmer::where('user_id', $user_id)->first();
        $cart = ProductMillerCart::where('farmer_id', $farmer->id)->latest('created_at')->first();
        try {
           $cart->items()->delete(); // Delete associated cart items
           $cart->delete();          // Delete the cart itself
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Unable to clear cart: ' . $th->getMessage());
            return redirect()->back();
        }
        toastr()->success('Cart cleared successfully');
        return redirect()->route('farmer.marketplace.products');
        //return redirect()->back();
    }





















}
