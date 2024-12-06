<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Collection;
use App\Cooperative;
use App\Lot;
use App\ProductMillerCart;
use App\ProductAuctionCartItem;
use App\FarmerAuctionOrder;
use App\FarmerAuctionOrderItem;
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


    public function view_checkout_cart($miller_id)
    {
        $user = Auth::user();
        try {
            $user_id = $user->id;
        } catch (\Throwable $th) {
            $user_id = null;
        }

        $farmer = Farmer::where('user_id', $user_id)->first();
        if ($farmer) {
            $farmer_id = $farmer->id;
        } else {
            $farmer_id = null; // Or handle the case where no farmer is found
        }
        // cart
        $cart = ProductMillerCart::where("farmer_id", $farmer_id)
            ->where("user_id", $user->id)
            ->first();
    
        if (!$cart) {
            // Handle case where no cart is found, e.g., return an error or redirect
            return redirect()->back()->with('error', 'Cart not found.');
        }
       
        // total in cart
        $totalInCart = DB::select(DB::raw("
            SELECT sum(item.quantity) AS total
            FROM product_auction_cart_items item
            WHERE item.cart_id = :cart_id
        "), ["cart_id" => $cart->id])[0]->total;

        $totalAmntInCart = DB::select(DB::raw("
        SELECT sum(prd.sale_price*item.quantity) AS total
        FROM product_auction_cart_items item
        JOIN products prd on item.product_id=prd.id
        WHERE item.cart_id = :cart_id
    "), ["cart_id" => $cart->id])[0]->total;
      
        /*
        // aggregate grade distribution
        $aggregateGradeDistribution = DB::select(DB::raw("
            SELECT SUM(d.quantity) AS total,
                pg.name AS grade
            FROM lot_grade_distributions d
            JOIN product_grades pg ON pg.id = d.product_grade_id
            JOIN lots l ON l.lot_number = d.lot_number
            JOIN miller_auction_cart_item item ON item.lot_number = l.lot_number
            WHERE item.cart_id = :cart_id
            GROUP BY d.product_grade_id
            ORDER BY total DESC
        "), ["cart_id" => $cart->id]);
       */
        // cart items
        $cartItems = DB::select(DB::raw("
            SELECT item.*,prd.sale_price,prd.name
            FROM product_auction_cart_items item
            JOIN product_miller_carts cart ON cart.id = item.cart_id AND cart.id = :cart_id
            JOIN products prd on item.product_id=prd.id;
        "), ["cart_id" => $cart->id]);
       
        //dd($cartItems);
        /*
        foreach ($cartItems as $item) {
            $item->distributions = DB::select(DB::raw("
                SELECT sum(distribution.quantity) AS total,
                    (SELECT name FROM product_grades pd WHERE pd.id = distribution.product_grade_id ) AS grade
                FROM lot_grade_distributions distribution
                JOIN lots l ON l.lot_number=distribution.lot_number AND l.lot_number=:lot_number
                GROUP BY distribution.product_grade_id
                ORDER BY total DESC
            "), ["lot_number" => $item->lot_number]);
        }
             */
        // cooperative
       /* $cooperative = null;
        $cooperatives = DB::select(DB::raw("
            SELECT * FROM cooperatives WHERE id = :coop_id;
        "), ["coop_id" => $coop_id]);
        if (count($cooperatives) > 0) {
            $cooperative = $cooperatives[0];
        }
         */
        // default batch number
        $suffix_batch_number = FarmerAuctionOrder::count() + 1;
        $now = Carbon::now();
        $now_str = strtoupper($now->format('Ymd'));
        $default_batch_number = "B$now_str-$suffix_batch_number";
    
        // warehouses
       /* $warehouses = DB::select(DB::raw("
            SELECT * FROM miller_warehouse WHERE miller_id = :miller_id
        "), ["miller_id" => $miller_id]); */
    
        return view('pages.farmer.marketplace.cart-checkout', compact('cartItems', 'totalInCart', 'totalAmntInCart', 'default_batch_number'));
    }


    public function remove_product_from_cart($cart_id, $item_id, Request $request)
    {
        DB::beginTransaction();
        $user = Auth::user();
        try {
            $user_id = $user->id;
        } catch (\Throwable $th) {
            $user_id = null;
        }
        $farmer = Farmer::where('user_id', $user_id)->first();

        $cartItem = ProductAuctionCartItem::where('cart_id', $cart_id)
        ->where('id', $item_id)
        ->latest('created_at')->first();
        try {
           $cartItem->delete();         
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Unable to clear cart: ' . $th->getMessage());
            return redirect()->back();
        }
        DB::commit();
        toastr()->success('Cart updated successfully');
        return redirect()->back();
    }
// creates order
public function checkout_cart(Request $request, $coop_id)
{
    $request->validate([
        "batch_number" => "required|unique:miller_auction_order,batch_number",
        "cart_amount" => "required",
    ]);

    $user = Auth::user();
    try {
        $user_id = $user->id;
    } catch (\Throwable $th) {
        $user_id = null;
    }
    $farmer = Farmer::where('user_id', $user_id)->first();
    if ($farmer) {
        $farmer_id = $farmer->id;
    } else {
        $farmer_id = null; // Or handle the case where no farmer is found
    }

    $cart = ProductMillerCart::where("farmer_id", $farmer_id)
    ->where("user_id", $user->id)
    ->first();

    $cartItemsCount = $cart->items->count();
    if ($cartItemsCount <= 0) {
        toastr()->error('Cart is empty');
        return redirect()->back();
    }
    // start transaction
    DB::beginTransaction();
    // create order
    try {
        $order = new FarmerAuctionOrder();
        $order->batch_number = $request->batch_number;
        $order->farmer_id = $farmer_id;
        $order->miller_id=$farmer_id;
        $order->user_id = $user->id;
        $order->paid_amount=$request->cart_amount;
        $order->save();
    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        DB::rollBack();
        toastr()->error('Unable to initialize cart');
        return redirect()->back();
    }

    // retrieve collections in cart
    $lotsInCart = DB::select(DB::raw("
        SELECT item.product_id, item.quantity,prd.sale_price
        FROM product_auction_cart_items item
        JOIN products prd on item.product_id=prd.id
        WHERE item.cart_id = :cart_id
    "), ["cart_id" => $cart->id]);
    // Initialize an array to collect lot numbers 
    $lotNumbers = [];

    foreach ($lotsInCart as $item) {
        try {
            $product_id = $item->product_id;

            $orderItem = new FarmerAuctionOrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product_id ;
            $orderItem->quantity = $item->quantity;
            $orderItem->selling_price = $item->sale_price;
            $orderItem->save();
        // Add the lot number to the array
        $lotNumbers[] = $product_id;
         //Reduce product qnty by sale no
         $product = Product::where('id', $product_id)->first();
         $product->quantity=$product->quantity-$item->quantity;
         $product->save();

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to create order item');
            return redirect()->back();
        }
    }
    // cart is no longer needed delete it
    $cart->delete();

    DB::commit();
    toastr()->success('Order created successfully');
    // redirect to orders
    //return redirect()->route("millers.orders.show");
    return redirect()->route("farmer.orders.show");

}







}
