<?php

namespace App\Http\Controllers\Farmer;

use App\ProductMillerCart;
use App\ProductAuctionCartItem;
use App\FarmerAuctionOrder;
use App\FarmerAuctionOrderItem;
use App\Product;
use App\Farmer;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        try {
            $user_id = $user->id;
        } catch (\Throwable $th) {
            $user_id = null;
        }

        $farmer = Farmer::where('user_id', $user_id)->first();
        $orders = FarmerAuctionOrder::where('farmer_id', $farmer->id)
               // ->join('auction_order_delivery', 'miller_auction_order.id', '=', 'auction_order_delivery.order_id')
                //->with('cooperative') // Assuming cooperative is a relationship
                ->select('farmer_auction_orders.*') // Select specific columns
                ->get();
        return view('pages.farmer.marketplace.orders.index', compact('orders'));
    }

    public function detail($id, Request $request)
    {
        $detail=0;
        $orderItems = DB::table('farmer_auction_order_items as item')
                ->join('products as prd', 'item.product_id', '=', 'prd.id')
                ->where('item.order_id', $id)
                ->select('item.*', 'prd.name as product_name','prd.sale_price') // Select all columns from both tables
                ->get();

        $order = DB::table('farmer_auction_orders as item')
                ->where('item.id', $id)
                ->first(); // Use `first()` to fetch a single record        
                    /*
        // total in order
        $totalInOrder = DB::select(DB::raw("
            SELECT sum(item.quantity) AS total
            FROM miller_auction_order_item item
            WHERE item.order_id = :order_id
        "), ["order_id" => $id])[0]->total;

        // aggregate grade distribution
        $aggregateGradeDistribution = DB::select(DB::raw("
            SELECT SUM(d.quantity) AS total,
                pg.name AS grade
            FROM lot_grade_distributions d
            JOIN product_grades pg ON pg.id = d.product_grade_id
            JOIN lots l ON l.lot_number = d.lot_number
            JOIN miller_auction_order_item item ON item.lot_number = l.lot_number
            WHERE item.order_id = :order_id
            GROUP BY d.product_grade_id
            ORDER BY total DESC
        "), ["order_id" => $id,]);

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
                SELECT item.*
                    FROM miller_auction_order_item item
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
           // $delivery_to_view = AuctionOrderDelivery::find($delivery_id_to_view);
            
            $delivery_to_view = AuctionOrderDelivery::join('users', 'auction_order_delivery.user_id', '=', 'users.id')
                   ->where('auction_order_delivery.id', $delivery_id_to_view)
                   ->select('auction_order_delivery.*', 'users.first_name as first_name', 'users.other_names as other_names') // Select fields
                  ->first();


            $deliveryItems = DB::select(DB::raw("
                SELECT item.*, order_item.lot_number
                FROM auction_order_delivery_item item
                JOIN miller_auction_order_item order_item ON order_item.id = item.order_item_id
                WHERE item.delivery_id = :delivery_id;
            "), ["delivery_id" => $delivery_id_to_view]);
        }
        return view('pages.miller-admin.orders.detail', compact('order', 'tab', 'orderItems', 'orderDeliveries', 'delivery_to_view', 'deliveryItems', 'totalInOrder', 'aggregateGradeDistribution'));
     */
      //dd($orderItems,$order);
     return view('pages.farmer.marketplace.orders.detail', compact('orderItems', 'order'));
        
    }
     
}
