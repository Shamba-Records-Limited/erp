<?php

namespace App\Http\Controllers\MillerAdmin;

use App\AuctionOrderDelivery;
use App\Cooperative;
use App\Http\Controllers\Controller;
use App\MillerAuctionOrder;
use App\MillerAuctionOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

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

    public function view_create_order($coop_id)
    {
        $cooperative = Cooperative::find($coop_id);

        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            throw $th;
        }

        $user_id = Auth::id();

        // get draft order
        $draftOrder = MillerAuctionOrder::where('published_at', null)
            ->where("user_id", $user_id)
            ->where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
            ->first();

        if (is_null($draftOrder)) {
            $now = Carbon::now();
            $today = $now->format('Y-m-d');
            $batchPrefix = $now->format('Ymd');

            $ordersCreatedToday = DB::select(DB::raw("
                SELECT count(1) AS count FROM miller_auction_order WHERE created_at > :today
            "), ["today" => $today])[0]->count;

            $batchInd = $ordersCreatedToday + 1;

            $batch_number = "BAT" . $batchPrefix . $batchInd;

            // default batch number
            $draftOrder = new MillerAuctionOrder();
            $draftOrder->batch_number = $batch_number;
            $draftOrder->miller_id = $miller_id;
            $draftOrder->cooperative_id = $coop_id;
            $draftOrder->user_id = $user_id;
            $draftOrder->save();
        }


        return view('pages.miller-admin.orders.create.index', compact('draftOrder', 'cooperative'));
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
            $delivery_to_view = AuctionOrderDelivery::find($delivery_id_to_view);

            $deliveryItems = DB::select(DB::raw("
                SELECT item.*, order_item.lot_number
                FROM auction_order_delivery_item item
                JOIN miller_auction_order_item order_item ON order_item.id = item.order_item_id
                WHERE item.delivery_id = :delivery_id;
            "), ["delivery_id" => $delivery_id_to_view]);
        }


        return view('pages.miller-admin.orders.detail', compact('order', 'tab', 'orderItems', 'orderDeliveries', 'delivery_to_view', 'deliveryItems'));
    }

    public function return_order_row(Request $request, $item_id)
    {
        return view('pages.miller-admin.orders.create.order-row');
    }

    public function create_order_row(Request $request, $coop_id)
    {
        $user = Auth::user();
        try {
            $miller_id = $user->miller_admin->miller_id;
        } catch (\Throwable $th) {
            throw $th;
        }

        $user_id = Auth::id();
        // get draft order
        $draftOrder = MillerAuctionOrder::where('published_at', null)
            ->where("user_id", $user_id)
            ->where("miller_id", $miller_id)
            ->where("cooperative_id", $coop_id)
            ->first();

        $item = new MillerAuctionOrderItem();
        $item->order_id = $draftOrder->id;
        $item->save();

        return redirect()->route("miller-admin.market-auction.coop-collections.render-order-row", $item->id);
    }

    public function approve_delivery($delivery_id)
    {
        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $delivery = AuctionOrderDelivery::find($delivery_id);
            $delivery->approved_at = Carbon::now();
            $delivery->approved_by = $user_id;
            $delivery->save();

            DB::commit();
            toastr()->success('Delivery approved');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollBack();
            toastr()->error('Unable to initialize cart');
            return redirect()->back();
        }
    }
}