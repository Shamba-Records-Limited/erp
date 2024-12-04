<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Collection;
use App\Cooperative;
use App\Lot;
use App\MillerAuctionCart;
use App\MillerAuctionCartItem;
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
        $product = Product::all();
        $products = $product->toArray();
        //dd($products);
        // Paginate the products (8 per page)
        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($products, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new LengthAwarePaginator($currentItems, count($products), $perPage);
        $paginatedProducts->setPath(route('farmer.marketplace.products'));
        // Pass the paginated products to the view
        return view('pages.farmer.marketplace.products', compact('paginatedProducts'));
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
        // retrieve lot
        /*
        try {
            $lot = Lot::where("cooperative_id", $coop_id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Lot does not exist');
            return redirect()->back();
        }

        // update or create cart
        $cart = null;
        try {
            $cart = MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->first();
            if ($cart == null) {
                throw new \Throwable("cart does not exist");
            }
        } catch (\Throwable $th) {
            try {
                $cart = new MillerAuctionCart();
                $cart->miller_id = $miller_id;
                $cart->cooperative_id = $coop_id;
                $cart->user_id = $user->id;
                $cart->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to initialize cart');
                return redirect()->back();
            }
        }

        try {
            $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $cartItem = new MillerAuctionCartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->lot_number = $lot_number;
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }       
        //update lot available quantity
       // $lot->available_quantity=$lot->available_quantity-$quantity;
       // $lot->save();

        DB::commit();
                           */
        toastr()->success('Item added to cart successfully');
        return redirect()->back();
    }























}
