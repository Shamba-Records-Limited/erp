<?php

namespace App\Http\Controllers\MillerAdmin;

use App\AuctionOrderDelivery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            $miller_id = null;
        }

        $orders = DB::select(DB::raw("
            SELECT ord.*,
                coop.name as cooperative_name
            FROM miller_auction_order ord
            JOIN cooperatives coop ON coop.id = ord.cooperative_id
            JOIN millers ON millers.id = ord.miller_id AND millers.id = :miller_id;
        "), ["miller_id" => $miller_id]);

        return view('pages.miller-admin.orders.index', compact('orders'));
    }

    public function detail($id, Request $request)
    {
        $order = null;
        $orders = DB::select(DB::raw("
            SELECT ord.*,
                coop.name as cooperative_name
            FROM miller_auction_order ord
            JOIN cooperatives coop ON coop.id = ord.cooperative_id
            WHERE ord.id = :id;
        "), ["id" => $id]);

        if (count($orders) > 0) {
            $order = $orders[0];
        }


        $tab = $request->query('tab', 'items');
        $delivery_id_to_view = $request->query('delivery_id_to_view', '');

        $orderItems = [];
        if ($tab == 'items') {
            $orderItems = DB::select(DB::raw("
                SELECT item.*,
                    c.quantity,
                    c.unit_id,
                    p.name as product_name,
                    pc.name as product_category,
                    u.abbreviation as unit_abbr
                FROM miller_auction_order_item item
                JOIN collections c ON item.collection_id = c.id
                JOIN products p ON p.id = c.product_id
                JOIN product_categories pc ON pc.id = p.category_id
                JOIN units u ON u.id = c.unit_id
                WHERE item.order_id = :order_id;
            "), ["order_id" => $id]);
        }

        $orderDeliveries = [];
        if ($tab == 'deliveries') {
            $orderDeliveries = DB::select(DB::raw("
                SELECT delivery.*,
                    (SELECT count(1) FROM auction_order_delivery_item item WHERE item.delivery_id = delivery.id) AS total_items,
                    delivery.published_at,
                    delivery.approved_at
                FROM auction_order_delivery delivery
                WHERE delivery.order_id = :order_id AND
                    delivery.published_at IS NOT NULL
            "), ["order_id" => $id]);
        }

        $delivery_to_view = null;
        $deliveryItems = [];
        if ($delivery_id_to_view) {
            $delivery_to_view = AuctionOrderDelivery::find($delivery_id_to_view);

            $deliveryItems = DB::select(DB::raw("
                SELECT item.*, p.name as product_name, pc.name as product_category, u.abbreviation as unit_abbr
                FROM auction_order_delivery_item item
                JOIN units u ON u.id = item.unit_id
                JOIN auction_order_delivery delivery ON delivery.id = item.delivery_id
                JOIN miller_auction_order_item order_item ON order_item.id = item.order_item_id
                JOIN collections c ON c.id = order_item.collection_id
                JOIN products p ON p.id = c.product_id
                JOIN product_categories pc ON pc.id = p.category_id
                WHERE delivery.id = :delivery_id;
            "), ["delivery_id" => $delivery_id_to_view]);

            dd($delivery_id_to_view);
            dd($deliveryItems);
        }


        return view('pages.miller-admin.orders.detail', compact('order', 'tab', 'orderItems', 'orderDeliveries', 'delivery_to_view', 'deliveryItems'));
    }
}
