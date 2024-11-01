<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\AuctionOrderDelivery;
use App\AuctionOrderDeliveryItem;
use App\Http\Controllers\Controller;
use App\MillerAuctionOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class OrdersController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $user = Auth::user();
        $coop = $user->cooperative;

        // $orders = DB::select(DB::raw("
        //     SELECT ord.*,
        //         millers.name as miller_name
        //     FROM miller_auction_order ord
        //     JOIN millers ON millers.id = ord.miller_id
        //     WHERE ord.cooperative_id = :coop_id
        // "), ["coop_id" => $coop->id]);

        $orders = MillerAuctionOrder::where("cooperative_id", $coop->id)->get();

        return view("pages.cooperative-admin.orders.index", compact('orders'));
    }

    public function detail($id, Request $request)
    {

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



        $user_id = Auth::id();

        $order = null;
        $orders = DB::select(DB::raw("
            SELECT ord.*,
                m.name as miller_name
            FROM miller_auction_order ord
            JOIN millers m ON m.id = ord.miller_id
            WHERE ord.id = :id;
        "), ["id" => $id]);

        if (count($orders) > 0) {
            $order = $orders[0];
        }


        $tab = $request->query('tab', 'items');
        $action = $request->query('action', '');

        $orderItems = [];
        if ($tab == 'items' || ($tab == 'deliveries' && $action == 'add_delivery')) {
            $orderItems = DB::select(DB::raw("
                SELECT item.*, (item.quantity - (SELECT SUM(quantity) FROM auction_order_delivery_item delivery_item WHERE delivery_item.order_item_id = item.id)) as undelivered
                FROM miller_auction_order_item item
                WHERE item.order_id = :order_id
                ORDER BY undelivered DESC;
            "), ["order_id" => $id]);
        }

        $orderDeliveries = [];
        if ($tab == 'deliveries') {
            $orderDeliveries = DB::select(DB::raw("
                SELECT delivery.*,
                    (SELECT count(1) FROM auction_order_delivery_item item WHERE item.delivery_id = delivery.id) AS total_items,
                    delivery.published_at
                FROM auction_order_delivery delivery
                WHERE delivery.order_id = :order_id AND (delivery.published_at IS NOT NULL OR delivery.user_id = :user_id)
            "), ["order_id" => $id, "user_id" => $user_id]);
        }

        $units = [];
        $draft_delivery = null;
        $draft_delivery_items = [];
        if ($action == 'add_delivery') {
            $units = config('enums.units');

            $draft_delivery_items = DB::select(DB::raw("
                SELECT item.*, order_item.lot_number
                FROM auction_order_delivery_item item
                JOIN auction_order_delivery delivery ON delivery.id = item.delivery_id
                JOIN miller_auction_order_item order_item ON order_item.id = item.order_item_id
                WHERE delivery.user_id = :user_id AND
                    delivery.order_id = :order_id AND
                    delivery.published_at IS NULL
            "), ["user_id" => $user_id, "order_id" => $id]);

            $draft_deliveries = DB::select(DB::raw("
                SELECT id FROM auction_order_delivery WHERE
                    published_at IS NULL AND
                    order_id = :order_id AND
                    user_id = :user_id
            "), ["user_id" => $user_id, "order_id" => $id]);
            if (count($draft_deliveries) > 0) {
                $draft_delivery = $draft_deliveries[0];
            }
        }
        // Calculate delivered and pending counts
            $deliveredCount = DB::table('auction_order_delivery')
                ->where('order_id', $id)
                ->whereNotNull('published_at')
                ->count();

            $pendingCount = DB::table('auction_order_delivery')
                ->where('order_id', $id)
                ->whereNull('published_at')
                ->count();


        return view('pages.cooperative-admin.orders.detail', compact('order', 'tab', 'action', 'orderItems', 'orderDeliveries', 'units', 'draft_delivery', 'draft_delivery_items', 'totalInOrder', 'aggregateGradeDistribution','deliveredCount','pendingCount' ));
    }

    public function add_delivery_item(Request $request, $order_id)
    {
        try {
            $order = MillerAuctionOrder::find($order_id);
        } catch (\Throwable $th) {
            $order = null;
            if ($order == null) {
                toastr()->error('Order not found');
                return redirect()->back();
            }
        }

        $request->validate([
            "order_item_id" => "required|exists:miller_auction_order_item,id",
            "quantity" => "required|gt:0|lte:$order->undeliveredQuantity",
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        // retrieve or create user delivery in draft
        try {
            $delivery = AuctionOrderDelivery::where("order_id", $order_id)
                ->where("user_id", $user->id)
                ->where("published_at", null)
                ->first();

            if ($delivery == null) {
                throw new \Throwable("delivery not found");
            }
        } catch (\Throwable $th) {
            try {
                // generate delivery number
                $now = Carbon::now();
                $now_str = strtoupper($now->format('Ymd'));
                $date_str = $now->format('Y-m-d') . " 00:00:00";
            
                $dateAfter_str = $now->format('Y-m-d') . " 23:59:59";
                
                $delivery_count = AuctionOrderDelivery::where('created_at', '>=', $date_str)
                    ->where('created_at', '<', $dateAfter_str)
                    ->count();

                $delivery_ind = $delivery_count + 1;

                $deliveryNumber = "DLV" . $now_str . str_pad($delivery_ind, 3, '0', STR_PAD_LEFT);

                $delivery = new AuctionOrderDelivery();
                $delivery->delivery_number = $deliveryNumber;
                $delivery->order_id = $order_id;
                $delivery->user_id = $user->id;
                $delivery->save();
            } catch (\Throwable $th) {
                Log::error($th->getMessage());
                DB::rollBack();
                toastr()->error('Unable to initialize delivery');
                return redirect()->back();
            }
        }

        // save order delivery items
        try {
            $item = new AuctionOrderDeliveryItem();
            $item->delivery_id = $delivery->id;
            $item->order_item_id = $request->order_item_id;
            $item->quantity = $request->quantity;
            $item->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to save item');
            return redirect()->back();
        }

        DB::commit();
        toastr()->success('Delivery item saved');
        return redirect()->back();
    }

    public function delete_delivery_item($id)
    {
        try {
            $deliveryItem = AuctionOrderDeliveryItem::find($id);
            $deliveryItem->delete();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Unable to delete item');
            return redirect()->back();
        }


        toastr()->success('Delivery item deleted');
        return redirect()->back();
    }

    public function publish_delivery_draft()
    {
        $user_id = Auth::id();
        $deliveryDraft = AuctionOrderDelivery::where("published_at", null)
            ->where("user_id", $user_id)
            ->first();

        if ($deliveryDraft == null) {
            toastr()->error("Sorry but you don't have permissions to publish this delivery draft");
            return redirect()->back();
        }

        try {
            $deliveryDraft->published_at = Carbon::now();
            $deliveryDraft->save();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Unable to publish draft delivery');
            return redirect()->back();
        }

        toastr()->success('Draft delivery item published');
        return redirect()->back();
    }

    public function discard_delivery_draft($id)
    {
        $user_id = Auth::id();
        $deliveryDraft = AuctionOrderDelivery::where("published_at", null)
            ->where("user_id", $user_id)
            ->first();

        if ($deliveryDraft == null) {
            toastr()->error("Sorry but you don't have permissions to delete this delivery draft");
            return redirect()->back();
        }

        try {
            $deliveryDraft->delete();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            toastr()->error('Unable to delete draft delivery');
            return redirect()->back();
        }

        toastr()->success('Draft delivery item deleted');
        return redirect()->back();
    }
}
