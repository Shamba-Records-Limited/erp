<?php

namespace App\Http\Controllers\MillerAdmin;

use App\Collection;
use App\Cooperative;
use App\Http\Controllers\Controller;
use App\MillerAuctionCart;
use App\MillerAuctionCartItem;
use App\MillerAuctionOrder;
use App\MillerAuctionOrderItem;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class MarketAuctionController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    // display cooperatives
    public function index()
    {
        $cooperatives = DB::select(DB::raw("
            SELECT coop.*,
                (SELECT count(1) FROM collections c
                    WHERE c.cooperative_id = coop.id AND
                    (SELECT count(1) FROM miller_auction_order_item item WHERE item.collection_id = c.id) = 0
                ) AS collections_count FROM cooperatives coop
        "));

        return view('pages.miller-admin.market-auction.index', compact('cooperatives'));
    }

    public function view_coop_collections($coop_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
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

        $collections = DB::select(DB::raw("
            SELECT c.*,
                p.name as product_name,
                pc.name as product_category,
                u.abbreviation as unit,
                (SELECT count(1) 
                    FROM miller_auction_cart_item item
                    WHERE item.collection_id = c.id AND
                    item.cart_id = :cart_id
                ) AS in_cart
            FROM collections c 
            JOIN products p ON p.id = c.product_id
            LEFT JOIN product_categories pc ON pc.id = p.category_id

            LEFT JOIN units u ON u.id = c.unit_id

            where c.cooperative_id = :coop_id AND
            (SELECT count(1) FROM miller_auction_order_item item WHERE item.collection_id = c.id) = 0
        "), ["coop_id" => $coop_id, "cart_id" => $cart->id]);

        $collections_count = DB::select(DB::raw("
            SELECT count(1) FROM collections c
            where c.cooperative_id = :coop_id
        "), ["coop_id" => $coop_id]);

        // cooperative
        $cooperative = null;
        $cooperatives = DB::select(DB::raw("
            SELECT * FROM cooperatives WHERE id = :coop_id;
        "), ["coop_id" => $coop_id]);
        if (count($cooperatives) > 0) {
            $cooperative = $cooperatives[0];
        }

        $items_in_cart_count = DB::select(DB::raw("
            SELECT count(1) as count FROM miller_auction_cart_item item WHERE cart_id = :cart_id
        "), ["cart_id" => $cart->id])[0]->count;


        return view('pages.miller-admin.market-auction.coop-collections', compact('cooperative', 'collections', 'collections_count', 'items_in_cart_count'));
    }

    public function add_collection_to_cart($coop_id, $collection_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }


        DB::beginTransaction();
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

        // check item duplication
        $isCartItemDuplicate = MillerAuctionCartItem::where("cart_id", $cart->id)
            ->where("collection_id", $collection_id)
            ->exists();

        if ($isCartItemDuplicate) {
            DB::rollBack();
            toastr()->error('Item duplication detected');
            return redirect()->back();
        }

        // add item to cart
        try {
            $cartItem = new MillerAuctionCartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->collection_id = $collection_id;
            $cartItem->save();

            toastr()->success('Added item to cart');
            DB::commit();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to add item to cart');
            return redirect()->back();
        }

        return redirect()->route("miller-admin.market-auction.coop-collections.show", $coop_id);
    }

    public function remove_collection_from_cart($coop_id, $collection_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $cart = MillerAuctionCart::where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
            ->where("user_id", $user->id)
            ->first();

        // check item exists
        $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
            ->where("collection_id", $collection_id);

        if (is_null($cartItem)) {
            toastr()->error('Item not in cart');
            return redirect()->back();
        }

        $cartItem->delete();


        toastr()->success('Removed item to cart');
        return redirect()->route("miller-admin.market-auction.coop-collections.show", $coop_id);
    }

    public function view_checkout_cart($coop_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        // cart
        $cart = MillerAuctionCart::where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
            ->where("user_id", $user->id)
            ->first();

        $cartItems = DB::select(DB::raw("
            SELECT item.*,
                p.name as product_name,
                pc.name as product_category,
                c.quantity as quantity,
                u.abbreviation as unit
            FROM miller_auction_cart_item item
            JOIN miller_auction_cart cart ON cart.id = item.cart_id AND cart.id = :cart_id
            JOIN collections c ON c.id = item.collection_id
            JOIN products p ON p.id = c.product_id
            LEFT JOIN product_categories pc ON pc.id = p.category_id
            LEFT JOIN units u ON u.id = c.unit_id;
        "), ["cart_id" => $cart->id]);

        // cooperative
        $cooperative = null;
        $cooperatives = DB::select(DB::raw("
            SELECT * FROM cooperatives WHERE id = :coop_id;
        "), ["coop_id" => $coop_id]);
        if (count($cooperatives) > 0) {
            $cooperative = $cooperatives[0];
        }

        // warehouses
        $warehouses = DB::select(DB::raw("
            SELECT * FROM miller_warehouse WHERE miller_id = :miller_id
        "), ["miller_id" => $miller_id]);


        // default batch number
        $default_batch_number = MillerAuctionOrder::count() + 1;

        return view('pages.miller-admin.market-auction.cart-checkout', compact('cooperative', 'cartItems', 'warehouses', 'default_batch_number'));
    }

    public function clear_cart($coop_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        try {
            MillerAuctionCart::where("miller_id", $miller_id)
                ->where("cooperative_id", $coop_id)
                ->where("user_id", $user->id)
                ->delete();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Unable to clear cart');
            return redirect()->back();
        }


        toastr()->success('Cart cleared successfully');
        return redirect()->route("miller-admin.market-auction.coop-collections.show", $coop_id);
    }

    // creates order
    public function checkout_cart(Request $request, $coop_id)
    {
        $request->validate([
            "batch_number" => "required|unique:miller_auction_order,batch_number",
            "miller_warehouse_id" => "required|exists:miller_warehouse,id",
        ]);

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $cart = MillerAuctionCart::where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
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
            $order = new MillerAuctionOrder();
            $order->batch_number = $request->batch_number;
            $order->miller_id = $miller_id;
            $order->miller_warehouse_id = $request->miller_warehouse_id;
            $order->cooperative_id = $coop_id;
            $order->user_id = $user->id;
            $order->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }

        // retrieve collections in cart
        $collectionsInCart = DB::select(DB::raw("
            SELECT item.collection_id FROM miller_auction_cart_item item
            WHERE item.cart_id = :cart_id
        "), ["cart_id" => $cart->id]);

        foreach ($collectionsInCart as $item) {
            try {
                $collection_id = $item->collection_id;

                $orderItem = new MillerAuctionOrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->collection_id = $collection_id;
                $orderItem->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to create order item');
                return redirect()->back();
            }
        }

        // cart is no longer needed delete it
        $cart->delete();

        // commit changes
        DB::commit();
        toastr()->error('Unable to create order item');

        // redirect to orders
       return redirect()->route("miller-admin.orders.show"); 
    }
}
