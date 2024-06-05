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
                (SELECT count(1) FROM lots l
                    WHERE l.cooperative_id = coop.id
                ) AS lots_count FROM cooperatives coop
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

        $lots = DB::select(DB::raw("
            SELECT l.*, item.quantity AS qty
            FROM lots l
            LEFT JOIN miller_auction_cart_item item ON item.lot_number = l.lot_number
            LEFT JOIN miller_auction_cart cart ON cart.id = item.cart_id
            where l.cooperative_id = :coop_id
        "), ["coop_id" => $coop_id]);

        $lots_count = DB::select(DB::raw("
            SELECT count(1) FROM lots l
            where l.cooperative_id = :coop_id
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


        return view('pages.miller-admin.market-auction.coop-lots', compact('cooperative', 'lots', 'lots_count', 'items_in_cart_count'));
    }

    // todo: remove
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

    // todo: replace with remove lot from cart
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
            SELECT item.*
            FROM miller_auction_cart_item item
            JOIN miller_auction_cart cart ON cart.id = item.cart_id AND cart.id = :cart_id;
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

    // todo: implement
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
        $lotsInCart = DB::select(DB::raw("
            SELECT item.lot_number, item.quantity FROM miller_auction_cart_item item
            WHERE item.cart_id = :cart_id
        "), ["cart_id" => $cart->id]);

        foreach ($lotsInCart as $item) {
            try {
                $lot_number = $item->lot_number;

                $orderItem = new MillerAuctionOrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->lot_number = $lot_number;
                $orderItem->quantity = $item->quantity;
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

    public function get_or_create_cart_item($coop_id, $lot_number): MillerAuctionCartItem
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

        try {
            $cartItem = MillerAuctionCartItem::where("cart_id", $cart->id)
                ->where("lot_number", $lot_number)
                ->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $cartItem = new MillerAuctionCartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->lot_number = $lot_number;
            $cartItem->quantity = 0;
            $cartItem->save();
        }

        return $cartItem;
    }

    public function set_quantity_in_cart($coop_id, $lot_number, Request $request)
    {
        $request->validate([
            'in_cart_quantity' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            //code...
            $cartItem = $this->get_or_create_cart_item($coop_id, $lot_number);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to retrieve cart item');
            return redirect()->back();
        }

        try {
            $cartItem->quantity = $request->in_cart_quantity;
            $cartItem->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }

        DB::commit();
        toastr()->success('Cart updated successfully');
        return redirect()->back();
    }

    public function increase_quantity_in_cart($coop_id, $lot_number)
    {
        DB::beginTransaction();
        try {
            //code...
            $cartItem = $this->get_or_create_cart_item($coop_id, $lot_number);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to retrieve cart item');
            return redirect()->back();
        }

        try {
            $cartItem->quantity += 1;   // increasing quantity
            $cartItem->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }

        DB::commit();

        toastr()->success('Cart updated successfully');
        return redirect()->back();
    }

    public function decrease_quantity_in_cart($coop_id, $lot_number, Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $cartItem = $this->get_or_create_cart_item($coop_id, $lot_number);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to retrieve cart item');
            return redirect()->back();
        }

        try {
            $cartItem->quantity -= 1;   // decreasing quantity
            $cartItem->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }

        DB::commit();
        toastr()->success('Cart updated successfully');
        return redirect()->back();
    }
}
